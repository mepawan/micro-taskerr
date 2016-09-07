<?php
/**
 * Taxonomy service_cat Loop template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

if ( have_posts() ) : ?>

	<?php appthemes_before_loop( TR_SERVICE_PTYPE ); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php appthemes_before_post( TR_SERVICE_PTYPE ); ?>

		<?php get_template_part( 'content', TR_SERVICE_PTYPE ); ?>

		<?php appthemes_after_post( TR_SERVICE_PTYPE ); ?>

	<?php endwhile; ?>

	<?php appthemes_after_loop( TR_SERVICE_PTYPE ); ?>

<?php else : ?>

	<?php get_template_part( 'none', TR_SERVICE_PTYPE ); ?>

<?php endif;