<?php
/**
 * Installation functions
 *
 * @package Taskerr\Admin\Install
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

add_action( 'appthemes_first_run', 'tr_setup_services' );
add_filter( 'tr_setup_menu_page_ids', 'tr_setup_pages' );
add_action( 'appthemes_first_run', 'tr_setup_menu' );
add_action( 'appthemes_first_run', 'tr_setup_widgets' );

/**
 * Inserts default Services on installation
 */
function tr_setup_services(){

	$services = get_posts( array(
		'post_type' => TR_SERVICE_PTYPE,
		'posts_per_page' => 1
	) );
	if ( !empty( $services ) ) {
		return;
	}

	$category = appthemes_maybe_insert_term( 'Software', TR_SERVICE_CATEGORY );

	$service_id = wp_insert_post( array(
		'post_type' => TR_SERVICE_PTYPE,
		'post_status' => 'publish',
		'post_author' => get_current_user_id(),
		'post_title' => 'AppThemes',
		'post_content' => 'AppThemes is a fast growing company that employs talent from all around the world. Our diverse team consists of highly skilled WordPress developers, designers, and enthusiasts who come together to make awesome premium themes available in over two dozen different languages.',
		'tax_input' => array(
			TR_SERVICE_CATEGORY => array( $category['term_id'] ),
			TR_SERVICE_TAG => 'wordpress, themes',
		)
	) );

	$service_data = array(
		'price' => '99',
		'delivery_time' => '0'
	);

	foreach ( $service_data as $meta_key => $meta_value ) {
		update_post_meta( $service_id, $meta_key, $meta_value );
	}
}

/**
 * Inserts default Pages on installation
 */
function tr_setup_pages( $page_ids ) {

	list( $args ) = get_theme_support( 'app-versions' );

	// Install example data/options only once
	if ( get_option( $args['option_key'] ) ) {
		return $page_ids;
	}

	$page_content = '';
	$page_content .= html( 'h2 style="text-align: center;"', sprintf( __( 'Welcome to %s! The best site to buy and sell services!', APP_TD ), get_bloginfo('name') ) ) . "\r\n";
	$page_content .= html( 'h3', __( 'Buyers', APP_TD ) ) . "\r\n";
	$page_content .= html( 'p', __( "Look around! See something you like? Press <code>Buy</code> on the service and fill out the task request form. The provider will then contact you through email to discuss payment details. When the provider has received your payment, the task request will be marked as <code>Paid</code>.", APP_TD ) ) . "\r\n";
	$page_content .= html( 'p', __( "Finally, when the provider has finished the task, the task request will be marked as <code>Completed</code>. Make sure the task was preformed as asked, and then mark the request as <code>Confirmed</code>! Finally, you can give the service provider a rating to how awesome of a job they did!", APP_TD ) ) . "\r\n";
	$page_content .= html( 'h3', __( 'Sellers', APP_TD ) ) . "\r\n";
	$page_content .= html( 'p', __( "Post up your service for users to view. When a user decides they want to purchase your service, they will fill out a task request with any special instruction they'd like to give you. Both you and the buyer will be sent a confirmation email along with contact details. You can then contact the user with details on how they should transfer payment to you.", APP_TD ) ) . "\r\n";
	$page_content .= html( 'p', __( "After you have received payment from the buyer, mark the task request as <code>Paid</code>. Then, when you have completed work on the task, mark it as <code>Completed</code>. The user will verify that you have completed the task and mark it as <code>Confirmed</code>.", APP_TD ) ) . "\r\n";
	$page_content .= html( 'p style="text-align: center;"', html( 'strong', __( "That's all there is to it!", APP_TD ) ) );

	$page_id = wp_insert_post( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'post_author'    => get_current_user_id(),
		'post_title'     => __( 'How Things Work', APP_TD ),
		'post_content'   => $page_content,
        'comment_status' => 'closed',
	) );

    update_post_meta( $page_id, '_wp_page_template',  'page-full.php' );
	$page_ids[] = $page_id;

	return $page_ids;
}

/**
 * Inserts default Menus on installation
 */
function tr_setup_menu(){

	if( is_nav_menu( 'header' )){

		$locations = get_theme_mod( 'nav_menu_locations' );
		if( empty( $locations ) ){
			$menu_obj = wp_get_nav_menu_object( 'header' );
			$locations['header'] = $menu_obj->term_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		return;

	}

	$menu_id = wp_create_nav_menu( __( 'Header', APP_TD ) );
	if( is_wp_error( $menu_id ) ){
		return;
	}

	$page_ids = array(
		TR_Process_Service_Create::get_id(),
	);

	$page_ids = apply_filters( 'tr_setup_menu_page_ids', $page_ids );
	foreach( $page_ids as $page_id ){
		$page = get_post( $page_id );
		if ( !$page ) {
			continue;
		}

		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-type' => 'post_type',
			'menu-item-object' => 'page',
			'menu-item-object-id' => $page_id,
			'menu-item-title' => $page->post_title,
			'menu-item-url' => get_permalink( $page ),
			'menu-item-status' => 'publish',
		) );
	}

	$locations = get_theme_mod( 'nav_menu_locations' );
	$locations['header'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

}

/**
 * Inserts default Widgets on installation
 */
function tr_setup_widgets() {
	$sidebars_widgets = array (
		'tr-central-home' => array(
			'text' => array(
				'title' => '',
				'text' => '<div class="home-head row">' . "\n"
						.'<h2>' . __( 'Create <strong>MicroJob</strong> site in minutes!', APP_TD ) . '</h2>' . "\n"
						.'<p>' . sprintf( __( 'This stylized area, as well as a list of the categories below are not part of the home page layout. This is nothing more than a simple widgets. You can change them, delete or add new in the Widgets settings section "%1$s". If you want to add these widgets on all other pages, use section "%2$s". Happy Styling!', APP_TD ), __( 'Central Area - Home', APP_TD ), __( 'Central Area', APP_TD ) ) . '</p>' . "\n"
						.'<a class="button large alert" href="' . tr_get_service_create_url() . '">' . __( 'get started', APP_TD ) . '</a>' . "\n"
						.'</div>',
			),
			'tr_taxonomy_list' => array(
				'title' => '',
				'menu_cols' => 7,
				'menu_depth' => 3,
				'menu_sub_num' => 3,
				'cat_parent_count' => false,
				'cat_child_count' => false,
				'archive_responsive' => false,
				'taxonomy' => TR_SERVICE_CATEGORY,
			),
		),
		'tr-advert-top' => array(
			'text' => array(
				'title' => '',
				'text' => '<a href="#"><img src="' . get_template_directory_uri() . '/img/pic_banner.png" alt="' . __( 'Advertisement', APP_TD ) . '"></a>',
			),
		),
		'tr-advert-bottom' => array(
			'text' => array(
				'title' => '',
				'text' => '<a href="#"><img src="' . get_template_directory_uri() . '/img/pic_banner_2.png" alt="' . __( 'Advertisement', APP_TD ) . '"></a>',
			),
		),
	);

	list( $args ) = get_theme_support( 'app-versions' );

	// Install example widgets only once
	if ( get_option( $args['option_key'] ) ) {
		return;
	}

	appthemes_install_widgets( $sidebars_widgets );
}
