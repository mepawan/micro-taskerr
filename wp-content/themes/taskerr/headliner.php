<?php
/**
 * Headliner
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
	<div class="row">
		<div class="large-12 columns">
			<?php if ( is_single() && isset( $post ) ) { ?>
				<span><?php printf( __( '%s Details', APP_TD ), get_post_type_object( $post->post_type )->labels->singular_name ); ?></span>
			<?php } elseif ( $step = appthemes_get_checkout() ) { ?>
				<h1><?php echo $step->get_data( 'title' ); ?></h1>
			<?php } elseif ( is_page() ) { ?>
				<h1><?php the_title(); ?></h1>
			<?php } ?>
		</div>
	</div>