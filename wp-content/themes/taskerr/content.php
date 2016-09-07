<?php
/**
 * Generic Content Template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'loop-content' ); ?>>
	<div class="row collapse">

		<div class="post-main small-12 columns white-con">
			<div class="features row">

				<div class="person large-3 medium-3 columns">
					<?php tr_service_thumbnail(); ?>

					<?php if ( is_sticky() ) { ?>
						<p class="featured-label"><?php _e( 'Featured', APP_TD ); ?></p>
					<?php } ?>
				</div>

				<div class="info large-9 medium-9 columns">
					<?php appthemes_before_post_title( $post_type ); ?>
					<header>
						<h1><a href="<?php the_permalink(); ?>" rel="bookmark">
							<?php the_title(); ?>
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
				<div class="post-meta service-author small-4 columns">
					<i class="genericon genericon-user"></i>
					<?php _e( 'by', APP_TD );?>
					<?php tr_author_link(); ?>
				</div>
				<div class="post-meta service-category small-4 columns left">
					<i class="genericon genericon-category"></i>
					<?php _e( 'Category:', APP_TD ); ?>
					<?php the_category( '<em class="separator">, </em>' ); ?>
				</div>

				<?php if ( has_tag() ) { ?>
					<div class="post-meta service-tags small-4 columns">
						<i class="genericon genericon-tag"></i>
						<?php _e( 'Tags:', APP_TD ); ?>
						<span><?php the_tags( '' ); ?></span>
					</div>
				<?php } ?>

			</div><!-- end row -->

			<?php if ( function_exists( 'sharethis_button' ) && $tr_options->listing_sharethis ) { ?>
				<div class="ft-bar project-meta-sharethis row">
					<div class="listing-sharethis post-meta text-center"><?php sharethis_button(); ?></div>
				</div>
			<?php } ?>

		</div><!-- end 10-columns -->

	</div><!-- end row -->
</article>