<?php
/**
 * Template Name: Register
 *
 * To customize form wrapper paste here contents of 'form.php' file.
 * Then if you need customize specific form contents you have to insert actual
 * form file contents inside wrapper.
 * For that you have to paste form content between `ACTUAL FORM CONTENT` tags
 * istead of `switch` statement.
 *
 * @see form.php
 * @see framework/templates/form-registration.php
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>

<?php global $wp_version; ?>

<section>
	<header>
		<div class="singular-headline">
			<?php appthemes_before_page_title(); ?>
			<?php get_template_part( 'headliner', app_template_base() ) ?>
			<?php appthemes_after_page_title(); ?>
		</div>
	</header>

	<div class="singular-row row">

		<?php do_action( 'appthemes_notices' ); ?>

		<main id="main" class="large-9 medium-8 columns" role="main">

			<?php appthemes_before_page_loop(); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php appthemes_before_page(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div id="overview" class="single-content white-con">
						<div class="post-info">
							<?php appthemes_before_page_content(); ?>

							<?php the_content(); ?>

							<form action="<?php echo appthemes_get_registration_url( 'login_post' ); ?>" method="post" class="login-form register-form" name="registerform" id="login-form">

								<p><?php _e( 'Complete the fields below to register.', APP_TD ); ?></p>

								<fieldset>
									<div class="form-field">
										<label>
											<?php _e( 'Username:', APP_TD ); ?>
											<input tabindex="1" type="text" class="text required" name="user_login" id="user_login" value="<?php if ( isset( $_POST['user_login'] ) ) echo esc_attr( stripslashes( $_POST['user_login'] ) ); ?>" />
										</label>
									</div>

									<div class="form-field">
										<label>
											<?php _e( 'Email:', APP_TD ); ?>
											<input tabindex="2" type="text" class="text required email" name="user_email" id="user_email" value="<?php if ( isset( $_POST['user_email'] ) ) echo esc_attr( stripslashes( $_POST['user_email'] ) ); ?>" />
										</label>
									</div>

									<?php if ( $wp_version < 4.3 ) : ?>

										<div class="form-field">
											<label>
												<?php _e( 'Password:', APP_TD ); ?>
												<input tabindex="3" type="password" class="text required" name="pass1" id="pass1" value="" autocomplete="off" />
											</label>
										</div>

										<div class="form-field">
											<label>
												<?php _e( 'Password Again:', APP_TD ); ?>
												<input tabindex="4" type="password" class="text required" name="pass2" id="pass2" value="" autocomplete="off" />
											</label>
										</div>

									<?php else: ?>

										<div class="user-pass1-wrap manage-password">
											<div class="form-field">
												<label for="pass1"><?php _e( 'Password:', APP_TD ); ?></label>

												<?php $initial_password = isset( $_POST['pass1'] ) ? stripslashes( $_POST['pass1'] ) : wp_generate_password( 18 ); ?>

												<input tabindex="3" type="password" id="pass1" name="pass1" class="text required" autocomplete="off" data-reveal="1" data-pw="<?php echo esc_attr( $initial_password ); ?>" aria-describedby="pass-strength-result" />
												<input type="text" style="display:none" name="pass2" id="pass2" autocomplete="off" />
											</div>

											<div class="form-field">
												<button type="button" class="button secondary wp-hide-pw hide-if-no-js" data-start-masked="<?php echo (int) isset( $_POST['pass1'] ); ?>" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password', APP_TD ); ?>">
													<span class="dashicons dashicons-hidden"></span>
													<span class="text"><?php _e( 'Hide', APP_TD ); ?></span>
												</button>
											</div>
										</div>

									<?php endif; ?>

									<div class="form-field">
										<div id="pass-strength-result" class="hide-if-no-js"><?php _e( 'Strength indicator', APP_TD ); ?></div>
										<p class="description indicator-hint"><?php _e( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).', APP_TD ); ?></p>
									</div>

									<?php do_action( 'register_form' ); ?>

									<div class="form-field">
										<?php echo APP_Login::redirect_field(); ?>
										<input tabindex="30" type="submit" class="btn reg" id="register" name="register" value="<?php _e( 'Register', APP_TD ); ?>" />
									</div>

								</fieldset>

								<!-- autofocus the field -->
								<script type="text/javascript">try{document.getElementById('user_login').focus();}catch(e){}</script>
							</form>

							<?php edit_post_link( __( 'Edit', APP_TD ), '<span class="edit-link">', '</span>' ); ?>

							<?php appthemes_after_page_content(); ?>
						</div>
					</div>

					<!-- ad space -->
					<?php get_template_part( 'advert-bottom', app_template_base() ); ?>

				</div>

				<?php appthemes_after_page(); ?>

			<?php endwhile; ?>

			<?php appthemes_after_page_loop(); ?>

		</main><!-- /#main -->

		<div id="sidebar" class="large-3 medium-4 columns" role="complementary">
			<?php get_sidebar( app_template_base() ); ?>
		</div><!-- end #sidebar -->

	</div><!-- end row -->
</section>
