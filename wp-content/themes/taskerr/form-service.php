<?php
/**
 * New Service Form
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
			<form id="edit-service" class="edit-service" enctype="multipart/form-data" method="post" action="<?php echo $action_url ?>">
				<?php wp_nonce_field( $nonce_check ); ?>

				<fieldset id="essential-fields">
					<div class="form-field"><label>
						<?php _e( 'Title', APP_TD ); ?>
						<span class="required"> *</span>
						<input name="post_title" type="text" value="<?php echo $listing->post_title; ?>" class="required"/>
					</label></div>

					<div class="form-field"><label>
						<?php _e( 'Days until Delivery', APP_TD ); ?>
						<span class="required"> *</span>
						<input name="delivery_time" type="text" value="<?php echo $listing->delivery_time; ?>" class="required digits" min="1"/>
					</label></div>

					<div class="form-field"><label>
						<?php _e( 'Price', APP_TD ); ?>
						<span class="required"> *</span>
						<input name="price" type="text" value="<?php echo $listing->price; ?>" class="required"/>
					</label></div>

					<div class="form-field"><label>
						<?php _e( 'Service Description', APP_TD ); ?>
						<span class="required"> *</span>
						<?php tr_editor( $listing->post_content, 'post_content' ); ?>
					</label></div>
				</fieldset>

				<fieldset id="category-fields">
					<div class="form-field"><label>
						<?php _e( 'Tags', APP_TD ); ?>
						<input name="tax_input[<?php echo TR_SERVICE_TAG; ?>]" type="text" value="<?php the_listing_tags_to_edit( $listing->ID ); ?>" />
					</label></div>

					<div class="form-field">
						<label>
							<?php _e( 'Categories', APP_TD ); ?>
							<span class="required"> *</span>
						</label>
						<div class='frame'>
							<ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-3">
								<?php tr_get_edit_categories( $listing, '', TR_SERVICE_CATEGORY ); ?>
							</ul>
						</div>
					</div>
				</fieldset>

				<fieldset id="misc-fields">
					<?php tr_the_images_manager( $listing->ID ); ?>
					<?php tr_the_videos_manager( $listing->ID ); ?>
					<?php tr_the_embeds_manager( $listing->ID ); ?>
				</fieldset>

				<fieldset>
					<?php do_action( 'appthemes_update_fields' ); ?>
					<input type="hidden" name="action" value="update-service">
					<input type="hidden" name="tr_post_id" value="<?php echo esc_attr( $listing->ID ); ?>">
					<div class="form-field">
						<button class="button large success" type="submit"><?php echo $action_text; ?></button>
					</div>
				</fieldset>
			</form>