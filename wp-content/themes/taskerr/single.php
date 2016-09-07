<?php
/**
 * The Template for displaying all single posts
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

		<?php while ( have_posts() ) : the_post(); ?>

			<?php appthemes_before_post( $post_type ); ?>

			<article id="<?php echo $post_type; ?>" <?php post_class(); ?>>

				<div class="single-content white-con">

					<header>
						<div class="post-head">
							<?php if ( is_sticky() ) { ?>
								<p class="featured-label"><?php _e( 'Featured', APP_TD ); ?></p>
							<?php } ?>
						</div>

						<?php appthemes_before_post_title( $post_type ); ?>
						<div class="post-title">
							<h1><?php the_title(); ?></h1>
						</div>
						<?php appthemes_after_post_title( $post_type ); ?>

					</header><!-- end header -->

					<div class="post-bar terms-bar"  data-equalizer>
						<div class="post-bar-item small-12<?php tr_post_bar_grid_class(); ?> columns" title="<?php _e( 'Categories', APP_TD );?>" data-equalizer-watch>
							<i class="genericon genericon-category">
								<span class="show-for-large-oup"><?php _e( 'Categories', APP_TD );?>:</span>
							</i>
							<?php the_category( '<em class="separator">, </em>' ); ?>
						</div>
						<?php if ( has_tag() ) { ?>
							<div class="post-bar-item small-12<?php tr_post_bar_grid_class(); ?>  columns" title="<?php _e( 'Tags', APP_TD );?>" data-equalizer-watch>
								<i class="genericon genericon-tag">
									<span class="show-for-large-oup"><?php _e( 'Tags', APP_TD );?>:</span>
								</i>
								<?php the_tags( '' ); ?>
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
							<li><?php tr_post_edit_action_button(); ?></li>
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

				<div class="tabs-content white-con">
					<div class="content active" id="panel2-1">
						<?php comments_template(); ?>
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