<?php

$boldthemes_options = get_option( BoldThemesFramework::$pfx . '_theme_options' );
if ( is_product() && isset( $boldthemes_options['shop_settings_page_slug'] ) && $boldthemes_options['shop_settings_page_slug'] != '' ) {
	BoldThemesFramework::$page_for_header_id = boldthemes_get_id_by_slug( $boldthemes_options['shop_settings_page_slug'] );
} else if ( ( is_shop() || is_product_category() || is_product_taxonomy() ) && get_option( 'woocommerce_shop_page_id' ) ) {
	BoldThemesFramework::$page_for_header_id = get_option( 'woocommerce_shop_page_id' );
}

get_header();

echo '<article class="btPostSingleItemStandard btWooCommerce gutter">';
	echo '<div class="port">';
		echo '<div class="btPostContentHolder">';
				woocommerce_content();
		echo '</div>';
	echo '</div>';
echo '</article>';

if ( is_product() && ( comments_open() || get_comments_number() ) ) {
	get_template_part( 'views/comments' );
}

get_footer(); 