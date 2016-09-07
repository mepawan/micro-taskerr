<?php
/**
 * Generic Page Sidebar template
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

appthemes_before_sidebar_widgets( 'tr-page' );
dynamic_sidebar( 'tr-page' );
appthemes_after_sidebar_widgets( 'tr-page' );