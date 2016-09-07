<?php
/**
 * New Service Author email template
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
 * @global WP_User $recipient Service author object
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
										switch( $service->post_status ) {
											case 'pending':
												_e( "Your service is awaiting moderation. You'll be notified once it is approved.", APP_TD );
												break;
											case 'publish':
												_e( 'Your service was successfully published. It is now live and publicly visible on our site.', APP_TD );
												break;
											case 'pending-payment':
												_e( 'Your service was submitted!', APP_TD );
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

										<?php if ( isset( $service_order ) ) { ?>

											<p style="<?php echo $tr_fonts; ?> font-weight: bold; font-size: 14px; line-height: 1.6; margin: 15px 0; padding: 0;">
												<?php _e( 'Order ID:', APP_TD ); ?> <?php echo $service_order->get_id(); ?><br />
												<?php _e( 'Status:', APP_TD ); ?> <?php echo $service_order->get_display_status(); ?><br />
											</p>

											<?php if ( 'pending-payment' === $service->post_status ) { ?>
												<h4 style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 23px; margin: 0 0 15px; padding: 0;">
													<?php _e( 'Payment Instrunctions:', APP_TD ); ?>
												</h4>
												<p style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
													<?php _e( 'The Order is awaiting payment.', APP_TD ); ?>
													<?php _e( 'Follow instructions to complete payment.', APP_TD ); ?>
												</p>
												<!-- Order Instructions -->
												<div class="callout" style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; background: #ECF8FF; margin: 0 0 15px; padding: 15px;">
													<?php echo wpautop( _tr_order_instructions( $service_order ) ); ?>
												</div>
												<!-- /Order Instructions -->

											<?php } ?>
										<?php } ?>


									<?php } ?>
									<!-- /Order Summary -->

									<!-- Callout Panel -->
									<p>
										<a href="<?php echo esc_url( get_permalink( $service ) ); ?>" class="btn" style="<?php echo $tr_fonts; ?>  color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; background: #666; margin: 10px 10px 0 0; padding: 10px 16px;">
											<?php _e( 'Review Service', APP_TD ); ?>
										</a>
										<a href="<?php echo tr_get_service_edit_url( $service ); ?>" class="btn" style="<?php echo $tr_fonts; ?>  color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; background: #666; margin: 10px 10px 0 0; padding: 10px 16px;">
											<?php _e( 'Edit Service', APP_TD ); ?>
										</a>
									</p>
									<!-- /Callout Panel -->

								</div>
								<!-- /Content -->

								<?php get_template_part( 'email-signature', $email_name ); ?>
							</td>
						</tr>
					</table>
				</div>