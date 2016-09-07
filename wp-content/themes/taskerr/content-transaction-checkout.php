<?php
/**
 * Template for displaying Order Checkout content.
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.3
 */

process_the_order();
$order = get_order();
if ( in_array( $order->get_status(), array( APPTHEMES_ORDER_COMPLETED, APPTHEMES_ORDER_ACTIVATED ) ) ) {
	$redirect_to = esc_url_raw( get_post_meta( $order->get_id(), 'complete_url', true ) );

	echo html( 'a', array( 'href' => $redirect_to ), __( 'Continue', APP_TD ) );
	echo html( 'script', 'location.href="' . $redirect_to . '"' );
}
