<?
/*
Plugin Name: Activist Network Global Menu
Description: Adds global menu to all sites in a multi-site network
Author: Pea, Glocal
Author URI: http://glocal.coop
Version: 0.1
License: GPL
*/

// Check for multisite - DONE
// Create options page with select list of existing menus (not related menu position) - DONE
// Selected menu will be global nav - DONE
// Use JQuery to insert menu on all sites except main site - DONE

// Considerations:
// Styling
// Location

/**
 * 
 * Set-up
 * 
 **/


$plugin_url =  WP_PLUGIN_URL . '/anp-global-menu';
$options = array();
$anp_global_menu_errors = new WP_Error();
$anp_global_menu_errors_print = 0;

//If this file is called directly, abort.
if( !defined( 'WPINC' ) ) {
	die();
}

//Only run this menu if in multisite...
if( is_multisite() ) { 
	include_once( 'anp-global-menu-options-render.php' );
	include_once( 'anp-global-menu-render.php' );

}

//Print errors
if( $anp_global_menu_errors_print ) {
	include_once( 'inc/anp-global-menu-errors.php' );
}


?>