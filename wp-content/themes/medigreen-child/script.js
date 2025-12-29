jQuery(document).ready(function($) {
	// Update price display on variation change
	$('form.variations_form').on('show_variation', function(event, variation) {
		$('.variation-price-display').html('<span class="price">' + variation.price_html + '</span>');
	});
});
