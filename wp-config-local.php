<?php
/**
 * The base configuration for WordPress
 *
 */

// ** Database settings - You can get this info from your web host ** //
if ((!empty($_SERVER['HTTPS_HOST']) && $_SERVER['HTTPS_HOST'] == "cnp2393.developer24x7.com") || empty($_SERVER['HTTPS_HOST'])) {
	/** The name of the database for WordPress */
	define('DB_NAME', 'cnp2393');

	/** MySQL database username */
	define('DB_USER', 'barunbhaumik');

	/** MySQL database password */
	define('DB_PASSWORD', 'WW678i$@12py');

	/** MySQL hostname */
	define('DB_HOST', 'cndb4mysql-dev.cnifsz8x7lk0.us-east-1.rds.amazonaws.com');	

	define('WP_HOME', 'https://cnp2393.developer24x7.com/');
	define('WP_SITEURL', 'https://cnp2393.developer24x7.com/');

} else {
	/** The name of the database for WordPress */
	define( 'DB_NAME', 'cnp2393' );

	/** Database username */
	define( 'DB_USER', 'root' );

	/** Database password */
	define( 'DB_PASSWORD', '123' );

	/** Database hostname */
	define( 'DB_HOST', 'localhost' );

	define('FS_METHOD', 'direct');

	define('WP_HOME', 'http://localhost/cnp2393/');
	define('WP_SITEURL', 'http://localhost/cnp2393//');
}

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );


define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true );
@ini_set( 'display_errors', 0 );