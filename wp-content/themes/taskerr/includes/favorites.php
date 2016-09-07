<?php
/**
 * Favorites processing
 *
 * @package Taskerr\Favorites
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

add_action('init', 'tr_favorites_init', 13);

function tr_favorites_init() {
	$ajax_action = 'taskerr_favorites';

	p2p_register_connection_type( array(
		'name' => TR_SERVICE_FAVORITES,
		'from' => TR_SERVICE_PTYPE,
		'to' => 'user'
	) );

	add_action( 'wp_ajax_' . $ajax_action, 'tr_handle_ajax_favorites' );
	add_action( 'wp_ajax_nopriv_' . $ajax_action, 'tr_handle_ajax_favorites' );
}

/**
 * Handle favorites ajax requests
 */
function tr_handle_ajax_favorites() {
	if ( !isset( $_POST['favorite'] ) && !isset( $_POST['post_id'] ) && !isset( $_POST['current_url'] ) ) {
		return;
	}
	if ( ! in_array( $_POST['favorite'], array('add', 'delete') ) ) {
		return;
	}
	
	$post_id = (int) $_POST['post_id'];

	check_ajax_referer( "favorite-" . $post_id );

	$redirect = '';
	$status = 'success';

	if ( ! is_user_logged_in() ) {

		$redirect = esc_url( $_POST['current_url'] );
		$status = 'error';
		$notice = sprintf ( __( 'You must <a href="%1$s">login</a> to be able to favorite listings.', APP_TD ), wp_login_url( $redirect ) );

		_tr_favorites_send_ajax_response( $status, $notice, $post_id, $redirect );

	}

	$p2p = p2p_type( TR_SERVICE_FAVORITES );
	$user_id = get_current_user_id();
	if ( 'add' == $_POST['favorite'] ) {
		$date = current_time( 'mysql' );
		$status = $p2p->connect( $post_id, $user_id, array( 'date' => $date ) );

		$message = __("Added '%s' to your favorites.", APP_TD);
		$notice = sprintf( $message, get_the_title( $post_id ) );
	}
	else {
		$status = $p2p->disconnect( $post_id, $user_id );

		$message = __( "Removed '%s' from your favorites.", APP_TD );
		$notice = sprintf( $message, get_the_title( $post_id ) );
	}

	if ( is_wp_error( $p2p ) ) {
		$status = 'error';

		$message = __( "Could not add '%s' to favorites at this time.", APP_TD );
		$notice = sprintf( $message, get_the_title( $post_id ) );
	}

	_tr_favorites_send_ajax_response( $status, $notice, $post_id );
}

function _tr_favorites_send_ajax_response( $status, $notice, $post_id, $redirect_url = '' ){

	ob_start();
	appthemes_display_notice( $status, $notice );
	$notice = ob_get_clean();

	$result = array(
		'html' 	 	=> tr_display_fave_button( $post_id, $echo = FALSE ),
		'status' 	=> $status,
		'notice' 	=> $notice,
		'redirect' 	=> $redirect_url,
	);

	die ( json_encode( $result ) );

}


/**
 * Check if a specific listing is already favorited
 *
 * @param int     $post_id The listing id to search in
 *
 * @return bool   Returns True if already favorited, False otherwise
 */
function tr_is_fave_listing( $post_id ) {

	$count = p2p_get_connections( TR_SERVICE_FAVORITES, array (
		'direction' => 'from',
		'from' 		=> $post_id,
		'to' 		=> get_current_user_id(),
		'fields' 	=> 'count'
	) );

	return (bool) $count;
}

/**
 * Return the current URL with additional query variables
 *
 * @param int     $post_id The listing id to search in
 * @param string  $action The favorite action - valid options (add|delete)
 *
 * @return bool
 */
function tr_get_favorite_url( $post_id, $action = 'add' ) {

	$args = array (
		'favorite'  => $action,
		'post_id' => $post_id,
		'ajax_nonce' => wp_create_nonce( "favorite-" . $post_id ),
	);
	return add_query_arg( $args, home_url() );
}

/**
 * Returns or echoes the favorite button
 *
 * @param int     $post_id The listing id to search in
 * @param bool    $echo If set to FALSE does not echo the button HTML
 *
 * @return string
 */
function tr_display_fave_button( $post_id = '', $echo = TRUE ) {

	if( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	if ( ! tr_is_fave_listing( $post_id ) || ! is_user_logged_in() ) {
		$text = html( 'span', __( 'Favorite', APP_TD ) );

		$icon = html( 'i', array(
			'class' => 'genericon genericon-heart',
		), '');

		$button = html( 'a', array(
			'class' => "fave-button listing-fave-link button",
			'href' => tr_get_favorite_url( $post_id ),
			'rel' => 'nofollow',
		), $icon . ' ' . $text );

	} else {
		$text = html( 'span', __( 'Delete Favorite', APP_TD ) );

		$icon = html( 'i', array(
			'class' => 'genericon genericon-unapprove',
		), '');


		$button = html( 'a', array(
			'class' => "fave-button listing-unfave-link button",
			'href' => tr_get_favorite_url( $post_id, 'delete' ),
			'rel' => 'nofollow',
		),  $icon . ' ' . $text );

	}

	if ( $echo ) {
		echo $button;
	} else {
		return $button;
	}
}
