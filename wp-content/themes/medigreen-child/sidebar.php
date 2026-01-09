<?php
	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		if ( is_active_sidebar( 'bt_shop_sidebar' ) ) {
			if ( wp_is_mobile() ) {
				?><button class="btSidebar-toggle" type="button">Filters</button>
				<div class="btSidebar-overlay"></div><?php
			}
			?><aside class="btSidebar<?= wp_is_mobile() ? ' btSidebar-mobile' : '' ?>">
				<?php if ( wp_is_mobile() ) : ?>
				<div class="btSidebar-header">
					<span class="btSidebar-title">Filters</span>
					<button class="btSidebar-close" type="button"></button>
				</div>
				<?php endif; ?>
				<?php dynamic_sidebar( 'bt_shop_sidebar' );
			?></aside><?php
		}
	} else {
		if ( is_active_sidebar( 'primary_widget_area' ) ) {
			?><aside class="btSidebar"><?php
				dynamic_sidebar( 'primary_widget_area' );
			?></aside><?php
		}
	}
