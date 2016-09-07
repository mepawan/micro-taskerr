<?php
/**
 * Search index
 *
 * @package Taskerr\Search
 * @author  AppThemes
 * @since   Taskerr 1.1
 */

add_action( 'init', '_tr_setup_build_search_index', 100 );
add_action( 'init', 'tr_register_search_index_items', 10 );
// update Search Index
add_action( 'tr_new_service_added', 'appthemes_update_search_index' );
add_action( 'tr_service_updated', 'appthemes_update_search_index' );
// load filters only on frontend
if ( ! is_admin() ) {
	add_filter( 'posts_join', 'tr_search_join' );
	add_filter( 'posts_where', 'tr_search_where' );
	add_filter( 'posts_groupby', 'tr_search_groupby' );
}

/**
 * Build Search Index for past items
 */
function _tr_setup_build_search_index() {
	if ( ! current_theme_supports( 'app-search-index' ) ) {
		return;
	}

	appthemes_add_instance( 'APP_Build_Search_Index' );
}

/**
 * Register items to index, post types, taxonomies, and custom fields
 */
function tr_register_search_index_items() {
	if ( ! current_theme_supports( 'app-search-index' ) ) {
		return;
	}

	// Service listings
	$listing_index_args = array(
		//'meta_keys'  => tr_get_special_fields(),
		'taxonomies' => array( TR_SERVICE_CATEGORY, TR_SERVICE_TAG ),
	);
	APP_Search_Index::register( TR_SERVICE_PTYPE, $listing_index_args );

	/* Search works only for Services for now */
	/*
	// Blog posts
	$post_index_args = array(
		'taxonomies' => array( 'category', 'post_tag' ),
	);
	APP_Search_Index::register( 'post', $post_index_args );

	// Pages
	APP_Search_Index::register( 'page' );
	*/
}

/**
 * Whether the Search Index is ready to use
 */
function tr_search_index_enabled() {
	if ( ! current_theme_supports( 'app-search-index' ) ) {
		return false;
	}

	return apply_filters( 'tr_search_index_enabled', appthemes_get_search_index_status() );
}

/**
 * Modifies JOIN clause of the search query
 *
 * @global wpdb     $wpdb     WordPress Database Access Abstraction Object
 * @global WP_Query $wp_query The WordPress Query class
 * @param  array    $join     Array of JOIN clauses to be modified
 *
 * @return array Array of modified JOIN clauses
 */
function tr_search_join( $join ) {
	global $wpdb, $wp_query;

	if ( is_search() && isset( $_GET['s'] ) ) {

		if ( ! tr_search_index_enabled() ) {
			$join  = " INNER JOIN $wpdb->term_relationships AS r ON ($wpdb->posts.ID = r.object_id) ";
			$join .= " INNER JOIN $wpdb->term_taxonomy AS x ON (r.term_taxonomy_id = x.term_taxonomy_id) ";
			$join .= " AND (x.taxonomy = '".TR_SERVICE_TAG."' OR x.taxonomy = '".TR_SERVICE_CATEGORY."' OR 1=1) "; // the custom taxonomies
		}

		// if a single category is selected, limit results to that cat only
		$catid = $wp_query->query_vars['cat'];

		if ( ! empty( $catid ) ) {

			// put the catid into an array
			(array) $include_cats[] = $catid;

			// get all sub cats of catid and put them into the array
			$descendants = get_term_children( (int) $catid, $tax_cat );

			foreach( $descendants as $key => $value )
				$include_cats[] = $value;

			// take catids out of the array and separate with commas
			$include_cats = "'" . implode("', '", $include_cats) . "'";

			// add the category filter to show anything within this cat or it's children
			$join .= " INNER JOIN $wpdb->term_relationships AS tr2 ON ($wpdb->posts.ID = tr2.object_id) ";
			$join .= " INNER JOIN $wpdb->term_taxonomy AS tt2 ON (tr2.term_taxonomy_id = tt2.term_taxonomy_id) ";
			$join .= " AND tt2.term_id IN ($include_cats) ";

		}

		if ( ! tr_search_index_enabled() ) {
			$join .= " INNER JOIN $wpdb->postmeta AS m ON ($wpdb->posts.ID = m.post_id) ";
			$join .= " INNER JOIN $wpdb->terms AS t ON x.term_id = t.term_id ";
		}

		remove_filter( 'posts_join', 'tr_search_join' );
	}

	return $join;
}

/**
 * Modifies WHERE clause of the search query
 *
 * @global wpdb       $wpdb       WordPress Database Access Abstraction Object
 * @global WP_Query   $wp_query   The WordPress Query class
 * @global scbOptions $tr_options Theme options object
 * @param  array      $where      Array of WHERE clauses to be modified
 *
 * @return array Array of modified WHERE clauses
 */
function tr_search_where( $where ) {
	global $wpdb, $wp_query, $tr_options;

	$old_where = $where; // intercept the old where statement

	if ( is_search() && isset( $_GET['s'] ) ) {

		// put the custom fields into an array
		$customs = array();
		//$customs = tr_get_special_fields();

		$query = '';

		$var_q = stripslashes( $_GET['s'] );

		if ( isset( $_GET['sentence'] ) || $var_q == '' ) {
			$search_terms = array( $var_q );
		} else {
			preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $var_q, $matches);
			$search_terms = array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);
		}

		$n = isset( $_GET['exact'] ) ? '' : '%';
		$searchand = '';

		foreach ( (array) $search_terms as $term ) {
			$term = addslashes_gpc( $term );

			$query .= "{$searchand}(";

			if ( ! tr_search_index_enabled() ) {
				$query .= "($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
				$query .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";
				$query .= " OR ((t.name LIKE '{$n}{$term}{$n}')) OR ((t.slug LIKE '{$n}{$term}{$n}'))";

			foreach( $customs as $custom ) {
				$query .= " OR (";
				$query .= "(m.meta_key = '$custom')";
				$query .= " AND (m.meta_value  LIKE '{$n}{$term}{$n}')";
				$query .= ")";
			}

			} else {
				$query .= "($wpdb->posts.post_content_filtered LIKE '{$n}{$term}{$n}')";
			}

			$query .= ")";
			$searchand = ' AND ';
		}

		$term = esc_sql( $var_q );
		if ( ! isset( $_GET['sentence'] ) && count( $search_terms ) > 1 && $search_terms[0] != $var_q ) {
			if ( ! tr_search_index_enabled() ) {
				$query .= " OR ($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
				$query .= " OR ($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";
			} else {
				$query .= " OR ($wpdb->posts.post_content_filtered LIKE '{$n}{$term}{$n}')";
			}
		}

		if ( ! empty( $query ) ) {

			$where = " AND ({$query}) AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'unreliable') ";

			// setup the array for post types
			$post_type_array = array();

			// always include the custom post type
			$post_type_array[] = TR_SERVICE_PTYPE;

			// check to see if we include blog posts
			//if ( ! $tr_options->search_ex_blog )
			//	$post_type_array[] = 'post';

			// check to see if we include pages
			//if ( ! $tr_options->search_ex_pages )
			//	$post_type_array[] = 'page';

			// build the post type filter sql from the array values
			$post_type_filter = "'" . implode("','",$post_type_array). "'";

			// return the post type sql to complete the where clause
			$where .= " AND ($wpdb->posts.post_type IN ($post_type_filter)) ";

		}

		remove_filter( 'posts_where', 'tr_search_where' );
	}

	return $where;
}


/**
 * Connects the custom search by groupby
 *
 * @global wpdb       $wpdb       WordPress Database Access Abstraction Object
 * @global WP_Query   $wp_query   The WordPress Query class
 * @param  string     $groupby    GROUPBY clause to be modified
 *
 * @return string modified GROUPBY clause
 */
function tr_search_groupby( $groupby ) {
	global $wpdb, $wp_query;

	if ( is_search() && isset( $_GET['s'] ) ) {
		$groupby = "$wpdb->posts.ID";

		remove_filter( 'posts_groupby', 'tr_search_groupby' );
	}

	return $groupby;
}