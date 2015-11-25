=== BP Groups CiviCRM Sync ===
Contributors: needle, cuny-academic-commons
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PZSKM8T5ZP3SC
Tags: civicrm, buddypress, user, groups, sync
Requires at least: 3.9
Tested up to: 4.3
Stable tag: 0.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

BP Groups CiviCRM Sync enables two-way synchronisation between BuddyPress groups and CiviCRM groups.



== Description ==

A port of the Drupal civicrm_og_sync module for WordPress that enables two-way synchronisation between BuddyPress Groups and CiviCRM. It does not rely on any core CiviCRM files, since any required (or adapted) methods are included.

For each *BuddyPress* group, the plugin will automatically create two *CiviCRM* groups:

* A "normal" (mailing list) group containing a contact record for each corresponding *BuddyPress* group member. This group is assigned the same name as the linked *BuddyPress* group.
* An "ACL" group containing the contact record of the administrators of the corresponding *BuddyPress* group. This gives *BuddyPress* group admins the ability to view and edit members of their group in *CiviCRM*.

When a new user is added to (or joins) a *BuddyPress* group, they are automatically added to the corresponding *CiviCRM* group. Likewise, when a contact is added to the "normal" *CiviCRM* group, they will be added as a member to the corresponding *BuddyPress* group. If a contact is added to the *CiviCRM* "ACL" group, they will be added to the *BuddyPress* group as an administrator.

### Requirements

This plugin requires a minimum of *WordPress 3.9*, *BuddyPress 1.8* and *CiviCRM 4.6-alpha1*. Please refer to the installation page for how to use this plugin with versions of CiviCRM prior to 4.6-alpha1.

### Plugin Development

This plugin is in active development. For feature requests and bug reports (or if you're a plugin author and want to contribute) please visit the plugin's [GitHub repository](https://github.com/christianwach/bp-groups-civicrm-sync).



== Installation ==

1. Extract the plugin archive
1. Upload plugin files to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. In Multisite, it is recommended that you network-activate the plugin

For versions of *CiviCRM* prior to 4.6-alpha1, this plugin requires the corresponding branch of the [CiviCRM WordPress plugin](https://github.com/civicrm/civicrm-wordpress) plus the custom WordPress.php hook file from the [CiviCRM Hook Tester repo on GitHub](https://github.com/christianwach/civicrm-wp-hook-tester) so that it overrides the built-in *CiviCRM* file. Please refer to the each repo for further instructions.



== Changelog ==

= 0.2.1 =

Set "Use Parent Group" to off by default

= 0.2 =

First public release

= 0.1 =

Initial release
