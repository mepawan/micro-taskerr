<?php
/**
 * The Template for displaying single service
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<div class="singular-headline">
	<?php get_template_part( 'headliner', $post_type ) ?>
</div>

<div class="singular-row row">

	<?php do_action( 'appthemes_notices' ); ?>

	<main id="main" class="large-9 medium-8 columns" role="main">

		<?php appthemes_after_loop( $post_type ); ?>

		<?php while ( have_posts() ) : the_post(); tr_process_page_view(); ?>

			<?php appthemes_before_post( $post_type ); ?>

			<article id="<?php echo $post_type; ?>" <?php post_class(); ?>>
				<!-- service -->
				<div class="single-content white-con">

					<header>
						<div class="post-head">
							<?php if ( tr_is_sticky() ) { ?>
								<p class="featured-label"><?php _e( 'Featured', APP_TD ); ?></p>
							<?php } ?>

							<div class="stat">
								<?php
								tr_service_reviews_stars();
								printf( _n( 'One Review', '%s Reviews', appthemes_get_post_total_reviews( get_the_ID() ), APP_TD ),
									number_format_i18n( appthemes_get_post_total_reviews( get_the_ID() ) ) );
								?>
							</div>
						</div>

						<?php appthemes_before_post_title( $post_type ); ?>
						<div class="post-title">
							<h1><?php the_title(); ?></h1>
						</div>
						<?php appthemes_after_post_title( $post_type ); ?>

					</header><!-- end header -->

					<div class="post-bar" data-equalizer>
						<div class="post-bar-item small-4 columns" title="<?php _e( 'Delivery timeframe', APP_TD );?>" data-equalizer-watch>
							<i class="genericon genericon-time">
								<span class="show-for-large-up"><?php _e( 'Will deliver in', APP_TD );?>:</span>
							</i>
							<strong><?php tr_delivery_time(); ?></strong>
						</div>
						<div class="post-bar-item small-4 columns" title="<?php _e( 'Tasks Completed', APP_TD );?>" data-equalizer-watch>
							<i class="genericon genericon-checkmark">
								<span class="show-for-large-up"><?php _e( 'Tasks Completed', APP_TD );?>:</span>
							</i>
							<strong><?php tr_tasks_completed(); ?></strong>
						</div>
						<div class="post-bar-item small-4 columns" title="<?php _e( 'Views', APP_TD );?>" data-equalizer-watch>
							<i class="genericon genericon-show">
								<span class="show-for-large-up"><?php _e( 'Views', APP_TD );?>:</span>
							</i>
							<strong><?php tr_service_views(); ?></strong>
						</div>
					</div>

					<div class="post-slider">
						<?php tr_get_attachments_slider(); ?>
					</div>

					<div class="post-bar terms-bar"  data-equalizer>
						<div class="post-bar-item small-12<?php tr_post_bar_grid_class(); ?> columns" title="<?php _e( 'Categories', APP_TD );?>" data-equalizer-watch>
							<i class="genericon genericon-category">
								<span class="show-for-large-up"><?php _e( 'Categories', APP_TD );?>:</span>
							</i>
							<?php tr_service_categories(); ?>
						</div>
						<?php if ( has_term( '', TR_SERVICE_TAG ) ) { ?>
							<div class="post-bar-item small-12<?php tr_post_bar_grid_class(); ?>  columns" title="<?php _e( 'Tags', APP_TD );?>" data-equalizer-watch>
								<i class="genericon genericon-tag">
									<span class="show-for-large-up"><?php _e( 'Tags', APP_TD );?>:</span>
								</i>
								<?php tr_service_tags(); ?>
							</div>
						<?php } ?>
						<?php if ( function_exists( 'sharethis_button' ) ) {  ?>
							<div class="post-bar-item small-12<?php tr_post_bar_grid_class(); ?>  columns" title="<?php _e( 'Share This', APP_TD );?>" data-equalizer-watch>
								<div class="listing-share">
									<?php sharethis_button(); ?>
								</div>
							</div>
						<?php } ?>
					</div>

					<?php
						// Service Description
					?>
					<div class="post-info">
						<ul class="button-group">
							<li><?php tr_display_fave_button(); ?></li>
							<li><?php tr_service_reviews_action_button(); ?></li>
							<li><?php tr_service_edit_action_button(); ?></li>
						</ul>

						<p class="desc-label"><?php _e( 'Description', APP_TD );?></p>
						<?php appthemes_before_post_content( $post_type ); ?>
						<div class="single-service-description-content">
							<?php the_content(); ?>
						</div>
						<?php appthemes_after_post_content( $post_type ); ?>
					</div>
				</div>
				<?php
				// Comments tab and reviews tab
				?>

				<dl class="tabs" data-tab data-options="deep_linking:true">
					<dd class="active"><a href="#comments-tab"><?php _e( 'Comments', APP_TD );?> (<?php echo get_comments_number(); ?>)</a></dd>
					<dd><a href="#reviews-tab"><?php _e( 'Reviews', APP_TD );?> (<?php tr_service_reviews_count(); ?>)</a></dd>
				</dl>
				<div class="tabs-content white-con">
					<div class="content active" id="comments-tab">
						<?php comments_template(); ?>
					</div>
					<div class="content" id="reviews-tab">
						<section id="reviews" class="feedback-section">
							<header>
								<h2 class="comments-title">
									<?php
										printf( _n( 'One review on &ldquo;%2$s&rdquo;', '%1$s reviews on &ldquo;%2$s&rdquo;', appthemes_get_post_total_reviews( get_the_ID() ), APP_TD ),
											number_format_i18n( appthemes_get_post_total_reviews( get_the_ID() ) ), '<span>' . get_the_title() . '</span>' );
									?>
									<?php tr_service_reviews_stars();?>
								</h2>
							</header>
							<?php get_template_part( 'loop', 'reviews' ); ?>
						</section><!-- #reviews -->
					</div>
				</div>

				<?php
				// Optional advertising space
				?>
				<!-- ad space -->
				<?php get_template_part( 'advert-bottom', app_template_base() ); ?>

			</article>

			<?php appthemes_after_post( $post_type ); ?>

		<?php endwhile; ?>

		<?php appthemes_after_endwhile( $post_type ); ?>

		<?php appthemes_after_loop( $post_type ); ?>

	</main><!-- end #main -->

	<div id="sidebar" class="large-3 medium-4 columns" role="complementary">
		<?php get_sidebar( app_template_base() ); ?>
	</div><!-- end #sidebar -->

</div><!-- end row -->