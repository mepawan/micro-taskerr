<?php
/**
 * Dashboard Sidebar template part
 *
 * called from dashboard-setup.php
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

$is_own_dashboard = true;
$dashboard_user = wp_get_current_user();

appthemes_before_sidebar_widgets( 'tr-dashboard' );

?>
<div class="row">
	<div id="tr_account_bage" class="widget static-widget widget-account-bage large-12 columns">
		<div class="clearfix">
			<div class="medium-text-center user-avatar">
				<?php echo get_avatar( $dashboard_user->ID, 200 ); ?>
			</div>

			<h3>
				<?php _e( 'Welcome' , APP_TD ); ?>,
				<?php echo $dashboard_user->display_name; ?>
			</h3>

			<p><?php _e( 'Manage your services or edit your profile from your personalized dashboard.', APP_TD ); ?></p>

			<div class="row collapse" data-equalizer>
				<div class="small-6 columns edit-profile">
					<a class="button alert" href="<?php echo esc_url( appthemes_get_edit_profile_url() ); ?>" data-equalizer-watch>
						<?php _e( 'Edit profile', APP_TD ); ?>
					</a>
				</div>
				<div class="small-6 columns add-service">
					<a class="button secondary" href="<?php echo esc_url( tr_get_service_create_url() ); ?>" data-equalizer-watch>
						<?php _e( 'Add a service', APP_TD ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div id="tr_dashboard_links" class="widget static-widget widget-dashboard-links large-12 columns">
		<div class="clearfix">
			<ul>
				<?php
				tr_dashboard_pages();
				do_action('tr_dashboard_sidebar_links', $dashboard_user ); ?>
			</ul>
		</div>
	</div>
</div>

<div class="row">
	<aside id="tr_account_stats" class="widget static-widget widget-account-stats large-12 columns">
		<header>
			<h1>
				<?php _e( 'Statistics', APP_TD ); ?>
			</h1>
		</header>
		<div class="clearfix">
			<ul>
				<li>
					<span class="left"><?php _e( 'Rating', APP_TD );?></span>
					<strong class="right"><?php tr_author_rating( $dashboard_user->ID ); ?></strong>
				</li>
				<li>
					<span class="left"><?php _e( 'Current Tasks', APP_TD );?></span>
					<strong class="right"><?php tr_get_provider_tasks_current_count(); ?></strong>
				</li>
				<li>
					<span class="left"><?php _e( 'Tasks completed', APP_TD );?></span>
					<strong class="right"><?php tr_provider_tasks_completed(); ?></strong>
				</li>
				<li>
					<span class="left"><?php _e( 'Services purchased', APP_TD );?></span>
					<strong class="right"><?php tr_get_user_services_purchased_count(); ?></strong>
				</li>
				<li>
					<span class="left"><?php _e( 'Total earnings', APP_TD );?></span>
					<strong class="right"><?php tr_get_provider_earnings(); ?></strong>
				</li>
				<li>
					<span class="left"><?php _e( 'Reviews received', APP_TD );?></span>
					<strong class="right"><?php tr_get_user_reviews_count(); ?></strong>
				</li>
				<li>
					<span class="left"><?php _e( 'Reviews made', APP_TD );?></span>
					<strong class="right"><?php tr_get_user_reviews_made_count(); ?></strong>
				</li>
			</ul>
		</div>
	</aside>
</div>

<div class="row">
	<aside id="tr_account_info" class="widget static-widget widget-account-info large-12 columns">
		<header>
			<h1>
				<?php _e( 'Account Info', APP_TD ); ?>
			</h1>
		</header>
		<div class="clearfix">
			<?php $user_social_networks = tr_get_user_social( $dashboard_user->ID ); ?>
			<?php if ( ! empty( $user_social_networks ) || tr_has_social( 'url', $dashboard_user->ID ) ) { ?>
				<ul>
				<?php foreach( $user_social_networks as $network ){ ?>
					<li>
						<a href="<?php echo esc_url( tr_get_social_url( $network, $dashboard_user->ID ) ); ?>" title="<?php echo tr_get_social_title( $network ); ?>">
							<i class="genericon genericon-<?php echo esc_attr( $network ); ?>"></i>
							<span>
								<?php if ( 'twitter' === $network ) echo '@';
								echo tr_get_social( $network, $dashboard_user->ID ); ?>
							</span>
						</a>
					</li>
				<?php } ?>
				<?php if( tr_has_social( 'url', $dashboard_user->ID ) ) { ?>
					<li>
						<a href="<?php echo esc_url( tr_get_social( 'url', $dashboard_user->ID ), 'mailto' ); ?>" title="<?php _e( 'Website', APP_TD ); ?>">
							<i class="genericon genericon-link"></i>
							<span><?php echo preg_replace( '#^http(s)?://#', '', tr_get_social( 'url', $dashboard_user->ID ) ); ?></span>
						</a>
					</li>
				<?php } ?>
				</ul>
			<?php } else { ?>
				<p><?php _e( 'No account information was provided. Please, edit your profile to add more info.', APP_TD ); ?></p>
				<a class="button alert aligncenter" href="<?php echo esc_url( appthemes_get_edit_profile_url() ); ?>" data-equalizer-watch>
					<?php _e( 'Edit profile', APP_TD ); ?>
				</a>
			<?php } ?>
		</div>
	</aside>
</div>

<?php dynamic_sidebar( 'tr-dashboard' );
appthemes_after_sidebar_widgets( 'tr-dashboard' );