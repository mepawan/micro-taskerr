<?php
/**
 * Generic Site navigation bar
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
		<nav id="top-bar-2" class="top-bar" data-topbar>
			<ul class="title-area">
				<li class="name"></li>
				<li class="toggle-topbar menu-icon">
					<a href="#">
						<span><?php _e( 'Navigation', APP_TD ); ?></span>
					</a>

			</ul>

			<section class="top-bar-section">
				<!-- Right Nav Section -->
				<ul class="right">
					<li class="has-form">
						<div class="row collapse">
							<div class="search-bar large-12 columns">
								<?php get_search_form(); ?>
							</div>
						</div>
					</li>
				</ul>

				<!-- Left Nav Section -->
				<?php tr_navigation_menu(); ?>

			</section>
		</nav>