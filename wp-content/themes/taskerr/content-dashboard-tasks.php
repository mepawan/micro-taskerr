<?php
/**
 * Dashboard Task loop content
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<article class="task loop-content">
	<div class="row">
		<div class="large-12 columns">
			<div class="post-main post-info white-con">
				<div class="row">
					<div class="large-12 columns">
						<header class="task-header">
							<?php appthemes_before_post_title( $post_type ); ?>
							<h1><span><?php esc_html_e( 'Task #', APP_TD ); ?></span><?php tr_the_task_id(); ?></h1>
							<span class="service-title">
								<?php _e( 'Item', APP_TD ); ?><span class="sep">: </span>
								<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
							</span>
							<?php appthemes_after_post_title( $post_type ); ?>
						</header>
					</div>
				</div>

				<div class="row">
					<div class="large-12 columns">
						<div class="stat-line">
							<p class="purchased-date">
								<span class="label-task"><?php _e( 'Purchased on', APP_TD ); ?></span>
								<span><?php tr_service_purchased_date(); ?></span>
							</p>

							<p class="purchaser">
								<span class="label-task"><?php _e( 'Buyer', APP_TD ); ?></span>
								<span class="sep">:</span>
								<span class="buyer-name"><?php echo tr_the_buyer_name(); ?></span>
								<?php
								/**
								 * link to send a message to the buyer
								 * @todo Provide communication module
								 */
								//echo html_link( tr_send_message_buyer_link(), sprintf(__( 'Send a message to %s', APP_TD ), tr_the_buyer_name() ) ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="large-8 columns task-main">

						<article class="buyer-message">

							<header>
								<h1><?php _e( "Message from buyer", APP_TD ); ?>:</h1>
							</header>

							<div class="message">
								<?php tr_the_buyer_message(); ?>
							</div>

							<?php $review = appthemes_get_user_authored_post_review( tr_the_buyer_id(), get_the_ID() ); ?>
							<?php if ( $review ) { ?>

								<article class="buyer-review">
									<p class="buyer-rating">
										<span class="label-task">
											<?php _e( "Buyer's rating", APP_TD ); ?>:
										</span>
										<?php
										/**
										 * the rating for this service. If no rating, display a link asking them to rate it
										 */
										tr_service_reviews_stars(); ?>

										<?php //echo html_link( tr_request_rating_link(), __( 'ask for a rating', APP_TD ) ); ?>
									</p>

									<header>
										<h1>
											<?php _e( "Buyer's review on", APP_TD ); ?>
											<a class="review-meta" href="<?php echo esc_url( get_comment_link( $review->get_id() ) ); ?>">
												<time datetime="<?php $review->get_date(); ?>">
													<?php echo mysql2date( get_option( 'date_format' ), $review->get_date() ); ?>
												</time>
											</a>:
										</h1>
									</header>

									<div class="message">
										<?php echo $review->get_content(); ?>
									</div>

								</article><!-- end .buyer-review -->
							<?php } ?>

						</article><!-- end .buyer-message -->
					</div><!-- end .task-main -->

					<div class="task-actions large-4 columns">
						<?php  tr_display_task_status(); ?>
					</div><!-- end .task-status -->
				</div>
			</div>
		</div>
	</div>
</article>