<?php
/**
 * None posts
 *
 * @todo Fix taxonomy messages
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
	<div class="page-content">
		<p>
			<?php
			if ( is_search() ) :
				printf( __( 'Sorry, no %s were found for "%s"', APP_TD ), ( 'any' === $post_type ) ? __( 'Entries', APP_TD ) : get_post_type_object( $post_type )->labels->name , get_search_query() );
			elseif ( is_archive() ) :
				if ( is_tax() ) :
					printf( __( 'Sorry there are no %s for %s "%s"', APP_TD ), __( 'Entries', APP_TD ), get_taxonomy_labels( get_taxonomy( $taxonomy ) )->singular_name, single_term_title( '', false ) );
				else:
					printf( __( 'Sorry there are no %s yet', APP_TD ), ( 'any' === $post_type ) ? __( 'Entries', APP_TD ) : get_post_type_object( $post_type )->labels->name );
				endif;
			elseif ( is_singular() ) :
					printf( __( 'Sorry there is no such %s', APP_TD ), get_post_type_object( $post_type )->labels->singular_name );
			else:
				_e( 'Sorry there is nothing yet', APP_TD );
			endif;
			?>
		<p>
	</div>