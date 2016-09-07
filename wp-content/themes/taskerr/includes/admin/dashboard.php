<?php
/**
 * Admin dashboard
 *
 * @package Taskerr\Admin\Dashboard
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Admin dashboard class
 */
class TR_Dashboard extends APP_Dashboard {

	public function __construct(){

		parent::__construct( array(
			'page_title' => __( 'Taskerr Dashboard', APP_TD ),
			'menu_title' => __( 'Taskerr', APP_TD ),
		) );

	}

}
