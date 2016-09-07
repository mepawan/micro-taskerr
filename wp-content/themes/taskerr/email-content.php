<?php
/**
 * Generic Email content email template
 *
 * With this template you can/can't do following:
 * - Add CSS styles directly to HTML tags in attribute "style".
 * - Don't use "id" or "class" selectors - they might be ignored in web representation of email.
 * - You can customize this template by copying this file to your child theme.
 * - You can override this template for specific email type by copying this
 *   file to your child theme and extend file name with email type ($email_name value).
 *
 *   Example: copy CURRENT_FILE_NAME.php, rename to CURRENT_FILE_NAME-new_service_admin.php.
 *   Then new file will loads only for New Service admin notifications.
 *
 * @global string $subject The email subject text
 * @global string $content The email content text (if set)
 * @global string $email_name The type of current email
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
									<?php echo $content; ?>
								</div>
								<!-- /Content -->

								<?php get_template_part( 'email-signature', $email_name ); ?>
							</td>
						</tr>
					</table>
				</div>