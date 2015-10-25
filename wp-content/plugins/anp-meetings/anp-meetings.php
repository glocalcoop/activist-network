<?php

/**
 * ANP Meetings Init
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.0.0
 * @package   ANP_Meetings
 */

/*
Plugin Name: Activist Network Meetings
Description: Creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display meeting notes/minutes and decisions.
Author: Pea, Glocal
Author URI: http://glocal.coop
Version: 0.1
License: GPL
Text Domain: anp_meetings
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


/* ---------------------------------- *
 * Constants
 * ---------------------------------- */

if ( !defined( 'ANP_MEETINGS_PLUGIN_DIR' ) ) {
    define( 'ANP_MEETINGS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'ANP_MEETINGS_PLUGIN_URL' ) ) {
    define( 'ANP_MEETINGS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/* ---------------------------------- *
 * Required Files
 * ---------------------------------- */

//define( 'ACF_LITE', true );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'advanced-custom-fields/acf.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'posts-to-posts/posts-to-posts.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'anp-meetings-render.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-content-filters.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-pre-get-filters.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-meetings.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-agendas.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-summaries.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-post-type-proposals.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/custom-fields.php' );
include_once( ANP_MEETINGS_PLUGIN_DIR . 'inc/post-type-connections.php' );



?>