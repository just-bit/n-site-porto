<?php
/**
 * Lost password confirmation
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version 10.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();
wc_print_notice( esc_html__( 'Password reset email has been sent.', 'medigreen' ) );
?>

<p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'medigreen' ) ); ?></p>
