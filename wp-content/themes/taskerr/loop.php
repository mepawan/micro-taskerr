<?php
/**
 * Generic Loop template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

if ( have_posts() ) : ?>

	<?php appthemes_before_loop( $post_type ); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php appthemes_before_post( $post_type ); ?>

		<?php get_template_part( 'content', $post_type ); ?>

		<?php appthemes_after_post( $post_type ); ?>

	<?php endwhile; ?>

	<?php appthemes_after_endwhile( $post_type ); ?>

	<?php appthemes_after_loop( $post_type ); ?>

<?php else : ?>

	<?php appthemes_loop_else( $post_type ); ?>

	<?php get_template_part( 'none', $post_type ); ?>

<?php endif;