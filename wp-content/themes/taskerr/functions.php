<?php
/**
 * Theme functions file
 *
 * DO NOT MODIFY THIS FILE. Make a child theme instead: htp://codex.wordpress.org/Child_Themes
 *
 * @package Taskerr
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

global $tr_options;

define( 'APP_TD', 'taskerr' );

define( 'TR_VERSION', '1.3.1' );
define( 'TR_META_KEY_PREFIX', 'tr_' );

define( 'TR_SERVICE_PTYPE', 'service' );
define( 'TR_SERVICE_CATEGORY', 'service_cat' );
define( 'TR_SERVICE_TAG', 'service_tag' );

define( 'TR_SERVICE_FAVORITES', 'service-favorites' );

define( 'TR_ITEM_FEATURED_HOME', 'featured-home' );
define( 'TR_ITEM_FEATURED_CAT', 'featured-cat' );

define( 'TR_SERVICE_STATUS_EXPIRED', 'expired' );
define( 'TR_SERVICE_STATUS_DELETED', 'deleted' );

define( 'TR_USER_ROLE', 'contributor' );

define( 'TR_ATTACHMENT_GALLERY', 'gallery' );
define( 'TR_ATTACHMENT_FILE', 'file' );

require dirname( __FILE__ ) . '/framework/load.php';
require dirname( __FILE__ ) . '/theme-framework/load.php';
require dirname( __FILE__ ) . '/framework/includes/tables.php';
require dirname( __FILE__ ) . '/framework/admin/class-user-meta-box.php';

appthemes_load_files( dirname( __FILE__ ) . '/includes/', array(
	'payments/load.php',
	'checkout/load.php',
	'plans/load.php',
	'addons/load.php',
	'slider/load.php',
	'search-index/load.php',
	'notifications/load.php',
	'reviews/load.php',
	'options.php',
	'core.php',
	'urls.php',
	'custom-post-type-helper.php',
	'display.php',
	'template-tags.php',
	'social.php',
	'favorites.php',
	'profile.php',
	'addons.php',
	'images.php',
	'capabilities.php',
	'utils.php',
	'service-status.php',
	'service-activate.php',
	'tasks.php',
	'stats.php',
	'views.php',
	'views-checkout.php',
	'views-review.php',
	'views-task.php',
	'widgets/load.php',
	'widgets.php',
	'task-actions-button.php',
	'sorting.php',
	'notifications.php',
	'foundation.php',
	'custom-header.php',
	'customizer.php',
	'delete-service.php',
	'search.php',
) );

APP_Mail_From::init();
TR_Foundation::init();

add_theme_support( 'app-versions', array(
	'update_page'     => 'admin.php?page=app-settings&firstrun=1',
	'current_version' => TR_VERSION,
	'option_key'      => 'tr_version'
) );

add_theme_support( 'app-reviews', array(
	'post_type'            => TR_SERVICE_PTYPE,
	'auto_approve'         => true,
	'admin_top_level_page' => 'app-dashboard',
	'admin_sub_level_page' => 'app-settings',
) );

add_theme_support( 'app-notifications', array(
	'post_type'            => TR_SERVICE_PTYPE,
	'admin_bar'            => false,
	'admin_top_level_page' => 'none',
	'admin_sub_level_page' => '',
) );

add_theme_support( 'app-slider', array(
	'enqueue_scripts' => false,
) );

add_theme_support( 'app-media-manager' );

add_action( 'init', 'tr_register_post_types', 9 );
add_action( 'init', 'tr_register_taxonomies', 8 );

appthemes_add_instance( array(
	'TR_Home',
	'TR_Blog_Archive',
	'TR_Service_Archive',
	'TR_Author',
	'APP_User_Profile',
	'TR_User_Notifications_Meta_Box',
	'TR_Service_Taxonomy_Archive',
	'TR_Sorting_Services_Archives',
	'TR_Process_Service_Create',
	'TR_Process_Service_Update',
	'TR_Process_Service_Renew',
	'TR_Process_Service_Order',
	'TR_Process_Service_Review',
	'TR_Select_Plan',
	'TR_Service_Edit',
	'TR_Service_Add',
	'TR_Service_Renew',
	'TR_Service_Rate',
	'TR_Task_Confirm',
	'TR_Task_Summary',
	'TR_Gateway_Select',
	'TR_Gateway_Process',
	'TR_Order_Summary',
	'TR_Dashboard_Home',
	'TR_Dashboard_Purchased',
	'TR_Dashboard_Tasks',
	'TR_Dashboard_Reviews',
	'TR_Dashboard_Favorites',
	'TR_Dashboard_Notifications',
	'TR_Widget_Taxonomy_List',
	'TR_Widget_125_Ads',
	'TR_Widget_Recent_Posts',
	'APP_Widget_Facebook',
	'APP_Widget_Breadcrumbs',

) );

if( is_admin() ){

	require dirname( __FILE__ ) . '/includes/admin/settings.php';
	require dirname( __FILE__ ) . '/includes/admin/dashboard.php';
	require dirname( __FILE__ ) . '/includes/admin/service-single.php';
	require dirname( __FILE__ ) . '/includes/admin/featured.php';
	require dirname( __FILE__ ) . '/includes/admin/addons-mp/load.php';

	appthemes_add_instance( array(
		'TR_Settings_Admin' => array( $tr_options ),
		'TR_Service_Pricing', 'TR_Dashboard',
		'TR_Listing_Pricing',
		'TR_Tasks_Summary',
		'APP_System_Info',
		'TR_Service_Media' => array( '_app_media', __( 'Attachments', APP_TD ), TR_SERVICE_PTYPE, 'normal', 'low' ),
	) );
}

require dirname( __FILE__ ) . '/includes/admin/install.php';
require dirname( __FILE__ ) . '/includes/admin/upgrade.php';

// load up wrapper.php

add_theme_support( 'app-wrapping' );

add_theme_support( 'app-login', array(
	'login'         => 'form-login.php',
	'register'      => 'form-registration.php',
	'recover'       => 'form-password-recovery.php',
	'reset'         => 'form-password-reset.php',
	'redirect'      => $tr_options->disable_wp_login,
	'settings_page' => 'admin.php?page=app-settings',
) );

add_theme_support( 'app-payments', array(
	'items' => array(
		array(
			'type'  => TR_ITEM_FEATURED_HOME,
			'title' => __( 'Feature on Homepage', APP_TD ),
			'meta'  => array(
				'price' => $tr_options->addons[ TR_ITEM_FEATURED_HOME ]['price']
			)
		),
		array(
			'type'  => TR_ITEM_FEATURED_CAT,
			'title' => __( 'Feature on Category', APP_TD ),
			'meta'  => array(
				'price' => $tr_options->addons[ TR_ITEM_FEATURED_CAT ]['price']
			)
		)
	),
	'options' => $tr_options,
	'items_post_types' => array( TR_SERVICE_PTYPE ),

) );

add_theme_support( 'app-price-format', array(
	'currency_default'    => $tr_options->currency_code,
	'currency_identifier' => $tr_options->currency_identifier,
	'currency_position'   => $tr_options->currency_position,
	'thousands_separator' => $tr_options->thousands_separator,
	'decimal_separator'   => $tr_options->decimal_separator,
	'hide_decimals'       => (bool) ( ! $tr_options->decimal_separator ),
) );

add_theme_support( 'app-feed', array(
	'post_type'     => TR_SERVICE_PTYPE,
	'blog_template' => 'index.php',
) );

add_theme_support( 'app-search-index', array(
	'admin_page' => true,
	'admin_top_level_page' => 'app-dashboard',
	'admin_sub_level_page' => 'app-system-info',
) );

add_theme_support( 'app-addons-mp', array(
	'product' => array( 'taskerr' ),
) );

add_theme_support( 'app-require-updater', true );

// setup theme

add_action( 'after_setup_theme', 'tr_setup_theme' );

appthemes_init();