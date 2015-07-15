=== Multisite Global Terms ===
Contributors:sbrajesh, buddydev
Tags: multisite, categories, global categories, sitewide, terms
Requires at least: 2.9
Tested up to: 3.8.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow global terms across all blogs on a multisite installation.

== Description ==
Multisite Global Terms allows all sites across a WordPress multisite network to share the categories/tags/terms. 

It uses the main site's table to store all the terms.
Please visit the blog post on [BuddyDev](http://buddydev.com/wordpress-multisite/want-global-categories-tags-taxonomies-across-wordpress-multisite-network/ "Multisite Global Terms") for support or to ask a questions.

== Installation ==

1. Upload `mu-globa-terms` directory to the `/wp-content/plugins/` directory
1. Network Activate the plugin through the 'Plugins' menu in WordPress Network Admin( NetworkAdmin->Dasrboard->Plugins)
1. That's it

== Frequently Asked Questions ==

= I can't see the plugin in my dashboard->Plugins screen =

It is a network only plugin and will only appear in the NetworkAdmin->Dashboadr->Plugins screen

= Will I loose my old Categories/tags/custom terms =

Depends. If there are categories on the sub blogs before activating this plugin, They won't be available. The main site categories/Terms will remain available.

= Does it work for Custom taxonomy terms Too? =

Yes. It works for custom taxonomy terms too. Just make sure the custom taxonomy is registered on the sit/blog you are trying to access.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* Initial release
