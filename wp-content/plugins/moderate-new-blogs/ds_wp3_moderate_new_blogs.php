<?php
/*
Plugin Name: Moderate New Blogs
Plugin URI: http://wordpress.org/extend/plugins/moderate-new-blogs/
Description: New blogs(aka sites) await a final click from a Network Admin to activate in Network-->Sites "Awaiting Moderation". WP3.3.2+ only
Author: D Sader
Version: 3.3.2
Author URI: http://dsader.snowotherway.org


 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 

Notes: 
To change the default message for an inactive blog use a drop-in plugin as described in wp-includes/ms-load.php:
		if ( file_exists( WP_CONTENT_DIR . '/blog-inactive.php' ) )
			return WP_CONTENT_DIR . '/blog-inactive.php';

*/
	
class ds_moderate_blog_signup {

	function ds_moderate_blog_signup() {
	}
	function admin_notices() {
		if( !is_network_admin() ) return;
		global $wpdb;
		// blogs awaiting activation
		$blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND deleted = '2' ", $wpdb->siteid ) , ARRAY_A );
		if( is_array( $blogs ) ) {
			echo '<div id="update-nag">The following blogs are "Awaiting Moderation" at <a href="'.network_admin_url().'sites.php">Site Admin->Blogs</a> (or click to activate): ';
			$list	= array();
			foreach( $blogs as $details ) {
				$blogname = get_blog_option( $details[ 'blog_id' ], 'blogname' );
				$list[]	= '<span class="activate"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=activateblog&amp;id=' . $details['blog_id'] . '&amp;msg=' . urlencode( sprintf( __( 'You are about to activate the site %s' ), $blogname ) ) ), 'confirm' ) ) . '">'. $blogname .'</a></span>';
				
			}
			if (count($list))  
				echo implode(' | ', $list); 
			echo '</div>';
		}
		// blogs waiting to be deleted
		$blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND deleted = '1' ", $wpdb->siteid ) , ARRAY_A );
			if( is_array( $blogs ) ) {
			echo '<div id="update-nag">The following blogs are "Awaiting Deletion" at <a href="'.network_admin_url().'sites.php">Site Admin->Blogs</a> (or click to delete): ';
			$list	= array();
			foreach( $blogs as $details ) {
				$blogname = get_blog_option( $details[ 'blog_id' ], 'blogname' );

				$list[]	= '<span class="delete"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=deleteblog&amp;id=' . $details['blog_id'] . '&amp;msg=' . urlencode( sprintf( __( 'You are about to delete the site %s' ), $blogname ) ) ), 'confirm' ) ) . '">'. $blogname .'</a></span>';
				
			}
			if (count($list))  
				echo implode(' | ', $list); 
			echo '</div>';
			}
	}
	function moderated($blog_id) {
		$number = intval(get_site_option('ds_moderate_blog_signup'));
		if ( $number == '2' ) {
			update_blog_status( $blog_id, "deleted", $number); 
			} else {
		return; 
		}
	}
	
	function wpmu_blogs_actions($blog_id) {
		$blogname = get_blog_option( $blog_id, 'blogname' );
		if ( get_blog_status( $blog_id, "deleted" ) == '2' ) {
			
		echo '<span class="activate"><a href="' . esc_url( wp_nonce_url( network_admin_url( 'sites.php?action=confirm&amp;action2=activateblog&amp;id=' . $blog_id . '&amp;msg=' . urlencode( sprintf( __( 'You are about to activate the site %s' ), $blogname ) ) ), 'confirm' ) ) . '">' . __( 'Awaiting Moderation' ) . '</a></span>';
		}
	}
	
	function options_page() {
		$number = intval(get_site_option('ds_moderate_blog_signup'));
		$checked = ( $number == '2' ) ? ' checked=""' : '';
		echo '<h3>' . __('Moderate New Sites') . '</h3>';
		echo '	
		<table class="form-table">
			<tr valign="top"> 
				<th scope="row">' . __('Moderation Enabled') . '</th>
				<td><input type="checkbox" name="ds_moderate_blog_signup" value="2" ' . $checked . ' /><br /><small>' . __('New sites await a final click from a Network Admin to <a href="'.network_admin_url().'sites.php">Activate</a>') . '</small>
				</td>
			</tr>
		</table>
		'; 
	}

	function update() {
		update_site_option('ds_moderate_blog_signup', $_POST['ds_moderate_blog_signup']);
	}
}

if (class_exists("ds_moderate_blog_signup")) {
	$ds_moderate_blog_signup = new ds_moderate_blog_signup();	
}

if (isset($ds_moderate_blog_signup)) {
	add_action( 'wpmu_new_blog', array(&$ds_moderate_blog_signup, 'moderated'), 10, 1);
	add_action( 'wpmublogsaction',  array(&$ds_moderate_blog_signup, 'wpmu_blogs_actions'), 10, 1);
	add_action( 'update_wpmu_options', array(&$ds_moderate_blog_signup, 'update'));
	add_action( 'wpmu_options', array(&$ds_moderate_blog_signup, 'options_page'));
	add_action( 'mu_rightnow_end', array(&$ds_moderate_blog_signup, 'admin_notices'));
}
?>