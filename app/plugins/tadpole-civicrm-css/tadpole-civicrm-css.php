<?php

/*
Plugin Name: Tadpole CiviCRM CSS for WordPress
Plugin URI: https://tadpole.cc
Description: Clean up CiviCRM default CSS handling.  On Activation, via CiviCRM API deactivate built in civicrm.css.  Then properly register and load Tadpole's custom civicrm.css on the front end, in the admin register the default civicrm.css.
Version: 1.2
Author: Tadpole Collective
Author URI: https://tadpole.cc
License: AGPL
CiviCRM Versions:  4.4 4.5 4.6
*/


/*Enqueue default CiviCRM CSS in admin.  Create a filter to allow themes and other plugins to overrride */
add_action( 'admin_enqueue_scripts', 'tc_admin_register_tad_civicrm_styles' );

function tc_admin_register_tad_civicrm_styles() {
		$tc_civi_css_admin = (plugin_dir_url('civicrm')  . 'civicrm/civicrm/css/civicrm.css');
		$tc_civi_css_admin = apply_filters('tc_civicss_override_admin', $tc_civi_css_admin);
        wp_enqueue_style ('tad_admin_civicrm',  $tc_civi_css_admin );
}

/*Enqueue custom CiviCRM CSS in front end of site.  Create a filter to allow themes and other plugins to overrride */
add_action( 'wp_print_styles', 'tc_register_tad_civicrm_styles', 110 );
function tc_register_tad_civicrm_styles() {
	$tc_civi_css = (plugin_dir_url( __FILE__ )  . 'css/civicrm.css') ;
	$tc_civi_css = apply_filters ( 'tc_civicss_override' ,  $tc_civi_css ) ;
	wp_enqueue_style ('tad_civicrm', $tc_civi_css );
}


/*On Activation, update CiviCRM via API to turn off Default CSS file */
register_activation_hook( __FILE__, 'tc_civi_api');
function tc_civi_api() {

civicrm_wp_initialize();

civicrm_api3('Setting', 'create', array('disable_core_css' => 1,));
}

/*On Deactivation, update CiviCRM via API to turn on Default CSS file */
register_deactivation_hook( __FILE__, 'tc_civi_deactivate');
function tc_civi_deactivate() {

civicrm_wp_initialize();

civicrm_api3('Setting', 'create', array('disable_core_css' => 0,));
}