<?php

/**
 * Plugin Name:		Activist Network Global Menu
 * Plugin URI:		https://github.com/glocalcoop/activist-network
 * Description:		Plugin that displays a global menu on sub-sites of a WP multi-site install.
 * Version:			0.0.1-dev
 * Author:			Pea, Glocal
 * Author URI:		http://glocal.coop
 * License:			GPLv3
 * License URI:		http://www.gnu.org/licenses/gpl.txt
 * Text Domain:		anp-global-menu
 * Multisite:		true
 * Domain Path:		/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define constant

define( 'ANP_GLOBAL_MENU_URL', WP_PLUGIN_URL . '/anp-global-menu' );

//Only run this menu if in multisite...
if( is_multisite() ) { 

	include_once( plugin_dir_path( __FILE__ ) . 'anp-global-menu-render.php' );
	include_once( plugin_dir_path( __FILE__ ) . 'class-anp-global-menu-options.php' );
	
}

// Instantiate ANP_Global_Menu_Options class

function run_anp_global_menu() {

	//ANP_Global_Menu::get_instance();
	ANP_Global_Menu_Options::get_instance();

}
run_anp_global_menu();
