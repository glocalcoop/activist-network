=== Moderate New Blogs ===
Contributors: dsader
Donate link: http://dsader.snowotherway.org
Tags: moderate, new blogs, moderation, multisite,
Requires at least: 4.3
Tested up to: 4.3
Stable tag: Trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress Multisite Network plugin. New blogs(aka sites) await a final click from a Network Admin to activate.

== Description ==
WordPress Multisite Network plugin. New blogs(aka sites) await a final click from a Network Admin to activate. This plugin flags new blogs in Network Admin-->Sites as "Awaiting Moderation". 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `ds_wp3_moderate_new_blogs.php` to the `/wp-content/plugins/` directory
2. Network Activate
3. Visit Network Admin->Settings page and checkbox "Moderate New Blogs".
4. Look for new blogs with "Awaiting Moderation" action at Network Admin->Sites page or Network Dashboard

Optional: 
To change the default message for an inactive blog use your own drop-in plugin as described in wp-includes/ms-load.php:
	`if ( file_exists( WP_CONTENT_DIR . '/blog-inactive.php' ) )
			return WP_CONTENT_DIR . '/blog-inactive.php';`

== Frequently Asked Questions ==

* Will this plugin stop spammer blogs? No. Legit blogs wait until approved manually by SuperAdmin.

== Changelog ==
= 4.3 = 

* Requires: WP 4.3

== Upgrade Notice ==
= 4.3 =
 
* Requires: WP 4.3
