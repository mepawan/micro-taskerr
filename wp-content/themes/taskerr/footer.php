<?php
/**
 * Generic Footer template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
		<!-- Footer Widgets -->
		<?php if ( is_active_sidebar( 'tr-footer' ) ) { ?>
		<div class="row widgets-footer">
			<div class="large-12 columns">
				<ul class="small-block-grid-2 medium-block-grid-4 large-block-grid-4">
					<?php dynamic_sidebar( 'tr-footer' ); ?>
				</ul>
			</div>
		</div>
		<!-- End footer Widgets -->
		<?php } ?>

		  <!-- Footer -->
		<div class="row">
			<div class="large-12 columns">
				<p class="copy"> &copy; <?php echo date('Y');?> | Micro Taskerr</p>
			</div>
		</div>
