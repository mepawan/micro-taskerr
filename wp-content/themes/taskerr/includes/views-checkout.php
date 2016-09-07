<?php
/**
 * Views Checkouts
 *
 * @package Taskerr\Views\Checkouts
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Select Plan Checkout step class
 */
class TR_Select_Plan extends APP_Checkout_Step {

	protected $errors;
	public function __construct(){

		parent::__construct( 'select-plan', array(
			'register_to' => array(
				'add-service',
				'renew-service',
			)
		) );

	}

	public function display( $order, $checkout ){
		global $tr_options;

		$plans = $this->get_available_plans();
		appthemes_load_template( 'purchase-service-new.php', array(
			'plans' => $plans,
			'tr_options' => $tr_options,
		) );
	}

	protected function get_available_plans() {

		$plans = new WP_Query( array(
			'post_type' => APPTHEMES_PRICE_PLAN_PTYPE,
			'nopaging' => 1,
		) );

		$plans_data = array();
		foreach ( $plans->posts as $key => $plan) {
			$plans_data[ $key ] = tr_get_plan_options( $plan->ID );
			$plans_data[ $key ]['post_data'] = $plan;
		}

		wp_reset_postdata();

		return $plans_data;
	}

	public function process( $order, $checkout ){
		global $tr_options;

		if ( ! $tr_options->service_charge ) {
			$this->finish_step();
		}

		$checkout->add_data( 'title', __( 'Select a Plan', APP_TD ) );
		appthemes_add_template_var( array( 'full_width' => true ) );

		if ( !isset( $_POST['action'] ) || 'purchase-service' != $_POST['action'] ) {
			return;
		}

		if ( !current_user_can( 'edit_services' ) ) {
			return;
		}

		$this->errors = apply_filters( 'appthemes_validate_purchase_fields', tr_get_service_error_obj() );

		$plan_id = $this->get_plan();
		$addons  = $this->get_addons();

		if ( $this->errors->get_error_codes() ){
			return false;
		}

		$checkout->add_data( 'plan', $plan_id );
		$checkout->add_data( 'addons', $addons );

		$this->finish_step();
	}

	protected function get_plan(){

		if( empty( $_POST['plan'] ) ){
			$this->errors->add( 'no-plan', __( 'No plan was chosen.', APP_TD ) );
			return false;
		}

		$plan = get_post( intval( $_POST['plan'] ) );
		if ( ! $plan ) {
			$this->errors->add( 'invalid-plan', __( 'The plan you choose no longer exists.', APP_TD ) );
			return false;
		}
		return $plan->ID ;
	}

	protected function get_addons(){

		$addons = array();
		foreach( tr_get_addons() as $addon ){

			if ( !empty( $_POST[ $addon.'_'.intval( $_POST['plan'] ) ] ) )
				$addons[] = $addon;

		}
		return $addons;
	}

}

class TR_Service_Edit extends APP_Checkout_Step {

	protected $errors, $required_fields;
	public function __construct(){

		parent::__construct( 'edit-service', array(
			'register_to' => array(
				'update-service',
			)
		) );

	}

	public function display( $order, $checkout ){

		the_post();

		appthemes_load_template( 'form-service.php', array(
			'action_text' => __( 'Save Service', APP_TD ),
			'action_url'  => appthemes_get_step_url(),
			'nonce_check' => $checkout->get_checkout_type(),
			'listing'     => $this->get_listing_obj(),
		) );
	}

	public function process( $order, $checkout ){
		$checkout->add_data( 'title', __( 'Edit Service Details', APP_TD ) );
		$service_listing = $this->process_form( $order, $checkout );
		if ( ! $service_listing ) {
			return;
		}

		do_action( 'tr_service_updated', $service_listing->ID, $order );

		wp_redirect( get_permalink( $service_listing ) );

	}

	public function process_form( $order, $checkout ){

		if ( ! isset( $_POST['action'] ) || 'update-service' !== $_POST['action'] ) {
			return;
		}

		add_filter( 'appthemes_validate_purchase_fields', array( $this, 'validator' ) );
		$this->required_fields = array(
			'post_title' => __( 'No title was submitted', APP_TD ),
			'post_content' => __( 'No content was submtited', APP_TD ),
			'delivery_time' => __( 'No delivery time was submitted', APP_TD ),
			'price' => __( 'No price was submitted', APP_TD ),
		);

		check_admin_referer( $checkout->get_checkout_type() );

		$post_id         = get_query_var( 'edit_listing' );
		$service_listing = $this->update_listing( $post_id );
		return $service_listing;
	}

	protected function update_listing( $post_id = '' ){

		$this->errors = apply_filters( 'appthemes_validate_purchase_fields', tr_get_service_error_obj() );
		if ( $this->errors->get_error_codes() ) {
			add_action( 'appthemes_notices', array( $this, 'display_errors' ) );
			return false;
		}

		$args = wp_array_slice_assoc( $_POST, array( 'post_title', 'post_content', 'tax_input' ) );
		$args['post_type'] = TR_SERVICE_PTYPE;

		if ( empty( $post_id ) ) {
			$id = wp_insert_post( $args );
		} else {
			$args['ID'] = $post_id;
			$id = wp_update_post( $args );
		}

		appthemes_handle_media_upload( $id );
		// TODO: Handle upload errors if needed
		/*
		$upload_errors = tr_handle_files( $id );
		if ( $upload_errors ) {
			if( empty( $post_id ) )
				wp_delete_post ( $id, true );
			return false;
		}*/

		foreach ( $this->get_special_fields() as $field ) {
			$value = $this->get_value( $field );
			update_post_meta( $id, $field, $value );
		}

		return apply_filters('tr_handle_update_listing', get_post( $id) );

	}

	protected function get_special_fields(){
		return tr_get_special_fields();
	}

	public function validator( $errors ){

		// Required fields should be filled
		foreach ( $this->required_fields as $field => $message ) {
			if( empty( $_REQUEST[ $field ] ) ) {
				$errors->add( 'missing-field', $message );
			}
		}

		// Category should be choosen
		if ( empty( $_REQUEST['tax_input']['service_cat'] ) ) {
			$errors->add( 'wrong-cat', __( 'No category was submitted.', APP_TD ) );
		}

		return $errors;

	}

	public function display_errors(){

		if ( $this->errors == null ) {
			return;
		}

		foreach ( $this->errors->get_error_messages() as $message ) {
			appthemes_display_notice( 'error', $message );
		}

	}

	/**
	 * Returns get_queried_object augmented with other listing fields:
	 * 	'price' => The listing's price
	 * 	'delivery_time' => The time for the listing to be delivered
	 * 	'category' => The category of the listing
	 * 	'tags' => A comma separated string of tags for the listing
	 */
	public function get_listing_obj(){
		return tr_get_listing_obj();
	}

	protected function get_value( $field, $default = '' ){

		if( isset( $_REQUEST[ $field ] ) ) {
			if ( is_array( $_REQUEST[ $field ] ) ) {
				return array_map( 'stripslashes', $_REQUEST[ $field ] );
			}
			return stripslashes( $_POST[ $field ] );
		} else {
			return $default;
		}
	}

	protected function sanitize( $value ) {
		return strip_tags( stripslashes( $value ) );
	}

	protected function moderate_listing( $listing ){
		$this->update_status( $listing, 'pending' );
	}

	protected function publish_listing( $listing ){
		$this->update_status( $listing, 'publish' );
	}

	protected function update_status( $listing, $new_status ){

		if ( is_object( $listing ) ) {
			$listing_id = $listing->ID;
		} else {
			$listing_id = (int) $listing;
		}

		wp_update_post( array(
			'ID' => $listing_id,
			'post_status' => $new_status
		) );
	}

}

class TR_Service_Add extends TR_Service_Edit {

	public function __construct(){

		$this->setup( 'edit-info', array(
			'register_to' => array(
				'add-service' => array( 'after' => 'select-plan' )
			)
		) );

	}

	public function display( $order, $checkout ){

		add_action( 'appthemes_notices', array( $this, 'display_errors' ) );

		the_post();

		appthemes_load_template( 'form-service.php', array(
			'action_text' => __( 'Next Step', APP_TD ),
			'action_url' => appthemes_get_step_url(),
			'nonce_check' => $checkout->get_checkout_type(),
			'listing' => $this->get_listing_obj(),
		) );

	}

	public function process( $order, $checkout ){
		global $tr_options;

		$checkout->add_data( 'title', __( 'Add Service Details', APP_TD ) );

		if ( ! isset( $_POST['action'] ) || 'update-service' !== $_POST['action'] ) {
			return;
		}

		if ( !current_user_can( 'edit_services' ) ) {
			return;
		}

		$service_listing = parent::process_form( $order, $checkout );

		if ( ! $service_listing ) {
			return;
		}

		$checkout->add_data( 'listing_id', $service_listing->ID );
		if ( $tr_options->service_charge ) {
			tr_add_plan_to_order( $order, $service_listing->ID, $checkout->get_data( 'plan' ) );
			tr_add_addons_to_order( $order, $service_listing->ID, $checkout->get_data( 'addons' ) );
			tr_add_order_description( $order, $service_listing->ID, $checkout->get_data( 'plan' ) );

			do_action( 'appthemes_create_order', $order );
			$this->finish_step();
			return;
		}

		tr_update_service_duration( $service_listing->ID );

		if ( $tr_options->moderate_services ){
			$this->moderate_listing( $service_listing );
			do_action( 'tr_new_service_added', $service_listing->ID, $order );
			$this->finish_step();
			return;
		}

		$this->publish_listing( $service_listing );
		do_action( 'tr_new_service_added', $service_listing->ID, $order );
		$this->finish_step();

	}

	public function get_listing_obj(){
		global $tr_options;

		require ABSPATH . '/wp-admin/includes/post.php';

		$listing = get_default_post_to_edit( TR_SERVICE_PTYPE );

		foreach ( array( 'post_title', 'post_content' ) as $field ) {
			$listing->$field = $this->get_value( $field, '' );
		}

		foreach ( $this->get_special_fields() as $field ) {
			$listing->$field = $this->get_value( $field, '' );
		}

		$listing->listing_duration = $tr_options->service_duration;

		return $listing;
	}

}

class TR_Service_Renew extends TR_Service_Add {

	public function __construct(){

		$this->setup( 'edit-info', array(
			'register_to' => array(
				'renew-service' => array( 'after' => 'select-plan' )
			)
		) );

	}

	public function get_listing_obj(){
		return tr_get_listing_obj();
	}

	public function process( $order, $checkout ){
		global $tr_options;

		$checkout->add_data( 'title', __( 'Edit Service Details', APP_TD ) );

		if ( ! isset( $_POST['action'] ) || 'update-service' !== $_POST['action'] ) {
			return;
		}

		if ( !current_user_can( 'edit_services' ) ) {
			return;
		}

		$service_listing = parent::process_form( $order, $checkout );

		if ( ! $service_listing ) {
			return;
		}

		$checkout->add_data( 'listing_id', $service_listing->ID );
		if ( $tr_options->service_charge ) {
			tr_add_plan_to_order( $order, $service_listing->ID, $checkout->get_data( 'plan' ) );
			tr_add_addons_to_order( $order, $service_listing->ID, $checkout->get_data( 'addons' ) );
			tr_add_order_description( $order, $service_listing->ID, $checkout->get_data( 'plan' ) );

			// Mark order is renewal
			update_post_meta( $order->get_id(), '_tr_is_renewal', 1 );

			do_action( 'appthemes_create_order', $order );
			$this->finish_step();
			return;
		}

		tr_update_service_duration( $service_listing->ID );

		$this->publish_listing( $service_listing );
		do_action( 'tr_service_renewed', $service_listing->ID, $order );
		$this->finish_step();

	}

}

class TR_Gateway_Select extends APP_Checkout_Step{

	public function __construct(){
		parent::__construct( 'gateway-select', array(
			'register_to' => array (
				'add-service'   => array( 'after' => 'edit-info' ),
				'renew-service' => array( 'after' => 'edit-info' )
				)
		) );
	}

	public function display( $order, $checkout ){

		query_posts( array( 'p' => $order->get_id(), 'post_type' => APPTHEMES_ORDER_PTYPE ) );
		appthemes_load_template( 'order-select.php' );

	}

	public function process( $order, $checkout ){
		global $tr_options;

		if ( ! $tr_options->service_charge ) {
			$this->finish_step();
			return;
		}

		$checkout->add_data( 'title', __( 'Select Gateway', APP_TD ) );

		if ( $order->get_total() == 0 ) {
			$order->complete();
			$this->finish_step();
		}

		if( ! empty( $_POST['payment_gateway'] ) ) {
			$is_valid = $order->set_gateway( $_POST['payment_gateway'] );
			if ( ! $is_valid ) {
				return;
			}

			$this->finish_step();
		}

	}

}

class TR_Gateway_Process extends APP_Checkout_Step{

	public function __construct() {
		parent::__construct( 'gateway-process', array(
			'register_to' => array(
				'add-service'   => array( 'after' => 'gateway-select' ),
				'renew-service' => array( 'after' => 'gateway-select' ),
			)
		) );
	}

	public function display( $order, $checkout ){
		query_posts( array(
			'p'         => $order->get_id(),
			'post_type' => APPTHEMES_ORDER_PTYPE
		) );
	}

	public function process( $order, $checkout ){
		global $tr_options;

		if ( ! $tr_options->service_charge ) {
			$this->finish_step();
			return;
		}

		$checkout->add_data( 'title', __( 'Order Process', APP_TD ) );
		update_post_meta( $order->get_id(), 'complete_url', appthemes_get_step_url( 'order-summary' ) );
		update_post_meta( $order->get_id(), 'cancel_url', appthemes_get_step_url( 'gateway-select' ) );
		wp_redirect( $order->get_return_url() );
		exit;
	}
}

class TR_Order_Summary extends APP_Checkout_Step{

	public function __construct(){
		parent::__construct( 'order-summary', array(
			'register_to' => array(
				'add-service'   => array( 'after' => 'gateway-process' ),
				'renew-service' => array( 'after' => 'gateway-process' ),
			),
		) );
	}

	public function display( $order, $checkout ){

		query_posts( array(
			'p' => $order->get_id(),
			'post_type' => APPTHEMES_ORDER_PTYPE
		) );
		appthemes_load_template( 'order-summary.php' );

	}

	public function process( $order, $checkout ){
		global $tr_options;

		if ( ! $tr_options->service_charge ) {
			$listing_id = $checkout->get_data( 'listing_id' );
			wp_redirect( get_post_permalink( $listing_id ) );
			exit;
		}

		$checkout->add_data( 'title', __( 'Order Summary', APP_TD ) );
	}
}


function tr_get_service_error_obj() {

	static $errors;

	if ( !$errors ) {
		$errors = new WP_Error();
	}

	return $errors;

}

function tr_get_addon_options( $addon ){
	global $tr_options;

	return array(
		'title'    => APP_Item_Registry::get_title( $addon ),
		'price'    => appthemes_get_price( APP_Item_Registry::get_meta( $addon, 'price' ) ),
		'duration' => $tr_options->addons[ $addon ]['duration']
	);

}

function tr_get_plan_options( $plan_id ){

	$data = get_post_custom( $plan_id );
	$collapsed_data = array();
	foreach ( $data as $key => $array ) {
		$collapsed_data[ $key ] = $array[0];
	}

	$collapsed_data['ID'] = $plan_id;

	return $collapsed_data;
}

function tr_add_plan_to_order( $order, $listing_id, $plan_id ){

	$plan = get_post( $plan_id );
	$plan_data = tr_get_plan_options( $plan_id );

	$item = $order->get_item( $plan->post_name );
	if ( $item ) {
		return;
	}

	$order->add_item( $plan->post_name, $plan_data['price'], $listing_id );
}

function tr_add_order_description( $order, $post_id, $plan_id = '' ) {
	$order_description_tags = array(
		'%post_title%',
		'%post_type%',
		'%plan_title%',
	);

	$order_description_tags = apply_filters( 'tr_order_description_tags', $order_description_tags );

	$order_description_tag_data = array();
	foreach( $order_description_tags as $order_description_tag ) {
		$order_description_tag_slug = str_ireplace( '%', '', $order_description_tag );

		$order_description_tag_value = apply_filters( '_tr_order_description_tag_' . $order_description_tag_slug, $order_description_tag, $order, $post_id, $plan_id );

		$order_description_tag_data[ $order_description_tag ] = $order_description_tag_value;
	}

	if ( !empty( $plan_id ) ) {
		$order_description = apply_filters( 'tr_order_description', __( '%post_type%: %post_title% - %plan_title%', APP_TD ) );
	} else {
		$order_description = apply_filters( 'tr_order_description', __( '%post_type%: %post_title%', APP_TD ) );
	}

	foreach ( $order_description_tag_data as $order_description_tag => $order_description_tag_value ) {
		$order_description = str_ireplace( $order_description_tag, $order_description_tag_value, $order_description );
	}

	$order->set_description( $order_description );
}

add_filter('_tr_order_description_tag_post_title', '_tr_order_description_tag_post_title', 10, 4 );
function _tr_order_description_tag_post_title( $order_description_tag, $order, $post_id, $plan_id ) {
	return get_the_title( $post_id );
}

add_filter('_tr_order_description_tag_post_type', '_tr_order_description_tag_post_type', 10, 4 );
function _tr_order_description_tag_post_type( $order_description_tag, $order, $post_id, $plan_id ) {
	return ucwords( get_post_type( $post_id ) );
}

add_filter('_tr_order_description_tag_plan_title', '_tr_order_description_tag_plan_title', 10, 4 );
function _tr_order_description_tag_plan_title( $order_description_tag, $order, $post_id, $plan_id ) {
	return !empty( $plan_id ) ? get_the_title( $plan_id ) : '';
}

function tr_add_addons_to_order( $order, $listing_id, $addons ){

	foreach ( $addons as $addon_id ) {

		if( get_post_meta( $listing_id, $addon_id, true ) )
			continue;

		$price = APP_Item_Registry::get_meta( $addon_id, 'price' );

		$order->add_item( $addon_id, $price, $listing_id );
	}

}

function _tr_no_featured_available( $plan ) {
	if ( empty($plan[TR_ITEM_FEATURED_HOME] ) && empty($plan[TR_ITEM_FEATURED_CAT] ) &&  empty($plan['disable_featured']) ) {
		if ( _tr_addon_disabled( TR_ITEM_FEATURED_HOME ) && _tr_addon_disabled( TR_ITEM_FEATURED_CAT )) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function _tr_show_featured_option( $addon, $enabled = false, $plan_id = ''){

	$name = $addon;
	if ( !empty( $plan_id ) ) {
		$name = $addon . '_' . $plan_id;
	}

	return html( 'input', array(
		'name' => $name,
		'id' => $name,
		'type' => 'checkbox',
		'disabled' => $enabled,
		'checked' => $enabled
	) );
}

function _tr_addon_disabled( $addon ){
	global $tr_options;
	return empty( $tr_options->addons[ $addon ]['enabled'] );
}

/**
 * Shows the field for an addon that can be purchased
 */
function _tr_show_purchasable_featured_addon( $addon_id, $plan_id ){

	$plan   = tr_get_plan_options( $plan_id );
	$addon  = tr_get_addon_options( $addon_id );
	$string = '';
	$option = '';

	if( ! empty( $plan[ $addon_id ] ) ){
		$option = _tr_show_featured_option( $addon_id, true, $plan_id );
		if ( $plan[ $addon_id . '_duration' ] == 0 ) {
			$string = sprintf( __( ' %s is included in this plan for Unlimited days.', APP_TD ), $addon['title'], $addon['price'] );
		} else {
			$string = sprintf( _n( '%s is included in this plan for %s day.', '%s is included in this plan for %s days.', $plan[ $addon_id . '_duration' ], APP_TD ), $addon['title'], $plan[ $addon_id . '_duration' ], $addon['price'] );
		}

	}
	else if( ! _tr_addon_disabled( $addon_id ) ){
		$option = _tr_show_featured_option( $addon_id, false, $plan_id );
		if ( $addon['duration'] == 0 ) {
			$string = sprintf( __( ' %s for Unlimited days for only %s more.', APP_TD ), $addon['title'], $addon['price'] );
		} else {
			$string = sprintf( __( ' %s for %d days for only %s more.', APP_TD ), $addon['title'], $addon['duration'], $addon['price'] );
		}

	}

	if ( $option || $string ) {
		echo html( 'div', $option . html( 'label', array( 'for' => $addon_id . '_' . $plan_id ), $string ) );
	}
}

function the_listing_tags_to_edit( $listing_id ) {

	$tags = array();

	if ( isset( $_POST['tax_input'] ) && isset( $_POST['tax_input'][ TR_SERVICE_TAG ] ) ) {
		$tags = $_POST['tax_input'][ TR_SERVICE_TAG ];
	} else {
		$tags = get_the_terms( $listing_id, TR_SERVICE_TAG );
		if ( $tags )
			$tags = implode( ', ', wp_list_pluck( $tags, 'name' ) );
	}

	if ( ! empty( $tags ) )
		echo esc_attr( $tags );
}
