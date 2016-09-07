<?php
/**
 * Display functions
 *
 * @package Taskerr\Display
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

add_action( 'wp_enqueue_scripts', 'tr_add_scripts' );
add_action( 'wp_enqueue_scripts', 'tr_add_styles' );
add_filter( 'body_class', 'tr_global_body_class' );
add_filter( 'oembed_result', 'tr_oembed_ajax_result' );
add_filter( 'get_comments_number', 'tr_comment_count', 0 );


function tr_global_body_class( $classes ) {
	$classes[] = 'no-js';
	return $classes;
}

function tr_add_styles() {
	global $tr_options;

	// Fonts
	wp_enqueue_style(
		'googleFonts',
		'http://fonts.googleapis.com/css?family=Lato:300,400,700,900,400italic|Noto+Serif',
		false,
		TR_VERSION
	);

	wp_enqueue_style(
		'genericons',
		get_template_directory_uri() .'/styles/genericons/genericons.css',
		false,
		'3.0.3'
	);

	// Foundation styles
	wp_enqueue_style( 'tr-foundation-styles' );

	if ( is_child_theme() ) {
		return;
	}

	wp_enqueue_style(
		'tr-color',
		get_template_directory_uri() . "/styles/$tr_options->color.css",
		array(),
		TR_VERSION
	);
}

function tr_add_scripts() {

	wp_enqueue_script(
		'main',
		get_template_directory_uri() .'/scripts/scripts.js',
		array( 'tr-foundation-scripts' ),
		TR_VERSION,
		true
	);


	if ( is_singular() ) {

		if ( is_single() ) {
			wp_enqueue_style( 'colorbox' );
			wp_enqueue_script( 'colorbox' );
		}

		if ( comments_open() && 1 == get_option( 'thread_comments' ) ) {
			wp_enqueue_script('comment-reply');
		}
	}


	wp_localize_script( 'main', 'Taskerr', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'current_url' => scbUtil::get_current_url()
	) );
}

function tr_pagination( $wp_query = '', $query_var = 'paged', $args = array() ){
	global $tr_options;

	if( empty( $wp_query ) )
		global $wp_query;

	$links = appthemes_pagenavi( $wp_query, $query_var, array_merge( array(
		'type' => 'array',
       	'echo' => false,
		'prev_text' => "&laquo;",
		'next_text' => "&raquo;",
	), $args ) );

	if ( ! is_array( $links ) ) {
		return;
	}

	foreach ( $links as $key => $link ) {
		$li_params = array();

		if ( preg_match( "/(prev page-numbers)/", $link ) ) {
			$li_params['class'] = 'arrow prev';
		} elseif ( preg_match( "/(next page-numbers)/", $link ) ) {
			$li_params['class'] = 'arrow next';
		} elseif ( preg_match( "/(page-numbers current)/", $link ) ) {
			$li_params['class'] = 'current';
		} elseif ( preg_match( "/(page-numbers dots)/", $link ) ) {
			$li_params['class'] = 'unavailable';
		}

		$links[ $key ] = html( 'li', $li_params, $link );
	}

	if ( is_object( $wp_query ) ) {
		$total = $wp_query->max_num_pages;
		$current = $wp_query->get( $query_var );

		if ( $current < 2 ) {
			array_unshift( $links, html( 'li', array( 'class' => 'arrow unavailable prev' ), html( 'span', "&laquo;" ) ) );
		}

		if ( $current == $total ) {
			$links[] = html( 'li', array( 'class' => 'arrow unavailable next' ), html( 'span', "&raquo;" ) );
		}
	}

	$links = join( '', $links );

	echo html( 'ul', array( 'class' => 'pagination' ), $links );

}

function tr_navigation_menu( $args = array() ){

	$defaults = array(
		'menu_class'     => 'left',
		'theme_location' => 'header',
		'container'      => false,
		'fallback_cb'    => false,
		'echo'           => false,
	);

	$args = wp_parse_args( $args, $defaults );

	$nav = wp_nav_menu( $args );

	echo str_replace(
		array( 'sub-menu', 'menu-item-has-children' ),
		array( 'sub-menu dropdown', 'menu-item-has-children has-dropdown' ),
		$nav
	);

}

function tr_oembed_ajax_result ( $html ) {
	// Make sure we filter only ajax appended html
	if ( isset( $_REQUEST['app_media_manager'] ) ) {
		$html = html( 'div class="flex-video"', $html );
	}
	return $html;
}

/**
 * Don't count reviews, pingbacks or trackbacks when determining
 * the number of comments on a post.
 */
function tr_comment_count( $count ) {
	global $id;
	$comment_count = 0;
	$comments = get_approved_comments( $id );
	foreach ( $comments as $comment ) {
		if ( $comment->comment_type === '' ) {
			$comment_count++;
		}
	}
	return $comment_count;
}