<?php
/**
 * Generic Service entry email template part
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
<div class="content" style="<?php echo $tr_fonts; ?> max-width: 600px; display: block; margin: 0 auto; padding: 15px 0;">
	<table bgcolor="" style="<?php echo $tr_fonts; ?> width: 100%; margin: 0; padding: 0;">
		<tbody>
			<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
				<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
					<h4 style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 23px; margin: 0 0 15px; padding: 0;">
						<?php echo get_the_title( $service ); ?>
					</h4>
				</td>
			</tr>
			<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
				<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
					<table style="<?php echo $tr_fonts; ?> width: 100%; margin: 0; padding: 0;">
						<tbody>
							<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
								<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
									<table class="column" style="<?php echo $tr_fonts; ?> width: 160px; float: left; min-width: 159px; margin: 0; padding: 0;">
										<tbody>
											<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
												<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
													<?php tr_service_thumbnail( $service ); ?>
												</td>
											</tr>
										</tbody>
									</table>
									<table class="column" style="<?php echo $tr_fonts; ?> float: left; width: auto; min-width: 189px; max-width: 340px; margin: 0; padding: 0;">
										<tbody>
											<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
												<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
													<?php _e( 'Author', APP_TD ); ?>
												</th>
												<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
													<?php tr_author_link( $service->post_author ); ?>
												</td>
											</tr>
											<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
												<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
													<?php _e( 'Email', APP_TD ); ?>
												</th>
												<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
													<a href="mailto:<?php echo esc_attr( tr_get_user_email ( $service->post_author ) ); ?>" style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
														<?php echo tr_get_user_email ( $service->post_author ); ?>
													</a>
												</td>
											</tr>
											<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
												<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
													<?php _e( 'Price', APP_TD ); ?>
												</th>
												<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
													<?php tr_service_price( $service->ID ); ?>
												</td>
											</tr>
											<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
												<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
													<?php _e( 'Delivery', APP_TD ); ?>
												</th>
												<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
													<?php tr_delivery_time( $service->ID ); ?>
												</td>
											</tr>
											<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
												<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
													<?php _e( 'Categories', APP_TD ); ?>
												</th>
												<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
													<?php tr_service_categories( $service->ID ); ?>
												</td>
											</tr>
											<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
												<th style="<?php echo $tr_fonts; ?> margin: 0; padding: 0; text-align: left;">
													<?php _e( 'Tags', APP_TD ); ?>
												</th>
												<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
													<?php tr_service_tags( $service->ID ); ?>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
				<td style="<?php echo $tr_fonts; ?> margin: 0; padding: 0;">
					<div style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
						<?php echo wpautop( $service->post_content ); ?>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>