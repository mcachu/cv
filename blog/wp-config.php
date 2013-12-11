<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'soportebcbd');

/** MySQL database username */
define('DB_USER', 'soportebcbd');

/** MySQL database password */
define('DB_PASSWORD', 'T@males4me');

/** MySQL hostname */
define('DB_HOST', 'soportebcbd.db.7695481.hostedresource.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '?~+ex()5l[|LAQ$4=|!!>:#c1Q<en2[1[O4E)]@;Y+N@.6XVIVZF;-Wp}a&SmkPe');
define('SECURE_AUTH_KEY',  '&Yb_9~G/(7)$80ijXJ#uPTvM5.at@f*MT^UTtA.L;gW{+FD:-bIoFLF|%b54/8&S');
define('LOGGED_IN_KEY',    'Nm56v|=;Y?h_8L;Im&Zi|VrvoUYys:= Ze8*yAdKG?pkiOvXC,qk)F9av#ON6jn5');
define('NONCE_KEY',        '$MB$0YlIu*yG[c8(5+N=g7W*&WiOv[9FF.+wOK Q{zKQGT2pW!0-0l($c&Djn$B6');
define('AUTH_SALT',        '(kUz>hW|vIg4[C/IQ|@h|~DnX[K_YK;j:3xH3vAVyOJ^jV?BxVM3jeAjBoo/0sru');
define('SECURE_AUTH_SALT', 'Q,n#xze! Qh2;OAaFbh+.c@ao&-|J3iWN:|E{(~4/9)e*gdHnQ^-{@7vGKo4|E1,');
define('LOGGED_IN_SALT',   '<0m(mJ]-:-IV|.Nh^QzpsqkYJ{Z|b/T|xbS3@rsbp=7[|n1BfPjS3k-+l/xK#mCK');
define('NONCE_SALT',       'EZ~VRv+^-1V/ac+s?]#o*X|KUT<^42G!4^_IQ:-@24EDzxh]pO,J`x]CuUPdH5kH');





/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
