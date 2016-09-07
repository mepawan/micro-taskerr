<?php
/**
 * Dashboard Services loop content
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php tr_post_class( 'loop-content' ); ?>>
	<div class="row collapse">

		<div class="post-main large-10 medium-10 columns white-con">

			<div class="post-info">

				<div class="row collapse stat-line" data-equalizer>
					<div class="large-4 small-12 columns" data-equalizer-watch>
						<p>
							<span><?php _e( 'Pending Tasks', APP_TD );?></span>:
							<span><?php tr_tasks_pending(); ?></span>
						</p>
						<p>
							<span><?php _e( 'Completed Tasks', APP_TD );?></span>:
							<span><?php tr_tasks_completed(); ?></span>
						</p>
					</div>
					<div class="separator" data-equalizer-watch></div>
					<div class="large-3 small-12 columns" data-equalizer-watch>
						<p>
							<strong><?php _e( 'Rating', APP_TD );?></strong>
							<strong>(<?php echo appthemes_get_post_avg_rating( get_the_ID() );?>)</strong>
							<br class="hide-for-small-only"/>
							<?php tr_service_reviews_stars(); ?>
						</p>
					</div>
					<div class="separator" data-equalizer-watch></div>
					<div class="large-3 end small-12 columns" data-equalizer-watch>
						<p>
							<strong>
								<?php printf( _n( 'One Review', '%s Reviews', appthemes_get_post_total_reviews( get_the_ID() ), APP_TD ),
									number_format_i18n( appthemes_get_post_total_reviews( get_the_ID() ) ) ); ?>
							</strong>
							<br class="hide-for-small-only"/>
							<a href="<?php echo tr_get_service_reviews_url(); ?>"><?php _e( 'View reviews', APP_TD );?></a>
						</p>
					</div>
				</div><!-- end row -->

				<div class="features row">

					<?php appthemes_before_post_title( $post_type ); ?>
					<header>
						<h1><a href="<?php the_permalink(); ?>" rel="bookmark">
							<span class="<?php echo esc_attr( tr_service_featured_class() ); ?>">
								<?php the_title(); ?>
							</span>
						</a></h1>
					</header>
					<?php appthemes_after_post_title( $post_type ); ?>

					<?php appthemes_before_post_content( $post_type ); ?>
					<div class="service-description">
						<?php the_excerpt(); ?>
					</div>
					<?php appthemes_after_post_content( $post_type ); ?>

				</div>
			</div>

			<!-- service meta author-->
			<div class="ft-bar row">
				<div class="post-meta service-category large-6 medium-6 small-12 columns left">
					<i class="genericon genericon-category"></i>
					<?php _e( 'Category:', APP_TD ); ?>
					<?php tr_service_categories(); ?>
				</div>

				<?php if ( has_term( '', TR_SERVICE_TAG ) ) { ?>
					<div class="post-meta service-tags large-6 medium-6 small-12 columns">
						<i class="genericon genericon-tag"></i>
						<?php _e( 'Tags:', APP_TD ); ?>
						<span><?php tr_service_tags(); ?></span>
					</div>
				<?php } ?>
			</div>
		</div><!-- end 10-columns -->

		<div class="btn-bar large-2 medium-2 columns">
			<div class="price"><?php tr_service_price(); ?></div>
			<a href="<?php echo tr_get_service_edit_url(); ?>" class="button success">
				<span><?php _e( 'Edit', APP_TD );?></span>
			</a>
			<?php if ( TR_SERVICE_STATUS_EXPIRED === get_post_status( get_the_ID() ) ) { ?>
			<a href="<?php echo tr_get_service_renew_url( get_the_ID() ); ?>" class="button secondary">
				<span><?php _e( 'Renew', APP_TD );?></span>
			</a>
			<?php } ?>
			<a href="<?php echo tr_get_delete_service_url( get_the_ID() ); ?>" class="button alert delete-service">
				<span><?php _e( 'Delete', APP_TD );?></span>
			</a>
		</div>

	</div><!-- end row -->
</article>