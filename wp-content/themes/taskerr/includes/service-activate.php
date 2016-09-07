<?php
/**
 * Service activation functions
 *
 * @package Taskerr\ServiceActivate
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

add_action( 'appthemes_transaction_completed', 'tr_handle_completed_transaction' );
add_action( 'appthemes_transaction_activated', 'tr_handle_activated_transaction' );
add_action( 'pending_to_publish', '_tr_handle_moderated_transaction');
add_action( 'draft_to_publish', '_tr_auto_complete_transaction');

add_action( 'appthemes_transaction_activated', '_tr_activate_plan');
add_action( 'appthemes_transaction_activated', '_tr_activate_addons');

function tr_handle_completed_transaction( $order ){
	global $tr_options;

	$listing_id = _tr_get_order_post_id( $order );

	if( ! $listing_id )
		return;

	if ( _tr_is_renewal_order( $order ) ) {
		tr_update_post_status( $listing_id, 'publish' );
		do_action( 'tr_service_renewed', $listing_id, $order );
		$order->activate();
		return;
	}

	if( $tr_options->moderate_services ) {
		tr_update_post_status( $listing_id, 'pending' );
		do_action( 'tr_new_service_added', $listing_id, $order );
		return;
	}

	tr_update_post_status( $listing_id, 'publish' );
	do_action( 'tr_new_service_added', $listing_id, $order );
	$order->activate();
}


/**
 * Be sure, that all listing with activated order are published.
 *
 * @param type $order
 * @return type
 */
function tr_handle_activated_transaction( $order ){
	$listing = get_post( _tr_get_order_post_id( $order ) );

	if( ! $listing )
		return;

	if ( 'publish' !== $listing->post_status ) {
		tr_update_post_status( $listing->ID, 'publish' );
		do_action( 'tr_new_service_added', $listing->ID, $order );
	}
}

/**
 * If used manual payment gateway (bank transfer for ex.), user might publish
 * listing before manually activate order.
 * This action will complete order automatically.
 *
 * 'draft_to_publish' action means, that order must be completed and activated
 * when listing is appearing to public
 *
 * Calling 'appthemes_transaction_completed' action, where order activates.
 *
 * @param type $post
 */
function _tr_auto_complete_transaction( $post ) {
	global $tr_options;

	if( $post->post_type != TR_SERVICE_PTYPE || ! $tr_options->service_charge )
		return;

	$order = appthemes_get_order_connected_to( $post->ID );
	if( !$order || $order->get_status() !== APPTHEMES_ORDER_PENDING )
		return;

	// First complete order and fire necessary actions using
	// tr_handle_completed_transaction()
	if ( APPTHEMES_ORDER_COMPLETED !== $order->get_status() ) {
		$order->complete();
	}

	// Then if order is not activated - do it manually
	if ( APPTHEMES_ORDER_ACTIVATED !== $order->get_status() ) {
		$order->activate();
	}
}

function _tr_handle_moderated_transaction( $post ){
	global $tr_options;

	if( $post->post_type != TR_SERVICE_PTYPE || ! $tr_options->service_charge )
		return;

	$order = appthemes_get_order_connected_to( $post->ID );
	if( !$order || $order->get_status() !== APPTHEMES_ORDER_COMPLETED )
		return;

	add_action( 'save_post', '_tr_activate_moderated_transaction', 11);
}

function _tr_activate_moderated_transaction( $post_id ){

	if( get_post_type( $post_id ) != TR_SERVICE_PTYPE )
		return;

	$order = appthemes_get_order_connected_to( $post_id );
	$order->activate();

}

function _tr_get_order_listing_info( $order ){

	$plans = new WP_Query( array( 'post_type' => APPTHEMES_PRICE_PLAN_PTYPE, 'nopaging' => 1, 'post_status' => 'any' ) );
	foreach( $plans->posts as $key => $plan){
		if ( empty( $plan->post_name ) )
			continue;

		$plan_slug = $plan->post_name;

		$items = $order->get_items( $plan_slug );
		if( $items ){
			$plan_data = tr_get_plan_options( $plan->ID );
			return array(
				'listing_id' => $items[0]['post_id'],
				'listing' => $items[0]['post'],
				'plan' => $plan,
				'plan_data' => $plan_data
			);
		}
	}

	return false;
}

function _tr_get_order_post_id( $order ){
	$items = $order->get_items();

	foreach ( $items as $item ) {
		if ( $item['post']->post_type === TR_SERVICE_PTYPE ) {
			return $item['post_id'];
		}
	}
}

function _tr_activate_plan( $order ){

	if( get_post_type( _tr_get_order_post_id( $order ) ) != TR_SERVICE_PTYPE )
		return;

	$listing_data =  _tr_get_order_listing_info( $order );
	if( !$listing_data )
		return;

	extract( $listing_data );

	if ( in_array( $listing->post_status, array( 'draft', TR_SERVICE_STATUS_EXPIRED ) ) )
		tr_update_post_status( $listing_id, 'publish' );

	tr_update_listing_start_date( $listing );
	tr_update_service_duration( $listing_id, $plan_data['duration'] );

	foreach( tr_get_addons() as $addon ){
		if( !empty( $plan_data[$addon] ) ){
			appthemes_add_addon( $listing_id, $addon, $plan_data[ $addon . '_duration' ] );
		}
	}
}

function _tr_activate_addons( $order ){
	global $tr_options;

	if( get_post_type( _tr_get_order_post_id( $order ) ) != TR_SERVICE_PTYPE )
		return;

	foreach( tr_get_addons() as $addon ){
		foreach( $order->get_items( $addon ) as $item ){
			appthemes_add_addon( $item['post_id'], $addon, $tr_options->addons[$addon]['duration'] );
		}
	}
}

function _tr_is_renewal_order( $order ){
	return get_post_meta( $order->get_id(), 'is_renewal', true );
}