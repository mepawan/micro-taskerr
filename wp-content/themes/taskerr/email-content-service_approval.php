<?php
/**
 * Service Approval email template
 *
 * With this template you can/can't do following:
 * - Add CSS styles directly to HTML tags in attribute "style".
 * - Don't use "id" or "class" selectors - they might be ignored in web representation of email.
 * - You can customize this template by copying current file to your child theme.
 *
 * @global  string  $subject The email subject text
 * @global  string  $content The email content text (if set)
 * @global  string  $email_name The type of current email
 * @global  WP_Post $service New created Service object
 * @global  WP_User $recipient Service author object
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
										<?php _e( 'Your service listing has been approved and is now live!', APP_TD ); ?>
									</p>
									<!-- /Lead Text -->

									<!-- Callout Panel -->
									<p>
										<a href="<?php echo esc_url( get_permalink( $service ) ); ?>" class="btn" style="<?php echo $tr_fonts; ?> text-decoration: none; color: #FFF; background-color: #666; padding: 10px 16px; font-weight: bold; margin-right: 10px; text-align: center; cursor: pointer; display: inline-block;">
											<?php _e( 'View Service', APP_TD ); ?>
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