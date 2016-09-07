<?php
/**
 * Implement an optional custom header for Taskerr
 *
 * See http://codex.wordpress.org/Custom_Headers
 *
 * @package Taskerr\Header
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Set up the WordPress core custom header arguments and settings.
 *
 * @uses add_theme_support() to register support for 3.4 and up.
 * @uses tr_header_style() to style front-end.
 * @uses tr_admin_header_style() to style wp-admin form.
 * @uses tr_admin_header_image() to add custom markup to wp-admin form.
 *
 * @since Taskerr 1.0
 */
function tr_custom_header_setup() {
	$args = array(
		// Text color and image (empty to use none).
		'default-text-color'     => 'blank',
		'header-text'            => true,
		'default-image'          => tr_get_default_customizer_value( 'header_image' ),

		// Set height and width.
		'height'                 => 27,
		'width'                  => 151,

		// Random image rotation off by default.
		'random-default'         => false,

		// Callbacks for styling the header and the admin preview.
		'wp-head-callback'       => 'tr_header_style',
		'admin-preview-callback' => 'tr_admin_header_image',
	);

	add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'tr_custom_header_setup' );

/**
 * Style the header text displayed on the blog.
 *
 * get_header_textcolor() options: fff is default, hide text (returns 'blank'), or any hex value.
 *
 * @since Taskerr 1.0
 */
function tr_header_style() {
	$text_color = get_header_textcolor();

	// If we get this far, we have custom styles.
	?>
	<style type="text/css" id="twentytwelve-header-css">
	<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px 1px 1px 1px); /* IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text, use that.
		else :
	?>
		.site-header h1 a,
		.site-header h1 a:hover,
		.site-header h2 {
			color: #<?php echo $text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}

/**
 * Output markup to be displayed on the Appearance > Header admin panel.
 *
 * This callback overrides the default markup displayed there.
 *
 * @since Taskerr 1.0
 */
function tr_admin_header_image() {
	?>
	<div id="headimg" style="background: <?php echo esc_attr( get_theme_mod( 'header_bgcolor' ) );?>;">
		<?php
		if ( ! display_header_text() )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_header_textcolor() . ';"';
		?>
		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<h2 id="desc" class="displaying-header-text"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></h2>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }