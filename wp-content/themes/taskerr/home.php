<?php
/**
 * Blog Template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<div class="archive-row row">

	<?php do_action( 'appthemes_notices' ); ?>

	<main id="main" class="large-9 medium-8 columns" role="main">

		<section id="posts">

			<?php get_template_part( 'loop-header', $post_type ); ?>

			<?php get_template_part( 'loop', $post_type ); ?>

			<?php get_template_part( 'loop-footer', $post_type ); ?>

		</section><!-- end #posts -->

	</main><!-- end #main -->

	<div id="sidebar" class="large-3 medium-4 columns" role="complementary">

		<?php get_sidebar( 'blog' ); ?>

	</div>

</div><!-- end row -->