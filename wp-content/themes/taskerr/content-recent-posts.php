<?php
/**
 * Generic Recent Posts content template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
				<li <?php post_class( 'recent-box' ); ?>>

					<div class="recent-box-thumb left">

						<?php if ( $instance['show_thumbnail'] ) { ?>

							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >

								<?php if ( has_post_thumbnail() ) { ?>

									<?php the_post_thumbnail( 'recent-posts-widget' ); ?>

								<?php } else { ?>

									<?php echo get_avatar( get_the_author_meta( 'ID' ), 60 ); ?>

								<?php } ?>

							</a>

						<?php } ?>

						<?php if ( $instance['show_rating'] ) { ?>
							<div class="recent-stars">
								<?php if ( TR_SERVICE_PTYPE === $instance[ 'post_type' ] ) {
									tr_service_reviews_stars();
								} elseif ( defined( 'STARSTRUCK_KEY' ) ) {
									echo starstruck_mini_ratings( $instance[ 'post_type' ] );
								} ?>
							</div>
						<?php } ?>

						</div><!-- end recent-box-thumb -->



					<div class="recent-box-content">

						<h2 class="recent-box-title">
							<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr( get_the_title() ? get_the_title() : get_the_ID() ); ?>">
								<?php if ( get_the_title() ) the_title(); else the_ID(); ?>
							</a>
						</h2>

						<?php if ( isset( $instance['show_excerpt'] ) && $instance['show_excerpt'] ) {
							the_excerpt();
						} ?>

						<?php if ( $instance['show_readmore'] ) : ?>
							<i class="clearfix"><a href="<?php the_permalink(); ?>"><?php _e( 'Read More', APP_TD );?></a></i>
						<?php endif; ?>

						<span class="recent-post-date clearfix">
							<?php echo __( 'by', APP_TD ) . "&nbsp;";
							tr_author_link();
							if ( $instance['show_date'] ) {
								echo "&nbsp;" . __( 'on', APP_TD ) . "&nbsp;" . get_the_date();
							} ?>
						</span>
					</div>
				</li><!-- end recent-box -->