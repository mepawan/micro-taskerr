<?php
/**
 * Slider HTML markup goes here
 *
 * @global TR_Slider $tr_slider Slider instance
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<ul class="attachements-slider" data-orbit data-options="bullets:false;timer:false;slide_number_text:<?php _e( 'of', APP_TD ); ?>;">
	<?php foreach ( $tr_slider->get_attachments() as $attachment ) { ?>
		<?php if( $mime_display = $tr_slider->display_mime_type( $attachment ) ) { ?>
			<li>
				<figure>
					<?php echo $mime_display; ?>
					<?php if ( $attachment->post_excerpt ) { ?>
						<figcaption class="orbit-caption">
							<?php echo $attachment->post_excerpt; ?>
						</figcaption>
					<?php } ?>
				</figure>
			</li>
		<?php } ?>
	<?php } ?>
</ul>