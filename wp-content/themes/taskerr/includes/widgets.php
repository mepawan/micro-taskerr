<?php
/**
 * Theme specific widgets or widget overrides
 *
 * @package Taskerr\Widgets
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Foundation-adapted version of APP_Widget_Taxonomy_List
 */
class TR_Widget_Taxonomy_List extends APP_Widget_Taxonomy_List {

	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base' => 'tr_taxonomy_list',
			'name' => __( 'Taskerr Taxonomy List', APP_TD ),
			'defaults' => array(
				'style_url' => '',
				'cat_nocatstext' => true,
			),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args );
	}

	public function content( $instance ) {
		$instance = array_merge( $this->defaults, (array) $instance );
		$terms_defaults = array();

		if ( is_tax( $instance['taxonomy'] ) && true == $instance['archive_responsive'] ) {
			$terms_defaults['child_of'] = get_queried_object_id();
		}


		$large_num = ( isset( $instance['menu_cols'] ) && $instance['menu_cols'] ) ? (int) $instance['menu_cols'] : 1;
		$small_num = 1;
		$medium_num = 1;

		if ( $large_num > 1 ) {
			$small_num = 2;
			if ( $large_num > 5 ) {
				$medium_num = 4;
			}
		}
		$class = "maincat-list small-block-grid-$small_num medium-block-grid-$medium_num large-block-grid-$large_num";

		$instance['menu_cols'] = 1;
		$output = appthemes_categories_list( $instance, $terms_defaults );

		$output = str_replace( 'maincat-list', $class, $output );
		$button = '<a href="#" class="cat-down button"><i class="genericon genericon-expand"></i><span>' . __( 'Expand/Collapse', APP_TD ) . '</span></a>';
		echo $button, $output;
	}

	protected function form_fields() {
		$form_fieds = parent::form_fields();

		foreach ( $form_fieds as $i => $form_fied ) {
			if ( array_key_exists( 'name', $form_fied ) && $form_fied['name'] == 'cat_nocatstext' ) {
				unset( $form_fieds[ $i ] );
			}
		}

		return $form_fieds;
	}

}

/**
 * Foundation-adapted version of APP_Widget_125_Ads
 */
class TR_Widget_125_Ads extends APP_Widget_125_Ads {

	public function __construct( $args = array() ) {

		$images_url = get_template_directory_uri() . '/img/';

		$default_args = array(
			'id_base' => 'tr_125_ads',
			'name' => __( 'Taskerr 125x125 Ads', APP_TD ),
			'defaults' => array(
				'ads' => "https://www.appthemes.com|" . $images_url . "ad-125.png|Ad 1|nofollow\n"
						."https://www.appthemes.com|" . $images_url . "ad-125.png|Ad 2|follow\n"
						."https://www.appthemes.com|" . $images_url . "ad-125.png|Ad 3|nofollow\n"
						."https://www.appthemes.com|" . $images_url . "ad-125.png|Ad 4|follow",
				// Internal custom options
				'style_url' => '',
				'images_url' => $images_url,
			),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args );
	}

	public function content( $instance ) {
		ob_start();
		parent::content( $instance );
		$html = ob_get_clean();
		$html = str_replace( array( '<li>', '<li class="alt">' ), '<li class="white-con">', $html );
		echo str_replace( '</li>', '</li> ', $html );
	}
}


/**
 * Foundation-adapted version of APP_Widget_Recent_Posts
 */
class TR_Widget_Recent_Posts extends APP_Widget_Recent_Posts{

	public function __construct( $args = array() ) {

		$defaults = array( 'name' => __( 'Taskerr Recent Posts', APP_TD ) );
		$args = $this->_array_merge_recursive( $defaults, $args );

		parent::__construct( $args );
	}

	function widget( $args, $instance ) {
		$args['after_widget'] = '</ul>' . $args['after_widget'];
		parent::widget( $args, $instance );
	}

	protected function form_fields() {
		$fields = parent::form_fields();
		$fields[] = array(
			'type'		 => 'checkbox',
			'name'		 => 'show_excerpt',
			'desc'		 => __( 'Display post excerpt:', APP_TD ),
		);
		return $fields;
	}

	public function sub_widget() {
		self::$i++;
		if ( 1 === self::$i ) {
			echo '<ul>';
		}
	}
}

/**
 * Recent posts by given author
 */
class TR_Widget_Author_Recent_Posts extends TR_Widget_Recent_Posts {
	protected function query_posts( $instance ) {
		$q_args = array(
			'post_type'		 => $instance[ 'post_type' ],
			'posts_per_page' => $instance[ 'number' ],
			'author'         => $instance[ 'author_id' ],
			'no_found_rows'	 => true,
			'post_status'	 => 'publish',
		);

		$post__in = array_map( 'trim', explode( ',', $instance[ 'post__in' ] ) );

		if ( ! empty( $post__in[0] ) )
			$q_args['post__in'] = $post__in;

		if ( $instance['sticky'] )
			$q_args['post__in'] = get_option( 'sticky_posts' );
		else
			$q_args['ignore_sticky_posts'] = true;

		return new WP_Query( $q_args );
	}
}

add_filter( 'widget_comments_args', 'tr_widget_comments_args' );

/**
 * Changes args of Comments widget to display them with only 'comment' type
 *
 * @param array $args widget-comments args
 * @return array
 */
function tr_widget_comments_args( $args ) {
	$args['type'] = 'comment';
	return $args;
}