<?php
/**
 * The template for displaying product content within loops
 *
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	do_action( 'woocommerce_before_shop_loop_item' );
	do_action( 'woocommerce_before_shop_loop_item_title' );
	do_action( 'woocommerce_shop_loop_item_title' );
	
	// Custom price display
	if ( $product->is_type( 'variable' ) ) {
		// Variable products - just "From £X" without sale price
		$prices = $product->get_variation_prices( true );
		if ( ! empty( $prices['price'] ) ) {
			$min_price = current( $prices['price'] );
			$max_price = end( $prices['price'] );
			
			if ( $min_price !== $max_price ) {
				echo '<span class="price"><span class="price-from">From </span>' . wc_price( $min_price, array( 'decimals' => 0 ) ) . '</span>';
			} else {
				echo '<span class="price">' . wc_price( $min_price, array( 'decimals' => 0 ) ) . '</span>';
			}
		}
	} else {
		// Simple product - show sale price
		if ( $product->is_on_sale() ) {
			$reg_price = $product->get_regular_price();
			$sale_price = $product->get_sale_price();
			echo '<span class="price"><del>' . wc_price( $reg_price, array( 'decimals' => 0 ) ) . '</del> <ins>' . wc_price( $sale_price, array( 'decimals' => 0 ) ) . '</ins></span>';
		} else {
			$price = $product->get_price();
			if ( $price ) {
				echo '<span class="price">' . wc_price( $price, array( 'decimals' => 0 ) ) . '</span>';
			}
		}
	}
	
	do_action( 'woocommerce_after_shop_loop_item_title' );
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
