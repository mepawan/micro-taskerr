<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<div class="singular-row row">

	<?php do_action( 'appthemes_notices' ); ?>

	<main id="main" class="large-9 medium-8 columns" role="main">
		<section class="error-404 not-found">

			<header class="page-header">
				<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', APP_TD ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p>
					<?php _e( "The page or listing you are trying to reach no longer exists or has expired.", APP_TD ); ?>
				</p>
			</div>
		</section>
	</main>

	<div id="sidebar" class="large-3 medium-4 columns" role="complementary">
		<?php get_sidebar( app_template_base() ); ?>
	</div>

</div><!-- end row -->