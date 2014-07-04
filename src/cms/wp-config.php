<?php

/*
 * VARS configured for each environment
 */
if(stristr( $_SERVER['SERVER_NAME'], "localhost")) {
	// DB
	define('DB_NAME', '*************');
	define('DB_USER', '*************');
	define('DB_PASSWORD', '*************');
	define('DB_HOST', '*************');

	// WP SITE - set this to be the wp base url
	define('WP_HOME','http://localhost/proj-proj/deploy/local/wp');
	define('WP_SITEURL','http://localhost/proj-proj/deploy/local/wp');
	
	// AMAZON AWS settings
	// need to have amazon-web-services and amazon-s3-and-cloudfront plugin
	// http://wordpress.org/plugins/amazon-s3-and-cloudfront/
	define('AWS_ACCESS_KEY_ID', '*************');
	define('AWS_SECRET_ACCESS_KEY', '*************');

	// S3 bucket
	define('PREVIEW_SITEURL', 'http://localhost/visi-cons/log/local/www/');
	define('MAIN_SITEURL', 'http://cons.dev.thesecretlocation.net/');

	// SCRIPTS
	define('DOCROOT', __DIR__.'/../../../');
	define('PREVIEW_SCRIPT', 'sh '.DOCROOT.'bin/preview.local.sh');
	define('PUBLISH_SCRIPT', 'sh '.DOCROOT.'bin/publish.local.sh');

} elseif(stristr( $_SERVER['SERVER_NAME'], "dev")) {
	
} elseif(stristr( $_SERVER['SERVER_NAME'], "stage")) {
	
} else {
	
}

/*
 * Common VARS
 */
define('WP_DEBUG', false);

// DB
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// SCRIPTS
define('LOCKFILE', DOCROOT.'log/lockfile');
define('LOG_FILE', DOCROOT.'log/build.log');
define('JSON_DATAPATH', DOCROOT.'log/data_cms/');

// PAGES
define('PAGE_INDEX', 'index.html');
define('PAGE_ABOUT', 'about.html');
define('PAGE_SPONSOR', 'sponsor.html');
define('PAGE_MEDIA', 'mediaArchivesTV.html');
define('PAGE_PRIVACY', 'privacy.html');

// Upload path
define('UPLOADS','../../../../log/uploads/');
if (!file_exists(UPLOADS)) {
	@mkdir(UPLOADS);
}

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

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
