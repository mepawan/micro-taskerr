<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'micro');

/** MySQL database username */
define('DB_USER', 'adminCXe5VVZ');

/** MySQL database password */
define('DB_PASSWORD', 'zqsrRJT2j8Iw');

/** MySQL hostname */
define('DB_HOST', '127.7.74.2');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'QiZ%`.#HT:x29nX*rOBUI(DUf!NygI0*_^I3Sa/e7*AK/(l,:}U|y)o@}EP, |rE');
define('SECURE_AUTH_KEY',  '%RcE/@J,WjX2=^ZGB-i@=(P#@mFco9HSRr( >-[p?+:b7nN~zw)`RUcDjf_lR}`<');
define('LOGGED_IN_KEY',    'i^MknwL9rB,kQ6k!mn2@K<GS>v!zXAzbRpt J.aBRC:fu>Bphq|#r$1c5/Lg]&v,');
define('NONCE_KEY',        'sjHTZCfWvi!78SD;v&=kY,al1TbTuIISVGz6FJpae{)|gxNJ& 6n;2J}8eN(QGi2');
define('AUTH_SALT',        ' q#&s;V?y|Ly<FZ}*n&s~Z*J4b&r1R2<VC)@g-uAow8!Rvif^8{gh=G~A1vB5=^3');
define('SECURE_AUTH_SALT', 'Q%~--bGT0sy]6t*:y8x}i3X == 9V,$Tsx}:Z://N8{VpAB}i-lmP|^lgd[`SMPn');
define('LOGGED_IN_SALT',   '|8=Y@:z@_Gh:^LJZ^:<8PUO]5Pe_Y$kBm1N;eTSmufJk!A(LIWqZW:Gj>0DK-4cK');
define('NONCE_SALT',       'C4,_$yyi+<,A-#Q1]9R0|x6fy(T{;jS;Br^c{9f,!+39z>HwazD,WDpyWs;2}CpO');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpmt_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
