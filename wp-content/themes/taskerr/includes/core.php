<?php
/**
 * Taskerr core functions
 *
 * @package Taskerr\Core
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Registers "Service" post type
 *
 * @global scbOptions $tr_options Theme options
 */
function tr_register_post_types() {

	global $tr_options;

	$labels = array(
		'name' => __( 'Services', APP_TD ),
		'singular_name' => __( 'Service', APP_TD ),
		'add_new' => __( 'Add New', APP_TD ),
		'add_new_item' => __( 'Add New Service', APP_TD ),
		'edit_item' => __( 'Edit Service', APP_TD ),
		'new_item' => __( 'New Service', APP_TD ),
		'view_item' => __( 'View Service', APP_TD ),
		'search_items' => __( 'Search Services', APP_TD ),
		'not_found' => __( 'No services found', APP_TD ),
		'not_found_in_trash' => __( 'No services found in Trash', APP_TD ),
		'parent_item_colon' => __( 'Parent Services:', APP_TD ),
		'menu_name' => __( 'Services', APP_TD ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchial' => false,

		'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions' ),

		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 6,
		'show_in_nav_menus' => false,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => array( 'slug' => $tr_options->service_permalink, 'with_front' => false ),
		'capability_type' => TR_SERVICE_PTYPE,
		'map_meta_cap' => true
	);

	register_post_type( TR_SERVICE_PTYPE, $args );
}

/**
 * Registers taxonomies
 *
 * @global scbOptions $tr_options Theme options
 */
function tr_register_taxonomies(){

	global $tr_options;

	$category_labels = array(
		'name' => __( 'Service Categories', APP_TD ),
		'singular_name' => __( 'Service Category', APP_TD ),
		'search_items' => __( 'Search Service Categories', APP_TD ),
		'all_items' => __( 'All Service Categories', APP_TD ),
		'parent_item' => __( 'Parent Listing Category', APP_TD ),
		'parent_item_colon' => __( 'Parent Listing Category:', APP_TD ),
		'edit_item' => __( 'Edit Service Category', APP_TD ),
		'update_item' => __( 'Update Service Category', APP_TD ),
		'add_new_item' => __( 'Add New Service Category', APP_TD ),
		'new_item_name' => __( 'New Listing Category Name', APP_TD ),
		'add_or_remopve_items' => __( 'Add or remove service categories', APP_TD ),
		'menu_name' => __( 'Categories', APP_TD ),
	);

	$category_slug = $tr_options->service_permalink . '/' . $tr_options->service_category_permalink;
	$category_args = array(
		'labels' => $category_labels,
		'rewrite' => array(
			'slug' => $category_slug,
			'with_front' => false
		),

		'public' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => false,
		'hierarchical' => true,
		'query_var' => true,
	);

	register_taxonomy( TR_SERVICE_CATEGORY, TR_SERVICE_PTYPE, $category_args );

	$tag_labels = array(
		'name' => __( 'Service Tags', APP_TD ),
		'singular_name' => __( 'Service Tag', APP_TD ),
		'search_items' => __( 'Search Service Tags', APP_TD ),
		'all_items' => __( 'All Service Tags', APP_TD ),
		'parent_item' => __( 'Parent Listing Tag', APP_TD ),
		'parent_item_colon' => __( 'Parent Listing Tag:', APP_TD ),
		'edit_item' => __( 'Edit Service Tag', APP_TD ),
		'update_item' => __( 'Update Service Tag', APP_TD ),
		'add_new_item' => __( 'Add New Service Tag', APP_TD ),
		'new_item_name' => __( 'New Listing Tag Name', APP_TD ),
		'add_or_remopve_items' => __( 'Add or remove service tags', APP_TD ),
		'menu_name' => __( 'Tags', APP_TD ),
	);

	$tag_slug = $tr_options->service_permalink . '/' . $tr_options->service_tag_permalink;
	$tag_args = array(
		'labels' => $tag_labels,
		'rewrite' => array(
			'slug' => $tag_slug,
			'with_front' => false
		),

		'public' => true,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => true,
		'hierarchical' => false,
		'query_var' => true,
	);

	register_taxonomy( TR_SERVICE_TAG, TR_SERVICE_PTYPE, $tag_args );
}

/**
 * Setup theme with nav menus, sidebars, and add_theme_support
 */
function tr_setup_theme() {
	// Menus
	register_nav_menu( 'header', __( 'Header Menu', APP_TD ) );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', APP_TD ),
		'id' => 'tr-main',
		'description' => __( 'Sidebar to the right of items on most pages', APP_TD ),
		'before_widget' => '<div class="row"><aside id="%1$s" class="widget large-12 columns %2$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => "</h1>\n",
	) );

	register_sidebar( array(
		'name' => __( 'Blog Sidebar', APP_TD ),
		'id' => 'tr-blog',
		'description' => __( 'Sidebar to the right of blog index', APP_TD ),
		'before_widget' => '<div class="row"><aside id="%1$s" class="widget large-12 columns %2$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => "</h1>\n",
	) );

	register_sidebar( array(
		'name' => __( 'Single Service Sidebar', APP_TD ),
		'id' => 'tr-service',
		'description' => __( 'Sidebar to the right of single service page', APP_TD ),
		'before_widget' => '<div class="row"><aside id="%1$s" class="widget large-12 columns %2$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => "</h1>\n",
	) );

	register_sidebar( array(
		'name' => __( 'Dashboard Sidebar', APP_TD ),
		'id' => 'tr-dashboard',
		'description' => __( 'Sidebar to the right of all dashboard pages', APP_TD ),
		'before_widget' => '<div class="row"><aside id="%1$s" class="widget large-12 columns %2$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => "</h1>\n",
	) );

	register_sidebar( array(
		'name' => __( 'Page Sidebar', APP_TD ),
		'id' => 'tr-page',
		'description' => __( 'Sidebar to the right of pages', APP_TD ),
		'before_widget' => '<div class="row"><aside id="%1$s" class="widget large-12 columns %2$s">',
		'after_widget' => '</aside></div>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => "</h1>\n",
	) );

	register_sidebar( array(
		'name' => __( 'Top Advert', APP_TD ),
		'id' => 'tr-advert-top',
		'description' => __( 'Optional widgets area above the header of each page', APP_TD ),
		'before_widget' => '<aside id="%1$s" class="widget large-12 centered columns %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => "</h1>\n",
	) );

	register_sidebar( array(
		'name' => __( 'Central Area', APP_TD ),
		'id' => 'tr-central',
		'description' => __( 'Optional widgets area under the header of each page', APP_TD ),
		'before_widget' => '<aside class="top-widget"><div id="top_%1$s" class="top-%2$s"><div class="row"><div class="large-12 centered columns"><div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></div></div></div></aside>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => "</h1>\n",
	) );

	register_sidebar( array(
		'name' => __( 'Central Area - Home', APP_TD ),
		'id' => 'tr-central-home',
		'description' => __( 'Optional widgets area under the header of Home page', APP_TD ),
		'before_widget' => '<aside class="top-widget"><div id="top_%1$s" class="top-%2$s"><div class="row"><div class="large-12 centered columns"><div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></div></div></div></aside>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => "</h1>\n",
	) );

	register_sidebar( array(
		'name' => __( 'Bottom Advert', APP_TD ),
		'id' => 'tr-advert-bottom',
		'description' => __( 'Optional widgets area above the Footer', APP_TD ),
		'before_widget' => '<aside id="%1$s" class="widget centered columns %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widgettitle">',
		'after_title' => "</h1>\n",
	) );

	register_sidebar( array(
		'name' => __( 'Footer', APP_TD ),
		'id' => 'tr-footer',
		'description' => __( 'Optional widget area at the bottom of each page', APP_TD ),
		'before_widget' => '<li id="%1$s" class="widget %2$s"><aside>',
		'after_widget' => '</aside></li>'
	) );

	// Misc
	add_theme_support( 'custom-background', array(
		'default-color' => 'eff1f2',
		'default-image' => get_template_directory_uri() . '/img/bg_body.png',
	) );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	add_image_size( 'service-thumbnail', 180, 180, true );
	add_image_size( 'tr-slider', 870, 489, true );
	add_image_size( 'recent-posts-widget', 60, 60, true );
}

/**
 * Set query variable with theme options to make them available on any template.
 * Using 'pre_get_posts' event prevents losing variable in a nested loops.
 *
 * @global scbOptions $tr_options Theme options
 * @param WP_Query $wp_query
 */
function tr_set_template_vars( $wp_query ) {
    global $tr_options;
	$wp_query->set( 'tr_options', $tr_options );

	// Restrict search results only to services
	if ( $wp_query->is_search && !is_admin() ) {
		$wp_query->set( 'post_type', TR_SERVICE_PTYPE );
	}
}
add_action( 'pre_get_posts', 'tr_set_template_vars' );

/**
 * ShareThis plugin compatibility
 */
function tr_sharethis_compatibility() {
	remove_filter( 'the_content', 'st_add_widget' );
	remove_filter( 'the_excerpt', 'st_add_widget' );
}
if ( function_exists( 'sharethis_button' ) ) {
	add_action( 'wp_head', 'tr_sharethis_compatibility' );
}

/**
 * Retrieve favicon URL
 *
 * @global scbOptions $tr_options Theme options
 * @param string $favicon_url
 * @return string Located favicon URL
 */
function tr_favicon( $favicon_url ) {
	global $tr_options;

	$favicon_url = ( $tr_options->favicon_url ) ? $tr_options->favicon_url : appthemes_locate_template_uri( 'favicon.ico' );
	return $favicon_url;
}
add_filter( 'appthemes_favicon', 'tr_favicon' );