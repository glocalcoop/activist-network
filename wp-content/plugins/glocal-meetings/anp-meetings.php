<?php
/*
Plugin Name: Activist Network Meetings
Description: Creates custom post types for Meetings with custom fields and custom taxonomies that can be used to store and display meeting notes/minutes and decisions.
Author: Pea, Glocal
Author URI: http://glocal.coop
Version: 0.1
License: GPL
Text Domain: anp_meetings
*/

/************* REQUIRE FILES *****************/

//define( 'ACF_LITE', true );
include_once('advanced-custom-fields/acf.php');
include_once('anp-meetings-render.php');
include_once('inc/custom-post-type-meetings.php');
include_once('inc/custom-post-type-agendas.php');
include_once('inc/custom-post-type-summaries.php');
include_once('inc/custom-post-type-proposals.php');



?>