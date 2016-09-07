<?php
/**
 * Template name: Home
 *
 * Template uses nested Loop with 'service' type by default.
 *
 * $nested_query (array) - Loop query arguments.
 * @see TR_Home class for arguments definition.
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<div class="archive-row row">

	<?php do_action( 'appthemes_notices' ); ?>

	<main id="main" class="large-9 medium-8 columns" role="main">

		<?php appthemes_before_page_loop(); ?>

		<?php while ( have_posts() ) : the_post(); ?>

		<?php appthemes_before_page(); ?>

		<section id="services">
			<!-- services -->

			<?php query_posts( $nested_query ); ?>

			<?php get_template_part( 'loop-header', $nested_query['post_type'] ); ?>

			<?php get_template_part( 'loop', $nested_query['post_type'] ); ?>

			<?php get_template_part( 'loop-footer', $nested_query['post_type'] ); ?>

			<?php wp_reset_query(); ?>

			<?php wp_reset_postdata(); ?>

			<?php /* End of Loop */ ?>

		</section><!-- end #projects -->

		<?php appthemes_after_page(); ?>

		<?php endwhile; ?>

		<?php appthemes_after_page_loop(); ?>

	</main><!-- end #main -->

	<div id="sidebar" class="large-3 medium-4 columns" role="complementary">

		<?php get_sidebar( app_template_base() ); ?>

	</div><!-- end #sidebar -->

</div><!-- end row -->