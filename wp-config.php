<?php
define( 'WP_CACHE', true );

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
define( 'DB_NAME', 'wordpress_db2' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost:3307' );

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
define( 'AUTH_KEY',         '7j?V8?=;gFl1vnQ2j[%l^w14;PgJpVQAWo^0D>o4Y;D:KB0=N=s8D+wp!QS[,* n' );
define( 'SECURE_AUTH_KEY',  'z.+nCOCkz8,KXY5_yem5_[C1&bczDSeu/Mw[jaY6:3kCJ71n)Lk6-@poc+id.Hh ' );
define( 'LOGGED_IN_KEY',    '&W@[qF@EEVzEki5%dk{ocSlMu5BTw:b]c9VD]7*e xn/<i!::RMLf 8K Yn8e~:e' );
define( 'NONCE_KEY',        'k56ilXt@V&D3o;2W#p&#H5v^OQLV.NpE&NX0cU%z`QO<%zi9vO2Y3Wva/=AT}PZB' );
define( 'AUTH_SALT',        'r0>7V8PYz$4}8uD)Apvy?HF|$I/}AZ6+U4l^#n)V+swxb$p3mW!)u~1>okLFoRRq' );
define( 'SECURE_AUTH_SALT', 'tzo_;38T$6SOl!|tmQTAN6?fScI$3)x/q0 NuW%&X (Yrux~ihjwpOLQGwhe/Q?g' );
define( 'LOGGED_IN_SALT',   'r}NUUV/K6}XlXo4ni{zwVxWdT=a(Y*rW8.6nnnrLz$:jR3/q;]_7]c[!1fm4:45(' );
define( 'NONCE_SALT',       'w.+fv5]qjCzVHB/4MV>L!p!nUvZLFKoIm7e~TG@n%7~8@Vdgq${_^Gx9tfO_6-./' );

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
