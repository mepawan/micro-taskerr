<?php
/**
 * Generic Email Header template
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
					<table bgcolor="#378AD8" style="width: 100%; margin: 0; padding: 0;">
						<tr style="margin: 0; padding: 0;">
							<td style="margin: 0; padding: 0;">
								<?php /* logo size 200x50 */ ?>
								<div id="logo" style="margin: 0; padding: 0;">
									<?php if ( get_header_image() ) { ?>
										<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
											<img src="<?php header_image(); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
										</a>
									<?php } else { ?>
										<h1 style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; margin:0; padding:0; color:#FFF; font-weight:200; font-size: 44px;">
											<a href="<?php echo home_url('/'); ?>" style="color:#FFF!important; padding: 0; margin: 0; text-decoration: none; "><?php bloginfo('name');?></a>
										</h1>
									<?php } ?>
								</div><!-- /header-logo -->
							</td>
							<td align="right" style="margin: 0; padding: 0;">
								<h6 class="collapse" style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #A0D1FF; font-weight: 900; font-size: 14px; text-transform: uppercase; margin: 0; padding: 0;">
									<?php bloginfo( 'description' ); ?>
								</h6>
							</td>
						</tr>
					</table>
				</div>