<?php
/**
 * Template Name: Full Width
 * Full Width template for custom pages
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

			<?php appthemes_before_page_loop(); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php appthemes_before_page(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div id="overview" class="single-content white-con">
						<div class="post-info">
							<?php appthemes_before_page_content(); ?>
							<?php the_content(); ?>
							<?php appthemes_after_page_content(); ?>
							<?php edit_post_link( __( 'Edit', APP_TD ), '<span class="edit-link">', '</span>' ); ?>
						</div>
					</div>

                    <?php if ( comments_open() ) { ?>
					<div class="tabs-content white-con">
						<div class="content active" id="panel2-1">
							<?php comments_template(); ?>
						</div>
					</div>
                    <?php } ?>

					<!-- ad space -->
					<?php get_template_part( 'advert-bottom', app_template_base() ); ?>

				</div>

				<?php appthemes_after_page(); ?>

			<?php endwhile; ?>

			<?php appthemes_after_page_loop(); ?>

		</main><!-- /#main -->

	</div><!-- end row -->
</section>