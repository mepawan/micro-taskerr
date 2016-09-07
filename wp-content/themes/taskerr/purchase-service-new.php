<?php
/**
 * Pricing template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
			<div class="pricing">
				<form id="create-service" method="POST" action="<?php echo appthemes_get_step_url(); ?>">
					<div class="plans row">
						<?php if( !empty( $plans ) ) { ?>
							<div class="top-info">
								<?php the_content(); ?>
							</div>
							<ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-3">
								<?php foreach( $plans as $key => $plan ) { ?>
								<li>
									<article id="plan-<?php echo esc_attr( $plan['ID'] ); ?>" class="plan">
										<div class="holder">
											<h1 class="type"><?php echo $plan['title']; ?></h1>
											<p class="price"><?php appthemes_display_price( $plan['price'] ); ?></p>
											<p class="period">
												<?php if( $plan['duration'] != 0 ){ ?>
													<?php printf( _n( 'for %s day', 'for %s days', $plan['duration'], APP_TD ), $plan['duration'] ); ?>
												<?php }else{ ?>
													<?php _e( 'Unlimited days', APP_TD ); ?>
												<?php } ?>
											</p>
											<?php if( _tr_no_featured_available( $plan ) ) { ?>
												<h4><?php _e( 'Features options are not available for this price plan.', APP_TD ); ?></h4>
											<?php } else { ?>
												<h4><?php _e( 'Features options', APP_TD ); ?></h4>
												<div class="large-10 large-centered columns features-options">
													<fieldset>
														<?php foreach ( array( TR_ITEM_FEATURED_HOME, TR_ITEM_FEATURED_CAT ) as $addon ) { ?>
															<?php _tr_show_purchasable_featured_addon( $addon, $plan['post_data']->ID ); ?>
														<?php } ?>
													</fieldset>
												</div>
											<?php } ?>
											<p class="text"><?php echo $plan['description']; ?></p>
											<button name="plan" value="<?php echo $plan['post_data']->ID; ?>" class="button alert large" type="submit"><?php _e( 'Get Started', APP_TD ); ?></button>
										</div>
									</article>
								</li>
								<?php } ?>
							</ul>
						<?php } ?>
						<input type="hidden" name="action" value="purchase-service">
					</div>

					<?php if ( $tr_options->phone ) { ?>
						<p class="help">
							<?php _e( 'Got questions about our pricing? Call us at', APP_TD ); ?>
							<a href="tel:<?php echo esc_attr( $tr_options->phone ); ?>">
								<?php echo $tr_options->phone; ?>
							</a>
						</p>
					<?php } ?>

				</form>
			</div>