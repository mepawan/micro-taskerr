<?php
/**
 * Functions with a dubious lifetime
 *
 * @package Taskerr\Utils
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Retrieves Service object
 *
 * @param type $post_id
 * @return type
 */
function tr_get_listing_obj( $post_id = '' ){

	$listing = get_post( $post_id );

	foreach ( tr_get_special_fields() as $field ) {
		$listing->$field = get_post_meta( $listing->ID, $field, true );
	}

	$categories = get_the_terms( $listing->ID, TR_SERVICE_CATEGORY );
	if ( !empty( $categories ) ) {
		$listing->categories = $categories;
	}

	$tags = get_the_terms( $listing->ID, TR_SERVICE_TAG );
	if ( !empty( $tags ) ) {
		$listing->tags = esc_attr( implode( ', ', wp_list_pluck( $tags, 'name' ) ) );
	}

	return $listing;

}

/**
 * Returns an array of pre-defined Service custom fields
 *
 * @return array Array custom fields names
 */
function tr_get_special_fields(){
	return array( 'price', 'delivery_time' );
}