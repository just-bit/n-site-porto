<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ajax
add_action( 'wp_ajax_bt_cc_wc_set_price_ajax', 'bt_cc_wc_set_price_ajax' );
add_action( 'wp_ajax_nopriv_bt_cc_wc_set_price_ajax', 'bt_cc_wc_set_price_ajax' );
function bt_cc_wc_set_price_ajax() {
	
	check_ajax_referer( 'bt_cc_nonce', 'bt_cc_nonce' );
	
	$product_id = intval( $_POST['product_id'] );
	$price = floatval( $_POST['price'] );
	$quote = wp_kses_post( $_POST['quote'] );
	$quote_text = sanitize_text_field( $_POST['quote_text'] );
	
	WC()->session->set( "bt_cc_quote_$product_id", $quote );
	WC()->session->set( "bt_cc_quote_text_$product_id", $quote_text );
	WC()->session->set( "bt_cc_custom_price_$product_id", $price );

	wp_die();
}

// Add data to cart
add_filter( 'woocommerce_add_cart_item_data', 'bt_cc_wc_add_custom_price_data_to_cart_item', 10, 2 );
function bt_cc_wc_add_custom_price_data_to_cart_item( $cart_item_data, $product_id ) {
	
	// $cart_item_data['bt_cc_uid'] = md5( microtime().rand() ); // Ensure cart item uniqueness
	$custom_price = WC()->session->get( "bt_cc_custom_price_$product_id" );
	$quote = WC()->session->get( "bt_cc_quote_$product_id" );
	$quote_text = WC()->session->get( "bt_cc_quote_text_$product_id" );
	
	// WC()->session->__unset( "bt_cc_custom_price_$product_id" );
	// WC()->session->__unset( "bt_cc_quote_$product_id" );
	// WC()->session->__unset( "bt_cc_quote_text_$product_id" );

	if ( $custom_price && $quote && $quote_text ) {
		$cart_item_data['bt_cc_price'] = $custom_price;
		$cart_item_data['bt_cc_quote'] = $quote;
		$cart_item_data['bt_cc_quote_text'] = $quote_text;
	}
	
    return $cart_item_data;
}

// Display custom data in the cart
add_filter( 'woocommerce_get_item_data', 'bt_cc_wc_display_custom_data_in_cart', 10, 2 );
function bt_cc_wc_display_custom_data_in_cart( $item_data, $cart_item ) {
	if ( array_key_exists( 'bt_cc_quote', $cart_item ) ) {
		$item_data[] = array(
			'name'  => $cart_item['bt_cc_quote_text'],
			'value' => wp_kses_post( urldecode( $cart_item['bt_cc_quote'] ) )
		);
	}
	return $item_data;
}

// Store custom data in order
add_action( 'woocommerce_checkout_create_order_line_item', 'bt_cc_wc_add_custom_data_to_order', 10, 4 );
function bt_cc_wc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
	if ( array_key_exists( 'bt_cc_quote', $values ) ) {
		$item->add_meta_data( $values['bt_cc_quote_text'], $values['bt_cc_quote'], true );
	}
}

// Set item price
add_action( 'woocommerce_before_calculate_totals', 'bt_cc_wc_apply_custom_price', 99 );
function bt_cc_wc_apply_custom_price( $cart ) {
	
	if ( is_admin() ) return;

	foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( isset( $cart_item['bt_cc_price'] ) ) {
			$cart_item['data']->set_price( $cart_item['bt_cc_price'] );
		}
	}
}

// Set item price in widget
add_action( 'woocommerce_cart_item_price', 'bt_cc_wc_cart_item_price', 10, 3 );
function bt_cc_wc_cart_item_price( $price, $cart_item, $cart_item_key ) {
	if ( isset( $cart_item['bt_cc_price'] ) ) {
		$price = wc_price( $cart_item['bt_cc_price'] );
	}
	return $price;
}

// Show calculator on single product page
//add_action( 'woocommerce_after_single_product_summary', 'bt_cc_wc_add_cc', 9 );
add_action( 'woocommerce_before_add_to_cart_form', 'bt_cc_wc_add_cc', 11 );
function bt_cc_wc_add_cc() {
	global $product;

	$bt_cc = get_post_meta( $product->get_id(), 'bt_cc', true );

	if ( $bt_cc ) {
		echo do_shortcode( '[bt_cc id="' . $bt_cc . '"]' );
	}
}

// Move add-to-cart button
//add_filter( 'woocommerce_post_class', 'bt_cc_wc_alt_layout' );
function bt_cc_wc_alt_layout( $classes ) {
	global $product;
	$bt_cc_alt_layout = get_post_meta( $product->get_id(), 'bt_cc_alt_layout', true );
	if ( $bt_cc_alt_layout && $bt_cc_alt_layout === '1' ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_price', 9 );
		add_action( 'woocommerce_after_single_product_summary', 'woocommerce_template_single_add_to_cart', 9 );
		$classes[] = 'bt_cc_wc_alt_layout_1';
		return $classes;
	}
	return $classes;
}

// Add data-bt-cc-product-id to price element
add_filter( 'woocommerce_get_price_html', function( $price ) {
	if ( is_admin() ) return;
	global $product;
	if ( $product && get_post_meta( $product->get_id(), 'bt_cc', true ) ) {
		return preg_replace( 
			'/(class="[^"]*amount[^"]*")/', 
			'$1 data-bt-cc-product-id="' . $product->get_id() . '"', 
			$price 
		);
	}
	return $price;
}, 10 );

// Disable add-to-cart button initially (ajax link - product in CC)
add_filter( 'woocommerce_loop_add_to_cart_link', function( $button, $product, $args ) {
	if ( ! is_product() && ! is_post_type_archive( 'product' ) ) {
		$bt_cc = get_post_meta( $product->get_id(), 'bt_cc', true );
		if ( $bt_cc ) {
			return preg_replace( '/class="([^"]*)"/', 'class="$1 bt_cc_disabled"', $button );
		}
	}
	return $button;
}, 10, 3 );

// Disable add-to-cart button initially (CC in product)
add_action( 'woocommerce_before_add_to_cart_form', function() {
	if ( is_product() ) {
		global $product;
		if ( $product && get_post_meta( $product->get_id(), 'bt_cc', true ) ) {
			ob_start();
		}
	}
});
add_action( 'woocommerce_after_add_to_cart_form', function() {
	if ( is_product() ) {
		global $product;
		if ( $product && get_post_meta( $product->get_id(), 'bt_cc', true ) ) {
			$content = ob_get_clean();
			echo preg_replace(
				'/<button([^>]*)type="submit"([^>]*)name="add-to-cart"([^>]*)>/',
				'<button$1type="submit"$2name="add-to-cart"$3 disabled="disabled">',
				$content
			);
		}
	}
});

// Modify add-to-cart button text for archive pages
add_filter( 'woocommerce_product_add_to_cart_text', 'modify_bt_cc_add_to_cart_text', 10, 2 );
function modify_bt_cc_add_to_cart_text( $text, $product ) {
   if ( is_post_type_archive( 'product' ) && $product->is_purchasable() && $product->is_in_stock() ) {
       $bt_cc = get_post_meta( $product->get_id(), 'bt_cc', true );
       if ( $bt_cc ) {
           return esc_html__( 'Select options', 'bt-cost-calculator' );
       }
   }
   return $text;
}

// Modify add-to-cart button URL for archive pages
add_filter( 'woocommerce_product_add_to_cart_url', 'modify_bt_cc_add_to_cart_url', 10, 2 );
function modify_bt_cc_add_to_cart_url( $url, $product ) {
   if ( is_post_type_archive( 'product' ) && $product->is_purchasable() && $product->is_in_stock() ) {
       $bt_cc = get_post_meta( $product->get_id(), 'bt_cc', true );
       if ( $bt_cc ) {
           return get_permalink( $product->get_id() );
       }
   }
   return $url;
}

// Remove ajax functionality for bt_cc products on archive pages
add_filter( 'woocommerce_loop_add_to_cart_args', 'modify_bt_cc_add_to_cart_args', 10, 1 );
function modify_bt_cc_add_to_cart_args( $args ) {
   global $product;
   
   if ( is_post_type_archive( 'product' ) && $product->is_purchasable() && $product->is_in_stock() ) {
       $bt_cc = get_post_meta( $product->get_id(), 'bt_cc', true );
       if ( $bt_cc && isset( $args['class'] ) ) {
           $args['class'] = str_replace( 'ajax_add_to_cart', '', $args['class'] );
       }
   }
   return $args;
}