<?php
/**
 * Generic Sidebar template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
appthemes_before_sidebar_widgets( 'tr-service' ); ?>

<div class="row">
	<div id="tr_buy_it_button" class="widget static-widget widget-buy-it large-12 columns">
		<?php tr_task_action_button(); ?>
	</div>
</div>

<div class="row">
	<aside id="tr_author_info" class="widget static-widget widget-author-info large-12 columns">
		<header>
			<span class='right author-avatar'>
				<?php echo get_avatar( get_the_author_meta('ID'), 32 ); ?>
			</span>
			<h1>
				<?php printf( __( 'Offered by %s', APP_TD ), get_the_author() ) ?>
			</h1>
		</header>
		<div class="clearfix">
			<ul>
				<li>
					<span class="left"><?php _e( 'Current Tasks', APP_TD ); ?>:</span>
					<strong class="right"><?php tr_get_provider_tasks_current_count( get_the_author_meta('ID') ); ?></strong>
				</li>
				<li>
					<span class="left"><?php _e( 'Tasks Completed', APP_TD ); ?>:</span>
					<strong class="right"><?php tr_provider_tasks_completed( get_the_author_meta('ID') ); ?></strong>
				</li>
				<li>
					<span class="left"><?php _e( 'Rating', APP_TD ); ?>:</span>
					<strong class="right"><?php tr_author_rating(); ?></strong>
				</li>
			</ul>
			<p class='text-center author-link'>
				<a href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>"><?php printf( __( "See all %s's services", APP_TD ), get_the_author() ); ?></a>
			</p>
		</div>
	</aside>
</div>

<div class="row">
	<aside id="tr_author_recent_posts" class="widget static-widget large-12 columns widget-author-recent-posts">
		<?php tr_recent_services_widget(); ?>
	</aside>
</div>

<?php dynamic_sidebar( 'tr-service' );
appthemes_after_sidebar_widgets( 'tr-service' );