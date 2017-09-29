<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

 define("COOKIE_DOMAIN", "www.mirrormuscles.com");
 
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mirrorm0_base');

/** MySQL database username */
define('DB_USER', 'mirrorm0_base');

/** MySQL database password */
define('DB_PASSWORD', 'jSQYVAF7KXJagxPT');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

//define('FORCE_SSL_ADMIN', true);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '%A32mI0^9-%LxOQ+iRMX[:n/oh(}|OA<m}1^7Ki^z^o#%Q ]cx=tv.)!<>1{tmlj');
define('SECURE_AUTH_KEY',  '7l+8YX)XCh).^=<~`i]9r%b#9D;@ZYxO1L6VsdI^Q*hp3l/cJ.Ur>UoWDK</|m v');
define('LOGGED_IN_KEY',    '/e+4+q~3GAi++b[Rgc2}.qxQj9+/|ubNAu7^5aV|m9YARvUiq~AmW<hPf^Mm!j/R');
define('NONCE_KEY',        'PtwM#LqV#$IpA1%~Hi[(ktZ/ZXOoyJ76C2m|@a5B+#2]!dGY(+m@~2cZ:[>#+P7`');
define('AUTH_SALT',        'hUOC(F#8wVhIKE[5)B4ff-dr=6urANY]5j/hiyGNmOwa39#QFeN US.ms[,z@-|Y');
define('SECURE_AUTH_SALT', 'GJk`TL^,n,&QSMba-.1 d9{woPj,G->tJA0+1wbc`J}-#%+E/^z)`mH+;IpJ^q7S');
define('LOGGED_IN_SALT',   'oJu8f=tf3k(O==}ce_A!Jp|</rL$GyIQ)]@lJG]iG3.h;e*k[+B;jyE-s}(pD[/B');
define('NONCE_SALT',       'XhL&lDz95CK1,7u8#OZ%V$5ir:hb:b?-6d#`Yd9f|86L&||QK3-J0w!s8*299Sxb');
define('CONCATENATE_SCRIPTS', false);
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG',0);


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
