<?php
/**
 * Template for displaying forms.
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

		<main id="main" class="large-9 medium-8 columns" role="main">

			<?php appthemes_before_page_loop(); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php appthemes_before_page(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div id="overview" class="single-content white-con">
						<div class="post-info">
							<?php appthemes_before_page_content(); ?>
							<?php the_content(); ?>

							<?php // ACTUAL FORM CONTENT STARTS HERE ?>

							<?php switch ( app_template_base() ) {
								case 'form-registration':
									if ( ! get_option('users_can_register') ) { ?>
										<h3><?php _e( 'User registration has been disabled.', APP_TD ); ?></h3>
										<?php break;
									}
								case 'form-login':
								case 'form-password-recovery':
								case 'form-password-reset':
									require_once APP_THEME_FRAMEWORK_DIR . '/templates/' . app_template_base() . '.php';
									break;
								case 'edit-profile':
									locate_template( 'form-edit-profile.php', true );
									break;
								default:
									break;
							 } ?>

							 <?php // ACTUAL FORM CONTENT ENDS HERE ?>

							<?php edit_post_link( __( 'Edit', APP_TD ), '<span class="edit-link">', '</span>' ); ?>
							<?php appthemes_after_page_content(); ?>
						</div>
					</div>

					<!-- ad space -->
					<?php get_template_part( 'advert-bottom', app_template_base() ); ?>

				</div>

				<?php appthemes_after_page(); ?>

			<?php endwhile; ?>

			<?php appthemes_after_page_loop(); ?>

		</main><!-- /#main -->

		<div id="sidebar" class="large-3 medium-4 columns" role="complementary">
			<?php get_sidebar( app_template_base() ); ?>
		</div><!-- end #sidebar -->

	</div><!-- end row -->
</section>
