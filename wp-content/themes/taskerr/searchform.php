<?php
/**
 * Search form template part.
 *
 * Use function get_search_form() to display this part.
 * Use Query args to change anything in Search query.
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */
?>
		<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<div class="right">
				<button value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" type="submit" title="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>">
					<i class="genericon genericon-search"></i>
					<span><?php _ex( 'Search', 'submit button' ); ?></span>
				</button>
			</div>
			<div class="search-input">
				<input type="text" id="s" name="s" placeholder="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" value="<?php the_search_query(); ?>" />
			</div>
		</form>