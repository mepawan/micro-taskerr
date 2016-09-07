<?php
/**
 * Top Bar Notifications loop
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
						<?php if ( tr_get_notifications_count() ) { ?>
							<ul id="notifications-dropdown" class="dropdown">
								<li class="dropdown-label"><label><?php _e( 'Unread notifications', APP_TD ); ?></label></li>
									<?php foreach ( tr_get_top_bar_notifications() as $notification ) { ?>
										<li><a class="notifications-node" href="<?php echo esc_url( tr_get_notification_url( $notification->id ) ); ?>">
												<span class="notification-date">
													<?php echo appthemes_display_date( $notification->time ); ?>
												</span>
												&nbsp;&mdash;&nbsp;
												<span class="notification-message">
													<?php echo wp_strip_all_tags( $notification->message ); ?>
												</span>
										</a></li>
									<?php } ?>
								<li class="divider"></li>
								<li><a class="notifications-node" href="<?php echo esc_url( tr_get_dashboard_notifications_url() ); ?>"><?php _e( 'See all', APP_TD ); ?> &rarr;</a></li>
							</ul>
						<?php }