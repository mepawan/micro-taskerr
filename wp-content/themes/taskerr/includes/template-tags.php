<?php
/**
 * Functions used in the Templates and Loops
 *
 * @package Taskerr\TemplateTags
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

add_action( 'appthemes_framework_loaded', 'tr_remove_default_notices' );
add_filter( 'appthemes_display_notice', 'tr_display_notice', 10, 2 );

/**
 * Displays the link to the Author's page
 *
 * @param type $user_id
 * @param type $args
 */
function tr_author_link( $user_id = '', $args = array() ){
	echo tr_get_author_link( $user_id, $args );
}

function tr_get_author_link( $user_id = '', $args = array() ){

	if( is_array( $user_id ) ){
		$args    = $user_id;
		$user_id = '';
	}

	extract( wp_parse_args( $args, array(
		'container_class' => 'author-posts-qty',
		'show_count'      => true,
		'separator_class' => 'separator',
		'separator_start' => '(',
		'separator_end'   => ')',
	)) );

	if( ! $user_id )
		$user_id = get_post()->post_author;

	$user = get_userdata( $user_id );

	$count_string = '';
	if( $show_count ){
		$count = count_user_posts( $user_id );
			if ( $count ) {
			$separator = '<em class="%s">%s</em>';
			$start     = sprintf( $separator, $separator_class, $separator_start );
			$end       = sprintf( $separator, $separator_class, $separator_end );

			$count_string = sprintf( '<span class="%s">%s%s%s</span>', $container_class, $start, $count, $end );
		}
	}

	$author_name = $user->display_name;
	$author_link = get_author_posts_url( $user->ID, $user->user_nicename );
	return sprintf( '<a href="%s">%s %s</a>', $author_link, $author_name, $count_string );
}

function tr_get_user_email ( $user_id = '' ) {

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$user = get_userdata( $user_id );

	return $user->user_email;
}

function tr_service_featured_class(){
	return 'featured';
}

function tr_post_class( $class = '', $post_id = null ) {
	$post    = get_post( $post_id );
	$classes = (array) $class;

	if ( tr_is_sticky( $post->ID, $post ) ) {
		$classes[] = 'sticky';
	}

	post_class($classes);
}

function tr_is_sticky( $post_id = 0, $post = null ) {
	$post_id = absint( $post_id );

	if ( ! $post_id )
		$post_id = get_the_ID();

	if ( ! $post )
		$post = get_post( $post_id );

	if ( $post && TR_SERVICE_PTYPE === $post->post_type ) {
		if ( tr_is_front_page() ) {
			return get_post_meta( $post->ID, '_' . TR_ITEM_FEATURED_HOME, true );
		} elseif ( is_tax( TR_SERVICE_CATEGORY ) ) {
			return get_post_meta( $post->ID, '_' . TR_ITEM_FEATURED_CAT, true );
		} elseif ( is_singular( TR_SERVICE_PTYPE ) ) {
			return get_post_meta( $post->ID, '_' . TR_ITEM_FEATURED_CAT, true ) || get_post_meta( $post->ID, '_' . TR_ITEM_FEATURED_HOME, true );
		}
	}

	return false;
}

function tr_service_views( $post_id = '' ){
	echo tr_get_service_views( $post_id );
}

function tr_get_service_views( $post_id = '' ){

	$post = get_post( $post_id );
	if( ! $post )
		return;

	return get_post_meta( $post->ID, 'view_count', true );
}

/**
 * Displays the total number of tasks completed by the provider
 *
 * author.php
 * sidebar-dashboard.php
 */

function tr_provider_tasks_completed( $user_id = '' ) {
	global $current_user;
	if ( ! $user_id )
		$user_id = $current_user->ID;

	$tasks = get_posts( array(
			'connected_type' => TR_TASKS,
			'author'         => $user_id,
			'post_type'      => TR_SERVICE_PTYPE,
			'connected_meta' => array(
				'status'     => array( TR_TASK_CONFIRMED, TR_TASK_COMPLETED ),
			),
	) );

	echo count( $tasks );
}

/**
 * Displays the number of tasks completed by the provider for a given task/service
 *
 * single-service.php
 * content-dashboard-service.php
 */
function tr_tasks_completed() {
	$tasks = get_posts( array(
		'connected_type' => TR_TASKS,
		'author'         => get_the_author_meta( 'ID' ),
		'post_status'    => array( 'publish' ),
		'post_type'      => TR_SERVICE_PTYPE,
		'p'              => get_the_ID(),
		'connected_meta' => array(
			'status' => array( TR_TASK_CONFIRMED )
		)
	) );

	echo count( $tasks );
}

/**
 * Displays the number of pending tasks for a given task/service for a provider
 *
 * content-dashboard-service.php
 */
function tr_tasks_pending() {
	$tasks = get_posts( array(
		'connected_type' => TR_TASKS,
		'author'         => get_the_author_meta( 'ID' ),
		'post_status'    => array( 'publish' ),
		'post_type'      => TR_SERVICE_PTYPE,
		'p'              => get_the_ID(),
		'connected_meta' => array(
			'status' => array( TR_TASK_PENDING )
		)
	) );

	echo count( $tasks );
}

/**
 * Retrieves the review count received for a user
 *
 * single-service.php
 * sidebar-dashboard.php
 *
 * @global object $current_user
 * @param int $user_id the ID of particular user as parameter, otherwise will used current user ID
 */
function tr_get_user_reviews_count( $user_id = '' ) {
	global $current_user;
	if ( ! $user_id )
		$user_id = $current_user->ID;

	$reviews = appthemes_get_reviews( array( 'post_author' => $user_id ) );
	echo count( $reviews );
}

/**
 * Retrieves the rating average received for a provider
 *
 * sidebar-dashboard.php
 */

function tr_get_rating_average( $user_id = '' ) {
	global $current_user;
	if ( ! $user_id )
		$user_id = $current_user->ID;

	$reviews = appthemes_get_reviews( array( 'post_author' => $user_id ) );
	$options = appthemes_reviews_get_args();

	$total_ratings = 0;
	$rating        = '0';
	foreach ( $reviews as $review ) {
		$total_ratings += (float) $review->get_rating();
	}

	if ( count($reviews) >= 1 )
		$rating = round( 100 * $total_ratings / ( count($reviews) * $options['max_rating'] ) ) . '%';

	return $rating;
}

/**
 * Retreives the number of current tasks that a provider has to fulfill
 *
 * sidebar-dashboard.php
 */

function tr_get_provider_tasks_current_count( $user_id = '' ) {
	global $current_user;
	if ( ! $user_id )
		$user_id = $current_user->ID;

	$tasks = get_posts( array(
			'connected_type' => TR_TASKS,
			'author'         => $user_id,
			'post_type'      => TR_SERVICE_PTYPE,
			'connected_meta' => array(
				'status'     => array( TR_TASK_PAID, TR_TASK_COMPLETED ),
			),
		) );

	echo count( $tasks );
}

/**
 * Retreives the total amount of money earned by a provider
 *
 * sidebar-dashboard.php
 */

function tr_get_provider_earnings() {
	global $current_user;
	$user_id = $current_user->ID;
	$tasks = get_posts( array(
			'connected_type' => TR_TASKS,
			'author'         => $user_id,
			'post_type'      => TR_SERVICE_PTYPE,
			'connected_meta' => array(
				'status'     => array( TR_TASK_PAID, TR_TASK_CONFIRMED, TR_TASK_COMPLETED ),
			),
	) );

	$i = 0;
	$completed_statuses = array( TR_TASK_PAID, TR_TASK_CONFIRMED, TR_TASK_COMPLETED );
	foreach ( $tasks as $task ) {
		$task = get_the_task( $task->p2p_id );

		if ( in_array( $task->get_status(), $completed_statuses ) )
			$i += $task->get_price();
	}

	appthemes_display_price( $i );
}

/**
 * Retrieves the number of services purchased by a user
 *
 * sidebar-dashboard.php
 */

function tr_get_user_services_purchased_count( $user_id = '' ) {
	global $current_user;
	if ( ! $user_id )
		$user = $current_user;
	else
		$user = get_user_by ( 'id', $user_id );

	$purchased = get_posts( array(
			'connected_type' => TR_TASKS,
			'connected_items' => $user,
			'post_type' => TR_SERVICE_PTYPE,
		) );

	echo count( $purchased );
}

/**
 * Retreives the number of reviews made by a user
 *
 * sidebar-dashboard.php
 *
 * @global object $current_user
 * @param int $user_id the ID of particular user as parameter, otherwise will used current user ID
 */

function tr_get_user_reviews_made_count( $user_id = '' ) {
	global $current_user;
	if ( ! $user_id )
		$user_id = $current_user->ID;

	echo appthemes_get_user_authored_total_reviews( $user_id );
}

/**
 * Returns the URL to the user's dashboard
 *
 * sidebar-dashboard.php
 */

function tr_dashboard_url() {
	echo tr_get_dashboard_url();
}

function tr_dashboard_pages() {

	$parent	 = TR_Dashboard_Home::get_id();
	$pages	 = get_pages( array( 'child_of' => $parent ) );

	if ( $pages ) {
		$pageids = array();
		foreach ( $pages as $page ) {
			$pageids[] = $page->ID;
		}

		$args = array(
			'child_of'     => TR_Dashboard_Home::get_id(),
			'include'      => $parent . ',' . implode( ",", $pageids ),
			'title_li'     => '',
		);
		wp_list_pages( $args );
	}
}

/**
 * Displays identfication number for a given task
 *
 * content-dashboard-purchased.php
 */

function tr_the_task_id( $task = '' ) {
	if ( ! $task || ! is_object( $task ) || ! is_a( $task, 'TR_Task' ) ) {
		$task = get_the_task();
	}
	echo $task->get_id();
}

/**
 * Displays the date the service was purchased
 *
 * content-dashboard-purchased.php
 */

function tr_service_purchased_date( $task = '' ) {
	if ( ! $task || ! is_object( $task ) || ! is_a( $task, 'TR_Task' ) ) {
		$task = get_the_task();
	}
	echo date( get_option('date_format'), strtotime( $task->get_created_date() ) );
}

/**
 * Displays the provider's screen name
 *
 * content-dashboard-purchased.php
 */

function tr_the_provider_name( $task = '' ) {
	if ( ! $task || ! is_object( $task ) || ! is_a( $task, 'TR_Task' ) ) {
		$task = get_the_task();
	}

	$post        = get_post( $task->get_service() );
	$provider_id = $post->post_author;
	return get_the_author_meta( 'display_name', $provider_id );
}

/**
 * Returns the URL to where a user can send a message to the provider
 *
 * content-dashboard-purchased.php
 */

function tr_send_message_provider_link() {
	return;
}

/**
 * Returns the URL to where a provider can send a message to the buyer
 *
 * content-dashboard-tasks.php
 */

function tr_send_message_buyer_link() {
	return;
}

/**
 * Returns the URL to the where a user can rate a service
 *
 * content-dashboard-purchased.php
 */

function tr_rate_this_service_link() {
	return;
}

/**
 * Returns the URL to the where a user can review a service
 *
 * content-dashboard-purchased.php
 */

function tr_review_this_service_link() {
	return;
}

/**
 * Sends a request for a rating from the provider to the buyer
 *
 * content-dashboard-tasks.php
 */

function tr_request_rating_link() {
	return;
}

/**
 * Sends a request for a review from the provider to the buyer
 *
 * content-dashboard-tasks.php
 */

function tr_request_review_link() {
	return;
}

/**
 * Returns the buyer's name
 *
 * content-dashboard-tasks.php
 */

function tr_the_buyer_name( $task = '' ) {
	if ( ! $task || ! is_object( $task ) || ! is_a( $task, 'TR_Task' ) ) {
		$task = get_the_task();
	}
	$buyer_id = $task->get_user();
	return get_the_author_meta( 'display_name', $buyer_id );
}

function tr_the_buyer_id( $task = '' ) {
	if ( ! $task || ! is_object( $task ) || ! is_a( $task, 'TR_Task' ) ) {
		$task = get_the_task();
	}
	return $task->get_user();
}

function tr_the_buyer_message( $task = '' ) {
	if ( ! $task || ! is_object( $task ) || ! is_a( $task, 'TR_Task' ) ) {
		$task = get_the_task();
	}
	echo apply_filters( 'the_content', $task->get_instructions() );
}

function tr_the_buyer_contacts( $task = '' ) {
	if ( ! $task || ! is_object( $task ) || ! is_a( $task, 'TR_Task' ) ) {
		$task = get_the_task();
	}
	echo apply_filters( 'the_content', $task->get_contacts() );
}

function tr_service_categories( $post_id = '' ) {
	echo  tr_get_terms_list( $post_id, TR_SERVICE_CATEGORY );
}

function tr_service_tags( $post_id = '' ) {

	echo tr_get_terms_list( $post_id, TR_SERVICE_TAG, array(
		'before_term' => '<span class="tag">',
		'after_term'  => '</span>'
	) );
}

function tr_get_terms_list( $post_id, $taxonomy, $args = array() ){

	$args = wp_parse_args( $args, array(
		'before'      => '',
		'after'       => '',
		'before_term' => '',
		'after_term'  => '',
	) );
	extract( $args );

	$term_objects = get_the_terms( $post_id, $taxonomy );
	if( ! $term_objects )
		return;

	$terms = array();
	foreach( get_the_terms( $post_id, $taxonomy ) as $term ){
		$terms[] = html_link( get_term_link( $term ), $before_term . $term->name . $after_term );
	}

	return $before . join( '<em class="separator">, </em>', $terms ) . $after;
}

function tr_delivery_time( $post_id = '' ) {
	$delivery_time = tr_get_delivery_time( $post_id );
	echo $delivery_time . ' <span>' . _n( 'day', 'days', $delivery_time, APP_TD ) . '</span>';
}

function tr_get_delivery_time( $post_id = '' ) {
	$post = get_post( $post_id );
	if( ! $post )
		return;

	return get_post_meta( $post->ID, 'delivery_time', true );
}

function tr_service_price( $post_id = '' ){
	$price = tr_get_service_price( $post_id );
	if( ! $price )
		return;

	$format_args = appthemes_price_format_get_args();
	$decimals    = ( $format_args['hide_decimals'] ) ? 0 : 2;
	$base_price  = number_format( $price, $decimals, $format_args['decimal_separator'], $format_args['thousands_separator'] );

	if ( $decimals ) {
		$base_price  = str_replace( $format_args['decimal_separator'] . "00", "", (string) $base_price );
	}

	$position = $format_args['currency_position'];
	$identifier = html( 'span', APP_Currencies::get_currency( $format_args['currency_default'], $format_args['currency_identifier'] ) );

	echo _appthemes_format_display_price( $base_price, $identifier, $position );
}

function tr_get_service_price( $post_id = '' ){
	$post = get_post( $post_id );
	if( ! $post )
		return;

	return get_post_meta( $post->ID, 'price', true );
}

function tr_review_stars( $rating ) {

	$rating = (int) $rating ;
	if( $rating < 0 )
		return;

	$bad_rating = 5 - $rating;

	$star = '<i class="genericon genericon-star good-star"></i>';
	$bad_star = '<i class="genericon genericon-star bad-star"></i>';

	$stars = array();
	if ( $rating ) {
		$stars = array_fill( 0, $rating, $star );
	}

	if ( $bad_rating ) {
		$stars = array_merge( $stars, array_fill( $rating, $bad_rating, $bad_star ) );
	}

	$string = join( '', $stars );
?>
	<span class="stars stars-<?php echo $rating; ?>">
		<?php echo $string; ?>
	</span>
	<?php
}

function tr_service_reviews_stars(){
	tr_review_stars( appthemes_get_post_avg_rating( get_the_ID() ) );
}

function tr_service_reviews_count(){
	echo appthemes_get_post_total_reviews( get_the_ID() );
}

function tr_service_reviews_action_button(){

	if( is_user_logged_in() && current_user_can( 'add_review', get_the_ID() ) && tr_has_paid_task( get_current_user_id(), get_the_ID() ) ){ ?>
		<a href="<?php echo esc_url( tr_service_add_review_url() ); ?>" class="button success">
		<i class="genericon genericon-star"></i>
		<span><?php _e( 'Add a Review', APP_TD );?></span>
		</a>
	<?php }

}

function tr_task_action_button(){

	if( current_user_can( 'add_task', get_the_ID() ) ){ ?>
	<a href="<?php echo TR_Process_Service_Order::get_link(); ?>" class="button success">
		<span><?php _e( 'Buy it for', APP_TD );?>&nbsp;<?php tr_service_price(); ?></span>
	</a>
	<?php } else { ?>
		<span class="button success"><?php _e( 'Cost of service', APP_TD );?>:&nbsp;<?php tr_service_price(); ?></span>
	<?php }

}

function tr_service_buy_it_action_button(){
	if ( ! is_user_logged_in() || current_user_can( 'add_task', get_the_ID() ) ){ ?>
	<a href="<?php echo TR_Process_Service_Order::get_link(); ?>" class="button success">
		<span><?php _e( 'Buy', APP_TD );?></span>
	</a>
	<?php }

}

function tr_author_section_name( $section_args = array() ) {
	global $wp_query;

	if ( isset( $section_args['total_entries'] ) )
		$count = $section_args['total_entries'];
	else
		$count = $wp_query->found_posts;

	echo esc_html( $section_args['name']. '&nbsp;(' . $count . ')' );
}

function tr_get_attachments_slider() {

	$args = array(
		'id'                    => 'service-slider',
		'image_a_attr'          => array( 'rel' => 'colorbox' ),
		'width'                 => 870,
		'height'                => 489,
		'video_embed_class'     => 'flex-video',
		'attachment_image_size' => 'tr-slider',
	);

	appthemes_add_template_var( array( 'tr_slider' => new APP_Slider( $args ) ) );

	get_template_part( 'slider' );
}

function tr_services_archive_title() {
	$method = tr_get_current_sorting_method( 'sort_by' );
	$order = tr_get_current_sorting_method( 'sort' );
	$post_type_title = post_type_archive_title( '', false );

	switch ( $method ) {
		case 'price':
			$title = ( 'asc' === $order ) ? __( 'Cheapest %s', APP_TD ) : __( 'Most Expensive %s', APP_TD );
			break;
		case 'rating':
			$title = ( 'asc' === $order ) ? __( 'Lowest Rated %s', APP_TD ) : __( 'Highest Rated %s', APP_TD );
			break;
		default:
			$title = ( 'asc' === $order ) ? __( 'Oldest %s', APP_TD ) : __( 'Latest %s', APP_TD );
			break;
	}

	printf( $title, $post_type_title );
}

function tr_sorting_link( $type, $method ) {

	if ( ! tr_get_sorting_method( $type, $method ) )
		return;

	$curr_class = '';
	if ( $method === tr_get_current_sorting_method( $type ) )
		$curr_class = ' sorting-current';

	$link = tr_get_sorting_url( $type, $method );
	$params = tr_get_sorting_method( $type, $method );

	echo html( 'a', array(
		'href'  => $link,
		'title' => $params['title'],
		'id'    => 'sorting-' . $method,
		'rel'   => 'nofollow',
		'class' => 'sorting-link' . $curr_class,
	), $params['content'] );
}

function tr_service_edit_action_button(){

	if( current_user_can( 'edit_service', get_the_ID() ) ){ ?>
		<a href="<?php echo tr_get_service_edit_url(); ?>" class="button secondary">
			<i class="genericon genericon-edit"></i>
			<span><?php _e( 'Edit Service', APP_TD );?></span>
		</a>
	<?php }

}

function tr_post_edit_action_button(){

	if ( !$post = get_post() )
		return;

	if ( !$url = get_edit_post_link( $post->ID ) )
		return;

	$link = html( 'i', array( 'class' => 'genericon genericon-edit' ), '' );
	$link .= html( 'span', __( 'Edit This', APP_TD ) );

	$post_type_obj = get_post_type_object( $post->post_type );
	$link = '<a class="post-edit-link button secondary" href="' . $url . '">' . $link . '</a>';
	echo apply_filters( 'edit_post_link', $link, $post->ID );
}

/**
 * Displays the service purchase summary table
 */
function tr_the_service_purchase_summary( $task = '' ){
	if ( ! $task || ! is_object( $task ) || ! is_a( $task, 'TR_Task' ) ) {
		$task = get_the_task();
	}
	$table = new TR_Service_Purchase_Summary_Table( $task );
	$table->show();
}


function tr_is_front_page() {
	return ( get_query_var( 'page_id' ) == TR_Home::get_id() ) || get_query_var( 'tr_front_page' );
}


function tr_the_images_manager( $listing_id = 0 ) {
	global $tr_options;

	// media manager fieldset attributes
	$args = array(
		'id'			=> 'images-manager',
		'class'			=> 'media-manager images-manager',
		'title'			=> __( 'Images', APP_TD ),
		'upload_text'	=> __( 'Add Images', APP_TD ),
		'manage_text'	=> __( 'Manage Images', APP_TD ),
		'no_media_text'	=> __( 'No images added yet', APP_TD ),
	);

	// filters to restrict allowed image files
	$filters = array(
		'file_limit'	=> $tr_options->max_images,
		'embed_limit'	=> 0,
		'file_size'		=> wp_convert_hr_to_bytes( $tr_options->max_image_size ),
		'mime_types'	=> 'image',
		'meta_type'		=> TR_ATTACHMENT_GALLERY,
	);

	appthemes_media_manager( $listing_id, $args, $filters );
}

function tr_the_videos_manager( $listing_id = 0 ) {
	global $tr_options;

	// media manager fieldset attributes
	$args = array(
		'id'			=> 'videos-manager',
		'class'			=> 'media-manager videos-manager',
		'title'			=> __( 'Videos', APP_TD ),
		'upload_text'	=> __( 'Add Videos', APP_TD ),
		'manage_text'	=> __( 'Manage Videos', APP_TD ),
		'no_media_text'	=> __( 'No videos added yet', APP_TD ),
	);

	// filters to restrict allowed video files
	$filters = array(
		'file_limit'	=> $tr_options->max_videos,
		'embed_limit'	=> 0,
		'file_size'		=> wp_convert_hr_to_bytes( $tr_options->max_video_size ),
		'mime_types'	=> 'video',
		'meta_type'		=> TR_ATTACHMENT_FILE,
	);

	appthemes_media_manager($listing_id, $args, $filters );
}

function tr_the_embeds_manager( $listing_id = 0 ) {
	global $tr_options;

	// media manager fieldset attributes
	$args = array(
		'id'			=> 'embeds-manager',
		'class'			=> 'media-manager embeds-manager',
		'title'			=> __( 'Embeds', APP_TD ),
		'upload_text'	=> __( 'Embed Media', APP_TD ),
		'manage_text'	=> __( 'Manage Embeds', APP_TD ),
		'no_media_text'	=> __( 'No media embedded yet', APP_TD ),
	);

	// filters to restrict allowed embeds
	$filters = array(
		'file_limit'	=> 0,
		'embed_limit'	=> $tr_options->max_embeds,
	);

	appthemes_media_manager( $listing_id, $args, $filters );
}

function tr_service_thumbnail( $service = null ) {
	global $_wp_additional_image_sizes;

	if ( ! $service ) {
		$service = get_post();
	}

	$size = $_wp_additional_image_sizes['service-thumbnail'];
	?>
	<a class="service-thumbnail" href="<?php the_permalink( $service ); ?>" title="<?php the_title_attribute( $service ); ?>">
		<?php if ( has_post_thumbnail( $service->ID ) ) {
			echo get_the_post_thumbnail( $service->ID, 'service-thumbnail' );
		} else { ?>
			<?php echo get_avatar( $service->post_author, 180 ); ?>
		<?php } ?>
	</a>
	<?php
}

function tr_author_location( $author_id = null ) {
	if ( ! $author_id )
		$author_id = get_the_author_meta('ID');
	?>
	<span class="author-loc">USA</span>
	<?php
}

function tr_author_rating( $author_id = null ) {
	if ( ! $author_id )
		$author_id = get_the_author_meta('ID');

	echo tr_get_rating_average( $author_id );
}

function tr_remove_default_notices() {
	remove_filter( 'appthemes_display_notice', array( 'APP_Notices', 'outputter' ), 10 );
}

function tr_display_notice( $class, $msgs ) {
?>
	<div data-alert="" class="notice <?php echo esc_attr( $class ); ?> alert-box">
		<?php foreach ( $msgs as $msg ): ?>
			<div><?php echo $msg; ?></div>
		<?php endforeach; ?>
		<a href="#" class="close">&times;</a>
	</div>
<?php
}

function tr_get_unread_notifications() {
	static $unread_notifications = false;

	if ( $unread_notifications ) {
		return $unread_notifications;
	}

	$current_user = wp_get_current_user();
	$unread_notifications = appthemes_get_notifications( $current_user->ID, array(
		'status' => 'unread',
	) );

	return $unread_notifications;
}

function tr_get_notifications_count() {
	return tr_get_unread_notifications()->found;
}

function tr_notifications_count() {
	$count = tr_get_notifications_count();
	if ( 0 !== $count ) {
		echo '(' . html( 'span', array( 'class' => 'unread-inbox' ), $count ) . ')';
	}
}

function tr_get_top_bar_notifications() {
	$unread_notifications = tr_get_unread_notifications();
	$list = $unread_notifications->results;
	$limit = 10;

	if ( $unread_notifications->found > $limit ) {
		$list = array_slice( $list, 0, $limit, true );
	}
	return $list;
}


/**
 * Callback function for Walker_Comment class
 * Output a comment in the HTML5 format.
 *
 * @param object $comment Comment to display.
 * @param int    $depth   Depth of comment.
 * @param array  $args    An array of arguments. @see wp_list_comments()
 */
function tr_html5_comment( $comment, $args, $depth ) {
	appthemes_load_template( 'comment.php', array( 'comment' => $comment, 'depth' => $depth, 'args' => $args ) );
}


/**
 * Used on Single Service page to get correct grid class
 * @staticvar boolean $grid_class
 */
function tr_post_bar_grid_class() {
	static $grid_class = false;

	if ( $grid_class ) {
		echo $grid_class;
		return;
	}

	$grid = 1;
	if ( has_term( '', TR_SERVICE_TAG ) || has_tag() ) {
		$grid++;
	}
	if ( function_exists( 'sharethis_button' ) ) {
		$grid++;
	}
	if ( $grid > 1 ) {
		$grid = 12/$grid;
		$grid_class = " medium-{$grid} large-{$grid}";
	}
	echo $grid_class;
	return;
}


/**
 * Multiple Query Tags
 *
 * A set of functions to work with multiple queries within one page.
 */

/**
 * Checks whether there are a sections for iteration and returns sections array on true
 */
function tr_sections() {
	global $wp_query;
	static $tr_sections = array();

	if ( empty( $tr_sections ) && isset( $wp_query->query_vars['tr_sections'] ) ) {
		$tr_sections = $wp_query->query_vars['tr_sections'];

		foreach ( $tr_sections as $name => $section ) {
			if ( isset( $section['args'] ) ) {
				$query = new WP_Query( $section['args'] );
				if ( ! $query->have_posts() ) {
					unset( $tr_sections[ $name ] );
				} else {
					$tr_sections[ $name ]['query'] = $query;
				}
			} elseif ( isset( $section['comments_query'] ) && empty( $section['comments_query'] ) ) {
				unset( $tr_sections[ $name ] );
			}
		}
	}

	return $tr_sections;
}

/**
 * Set up current section. Executes the query and caches the results.
 */
function tr_the_section( $section = array() ) {
	global $wp_query;

	if ( isset( $section['max_num_pages'] ) ) {
		$wp_query->max_num_pages = $section['max_num_pages'];
	}

	if ( isset( $section['comments_query'] ) ) {
		$wp_query->set( 'comments_query', $section['comments_query'] );
	}

	if ( isset( $section['query'] ) ) {
		$wp_query = $section['query'];
	}

}

/**
 * Destroy the previous query and set up an original query.
 */
function tr_reset_section_query( $section = array() ) {
	if ( isset( $section['query'] ) ) {
		wp_reset_query();
	}
}

/**
 * Checks whether given section is first in iteration
 */
function tr_is_first_section( $section = '' ) {
	$sections = tr_sections();
    reset( $sections );
    return $section === key( $sections );
}

function tr_recent_services_widget() {
	$instance = array(
		'title'			 => sprintf( __( 'More Services from %s', APP_TD ), get_the_author() ),
		'post_type'		 => TR_SERVICE_PTYPE,
		'author_id'      => get_the_author_meta('ID'),
		'show_thumbnail' => true,
	);
	$args = array(
		'before_title'  => '<header><h1 class="widgettitle">',
		'after_title'   => "</h1>\n</header>\n",
		'before_widget' => '',
		'after_widget'  => '',
	);
	$widget = new TR_Widget_Author_Recent_Posts();
	$widget->widget( $args, $instance );
}

/**
 * Renders WP editor.
 *
 * @see wp-includes/class-wp-editor.php
 * @since Taskerr 1.1
 *
 * @param string $content   Initial content for the editor.
 * @param string $editor_id HTML ID attribute value for the textarea and TinyMCE. Can only be /[a-z]+/.
 * @param array  $settings  See _WP_Editors::editor().
 */
function tr_editor( $content = '', $editor_id = '', $settings = array() ) {
	$defaults = array(
		'media_buttons' => false,
		'textarea_name' => $editor_id,
		'textarea_rows' => 10,
		'editor_class'  => 'required',
		'tinymce'       => false,
		'quicktags'     => array(
			'buttons' => 'strong,em,link,block,del,ins,ul,li'
		)
	);

	$settings = apply_filters( 'tr_editor_settings', wp_parse_args( $settings, $defaults ), $editor_id );

	wp_editor( $content, $editor_id, $settings );
}