<?php
/**
 * Views Reviews
 *
 * @package Taskerr\Views\Reviews
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Service Rate and Review checkout step class
 */
class TR_Service_Rate extends APP_Checkout_Step{

	protected $errors;
	public function __construct(){

		parent::__construct( 'rate-service', array(
			'register_to' => array(
				'review-service',
			)
		) );

		add_filter( 'appthemes_handle_review', array( __CLASS__, 'handle_submit' ) );
		add_filter( 'appthemes_validate_review', array( __CLASS__, 'validate_submit' ), 10, 2 );
	}

	public function display( $order, $checkout ){

		appthemes_load_template( 'form-review.php', array(
			'action_text' => __( 'Submit Review', APP_TD ),
			'action_url'  => appthemes_get_step_url(),
			'nonce_check' => $checkout->get_checkout_type(),
			'listing'     => tr_get_listing_obj(),
		) );

	}

	public function process( $task, $checkout ){
		$checkout->add_data( 'title', __( 'Review Service', APP_TD ) );
	}

	public static function validate_submit( $errors, $post_id ){

		if ( ! current_user_can( 'add_review', $post_id ) ) {
			$errors->add( 'bad-permissions', 'You cannot add a review to this listing right now.' );
		}

		return $errors;
	}

	public static function handle_submit( $data ){

		if ( empty( $_POST['review_rating'] ) ) {
			return false;
		}

		$rating = floatval( trim( $_POST['review_rating'] ) );
		$data   = array(
			'rating' => $rating,
			'meta'   => array()
		);

		return $data;

	}

}
