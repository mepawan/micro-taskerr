<?php
/**
 * Generic single comment template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
?>
		<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<header class="comment-meta">
					<div class="comment-author">
						<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
						<h3 class="comment-title">
							<?php printf( '<span class="fn">%s</span>', get_comment_author_link() ); ?>
							<?php _e( 'on', APP_TD ); ?>
							<a class="review-meta" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>">
									<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', APP_TD ), get_comment_date(), get_comment_time() ); ?>
								</time>
							</a>
						</h3>
					</div><!-- .comment-author -->



					<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', APP_TD ); ?></p>
					<?php endif; ?>
				</header><!-- .comment-meta -->

				<div class="comment-content">
					<?php comment_text(); ?>
					<span class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						<?php edit_comment_link( '| ' . __( 'Edit', APP_TD ) ); ?>
					</span><!-- .reply -->
				</div><!-- .comment-content -->


			</article><!-- .comment-body -->