=== Moderate New Blogs ===
Contributors: dsader
Donate link: http://dsader.snowotherway.org
Tags: moderate, new blogs, moderation, multisite,
Requires at least: 3.3.2
Tested up to: 3.7.1
Stable tag: Trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP3.0 multisite "mu-plugin". New blogs(aka sites) await a final click from a Network Admin to activate.

== Description ==
WP3.0 multisite "mu-plugin". New blogs(aka sites) await a final click from a Network Admin to activate. This plugin flags new blogs in Network Admin-->Sites as "Awaiting Moderation". 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `ds_wp3_moderate_new_blogs.php` to the `/wp-content/mu-plugins/` directory
2. Visit Network Admin->Settings page and checkbox "Moderate New Blogs".
3. Look for new blogs with "Awaiting Moderation" action at Network Admin->Sites page

Optional: 
To change the default message for an inactive blog use your own drop-in plugin as described in wp-includes/ms-load.php:
	`if ( file_exists( WP_CONTENT_DIR . '/blog-inactive.php' ) )
			return WP_CONTENT_DIR . '/blog-inactive.php';`

== Frequently Asked Questions ==

* Will this plugin stop spammer blogs? No.

== Changelog ==
= 3.3.2 = 

* Tested up to: WP 3.3.2

= 3.1 = 

* Tested up to: WP 3.2.1

= 3.0.0 = 

* initial release

== Upgrade Notice ==
= 3.3.2 =
 
* Tested up to: WP 3.3.2

= 3.0.0 = 

* initial release