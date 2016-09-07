<?php
/**
 * Loop header for the "Service" archives
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
		<header class="archive-headline row">
			<div class="archive-title large-6 medium-5  columns">
				<h1>
					<?php tr_services_archive_title();
					if ( is_tax() )
						echo single_term_title(); ?>
				</h1>
			</div>

			<div class="archive-sorting large-4 medium-5 left columns">
				<?php get_template_part( 'sort', TR_SERVICE_PTYPE ); ?>
			</div>
		</header>