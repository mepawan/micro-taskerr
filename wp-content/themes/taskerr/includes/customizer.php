<?php
/**
 * Theme customizer
 *
 * Contains methods for customizing the theme customization screen.
 *
 * @package Taskerr\Customizer
 * @author  AppThemes
 * @link    http://codex.wordpress.org/Theme_Customization_API
 * @since   Taskerr 1.0
 */
class TR_Customize {

	static $customizer = array();

	protected static function get_controls() {
		global $tr_options;

		$scheme = self::get_color_schemes( $tr_options->color );

		$modificators = ( isset( $scheme['controls'] ) ) ? $scheme['controls'] : array();

		$controls = array(
			'header_bgcolor' => array(
				'control' => array(
					'label' => __( 'Header Background Color', APP_TD ),
				),
				'css' => array(
					'#header' => 'background-color',
				),
			),
			'footer_bgcolor' => array(
				'control' => array(
					'label' => __( 'Footer Background Color', APP_TD ),
				),
				'css' => array(
					'#footer' => 'background-color',
				),
			),
		);

		foreach ( $controls as $key => $control ) {
			if ( isset( $modificators[ $key ] ) ) {
				$controls[ $key ] = self::_array_merge_recursive( $controls[ $key ],  $modificators[ $key ] );
			}
		}

		$controls = apply_filters( 'tr_theme_colors', $controls );

		if ( ! is_array( $controls ) ) {
			return array();
		}

		return $controls;
	}

	/**
	 * Retrieves the theme available color choices.
	 */
	public static function get_color_choices(){
		$color_choices = array();

		foreach ( self::get_color_schemes() as $key => $scheme ) {
			$color_choices[ $key ] = ( isset( $scheme['name'] ) ) ? $scheme['name'] : $key;
		}

		return $color_choices;
	}

	/**
	 * Retrieves the color schemes with all appropriate control modificators.
	 *
	 * @uses apply_filters() Calls 'tr_color_schemes'
	 */
	public static function get_color_schemes( $scheme = false ){

		$color_schemes = apply_filters( 'tr_color_schemes', array(
			'blue' => array(
				'name'     => __( 'Blue (default)', APP_TD ),
				'controls' => array(
					'header_bgcolor' => array(
						'settings' => array(
							'default' => '#378ad8',
						),
					),
					'header_image' => array(
						'settings' => array(
							'default' => get_template_directory_uri() . '/img/logo-red-clock.png',
						),
					),
					'footer_bgcolor' => array(
						'settings' => array(
							'default'  => '#333a3e',
						),
					),
				),
			),
			'turquoise' => array(
				'name'     => __( 'Turquoise', APP_TD ),
				'controls' => array(
					'header_bgcolor' => array(
						'settings' => array(
							'default' => '#6ebdc1',
						),
					),
					'header_image' => array(
						'settings' => array(
							'default' => get_template_directory_uri() . '/img/logo-red-clock.png',
						),
					),
					'footer_bgcolor' => array(
						'settings' => array(
							'default'  => '#414545',
						),
					),
				),
			),
			'green' => array(
				'name'     => __( 'Green', APP_TD ),
				'controls' => array(
					'header_bgcolor' => array(
						'settings' => array(
							'default' => '#6cae71',
						),
					),
					'header_image' => array(
						'settings' => array(
							'default' => get_template_directory_uri() . '/img/logo-yellow-clock.png',
						),
					),
					'footer_bgcolor' => array(
						'settings' => array(
							'default'  => '#424542',
						),
					),
				),
			),
			'yellow' => array(
				'name'     => __( 'Yellow', APP_TD ),
				'controls' => array(
					'header_bgcolor' => array(
						'settings' => array(
							'default' => '#f5b445',
						),
					),
					'header_image' => array(
						'settings' => array(
							'default' => get_template_directory_uri() . '/img/logo-red-clock.png',
						),
					),
					'footer_bgcolor' => array(
						'settings' => array(
							'default'  => '#42413e',
						),
					),
				),
			),
			'red' => array(
				'name'     => __( 'Red', APP_TD ),
				'controls' => array(
					'header_bgcolor' => array(
						'settings' => array(
							'default' => '#e9604a',
						),
					),
					'header_image' => array(
						'settings' => array(
							'default' => get_template_directory_uri() . '/img/logo-yellow-clock.png',
						),
					),
					'footer_bgcolor' => array(
						'settings' => array(
							'default'  => '#322f2f',
						),
					),
				),
			),
		) );

		if ( $scheme && isset( $color_schemes[ $scheme ] ) ) {
			return $color_schemes[ $scheme ];
		}

		return $color_schemes;
	}

	/**
	 * This hooks into 'customize_register' (available as of WP 3.4) and allows
	 * you to add new sections and controls to the Theme Customize screen.
	 *
	 * Note: To enable instant preview, we have to actually write a bit of custom
	 * javascript. See live_preview() for more.
	 *
	 * @see add_action('customize_register',$func)
	 * @param \WP_Customize_Manager $wp_customize
	 * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
	 * @since Taskerr 1.0
	 */
	public static function register( $wp_customize ) {

		global $tr_options;

		$wp_customize->add_setting( 'tr_options[color]', array(
			'default' => $tr_options->color,
			'type' => 'option',
		) );

		$wp_customize->add_control( 'tr_color_scheme', array(
			'label'      => __( 'Color Scheme', APP_TD ),
			'section'    => 'colors',
			'settings'   => 'tr_options[color]',
			'type'       => 'radio',
			'choices'    => self::get_color_choices(),
		) );

		$options = self::get_controls();

		$n = 35;

		foreach ( $options as $key => $option ) {

			if ( ! isset( $option['settings'] ) || ! isset( $option['control'] ) ) {
				continue;
			}

			$settings = array_merge( $option['settings'], array(
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'postMessage',
			) );

			$control = array_merge( $option['control'], array(
				'section'  => 'colors',
				'priority' => $n++,
				'settings' => $key,
			) );

			$control_name = 'tr_' . $key;

			$wp_customize->add_setting( $key, $settings );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control_name, $control ) );
		}

		if ( $wp_customize->is_preview() && ! is_admin() ) {
			add_action( 'wp_footer', array( 'TR_Customize', 'live_preview' ), 21);
		}
	}

	/**
	 * This will output the custom WordPress settings to the live theme's WP head.
	 *
	 * Used by hook: 'tr_print_customizer_styles'
	 *
	 * @since Taskerr 1.0
	 */
	public static function header_output() {
		$output = array();

		foreach ( self::get_controls() as $key => $option) {

			$mod = get_theme_mod( $key );

			if ( ! isset( $option['css'] ) || ! is_array( $option['css'] ) || empty( $mod ) || $mod == $option['settings']['default'] ) {
				continue;
			}

			$groups = array();

			foreach ( $option['css'] as $selector => $property ) {
				$groups[ $property ][] = $selector;
			}

			foreach ( $groups as $property => $selector ) {
				$selector = implode( ', ', $selector );
				$output[] = sprintf( '%s { %s:%s; }', $selector, $property, $mod );
			}

		}

		if ( ! empty( $output ) ) { ?>
		<!--Customizer CSS-->
		<style type="text/css">
		<?php echo implode( "\n", $output );?>
		</style>
		<!--/Customizer CSS-->
		<?php }

	}

	/**
	 * This outputs the javascript needed to automate the live settings preview.
	 * Also keep in mind that this function isn't necessary unless your settings
	 * are using 'transport'=>'postMessage' instead of the default 'transport'
	 * => 'refresh'
	 *
	 * Used by hook: 'customize_preview_init'
	 *
	 * @see add_action('customize_preview_init',$func)
	 * @since Taskerr 1.0
	 */
	public static function live_preview() {
		?>
		<script type="text/javascript">
		( function( $ ){
		<?php foreach ( self::get_controls() as $key => $option) { ?>
		wp.customize('<?php echo esc_js($key); ?>',function( value ) {
			value.bind(function(to) {
			<?php foreach ( $option['css'] as $selector => $property ) {?>
				$('<?php echo $selector; ?>').css('<?php echo esc_js($property); ?>', to ? to : '' );
			<?php } ?>
			});
		});
		<?php } ?>
			var newScheme = parent.trNewScheme;
			var colorSchemes = parent.trColorSchemes;
			if(newScheme && colorSchemes && colorSchemes[newScheme].controls){
				controls = colorSchemes[newScheme].controls;
				$.each(controls, function(controlName, controlSettings) {
					if(controlSettings.settings.default){
						var defaultVal = controlSettings.settings.default;
						var control = parent.wp.customize.control.instance('tr_'+controlName);
						if (typeof control === 'undefined'){
							return;
						}

						var picker = control.container.find('.color-picker-hex');
						control.setting.set(defaultVal);
						picker.wpColorPicker('defaultColor', defaultVal);
						picker.val(control.setting()).change();
					}
				});
			}
		} )( jQuery );
		</script>
		<?php
	}

	/**
	 * Tweaks Color Schemes control
	 */
	public static function footer_output() {
		?>
		<script type="text/javascript">
			var trNewScheme;
			var trColorSchemes = <?php echo json_encode( self::get_color_schemes() ); ?>;
			(function($){
				$('input[name=_customize-radio-tr_color_scheme]').change(function(){
					trNewScheme = $(this).val();
					if ( trNewScheme ) {
						var defaultVal = trColorSchemes[trNewScheme].controls.header_image.settings.default;
						var control = wp.customize.control.instance('header_image');
						if (defaultVal === wp.customize.instance('header_image').get()) {
							control.setting.set(defaultVal);
							var img = control.container.find('img');
							img.attr('src', defaultVal);
						}
					}
				});
			})(jQuery);
		</script>
		<?php
	}

	protected static function _array_merge_recursive( $array1, $array2 ) {
		if ( ! is_array( $array1 ) || ! is_array( $array2 ) ) {
			return $array2;
		}

		foreach ( $array2 as $key2 => $value2 ) {
			if ( ! isset( $array1[ $key2 ] ) ) {
				$array1[ $key2 ] = $value2;
			} else {
				$array1[ $key2 ] = self::_array_merge_recursive( $array1[ $key2 ], $value2 );
			}
		}

		return $array1;
	}

}

/**
 * Returns default customizer value for given mod
 *
 * @global scbOptions $tr_options Theme options
 * @param  string     $mod        Mod name
 *
 * @return string Default value for given mod
 */
function tr_get_default_customizer_value( $mod = false ) {
	global $tr_options;

	$scheme = TR_Customize::get_color_schemes( $tr_options->color );
	if ( $mod && isset( $scheme['controls'][$mod]['settings']['default'] ) ) {
		return $scheme['controls'][$mod]['settings']['default'];
	}
}


if ( ! is_child_theme() ) {
	// Setup the Theme Customizer settings and controls...
	add_action( 'customize_register', array( 'TR_Customize', 'register' ) );

	// Output custom CSS to live site
	add_action( 'tr_print_customizer_styles', array( 'TR_Customize', 'header_output' ) );

	add_action( 'customize_controls_print_footer_scripts', array( 'TR_Customize', 'footer_output' ) );
}