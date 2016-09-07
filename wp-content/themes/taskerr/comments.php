<?php
/**
 * The template for displaying Comments.
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
	<section id="comments" class="feedback-section">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', APP_TD ); ?></p>
	</section><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php appthemes_before_comments(); ?>

	<?php if ( have_comments() ) : ?>
		<header>
			<h2 class="comments-title">
				<?php
					printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), APP_TD ),
						number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
				?>
			</h2>
		</header>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', APP_TD ) ); ?></div>
			<div class="nav-next right"><?php next_comments_link( __( 'Newer Comments &rarr;', APP_TD ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">

			<?php appthemes_list_comments();

			/* Loop through and list the comments. Tell wp_list_comments()
			 * to use twentyeleven_comment() to format the comments.
			 * If you want to overload this in a child theme then you can
			 * define twentyeleven_comment() and that will be used instead.
			 * See twentyeleven_comment() in twentyeleven/functions.php for more.
			 */
			//wp_list_comments( array( 'callback' => 'twentyeleven_comment' ) );
			wp_list_comments( array(
				'avatar_size'=> 85,
				'callback' => 'tr_html5_comment',
			) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', APP_TD ) ); ?></div>
			<div class="nav-next right"><?php next_comments_link( __( 'Newer Comments &rarr;', APP_TD ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<hr />

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', APP_TD ); ?></p>
	<?php endif; ?>

	<?php appthemes_after_comments(); ?>

	<?php appthemes_before_respond(); ?>

	<?php appthemes_before_comments_form(); ?>

	<?php comment_form(); ?>

	<?php appthemes_after_comments_form(); ?>

	<?php appthemes_after_respond(); ?>

</section><!-- #comments -->
