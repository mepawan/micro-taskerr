<?php
/**
 * Dashboard Loop template.
 *
 * Tryies to load specific dashboard content part,
 * on failure will try to load specific post type content part
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

if ( have_posts() ) : ?>

	<?php appthemes_before_loop( app_template_base() ); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php appthemes_before_post( app_template_base() ); ?>

		<?php if ( locate_template( "content-dashboard-{$dashboard_type}.php" ) ) { ?>

			<?php get_template_part( 'content', app_template_base() ); ?>

		<?php } else { ?>

			<?php get_template_part( 'content', $post_type ); ?>

		<?php } ?>

		<?php appthemes_after_post( app_template_base() ); ?>

	<?php endwhile; ?>

	<?php appthemes_after_loop( app_template_base() ); ?>

<?php else : ?>

	<?php get_template_part( 'none-dashboard', $dashboard_type ); ?>

<?php endif;