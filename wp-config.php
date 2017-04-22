<?php
define('WP_HOME','http://thibodavid.com/');
define('WP_SITEURL','http://thibodavid.com/');
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
define('DB_NAME', 'thibodav_jqnp');

/** MySQL database username */
define('DB_USER', 'thibodav_jqnp');

/** MySQL database password */
define('DB_PASSWORD', 'l46qu5j6dskf4cqe');

/** MySQL hostname */
define('DB_HOST', '10.169.0.140');

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
define('AUTH_KEY',         'R83VDPHOoLD2dhDJjErMN6BEQYHqVfCK8HcuD8L7VtlLwP5iXx1xKYviiVgzM31U');
define('SECURE_AUTH_KEY',  'b61syeK27sh5zf1iTslidM369uvdmTguFW9LoX96DFEcTuGY8fw4A7ZCPjO9UanB');
define('LOGGED_IN_KEY',    '67WcW97elxTK1jUFx6KDm14E4oD3FJfs2bPEfanQkqEIIbVcRpPJjHy1y62n5KK0');
define('NONCE_KEY',        'JjodqEBmUEnDK6Aj5H97Q0qP1O4cY2bF8l8OgVuQpiHEMespq2ozHsJXShVWtvq0');
define('AUTH_SALT',        'vNZKMMFVZsYKtEIovQOAAXWPXyc43xFhuBMgV750MHvPzinc1Fe5kLTiEIhWPUqS');
define('SECURE_AUTH_SALT', 'YtPj9AqXq1MLF44CCWTSkAYL7Hek7Tpge8GEvac4kHtYnw3pBlOeQP21S44nIBwg');
define('LOGGED_IN_SALT',   '91mfxk79mRuFeHcao8B2anzWKBJsHXyKgFhMiB1EVT3WHqDuYpA1eANBYzlZf4fC');
define('NONCE_SALT',       'rcgph70dCkcdPH8Ly3nBr8BeaqA0kQsdAyYZK09c0HubPxYHSK13uSoApUWei9mK');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'zzkl_';

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
define('WP_MEMORY_LIMIT', '512M');
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');