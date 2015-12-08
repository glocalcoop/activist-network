<?php
/*
Plugin Name: Tagregator
Plugin URI:  http://wordpress.org/plugins/tagregator
Description: Aggregates hashtagged content from multiple social media sites into a single stream.
Version:     0.6
Author:      WordCamp.org
Author URI:  http://wordcamp.org
License:     GPLv2 or later
*/

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
	die( 'Access denied.' );

define( 'TGGR_NAME',                 'Tagregator' );
define( 'TGGR_REQUIRED_PHP_VERSION', '5.3' ); // because of get_called_class()
define( 'TGGR_REQUIRED_WP_VERSION',  '3.9' ); // because of 'masonry' v3 script handle

/**
 * Checks if the system requirements are met
 * @return bool True if system requirements are met, false if not
 */
function tggr_requirements_met() {
	global $wp_version;

	if ( version_compare( PHP_VERSION, TGGR_REQUIRED_PHP_VERSION, '<' ) ) {
		return false;
	}

	if ( version_compare( $wp_version, TGGR_REQUIRED_WP_VERSION, '<' ) ) {
		return false;
	}

	return true;
}

/**
 * Prints an error that the system requirements weren't met.
 */
function tggr_requirements_error() {
	global $wp_version;

	require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
}

/**
 * Loads all the files that make up Tagregator
 */
function tggr_include_files() {
	require_once( dirname( __FILE__ ) . '/classes/tggr-module.php' );
	require_once( dirname( __FILE__ ) . '/classes/tagregator.php' );
	require_once( dirname( __FILE__ ) . '/classes/tggr-settings.php' );
	require_once( dirname( __FILE__ ) . '/classes/tggr-shortcode-tagregator.php' );
	require_once( dirname( __FILE__ ) . '/classes/tggr-media-source.php' );
	require_once( dirname( __FILE__ ) . '/classes/tggr-source-twitter.php' );
	require_once( dirname( __FILE__ ) . '/classes/tggr-source-instagram.php' );
	require_once( dirname( __FILE__ ) . '/classes/tggr-source-flickr.php' );
	require_once( dirname( __FILE__ ) . '/classes/tggr-source-google.php' );
}

/*
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
if ( tggr_requirements_met() ) {
	tggr_include_files();

	if ( class_exists( 'Tagregator' ) ) {
		$GLOBALS['tggr'] = Tagregator::get_instance();

		register_activation_hook(   __FILE__, array( $GLOBALS['tggr'], 'activate' ) );
		register_deactivation_hook( __FILE__, array( $GLOBALS['tggr'], 'deactivate' ) );
	}
} else {
	add_action( 'admin_notices', 'tggr_requirements_error' );
}