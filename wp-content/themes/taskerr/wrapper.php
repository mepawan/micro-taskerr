<?php
/**
 * Template wrapper
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<title><?php wp_title(''); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<?php wp_head(); ?>

	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

	<?php do_action( 'tr_print_customizer_styles' ); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

	<script type="text/javascript">
		document.body.className = document.body.className.replace('no-js','js');
	</script>

	<?php appthemes_before(); ?>

	<?php appthemes_before_header(); ?>

	<!-- HEADER -->
	<header id="header" role="banner">
		<?php get_header( app_template_base() ); ?>
	</header><!-- /HEADER -->

	<?php appthemes_after_header(); ?>

	<!-- WIDGETIZED AREA -->
	<div id="top-widgets" role="complementary">
		<?php get_template_part( 'widgetized-area', app_template_base() ); ?>
	</div><!-- /WIDGETIZED AREA -->

	<!-- CONTENT -->
	<div id="content">
		<?php load_template( app_template_path() ); ?>
	</div><!-- /CONTENT -->

	<?php appthemes_before_footer(); ?>

	<!-- FOOTER -->
	<footer id="footer" role="contentinfo">
		<?php get_footer( app_template_base() ); ?>
	</footer><!-- /FOOTER -->

	<?php appthemes_after_footer(); ?>

	<?php appthemes_after(); ?>

	<?php wp_footer();?>

	<?php
		// Fix WordPress <-> Foundation sticky conflict
		// See https://github.com/zurb/foundation/pull/1494#issuecomment-15294677
	?>
	<script>
		jQuery(document).foundation({topbar: {stickyClass: 'sticky-top-bar'}});
	</script>

</body>
</html>
