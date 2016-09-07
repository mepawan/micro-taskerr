<?php
/**
 * Optional advertise block over the header
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<?php if ( is_active_sidebar( 'tr-advert-top' ) ) { ?>
	<!-- Header ad space -->
	<div id="header-ad" class="banner hide-for-small-only">
		<?php dynamic_sidebar( 'tr-advert-top' ); ?>
		<?php appthemes_advertise_header(); ?>
	</div><!-- /Header ad space -->
<?php } ?>