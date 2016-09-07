<?php
/**
 * Admin Pricing Metabox
 *
 * @package Taskerr\Admin\Pricing\Metaboxes
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Pricing General Box class
 */
class TR_Pricing_General_Box extends APP_Meta_Box {

	public function __construct(){
		parent::__construct( 'pricing-details', __( 'Pricing Details', APP_TD ), APPTHEMES_PRICE_PLAN_PTYPE, 'normal', 'high' );
	}

	public function admin_enqueue_scripts() {
		wp_enqueue_script( 'form-builder-helper', get_template_directory_uri() . '/framework/custom-forms/form-builder-helper.js', array( 'jquery' ), '20110909' );
	}

	public function before_form( $post ){
		?><style type="text/css">#notice{ display: none; }</style><?php
	}

	public function form_fields(){
		$plan_form =  array();

		$plan_form[] = array(
			'title' => __( 'Plan Name', APP_TD ),
			'type' => 'text',
			'name' => 'title',
		);

		$plan_form[] = array(
			'title' => __( 'Description', APP_TD ),
			'type' => 'textarea',
			'name' => 'description',
			'extra' => array(
				'style' => 'width: 25em;'
			)
		);

		$plan_form[] = array(
			'title' => __( 'Price', APP_TD ),
			'type' => 'text',
			'name' => 'price',
			'desc' => sprintf( __( 'Example: %s ' , APP_TD ), '1.00' ),
			'extra' => array(
				'style' => 'width: 50px;'
			)
		);

		$plan_form[] = array(
			'title' => __( 'Listing Duration', APP_TD ),
			'type' => 'text',
			'name' => 'duration',
			'desc' => __( 'days ( 0 = Infinite )', APP_TD),
			'extra' => array(
				'style' => 'width: 50px;'
			)
		);

		return $plan_form;
	}

	public function validate_post_data( $data, $post_id ){

		$errors = new WP_Error();

		if( empty( $data['title'] ) ){
			$errors->add( 'title', '' );
		}

		if( !is_numeric( $data['price'] ) ){
			$errors->add( 'price', '' );
		}

		if( !is_numeric( $data['duration'] ) ){
			$errors->add( 'duration', '' );
		}

		if( $data['duration'] < 0 ){
			$errors->add ('duration', '' );
		}

		return $errors;

	}

	public function before_save( $data, $post_id ){
		$data['duration'] = absint( $data['duration'] );
		return $data;
	}

	public function post_updated_messages( $messages ) {
		$messages[ APPTHEMES_PRICE_PLAN_PTYPE ] = array(
		 	1 => __( 'Plan updated.', APP_TD ),
		 	4 => __( 'Plan updated.', APP_TD ),
		 	6 => __( 'Plan created.', APP_TD ),
		 	7 => __( 'Plan saved.', APP_TD ),
		 	9 => __( 'Plan scheduled.', APP_TD ),
			10 => __( 'Plan draft updated.', APP_TD ),
		);
		return $messages;
	}

}

/**
 * Pricing Addons class
 */
class TR_Pricing_Addon_Box extends APP_Meta_Box {

	public function __construct(){
		parent::__construct( 'pricing-addons', __( 'Featured Addons', APP_TD ), APPTHEMES_PRICE_PLAN_PTYPE, 'normal', 'high' );
	}

	public function form_fields(){

		$output = array();

		foreach( APP_Addon_Registry::get_addons()  as $addon ){
			$addon_info = appthemes_get_addon_info( $addon );

			if( isset( $addon_info['title'] ) )
				$title = $addon_info['title'];
			else
				$title = $addon;

			$enabled = array(
				'title' => $title,
				'type' => 'checkbox',
				'name' => $addon,
				'desc' => __( 'Included', APP_TD ),
			);

			$duration = array(
				'title' => __( 'Duration', APP_TD ),
				'type' => 'text',
				'name' => $addon . '_duration',
				'desc' => __( 'days', APP_TD ),
				'extra' => array(
					'size' => '3'
				),
			);

			$output[] = $enabled;
			$output[] = $duration;

		}

		return $output;

	}

	public function before_save( $data, $post_id ){

		foreach( tr_get_addons() as $addon ){

			if( !empty( $data[ $addon ] ) && empty( $data[ $addon . '_duration' ] ) ){
				$data[ $addon . '_duration' ] = get_post_meta( $post_id, 'duration', true );
			}

			$data[ $addon . '_duration' ] = absint( $data[ $addon . '_duration' ] );

		}

		return $data;
	}

	public function validate_post_data( $data, $post_id ){
		$errors = new WP_Error();

		$project_project_duration = intval( get_post_meta( $post_id, 'duration', true ) );
		foreach( tr_get_addons() as $addon ){

			if( !empty( $data[ $addon . '_duration' ] ) ){

				$addon_duration = $data[ $addon . '_duration' ];
				if( !is_numeric( $addon_duration ) )
					$errors->add( $addon . '_duration', '' );

				if( intval( $addon_duration ) > $project_project_duration && $project_project_duration != 0 )
					$errors->add( $addon . '_duration', '' );

				if( intval( $addon_duration ) < 0 )
					$errors->add( $addon . '_duration', '' );

			}

		}

		return $errors;
	}

	public function before_form( $post ){
		echo html( 'p', array(), __( 'You can include featured addons in a plan. These will be immediately added to the listing upon purchase. After they run out, the customer can then purchase regular featured addons.', APP_TD ) );
	}


	public function after_form( $post ){
		echo html( 'p', array('class' => 'howto'), __( 'Durations must be shorter than the listing duration.', APP_TD ) );
	}

}