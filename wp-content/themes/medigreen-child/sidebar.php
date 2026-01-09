<?php
if (function_exists('is_woocommerce') && is_woocommerce()) {
	global $wp_registered_sidebars;
	$sidebar_registered = isset($wp_registered_sidebars['bt_shop_sidebar']);
	$sidebar_active = is_active_sidebar('bt_shop_sidebar');
	
	// Show sidebar if active, or on mobile if registered (for button)
	if ($sidebar_active || ($sidebar_registered && wp_is_mobile())) {
		if (wp_is_mobile()) {
			?>
			<button class="btSidebar-toggle" type="button">Filters</button>
			<div class="btSidebar-overlay"></div><?php
		}
		?>
		<aside class="btSidebar<?= wp_is_mobile() ? ' btSidebar-mobile' : '' ?>">
		<?php if (wp_is_mobile()) : ?>
			<div class="btSidebar-header">
				<span class="btSidebar-title">Filters</span>
				<button class="btSidebar-close" type="button"></button>
			</div>
		<?php endif; ?>
		<?php if ($sidebar_active) {
			dynamic_sidebar('bt_shop_sidebar');
		}
		?></aside><?php
	}
} else {
	if (is_active_sidebar('primary_widget_area')) {
		?>
		<aside class="btSidebar"><?php
		dynamic_sidebar('primary_widget_area');
		?></aside><?php
	}
}
