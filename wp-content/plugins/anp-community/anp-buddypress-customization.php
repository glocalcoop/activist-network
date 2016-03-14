<?php

/**
 * ANP BuddyPress Customization
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_BuddyPress_Customization
 */

/*
Plugin Name: Activist Network BuddyPress Customization
Description: Customization for BuddyPress and associated plugins.
Author: Pea, Glocal
Author URI: http://glocal.coop
Version: 1.0
License: GPLv3
Text Domain: anp-bp-custom
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


/* ---------------------------------- *
 * Constants
 * ---------------------------------- */

if ( !defined( 'ANP_BP_CUSTOM_PLUGIN_DIR' ) ) {
    define( 'ANP_BP_CUSTOM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'ANP_BP_CUSTOM_PLUGIN_URL' ) ) {
    define( 'ANP_BP_CUSTOM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( !defined( 'ANP_BP_CUSTOM_PLUGIN_NAMESPACE' ) ) {
    define( 'ANP_BP_CUSTOM_PLUGIN_NAMESPACE', 'anp-bp-custom' );
}


/* ---------------------------------- *
 * Required Files
 * ---------------------------------- */

include_once( ANP_BP_CUSTOM_PLUGIN_DIR . '/inc/bbpress-custom-functions.php' );


?>