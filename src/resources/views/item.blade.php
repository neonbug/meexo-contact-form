<script src="https://code.jquery.com/jquery-1.12.1.min.js"></script>

<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="{{ cached_asset('vendor/contact_form/js/partial.js') }}"></script>
<script type="text/javascript">
$(function() {
	ContactForm.init({
		base_url: {!! json_encode(route('contact_form::submit', [ ':id:' ])) !!}, 
		csrf_token: {!! json_encode(csrf_token()) !!}, 
		success_event_handler: function(id_contact_form) {
			alert($('.contact-form[data-id-contact-form="' + id_contact_form + '"] .success-message').html());
		}, 
		error_event_handler: function(id_contact_form) {
			alert('Error');
		}, 
		before_ajax_event_handler: function(id_contact_form) { }
	});
});
</script>

<style type="text/css">
.contact-form
{
}

	.contact-form label
	{
		float: left;
	}
	.contact-form input
	{
		display: block;
	}
</style>

<h1>{{ $item->title }}</h1>

<div>
	{!! $contact_form !!}
</div>
