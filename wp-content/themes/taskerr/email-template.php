<?php
/**
 * Generic Email Body template
 *
 * With this template you can/can't do following:
 * - You can customize this template by copying this file to your child theme.
 * - You can't override this template for specific email type.
 * - Add CSS styles directly to HTML tags in attribute "style".
 * - Don't use "id" or "class" selectors - they might be ignored in web representation of email.
 *
 * @global string $subject The email subject text
 * @global string $content The email content text (if set)
 * @global string $email_name The type of current email
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

global $tr_options;
$tr_fonts = "font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;";
appthemes_add_template_var( array(
	'subject'  => $subject,
	'content'  => $content,
	'tr_fonts' => $tr_fonts,
	'tr_options' => $tr_options,
) );
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<title><?php bloginfo( 'name' ); ?></title>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<?php if( ! isset( $email_name ) ) {
	$email_name = '';
} ?>

<body class="email-body email-<?php echo esc_attr( $email_name ); ?>" bgcolor="#FFFFFF" style="<?php echo $tr_fonts; ?> -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; margin: 0; padding: 0;<?php if ( is_rtl() ) echo ' direction:rtl;'; ?>">
	<style type="text/css">
		@media only screen and (max-width: 600px) {
			a[class="btn"] {display: block !important; margin-bottom: 10px !important; background-image: none !important; margin-right: 0 !important;}
			div[class="column"] {width: auto !important; max-width: none !important; float: none !important;}
			table.social div[class="column"] {width: auto !important;}
		}
	</style>

	<!-- HEADER -->
	<table class="head-wrap" bgcolor="#378AD8" style="width: 100%; margin: 0; padding: 0;">
		<tr style="margin: 0; padding: 0;">
			<td class="header container" style="<?php echo $tr_fonts; ?> display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">
				<?php get_template_part( 'email-header', $email_name ); ?>
			</td>
		</tr>
	</table>
	<!-- /HEADER -->

	<!-- BODY -->
	<table class="body-wrap" style="width: 100%; margin: 0; padding: 0;">
		<tr style="margin: 0; padding: 0;">
			<td class="container" bgcolor="#FFFFFF" style="<?php echo $tr_fonts; ?> display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">
				<?php get_template_part( 'email-content', $email_name ); ?>
			</td>
		</tr>
	</table>
	<!-- /BODY -->

	<!-- FOOTER -->
	<table class="footer-wrap" style="width: 100%; clear: both !important; margin: 0; padding: 0;">
		<tr style="margin: 0; padding: 0;">
			<td class="container" style="<?php echo $tr_fonts; ?> display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">
				<?php get_template_part( 'email-footer', $email_name ); ?>
			</td>
		</tr>
	</table>
	<!-- /FOOTER -->

</body>
</html>
