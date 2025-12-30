jQuery(document).ready(function($) {
	// Update price display on variation change
	$('form.variations_form').on('show_variation', function(event, variation) {
		$('.variation-price-display').html('<span class="price">' + variation.price_html + '</span>');
	});

	var titleText = 'Products with promotions';
	var currentLang = $('html').attr('lang');
	if (currentLang && currentLang.indexOf('pt') === 0) {
		titleText = 'Produtos em promoção';
	}
	$('.woof_checkbox_sales_container').prepend('<h4>' + titleText + '</h4>');});
