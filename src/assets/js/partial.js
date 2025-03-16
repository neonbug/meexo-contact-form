var ContactForm = {
	base_url: '', 
	success_event_handler: null, 
	error_event_handler: null, 
	before_ajax_event_handler: null, 
	csrf_token: '', 
	method: 'POST', 
	
	init: function(settings) {
		var self = ContactForm;
		
		self.base_url                  = settings.base_url;
		self.success_event_handler     = settings.success_event_handler;
		self.error_event_handler       = settings.error_event_handler;
		self.before_ajax_event_handler = settings.before_ajax_event_handler;
		self.csrf_token                = settings.csrf_token;
		
		if (settings.method)
		{
			self.method = settings.method;
		}
		
		var base_url   = self.base_url;
		var csrf_token = self.csrf_token;
		var method     = self.method;
		
		$('.contact-form').each(function(idx, item) {
			var id_contact_form = $(item).data('id-contact-form');
			$('form', item).submit(function(e) {
				e.preventDefault();
				
				var form_data = $(this).serializeArray();
				var post_data = {};
				
				for (var i=0; i<form_data.length; i++)
				{
					post_data[form_data[i].name] = form_data[i].value;
				}
				
				if (self.before_ajax_event_handler != null)
				{
					self.before_ajax_event_handler(id_contact_form);
				}
				
				$.ajax({
					method: method, 
					url: base_url.replace(':id:', id_contact_form), 
					data: post_data, 
					headers: (csrf_token == null ? {} : { 'X-CSRF-TOKEN': csrf_token })
				}).always(function(data) {
					if (data.success === true)
					{
						if (self.success_event_handler != null)
						{
							self.success_event_handler(id_contact_form);
						}
					}
					else
					{
						if (self.error_event_handler != null)
						{
							self.error_event_handler(id_contact_form, data);
						}
					}
					
					if ($('.g-recaptcha', item).length > 0)
					{
						grecaptcha.reset();
					}
				});
			});
		});
	}
};
