<?php
/**
 * Admin Single Service Metaboxes
 *
 * @package Taskerr\Admin\Metaboxes\Service
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

add_action( 'admin_init', 'tr_service_metaboxes' );

/**
 * Removes some standard metaboxes from Edit Service page in admin area
 */
function tr_service_metaboxes(){

	$disabled = array(
		'postexcerpt',
		'revisionsdiv',
		'postcustom',
	);

	foreach( $disabled as $id )
		remove_meta_box( $id, TR_SERVICE_PTYPE, 'normal' );

}

/**
 * Service Pricing Details metabox
 */
class TR_Service_Pricing extends APP_Meta_Box {

	public function __construct(){
		parent::__construct( 'service-price', __( 'Service Pricing Details', APP_TD ), TR_SERVICE_PTYPE, 'normal' );
	}

	public function form_fields(){

		return array(
			array(
				'title' => __( 'Cost for Service', APP_TD ),
				'type' => 'text',
				'name' => 'price',
				'extra' => array(
					'size' => 3
				)
			),
			array(
				'title' => __( 'Days until Delivery', APP_TD ),
				'type' => 'text',
				'name' => 'delivery_time',
				'extra' => array(
					'size' => 3
				)
			),
		);

	}

	public function validate_post_data( $data, $post_id ){

		$errors = new WP_Error();

		if( ! empty( $data['price'] ) ){

			if( ! is_numeric( $data['price'] ) ){
				$errors->add( 'price', __( 'Price must be numeric', APP_TD ) );
			}

			if( $data['price'] < 0 ){
				$errors->add( 'price', __( 'Price must be greater than or equal to zero.', APP_TD ) );
			}

		}

		if( ! empty( $data['delivery_time'] ) ){

			if( ! is_numeric( $data['delivery_time'] ) ){
				$errors->add( 'delivery_time', __( 'Delivery Time must be numeric', APP_TD ) );
			}

			if( $data['delivery_time'] < 0 ){
				$errors->add( 'delivery_time', __( 'Delivery Time must be greater than or equal to zero.', APP_TD ) );
			}

		}

		return $errors;

	}



}

/**
 * Summary tasks table
 */
class TR_Tasks_Summary_Table extends APP_Table {

	protected $log;
	public function __construct( $post ){
		$this->post = $post;
	}

	public function display(){

		$connections = p2p_get_connections( TR_TASKS, array(
			'to' => $this->post->ID,
		) );
		echo $this->table( $connections, array( 'class' => 'widefat tasks' ) );
	}

	public function header( $data ){

		$cells = $this->cells( array(
			__( 'Created Date', APP_TD ),
			__( 'User', APP_TD ),
			__( 'Status', APP_TD ),
			__( 'Special Instructions', APP_TD )
		), 'th' );

		return html( 'tr', array(), $cells );

	}

	public function footer( $data ){
		return $this->header( $data );
	}

	protected function row( $item ){

		$data = p2p_get_meta( $item->p2p_id );
		$user = get_userdata( $item->p2p_from );
		$cells = $this->cells( array(
			$data['created_date'][0],
			$user->display_name,
			$data['status'][0],
			$data['instructions'][0]
		) );

		return html( 'tr', array(), $cells );

	}

}

/**
 * Tasks Summary metabox
 */
class TR_Tasks_Summary extends APP_Meta_Box {

	public function __construct(){
		parent::__construct( 'tasks', __( 'Tasks', APP_TD ), TR_SERVICE_PTYPE, 'normal' );
	}

	public function display( $post ){
		$table = new TR_Tasks_Summary_Table( $post );
		$table->display();
	}

}

/**
 * Listing Pricing metabox
 */
class TR_Listing_Pricing extends APP_Meta_Box {

	public function __construct(){
		parent::__construct( 'listing-pricing', __( 'Listing Pricing Details', APP_TD ), TR_SERVICE_PTYPE, 'normal', 'low' );
	}

	public function admin_enqueue_scripts(){
		if( is_admin() ){
			wp_enqueue_style('jquery-ui-style');

			wp_enqueue_script(
				'tr-addons-metabox',
				get_template_directory_uri() . '/includes/admin/scripts/addons.js',
				array( 'jquery-ui-datepicker-lang', 'jquery-ui-datepicker' ),
				TR_VERSION,
				true
			);

			wp_localize_script( 'tr-addons-metabox', 'trLabels', array(
				'Never'      => __( 'Never', APP_TD ),
				'dateFormat' => _x( 'mm/dd/yy', 'Datepicker default date format, see: http://goo.gl/6MWmLK', APP_TD ),
			) );

			// Now add listing info fields as usual addon
			$addons_info = $this->get_listing_info();
			foreach ( tr_get_addons() as $addon_type ) {
				$addon_info = appthemes_get_addon_info( $addon_type );
				$addons_info[ $addon_info['flag_key'] ] = $addon_info;
			}
			wp_localize_script( 'tr-addons-metabox', 'trAddons', $addons_info );
		}
	}

	protected function get_listing_info() {
		return array(
			'_blank' => array(
				'title' => __( 'Listing', APP_TD ),
				'flag_key' => '_blank',
				'duration_key' => 'listing_duration',
				'start_date_key' => 'listing_start_date',
			),
		);
	}

	public function before_display( $form_data, $post ){
		$form_data['listing_start_date'] = $post->post_date;
		return $form_data;
	}

	public function before_form( $post ) {
		echo html( 'p', __( 'These settings allow you to override the defaults that have been applied to the listings based on the plan the owner chose. They will apply until the listing expires.', APP_TD ) );
	}

	public function form_fields(){

		$listing = $this->get_listing_info();
		$output = $this->addon_fields( (object) $listing['_blank'] );
		$addons = tr_get_addons();

		foreach( $addons as $addon_type ){
			$addon = (object) appthemes_get_addon_info( $addon_type );
			$addon_fields = $this->addon_fields( $addon );
			$output = array_merge( $output, $addon_fields );
		}

		return $output;
	}

	protected function addon_fields( $addon ) {
		return array(
			array(
				'title' => $addon->title,
				'type' => 'checkbox',
				'name' => $addon->flag_key,
				'desc' => __( 'Yes', APP_TD ),
				'extra' => array(
					'id' => $addon->flag_key,
					'class' => 'enable-addon',
				),
			),
			array(
				'title' => __( 'Duration', APP_TD ),
				'desc' => __( 'days (0 = Infinite)', APP_TD ),
				'type' => 'text',
				'name' => $addon->duration_key,
				'extra' => array(
					'size' => '3'
				),
			),
			array(
				'title' => __( 'Start Date', APP_TD ),
				'type' => 'custom',
				'name' => $addon->start_date_key,
				'render' => array('TR_Date_Field_Type', '__render'),
				'sanitize' => array('TR_Date_Field_Type', '__sanitize'),
			),
			array(
				'title' => __( 'Expires on', APP_TD ),
				'type' => 'text',
				'name' => '_blank',
				'extra' => array(
					'style' => 'background-color: #EEEEEF;',
					'id' => '_blank_expire_' . $addon->flag_key,
				)
			),
		);
	}

	function before_save( $data, $post_id ){
		global $tr_options;

		unset( $data['_blank'] );
		unset( $data['listing_start_date'] );

		$addons = tr_get_addons();

		foreach( $addons as $addon_type ){

			$addon = (object) appthemes_get_addon_info( $addon_type );

			if( $data[ $addon->flag_key ] ){

				if( $data[ $addon->duration_key ] !== '0' && empty( $data[ $addon->duration_key ] ) ){
					$data[ $addon->duration_key ] = $tr_options->addons[ $addon_type ]['duration'];
				}

				if ( empty( $data[ $addon->start_date_key ] ) ){
					$data[ $addon->start_date_key ] = current_time( 'mysql' );
				}

			} else {
				$data[ $addon->duration_key ] = '';
				$data[ $addon->start_date_key ] = '';
			}
		}

		return $data;
	}
}

/**
 * Date field custom controller
 */
class TR_Date_Field_Type {

	static function __render( $value, $inst ) {
		$output = '';
		// Hidden field
		$hidden = array(
			'name' => $inst->name,
			'type' => 'hidden',
			'value' => ( ! $value ) ? $value : '',
			'extra' => array( 'class' => 'alt-date-field' ),
		);

		if ( ! isset( $inst->extra ) )
			$inst->extra = array();

		if ( ! isset( $inst->extra['id'] ) && ! is_array( $inst->name ) && false === strpos( $inst->name, '[' ) )
			$hidden['extra']['id'] = $inst->name;

		// Front field
		$args = array(
			'name' => '_blank_' . $inst->name,
			'id' => '_blank_' . $hidden['extra']['id'],
			'value' => '',
			'desc' => $inst->desc,
			'desc_pos' => 'after',
			'extra' => $inst->extra,
			'type' => 'text',
		);

		$output = scbForms::input_with_value( $args, $value );
		$output .= scbForms::input_with_value( $hidden, $value );
		return $output;
	}

	static function __sanitize( $value, $inst ) {
		if ( ! empty( $value ) )
			return date('Y-m-d H:i:s', strtotime( $value ) );
	}
}

/**
 * Service Media metabox
 */
class TR_Service_Media extends APP_Media_Manager_Metabox {

	public function __construct( $id, $title, $post_type, $context = 'normal', $priority = 'default' ) {
		parent::__construct( $id, $title, $post_type, $context, $priority );
	}

	function display( $post ) {
		tr_the_images_manager( $post->ID );
		tr_the_videos_manager( $post->ID );
		tr_the_embeds_manager( $post->ID );
	}
}