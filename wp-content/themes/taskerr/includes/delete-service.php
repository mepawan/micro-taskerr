<?php
/**
 * Delete Service processing
 *
 * @package Taskerr\Delete-Service
 * @author  AppThemes
 * @since   Taskerr 1.1
 */

add_action( 'init', 'tr_delete_service_init', 14 );

function tr_delete_service_init() {
	$ajax_action = 'taskerr_delete_service';

	add_action( 'wp_ajax_' . $ajax_action, 'tr_handle_ajax_delete_service' );
	add_action( 'wp_ajax_nopriv_' . $ajax_action, 'tr_handle_ajax_delete_service' );
	add_action( 'wp_enqueue_scripts', 'tr_localize_dashboard' );
}

function tr_localize_dashboard() {
	wp_localize_script( 'tr-dashboard', 'dasboardL10n', array( 'delete_service' => __( 'Are you sure want to delete that sevice?', APP_TD ) ) );
}

/**
 * Return the current URL with additional query variables
 *
 * @param int     $post_id The listing id to search in
 *
 * @return string URL
 */
function tr_get_delete_service_url( $post_id ) {

	$args = array (
		'delete'     => $post_id,
		'ajax_nonce' => wp_create_nonce( "delete-service-" . $post_id ),
	);
	return add_query_arg( $args );
}

function tr_handle_ajax_delete_service() {

	if ( ! isset( $_POST['delete'] ) ) {
		return;
	}

	$post_id = (int) $_POST['delete'];

	check_ajax_referer( "delete-service-" . $post_id );

	$status = 'success';

	if ( ! current_user_can( 'edit_service', $post_id ) ) {

		$status = 'error';
		$notice = sprintf ( __( 'You do not have permission to delete that service.', APP_TD ) );

		_tr_delete_service_send_ajax_response( $status, $notice );

	}

	tr_update_post_status( $post_id, TR_SERVICE_STATUS_DELETED );

	$message = __("Deleted service '%s'.", APP_TD);
	$notice = sprintf( $message, get_the_title( $post_id ) );

	_tr_delete_service_send_ajax_response( $status, $notice );
}

function _tr_delete_service_send_ajax_response( $status, $notice ){

	ob_start();
	appthemes_display_notice( $status, $notice );
	$notice = ob_get_clean();

	$result = array(
		'html' 	 	=> '',
		'status' 	=> $status,
		'notice' 	=> $notice,
	);

	die ( json_encode( $result ) );

}