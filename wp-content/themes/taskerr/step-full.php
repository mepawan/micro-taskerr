<?php
/**
 * Template for displaying checkout steps.
 *
 * Inherited from page template.
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<section>
	<header>
		<div class="singular-headline">
			<?php appthemes_before_page_title(); ?>
			<?php get_template_part( 'headliner', app_template_base() ) ?>
			<?php appthemes_after_page_title(); ?>
		</div>
	</header>

	<div class="singular-row row">

		<?php do_action( 'appthemes_notices' ); ?>

		<main id="main" class="large-12 columns" role="main">

			<?php appthemes_before_page(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div id="overview" class="single-content step">
					<?php appthemes_before_page_content(); ?>
					<?php appthemes_display_checkout(); ?>
					<?php appthemes_after_page_content(); ?>
				</div>

				<!-- ad space -->
				<?php get_template_part( 'advert-bottom', app_template_base() ); ?>

			</div>

			<?php appthemes_after_page(); ?>

		</main><!-- /#main -->

	</div><!-- end row -->
</section>
