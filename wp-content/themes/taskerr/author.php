<?php
/**
 * Generic Author template
 *
 *   $curauth (object)           - Current author object
 *   $is_own_dashboard (boolean) - Is current user is author or not
 *   $tr_sections (array)    - The page tabs
 *
 * See variables definitions in TR_Author() class
 *
 * Example one item of page tabs array:
 * $tr_sections = array(
 *      'services-current' => array(                    // Tab CSS class (as array key)
 *          'args' => array(                            // Loop query arguments
 *              'post_type' => TR_SERVICE_PTYPE,
 *              'author' => $curauth->ID,
 *              'paged' => get_query_var( 'paged' ),
 *          ),
 *          'name' => __( 'Current Services', APP_TD ), // Tab title
 *          'loop' => TR_SERVICE_PTYPE,                 // Loop template part name
 *
 *          // Optional items used for custom comments loops:
 *          'max_num_pages' => (int),                   // calculated total number of comments pages
 *          'comments_query' => array(),                // WP_Comments_Query or another comments collection
 *          'total_entries' => (int),                   // Total number of comments in collection
 *      ),                                              // and so on...
 * );
 *
 * Use 'tr_author_tabs' filter to change or add new tabs
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
<div class="archive-row row">

	<?php do_action( 'appthemes_notices' ); ?>

	<main id="main" class="large-9 medium-8 columns" role="main">

		<section id="author_page">

			<header class="large-12 columns user-content white-con">

				<div class="row">

					<div class="large-3 columns user-avatar">
						<?php echo get_avatar( $curauth->ID, 150 ); ?>
					</div>
					<div class="large-9 columns user-info">
						<div class="user-name">
							<h1><?php echo $curauth->display_name; ?></h1>
						</div>

						<div class="user-description">
							<?php echo apply_filters( 'the_content', get_the_author_meta( 'description' ) ); ?>
						</div>

						<div class="user-social-links">
							<ul class="inline-list">
							<?php if( tr_has_social( 'facebook', $curauth->ID ) ) { ?>
								<li>
									<a href="<?php echo esc_url( tr_get_social_url( 'facebook', $curauth->ID ) ); ?>" title="<?php _e( 'Facebook', APP_TD ); ?>">
										<i class="genericon genericon-facebook"></i>
										<span><?php echo tr_get_social( 'facebook', $curauth->ID ); ?></span>
									</a>
								</li>
							<?php } ?>
							<?php if( tr_has_social( 'twitter', $curauth->ID ) ) { ?>
								<li>
									<a href="<?php echo esc_url( tr_get_social_url( 'twitter', $curauth->ID ) ); ?>" title="<?php _e( 'Twitter', APP_TD ); ?>">
										<i class="genericon genericon-twitter"></i>
										<span>@<?php echo tr_get_social( 'twitter', $curauth->ID ); ?></span>
									</a>
								</li>
							<?php } ?>
							<?php if( tr_has_social( 'url', $curauth->ID ) ) { ?>
								<li>
									<a href="<?php echo esc_url( tr_get_social( 'url', $curauth->ID ), 'mailto' ); ?>" title="<?php _e( 'Website', APP_TD ); ?>">
										<i class="genericon genericon-planet"></i>
										<span><?php echo preg_replace( '#^http(s)?://#', '', tr_get_social( 'url', $curauth->ID ) ); ?></span>
									</a>
								</li>
							<?php } ?>
							<?php foreach( apply_filters( 'tr_author_social_info', array() ) as $network ){ ?>
								<li>
									<a href="<?php echo esc_url( tr_get_social_url( $network, $curauth->ID ) ); ?>" title="<?php echo tr_get_social_title( $network ); ?>">
										<i class="genericon genericon-<?php echo esc_attr( $network ); ?>"></i>
										<span><?php echo tr_get_social( $network, $curauth->ID ); ?></span>
									</a>
								</li>
							<?php } ?>
							</ul>
						</div>

						<div class="user-header-meta row">
							<div class="meta-rating large-4 columns">
								<strong><?php tr_author_rating( $curauth->ID ); ?></strong><span class="label-meta"><?php _e( 'Rating', APP_TD );?></span>
							</div>
							<div class="meta-current large-4 columns">
								<strong><?php tr_get_user_reviews_count( $curauth->ID ); ?></strong><span class="label-meta"><?php _e( 'Reviews received', APP_TD );?></span>
							</div>
							<div class="meta-completed large-4 columns">
								<strong><?php tr_provider_tasks_completed( $curauth->ID ); ?></strong><span class="label-meta"><?php _e( 'Tasks Completed', APP_TD );?></span>
							</div>
						</div><!-- end row -->

					</div>
				</div><!-- end row -->
			</header>

			<div class="large-12 columns user-posts">
				<div class="row">
					<?php if ( count( tr_sections() ) > 1 ) { ?>
						<dl class="tabs" data-tab data-options="deep_linking:true">
							<?php foreach ( tr_sections() as $section_name => $section_args ) { tr_the_section( $section_args ); ?>
								<dd>
									<a href="#panel-<?php echo esc_attr( $section_name ); ?>"><?php tr_author_section_name( $section_args ) ?></a>
								</dd>
								<?php tr_reset_section_query( $section_args ); ?>
							<?php } ?>
						</dl>
					<?php } ?>
					<div class="tabs-content">
						<?php foreach ( tr_sections() as $section_name => $section_args ) { tr_the_section( $section_args ); ?>
							<section class="content <?php echo esc_attr( $section_name ); ?>" id="panel-<?php echo esc_attr( $section_name ); ?>">
								<?php get_template_part( 'loop', $section_args['loop'] ); ?>
								<?php get_template_part( 'loop-footer', $section_args['loop'] ); ?>
							</section>
							<?php tr_reset_section_query( $section_args ); ?>
						<?php } ?>
					</div>
				</div>
			</div><!-- end .user-posts -->

		</section>

	</main><!-- end main -->

	<div id="sidebar" class="large-3 medium-4 columns" role="complementary">

		<?php get_sidebar( app_template_base() ); ?>

	</div><!-- end #sidebar -->

</div><!-- end row -->