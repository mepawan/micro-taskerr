<?php
/**
 * Handle tasks
 *
 * @package Taskerr\Tasks
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

define( 'TR_TASKS', 'tasks' );
define( 'TR_TASK_PENDING', 'pending' );
define( 'TR_TASK_PAID', 'paid' );
define( 'TR_TASK_COMPLETED', 'completed' );
define( 'TR_TASK_CONFIRMED', 'confirmed' );

add_action( 'init', 'tr_register_tasks' );

function tr_register_tasks(){
	p2p_register_connection_type( array(
		'name' => TR_TASKS,
		'from' => 'user',
		'to'   => TR_SERVICE_PTYPE,
		'prevent_duplicates' => false,
	) );
}

function tr_add_task( $user_id, $listing_id, $data ){
	global $post;

	$data = wp_parse_args( $data, array(
		'created_date' => current_time( 'mysql' ),
		'instructions' => '',
		'contacts'     => '',
		'price'        => '',
		// Needs for sorting Tasks and Purchases
		'provider'     => get_the_author_meta( 'display_name', $post->post_author ),
		'buyer'        => get_the_author_meta( 'display_name', $user_id ),
		'status'       => TR_TASK_PENDING
	) );
	return p2p_type( TR_TASKS )->connect( $user_id, $listing_id, $data );

}

function tr_remove_task( $user_id, $listing_id ){

	return p2p_type( TR_TASKS )->disconnect( $user_id, $listing_id );

}

function tr_get_tasks( $user_id, $listing_id ){

	$tasks = p2p_get_connections( TR_TASKS, array(
		'direction' => 'from',
		'from'      => $user_id,
		'to'        => $listing_id,
	) );
	return $tasks;

}

function tr_has_task( $user_id, $listing_id ){

	$count = p2p_get_connections( TR_TASKS, array(
		'direction' => 'from',
		'from'      => $user_id,
		'to'        => $listing_id,
		'fields'    => 'count',
	) );
	return (bool) $count;

}

function tr_has_pending_task( $user_id, $listing_id ){

	$connections = p2p_get_connections( TR_TASKS, array(
		'direction' => 'from',
		'from'      => $user_id,
		'to'        => $listing_id,
	) );

	$count = 0;
	foreach( $connections as $connection ){
		$status = p2p_get_meta( $connection->p2p_id, 'status', true );

		$pending_statuses = array( TR_TASK_PENDING, TR_TASK_PAID );
		if( in_array( $status, $pending_statuses ) ){
			$count++;
		}
	}

	return (bool) $count;

}

function tr_has_paid_task( $user_id, $listing_id ){

	$connections = p2p_get_connections( TR_TASKS, array(
		'direction' => 'from',
		'from'      => $user_id,
		'to'        => $listing_id,
	) );

	$count = 0;
	foreach( $connections as $connection ){
		$status = p2p_get_meta( $connection->p2p_id, 'status', true );

		$pending_statuses = array( TR_TASK_PAID, TR_TASK_COMPLETED, TR_TASK_CONFIRMED );
		if( in_array( $status, $pending_statuses ) ){
			$count++;
		}
	}

	return (bool) $count;

}

function get_the_task( $id = '' ){
	if( ! $id ) {
		$checkout = appthemes_get_checkout();
		if ( $checkout && $checkout->get_data('task_id') ) {
			$id = $checkout->get_data( 'task_id' );
		} else {
			$post = get_post();
			$id = $post->p2p_id;
		}
	}

	$data = p2p_get_connection( $id );
	$to   = ( $data ) ? $data->p2p_to   : '';
	$from = ( $data ) ? $data->p2p_from : '';

	return new TR_Task( $id, p2p_get_meta( $id ), $to, $from );
}

class TR_Task {

	protected $id, $service, $user, $created_date, $instructions, $price, $status;

	function __construct( $id, $data, $service, $user ){

		$this->id           = $id;
		$this->service      = $service;
		$this->user         = $user;
		$this->created_date = $data['created_date'][0];
		$this->instructions = $data['instructions'][0];
		$this->contacts     = $data['contacts'][0];
		$this->price        = $data['price'][0];
		$this->status       = $data['status'][0];

	}

	function get_id(){
		return $this->id;
	}

	function get_service(){
		return $this->service;
	}

	function get_service_author(){
		$service = get_post( $this->service );
		return $service->post_author;
	}

	function get_user(){
		return $this->user;
	}

	function get_created_date(){
		return $this->created_date;
	}

	function get_instructions(){
		return $this->instructions;
	}

	function get_contacts(){
		return $this->contacts;
	}

	function get_price(){
		return $this->price;
	}

	function get_status(){
		return $this->status;
	}

	function mark_pending(){
		return $this->mark( TR_TASK_PENDING );
	}

	function mark_paid(){
		return $this->mark( TR_TASK_PAID );
	}

	function mark_completed(){
		return $this->mark( TR_TASK_COMPLETED );
	}

	function mark_confirmed(){
		return $this->mark( TR_TASK_CONFIRMED );
	}

	protected function mark( $status ){
		return p2p_update_meta( $this->id, 'status', $status );
	}

}

/**
 * Used to construct and display summary table for a task
 */
class TR_Service_Purchase_Summary_Table extends APP_Table{

	protected $task, $currency;

	public function __construct( $task = '', $args = array() ){

		$this->task = $task;
		$this->args = wp_parse_args( $args, array(
			'wrapper_html'   => 'table',
			'header_wrapper' => 'thead',
			'body_wrapper'   => 'tbody',
			'footer_wrapper' => 'tfoot',
			'row_html'       => 'tr',
			'cell_html'      => 'td',
		) );

	}

	public function show( $attributes = array() ){
		// Items array intended for Service Extras provided by seller
		$items = array(
			array(
				'title' => get_the_title( $this->task->get_service() ),
				'price' => appthemes_get_price( $this->task->get_price() ),
			),
		);
		echo $this->table( $items, $attributes, $this->args );
	}

	protected function footer( $items ){

		$cells = array(
			__( 'Total', APP_TD ),
			appthemes_get_price( $this->task->get_price() ),
		);

		return html( $this->args['row_html'], array(), $this->cells( $cells, $this->args['cell_html'] ) );

	}

	protected function row( $item ){
		return html( $this->args['row_html'], array(), $this->cells( $item ) );
	}

}
