<?php
/**
 * Task Button functions
 *
 * @package Taskerr\TaskButton
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

add_action('init', 'tr_task_buttons_init', 13);

function tr_task_buttons_init() {
	$ajax_action = 'taskerr_task_update';

	add_action( 'wp_ajax_' . $ajax_action, 'tr_handle_ajax_task_update' );
	add_action( 'wp_ajax_nopriv_' . $ajax_action, 'tr_handle_ajax_task_update' );
}

/**
 * Handle favorites ajax requests
 */
function tr_handle_ajax_task_update() {
	if ( !isset( $_POST['task_action'] ) && !isset( $_POST['task_id'] ) && !isset( $_POST['current_url'] ) )
		return;

	if ( ! in_array( $_POST['task_action'], array('paid', 'unpaid', 'complete', 'uncomplete', 'confirm', 'unconfirm' ) ) )
		return;

	if ( ! is_user_logged_in() ) {
		return;
	}

	$task_id = (int) $_POST['task_id'];

	check_ajax_referer( "task-" . $task_id );

	$redirect = '';
	$status = 'success';

	$user_id = get_current_user_id();
	$task = get_the_task( $task_id );
	$updated = false;

	switch( $_POST['task_action'] ){

		case 'paid':
			if( $task->get_status() != TR_TASK_PENDING ){
				return;
			}
			$updated = $task->mark_paid();
			break;
		case 'unpaid':
			if( $task->get_status() != TR_TASK_PAID ){
				return;
			}
			$updated = $task->mark_pending();
			break;
		case 'complete':
			if( $task->get_status() != TR_TASK_PAID ){
				return;
			}
			$updated = $task->mark_completed();
			break;
		case 'uncomplete':
			if( $task->get_status() != TR_TASK_COMPLETED ){
				return;
			}
			$updated = $task->mark_paid();
			break;
		case 'confirm':
			if( $task->get_status() != TR_TASK_COMPLETED ){
				return;
			}
			$updated = $task->mark_confirmed();
			break;
		case 'unconfirm':
			if( $task->get_status() != TR_TASK_CONFIRMED ){
				return;
			}
			$updated = $task->mark_completed();
			break;
		default;
			break;

	}

	if ( $updated ) {
		do_action( 'tr_task_status_updated', $task->get_id(), $_POST['task_action'] );
	}

	tr_display_task_status( $task->get_id() );
	die;
}

/**
 * Return the current URL with additional query variables
 *
 * @param int    $task_id The task id
 * @param string $action The task action - valid options @see tr_handle_ajax_task_update()
 *
 * @return bool
 */
function tr_get_task_url( $task_id, $action ) {

	$args = array (
		'task_action' => $action,
		'task_id'     => $task_id,
		'ajax_nonce'  => wp_create_nonce( "task-" . $task_id ),
	);
	return add_query_arg( $args, home_url() );
}

function tr_display_task_action_button( $task_id = '', $action = 'paid', $text = '' ) {

	$button = html( 'a', array(
		'class' => 'task-button button success',
		'href'  => tr_get_task_url( $task_id, $action ),
		'rel'   => 'nofollow',
	),  $text );
	echo $button;
}

function tr_display_task_status( $task_id = '' ){

	$task = get_the_task( $task_id );

	$status  = '<span class="task-status button alert ' . $task->get_status() . '">';
	$status .= tr_get_task_status( $task );
	$status .= '</span>';

	echo $status;

	$actions = tr_get_task_actions( $task );
	foreach( $actions as $action => $text ){
		tr_display_task_action_button( $task->get_id(), $action, $text );
	}
}

function tr_get_task_status( $task ){

	$message = '';
	switch( $task->get_status() ){

		case TR_TASK_PENDING:
			$message = __( 'Pending Payment', APP_TD );
			break;
		case TR_TASK_PAID:
			$message = __( 'Payment Received', APP_TD );
			break;
		case TR_TASK_COMPLETED:
			$message = __( 'Task Completed', APP_TD );
			break;
		case TR_TASK_CONFIRMED:
			$message = __( 'Task Confirmed', APP_TD );
			break;
	}

	return $message;
}

function tr_get_task_actions( $task ){

	$actions = array();
	switch( $task->get_status() ){

		case TR_TASK_PENDING:
			if( current_user_can( 'paid_task', $task->get_id() ) ){
				$actions['paid'] = __( 'Mark as Paid', APP_TD );
			}
			break;
		case TR_TASK_PAID:
			if( current_user_can( 'paid_task', $task->get_id() ) ){
				$actions['unpaid'] = __( 'Mark as Unpaid', APP_TD );
			}
			if( current_user_can( 'complete_task', $task->get_id() ) ){
				$actions['complete'] = __( 'Mark as Completed', APP_TD );
			}
			break;
		case TR_TASK_COMPLETED:
			if( current_user_can( 'complete_task', $task->get_id() ) ){
				$actions['uncomplete'] = __( 'Mark as Uncompleted', APP_TD );
			}
			if( current_user_can( 'confirm_task', $task->get_id() ) ){
				$actions['confirm'] = __( 'Mark as Confirmed', APP_TD );
			}
			break;
		case TR_TASK_CONFIRMED:
			if( current_user_can( 'confirm_task', $task->get_id() ) ){
				$actions['unconfirm'] = __( 'Mark as Unconfirmed', APP_TD );
			}
			break;

	}

	return $actions;
}
