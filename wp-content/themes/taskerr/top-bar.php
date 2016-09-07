<?php
/**
 * Generic Site top bar
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>

		<div id="top-bar-1" class="top-bar" data-topbar>
  			<ul class="title-area">
				<!-- Title Area -->
				<li class="name">
					<ul class="social-bar left">
						<?php foreach( tr_get_top_bar_social() as $network ){ ?>
							<li>
								<a href="<?php echo esc_url( tr_get_social_url( $network ) ); ?>" title="<?php echo tr_get_social_title( $network ); ?>">
									<i class="genericon genericon-<?php echo esc_attr( $network ); ?>"></i>
									<span><?php echo tr_get_social_title( $network ); ?></span>
								</a>
							</li>
						<?php } ?>
						<li>
							<a href="<?php echo esc_url( appthemes_get_feed_url() ); ?>" title="<?php _e( 'RSS', APP_TD ); ?>">
								<i class="genericon genericon-feed"></i>
								<span><?php _e( 'RSS', APP_TD ); ?></span>
							</a>
						</li>
					</ul>

				</li>
    			<li class="toggle-topbar menu-icon">
					<a href="#"><span><?php _e( 'Menu', APP_TD ); ?></span></a>
				</li>
  			</ul>
			<section class="top-bar-section">
				<!-- Right Nav Section -->
				<ul class="right">

					<?php if( is_user_logged_in() ) { ?>
						<li>
							<a href="<?php echo esc_url( tr_get_dashboard_favorites_url() ); ?>" title="<?php _e( 'Favorites', APP_TD ); ?>">
								<i class="genericon genericon-heart"></i>
								<span class="hide-for-medium-only"><?php _e( 'Favorites', APP_TD ); ?></span>
							</a>
						</li>
						<li class="inbox-btn<?php if ( tr_get_notifications_count() ) echo ' has-dropdown unread-inbox-btn'; ?>">
							<a href="<?php echo esc_url( tr_get_dashboard_notifications_url() ); ?>" title="<?php _e( 'Notifications', APP_TD ); ?>">
								<i class="genericon genericon-<?php echo ( tr_get_notifications_count() ) ? 'subscribed' : 'mail'; ?>"></i>
								<span class="hide-for-medium-only"><?php _e( 'Notifications', APP_TD ); ?>&nbsp;<?php tr_notifications_count(); ?></span>
							</a>
							<?php get_template_part( 'loop-top-bar-notifications', app_template_base() ); ?>
						</li>
						<li>
							<a href="<?php echo esc_url( appthemes_get_edit_profile_url() ); ?>" title="<?php _e( 'Your Profile', APP_TD ); ?>">
								<i class="genericon genericon-user"></i>
								<span class="hide-for-medium-only"><?php _e( 'Your Profile', APP_TD ); ?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( tr_get_dashboard_url() ); ?>" title="<?php _e( 'Dashboard', APP_TD ); ?>">
								<i class="genericon genericon-cog"></i>
								<span class="hide-for-medium-only"><?php _e( 'Dashboard', APP_TD ); ?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( wp_logout_url() ); ?>" title="<?php _e( 'Logout', APP_TD ); ?>">
								<i class="genericon genericon-key"></i>
								<span class="hide-for-medium-only"><?php _e( 'Logout', APP_TD ); ?></span>
							</a>
						</li>

					<?php } else { ?>

						<li>
							<a href="<?php echo esc_url( wp_login_url() ); ?>" title="<?php _e( 'Login', APP_TD ); ?>">
								<i class="genericon genericon-key"></i>
								<span class="hide-for-medium-only"><?php _e( 'Login', APP_TD ); ?></span>
							</a>
						</li>

						<?php if ( get_option( 'users_can_register' ) ) { ?>
							<li>
								<a href="<?php echo esc_url( wp_registration_url() ); ?>" title="<?php _e( 'Register', APP_TD ); ?>">
									<i class="genericon genericon-lock"></i>
									<span class="hide-for-medium-only"><?php _e( 'Register', APP_TD ); ?></span>
								</a>
							</li>
						<?php } ?>

					<?php } ?>
				</ul>
			</section>
		</div>