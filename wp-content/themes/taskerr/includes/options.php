<?php
/**
 * Default Options
 *
 * @package Taskerr\Options
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

$GLOBALS['tr_options'] = new scbOptions( 'tr_options', false, array(

	// Services
	'services_per_page'	=> 10,
	'service_charge'	=> '',
	'moderate_services'	=> '',
	'service_duration'  => 30,

	// Security Settings
	'admin_security'   => 'manage_options',
	'disable_wp_login' => 0,

	// Permalink Defaults
	'service_permalink'			 => 'services',
	'edit_service_permalink'	 => 'edit',
	'add_task_permalink'		 => 'add-task',
	'review_service_permalink'	 => 'review',
	'purchase_service_permalink' => 'purchase',
	'renew_service_permalink'	 => 'renew',
	'service_category_permalink' => 'category',
	'service_tag_permalink'		 => 'tag',


	// Featured Services
	'addons' => array(
		TR_ITEM_FEATURED_HOME => array(
			'enabled'	 => 'yes',
			'price'		 => 0,
			'duration'	 => 30,
		),

		TR_ITEM_FEATURED_CAT => array(
			'enabled'	 => 'yes',
			'price'		 => 0,
			'duration'	 => 30,
		),
	),


	// Gateways
	'gateways' => array(
		'enabled' => array( )
	),

	// Payments
	'currency_code' => 'USD',
	'currency_identifier' => 'symbol',
	'currency_position' => 'left',
	'thousands_separator' => ',',
	'decimal_separator' => '.',
	'tax_charge' => 0,

	// Integration
	'listing_sharethis' => 0,
	'blog_post_sharethis' => 0,

	// Contact info
	'email' => get_option( 'admin_email' ),
	'phone' => '',
	'skype' => '',


	// Social networks
	'social' => array(
		'twitter'		 => '',
		'facebook'		 => '',
		'linkedin'		 => '',
		'youtube'		 => '',
		'google-plus'	 => '',
		'instagram'		 => '',
		'tumblr'		 => '',
		'pinterest'		 => '',
		'github'		 => '',
		'wordpress'		 => '',
		'path'			 => '',
		'vimeo'			 => '',
		'flickr'		 => '',
		'picassa'		 => '',
		'foursquare'	 => '',
	),

	// File Uploads / Media Embeddings
	'max_images' => 5,
	'max_videos' => 5,
	'max_embeds' => 5,
	'max_image_size' => size_format( wp_max_upload_size() ),
	'max_video_size' => size_format( wp_max_upload_size() ),

	// Notifications
	'notify_new_services' => 'yes',
	'notify_new_review' => 'yes',

	// Appearance
	'color' => 'blue',

	// Favicon
	'favicon_url'	=> appthemes_locate_template_uri( 'favicon.ico' ),

) );


