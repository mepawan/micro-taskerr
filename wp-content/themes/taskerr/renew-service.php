<?php
/**
 * Add Renew Service steps wrapper
 *
 * To customize step wrapper paste here contents of 'step.php' file or 'step-full.php'
 * for Pricing form.
 * Then if you need customize specific step content you have edit actual step file
 *
 * @see step.php
 * @see purchase-service-new.php
 * @see form-service.php
 * @see order-select.php
 * @see order-checkout.php
 * @see order-summary.php
 *
 * @package Taskerr\Templates
 * @author  AppThemes
 * @since   Taskerr 1.1
 */

if ( isset( $full_width ) ) {
	include 'step-full.php';
} else {
	include 'step.php';
}
