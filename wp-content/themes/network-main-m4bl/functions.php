<?php
/*
Author: Pea, Glocal
URL: htp://glocal.coop
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( !is_admin() ) add_action( 'wp_enqueue_scripts', 'glocal_child_scripts_and_styles' );

function glocal_child_scripts_and_styles() {

	// Responsive Script
	//wp_register_script( 'MYSCRIPTNAME', get_template_directory_uri() . '/path/to/script', array(), '', true );

	// Responsive Style
	//wp_register_style( 'MYSTYLENAME', get_template_directory_uri() . '/path/to/stylesheet');

	// Enqueue styles and scripts
    //wp_enqueue_script( 'MYSCRIPTNAME' );
    //wp_enqueue_style( 'MYSTYLENAME' );
    
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/library/css/style.css', array('glocal-stylesheet') );
    
}





?>