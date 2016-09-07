<?php
/**
 * None posts on Dashboard
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>

	<div class="page-content">
		<p>

			<?php switch ( $dashboard_type ) {

				case 'service':
					_e( 'You have no services at this time. ', APP_TD ) . html_link( tr_get_service_create_url(), __( 'Click Here', APP_TD ) ) . __( ' to add a service now.', APP_TD );
					break;

				case 'tasks':
					_e( 'You have no tasks at this time. ', APP_TD );
					break;

				case 'purchases':
					_e( 'You have no purchases at this time. ', APP_TD );
					break;

				case 'favorites':
					_e( 'You have no favorites at this time. ', APP_TD );
					break;

				case 'reviews':
					_e( 'You have no reviews at this time. ', APP_TD );
					break;

				default:
					break;
			} ?>

		</p>
	</div>