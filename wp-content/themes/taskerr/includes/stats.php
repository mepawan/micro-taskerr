<?php
/**
 * Super simple framework for tracking page views.
 *
 * The filtering for when it tracks a view is able to be controlled by filter 'tr_log_page_view'
 *
 * @package Taskerr\Stats
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
add_filter( 'tr_log_page_view', 'tr_page_views_check_ip_address' );

/**
 * Updates post meta with increased page views count
 */
function tr_process_page_view(){

	$post = get_queried_object();
	if ( ! $post ) {
		return;
	}

	$views = get_post_meta( $post->ID, 'view_count', true );

	$shouldLogView = apply_filters( 'tr_log_page_view', true );
	if ( $shouldLogView ) {
		update_post_meta( $post->ID, 'view_count', $views + 1 );
	}

}

/**
 * Stops page views from counting more than once per an ip address
 */
function tr_page_views_check_ip_address(){

	$post = get_queried_object();
	if( ! $post ) {
		return;
	}

	$addresses = get_post_meta( $post->ID, '_tr_view_ip_addresses', true );
	if( ! $addresses ) {
		$addresses = array();
	}

	$ip_address = appthemes_get_ip();
	if( ! in_array( $ip_address, $addresses ) ) {
		$addresses[] = $ip_address;
		$addresses = array_slice( $addresses, 0, 50 );
		update_post_meta( $post->ID, '_tr_view_ip_addresses', $addresses );
		return true;
	} else {
		return false;
	}

}