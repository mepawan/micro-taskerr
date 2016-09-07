<?php
/**
 * New Review email template
 *
 * With this template you can/can't do following:
 * - Add CSS styles directly to HTML tags in attribute "style".
 * - Don't use "id" or "class" selectors - they might be ignored in web representation of email.
 * - You can customize this template by copying current file to your child theme.
 *
 * @global string $subject The email subject text
 * @global string $content The email content text (if set)
 * @global string $email_name The type of current email
 * @global APP_Single_Review $review New created Review object
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
										<?php _e( "Your Review has been successfully submitted.", APP_TD ); ?>
									</p>
									<!-- /Lead Text -->

									<!-- Review Content -->
									<div class="review-content" style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0 15px; border-left: 3px solid #CCC;">
										<?php echo $review->get_content(); ?>
									</div>
									<!-- /Review Content -->

									<!-- Callout Panel -->
									<p class="callout" style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; background: #ECF8FF; margin: 0 0 15px; padding: 15px;">
										<span><?php _e( 'Rating', APP_TD ); ?>: </span><strong style="margin: 0; padding: 0;"><?php echo $review->get_rating() . '/' . appthemes_reviews_get_args( 'max_rating' ); ?></strong><br />
										<span><?php _e( 'Review Link', APP_TD ); ?>: </span>
										<a href="<?php echo esc_url( get_comment_link( $review->get_id() ) ); ?>" style="<?php echo $tr_fonts; ?> color: #2BA6CB; font-size: 12px; font-weight: normal; margin: 0; padding: 0;">
											<?php echo esc_url( get_comment_link( $review->get_id() ) ); ?>
										</a>
									</p>
									<!-- /Callout Panel -->

								</div>
								<!-- /Content -->

								<?php get_template_part( 'email-signature', $email_name ); ?>

							</td>
						</tr>
					</table>
				</div><!-- /content -->