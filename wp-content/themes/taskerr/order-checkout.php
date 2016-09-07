<?php
/**
 * Template for displaying order checkout steps.
 *
 * Inherited from step template.
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

		<main id="main" class="large-9 medium-8 columns" role="main">

			<?php appthemes_before_page(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div id="overview" class="single-content white-con step">
					<div class="post-info">
						<?php
						appthemes_before_page_content();

						appthemes_get_template_part( 'content-transaction', 'checkout' );

						appthemes_after_page_content(); ?>
					</div>
				</div>

				<!-- ad space -->
				<?php get_template_part( 'advert-bottom', app_template_base() ); ?>

			</div>

			<?php appthemes_after_page(); ?>

		</main><!-- /#main -->

		<div id="sidebar" class="large-3 medium-4 columns" role="complementary">
			<?php get_sidebar( app_template_base() ); ?>
		</div><!-- end #sidebar -->

	</div><!-- end row -->
</section>
