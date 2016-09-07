<?php
/**
 * Review Service Form
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
			<form id="review-service" enctype="multipart/form-data" method="post" action="<?php echo site_url( '/wp-comments-post.php' ); ?>">
				<?php wp_nonce_field( $nonce_check ); ?>
				<input type="hidden" name="comment_post_ID" value="<?php echo $listing->ID; ?>" />
				<input type="hidden" name="comment_type" value="<?php echo APP_REVIEWS_CTYPE; ?>" />
				<?php wp_comment_form_unfiltered_html_nonce(); ?>

				<fieldset id="essential-fields">
					<div class="form-field"><label>
						<?php _e( 'Rating', APP_TD ); ?>
						<span class="label-helper">(<?php _e( 'required', APP_TD ); ?>)</span>
						<div id="review-rating"></div>
					</label></div>
					<div class="form-field"><label>
						<?php _e( 'Review', APP_TD ); ?>
						<span class="label-helper">(<?php _e( 'required', APP_TD ); ?>)</span>
						<?php tr_editor( '', 'review_body', array( 'textarea_name' => 'comment' ) ); ?>
					</label></div>
				</fieldset>

				<fieldset>
					<div class="form-field">
						<input type="submit" class="button success large" value="<?php echo esc_attr( $action_text ); ?>">
					</div>
				</fieldset>
			</form>