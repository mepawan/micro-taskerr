<?php
/**
 * New Service Admin email template
 *
 * With this template you can/can't do following:
 * - Add CSS styles directly to HTML tags in attribute "style".
 * - Don't use "id" or "class" selectors - they might be ignored in web representation of email.
 * - You can customize this template by copying current file to your child theme.
 *
 * @global string $subject The email subject text
 * @global string $content The email content text (if set)
 * @global string $email_name The type of current email
 * @global WP_Post $service New created Service object
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
				<div class="content" style="max-width: 600px; display: block; margin: 0 auto; padding: 15px;">
					<table style="width: 100%; margin: 0; padding: 0;">
						<tr style="margin: 0; padding: 0;">
							<td style="margin: 0; padding: 0;">

								<!-- Greeting -->
								<h3 style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 27px; margin: 0 0 15px; padding: 0;">
									<?php _e( 'Hello', APP_TD ); ?>, <?php echo $recipient->display_name; ?>
								</h3>
								<!-- /Greeting -->

								<!-- Content -->
								<div class="email-content" style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0; padding: 0;">

									<!-- Lead Text -->
									<p class="lead" style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 17px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
										<?php
										switch( $service->post_status) {
											case 'pending':
												_e( 'A new service is awaiting moderation.', APP_TD );
												break;
											case 'publish':
												_e( 'A new service was published.', APP_TD );
												break;
											default:
												break;
										} ?>
									</p>
									<!-- /Lead Text -->

									<!-- Service Content -->
									<?php get_template_part( 'email-entry-service', $service->post_status ); ?>
									<!-- /Service Content -->

									<!-- Order Summary -->
									<?php if ( $tr_options->service_charge ) { ?>
										<h4 style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 23px; margin: 0 0 15px; padding: 0;">
											<?php _e( 'Order Summary:', APP_TD ); ?>
										</h4>
										<?php echo $content; ?>
									<?php } ?>
									<!-- /Order Summary -->

									<!-- Callout Panel -->
									<p>
										<a href="<?php echo esc_url( get_permalink( $service ) ); ?>" class="btn" style="<?php echo $tr_fonts; ?>  color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; background: #666; margin: 10px 10px 0 0; padding: 10px 16px;">
											<?php _e( 'Review Service', APP_TD ); ?>
										</a>
										<a href="<?php echo get_edit_post_link( $service, false ); ?>" class="btn" style="<?php echo $tr_fonts; ?>  color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; background: #666; margin: 10px 10px 0 0; padding: 10px 16px;">
											<?php _e( 'Edit Service', APP_TD ); ?>
										</a>
										<?php
										switch( $service->post_status ) {
											case 'pending': ?>
												<a href="<?php echo esc_url( admin_url( 'edit.php?post_status=pending&post_type='.TR_SERVICE_PTYPE ) ); ?>" class="btn" style="<?php echo $tr_fonts; ?>  color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; background: #666; margin: 10px 10px 0 0; padding: 10px 16px;">
													<?php _e( 'Review all pending services', APP_TD ); ?>
												</a>
												<?php break;
											case 'publish': ?>
												<a href="<?php echo esc_url( admin_url( 'edit.php?post_status=publish&post_type='.TR_SERVICE_PTYPE ) ); ?>" class="btn" style="<?php echo $tr_fonts; ?>  color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; background: #666; margin: 10px 10px 0 0; padding: 10px 16px;">
													<?php _e( 'Review all published services', APP_TD ); ?>
												</a>
												<?php break;
											default:
												break;
										} ?>
									</p>
									<!-- /Callout Panel -->

								</div>
								<!-- /Content -->

							</td>
						</tr>
					</table>
				</div>