<?php
/**
 * Loads Foundation framework
 *
 * @package Taskerr\Foundation
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

class TR_Foundation {

	static $version = '5.2.2';
	static $url = '';

	public static function init() {
		self::$url = get_template_directory_uri() . '/foundation';
		add_action( 'wp_enqueue_scripts', array( 'TR_Foundation', 'register_styles' ), 9 );
		add_action( 'wp_enqueue_scripts', array( 'TR_Foundation', 'register_scripts' ), 9 );
	}

	public static function register_styles() {
		wp_register_style(
			'tr-normalize',
			self::$url . '/css/normalize.css',
			false,
			self::$version
		);
		wp_register_style(
			'tr-foundation-styles',
			self::$url . '/css/foundation.min.css',
			array( 'tr-normalize' ),
			self::$version
		);
	}

	public static function register_scripts() {
		wp_register_script(
			'tr-modernizr',
			self::$url . '/js/vendor/modernizr.js',
			array(),
			self::$version,
			false
		);
		wp_register_script(
			'tr-fastclick',
			self::$url . '/js/vendor/fastclick.js',
			array( 'jquery' ),
			self::$version,
			true
		);
		wp_register_script(
			'tr-foundation-scripts',
			self::$url . '/js/foundation.min.js',
			array( 'tr-modernizr', 'tr-fastclick' ),
			self::$version,
			true
		);
	}
}