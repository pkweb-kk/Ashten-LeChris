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
define('DB_NAME', 'aslechrisdb');

/** MySQL database username */
define('DB_USER', 'aslechrisusr');

/** MySQL database password */
define('DB_PASSWORD', 'TK4B/BMU#RTp4!Q>');

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
define('AUTH_KEY',         '.{qurAkQAvqq`bf:1<bKwJt)iT@z9M8>rrxDa%^YUM|HxocCpjVsf>d,hB.MRr5j');
define('SECURE_AUTH_KEY',  '[P3|>-y:s);Q:c!%S]]R$&iQ@oi/Fk^g<o>M=:I1q^P<p6rD(0LLchR;|dE2>Q*5');
define('LOGGED_IN_KEY',    '0Jy`Ag,:zV5o`u6*/$K1[guttx]SFoS^v]RJl+8|_Az yLOqBm.;(7+zp:tTXx-V');
define('NONCE_KEY',        'n?d[p+<<%q!_^IJKx^UM49 _af*~sj{tbUBx;E, so=[wl,TwS2fCo,JuN?#wI@l');
define('AUTH_SALT',        'jQ:rk>p;dBcXl;#e}3O9JZZoeU1GQXg>NuTsNV[Q{EkHO=xHXOWrcc+ViCh*^x:3');
define('SECURE_AUTH_SALT', ']+P=i)=$0$=`ex7a/ 9(t7=l~SZNIpW/6`YG9iW[6y)[Cl)yM*5NgrJ>e qh~pB1');
define('LOGGED_IN_SALT',   'UbPm?BLCq}&*F.pm~fRl>>O@f{y|6VO~#>&{k=s/avAx(PY?<SbRPqGD}+0q^3)|');
define('NONCE_SALT',       ':H48NJ{w.vYUkY3RF|68PlOk}qqT JNit$nqk R fr3LA$I!w;`)I,&kj$??=9n0');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'asl_';

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
