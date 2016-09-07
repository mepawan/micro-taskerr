<?php
/**
 * Register Service Addons
 *
 * @package Taskerr\Addons
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

appthemes_register_addon( TR_ITEM_FEATURED_HOME, array(
	'type' => TR_SERVICE_PTYPE,
	'title' => __( 'Featured on Homepage', APP_TD ),
) );

appthemes_register_addon( TR_ITEM_FEATURED_CAT, array(
	'type' => TR_SERVICE_PTYPE,
	'title' => __( 'Featured on Category', APP_TD ),
) );

/**
 * Returns an array of registered payment Addons
 *
 * @return array Array of addons names
 */
function tr_get_addons(){
	return APP_Addon_Registry::get_addons();
}

/**
 * Pre fills addon's meta with 0 values for each new service.
 * Required for correct sorting.
 *
 * @param int $post_ID
 */
function tr_pre_fill_addons( $post_ID ) {
	$addons = tr_get_addons();

	foreach ( $addons as $addon ) {
		$info = appthemes_get_addon_info( $addon );

		if ( get_post_meta( $post_ID, $info['flag_key'], true ) )
			return;

		update_post_meta( $post_ID, $info['flag_key'], 0 );
	}
}
add_action( 'save_post_' . TR_SERVICE_PTYPE, 'tr_pre_fill_addons' );