<?php
/**
 * Slider Load
 *
 * @package Slider
 */

add_action( 'init', '_appthemes_load_slider' );

function _appthemes_load_slider() {
	if ( ! current_theme_supports( 'app-slider' ) ) {
		return;
	}

	require_once dirname( __FILE__ ) . '/slider.php';

	$args = appthemes_slider_get_args();

	if ( $args['enqueue_scripts'] ) {
		add_action( 'wp_enqueue_scripts', 'appthemes_slider_enqueue_scripts' );
	}

	if ( $args['enqueue_styles'] ) {
		add_action( 'wp_enqueue_scripts', 'appthemes_slider_enqueue_styles' );
	}

	appthemes_slider_init_image_size();
}

function appthemes_slider_enqueue_scripts( $script_uri = '' ) {
	if ( ! current_theme_supports( 'app-slider' ) ) {
		return;
	}

	$args = appthemes_slider_get_args();

	$script_uri = ! empty( $script_uri ) ? $script_uri : $args['url'] . '/slider.js';
	wp_enqueue_script(
		'app-slider',
		$script_uri,
		array( 'jquery' ),
		'1.1'
	);
}

function appthemes_slider_enqueue_styles( $style_uri = '' ) {
	if ( ! current_theme_supports( 'app-slider' ) ) {
		return;
	}

	$args = appthemes_slider_get_args();

	$style_uri = ! empty( $style_uri ) ? $style_uri : $args['url'] . '/slider.css';
	wp_enqueue_style(
		'app-slider-style',
		$style_uri,
		array(),
		'1.0'
	);
}

function appthemes_slider_init_image_size() {
	global $_wp_additional_image_sizes;

	if ( ! current_theme_supports( 'app-slider' ) ) {
		return;
	}

	$args = appthemes_slider_get_args();

	// check if we need to register new image size
	if ( isset( $_wp_additional_image_sizes[ $args['attachment_image_size'] ] ) ) {
		return;
	}

	$size = apply_filters( 'appthemes_slider_image_size', array( 'width' => $args['width'], 'height' => $args['height'] ) );

	add_image_size( $args['attachment_image_size'], $size['width'], $size['height'], true );
}

/**
 * Retrieve slider theme support options,
 * which can be overriden by the slider instance properties
 *
 */
function appthemes_slider_get_args() {
	global $content_width, $_wp_additional_image_sizes;
	static $args = array();

	if ( ! current_theme_supports( 'app-slider' ) ) {
		return array();
	}

	if ( empty( $args ) ) {

		// numeric array, contains multiple sets of arguments
		// first item contains preferable set
		$args_sets = get_theme_support( 'app-slider' );

		if ( ! is_array( $args_sets ) ) {
			$args_sets = array();
		}

		foreach ( $args_sets as $args_set ) {
			foreach ( $args_set as $key => $arg ) {
				if ( ! isset( $args[ $key ] ) ) {
					$args[ $key ] = $arg;
				} elseif ( ( 'enqueue_scripts' === $key || 'enqueue_styles' === $key ) && ! $arg ) {
					$args[ $key ] = false;
				} elseif ( is_array( $arg ) ) {
					$args[ $key ] = array_merge_recursive( (array) $args[ $key ], $arg );
				}
			}
		}

		$defaults = array(
			'mime_groups'                   => array( 'image', 'video', 'video_iframe_embed' ),
			'image_mime_types'              => array( 'image/png', 'image/jpeg', 'image/gif' ),
			'video_mime_types'              => array( 'video/mp4' ),
			'video_iframe_embed_mime_types' => array( 'video/youtube-iframe-embed', 'video/vimeo-iframe-embed', 'video/iframe-embed' ),
			'id'                            => 'app-slider',
			'slider_class'                  => '',
			'video_embed_class'             => '',
			'attachment_image_size'         => 'app_slider',
			'image_a_attr'                  => array(),
			'enqueue_scripts'               => true,
			'enqueue_styles'                => true,
			'effect'                        => 'slide',
			'duration'                      => 300,
			'url'                           => get_template_directory_uri() . '/includes/slider',
		);

		$args = wp_parse_args( $args, $defaults );

		if ( ! isset( $args['width'] ) || ! isset( $args['height'] ) ) {
			if ( isset( $_wp_additional_image_sizes[ $args['attachment_image_size'] ] ) ) {
				// first check if image size is registered
				$args = wp_parse_args( $args, $_wp_additional_image_sizes[ $args['attachment_image_size'] ] );
			} else {
				// otherwise generate own values
				$content_width = intval( $content_width );
				if ( $content_width ) {
					// if content_width is set, we can use it for default slider size
					$args['width']  = $content_width;
					$args['height'] = intval( $content_width * 9 / 16 );
				} else {
					// otherwise we have to fallback to fixed values
					$args['width']  = 475;
					$args['height'] = 300;
				}
			}
		}
	}

	return $args;
}