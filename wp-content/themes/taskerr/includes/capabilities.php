<?php
/**
 * Register custom User capabilities
 *
 * @package Taskerr\Capabilities
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

add_action( 'appthemes_first_run', 'tr_add_caps' );
add_filter( 'map_meta_cap', 'tr_map_caps', 10, 4 );
add_action( 'admin_init', 'tr_restrict_admin_access' );
add_action( 'edit_profile_url', 'tr_restrict_admin_profile_page' );

/**
 * Initiates add cups process
 */
function tr_add_caps() {
	if ( get_option( 'default_role' ) === 'subscriber' )
		update_option( 'default_role', TR_USER_ROLE );

	tr_manage_caps( 'add_cap' );
}

/**
 * Initiates remove cups process
 */
function tr_remove_caps() {
	tr_manage_caps( 'remove_cap' );
}

/**
 * Adds default capabilities for roles
 *
 * @global WP_Roles $wp_roles
 * @param string $operation Type of operation: 'remove_cap' | 'add_cap'
 */
function tr_manage_caps( $operation ){
	global $wp_roles;

	if( ! isset( $wp_roles ) )
		$wp_roles = new WP_Roles();

	foreach( $wp_roles->roles as $role => $details ) {
		foreach ( tr_get_custom_caps( $role ) as $cap ) {
			$wp_roles->$operation( $role, $cap );
		}
	}

}

/**
 * Returns default capabilities for user role
 *
 * @param string $role Given role slug
 * @return array Array of default roles slugs
 */
function tr_get_custom_caps( $role ) {
	$caps = array(
		'edit_services',
		'edit_published_services',
		'add_tasks',
		'delete_services',
	);

	if( $role === TR_USER_ROLE ) {
		$caps = array_merge( $caps, array(
			'upload_media',
			'embed_media'
		) );
	}

	if( in_array( $role, array( 'editor', 'administrator' ) ) ) {
		$caps = array_merge( $caps, array(
			'edit_others_services',
			'publish_services',
			'delete_published_services',
			'delete_others_services'
		) );
	}
	return $caps;
}

/**
 * Maping caps
 *
 * @param array  $caps
 * @param string $cap
 * @param int    $user_id
 * @param array  $args
 *
 * @return array Returns modified caps array
 */
function tr_map_caps( $caps, $cap, $user_id, $args ){

	switch( $cap ){


		case 'add_task':
			$service = get_post( $args[0] );
			$caps = array( 'add_tasks' );
			if( $user_id ==  $service->post_author ){
				$caps[] = 'do_not_allow';
			}
			break;
		case 'paid_task':
			$task = get_the_task( $args[0] );
			$caps = array( 'edit_services' );
			if( $user_id != $task->get_service_author() ){
				$caps[] = 'do_not_allow';
			}
			if( ! in_array( $task->get_status(), array( TR_TASK_PENDING, TR_TASK_PAID ) ) ){
				$caps[] = 'do_not_allow';
			}
			break;
		case 'complete_task':
			$task = get_the_task( $args[0] );
			$caps = array( 'edit_services' );
			if( $user_id != $task->get_service_author() ){
				$caps[] = 'do_not_allow';
			}
			if( ! in_array( $task->get_status(), array( TR_TASK_PAID, TR_TASK_COMPLETED ) ) ){
				$caps[] = 'do_not_allow';
			}
			break;
		case 'confirm_task':
			$task = get_the_task( $args[0] );
			$caps = array( 'edit_services' );
			if( $user_id != $task->get_user() ){
				$caps[] = 'do_not_allow';
			}
			if( ! in_array( $task->get_status(), array( TR_TASK_COMPLETED, TR_TASK_CONFIRMED ) ) ){
				$caps[] = 'do_not_allow';
			}
			break;
		case 'rate_task':
			if( false /* User is Recepient of Any Task */ )
				$caps[] = 'do_not_allow';
			if( false /* Task isn't Completed/Confirmed */ )
				$caps[] = 'do_not_allow';
			break;

	}

	return $caps;
}

/**
 * Admin area access control with redirect
 */
function tr_restrict_admin_access() {
	global $tr_options;

	$access_level = $tr_options->admin_security;

	if ( empty( $access_level ) ) {
		$access_level = 'manage_options';
	}

	$is_async_upload = stripos( $_SERVER['PHP_SELF'], 'async-upload.php' );

	if ( $access_level == 'disable' || current_user_can( $access_level ) || defined( 'DOING_AJAX' ) || $is_async_upload ) {
		return;
	} else {
		wp_redirect( site_url() );
		exit;
	}
}

/**
 * Redirects user to front-end Profile page if admin page is secured
 *
 * @global scbOptions $tr_options
 * @param string $url
 * @return type
 */
function tr_restrict_admin_profile_page( $url ) {
	global $tr_options;

	$access_level = $tr_options->admin_security;

	if ( empty( $access_level ) ) {
		$access_level = 'manage_options';
	}

	if ( $access_level == 'disable' || current_user_can( $access_level ) ) {
		return $url;
	} else {
		return appthemes_get_edit_profile_url();
	}

	return $url;
}