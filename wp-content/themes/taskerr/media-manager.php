<?php
/**
 * Media Manager template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<div id="<?php echo esc_attr( $atts['id'] ); ?>" class="media-manager <?php echo esc_attr( $atts['class'] );  ?>">

	<?php if ( $atts['title'] ): ?>
		<label><?php echo $atts['title']; ?></label>
	<?php endif; ?>

		<div id="<?php echo esc_attr( $atts['id'] ); ?>" class="media_placeholder">
			<?php if ( empty( $atts['attachment_ids'] ) && empty( $atts['embed_urls'] ) ): ?>

				<span class="no-media">
					<?php echo $atts['no_media_text']; ?>
				</span>

			<?php endif; ?>

			<div class="media-attachments">
				<?php appthemes_output_attachments( $atts['attachment_ids'], $atts['attachment_params'] ); ?>
			</div>

			<div class="media-embeds">
				<?php foreach( (array) $atts['embed_urls'] as $url ) {
					$oembed = wp_oembed_get( trim( $url ) );
					if ( $oembed ) { ?>
						<div class="flex-video">
							<?php echo $oembed; ?>
						</div>
					<?php } else {
						echo $url;
					}
				} ?>
			</div>

		</div>

		<button group_id="<?php echo esc_attr( $atts['id'] ); ?>" class="button upload_button" upload_text="<?php echo esc_attr( $atts['upload_text'] ); ?>" manage_text="<?php echo esc_attr( $atts['manage_text'] ); ?>">
			<?php echo $atts['button_text']; ?>
		</button>

</div>
<hr>