<?php
/**
 * Order Summary form template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
			<div class="order-summary">
				<?php the_order_summary(); ?>
				<form class="edit-service" action="<?php echo appthemes_get_step_url(); ?>" method="POST">
					<p><?php _e( 'Please select a method for processing your payment:', APP_TD ); ?></p>
					<fieldset>
						<?php appthemes_list_gateway_dropdown(); ?>
						<button class="button large success" type="submit"><?php _e( 'Submit', APP_TD ); ?></button>
					</fieldset>
				</form>
			</div>