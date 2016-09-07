<?php
/**
 * Addons query filter
 *
 * @package Components\Addons
 */

add_action( 'pre_get_posts', 'appthemes_addon_query_filter' );

function appthemes_addon_query_filter( $wp_query ){

	$addon_type = $wp_query->get( 'addon' );
	if( ! $addon_type )
		return;

	if( ! appthemes_addon_exists( $addon_type ) )
		return;

	extract( appthemes_get_addon_info( $addon_type ) );

	$wp_query->set( 'meta_key', $flag_key );
	$wp_query->set( 'meta_value', 1 );

}
