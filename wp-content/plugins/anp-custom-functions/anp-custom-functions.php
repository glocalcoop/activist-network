<?php
/**
 * ANP Custom Functions
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Custom
 */

/*
Plugin Name: Activist Network Custom Functions
Plugin URI: https://github.com/glocalcoop/anp-custom-functions
Description: Creates custom functions and shortcodes.
Author: Pea, Glocal
Author URI: http://glocal.coop
Version: 0.1
License: GPL
Text Domain: anp_custom_functions
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


/* ---------------------------------- *
 * Constants
 * ---------------------------------- */

if ( !defined( 'ANP_CUSTOM_FUNC_PLUGIN_DIR' ) ) {
    define( 'ANP_CUSTOM_FUNC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'ANP_CUSTOM_FUNC_PLUGIN_URL' ) ) {
    define( 'ANP_CUSTOM_FUNC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/* ---------------------------------- *
 * Required Files
 * ---------------------------------- */

include_once( ANP_CUSTOM_FUNC_PLUGIN_DIR . 'inc/custom-shortcodes.php' );
//include_once( ANP_CUSTOM_FUNC_PLUGIN_DIR . 'inc/custom-handbook.php' );


?>