<?php
/**
 * Taxonomies helpers
 *
 * @package Taskerr\CPT-Helper
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Taxonomies list walker
 */
class TR_Multiple_Category_Checklist_Walker extends Walker {

	var $tree_type = 'category';
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		extract($args);
		if ( empty($taxonomy) )
			$taxonomy = 'category';

		if ( !empty( $name ) ) {

		} else if ( $taxonomy == 'category' ) {
			$name = 'post_category';
		} else {
			$name = 'tax_input['.$taxonomy.']';
		}

		$class = in_array( $category->term_id, $popular_cats ) ? 'popular-category ' : '';

		$input_class = !empty( $input_class ) ? ' class="' . $input_class . '"' : '';

		$label = apply_filters('the_category', $category->name );
		$label = apply_filters('tr_multiple_category_checklist_label', $label, $category, $taxonomy );

		$output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" . '<label class="selectit"><input value="' . $category->term_id . '" type="checkbox" ' . $input_class . ' name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . disabled( empty( $args['disabled'] ), false, false ) . ' /> ' . esc_html( $label ) . '</label>';

		if ( $hidden_on_disabled && $args['disabled'] ) {
			$output .= "\n".'<input type="hidden" name="'.$name.'[]" value="' . $category->term_id . '" />';
		}
	}
}

function tr_terms_checklist($post_id = 0, $args = array()) {
 	$defaults = array(
		'descendants_and_self' => 0,
		'selected_cats' => false,
		'popular_cats' => false,
		'walker' => null,
		'taxonomy' => 'category',
		'checked_ontop' => true,
		'disabled' => null,
		'hidden_on_disabled' => true,
		'name' => '',
		'input_class' => '',
		'get_terms' => array()
	);

	$args = apply_filters( 'wp_terms_checklist_args', $args, $post_id );
	$args = apply_filters( 'tr_terms_checklist_args', $args, $post_id );

	extract( wp_parse_args($args, $defaults), EXTR_SKIP );

	if ( empty($walker) || !is_a($walker, 'Walker') )
		$walker = new Walker_Category_Checklist;

	$descendants_and_self = (int) $descendants_and_self;

	$args = array('taxonomy' => $taxonomy);

	$tax = get_taxonomy($taxonomy);
	$args['disabled'] = !is_null( $disabled ) ? $disabled : !current_user_can($tax->cap->assign_terms);
	$args['hidden_on_disabled'] = $hidden_on_disabled;
	$args['name'] = $name;
	$args['input_class'] = $input_class;

	if ( is_array( $selected_cats ) )
		$args['selected_cats'] = $selected_cats;
	elseif ( $post_id )
		$args['selected_cats'] = wp_get_object_terms($post_id, $taxonomy, array_merge($args, array('fields' => 'ids')));
	else
		$args['selected_cats'] = array();

	if ( is_array( $popular_cats ) )
		$args['popular_cats'] = $popular_cats;
	else
		$args['popular_cats'] = get_terms( $taxonomy, array( 'fields' => 'ids', 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );



	if ( $descendants_and_self ) {
		$categories = (array) get_terms($taxonomy, array_merge( $get_terms, array( 'child_of' => $descendants_and_self, 'hierarchical' => 0, 'hide_empty' => 0 ) ) );
		$self = get_term( $descendants_and_self, $taxonomy );
		array_unshift( $categories, $self );
	} else {
		$categories = (array) get_terms($taxonomy, array_merge( $get_terms, array('get' => 'all') ) );
	}

	if ( $checked_ontop ) {
		// Post process $categories rather than adding an exclude to the get_terms() query to keep the query the same across all posts (for any query cache)
		$checked_categories = array();
		$keys = array_keys( $categories );

		foreach( $keys as $k ) {
			if ( in_array( $categories[$k]->term_id, $args['selected_cats'] ) ) {
				$checked_categories[] = $categories[$k];
				unset( $categories[$k] );
			}
		}

		// Put checked cats on top
		echo call_user_func_array(array(&$walker, 'walk'), array($checked_categories, 0, $args));
	}
	// Then the rest of them
	echo call_user_func_array(array(&$walker, 'walk'), array($categories, 0, $args));
}


function tr_multiple_category_checkboxes( $taxonomy, $selected_cats = array(), $disabled = false ) {

	$get_terms = array();

	ob_start();
	tr_terms_checklist( 0, array(
		'taxonomy' => $taxonomy,
		'selected_cats' => $selected_cats,
		'checked_ontop' => false,
		'disabled' => $disabled,
		'hidden_on_disabled' => true,
		'get_terms' => $get_terms,
		'input_class' => 'required',
		'walker' => new TR_Multiple_Category_Checklist_Walker(),
	) );

	$output = ob_get_clean();

	return $output;
}


function tr_get_edit_categories( $post, $label, $taxonomy, $disabled = false ) {

	if ( isset( $_POST['tax_input'] ) && isset( $_POST['tax_input'][$taxonomy] ) )
		$selected_categories = $_POST['tax_input'][$taxonomy];
	elseif ( !empty( $post->categories ) )
		$selected_categories = array_keys( $post->categories );
	else
		$selected_categories = array();

	echo tr_multiple_category_checkboxes( $taxonomy, $selected_categories, $disabled );
}
