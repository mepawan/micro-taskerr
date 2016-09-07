<?php
/**
 * Generic template for Sorting Panel
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>

	<?php foreach( tr_get_sorting_panels() as $sort_panel_id => $sort_panel ) { ?>

		<a href="#" data-dropdown="<?php echo esc_attr( $sort_panel_id ); ?>" class="button expand dropdown">
			<?php tr_sorting_title( $sort_panel_id );?>
		</a><br />
		<ul id="<?php echo esc_attr( $sort_panel_id ); ?>" class="f-dropdown">

			<?php foreach( $sort_panel as $sort_method ) { ?>
				<li><?php tr_sorting_link( $sort_panel_id, $sort_method ); ?></li>
			<?php } ?>

		</ul>

	<?php }