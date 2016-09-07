<?php
/**
 * Dashboard Notifications loop.
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<form id="manage_notifications" name="manage_notifications" method="post" class="custom">

	<table width="100%" class="white-con">
		<thead>
			<tr>
				<th width="10%"><?php echo __( 'Date', APP_TD ); ?></th>
				<th width="5%">&nbsp;</th>
				<th width="75%"><?php echo __( 'Message', APP_TD ); ?></th>
				<th width="10%"><input type="checkbox" id="bulk_select" /></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $notifications->results as $notification ): ?>
				<tr id="<?php echo esc_attr( $notification->id ); ?>">
					<td>
						<span class="notification-date"><?php echo appthemes_display_date( $notification->time ); ?></small>
					</td>
					<td>
						<?php if ( 'unread' == $notification->status ) { ?>
							<span class="label notify-label" ><?php _e( 'New', APP_TD ); ?></span>
						<?php } ?>
					</td>
					<td>
						<span class="notification-message <?php echo esc_attr( $notification->status ); ?>">
							<?php echo $notification->message; ?>
						</span>
					</td>
					<td>
						<input type="checkbox" class="notification-select" name="notification_id[]" value="<?php echo esc_attr( $notification->id ); ?>" />
					</td>
				</tr>
			<?php endforeach; ?>

		</tbody>
	</table>

	<input id="bulk_delete" name="bulk_delete" type="submit" class="button" value="<?php esc_attr_e( 'Delete Selected', APP_TD ); ?>" />

	<?php wp_nonce_field( 'tr-dashboard-notifications' ); ?>

	<input type="hidden" name="action" value="manage_notifications" />

</form>