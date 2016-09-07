<?php
/**
 * Service loop content template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php tr_post_class( 'loop-content' ); ?>>
	<div class="row collapse">

		<div class="post-main large-10 medium-10 columns white-con">
			<div class="features row">

				<div class="person large-3 medium-3 columns">
					<?php tr_service_thumbnail(); ?>

					<?php if ( tr_is_sticky() ) { ?>
						<p class="featured-label"><?php _e( 'Featured', APP_TD ); ?></p>
					<?php } ?>

					<div class="review-meta column centered">
						<?php tr_service_reviews_stars();?>
					</div>
				</div>

				<div class="info large-9 medium-9 columns">
					<?php appthemes_before_post_title( $post_type ); ?>
					<header>
						<h1><a href="<?php the_permalink(); ?>" rel="bookmark">
							<span class="<?php echo esc_attr( tr_service_featured_class() ); ?>">
								<?php the_title(); ?>
							</span>
						</a></h1>
					</header>
					<?php appthemes_after_post_title( $post_type ); ?>

					<?php appthemes_before_post_content( $post_type ); ?>
					<div class="service-description">
						<?php the_excerpt(); ?>
					</div>
					<?php appthemes_after_post_content( $post_type ); ?>
				</div>

			</div>

			<!-- service meta author-->
			<div class="ft-bar row">
				<div class="post-meta service-author large-3 medium-3 small-6 columns">
					<i class="genericon genericon-user"></i>
					<?php _e( 'by', APP_TD );?>
					<?php tr_author_link(); ?>
				</div>
				<div class="post-meta service-author-rating large-3 medium-3 small-6 columns">
					<i class="genericon genericon-star"></i>
					<?php _e( 'Rating:', APP_TD );?>
					<?php // TODO: Consider to use less expensive solution (or do not use at all): ?>
					<span><?php tr_author_rating(); ?></span>
				</div>
				<div class="post-meta service-category large-3 medium-3 small-6 columns left">
					<i class="genericon genericon-category"></i>
					<?php _e( 'Category:', APP_TD ); ?>
					<?php tr_service_categories(); ?>
				</div>

				<?php if ( has_term( '', TR_SERVICE_TAG ) ) { ?>
					<div class="post-meta service-tags large-3 medium-3 small-6 columns">
						<i class="genericon genericon-tag"></i>
						<?php _e( 'Tags:', APP_TD ); ?>
						<span><?php tr_service_tags(); ?></span>
					</div>
				<?php } ?>

			</div><!-- end row -->

			<?php if ( function_exists( 'sharethis_button' ) && $tr_options->listing_sharethis ) { ?>
				<div class="ft-bar project-meta-sharethis row">
					<div class="listing-sharethis post-meta text-center"><?php sharethis_button(); ?></div>
				</div>
			<?php } ?>

		</div><!-- end 10-columns -->

		<div class="btn-bar large-2 medium-2 columns">

			<div class="price grey"><?php tr_service_price(); ?></div>
			<?php tr_service_buy_it_action_button(); ?>
			<?php tr_display_fave_button(); ?>

		</div>

	</div><!-- end row -->
</article>
