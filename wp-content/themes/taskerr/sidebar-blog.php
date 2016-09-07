<?php
/**
 * Generic Blog Sidebar template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

appthemes_before_sidebar_widgets( 'tr-blog' );
dynamic_sidebar( 'tr-blog' );
appthemes_after_sidebar_widgets( 'tr-blog' );