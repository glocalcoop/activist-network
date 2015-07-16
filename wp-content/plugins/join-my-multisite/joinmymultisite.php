<?php
/*
Plugin Name: Join My Multisite
Plugin URI: http://halfelf.org/plugins/join-my-multisite/
Description: Allow logged in users to add themselves to sites (or auto-add them to all sites). <strong>Settings are per-site, under the Users menu</strong>.
Version: 1.7.8
Author: Mika Epstein (Ipstenu)
Author URI: http://halfelf.org/
Network: true
Text Domain: join-my-multisite
Domain Path: /i18n

Copyright 2012 Mika Epstein (email: ipstenu@ipstenu.org)

    This file is part of Join My Multisite, a plugin for WordPress.

    Join My Multisite is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    Join My Multisite is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.
*/

// First we check to make sure you meet the requirements
global $wp_version;
$exit_msg_version = 'Sorry, but this plugin is no longer supported on pre-3.7 WordPress installs.';
if (version_compare($wp_version,"3.7","<")) { exit($exit_msg_version); }
$exit_msg_multisite = 'This plugin only functions on WordPress Multisite.';
if( !is_multisite() ) { exit($exit_msg_multisite); }

// My Defines
require_once dirname(__FILE__) . '/admin/defines.php';

class JMM {

    public static function init() {
        load_plugin_textdomain( 'join-my-multisite', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/' );
    }

    // donate link on manage plugin page
    public static function donate_link($links, $file) {
        if ($file == plugin_basename(__FILE__)) {
                $donate_link = '<a href="https://store.halfelf.org/donate/">Donate</a>';
                $links[] = $donate_link;
        }
        return $links;
    }

     // Sets up the settings page
	public static function add_settings_page() {
        global $jmm_settings_page;
        $jmm_settings_page = add_users_page(__('Join My Multisite Settings','join-my-multisite'), __('Join My Multisite','join-my-multisite'), 'manage_options', 'jmm', array('JMM', 'settings_page'));
    	}
	 
 	public static function settings_page() {
	   // Main Settings
		include_once( JMM_PLUGIN_DIR . '/admin/settings.php');
	}
	
	    // Add users
    static function join_site( ) {
        global $current_user, $blog_id;
        
        $jmm_options = get_option( 'helfjmm_options' );
    
        if(!is_user_logged_in())
        return false;
     
        if( !is_user_member_of_blog() ) {
            add_user_to_blog($blog_id, $current_user->ID, $jmm_options['role']);
        }
    }

}

new JMM();

// Why are you still reading this? Do you want a cookie?