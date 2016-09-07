<?php
/**
 * Template part for displaying Order Summary content.
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>

<?php the_order_summary(); ?>

<p><?php esc_html_e( 'Your order has been completed.', APP_TD ); ?></p>

<?php
	$item_id = 0;
	if ( appthemes_get_checkout() ) {
		$item_id = appthemes_get_checkout()->get_data( 'listing_id' );
	} else {
		foreach ( get_order()->get_items() as $order_item ) {
			if ( isset( $order_item['post_id'] ) ) {
				$item_id = $order_item['post_id'];
				break;
			}
		}
	}

	if ( $item_id ) {
		$main_item = get_post( $item_id );
		$post_type_obj = get_post_type_object( $main_item->post_type );
		$name = $post_type_obj->labels->singular_name;
		$url = get_permalink( $main_item->ID );
	?>
		<a href="<?php echo esc_url( $url ); ?>">
			<button class="button large success"><?php printf( __('Continue to %s', APP_TD ), $name ); ?></button>
		</a>
	<?php
	}
