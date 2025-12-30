jQuery(document).ready(function($) {
	// Update price display on variation change
	$('form.variations_form').on('show_variation', function(event, variation) {
		$('.variation-price-display').html('<span class="price">' + variation.price_html + '</span>');
	});

	// Mobile filters
	$('.btSidebar-toggle').on('click', function() {
		$('body').addClass('mobile-filters-open');
	});

	$('.btSidebar-close, .btSidebar-overlay').on('click', function() {
		$('body').removeClass('mobile-filters-open');
	});
});
