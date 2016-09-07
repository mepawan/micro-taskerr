<?php
/**
 * Upgrade functions
 *
 * @package Taskerr\Admin\Upgrade
 * @author  AppThemes
 * @since   Taskerr 1.1
 */

add_action( 'appthemes_first_run', array( 'TR_Upgrade', 'init' ) );

/**
 * Class for controlling upgrade changes
 */
class TR_Upgrade {

	static $old_ver;
	static $new_ver;

	static function init() {

		list( $args ) = get_theme_support( 'app-versions' );
		if ( ! get_option( $args['option_key'] ) ) {
			return;
		}

		self::$old_ver = get_option( $args['option_key'] );
		self::$new_ver = $args['current_version'];

		if ( self::$old_ver !== self::$new_ver ) {
			self::version_actions();
		}
	}

	static protected function version_actions() {
		if ( version_compare( self::$old_ver, '1.1', '<' ) ) {
			self::upgrade_pages_1_1();
			self::upgrade_service_meta_1_1();
		}
	}

	static protected function upgrade_pages_1_1() {
		$plans_page_id = TR_Process_Service_Create::get_id();
		$plans_page = get_post( $plans_page_id );

		if ( ! $plans_page->post_content ) {
			$plans_page_content = html( 'h2 style="text-align: center;"', __( 'Select your plan &amp; pricing', APP_TD ) ) . "\r\n";
			$plans_page_content .= html( 'p style="text-align: center;"', __( "Each plan has its price, duration and set of features. Choose the one that suits you.", APP_TD ) );

			wp_update_post( array(
				'ID'           => $plans_page_id,
				'post_content' => $plans_page_content
			) );
		}
	}

	static protected function upgrade_service_meta_1_1() {
		$not_expiring = new WP_Query( array(
			'post_type'      => TR_SERVICE_PTYPE,
			'post_status'    => 'any',
			'nopaging'       => true,
				'meta_query' => array(
					array(
						'key'     => 'listing_duration',
						'value'   => 'trick',
						'compare' => 'NOT EXISTS',
					),
				),
		) );

		foreach ( $not_expiring->posts as $post ) {
			tr_update_service_duration( $post->ID );
		}
	}


}