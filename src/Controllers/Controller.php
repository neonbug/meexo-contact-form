<?php namespace Neonbug\ContactForm\Controllers;

use App;
use Mail;
use Cache;
use Request;

class Controller extends \App\Http\Controllers\Controller {
	
	const PACKAGE_NAME  = 'contact_form';
	const PREFIX        = 'contact_form';
	const CONFIG_PREFIX = 'neonbug.contact_form';
	
	private $model;
	
	public function __construct()
	{
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	public function index()
	{
		$get_items = function() {
			$repo = App::make('\Neonbug\ContactForm\Repositories\ContactFormRepository');
			$items = $repo->getLatest();
			
			$language = App::make('Language');
			App::make('ResourceRepository')->inflateObjectsWithValues($items, $language->id_language);
			
			return $items;
		};
		
		$items = (!App::environment('production') ? $get_items() : 
			Cache::rememberForever(static::PACKAGE_NAME . '::items', $get_items));
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'index', [ 'items' => $items ]);
	}
	
	public function item($id)
	{
		$get_item = function() use ($id) {
			return $this->getItem($id);
		};
		
		$item = (!App::environment('production') ? $get_item() : 
			Cache::rememberForever(static::PACKAGE_NAME . '::item::' . $id, $get_item));
		if ($item == null)
		{
			abort(404);
		}
		
		$contact_form_render_helper = App::make('\Neonbug\ContactForm\Helpers\Render');
		
		return App::make('\Neonbug\Common\Helpers\CommonHelper')
			->loadView(static::PACKAGE_NAME, 'item', [ 'item' => $item, 
				'contact_form' => $contact_form_render_helper->partial($item->id_contact_form) ]);
	}
	
	protected function getItem($id)
	{
		$model = $this->model;
		$item = $model::find($id);
		if ($item != null)
		{
			$language = App::make('Language');
			App::make('ResourceRepository')->inflateObjectWithValues($item, $language->id_language);
		}
		
		return $item;
	}
	
	public function submitPost($id)
	{
		$item = $this->getItem($id);
		if ($item == null)
		{
			abort(404);
		}
		
		$config_key = $item->config;
		
		$form_config      = config(static::CONFIG_PREFIX . '.frontend.' . $config_key);
		$recaptcha_config = config(static::CONFIG_PREFIX . '.recaptcha');
		
		/* == VERIFY RECAPTCHA == */
		if ($form_config['show_recaptcha'])
		{
			$recaptcha_response = Request::input('g-recaptcha-response', '');
			if ($recaptcha_response == '')
			{
				return [ 'success' => false, 'error_code' => 1, 'error_message' => 'Missing recaptcha response' ];
			}
			
			$recaptcha = new \ReCaptcha\ReCaptcha($recaptcha_config['secret_key']);
			$resp = $recaptcha->verify($recaptcha_response, $_SERVER['REMOTE_ADDR']);
			if (!$resp->isSuccess())
			{
				$errors = $resp->getErrorCodes();
				return [ 'success' => false, 'error_code' => 2, 'error_message' => json_encode($errors) ];
			}
		}
		
		/* == GATHER DATA == */
		$data = [];
		foreach ($form_config['fields'] as $form_field)
		{
			$required = (array_key_exists('required', $form_field) ? $form_field['required'] : true);
			$val = Request::input($form_field['name'], '');
			if ($val == '' && $required)
			{
				return [ 'success' => false, 'error_code' => 3, 'error_message' => 'Missing required field' ];
			}
			
			$data[$form_field['name']] = $val;
		}
		
		/* == SEND MAIL == */
		$admin_locale = config('app.admin_default_locale');
		
		$title   = trans(static::PACKAGE_NAME . '::frontend.mail.title',   [], 'messages', $admin_locale);
		$subject = trans(static::PACKAGE_NAME . '::frontend.mail.subject', [], 'messages', $admin_locale);
		
		$view_name = App::make('\Neonbug\Common\Helpers\CommonHelper')->resolveViewName(static::PACKAGE_NAME, 'mail');
		Mail::send($view_name, [ 'title' => $title, 'data' => $data ], function($message) use ($item, $subject) {
			$message->to($item->getRecepients());
			$message->subject($subject);
		});
		
		if (sizeof(Mail::failures()) > 0)
		{
			return [ 'success' => false, 'error_code' => 4, 'error_message' => json_encode(Mail::failures()) ];
		}
		
		return [ 'success' => true ];
	}
	
}
