<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'now420' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '~l/c5cu[yZCHPXMEE@X)x#YO=E!rS#Gdw+_:[<yU7P]#=~J%M)df|!}Iy.@`f*Mv' );
define( 'SECURE_AUTH_KEY',  'XSk]kRH9>kbmVywV{^OzKq8P}XVx(VCiC4MeZ|Q}Y4d&<7vxC)a~s|=!`0q(QD0@' );
define( 'LOGGED_IN_KEY',    'mO]Cn%#Xl7IO(8bIs?,%t4R9|Z)uk}h5ni[[bOtvVsM6/@;(_Y>lfr2*A:{6}FzX' );
define( 'NONCE_KEY',        '{FC{S->Ax%<w&s_P_s_|xKD_LdY5/u7Y(N=br1>JMeXXw/#BR/]`Iu7&L=FLl?4e' );
define( 'AUTH_SALT',        '8spp~AmCNh|@x.n4U_89yeZ={geTJ>2atp$/i(,e~FU=NR2.[KN-,2(JU-28ty~r' );
define( 'SECURE_AUTH_SALT', '$IM&B/3x^]VAV,VpzuY,Nb Y[H>mf;BDaZt<<w/Vbx)Y!eDB|U_[I6PqjAVr4K/>' );
define( 'LOGGED_IN_SALT',   'Vg8R|4X2ewhZ,9I4GoUfG@L*slw@gh=IpJJ! fu:rH3U3gr]X@{4||}/]>l8Td[.' );
define( 'NONCE_SALT',       '-Z)8/D8K0AZ35V7bvU!3#sPu^_Cy<mf 8t6@LBBk&^)}Ps`u9;[xpEjkeB$Suk)<' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
