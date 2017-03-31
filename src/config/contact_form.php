<?php return [
	
	'model' => '\Neonbug\ContactForm\Models\ContactForm', 
	'supports_preview' => false, 
	
	'list' => [
		'fields' => [
			'id_contact_form' => [
				'type' => 'text'
			], 
			'title' => [
				'type' => 'text'
			], 
			'recepients' => [
				'type' => 'text'
			], 
			'slug' => [
				'type' => 'text', 
				'important' => false
			], 
			'updated_at' => [
				'type' => 'date', 
				'important' => false
			], 
		]
	], 
	
	'add' => [
		'language_dependent_fields' => [
			[
				'name' => 'title', 
				'type' => 'single_line_text', 
				'value' => ''
			], 
			[
				'name' => 'slug', 
				'type' => 'slug', 
				'value' => '', 
				'generate_from' => 'title'
			], 
			[
				'name' => 'description', 
				'type' => 'rich_text', 
				'value' => ''
			], 
			[
				'name' => 'thank_you_message', 
				'type' => 'rich_text', 
				'value' => ''
			], 
		], 
		'language_independent_fields' => [
			[
				'name' => 'recepients', 
				'type' => 'single_line_text', 
				'value' => '', 
				'required' => true, 
				'note' => 'contact_form::admin.add.recepients.note'
			], 
			[
				'name' => 'config', 
				'type' => 'dropdown', 
				'repository' => '\Neonbug\ContactForm\Repositories\ContactFormRepository', 
				'method' => 'structuredItemsForConfigDropdown', 
				'required' => true
			], 
		]
	], 
	
	'edit' => [
		'language_dependent_fields' => [
			[
				'name' => 'title', 
				'type' => 'single_line_text', 
				'value' => ''
			], 
			[
				'name' => 'slug', 
				'type' => 'slug', 
				'value' => '', 
				'generate_from' => 'title'
			], 
			[
				'name' => 'description', 
				'type' => 'rich_text', 
				'value' => ''
			], 
			[
				'name' => 'thank_you_message', 
				'type' => 'rich_text', 
				'value' => ''
			], 
		], 
		'language_independent_fields' => [
			[
				'name' => 'recepients', 
				'type' => 'single_line_text', 
				'value' => '', 
				'required' => true, 
				'note' => 'contact_form::admin.add.recepients.note'
			], 
			[
				'name' => 'config', 
				'type' => 'dropdown', 
				'repository' => '\Neonbug\ContactForm\Repositories\ContactFormRepository', 
				'method' => 'structuredItemsForConfigDropdown', 
				'required' => true
			], 
		]
	], 
	
	'frontend' => [
		'default' => [
			'fields' => [
				[
					'name' => 'email', 
					'type' => 'single_line_text', 
					'title' => 'contact_form::frontend.default.email', 
					'required' => true
				], 
				[
					'name' => 'name', 
					'type' => 'single_line_text', 
					'title' => 'contact_form::frontend.default.name', 
					'required' => true
				], 
				[
					'name' => 'message', 
					'type' => 'multi_line_text', 
					'title' => 'contact_form::frontend.default.message', 
					'required' => true
				], 
			], 
			'submit_title' => 'contact_form::frontend.default.submit', 
			'show_recaptcha' => true, 
		], 
	], 
	
	'recaptcha' => [ //https://www.google.com/recaptcha/admin
		'site_key' => '', 
		'secret_key' => ''
	], 
	
];
