<?php
/**
 * Admin Pricing functions
 *
 * @package Taskerr\Admin\Pricing\Functions
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

define( 'APPTHEMES_PRICE_PLAN_PTYPE', 'pricing-plan' );

add_action( 'init', 'appthemes_pricing_setup' );
add_action( 'admin_menu', 'appthemes_pricing_add_menu', 11 );

if( is_admin() ){
	add_filter( 'the_title', 'appthemes_pricing_modify_title', 10, 2 );
}

/**
 * Registers Plans post type
 */
function appthemes_pricing_setup(){

	$labels = array(
		'name' => __( 'Plans', APP_TD ),
		'singular_name' => __( 'Plans', APP_TD ),
		'add_new' => __( 'Add New', APP_TD ),
		'add_new_item' => __( 'Add New Plan', APP_TD ),
		'edit_item' => __( 'Edit Plan', APP_TD ),
		'new_item' => __( 'New Plan', APP_TD ),
		'view_item' => __( 'View Plan', APP_TD ),
		'search_items' => __( 'Search Plans', APP_TD ),
		'not_found' => __( 'No Plans found', APP_TD ),
		'not_found_in_trash' => __( 'No Plans found in Trash', APP_TD ),
		'parent_item_colon' => __( 'Parent Plan:', APP_TD ),
		'menu_name' => __( 'Plans', APP_TD ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'supports' => array( 'no-ops' ),
		'public' => false,
		'capability_type' => 'page',
		'show_ui' => true,
		'show_in_menu' => false,
	);

	register_post_type( APPTHEMES_PRICE_PLAN_PTYPE, $args );

	$plans = new WP_Query(array( 'post_type' => APPTHEMES_PRICE_PLAN_PTYPE, 'nopaging' => 1));
	foreach( $plans->posts as $plan){
		$data = get_post_custom( $plan->ID );
		if( isset( $data['title']) )
			APP_Item_Registry::register( $plan->post_name, $data['title'][0] );
	}
}

/**
 * Registers Pricing menu in Admin area
 *
 * @global string $pagenow
 * @global string $typenow
 */
function appthemes_pricing_add_menu(){
	global $pagenow, $typenow;
	$ptype = APPTHEMES_PRICE_PLAN_PTYPE;
	$ptype_obj = get_post_type_object( $ptype );

	add_submenu_page( 'app-payments', $ptype_obj->labels->name, $ptype_obj->labels->all_items, $ptype_obj->cap->edit_posts, "edit.php?post_type=$ptype" );

	if($pagenow == 'post-new.php' && $typenow == $ptype) {
		add_submenu_page( 'app-payments', $ptype_obj->labels->new_item, $ptype_obj->labels->new_item, $ptype_obj->cap->edit_posts, "post-new.php?post_type=$ptype" );
	}
}

add_filter('parent_file', 'appthemes_pricing_menu_edit_page_menu_workaround');

/**
 * Edit pricing single page workaround
 *
 * @global string $pagenow
 * @global string $typenow
 * @param string $parent_file
 *
 * @return string
 */
function appthemes_pricing_menu_edit_page_menu_workaround($parent_file) {
	global $pagenow, $typenow;

	$ptype = APPTHEMES_PRICE_PLAN_PTYPE;
	$ptype_obj = get_post_type_object( $ptype );

	if($parent_file == "edit.php?post_type=$ptype" && ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == $ptype) {
		return 'app-payments';
	}

	return $parent_file;
}

/**
 * Modifies Pricing page title
 *
 * @param string $title
 * @param int $post_id
 *
 * @return string
 */
function appthemes_pricing_modify_title( $title, $post_id ){

	$post = get_post( $post_id );
	if( $post->post_type != APPTHEMES_PRICE_PLAN_PTYPE ){
		return $title;
	}

	return get_post_meta( $post_id, 'title', true );

}