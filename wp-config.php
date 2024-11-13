<?php
define( 'WP_CACHE', false ); // By Speed Optimizer by SiteGround

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dbhplkyetqqf0v' );

/** Database username */
define( 'DB_USER', 'uaps3ggxwtbuj' );

/** Database password */
define( 'DB_PASSWORD', 'os2gfhk28tlj' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'QJ8}/r?`gZA]0-n@B */4)gNt+PDP-!z|}80I|)sk/yB)o(31XDm%vDTL$xz15I7' );
define( 'SECURE_AUTH_KEY',   '}7Dy.g;fL0]*nL,Y7@bGrhukR:|I/5hWTs`Tqp<P&}Rt_[&WV^Ys44~]9q<S?(rH' );
define( 'LOGGED_IN_KEY',     '$ioxkXF$CAE)vo;Kqh}CQ;%$G!!2FP6;a}7-2%[{jaqzF8(>6a#EgAApeBwUK;sC' );
define( 'NONCE_KEY',         '*F?d`x2b%]>BhD]eVSqv*`|fU4V_5348]`GAs>EMxOeks?3xPgtHs<Mjzw7l(4Tb' );
define( 'AUTH_SALT',         'H48uj},9NWl=;su]I<$*HL6l5>p8m~Uw*MnPJ8CQ!/Ev-LS?$}cIy1p^HMO@!&]d' );
define( 'SECURE_AUTH_SALT',  '8Yol+Fxuu)b<~-F4Gp/ 9Y{gAT;As*x80^Ia)jC.{(tR/vnO>aM`JaW5R!Y[<Jn@' );
define( 'LOGGED_IN_SALT',    'F_M48kNSwgN:rxMr,Q+m2Y%|leMwl )}/3gNMroA{klN2~G@f2|B3xZ^K F:mOEA' );
define( 'NONCE_SALT',        '70XpE/@w% n7~Qcr:9-8d`:J) ,;8Q~&{}xlb<P)m+p`If<kVX.-cX?BC.2!iZ-^' );
define( 'WP_CACHE_KEY_SALT', '|C51hQjENYWXL=Y6,&I8}&^.pO6i+pAv4T1%NoGRg+3n|l$FNFYBduLn85Yn6C^^' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'cbr_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
	//define( 'WP_DEBUG_LOG', true );
	//define( 'WP_DEBUG_DISPLAY', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
