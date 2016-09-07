<?php
/**
 * Task Summary template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
			<div class="task-summary">

				<p class="highlight-large"><?php _e( 'Your task has been sent.', APP_TD ); ?></p>

				<div class="inline-tip">
					<span class="highlight-blue"><?php _e( 'Congratulations on your purchase. Here are some important details about your order.', APP_TD ); ?></span>
						<ul>
							<li><?php _e( 'The seller of this item has been notified.', APP_TD ); ?></li>
							<li><?php _e( 'The seller should contact you soon with payment details.', APP_TD ); ?></li>
							<li><?php _e( 'You will be able to view this order on your customer dashboard.', APP_TD ); ?></li>
						</ul>
				</div>

				<div class="dashboard-task-info">

					<div class="panel summary-info">
						<div>
							<strong><?php _e( 'Task #', APP_TD ); ?>:</strong>
							<span><?php tr_the_task_id(); ?><span>
						</div>
						<div>
							<strong><?php _e( 'Item', APP_TD ); ?>:</strong>
							<span><?php echo $listing->post_title; ?><span>
						</div>
						<div>
							<strong><?php _e( 'Seller', APP_TD ); ?>:</strong>
							<span><?php echo tr_the_provider_name(); ?><span>
						</div>
						<div>
							<strong><?php _e( 'Purchased on', APP_TD ); ?>:</strong>
							<span><?php tr_service_purchased_date(); ?><span>
						</div>
						<div>
							<strong><?php _e( 'Price', APP_TD ); ?>:</strong>
							<span><?php tr_service_price(); ?><span>
						</div>
						<div class="textalignright">
							<i class="genericon genericon-time"></i>
							<span><?php _e( 'Will deliver in', APP_TD );?> <?php tr_delivery_time(); ?></span>
						</div>
					</div>

					<dl>
						<dt><?php _e( 'Your message to the seller', APP_TD ); ?>:</dt>
						<dd>
							<em class="message"><?php tr_the_buyer_message(); ?></em>
						</dd>
						<dt><?php _e( 'What would you like to do now?', APP_TD ); ?></dt>
						<dd class="inline-tip">
							<ul class="task-summary-bar highlight-blue">
								<li><a href="<?php echo esc_url( appthemes_get_step_url( 'task-confirm' ) ); ?>"><?php _e( 'Create new Task', APP_TD ); ?></a></li>
								<li><a href="<?php the_permalink(); ?>"><?php _e( 'Go to the service', APP_TD ); ?></a></li>
								<li><a href="<?php echo esc_url( tr_get_dashboard_url() ); ?>"><?php _e( 'Go to my dashboard', APP_TD ); ?></a></li>
								<li><a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ); ?>"><?php _e( 'Go to the seller page', APP_TD ); ?></a></li>
								<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Go to the home page', APP_TD ); ?></a></li>
							</ul>
						</dd>
					</dl>

				</div><!-- end .dashboard-task-info -->
			</div>