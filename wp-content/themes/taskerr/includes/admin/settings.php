<?php
/**
 * Admin Settings
 *
 * @package Taskerr\Admin\Settings
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Settings Tabs page
 */
class TR_Settings_Admin extends APP_Tabs_Page {

	function setup() {

		$this->textdomain = APP_TD;

		$this->args = array(
			'page_title'            => __( 'Taskerr Settings', APP_TD ),
			'menu_title'            => __( 'Settings', APP_TD ),
			'page_slug'             => 'app-settings',
			'parent'                => 'app-dashboard',
			'screen_icon'           => 'options-general',
			'admin_action_priority' => 11,
		);

	}

	protected function init_tabs() {
		// Remove unwanted query args from urls
		$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'firstrun' ), $_SERVER['REQUEST_URI'] );

		$this->tabs->add( 'general', __( 'General', APP_TD ) );
		$this->tabs->add( 'services', __( 'Services', APP_TD ) );
		$this->tabs->add( 'notifications', __( 'Notifications', APP_TD ) );

		$this->social_tab();

		$this->tab_sections['general']['security'] = array(
			'title'	 => __( 'Security Settings', APP_TD ),
			'fields' => array(
				array(
					'title'  => __( 'Back Office Access', APP_TD ),
					'desc'   => '<br />' . sprintf( __( "View the WordPress <a target='_new' href='%s'>Roles and Capabilities</a> for more information.", APP_TD ), 'http://codex.wordpress.org/Roles_and_Capabilities' ),
					'tip'    => __( 'Allows you to restrict access to the WordPress Back Office (wp-admin) by specific role. Keeping this set to admins only is recommended. Select Disable if you have problems with this feature.', APP_TD ),
					'type'   => 'select',
					'name'   => 'admin_security',
					'values' => array(
						'manage_options'     => __( 'Admins Only', APP_TD ),
						'edit_others_posts'  => __( 'Admins, Editors', APP_TD ),
						'publish_posts'      => __( 'Admins, Editors, Authors', APP_TD ),
						'edit_posts'         => __( 'Admins, Editors, Authors, Contributors', APP_TD ),
						'read'               => __( 'All Access', APP_TD ),
						'disable'            => __( 'Disable', APP_TD ),
					),
				),
				array(
					'title' => __( 'Disable WordPress Login Page', APP_TD ),
					'name'  => 'disable_wp_login',
					'type'  => 'checkbox',
					'desc'  => __( 'Yes', APP_TD ),
					'tip'   => __( 'If someone tries to access <code>wp-login.php</code> directly, they will be redirected to Taskerr themed login pages. If you want to use any "maintenance mode" plugins, you should enable the default WordPress login page.', APP_TD ),
				),
			),
		);

		$this->tab_sections['general']['contacts'] = array(
			'title'	 => __( 'Contact Settings', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Public Email', APP_TD ),
					'tip'   => __( 'Publicy visible email address', APP_TD ),
					'type'  => 'email',
					'name'  => 'email',
					'extra' => array(
						'style' => 'width: 200px',
					),
				),
				array(
					'title' => __( 'Phone number', APP_TD ),
					'tip'   => __( 'Publicy visible phone number', APP_TD ),
					'type'  => 'tel',
					'name'  => 'phone',
					'extra' => array(
						'style' => 'width: 200px',
					),
				),
				array(
					'title' => __( 'Skype', APP_TD ),
					'tip'   => __( 'Publicy visible Skype account', APP_TD ),
					'type'  => 'text',
					'name'  => 'skype',
					'extra' => array(
						'style' => 'width: 200px',
					),
				),
			),
		);

		$this->tab_sections['general']['appearance'] = array(
			'title'  => __( 'Appearance', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Favicon', APP_TD ),
					'desc'	=> __( 'Type an image URL.', APP_TD ),
					'type'  => 'text',
					'name'  => 'favicon_url',
					'tip'   => __( 'Paste the URL of your web site favicon image here.', APP_TD ),
				),
				array(
					'title' => __( 'Theme Customizer', APP_TD ),
					'desc'  => sprintf( __( '<a href="%s">Customize Taskerr</a> design and settings and see the results real-time without opening or refreshing a new browser window.' , APP_TD), 'customize.php' ),
					'type'  => 'text',
					'name'  => '_blank',
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip'   => __( 'Use the WordPress Theme Customizer to try out different design options and other Taskerr settings.', APP_TD ),
				),
				array(
					'title' => __( 'Header Image', APP_TD ),
					'desc'  => sprintf( __( 'Set Your Header Image in the <a href="%s">Header</a> settings.', APP_TD ),
						 'themes.php?page=custom-header' ),
					'type'  => 'text',
					'name'  => '_blank',
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip'   => __( 'This is where you can upload/manage your logo that appears in your site\'s header along with settings to control the text below the logo.', APP_TD ),
				),
				array(
					'title' => __( 'Background Image', APP_TD ),
					'desc'  => sprintf( __( 'Set Your Background Image in the <a href="%s">Background</a> settings.', APP_TD ),
						 'themes.php?page=custom-background' ),
					'type'  => 'text',
					'name'  => '_blank',
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip'   => __( 'This is where you can upload/manage background.', APP_TD ),
				),
			),
		);

		$this->tab_sections['services'][] = array(
			'fields' => array(
				array(
					'title' => __( 'Charge for Services', APP_TD ),
					'name'  => 'service_charge',
					'type'  => 'checkbox',
					'desc'  => __( 'Yes', APP_TD ),
					'tip'   => sprintf( __( 'Do you want to charge for creating a service on your site? You can manage your <a href="%s">Payments Settings</a> in the Payments Menu.', APP_TD ), 'admin.php?page=app-payments-settings'),
				),
				array(
					'title' => __( 'Moderate Services', APP_TD ),
					'type'  => 'checkbox',
					'name'  => 'moderate_services',
					'desc'  => __( 'Yes', APP_TD ),
					'tip'   => __( 'Do you want to moderate new services before they are displayed live?', APP_TD ),
				),
				array(
					'title' => __( 'Duration (Free Services Only)', APP_TD ),
					'type'  => 'number',
					'name'  => 'service_duration',
					'tip'   => __( 'The duration in days to be applied to new services.', APP_TD ),
				),
			)
		);

		$this->tab_sections['services']['media'] = array(
			'title'  => __( 'Media Options', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Max Images Per Listing', APP_TD ),
					'type'  => 'number',
					'name'  => 'max_images',
					'tip'   => __( 'The number of images the service provider can upload with each of their listing. "0" - uploading images is not allowed, "-1" - no limit.', APP_TD ),
				),
				array(
					'title' => __( 'Max Videos Per Listing', APP_TD ),
					'type'  => 'number',
					'name'  => 'max_videos',
					'tip'   => __( 'The number of videos the service provider can upload with each of their listing. "0" - uploading videos is not allowed, "-1" - no limit.', APP_TD ),
				),
				array(
					'title' => __( 'Max Embeddings Per Listing', APP_TD ),
					'type'  => 'number',
					'name'  => 'max_embeds',
					'tip'   => __( 'The number of media links the service provider can embed to each of their listing. "0" - embedding media is not allowed, "-1" - no limit.', APP_TD ),
				),
				array(
					'title' => __( 'Max Size Per Image', APP_TD ),
					'type'  => 'text',
					'name'  => 'max_image_size',
					'tip'   => __( 'The maximum image size (per image) the service provider can upload with each of their listing. "-1" - use system value. Default unit is Bytes ("B"), you can use also "KB", "K", "MB", "M", "GB", "G".', APP_TD ),
				),
				array(
					'title' => __( 'Max Size Per Video', APP_TD ),
					'type'  => 'text',
					'name'  => 'max_video_size',
					'tip'   => __( 'The maximum video size (per video) the service provider can upload with each of their listing. "-1" - use system value. Default unit is Bytes ("B" can be omitted), you can use also "KB", "K", "MB", "M", "GB", "G".', APP_TD ),
				),
			),
		);

		$this->tab_sections['services']['integration'] = array(
			'title'  => __( 'Integration', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Display ShareThis on Services Lists', APP_TD ),
					'type'  => 'checkbox',
					'desc'  => __( 'Yes', APP_TD ),
					'name'  => 'listing_sharethis',
					'extra' => ( ! function_exists ( 'sharethis_button' ) ? array ( 'disabled' => 'disabled' ) : '' ),
					'tip'   => sprintf( __( 'If you have the <a href="%1$s" target="_blank">ShareThis</a> plugin instaled it will be only visible on single services. This option enables you to display it on the services list views also.', APP_TD ) , 'http://wordpress.org/extend/plugins/share-this/' ),
				),
				array(
					'title' => __( 'Display ShareThis on Blog Posts', APP_TD ),
					'type'  => 'checkbox',
					'desc'  => __( 'Yes', APP_TD ),
					'name'  => 'blog_post_sharethis',
					'extra' => ( ! function_exists ( 'sharethis_button' ) ? array ( 'disabled' => 'disabled' ) : '' ),
					'tip'   => sprintf( __( 'If you have the <a href="%1$s" target="_blank">ShareThis</a> plugin instaled it will be only visible on single listings. This option enables you to display it on single blog posts also.', APP_TD ) , 'http://wordpress.org/extend/plugins/share-this/' ),
				),
			),

		);

		$this->tab_sections['notifications']['notification'] = array(
			'title'  => __( 'Notifications', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'New Services', APP_TD ),
					'type'  => 'checkbox',
					'name'  => 'notify_new_services',
					'desc'  => __( 'Yes', APP_TD ),
					'tip'   => __( 'Notify admins when new services are posted', APP_TD ),
				),
			)
		);

	}

	private function social_tab() {

		$this->tabs->add( 'social', __( 'Social', APP_TD ) );

		$allowed_networks = apply_filters( 'tr_settings_social', _tr_allowed_networks() );

		foreach( $allowed_networks as $network ){

			$fields[] = array(
				'title' => APP_Social_Networks::get_title( $network ),
				'type'  => 'text',
				'tip'   => APP_Social_Networks::get_tip( $network ),
				'extra' => array(
					'style' => 'width: 200px',
				),
				'name'  => array( 'social', $network )
			);

		}
		$this->tab_sections['social']['accounts'] = array(
			'title'  => __( 'Accounts', APP_TD ),
			'fields' => $fields,
		);

	}

}
