<?php
/**
 * Views
 *
 * @package Taskerr\Views
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * A View-class for controlling the Service post type archives and related tax archive pages
 *
 * Intended to register sorting types.
 */
class TR_Sorting_Services_Archives extends APP_View {
	function condition() {
		return ( is_post_type_archive( TR_SERVICE_PTYPE ) || is_tax( array( TR_SERVICE_CATEGORY, TR_SERVICE_TAG ) ) ) && ! is_admin();
	}

	function pre_get_posts( $wp_query ) {
		$wp_query->query_vars = array_merge( $wp_query->query_vars, tr_get_sorting_args() );
	}

	function parse_query( $wp_query ) {
		tr_register_sorting_type( 'TR_Sorting_Services' );
		tr_register_sorting_type( 'TR_Sorting_Order' );
	}

}

/**
 * A View-class for controlling only the Service post type archive pages
 */
class TR_Service_Archive extends APP_View {

	function condition(){
		return is_post_type_archive( TR_SERVICE_PTYPE ) && ! is_tax() && ! is_admin();
	}

	function parse_query( $wp_query ){
		global $wpdb, $tr_options;

		$wp_query->set( 'posts_per_page', $tr_options->listings_per_page );
		$wp_query->is_archive = true;
	}
}

/**
 * A View-page-class for controlling 'Add Service' page
 */
class TR_Process_Service_Create extends APP_View_Page {

	function __construct(){
		parent::__construct( 'add-service.php', __( 'Add Service', APP_TD ) );
	}

	static function get_id(){
		return parent::_get_page_id( 'add-service.php' );
	}

	function template_include( $path ){

		appthemes_require_login( array(
			'login_text'          => __( 'You must first login to add a service.', APP_TD ),
			'login_register_text' => __( 'You must first login or <a href="%s">register</a> to add a service.', APP_TD )
		) );

		if ( ! current_user_can( 'edit_services' ) ) {
			return locate_template( '404.php' );
		}


		appthemes_setup_checkout( 'add-service', get_permalink( self::get_id() ) );
		$step_found = appthemes_process_checkout();
		if ( ! $step_found ) {
			return locate_template( '404.php' );
		}

		return locate_template( 'add-service.php' );

	}

	function body_class($classes) {
		$classes[] = 'new-task';
		return $classes;
	}

	function template_redirect() {
		global $post;

		wp_enqueue_script(
			'tr-service-edit',
			get_template_directory_uri() . '/scripts/service-edit.js',
			array( 'validate', 'validate-lang', 'jquery-ui-sortable' ),
			TR_VERSION,
			true
		);

		wp_localize_script(
			'tr-service-edit',
			'TR_i18n',
			array(
				'clear'	  => __( 'Clear', APP_TD ),
			)
		);

		appthemes_enqueue_media_manager( array( 'post_id_field' => 'tr_post_id' ) );

		add_filter( 'body_class', array( $this, 'body_class' ), 99 );

		do_action( strtolower( __CLASS__ . '_' . __FUNCTION__ ) );
	}

}

/**
 * A Parent View-class for controlling post-centric pages
 */
class TR_Post_Centric_Page extends APP_View {

	protected $query_variable = '';
	protected $base_path = '';
	protected $action = '';

	function init() {
		global $wp;

		$wp->add_query_var( $this->query_variable );

		$path = $this->base_path . '/' . $this->action;
		appthemes_add_rewrite_rule( $path . '/(\d+)/?$', array(
			$this->query_variable => '$matches[1]',
		) );
	}

	function condition() {
		return (bool) get_query_var( $this->query_variable );
	}

}

/**
 * A View-class for controlling Edit service pseudo page
 */
class TR_Process_Service_Update extends TR_Post_Centric_Page {

	function __construct(){
		global $tr_options;

		parent::__construct();

		$this->query_variable = 'edit_listing';

		$this->base_path = $tr_options->service_permalink;
		$this->action = $tr_options->edit_service_permalink;

	}

	static function get_id(){
		return parent::_get_id( __CLASS__ );
	}

	function parse_query( $wp_query ) {
		$listing_id = $wp_query->get( $this->query_variable );

		if( ! current_user_can( 'edit_service', $listing_id ) ){
			wp_die( __( 'You do not have permission to edit that service', APP_TD ) );
		}

		$wp_query->is_home = false;

		$wp_query->query_vars = array_merge( $wp_query->query_vars, array(
			'post_type'   => TR_SERVICE_PTYPE,
			'post_status' => 'any',
			'post__in'    => array( $listing_id )
		) );

	}

	function the_posts( $posts, $wp_query ) {

		if( ! empty( $posts ) ) {
			$wp_query->queried_object = reset( $posts );
			$wp_query->queried_object_id = $wp_query->queried_object->ID;
		}

		return $posts;

	}

	function template_include( $path ){

		appthemes_require_login();

		appthemes_setup_checkout( 'update-service', $this->get_link() );
		$step_found = appthemes_process_checkout();
		if ( ! $step_found ) {
			return locate_template( '404.php' );
		}

		return locate_template( 'edit-service.php' );

	}

	static function get_link( $post_id = '' ) {
		global $wp_rewrite, $tr_options;

		$post = get_post( $post_id );
		if ( empty( $post ) ) {
			return;
		}

		if ( $wp_rewrite->using_permalinks() ) {
			$service_path = $tr_options->service_permalink;
			$edit_path = $tr_options->edit_service_permalink;
			return home_url( user_trailingslashit( "$service_path/$edit_path/{$post->ID}" ) );
		}

		return home_url( "?edit_listing={$post->ID}" );
	}

	function title_parts( $parts ) {
		return array( sprintf( __( 'Edit "%s"', APP_TD ), get_the_title( get_queried_object_id() ) ) );
	}

	function template_redirect() {
		global $post;
		wp_enqueue_script(
			'tr-service-edit',
			get_template_directory_uri() . '/scripts/service-edit.js',
			array( 'validate', 'validate-lang', 'jquery-ui-sortable' ),
			TR_VERSION,
			true
		);

		wp_localize_script(
			'tr-service-edit',
			'TR_i18n',
			array(
				'clear'	  => __( 'Clear', APP_TD ),
			)
		);

		appthemes_enqueue_media_manager( array( 'post_id_field' => 'tr_post_id' ) );

		add_filter( 'body_class', array( $this, 'body_class' ), 99 );

		do_action( strtolower( __CLASS__ . '_' . __FUNCTION__ ) );
	}

	function body_class($classes) {
		$classes[] = 'tr_listing_update';
		return $classes;
	}

}

/**
 * A View-class for controlling Renew service pseudo page
 */
class TR_Process_Service_Renew extends TR_Post_Centric_Page {

	function __construct(){
		global $tr_options;

		parent::__construct();

		$this->query_variable = 'renew_listing';

		$this->base_path = $tr_options->service_permalink;
		$this->action = $tr_options->renew_service_permalink;

	}

	function parse_query( $wp_query ) {
		$listing_id = $wp_query->get( $this->query_variable );

		if( ! current_user_can( 'edit_service', $listing_id ) ){
			wp_die( __( 'You do not have permission to edit that service', APP_TD ) );
		}

		$wp_query->is_home = false;

		$wp_query->query_vars = array_merge( $wp_query->query_vars, array(
			'post_type'   => TR_SERVICE_PTYPE,
			'post_status' => 'any',
			'post__in'    => array( $listing_id )
		) );

		if ( TR_SERVICE_STATUS_EXPIRED !=  get_post_status( $listing_id ) ) {
			wp_redirect( tr_get_service_edit_url( $listing_id ) );
			exit;
		}
	}

	function the_posts( $posts, $wp_query ) {

		if( ! empty( $posts ) ) {
			$wp_query->queried_object = reset( $posts );
			$wp_query->queried_object_id = $wp_query->queried_object->ID;
		}

		return $posts;

	}

	function template_include( $path ){

		appthemes_require_login();

		appthemes_setup_checkout( 'renew-service', $this->get_link() );
		$step_found = appthemes_process_checkout();
		if ( ! $step_found ) {
			return locate_template( '404.php' );
		}

		return locate_template( 'renew-service.php' );

	}

	static function get_link( $post_id = '' ) {
		global $wp_rewrite, $tr_options;

		$post = get_post( $post_id );
		if ( empty( $post ) ) {
			return;
		}

		if ( $wp_rewrite->using_permalinks() ) {
			$service_path = $tr_options->service_permalink;
			$renew_path = $tr_options->renew_service_permalink;
			return home_url( user_trailingslashit( "$service_path/$renew_path/{$post->ID}" ) );
		}

		return home_url( "?renew_listing={$post->ID}" );
	}

	function title_parts( $parts ) {
		return array( sprintf( __( 'Renew "%s"', APP_TD ), get_the_title( get_queried_object_id() ) ) );
	}

	function template_redirect() {
		global $post;
		wp_enqueue_script(
			'tr-service-edit',
			get_template_directory_uri() . '/scripts/service-edit.js',
			array( 'validate', 'validate-lang', 'jquery-ui-sortable' ),
			TR_VERSION,
			true
		);

		wp_localize_script(
			'tr-service-edit',
			'TR_i18n',
			array(
				'clear'	  => __( 'Clear', APP_TD ),
			)
		);

		appthemes_enqueue_media_manager( array( 'post_id_field' => 'tr_post_id' ) );

		add_filter( 'body_class', array( $this, 'body_class' ), 99 );

		do_action( strtolower( __CLASS__ . '_' . __FUNCTION__ ) );
	}

	function body_class($classes) {
		$classes[] = 'tr_listing_renew';
		return $classes;
	}

}

/**
 * A View-class for controlling Add Task pseudo page
 */
class TR_Process_Service_Order extends TR_Post_Centric_Page {

	function __construct(){
		global $tr_options;

		parent::__construct();

		$this->query_variable = 'add_task';

		$this->base_path = $tr_options->service_permalink;
		$this->action = $tr_options->add_task_permalink;

	}

	function parse_query( $wp_query ) {
		$listing_id = $wp_query->get( $this->query_variable );

		appthemes_require_login();

		if ( ! current_user_can( 'add_task', $listing_id ) ){
			wp_die( __( 'You do not have permission to order that service', APP_TD ) );
		}

		$wp_query->is_home = false;

		$wp_query->query_vars = array_merge( $wp_query->query_vars, array(
			'post_type' => TR_SERVICE_PTYPE,
			'post_status' => 'any',
			'post__in' => array( $listing_id )
		) );

	}

	function the_posts( $posts, $wp_query ) {

		if ( ! empty( $posts ) ) {
			$wp_query->queried_object = reset( $posts );
			$wp_query->queried_object_id = $wp_query->queried_object->ID;
		}

		return $posts;

	}

	function template_include( $path ){

		appthemes_require_login();

		appthemes_setup_checkout( 'add-task', $this->get_link() );
		$step_found = appthemes_process_checkout();
		if ( ! $step_found ) {
			return locate_template( '404.php' );
		}

		return locate_template( 'add-task.php' );

	}

	function title_parts( $parts ) {
		return array( sprintf( __( 'Order "%s"', APP_TD ), get_the_title( get_queried_object_id() ) ) );
	}

	function template_redirect() {

	}

	function body_class($classes) {
		$classes[] = 'tr_task';
		return $classes;
	}

	static function get_id(){
		return parent::_get_id( __CLASS__ );
	}

	static function get_link( $post_id = '' ) {
		global $wp_rewrite, $tr_options;

		$post = get_post( $post_id );
		if ( empty( $post ) ) {
			return;
		}

		if ( $wp_rewrite->using_permalinks() ) {
			$service_path = $tr_options->service_permalink;
			$order_path = $tr_options->add_task_permalink;
			return home_url( user_trailingslashit( "$service_path/$order_path/{$post->ID}" ) );
		}

		return home_url( "?add_task={$post->ID}" );
	}

}

/**
 * A View-class for controlling Renew service pseudo page
 */
class TR_Process_Service_Review extends TR_Post_Centric_Page {

	function __construct(){
		global $tr_options;

		parent::__construct();

		$this->query_variable = 'review_listing';

		$this->base_path = $tr_options->service_permalink;
		$this->action    = $tr_options->review_service_permalink;

	}

	static function get_id(){
		return parent::_get_id( __CLASS__ );
	}

	function template_redirect(){

		appthemes_reviews_enqueue_scripts();
		appthemes_reviews_enqueue_styles();
	}

	function parse_query( $wp_query ) {
		$listing_id = $wp_query->get( 'review_listing' );

		if ( ! current_user_can( 'add_review', $listing_id ) ){
			wp_die( __( 'You do not have permission to review this service.', APP_TD ) );
		}

		$wp_query->is_home = false;

		$wp_query->query_vars = array_merge( $wp_query->query_vars, array(
			'post_type'   => TR_SERVICE_PTYPE,
			'post_status' => 'any',
			'post__in'    => array( $listing_id )
		) );

	}

	function the_posts( $posts, $wp_query ) {

		if( ! empty( $posts ) ) {
			$wp_query->queried_object = reset( $posts );
			$wp_query->queried_object_id = $wp_query->queried_object->ID;
		}

		return $posts;

	}

	function template_include( $path ){

		appthemes_require_login();

		appthemes_setup_checkout( 'review-service', $this->get_link() );
		$step_found = appthemes_process_checkout();
		if ( ! $step_found ) {
			return locate_template( '404.php' );
		}

		return locate_template( 'review-service.php' );

	}

	static function get_link( $post_id = '' ) {
		global $wp_rewrite, $tr_options;

		$post = get_post( $post_id );
		if ( empty( $post ) ) {
			return;
		}

		if ( $wp_rewrite->using_permalinks() ) {
			$service_path = $tr_options->service_permalink;
			$edit_path    = $tr_options->review_service_permalink;
			return home_url( user_trailingslashit( "$service_path/$edit_path/{$post->ID}" ) );
		}

		return home_url( "?review_listing={$post->ID}" );
	}

	function title_parts( $parts ) {
		return array( sprintf( __( 'Review "%s"', APP_TD ), get_the_title( get_queried_object_id() ) ) );
	}

	function body_class($classes) {
		$classes[] = 'tr_listing_review';
		return $classes;
	}

}

/**
 * A View-page-class for controlling Front page
 */
class TR_Home extends APP_View_Page {

	function __construct() {
		parent::__construct( 'front-page.php', __( 'Home', APP_TD ) );

		add_action( 'appthemes_first_run', array( $this, 'setup' ), 9 );
	}

	function condition(){

		$page_id = (int) get_query_var( 'page_id' );

		return $page_id && $page_id == get_option( 'page_on_front' );
	}

	static function get_id(){
		return parent::_get_page_id( 'front-page.php' );
	}

	function setup(){
		list( $args ) = get_theme_support( 'app-versions' );

		// Set front-page only once
		if ( ! get_option( $args['option_key'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', self::get_id() );
			update_option( 'page_for_posts', TR_Blog_Archive::get_id() );
		}
	}

	function parse_query( $query ) {
		tr_register_sorting_type( 'TR_Sorting_Services' );
		tr_register_sorting_type( 'TR_Sorting_Order' );
		$query->set( 'paged', (int) get_query_var( 'page' ) );
	}

	function template_vars() {
		$nested_query = array(
			'post_type'     => TR_SERVICE_PTYPE,
			'paged'         => (int) get_query_var( 'page' ),
			'tr_front_page' => true,
		);

		return array(
			'nested_query' => array_merge( $nested_query, tr_get_sorting_args() ),
		);
	}

	function template_include( $path ){
		return get_page_template();
	}
}

/**
 * A View-page-class for controlling Blog archive
 */
class TR_Blog_Archive extends APP_View_Page {

	function __construct() {
		parent::__construct( 'index.php', __( 'Blog', APP_TD ) );
	}

	static function get_id() {
		return parent::_get_page_id( 'index.php' );
	}

	function condition(){
		return is_home();
	}

	function template_include( $path ) {
		if ( 'posts' == get_option( 'show_on_front' ) ) {
			$path = get_home_template();
		}
		return $path;
	}

}

/**
 * A View-page-class for controlling parent Dashboard page
 */
class TR_Dashboard_Home extends APP_View_Page {

	protected $type;

	function __construct( $template = null, $default_title = null ) {

		if ( ! $template ) {
			$template = 'dashboard-service.php';
		}

		$this->type = str_replace( array( 'dashboard-', '.php' ), '', $template );

		if ( ! $default_title ) {
			$default_title = __( 'Dashboard', APP_TD );
		}

		parent::__construct( $template, $default_title );

	}

	static function get_id(){
		return parent::_get_page_id( 'dashboard-service.php' );
	}

	function template_redirect(){
		// if not logged in, redirect to login page
		appthemes_auth_redirect_login();
		add_filter( 'body_class', array($this, 'body_class' ), 0 );
		// Since we use nested queries, we should care about adding template vars there too.
		add_action( 'get_template_part_loop-header-dashboard', array( $this, 'nested_query_vars' ) );
		// Do Nested Queries with Hooks
		add_action( 'appthemes_before_dashboard_loop', array( $this, 'before_loop' ) );
		add_action( 'appthemes_after_dashboard_loop', array( $this, 'after_loop' ) );

		wp_enqueue_script(
			'tr-dashboard',
			get_template_directory_uri() . '/scripts/dashboard.js',
			array( 'jquery', 'tr-foundation-scripts' ),
			TR_VERSION,
			true
		);
	}

	function nested_query_vars() {
		appthemes_add_template_var( $this->template_vars() );
	}

	function parse_query( $wp_query ) {
		tr_register_sorting_type( 'TR_Filter_Services' );
		tr_register_sorting_type( 'TR_Sorting_Services' );
		tr_register_sorting_type( 'TR_Sorting_Order' );
	}

	function before_loop( $type ) {
		global $current_user;

		$query = array(
			'post_type'   => TR_SERVICE_PTYPE,
			'author'      => $current_user->ID,
			'paged'       => get_query_var( 'paged' ),
			'post_status' => array( 'publish', 'pending', TR_SERVICE_STATUS_EXPIRED, 'draft' ),
		);

		$query = array_merge( $query, tr_get_sorting_args() );

		query_posts( $query );
	}

	function after_loop( $type ) {
		if ( is_archive() ) {
			wp_reset_query();
			wp_reset_postdata();
		}
	}

	function template_vars() {
		global $wp_query;

		return array(
			'dashboard_type' => $this->type,
			'total_entries'  => $wp_query->found_posts,
		);
	}

	protected function get_query_args() {
		return array();
	}

	function body_class($classes) {
		$classes[] = 'tr-dashboard';
		$classes[] = 'tr-dashboard-'. $this->type;

		return $classes;
	}
}

/**
 * A View-page-class for controlling Purchases Dashboard page
 */
class TR_Dashboard_Purchased extends TR_Dashboard_Home {

	function __construct() {
		add_action( 'appthemes_first_run', array( $this, 'set_parent' ), 99 );
		parent::__construct( 'dashboard-purchased.php', __( 'Purchased', APP_TD ) );
	}

	static function get_id(){
		return parent::_get_page_id( 'dashboard-purchased.php' );
	}

	function parse_query( $wp_query ) {
		tr_register_sorting_type( 'TR_Filter_Tasks' );
		tr_register_sorting_type( 'TR_Sort_Service_Purchases' );
		tr_register_sorting_type( 'TR_Sorting_Order' );
	}

	function before_loop( $type ) {
		global $current_user;

		$query = array(
			'connected_type'  => TR_TASKS,
			'connected_items' => $current_user,
			'post_status'     => array( 'publish' ),
			'post_type'       => TR_SERVICE_PTYPE,
			'paged'           => get_query_var( 'paged' ),
		);

		$query = array_merge( $query, tr_get_sorting_args() );

		query_posts( $query );
	}

	function set_parent() {
		$args = array(
			'ID'          => self::get_id(),
			'post_parent' => parent::get_id(),
		);
		wp_update_post( $args );
	}

}

/**
 * A View-page-class for controlling Tasks Dashboard page
 */
class TR_Dashboard_Tasks extends TR_Dashboard_Home {

	function __construct() {
		add_action( 'appthemes_first_run', array( $this, 'set_parent' ), 99 );
		parent::__construct( 'dashboard-tasks.php', __( 'Tasks', APP_TD ) );
	}

	static function get_id(){
		return parent::_get_page_id( 'dashboard-tasks.php' );
	}

	function parse_query( $wp_query ) {
		tr_register_sorting_type( 'TR_Filter_Tasks' );
		tr_register_sorting_type( 'TR_Sort_Tasks' );
		tr_register_sorting_type( 'TR_Sorting_Order' );
	}

	function before_loop( $type ) {
		global $current_user;

		$query = array(
			'connected_type' => TR_TASKS,
			'author'         => $current_user->ID,
			'post_status'    => array( 'publish' ),
			'post_type'      => TR_SERVICE_PTYPE,
			'paged'          => get_query_var( 'paged' ),
		);

		$query = array_merge( $query, tr_get_sorting_args() );

		query_posts( $query );
	}

	function set_parent() {
		$args = array(
			'ID' => self::get_id(),
			'post_parent' => parent::get_id(),
		);
		wp_update_post( $args );
	}
}

/**
 * A View-page-class for controlling Reviews Dashboard page
 */
class TR_Dashboard_Reviews extends TR_Dashboard_Home {

	private $error;

	function __construct() {
		add_action( 'appthemes_first_run', array( $this, 'set_parent' ), 99 );
		parent::__construct( 'dashboard-reviews.php', __( 'Reviews', APP_TD ) );
	}

	static function get_id(){
		return parent::_get_page_id( 'dashboard-reviews.php' );
	}

	function parse_query( $query ) {
		tr_register_sorting_type( 'TR_Filter_Reviews' );
		tr_register_sorting_type( 'TR_Sort_Reviews' );
		tr_register_sorting_type( 'TR_Sorting_Order' );
		$query->set( 'paged', (int) get_query_var( 'paged' ) );
	}

	function before_loop( $type ) {
		return false;
	}

	function template_vars() {
		global $wp_query, $current_user, $tr_options;

		$number = 10 /*get_option('comments_per_page')*/;
		$offset = ( get_query_var( 'paged' ) > 1 ) ? ( get_query_var( 'paged' ) - 1 ) * $number : 0;

		// We have to get all reviews bypassing 'number' and 'offset' parameters
		// just for get total number of reviews and pages through one query.
		$query_args    = array_merge( array( 'post_author' => $current_user->ID ), tr_get_sorting_args() );
		$query         = appthemes_get_reviews( $query_args );
		$max_num_pages = ceil( count( $query ) / $number );

		// Needs for pagination
		$wp_query->max_num_pages = $max_num_pages;

		return array(
			'dashboard_type' => $this->type,
			'comments_query' => array_slice( $query, $offset, $number ),
			'total_entries'  => count( $query )
		);
	}

	function init() {
		$this->handle_form();
	}

	private function handle_form() {
		if ( !isset( $_POST['action'] ) || 'dashboard-reviews' != $_POST['action'] )
			return;

		if ( empty($_POST) || !wp_verify_nonce($_POST['_wpnonce'],'tr-dashboard-reviews') ) {
			//nonce did not verify
			$this->error = __("There was an error. Please try again.", APP_TD );
		} else {
			// process form data
			// nonce did verify
			$review  = get_comment($_POST['review_id']);
			$user_id = get_current_user_id();
			if ($user_id == $review->user_id ) {
				tr_delete_review($_POST['review_id']);
				wp_redirect( './?deleted=true' );
				exit();
			} else {
				$this->error = __("Cannot delete review, it belongs to another user.", APP_TD );
			}
		}
	}

	function notices() {
		if ( !empty( $this->error ) ) {
			appthemes_display_notice( 'success-pending', $this->error );
		} elseif ( isset( $_GET['deleted'] ) ) {
			appthemes_display_notice( 'success', __( 'Review deleted.', APP_TD ) );
		}
	}

	function set_parent() {
		$args = array(
			'ID'          => self::get_id(),
			'post_parent' => parent::get_id(),
		);
		wp_update_post( $args );
	}
}

/**
 * A View-page-class for controlling Reviews Dashboard page
 */
class TR_Dashboard_Favorites extends TR_Dashboard_Home {

	function __construct() {
		add_action( 'appthemes_first_run', array( $this, 'set_parent' ), 99 );
		parent::__construct( 'dashboard-favorites.php', __( 'Favorites', APP_TD ) );
	}

	static function get_id(){
		return parent::_get_page_id( 'dashboard-favorites.php' );
	}

	function before_loop( $type ) {
		global $current_user;

		$query = array(
			'connected_type'  => TR_SERVICE_FAVORITES,
			'connected_items' => $current_user,
			'post_status'     => array( 'publish' ),
			'post_type'       => TR_SERVICE_PTYPE,
			'paged'           => get_query_var( 'paged' ),
		);

		$query = array_merge( $query, tr_get_sorting_args() );

		query_posts( $query );
	}

	function set_parent() {
		$args = array(
			'ID'          => self::get_id(),
			'post_parent' => parent::get_id(),
		);
		wp_update_post( $args );
	}
}

/**
 * A View-page-class for controlling Notifications Dashboard page
 */
class TR_Dashboard_Notifications extends TR_Dashboard_Home {

	function __construct() {
		add_action( 'appthemes_first_run', array( $this, 'set_parent' ), 99 );
		parent::__construct( 'dashboard-notifications.php', __( 'Notifications', APP_TD ) );
	}

	static function get_id(){
		return parent::_get_page_id( 'dashboard-notifications.php' );
	}

	function parse_query( $query ) {
		tr_register_sorting_type( 'TR_Filter_Notifications' );
		tr_register_sorting_type( 'TR_Sort_Notifications' );
		tr_register_sorting_type( 'TR_Sorting_Order' );
		$query->set( 'paged', (int) get_query_var( 'paged' ) );
	}

	function before_loop( $type ) {
		return false;
	}

	function template_vars() {
		global $wp_query, $current_user, $tr_options;

		$number = 20 /*get_option('comments_per_page')*/;
		$offset = ( get_query_var( 'paged' ) > 1 ) ? ( get_query_var( 'paged' ) - 1 ) * $number : 0;

		// We have to get all notifications bypassing 'number' and 'offset' parameters
		// just for get total number of notifications and pages through one query.
		$query_args    = array_merge( tr_get_sorting_args(), array( 'limit' => $number, 'offset' => $offset ) );
		$notifications = appthemes_get_notifications( $current_user->ID, $query_args );

		$max_num_pages = ceil( $notifications->found / $number );

		// Needs for pagination
		$wp_query->max_num_pages = $max_num_pages;

		// automatically mark all notifications as read on load
		foreach( $notifications->results as $notification ) {
			appthemes_set_notification_status( $notification->id, 'read' );
		}

		return array(
			'dashboard_type' => $this->type,
			'notifications'  => $notifications,
			'total_entries'  => $notifications->found,
		);
	}

	function init() {
		$this->handle_form();
	}

	protected function handle_form() {
		$notice = false;

		if ( !isset( $_POST['action'] )
			|| 'manage_notifications' != $_POST['action']
			|| empty( $_POST['bulk_delete'] )
			|| empty( $_POST['notification_id'] )
		) return;

		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'],'tr-dashboard-notifications' ) ) {
			//nonce did not verify
			$notice = __( 'There was an error. Please try again.', APP_TD );
		} else {
			// process form data
			// nonce did verify
			$notifications_ids = array_map( 'appthemes_filter', $_POST['notification_id'] );

			foreach( $notifications_ids as $notification_id ) {
				if ( ! appthemes_delete_notification( $notification_id ) )
					$notice = __('Some notifications could not be deleted.', APP_TD );
			}

			if ( ! $notice )
				appthemes_add_notice( 'delete-success', __( 'Selected notifications were deleted', APP_TD ), 'success' );
			else
				appthemes_add_notice( 'delete-error', $notice, APP_TD );
		}
	}

	function set_parent() {
		$args = array(
			'ID'          => self::get_id(),
			'post_parent' => parent::get_id(),
		);
		wp_update_post( $args );
	}
}

/**
 * A View-class for controlling Author archive
 */
class TR_Author extends APP_View {

	function condition() {
		return is_author();
	}

	function template_vars() {
		global $current_user, $wp_query;

		$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));

		if ( ! $curauth )
			return false;

		// Do pagination for custom comments loops
		$number = 10 /*get_option('comments_per_page')*/;
		$offset = ( get_query_var( 'paged' ) > 1 ) ? ( get_query_var( 'paged' ) - 1 ) * $number : 0;
		// We have to get all custom comments bypassing 'number' and 'offset' parameters
		// just for get total number of reviews and pages through one query.
		$reviews_query = appthemes_get_reviews( array( 'post_author' => $curauth->ID ) );


		$sections = apply_filters( 'tr_author_tabs', array(
			'services-current' => array(
				'args' => array(
					'post_type' => TR_SERVICE_PTYPE,
					'author'    => $curauth->ID,
					'paged'     => get_query_var( 'paged' ),
				),
				'name' => __( 'Current Services', APP_TD ),
				'loop' => TR_SERVICE_PTYPE,
			),
			'service-reviews' => array(
				'max_num_pages'  => ceil( count( $reviews_query ) / $number ),
				'comments_query' => array_slice( $reviews_query, $offset, $number ),
				'total_entries'  => count( $reviews_query ),
				'name'           => __( 'Reviews', APP_TD ),
				'loop'           => 'reviews',
			),
			'blog-posts' => array(
				'args'   => array(
					'post_type' => 'post',
					'author'    => $curauth->ID,
					'paged'     => get_query_var( 'paged' ),
				),
				'name' => __( 'Blog Posts', APP_TD ),
				'loop' => '',
			),
		) );

		return array(
			'curauth'          => $curauth,
			'tr_sections'      => $sections,
			'is_own_dashboard' => $curauth->ID === $current_user->ID,
		);
	}

	function template_redirect(){
		// Since we use nested queries, we should care about adding template vars there too.
		add_action( 'get_template_part_loop-header-dashboard', array( $this, 'nested_query_vars' ) );
	}

	function nested_query_vars() {
		appthemes_add_template_var( $this->template_vars() );
	}

	/**
	 * Fixes pagination for all post types and even comment types.
	 * Works in pair with pre_get_posts()
	 *
	 * @param array $pieces
	 * @return array
	 */
	function posts_clauses( $pieces ) {
		$pieces['limits'] = 'LIMIT 0, 1';
		return $pieces;
	}

	/**
	 * Adds all used post types in query to get at least one post
	 * to make pagination work
	 *
	 * @param type $query
	 */
	function pre_get_posts( $query ) {

		if ( ! $query->is_main_query() )
			return $query;

		$template_vars = $this->template_vars();

		if ( ! $template_vars )
			return $query;

		extract( $template_vars );

		if ( ! $curauth )
			return $query;

		// collect all post types to change main query and fix pagination
		$ptypes = array();
		foreach ( $tr_sections as $author_section ) {
			if ( isset( $author_section['args']['post_type'] ) )
				$ptypes[] = $author_section['args']['post_type'];
		}
		$query->set( 'post_type', array_unique( $ptypes ) );
	}
}