<?php
/**
 * New Task form
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
			<form id="add-task" enctype="multipart/form-data" method="post" action="<?php echo $action_url; ?>">
				<?php wp_nonce_field( $nonce_check ); ?>

				<fieldset id="essential-fields">

					<p class="highlight-large"><?php _e( 'You are about the order the following service:', APP_TD ); ?></p>

					<div class="panel summary-info">
						<div>
							<strong><?php _e( 'Item', APP_TD ); ?>:</strong>
							<span><?php echo $listing->post_title; ?><span>
						</div>
						<div>
							<strong><?php _e( 'Seller', APP_TD ); ?>:</strong>
							<span><?php echo tr_the_provider_name(); ?><span>
						</div>
						<div>
							<strong><?php _e( 'Price', APP_TD ); ?>:</strong>
							<span><?php tr_service_price(); ?><span>
						</div>
						<div class="textalignright">
							<i class="genericon genericon-time"></i>
							<span><?php _e( 'Will deliver in', APP_TD );?> <?php tr_delivery_time(); ?></span>
						</div>
					</div>

					<div class="form-field">
						<label for="instructions"><?php _e( 'Your message to the seller', APP_TD ); ?>:</label>
						<?php tr_editor( '', 'instructions' ); ?>
					</div>

					<div class="form-field">
						<label for="contacts"><?php _e( 'Your contact information (email used by default)', APP_TD ); ?>:</label>
						<textarea name="contacts" id="contacts" ><?php _e( 'Email:' ); ?> <?php echo esc_textarea( tr_get_user_email() ); ?></textarea>
					</div>

					<div class="form-field">
						<input type="hidden" name="action" value="add-task">
						<input type="submit" class="button success large" value="<?php echo esc_attr( $action_text ); ?>">
					</div>
				</fieldset>

			</form>