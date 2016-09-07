<?php
/**
 * The Template for displaying single orders
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.3
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

					<div class="post-info">

						<?php appthemes_before_post_content( $post_type ); ?>

						<?php appthemes_get_template_part( 'content-transaction', $app_order_content ); ?>

						<?php appthemes_after_post_content( $post_type ); ?>
					</div>
				</div>

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