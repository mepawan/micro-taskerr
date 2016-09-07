<?php
/**
 * Generic Dashboard template. Used for all Dashboard types.
 * Child theme developer can override this template if needed or extend.
 *
 * According to the structure, this is a mixture of temlate page.php and archive.php.
 * This is a normal page, which can adjust the Admin, but it contains a nested Loop with its own query.
 *
 *   $comments_query (array)  - Comments query arguments (used for reviews and other CCTs)
 *   $dashboard_type (string) - Type of dashboard i.e. 'service', 'reviews', etc.
 *   $total_entries  (int)    - Total found posts/comments/users
 *
 * @see TR_Dashboard_Home() class for variables definitions
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

		<section id="projects" class="<?php echo esc_attr( $dashboard_type . '-section' ); ?>">

			<?php do_action( 'appthemes_before_dashboard_loop', $dashboard_type ); ?>

			<?php get_template_part( 'loop-header-dashboard', $dashboard_type ); ?>

			<?php get_template_part( 'loop-dashboard', $dashboard_type ); ?>

			<?php get_template_part( 'loop-footer', app_template_base() ); ?>

			<?php do_action( 'appthemes_after_dashboard_loop', $dashboard_type ); ?>

			<?php /* End of nested Loop */ ?>

		</section><!-- end #projects -->

		<?php appthemes_after_page(); ?>

		<?php endwhile; ?>

		<?php appthemes_after_page_loop(); ?>

	</main><!-- end #main -->

	<div id="sidebar" class="large-3 medium-4 columns dashboard-side" role="complementary">

		<?php get_template_part( 'sidebar-dashboard', $dashboard_type ); ?>

	</div>

</div><!-- end row -->