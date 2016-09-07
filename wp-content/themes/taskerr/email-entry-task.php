<?php
/**
 * Generic Task entry email template part
 *
 * With this template you can/can't do following:
 * - Add CSS styles directly to HTML tags in attribute "style".
 * - Don't use "id" or "class" selectors - they might be ignored in web representation of email.
 * - You can customize this template by copying current file to your child theme.
 *
 * @global string  $subject     The email subject text
 * @global string  $content     The email content text (if set)
 * @global string  $email_name  The type of current email
 * @global WP_Post $service     New created Service object
 * @global WP_User $recipient   Service author object
 * @global TR_Task $task        The Task object
 * @global string  $task_action Current action
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<h4 style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 23px; margin: 25px 0 15px; padding: 0;">
	<?php _e( 'Task Summary:', APP_TD ); ?>
</h4>
<table class="column" style="<?php echo $tr_fonts; ?> width: 100%; min-width: 189px; margin: 0; padding: 0;">
	<tbody>
		<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
			<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
				<?php _e( 'Task #', APP_TD ); ?>
			</th>
			<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
				<?php tr_the_task_id( $task ); ?>
			</td>
		</tr>
		<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
			<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
				<?php _e( 'Task Status', APP_TD ); ?>
			</th>
			<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
				<?php echo tr_get_task_status( $task ); ?>
			</td>
		</tr>
		<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
			<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
				<?php _e( 'Date', APP_TD ); ?>
			</th>
			<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
				<?php tr_service_purchased_date( $task ); ?>
			</td>
		</tr>
		<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
			<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
				<?php _e( 'Service Provider', APP_TD ); ?>
			</th>
			<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
				<?php echo tr_the_provider_name( $task ); ?>
			</td>
		</tr>
		<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
			<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
				<?php _e( 'Service Buyer', APP_TD ); ?>
			</th>
			<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
				<?php echo tr_the_buyer_name( $task ); ?>
			</td>
		</tr>
	</tbody>
</table>

<h4 style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 23px; margin: 25px 0 15px; padding: 0;">
	<?php _e( 'Task Instructions:', APP_TD ); ?>
</h4>
<div class="email-task-instructions" style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; background: #ECF8FF; margin: 0 0 15px; padding: 15px;">
	<?php tr_the_buyer_message( $task ); ?>
</div>

<h4 style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 23px; margin: 25px 0 15px; padding: 0;">
	<?php _e( 'Contacts:', APP_TD ); ?>
</h4>
<div class="email-contacts" style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; background: #ECF8FF; margin: 0 0 15px; padding: 15px;">
	<?php tr_the_buyer_contacts( $task ); ?>
</div>