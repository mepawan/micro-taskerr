<?php
/**
 * Sorting Posts API
 *
 * @package Taskerr\Sorting
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Registers sorting type
 *
 * @param type $class_name
 */

function tr_register_sorting_type( $class_name = '' ) {
	if ( $class_name && class_exists( $class_name ) )
		TR_Sorting::register_sort_type( new $class_name );
}

function tr_get_sorting_args() {
	return TR_Sorting::get_query_args();
}

function tr_get_sorting_method( $type, $method ) {
	return TR_Sorting::get_sorting_method( $type, $method );
}

function tr_get_current_sorting_method( $type = '' ) {
	return TR_Sorting::get_current_method_name( $type );
}

function tr_get_sorting_url( $type, $method ) {
	return TR_Sorting::get_sorting_url( $type, $method );
}

function tr_get_sorting_panels() {
	return TR_Sorting::template_vars();
}

function tr_sorting_title( $type ) {
	echo TR_Sorting::get_sorting_title( $type );
}

class TR_Sorting {
	protected static $sort_types = array();

	static function register_sort_type( $instance ) {
		if ( ! is_a( $instance, 'TR_Sorting_Type' ) )
			return;

		self::$sort_types[ $instance->get_type() ] = $instance;
	}

	static function template_vars() {
		$panels = array();

		foreach( self::$sort_types as $var_name => $sort_type ) {
			$template_vars = $sort_type->get_template_vars();
			if ( ! empty( $template_vars ) )
				$panels[ $var_name ] = $sort_type->get_template_vars();
		}

		return $panels;
	}

	static function get_query_args() {
		$args = array();

		foreach( self::$sort_types as $var_name => $sort_type ) {
			$args = array_merge( $args, $sort_type->get_query_args() );
		}
		return $args;

	}

	static function get_sorting_url( $type, $method ) {
		$args[ $type ] = $method;

		foreach( self::$sort_types as $var_name => $sort_type ) {
			if ( $type !== $var_name )
				$args[ $var_name ] = $sort_type->get_url_arg( $type, $method );
		}
		return add_query_arg( $args );
	}

	static function get_sorting_title( $type ) {
		if ( ! isset( self::$sort_types[ $type ] ) )
			return;
		$title = '';
		$sort_type = self::$sort_types[ $type ];
		$method = $sort_type->get_current_method();
		$prefix = $sort_type->get_title();

		if ( $prefix )
			$title .= $prefix . ' ';

		if ( $method && isset( $method['content'] ) )
			$title .= $method['content'];
		else
			$title .= __( '...', APP_TD );

		return $title;
	}

	static function get_current_method_name( $type ) {
		if ( ! isset( self::$sort_types[ $type ] ) )
			return;

		return self::$sort_types[ $type ]->get_current_method_name();
	}

	static function get_sorting_method( $type, $method ) {
		if ( ! isset( self::$sort_types[ $type ] ) )
			return;

		return self::$sort_types[ $type ]->get_sorting_method( $method );
	}

	static function add_sorting_method( $type, $name, $query_args = array(), $content = '', $title = '' ) {
		if ( isset( self::$sort_types[ $type ] ) )
			self::$sort_types[ $type ]->add_sorting_method( $name, $query_args, $content, $title );
	}

}

class TR_Sorting_Type {
	protected $type, $methods, $title;

	function __construct( $var_name, $title = '' ) {
		$this->type = $var_name;
		$this->methods = $this->sorting_methods();
		$this->title = $title;
	}

	/**
	 * Retrieves expected values of registered $_GET parameter
	 *
	 * @return array
	 */
	protected function sorting_methods() {
		return array();
	}

	public function add_sorting_method( $name, $query_args = array(), $content = '', $title = '' ) {
		$this->methods[ $name ] = array(
			'args' => $query_args,
			'content' => $content,
			'title' => $title,
		);
	}

	public function get_sorting_method( $name ) {
		if ( isset( $this->methods[ $name ] ) )
			return $this->methods[ $name ];
	}

	public function get_current_method() {
		$method_name = $this->get_current_method_name();
		if ( $method_name && isset( $this->methods[ $method_name ] ) )
			return $this->methods[ $this->get_current_method_name() ];
	}

	public function get_current_method_name() {
		return ( isset( $_GET[ $this->type ] ) ) ? $_GET[ $this->type ] : 'default';
	}

	/**
	 * Retrieves current sorting type name
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	public function get_title() {
		return $this->title;
	}

	public function get_template_vars() {
		$methods = $this->methods;
		if ( isset( $methods['default'] ) )
			unset( $methods['default'] );

		return array_keys( $methods );
	}

	public function get_query_args() {
		$current = $this->get_current_method_name();
		$methods = $this->methods;
		if ( ! $current || ! isset( $methods[ $current ] ) || ! isset( $methods[ $current ]['args'] ) )
			return array();
		else
			return $methods[ $current ]['args'];
	}

	public function get_url_arg( $type, $method ) {
		return $this->get_current_method_name();
	}
}


/**
 * Sort Services by post meta
 */
class TR_Sorting_Services extends TR_Sorting_Type {

	protected $featured_key = '';

	function __construct() {
		parent::__construct( 'sort_by', __( 'Sort By', APP_TD ), true );
	}

	function posts_clauses( $clauses ) {
		global $wpdb;

		$orderby = get_query_var( 'orderby' );
		$meta_key = get_query_var( 'meta_key' );

		if( 'meta_value_num' === $orderby && $this->featured_key === $meta_key ){
			$clauses['orderby'] .= ", {$wpdb->posts}.post_date DESC";
		}

		return $clauses;
	}

	protected function sorting_methods() {
		$defaults = array();

		if( tr_is_front_page() ) {
			$this->featured_key = '_' . TR_ITEM_FEATURED_HOME;
		} elseif ( is_tax( TR_SERVICE_CATEGORY ) ) {
			$this->featured_key = '_' . TR_ITEM_FEATURED_CAT;
		}

		if( $this->featured_key ) {
			add_filter( 'posts_clauses', array( $this, 'posts_clauses' ) );
			$defaults = array(
				'meta_key'	 => $this->featured_key,
				'orderby'	 => 'meta_value_num',
				'order'		 => 'DESC',
			);
		}


		return array(
			'date' => array(
				'args' => array(
					'orderby' => 'date'
				),
				'content' => __( 'Date', APP_TD ),
				'title' => __( 'Order by Date', APP_TD ),
			),
			'rating' => array(
				'args' => array(
					'orderby' => 'meta_value_num',
					'meta_key' => '_review_rel',
				),
				'content' => __( 'Rating', APP_TD ),
				'title' => __( 'Order by Rating', APP_TD ),
			),
			'price' => array(
				'args' => array(
					'orderby' => 'meta_value_num',
					'meta_key' => 'price',
				),
				'content' => __( 'Price', APP_TD ),
				'title' => __( 'Order by Price', APP_TD ),
			),
			'default' => array(
				'args' => $defaults,
			),
		);

	}

}

/**
 * Specifies the sort order of Services
 */
class TR_Sorting_Order extends TR_Sorting_Type {

	function __construct() {
		parent::__construct( 'sort' );
	}

	protected function sorting_methods() {
		return array(
			'desc' => array(
				'args' => array(
					'order' => 'DESC'
				),
			),
			'asc' => array(
				'args' => array(
					'order' => 'ASC',
				),
			),
		);

	}

	public function get_template_vars() {
		return array();
	}

	public function get_current_method_name() {
		if ( isset( $_GET[ $this->type ] ) )
			return $_GET[ $this->type ];
	}

	public function get_url_arg( $type, $method ) {

		if ( 'sort_by' === $type ) {
			if ( 'desc' === $this->get_current_method_name() && $method === TR_Sorting::get_current_method_name( $type ) ) {
				$order = 'asc';
				$arrow = '&nbsp;&uarr;';
			} else {
				$order = 'desc';
				$arrow = '&nbsp;&darr;';
			}
			$params = TR_Sorting::get_sorting_method( $type, $method );
			$params['content'] .= html( 'span', array( 'class' => 'sorting-order-' . strtolower( $order ) ), $arrow );
			TR_Sorting::add_sorting_method( $type, $method, $params['args'], $params['content'], $params['title'] );
			return $order;
		}

		return $this->get_current_method_name();
	}

}

/**
 * Filter User's Services by custom post type meta
 */
class TR_Filter_Services extends TR_Sorting_Type {

	function __construct() {
		parent::__construct( 'filter_by', __( 'Filter By', APP_TD ) );
	}

	protected function sorting_methods() {
		return array(
			'' => array(
				'args' => array(),
				'content' => __( 'No filter', APP_TD ),
				'title' => __( 'Display all services', APP_TD ),
			),
			'reviewed' => array(
				'args' => array(
					'meta_query' => array(
						array(
							'key' => '_review_total',
							'value' => 0,
							'type' => 'NUMERIC',
							'compare' => '>',
						),
					),
				),
				'content' => __( 'Reviewed', APP_TD ),
				'title' => __( 'Display only reviewed services', APP_TD ),
			),
			'unreviewed' => array(
				'args' => array(
					'meta_query' => array(
						array(
							'key' => '_review_total',
							'value' => 'trick',
							'compare' => 'NOT EXISTS',
						),
					),
				),
				'content' => __( 'Unreviewed', APP_TD ),
				'title' => __( 'Display only unreviewed services', APP_TD ),
			),
			'live' => array(
				'args' => array(
					'post_status' => 'publish',
				),
				'content' => __( 'Live', APP_TD ),
				'title' => __( 'Display only live services', APP_TD ),
			),
			'pending' => array(
				'args' => array(
					'post_status' => 'pending',
				),
				'content' => __( 'Awaiting Moderation', APP_TD ),
				'title' => __( 'Display awaiting moderation services', APP_TD ),
			),
			'pending_payment' => array(
				'args' => array(
					'post_status' => 'draft',
				),
				'content' => __( 'Pending Payment', APP_TD ),
				'title' => __( 'Display panding payments services', APP_TD ),
			),
			'expired' => array(
				'args' => array(
					'post_status' => TR_SERVICE_STATUS_EXPIRED,
				),
				'content' => __( 'Expired', APP_TD ),
				'title' => __( 'Display only expired services', APP_TD ),
			),
		);
	}
}

/**
 * Sort Tasks by connection meta
 */
class TR_Sort_Tasks extends TR_Sorting_Type {

	function __construct() {
		parent::__construct( 'sort_by', __( 'Sort By', APP_TD ) );
	}

	protected function sorting_methods() {
		$order = ( isset( $_GET['sort'] ) ) ? $_GET['sort'] : 'desc';
		return array(
			'date' => array(
				'args' => array(
					'connected_orderby' => 'created_date',
					'connected_order' => $order,
				),
				'content' => __( 'Date', APP_TD ),
				'title' => __( 'Order by Date', APP_TD ),
			),
			'buyer' => array(
				'args' => array(
					'connected_orderby' => 'buyer',
					'connected_order' => $order,
				),
				'content' => __( 'Buyer', APP_TD ),
				'title' => __( 'Order by Buyer Name', APP_TD ),
			),
			'default' => array(
				'args' => array(
					'connected_orderby' => 'created_date',
					'connected_order' => $order,
				),
			),
		);
	}
}

/**
 * Sort Purchases by connection meta
 */
class TR_Sort_Service_Purchases extends TR_Sorting_Type {

	function __construct() {
		parent::__construct( 'sort_by', __( 'Sort By', APP_TD ) );
	}

	protected function sorting_methods() {
		$order = ( isset( $_GET['sort'] ) ) ? $_GET['sort'] : 'desc';
		return array(
			'date' => array(
				'args' => array(
					'connected_orderby' => 'created_date',
					'connected_order' => $order,
				),
				'content' => __( 'Date', APP_TD ),
				'title' => __( 'Order by Date', APP_TD ),
			),
			'provider' => array(
				'args' => array(
					'connected_orderby' => 'provider',
					'connected_order' => $order,
				),
				'content' => __( 'Provider', APP_TD ),
				'title' => __( 'Order by Service Provider', APP_TD ),
			),
			'default' => array(
				'args' => array(
					'connected_orderby' => 'created_date',
					'connected_order' => $order,
				),
			),
		);
	}
}

/**
 * Filter Tasks by connection meta
 */
class TR_Filter_Tasks extends TR_Sorting_Type {

	function __construct() {
		parent::__construct( 'filter_by', __( 'Filter By', APP_TD ) );
	}

	protected function sorting_methods() {
		return array(
			'' => array(
				'args' => array(),
				'content' => __( 'No filter', APP_TD ),
				'title' => __( 'Display all tasks', APP_TD ),
			),
			'confirmed' => array(
				'args' => array(
					'connected_meta' => array(
						'status' => array( TR_TASK_CONFIRMED )
					)
				),
				'content' => __( 'Confirmed', APP_TD ),
				'title' => __( 'Display only confirmed tasks', APP_TD ),
			),
			'completed' => array(
				'args' => array(
					'connected_meta' => array(
						'status' => array( TR_TASK_COMPLETED )
					)
				),
				'content' => __( 'Completed', APP_TD ),
				'title' => __( 'Display only completed tasks', APP_TD ),
			),
			'incomplete' => array(
				'args' => array(
					'connected_meta' => array(
						array(
							'key' => 'status',
							'value' => TR_TASK_COMPLETED,
							'compare' => '!='
						),
						array(
							'key' => 'status',
							'value' => TR_TASK_CONFIRMED,
							'compare' => '!='
						),
					),
				),
				'content' => __( 'Incomplete', APP_TD ),
				'title' => __( 'Display only incompleted tasks', APP_TD ),
			),
			'paid' => array(
				'args' => array(
					'connected_meta' => array(
						'status' => array( TR_TASK_COMPLETED, TR_TASK_CONFIRMED, TR_TASK_PAID ),
					)
				),
				'content' => __( 'Paid', APP_TD ),
				'title' => __( 'Display only paid tasks', APP_TD ),
			),
			'unpaid' => array(
				'args' => array(
					'connected_meta' => array(
						array(
							'key' => 'status',
							'value' => TR_TASK_COMPLETED,
							'compare' => '!='
						),
						array(
							'key' => 'status',
							'value' => TR_TASK_CONFIRMED,
							'compare' => '!='
						),
						array(
							'key' => 'status',
							'value' => TR_TASK_PAID,
							'compare' => '!='
						),
					),
				),
				'content' => __( 'Unpaid', APP_TD ),
				'title' => __( 'Display only unpaid tasks', APP_TD ),
			),
		);
	}

}

/**
 * Sort Reviews by comment type meta
 */
class TR_Sort_Reviews extends TR_Sorting_Type {

	function __construct() {
		parent::__construct( 'sort_by', __( 'Sort By', APP_TD ) );
	}

	protected function sorting_methods() {
		return array(
			'date' => array(
				'args' => array(
					'orderby' => 'date'
				),
				'content' => __( 'Date', APP_TD ),
				'title' => __( 'Order by Date', APP_TD ),
			),
			'reviewer' => array(
				'args' => array(
					'orderby' => 'comment_author'
				),
				'content' => __( 'Reviewer', APP_TD ),
				'title' => __( 'Order by Reviewer Name', APP_TD ),
			),
			'rating' => array(
				'args' => array(
					'orderby' => 'meta_value_num',
					'meta_key' => '_review_rating',
				),
				'content' => __( 'Rating', APP_TD ),
				'title' => __( 'Order by Rating', APP_TD ),
			),
			'service' => array(
				'args' => array(
					'orderby' => 'post_name'
				),
				'content' => __( 'Service', APP_TD ),
				'title' => __( 'Order by Service', APP_TD ),
			),
		);
	}
}

/**
 * Filter Reviews by comment type meta
 */
class TR_Filter_Reviews extends TR_Sorting_Type {

	function __construct() {
		parent::__construct( 'rated', __( 'Rated', APP_TD ) );
	}

	protected function sorting_methods() {

		$max_rating = appthemes_reviews_get_args( 'max_rating' ) + 1;

		$sorting_methods[] = array(
			'args' => array(),
			'content' => __( 'No filter', APP_TD ),
			'title' => __( 'Display all reviews', APP_TD ),
		);

		for ( $rating = 1; $rating < $max_rating; $rating++ ) {
			$sorting_methods[] = array(
				'args' => array(
					'meta_key' => '_review_rating',
					'meta_value' => $rating,
				),
				'content' => sprintf( _n( '1 Star', '%s Stars', $rating, APP_TD ), $rating ),
				'title' => sprintf( _n( 'Display only reviews with 1 Star', 'Display only reviews with %s Stars', $rating, APP_TD ), $rating ),
			);
		}

		$sorting_methods['no-stars'] = array(
			'args' => array(
				'meta_query' => array(
					array(
						'key'		 => '_review_rating',
						'value'		 => 'trick',
						'compare'	 => 'NOT EXISTS'
					)
				),
			),
			'content' => __( 'No Stars', APP_TD ),
			'title' => __( 'Display only reviews without Stars', APP_TD ),
		);

		return $sorting_methods;
	}
}

/**
 * Filter Notifications by type
 */
class TR_Filter_Notifications extends TR_Sorting_Type {

	function __construct() {
		parent::__construct( 'filter_by', __( 'Type', APP_TD ) );
	}

	protected function sorting_methods() {

		$types = array();
		$sorting_methods = array();
		$current_user = wp_get_current_user();
		$notifications = appthemes_get_notifications( $current_user->ID );

		if ( $notifications ) {
			foreach( $notifications->results as $notification ) {
				$type = $notification->type;
				$types[ $type ] = $this->get_notifications_verbiage( $type );
			}
			$sorting_methods[''] = array(
				'args' => array(),
				'content' => __( 'All', APP_TD ),
				'title' => __( 'Display all notifications', APP_TD ),
			);
		}

		foreach ( $types as $type => $content ) {
			$sorting_methods[ $type ] = array(
				'args' => array(
					'type' => $type,
				),
				'content' => $content,
				'title' => sprintf( __( 'Display only notifications with type "%s"', APP_TD ), $content ),
			);
		}

		return $sorting_methods;
	}

	/**
	 * Retrieves notifications verbiages
	 */
	protected function get_notifications_verbiage( $type = '' ) {

		$verbiage = array(
			'notification' => __( 'Notification', APP_TD ),
			'status' => __( 'Status', APP_TD ),
			'action' => __( 'Action Required', APP_TD ),
			'review' => __( 'Reviews', APP_TD )
		);

		if ( ! isset( $verbiage[ $type ] ) ) {
			return $type;
		}

		return $verbiage[$type];
	}

}

class TR_Sort_Notifications extends TR_Sorting_Type {

	function __construct() {
		parent::__construct( '_sort_by', __( 'Sort By', APP_TD ) );
	}

	protected function sorting_methods() {
		return array(
			'newest' => array(
				'args' => array(
					'order' => 'DESC'
				),
				'content' => __( 'Newest', APP_TD ),
				'title' => __( 'Display newest notifications first', APP_TD ),
			),
			'oldest' => array(
				'args' => array(
					'order' => 'ASC',
				),
				'content' => __( 'Oldest', APP_TD ),
				'title' => __( 'Display oldest notifications first', APP_TD ),
			),
		);
	}

}