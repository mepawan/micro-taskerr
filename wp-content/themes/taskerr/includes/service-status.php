<?php
/**
 * Service status functions
 *
 * @package Taskerr\ServiceStatus
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

add_action( 'pending_to_publish', 'tr_update_listing_start_date' );
add_action( 'draft_to_publish', 'tr_update_listing_start_date' );

add_action( 'init', 'tr_register_custom_statuses' );
add_action( 'init', 'tr_schedule_listing_prune' );
add_action( 'tr_prune_expired_listings', 'tr_prune_expired_listings' );

add_filter( 'posts_clauses', 'tr_expired_listing_sql', 10, 2 );

function tr_register_custom_statuses() {
	register_post_status( TR_SERVICE_STATUS_EXPIRED, array(
		'label'                     => _x( 'Expired', 'service', APP_TD ),
		'public'                    => false,
		'protected'                 => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', APP_TD ),
	) );

	register_post_status( TR_SERVICE_STATUS_DELETED, array(
		'label'                     => _x( 'Deleted', 'service', APP_TD ),
		'public'                    => false,
		'exclude_from_search'       => true,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Deleted <span class="count">(%s)</span>', 'Deleted <span class="count">(%s)</span>', APP_TD ),
	) );
}

function tr_update_listing_start_date( $post ) {
	if ( $post->post_type == TR_SERVICE_PTYPE ) {
		wp_update_post( array(
			"ID" => $post->ID,
			"post_date" => current_time( 'mysql' )
		) );
	}
}

function tr_schedule_listing_prune() {
	if ( !wp_next_scheduled( 'tr_prune_expired_listings' ) )
		wp_schedule_event( time(), 'hourly', 'tr_prune_expired_listings' );
}

function tr_prune_expired_listings() {

	$expired_posts = new WP_Query( array(
		'post_type'        => TR_SERVICE_PTYPE,
		'post_status'      => 'publish',
		'expired_listings' => true,
		'nopaging'         => true,
	) );

	foreach ( $expired_posts->posts as $post ) {
		tr_update_post_status( $post->ID, TR_SERVICE_STATUS_EXPIRED );
	}
}

function tr_expired_listing_sql( $clauses, $wp_query ) {
	global $wpdb;

	if ( $wp_query->get( 'expired_listings' ) ) {
		$clauses['join'] .= " INNER JOIN " . $wpdb->postmeta ." AS exp1 ON (" . $wpdb->posts .".ID = exp1.post_id)";

		$clauses['where'] .= " AND ( exp1.meta_key = 'listing_duration' AND DATE_ADD(post_date, INTERVAL exp1.meta_value DAY) < '" . current_time( 'mysql' ) . "' AND exp1.meta_value > 0 )";
	}

	return $clauses;
}

function tr_update_post_status( $post_id, $new_status ) {
	wp_update_post( array(
		'ID' => $post_id,
		'post_status' => $new_status
	) );
}

/**
 * Updates the duration for a given service.
 */
function tr_update_service_duration( $post_id, $duration = null ) {
    global $tr_options;

    if ( null === $duration ) {
        $duration = $tr_options->service_duration;
    }

	$duration = (int) $duration;
    return update_post_meta( $post_id, 'listing_duration', $duration );
}