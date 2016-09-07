<?php
/**
 * Generic template for displaying reviews
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

$comments_query = ( empty( $comments_query ) ) ? appthemes_get_post_reviews( get_the_ID() )->reviews : $comments_query;

if ( ! empty( $comments_query ) ) { ?>
	<ol class="commentlist">
		<?php foreach( $comments_query as $review ) { ?>
			<li id="review-<?php echo $review->get_id(); ?>" <?php comment_class( '', $review->get_id() ); ?>>
				<article id="div-comment-<?php echo $review->get_id(); ?>" class="comment-body">
					<header class="comment-meta">
						<div class="comment-author">
							<?php echo get_avatar( $review->get_author_ID(), 85 ); ?>
							<h3 class="comment-title">
								<span class="fn">
									<?php tr_review_stars( $review->get_rating() ); ?>
									<a href="<?php echo get_author_posts_url( $review->get_author_ID() ); ?>">
										<?php the_author_meta( 'display_name', $review->get_author_ID() ); ?>
									</a>
								</span>
								<?php _e( 'on', APP_TD ); ?>
								<a class="review-meta" href="<?php echo esc_url( get_comment_link( $review->get_id() ) ); ?>">
									<time datetime="<?php $review->get_date(); ?>">
										<?php echo mysql2date( get_option( 'date_format' ), $review->get_date() ); ?>
									</time>
								</a>

								<?php if ( ! is_single( $review->get_post_ID() ) ) { ?>
									<?php _e( 'about', APP_TD ); ?>
									<a class="review-meta" href="<?php echo get_permalink( $review->get_post_ID() ); ?>">
										<?php echo get_the_title( $review->get_post_ID() ); ?>
									</a>
								<?php } ?>
							</h3>
						</div><!-- .comment-author -->
					</header><!-- .comment-meta -->

					<div class="comment-content">
						<?php echo $review->get_content(); ?>
					</div><!-- .comment-content -->

				</article><!-- .comment-body -->
			</li>
		<?php } ?>
	</ol>
<?php }

if ( empty( $comments_query ) ) { ?>
	<p><?php _e( 'There are no reviews yet.', APP_TD ) ?></p>
<?php }