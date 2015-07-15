<?php
/**
 * Use OpenStreetMap in WordPress Events Manager
 * 
 * PHP Version 5.4
 * 
 * @category Plugin
 * @package  EventsManagerOSM
 * @author   StrasWeb <contact@strasweb.fr>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     https://github.com/StrasWeb/events-manager-osm
 */
/*
Plugin Name: Events Manager OSM
Plugin URI: https://github.com/StrasWeb/events-manager-osm
Description: Use OpenStreetMap in WordPress Events Manager
Author: StrasWeb
Version: 0.2
Author URI: https://strasweb.fr/
*/

/**
 * Load the JavaScript
 * 
 * @return void
 * */
function loadOSMJS()
{
    wp_enqueue_script(
        'events-manager-osm',
        plugin_dir_url(__FILE__).'/events-manager-osm.js',
        array('jquery')
    );
}

add_action('wp_head', 'loadOSMJS');

?>
