<?php
/**
 * Helper functions for getting URLs and links
 *
 * @package Taskerr\URLs
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

function tr_get_profile_url(){
	$current_user = wp_get_current_user();
	return get_author_posts_url( $current_user->ID );
}

function tr_get_dashboard_url() {
	return get_permalink( TR_Dashboard_Home::get_id() );
}

function tr_get_dashboard_notifications_url(){
	return get_permalink( TR_Dashboard_Notifications::get_id() );
}

function tr_get_notification_url( $id = '' ){
	return get_permalink( TR_Dashboard_Notifications::get_id() ) . '#' . $id;
}

function tr_get_dashboard_favorites_url(){
	return get_permalink( TR_Dashboard_Favorites::get_id() );
}

function tr_get_dashboard_purchased_url(){
	return get_permalink( TR_Dashboard_Purchased::get_id() );
}

function tr_get_dashboard_tasks_url(){
	return get_permalink( TR_Dashboard_Tasks::get_id() );
}

function tr_get_service_reviews_url(){
	return get_permalink() . '#reviews-tab';
}

/**
 * @param type $post_id
 * @return string Front end service renew form url
 */
function tr_get_service_renew_url( $post_id ) {
	return TR_Process_Service_Renew::get_link( $post_id );
}

/**
 * @param int $post_id
 * @return string Front end service edit form url
 */
function tr_get_service_edit_url( $post_id = '' ) {
	return TR_Process_Service_Update::get_link( $post_id );
}

/**
 * Returns the url for creating a new service
 *
 * dashboard-services.php
 * sidebar-dashboard.php
 */

function tr_get_service_create_url() {
	return get_permalink( TR_Process_Service_Create::get_id() );
}

function tr_service_add_review_url() {
	return TR_Process_Service_Review::get_link();
}