<?php
/**
 * Generic Loop Header
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<header class="archive-headline row">

	<div class="archive-title large-12 columns">
		<h1 class="page-title">
			<?php
				if ( is_home() ) :
					single_post_title();

				elseif ( is_search() ) :
					_e( 'Search', APP_TD );

				elseif ( is_category() ) :
					single_cat_title();

				elseif ( is_tag() ) :
					single_tag_title();

				//elseif ( is_author() ) :
					/* Queue the first post, that way we know
					 * what author we're dealing with (if that is the case).
					*/
					//the_post();
					//printf( __( 'Author: %s', APP_TD ), '<span class="vcard">' . get_the_author() . '</span>' );
					/* Since we called the_post() above, we need to
					 * rewind the loop back to the beginning that way
					 * we can run the loop properly, in full.
					 */
					//rewind_posts();

				elseif ( is_day() ) :
					printf( __( 'Day: %s', APP_TD ), '<span>' . get_the_date() . '</span>' );

				elseif ( is_month() ) :
					printf( __( 'Month: %s', APP_TD ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

				elseif ( is_year() ) :
					printf( __( 'Year: %s', APP_TD ), '<span>' . get_the_date( 'Y' ) . '</span>' );

				elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
					_e( 'Asides', APP_TD );

				elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
					_e( 'Images', APP_TD);

				elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
					_e( 'Videos', APP_TD );

				elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
					_e( 'Quotes', APP_TD );

				elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
					_e( 'Links', APP_TD );
				elseif ( is_tax() ) :
					echo single_term_title();
				elseif ( is_archive() ) :
					echo post_type_archive_title( __( 'Latest ', APP_TD ) );
				else :
					_e( 'Archives', APP_TD );

				endif;
			?>
		</h1>
	</div>
	<div class="archive-description">
		<?php
		if ( is_tax() ) {
			echo term_description();
		} elseif ( is_search() ) {
			printf( __( '%s found for "%s" (%s)', APP_TD ), ( 'any' === $post_type ) ? __( 'Entries', APP_TD ) : get_post_type_object( $post_type )->labels->name, get_search_query(), $wp_query->found_posts);
		} ?>
	</div>
</header><!-- .page-header -->