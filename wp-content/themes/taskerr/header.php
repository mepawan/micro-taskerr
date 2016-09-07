<?php
/**
 * The header.
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
		<div class="row">
			<div class="large-10 large-centered columns">
				<?php get_template_part( 'advert-top', app_template_base() ); ?>
			</div><!-- end columns -->
		</div><!-- end row -->

		<div class="row">
			<div class="large-12 columns">
				<?php get_template_part( 'top-bar', app_template_base() ); ?>
			</div><!-- end columns -->
		</div><!-- end row -->

		<div class="row">
			<div class="large-12 columns site-header">
				<div class="clearfix">
					<h1 class="site-title left"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description right"><?php bloginfo( 'description' ); ?></h2>
				</div>

				<?php if ( get_header_image() ) { ?>
					<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php header_image(); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
					</a>
				<?php } ?>

				<?php get_template_part( 'nav', app_template_base() ); ?>
			</div><!-- end columns -->
		</div><!-- end row -->