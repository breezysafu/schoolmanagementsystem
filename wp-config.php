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
define('DB_NAME', 'schoolmanagementsystem');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'rusakhatun');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'X{l)4yLg#.>Kp6o7{~J?E2ppepE{JeSon7ceI-bn<9{bL=;>9v7Rj%Mf!N@j2h#E');
define('SECURE_AUTH_KEY',  '-`9uv1#^u:ntW]I$|&ftk>9iA=,-3Y ?W~|yL.]*_8t_tS;~ vu${07cThy.bKN|');
define('LOGGED_IN_KEY',    'mF%gZz8Y9K|^H&DPxKL@h0]Dx|nr0bo!}:r?jydDf,UpECPbhiQNg<D5,64Amq*q');
define('NONCE_KEY',        'XO`A:H9]^ps.&Go#PHOI<Qf$t27A:d_QD+l K3q5lCzhmm=l9yd0_x_*aH^Q@D 7');
define('AUTH_SALT',        '-@2kvXA^6],[=r3[c5#Dk1^!On1hF[<^yHQ|h*bS8J+Kq|Whi8d:v2YN5U}D>8@}');
define('SECURE_AUTH_SALT', 'GOTjS/btGY.#;tv66f-6YZg?tv~0sOx$:*kTd?;6px(rBoqcg)xvOi^oPrHRkxr_');
define('LOGGED_IN_SALT',   'R>3mv#.lWlR0JW{&@~`hvmHC&)O:?_X`y y_Q.-@gEnQV_k2%c=`i35&C)~&Fy|z');
define('NONCE_SALT',       'eJS=fd<ci[U9@)Fx2-[Y]DNEvsq+T^S{,3?(7bXZRf)ua(zjS:wk|8uvd$`hx (!');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
