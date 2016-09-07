<?php
/**
 * Images processing
 *
 * @package Taskerr\Images
 * @author  AppThemes
 * @since   Taskerr 1.0
 * @todo Review all the image functions below, it seems they are useless.
 */

//add_action( 'appthemes_first_run', 'tr_init_image_sizes' );
//add_filter( 'intermediate_image_sizes_advanced', 'tr_set_image_crop' );
//add_filter( 'wp_get_attachment_image_attributes', 'tr_attachment_attributes' );
add_action( 'save_post', 'tr_update_featured_image' );

function tr_init_image_sizes( $sizes ) {
	update_option( 'thumbnail_size_w', 50 );
	update_option( 'thumbnail_size_h', 50 );
	update_option( 'thumbnail_crop', true );
	update_option( 'medium_size_w', 230 );
	update_option( 'medium_size_h', 230 );
}


function tr_set_image_crop( $sizes ) {
	$sizes['thumbnail']['crop'] = true;
	$sizes['medium']['crop'] = true;

	return $sizes;
}

function tr_attachment_attributes( $attr ) {
	unset( $attr['title'] );

	return $attr;
}

/**
 * Set first attachement as featured image
 */
function tr_update_featured_image( $post_id ) {
	global $post;

	if ( $post_id ) {
		$post = get_post( $post_id );
	}

	// Set up featured images only for services and blog posts
	if ( ! $post || ( TR_SERVICE_PTYPE !== $post->post_type && 'post' !== $post->post_type ) ) {
		return;
	}

	$args = array(
		'posts_per_page' => 1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_mime_type' => 'image',
		'post_parent'    => $post->ID,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'meta_key'       => '_app_attachment_type',
		'meta_value'     => TR_ATTACHMENT_GALLERY,
		'fields'         => 'ids',
	);

	$attached_image = get_posts( $args );

	if ( $attached_image ) {
		foreach ( $attached_image as $attachment_id ) {
			set_post_thumbnail( $post->ID, $attachment_id );
		}
	}
}

function tr_get_attachment_link( $att_id, $size = 'thumbnail' ) {
	return html( 'a', array(
		'href' => wp_get_attachment_url( $att_id ),
		'rel' => 'colorbox',
		'title' => trim( strip_tags( get_post_meta( $att_id, '_wp_attachment_image_alt', true ) ) )
	), wp_get_attachment_image( $att_id, $size ) );
}

function get_the_listing_thumbnail( $listing_id = '' ) {
	$listing_id = ( !empty( $listing_id ) ) ? $listing_id : get_the_ID();

	$featured_id = get_post_thumbnail_id( $listing_id );

	if ( !$featured_id ) {
		$attachments = tr_get_post_attachments( $listing_id, 1 );

		if ( empty( $attachments ) )
			return html( 'img', array( 'src' => get_bloginfo('template_directory') . '/images/no-thumb-sm.jpg' ) );

		$featured_id = $attachments[0]->ID;
	}

	return wp_get_attachment_image( $featured_id, 'thumbnail' );

}

function the_listing_thumbnail ( $listing_id = '' ) {

	$listing_id = ( !empty( $listing_id ) ) ? $listing_id : get_the_ID();

	echo get_the_listing_thumbnail($listing_id);

}