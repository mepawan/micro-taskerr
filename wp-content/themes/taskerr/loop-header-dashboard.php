<?php
/**
 * Loop header on dashboards
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
		<header class="archive-headline row">
			<div class="archive-title large-7 medium-6  columns">
				<h1><?php echo the_title() . "&nbsp;($total_entries)"; ?></h1>
			</div>
			<div class="archive-sorting large-5 medium-6 columns">
				<?php get_template_part( 'sort-dashboard', $dashboard_type ); ?>
			</div>
		</header>