<?php
/**
 * Social meta functions
 *
 * @package Taskerr\Social
 * @author  AppThemes
 * @since   Taskerr 1.0
 */

/**
 * Returns true if the site owner has entered the specified social network.

 * If no social network given, returns if any social networks have been entered.
 * @param string $social_network Optional.
 * @return bool
 */
function tr_has_social( $social_network = '', $user_id = false ){

	if( empty( $social_network ) && ! $user_id ){
		$settings = _tr_get_social_settings();
		foreach( $settings as $network => $value ){
			if( !empty( $value ) )
				return true;
		}
		return false;
	}

	$account = tr_get_social( $social_network, $user_id );
	if( ! $account )
		return false;
	else
		return true;

}

/**
 * Returns the username entered for the given social network
 * @param string $social_network
 * @return mixed Returns username or false
 */
function tr_get_social(	$social_network, $user_id = false ){
	if ( ! $user_id ) {
		$account = _tr_get_social_settings( $social_network );
	} else {
		$account = _tr_get_social_user( $social_network, $user_id );
	}
	if( empty( $account ) )
		return false;
	else
		return $account;

}

/**
 * Returns the user account URL for the given social network
 * @param string $social_network
 * @return mixed Returns URL or false
 */
function tr_get_social_url( $social_network, $user_id = false ){
	$account = tr_get_social( $social_network, $user_id );
	if( !$account )
		return false;

	return APP_Social_Networks::get_url( $social_network, $account );
}

/**
 * Returns the title for the given social network
 * @param string $social_network
 * @return string social network name
 */
function tr_get_social_title( $social_network ){
	return APP_Social_Networks::get_title( $social_network );
}

/**
 * Returns settings object for social API use
 * @internal
 * @param string $social_network Limits the return to the value of the social network field
 * @returns scbOject
 */
function _tr_get_social_settings( $social_network = '' ){

	global $tr_options;
	if( empty( $social_network ) )
		return $tr_options->social;
	else if( isset( $tr_options->social[ $social_network] ) )
		return $tr_options->social[ $social_network ];
	else
		return false;

}

function _tr_get_social_user( $social_network, $user_id ) {

	if ( 'url' === $social_network ) {
		return get_userdata( $user_id )->user_url;
	}

	return get_user_meta( $user_id, $social_network, true );
}

function _tr_allowed_networks() {
	return array(
		'facebook',
		'twitter',
		'pinterest',
		'googleplus',
		'tumblr',
		'instagram',
	);
}

function tr_get_top_bar_social() {

	$allowed_networks = apply_filters( 'tr_top_bar_social', _tr_allowed_networks() );

	$networks = array();
	foreach( $allowed_networks as $network ){
		if( tr_has_social( $network ) ) {
			$networks[] = $network;
		}
	}
	return $networks;
}

function _tr_allowed_networks_user() {
	return apply_filters( 'tr_user_social', _tr_allowed_networks() );
}

function tr_get_user_social( $user_id = false ) {

	$allowed_networks = _tr_allowed_networks_user();

	$networks = array();
	foreach( $allowed_networks as $network ){
		if( tr_has_social( $network, $user_id ) ) {
			$networks[] = $network;
		}
	}

	return $networks;
}

function tr_social_networks( $networks ) {
	return array_diff( $networks, array( 'google-plus' ) );
}
add_filter( 'appthemes_social_networks', 'tr_social_networks' );

APP_Social_Networks::register_network( 'googleplus', array(
	'title' => __( 'Google+', APP_TD ),
	'base_url' => 'http://plus.google.com/',
	'user_url' => 'http://plus.google.com/%s/',
	'tip' => sprintf(
		__( 'Enter your Google+ ID here. The URL will look like this: %s where the number is your ID.', APP_TD ),
		'http://plus.google.com/108097040296611426034/'
	),
) );

APP_Social_Networks::register_network( 'tumblr', array(
	'title' => __( 'Tumblr', APP_TD ),
	'user_url' => 'https://%s.tumblr.com/'
) );