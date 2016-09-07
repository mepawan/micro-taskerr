<?php
/**
 * Optional advertise block under the Loop
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<?php if ( is_active_sidebar( 'tr-advert-bottom' ) ) { ?>
	<!-- ad space -->
	<div class="row">
		<div id="footer-ad" class="banner type-2 large-12 columns hide-for-small-only">
			<?php dynamic_sidebar( 'tr-advert-bottom' ); ?>
			<?php appthemes_advertise_content(); ?>
		</div><!-- end columns -->
	</div><!-- end row -->
	<!-- / ad space -->
<?php } ?>