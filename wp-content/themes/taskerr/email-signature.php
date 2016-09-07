<?php
/**
 * Generic email signature and contact info
 *
 * With this template you can/can't do following:
 * - Add CSS styles directly to HTML tags in attribute "style".
 * - Don't use "id" or "class" selectors - they might be ignored in web representation of email.
 * - You can customize this template by copying current file to your child theme.
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

							<!-- Signature -->
							<div class="email-footer"  style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0; padding: 0;">
								<p><?php _e( "Thanks,", APP_TD ); ?></p>
								<p><?php printf( __( "The %s team", APP_TD ), get_bloginfo('name') ); ?></p>
							</div>
							<!-- /Signature -->

							<!-- social & contact -->
							<table class="social" width="100%" style="width: 100%; background: #ebebeb; margin: 0; padding: 0;" bgcolor="#ebebeb">
								<tr style="margin: 0; padding: 0;">
									<td style="margin: 0; padding: 0;">

										<?php if ( tr_has_social( 'facebook' ) || tr_has_social( 'twitter' ) || tr_has_social( 'google-plus' ) ) { ?>
											<!-- column 1 -->
											<table align="left" class="column" style="width: 280px; float: left; min-width: 279px; margin: 0; padding: 0;">
												<tr style="margin: 0; padding: 0;">
													<td style="margin: 0; padding: 15px;">
														<h5 class="" style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 900; font-size: 17px; margin: 0 0 15px; padding: 0;">
															<?php _e( 'Connect with Us:', APP_TD ); ?>
														</h5>
														<p style="font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
															<?php if ( tr_has_social( 'facebook' ) ) { ?>
																<a href="<?php echo esc_url( tr_get_social_url( 'facebook' ) ); ?>" class="soc-btn fb" style="<?php echo $tr_fonts; ?> color: #FFF; font-size: 12px; text-decoration: none; font-weight: bold; display: block; text-align: center; background-color: #3B5998 !important; margin: 0 0 10px; padding: 3px 7px;">
																	<?php echo tr_get_social_title( 'facebook' ); ?>
																</a>
															<?php } ?>
															<?php if ( tr_has_social( 'twitter' ) ) { ?>
																<a href="<?php echo esc_url( tr_get_social_url( 'twitter' ) ); ?>" class="soc-btn tw" style="<?php echo $tr_fonts; ?> color: #FFF; font-size: 12px; text-decoration: none; font-weight: bold; display: block; text-align: center; background-color: #1daced !important; margin: 0 0 10px; padding: 3px 7px;">
																	<?php echo tr_get_social_title( 'twitter' ); ?>
																</a>
															<?php } ?>
															<?php if ( tr_has_social( 'google-plus' ) ) { ?>
																<a href="<?php echo esc_url( tr_get_social_url( 'google-plus' ) ); ?>" class="soc-btn gp" style="<?php echo $tr_fonts; ?> color: #FFF; font-size: 12px; text-decoration: none; font-weight: bold; display: block; text-align: center; background-color: #DB4A39 !important; margin: 0 0 10px; padding: 3px 7px;">
																	<?php echo tr_get_social_title( 'google-plus' ); ?>
																</a>
															<?php } ?>
														</p>
													</td>
												</tr>
											</table>
											<!-- /column 1 -->
										<?php } ?>

										<?php if ( $tr_options->email || $tr_options->phone || $tr_options->skype ) { ?>
											<!-- column 2 -->
											<table align="left" class="column" style="width: 280px; float: left; min-width: 279px; margin: 0; padding: 0;">
												<tr style="margin: 0; padding: 0;">
													<td style="margin: 0; padding: 15px;">
														<h5 class="" style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 900; font-size: 17px; margin: 0 0 15px; padding: 0;">
															<?php _e( 'Contact Info:', APP_TD ); ?>
														</h5>
														<p style="<?php echo $tr_fonts; ?> font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
															<?php if ( $tr_options->phone ) { ?>
																<?php _e( 'Phone:', APP_TD ); ?> <strong style="margin: 0; padding: 0;">
																	<a href="tel:<?php echo esc_attr( $tr_options->phone ); ?>" style="<?php echo $tr_fonts; ?> color: #2BA6CB; margin: 0; padding: 0;">
																		<?php echo $tr_options->phone; ?>
																	</a>
																</strong><br />
															<?php } ?>
															<?php if ( $tr_options->email ) { ?>
																<?php _e( 'Email:', APP_TD ); ?> <strong style="margin: 0; padding: 0;">
																	<a href="mailto:<?php echo esc_attr( $tr_options->email ); ?>" style="<?php echo $tr_fonts; ?> color: #2BA6CB; margin: 0; padding: 0;">
																		<?php echo $tr_options->email; ?>
																	</a>
																</strong><br />
															<?php } ?>
															<?php if ( $tr_options->skype ) { ?>
																<?php _e( 'Skype:', APP_TD ); ?> <strong style="margin: 0; padding: 0;">
																	<a href="skype:<?php echo esc_attr( $tr_options->skype ); ?>" style="<?php echo $tr_fonts; ?> color: #2BA6CB; margin: 0; padding: 0;">
																		<?php echo $tr_options->skype; ?>
																	</a>
																</strong>
															<?php } ?>
														</p>
													</td>
												</tr>
											</table>
											<!-- /column 2 -->
										<?php } ?>
										<span class="clear" style="display: block; clear: both; margin: 0; padding: 0;"></span>

									</td>
								</tr>
							</table><!-- /social & contact -->
