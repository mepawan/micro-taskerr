<?php
/**
 * Generic widgetized area under the header
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
		<!-- widgetized area below navbar -->
		<?php dynamic_sidebar( 'tr-central' ); ?>

		<?php if ( is_singular() ) {
			if ( is_page() ) { ?>

			<?php } elseif ( is_singular( TR_SERVICE_PTYPE ) ) {

			} else {

			}
		}
