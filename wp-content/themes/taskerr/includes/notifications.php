<?php
/**
 * Email and Dashboard Notifications
 *
 * @package Taskerr\Notifications
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

// New service has been posted
add_action( 'tr_new_service_added', 'tr_new_service_notify_admin', 15, 2 );
add_action( 'tr_new_service_added', 'tr_new_service_notify_author', 15, 2 );

// Existing service has been renewed
add_action( 'tr_service_renewed', 'tr_service_renewed_notify_admin', 15, 2 );
add_action( 'tr_service_renewed', 'tr_service_renewed_notify_author', 15, 2 );

// New Bank Transfer order notification to user
add_filter( 'appthemes_email_admin_bt_pending', 'tr_bt_pending_notify_author', 10, 2 );

// Service has been approved or expired
add_action( 'transition_post_status', 'tr_send_status_change_notifications', 10, 3 );

// New Task has been added or updated
add_action( 'tr_new_task_added', 'tr_task_action_notify', 10, 2 );
add_action( 'tr_task_status_updated', 'tr_task_action_notify', 10, 2 );

// New Review has been submitted
add_action( 'appthemes_new_post_review', 'tr_new_service_review_notify' );


/**
 * Notify admins on new posted service
 * Email Only
 *
 * @global scbOptions $tr_options
 * @param int $post_id
 * @param APP_Order $order
 */
function tr_new_service_notify_admin( $post_id, $order = '' ) {
	global $tr_options, $wp_query;

	$post = get_post( $post_id );

	if ( ! $tr_options->notify_new_services || ( 'pending' !== $post->post_status && 'publish' !== $post->post_status ) ) {
		return;
	}

	$content = '';

	switch( $post->post_status) {
		case 'pending':
			$subject = sprintf( __( '[%1$s] New Service Awaiting Moderation: %2$s', APP_TD ), get_bloginfo( 'name' ), get_the_title( $post ) );
			break;
		case 'publish':
			$subject = sprintf( __( '[%1$s] New Service Published: %2$s', APP_TD ), get_bloginfo( 'name' ), get_the_title( $post ) );
			if ( $tr_options->service_charge ) {
				$content = _tr_order_summary_email_body( $order, $post );
			}
			break;
	}

	$to = get_option( 'admin_email' );
	$recipient = get_user_by( 'email', $to );

	$wp_query->set( 'email_name', 'new_service_admin' );
	$wp_query->set( 'service', $post );
	$wp_query->set( 'recipient', $recipient );

	appthemes_send_email( $to, $subject, $content );
}

/**
 * Notify admins on renewed service
 * Email Only
 *
 * @global scbOptions $tr_options
 * @param int $post_id
 * @param APP_Order $order
 */
function tr_service_renewed_notify_admin( $post_id, $order = '' ) {
	global $tr_options, $wp_query;

	$post = get_post( $post_id );

	if ( ! $tr_options->notify_new_services || 'publish' !== $post->post_status ) {
		return;
	}

	$content = '';

	$subject = sprintf( __( '[%1$s] Service Renewed: %2$s', APP_TD ), get_bloginfo( 'name' ), get_the_title( $post ) );
	if ( $tr_options->service_charge ) {
		$content = _tr_order_summary_email_body( $order, $post );
	}

	$to = get_option( 'admin_email' );
	$recipient = get_user_by( 'email', $to );

	$wp_query->set( 'email_name', 'service_renewed_admin' );
	$wp_query->set( 'service', $post );
	$wp_query->set( 'recipient', $recipient );

	appthemes_send_email( $to, $subject, $content );
}

/**
 * Notify author on his new posted service
 * Notification + Email
 */
function tr_new_service_notify_author( $post_id, $order = '' ) {
	global $wp_query, $tr_options;

	$post = get_post( $post_id );

	$recipient = get_user_by( 'id', $post->post_author );

	$service_link = html_link( get_permalink( $post ), get_the_title($post) );

	$content = '';

	switch( $post->post_status) {
		case 'pending':
			$subject = sprintf( __( "Your Service - %s - was submitted and is awaiting moderation", APP_TD ), $service_link );
			break;

		case 'publish':
			$subject = sprintf( __( "Your Service - %s - was submitted and is now live!", APP_TD ), $service_link );
			if ( $tr_options->service_charge ) {
				$content = _tr_order_summary_email_body( $order, $post );
			}
			break;
	}

	$email_name = 'new_service_author';
	$send_mail = array();
	$user_option = get_user_meta( $recipient->ID, 'notifications', true );

	// If user notification settings is empty then send email by default
	// If user notificationsettings is not empty then check apropriate option
	if ( empty( $user_option ) || in_array( $email_name, $user_option ) ) {
		$wp_query->set( 'email_name', $email_name );
		$wp_query->set( 'service', $post );
		$wp_query->set( 'recipient', $recipient );
		if ( $tr_options->service_charge ) {
			$wp_query->set( 'service_order', $order );
		}
		$send_mail = array( 'content' => $content );
	}

	$meta = array( 'subject' => wp_strip_all_tags( $subject ) );

	appthemes_send_notification( $recipient->ID, $subject, 'notification', $meta, array( 'send_mail' => $send_mail ) );
}

/**
 * Notify author on his renewed service
 * Notification + Email
 */
function tr_service_renewed_notify_author( $post_id, $order = '' ) {
	global $wp_query, $tr_options;

	$post = get_post( $post_id );

	$recipient = get_user_by( 'id', $post->post_author );

	$service_link = html_link( get_permalink( $post ), get_the_title($post) );

	$content = '';

	$subject = sprintf( __( "Your Service - %s - was renewed and is now live!", APP_TD ), $service_link );
	if ( $tr_options->service_charge ) {
		$content = _tr_order_summary_email_body( $order, $post );
	}

	$email_name = 'service_renewed_author';
	$send_mail = array();
	$user_option = get_user_meta( $recipient->ID, 'notifications', true );

	// If user notification settings is empty then send email by default
	// If user notificationsettings is not empty then check apropriate option
	if ( empty( $user_option ) || in_array( $email_name, $user_option ) ) {
		$wp_query->set( 'email_name', $email_name );
		$wp_query->set( 'service', $post );
		$wp_query->set( 'recipient', $recipient );
		if ( $tr_options->service_charge ) {
			$wp_query->set( 'service_order', $order );
		}
		$send_mail = array( 'content' => $content );
	}

	$meta = array( 'subject' => wp_strip_all_tags( $subject ) );

	appthemes_send_notification( $recipient->ID, $subject, 'notification', $meta, array( 'send_mail' => $send_mail ) );
}

/**
 * Notify author on his new bank transfer order
 *
 * Uses filter `appthemes_email_admin_bt_pending` to get right event of creating notification
 *
 * @global type $wp_query
 * @global scbOptions $tr_options
 * @param type $email
 * @param type $post
 * @return string Admin bt pending email text
 */
function tr_bt_pending_notify_author( $email, $post ) {
	global $wp_query, $tr_options;

	$order = appthemes_get_order( $post->ID );
	$item = $order->get_item();

	if ( ! isset( $item['post_id'] ) ) {
		return;
	}

	$recipient = get_user_by( 'id', $order->get_author() );
	$service = get_post( $item['post_id'] );

	$service_link = html_link( get_permalink( $service ), get_the_title($service) );
	$subject = sprintf( __( "Your Service - %s - was submitted and is awaiting payment", APP_TD ), $service_link );

	$content = _tr_order_summary_email_body( $order, $service );
	$service->post_status = 'pending-payment';

	$email_name = 'new_service_author';
	$send_mail = array();
	$user_option = get_user_meta( $recipient->ID, 'notifications', true );

	// If user notification settings is empty then send email by default
	// If user notificationsettings is not empty then check apropriate option
	if ( empty( $user_option ) || in_array( $email_name, $user_option ) ) {
		$wp_query->set( 'email_name', $email_name );
		$wp_query->set( 'service', $service );
		$wp_query->set( 'recipient', $recipient );
		$wp_query->set( 'service_order', $order );
		$send_mail = array( 'content' => $content );
	}

	$meta = array( 'subject' => wp_strip_all_tags( $subject ) );

	appthemes_send_notification( $recipient->ID, $subject, 'notification', $meta, array( 'send_mail' => $send_mail ) );

	// return to original message
	$wp_query->set( 'email_name', 'new_order_admin' );
	$wp_query->set( 'service', $service );
	$wp_query->set( 'recipient', get_user_by( 'email', $email['to'] ) );
	$wp_query->set( 'content', $email['message'] );
	$wp_query->set( 'service_order', $order );

	return $email;
}

/**
 * Notify Buyer and Provider about new tasks and task status updates
 *
 * @param int $task_id
 * @param string $action
 */
function tr_task_action_notify( $task_id, $action = 'created' ) {
	global $wp_query;

	$task = get_the_task( $task_id );
	$service = get_post( $task->get_service() );
	$buyer = get_user_by( 'id', $task->get_user() );
	$provider = get_user_by( 'id', $task->get_service_author() );

	$buyer_link = html_link( get_author_posts_url( $buyer->ID, $buyer->user_nicename ), $buyer->display_name );
	$provider_link = html_link( get_author_posts_url( $provider->ID, $provider->user_nicename ), $provider->display_name );

	$tasks_link = html_link( tr_get_dashboard_tasks_url(), get_the_title( $service ) );
	$purchases_link = html_link( tr_get_dashboard_purchased_url(), get_the_title( $service ) );

	$task_link = html_link( tr_get_dashboard_tasks_url(), $task_id );
	$purchase_link = html_link( tr_get_dashboard_purchased_url(), $task_id );

	$buyer_notify_subject = '';
	$provider_notify_subject = '';

	$content = '';

	switch( $action ){
		case 'created':
			$buyer_notify_subject = sprintf( __( 'You have ordered a service - %s', APP_TD ), $purchases_link );
			$provider_notify_subject = sprintf( __( 'User %1$s has just ordered your service - %2$s', APP_TD ), $buyer_link, $tasks_link );
			break;
		case 'paid':
			$buyer_notify_subject = sprintf( __( '%1$s has marked task #%2$s - as Paid', APP_TD ), $provider_link, $purchase_link );
			$provider_notify_subject = sprintf( __( 'You have marked task #%s - as Paid', APP_TD ), $task_link );
			break;
		case 'unpaid':
			$buyer_notify_subject = sprintf( __( '%1$s has marked task #%2$s - as Unpaid', APP_TD ), $provider_link, $purchase_link );
			$provider_notify_subject = sprintf( __( 'You have marked task #%s - as Unpaid', APP_TD ), $task_link );
			break;
		case 'complete':
			$buyer_notify_subject = sprintf( __( '%1$s has marked task #%2$s - as Completed', APP_TD ), $provider_link, $purchase_link );
			$provider_notify_subject = sprintf( __( 'You have marked task #%s - as Completed', APP_TD ), $task_link );
			break;
		case 'uncomplete':
			$buyer_notify_subject = sprintf( __( '%1$s has marked task #%2$s - as Uncompleted', APP_TD ), $provider_link, $purchase_link );
			$provider_notify_subject = sprintf( __( 'You have marked task #%s - as Uncompleted', APP_TD ), $task_link );
			break;
		case 'confirm':
			$buyer_notify_subject = sprintf( __( 'You have marked task #%s - as Confirmed', APP_TD ), $purchase_link );
			$provider_notify_subject = sprintf( __( '%1$s has marked task #%2$s - as Confirmed', APP_TD ), $provider_link, $task_link );
			break;
		case 'unconfirm':
			$buyer_notify_subject = sprintf( __( 'You have marked task #%s - as Unconfirmed', APP_TD ), $purchase_link );
			$provider_notify_subject = sprintf( __( '%1$s has marked task #%2$s - as Unconfirmed', APP_TD ), $provider_link, $task_link );
			break;
		default;
			break;
	}


	$buyer_option = get_user_meta( $buyer->ID, 'notifications', true );
	$provider_option = get_user_meta( $provider->ID, 'notifications', true );

	// Buyer notify
	$send_mail = array();
	if ( empty( $buyer_option ) || in_array( 'task_' . $action . '_buyer', $buyer_option ) ) {
		$wp_query->set( 'service', $service );
		$wp_query->set( 'task', $task );
		$wp_query->set( 'task_action', $action );
		$wp_query->set( 'email_name', 'task_action_buyer' );
		$wp_query->set( 'recipient', $buyer );
		$send_mail = array( 'content' => $content );
	}

	$meta = array( 'subject' => wp_strip_all_tags( $buyer_notify_subject ) );
	appthemes_send_notification( $buyer->ID, $buyer_notify_subject, 'notification', $meta, array( 'send_mail' => $send_mail ) );

	// Provider notify
	$send_mail = array();
	if ( empty( $provider_option ) || in_array( 'task_' . $action . '_provider', $provider_option ) ) {
		$wp_query->set( 'service', $service );
		$wp_query->set( 'task', $task );
		$wp_query->set( 'task_action', $action );
		$wp_query->set( 'email_name', 'task_action_provider' );
		$wp_query->set( 'recipient', $provider );
		$send_mail = array( 'content' => $content );
	}

	$meta = array( 'subject' => wp_strip_all_tags( $provider_notify_subject ) );
	appthemes_send_notification( $provider->ID, $provider_notify_subject, 'notification', $meta, array( 'send_mail' => $send_mail ) );
}

/**
 * Create events on service status changes
 *
 * @param string $new_status
 * @param string $old_status
 * @param WP_Post $post
 */
function tr_send_status_change_notifications( $new_status, $old_status, $post ) {

	if ( TR_SERVICE_PTYPE != $post->post_type ) {
		return;
	}

	elseif ( 'publish' == $new_status && 'pending' == $old_status ) {
		tr_service_approval_notify( $post );
	}

	elseif ( TR_SERVICE_STATUS_EXPIRED == $new_status && 'publish' == $old_status ) {
		tr_service_expired_notify( $post );
	}

}

/**
 * Notify service author on service approval
 * Notification + Email
 *
 * @param WP_Post $post
 */
function tr_service_approval_notify( $post ) {
	global $wp_query;

	$recipient = get_user_by( 'id', $post->post_author );
	$service_link = html_link( get_permalink( $post ), get_the_title( $post ) );
	$subject_message = sprintf( __( "Your Service listing - %s - has been approved!", APP_TD ), $service_link );
	$content = '';

	$email_name = 'service_approval';
	$send_mail = array();
	$user_option = get_user_meta( $recipient->ID, 'notifications', true );

	// If user notification settings is empty then send email by default
	// If user notificationsettings is not empty then check apropriate option
	if ( empty( $user_option ) || in_array( $email_name, $user_option ) ) {
		$wp_query->set( 'email_name', $email_name );
		$wp_query->set( 'service', $post );
		$wp_query->set( 'recipient', $recipient );
		$send_mail = array( 'content' => $content );
	}

	$meta = array( 'subject' => wp_strip_all_tags( $subject_message ) );
	appthemes_send_notification( $recipient->ID, $subject_message, 'notification', $meta, array( 'send_mail' => $send_mail ) );
}

/**
 * Notify author about expired service
 * Notification + Email
 *
 * @param WP_Post $post
 */
function tr_service_expired_notify( $post ) {
	global $wp_query;

	$recipient = get_user_by( 'id', $post->post_author );
	$service_link = html_link( get_permalink( $post ), get_the_title( $post ) );
	$subject_message = sprintf( __( "Your Service listing - %s - has expired", APP_TD ), $service_link );
	$content = '';

	$email_name = 'service_expired';
	$send_mail = array();
	$user_option = get_user_meta( $recipient->ID, 'notifications', true );

	// If user notification settings is empty then send email by default
	// If user notificationsettings is not empty then check apropriate option
	if ( empty( $user_option ) || in_array( $email_name, $user_option ) ) {
		$wp_query->set( 'email_name', $email_name );
		$wp_query->set( 'service', $post );
		$wp_query->set( 'recipient', $recipient );
		$send_mail = array( 'content' => $content );
	}

	$meta = array( 'subject' => wp_strip_all_tags( $subject_message ) );
	appthemes_send_notification( $recipient->ID, $subject_message, 'notification', $meta, array( 'send_mail' => $send_mail ) );
}

/**
 * Notify service buyer/provider on new reviews
 * Notification + Email
 */
function tr_new_service_review_notify( $review ) {
	global $wp_query;

	$service = get_post( $review->get_post_ID() );

	$reviewee = get_user_by( 'id', $service->post_author );
	$reviewer = get_user_by( 'id', $review->user_id );

	$service_id = $review->get_post_ID();

	$service_link = html_link( get_permalink( $service_id ), get_the_title( $service_id ) );
	$reviewee_link = html_link( get_author_posts_url( $reviewee->ID, $reviewee->user_nicename ), $reviewee->display_name );
	$reviewer_link = html_link( get_author_posts_url( $reviewer->ID, $reviewer->user_nicename ), $reviewer->display_name );

	// notify reviewer
	$subject_message = sprintf( __( 'Your review on - %1$s - was sent to %2$s', APP_TD ), $service_link, $reviewee_link );
	$content = '';

	$email_name = 'new_review';
	$send_mail = array();
	$user_option = get_user_meta( $reviewer->ID, 'notifications', true );

	// If user notification settings is empty then send email by default
	// If user notificationsettings is not empty then check apropriate option
	if ( empty( $user_option ) || in_array( $email_name, $user_option ) ) {
		$wp_query->set( 'email_name', $email_name );
		$wp_query->set( 'review', $review );
		$wp_query->set( 'recipient', $reviewer );
		$send_mail = array( 'content' => $content );
	}

	$meta = array( 'subject' => wp_strip_all_tags( $subject_message ) );
	appthemes_send_notification( $reviewer->ID, $subject_message, 'notification', $meta, array( 'send_mail' => $send_mail ) );

	// notify provider
	$subject_message = sprintf( __( 'User %1$s has just sent you a review on - %2$s', APP_TD ), $reviewer_link, $service_link );

	$meta = array( 'subject' => wp_strip_all_tags( $subject_message ) );

	appthemes_send_notification( $reviewee->ID, $subject_message, 'review', $meta );
}

/**
 * Retrieves the Order summary email body
 *
 * @param APP_Order $order
 * @return string Generated html
 */
function _tr_order_summary_email_body( $order, $post ) {

	$args = array(
		'wrapper_html' => 'table style="width: 100%; border-collapse: collapse;"',
		'footer_wrapper' => 'tfoot style="font-weight: bold;"',
	);

	$table = new APP_Order_Summary_Table( $order, $args );

	ob_start();

	$table->show();
	$table_output = ob_get_clean();

	return $table_output;
}

/**
 * Returns Order instructions
 * 
 * @param APP_Order $order
 * @return null|string Returns gateway message if order is instance of APP_Order.
 */
function _tr_order_instructions( $order = null ) {
	if ( ! $order || ! is_a( $order, 'APP_Order' ) ) {
		return;
	}
	$options = APP_Gateway_Registry::get_gateway_options( $order->get_gateway() );
	if ( isset( $options['message'] ) ) {
		return $options['message'];
	}
}