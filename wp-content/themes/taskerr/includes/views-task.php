<?php
/**
 * Views Task
 *
 * @package Taskerr\Views\Task
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Task Confirm checkout step class
 */
class TR_Task_Confirm extends APP_Checkout_Step{

	public function __construct(){
		$this->setup( 'task-confirm', array(
			'register_to' => 'add-task'
		) );
	}

	public function display( $task, $checkout ){

		appthemes_load_template( 'form-task.php', array(
			'action_text' => __( 'Confirm Purchase', APP_TD ),
			'action_url'  => appthemes_get_step_url(),
			'listing'     => tr_get_listing_obj(),
			'nonce_check' => $checkout->get_checkout_type(),
		) );
	}

	public function process( $task, $checkout ){
		$checkout->add_data( 'title', __( 'Create a Task', APP_TD ) );

		if ( ! isset( $_POST['action'] ) || 'add-task' !== $_POST['action'] ) {
			return;
		}

		check_admin_referer( $checkout->get_checkout_type() );

		$listing_id   = get_query_var( 'add_task' );
		$user_id      = get_current_user_id();
		$instructions = ( isset( $_POST['instructions'] ) ) ? stripslashes( nl2br( $_POST['instructions'] ) ) : '';
		$contacts     = ( isset( $_POST['contacts'] ) ) ? stripslashes( nl2br( $_POST['contacts'] ) ) : '';

		$task = tr_add_task( $user_id, $listing_id, array(
			'price'        => get_post_meta( $listing_id, 'price', true ),
			'instructions' => $instructions,
			'contacts'     => $contacts,
		) );

		if ( $task ) {
			$checkout->add_data( 'task_id', $task );
			do_action( 'tr_new_task_added', $task );
			$this->finish_step();
		}
	}
}

/**
 * Task Summary checkout step class
 */
class TR_Task_Summary extends APP_Checkout_Step{

	public function __construct(){
		$this->setup( 'task-summary', array(
			'register_to' => array(
				'add-task' => array( 'after' => 'task-confirm' ) ),
		) );
	}

	public function display( $task, $checkout ){
		appthemes_load_template( 'task-summary.php', array(
			'listing'  => tr_get_listing_obj( get_query_var( 'add_task' ) ),
		) );
	}

	public function process( $task, $checkout ){
		$checkout->add_data( 'title', __( 'Task Summary', APP_TD ) );
	}
}