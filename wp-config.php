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
define( 'DB_NAME', 'victory-test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'HqR`UbO7T5gJuG@}bhRPK!EM>cD^JOLbe_f,+U%BL>^>EKP>aWlDi*p)AOzPb9hC' );
define( 'SECURE_AUTH_KEY',  'h<H!QpJ7dU^T^$[a<<RwO4Jh+*kG9fU#7@yDeYu5[dnIl&-xh`=+K}?<5l>Z^9;*' );
define( 'LOGGED_IN_KEY',    'Y7Bs4ODUQ)(IVnE<C:QA(Isav8-Ekij7[dOTnN8x=BhuqU,=xWDObBB4FGZc1UIL' );
define( 'NONCE_KEY',        'q{9=Eo<t^htuE}igG2K[:$Dh7gdcJ6DtIN.KcFy.AIqU}~er?*yBUC:OjL*>3f|J' );
define( 'AUTH_SALT',        '!F#l)-zj(OB@PCsvS/!ej*yT2dq)$fxz)/xg;z*s<sBZL#DV~DflwzAQpL>ta%~c' );
define( 'SECURE_AUTH_SALT', '/>Kvyf^YAKt1eQaL~*(Dkt<+W,u|p}Rrd9=xV,Aa+6T%G4^mjEqMzA>F.tk4 3]:' );
define( 'LOGGED_IN_SALT',   'Itn2VO9+ oB{G38-nChL8^wi&a3<{<-x5O#:[,ezr%i#FtY_v>_)1Z^}o/O)]V+i' );
define( 'NONCE_SALT',       '|U1}3q1+*)B5|1bGD=@]Y@.{_,S5efr(7zakX#Y}F8<7K=el_;`&<$`F,Gx0sghX' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';