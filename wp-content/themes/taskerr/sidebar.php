<?php
/**
 * Generic Sidebar template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

appthemes_before_sidebar_widgets( 'tr-main' );
dynamic_sidebar( 'tr-main' );
appthemes_after_sidebar_widgets( 'tr-main' );