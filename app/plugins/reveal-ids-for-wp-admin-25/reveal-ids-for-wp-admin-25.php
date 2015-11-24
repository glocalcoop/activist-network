<?php
/*
Plugin Name: Reveal IDs
Version: 1.4.6.1
Plugin URI: http://www.schloebe.de/wordpress/reveal-ids-for-wp-admin-25-plugin/
Description: Reveals hidden IDs in Admin interface that have been removed with WordPress 2.5 (formerly known as Entry IDs in Manage Posts/Pages View for WP 2.5). See <a href="options-general.php?page=reveal-ids-for-wp-admin-25/reveal-ids-for-wp-admin-25.php">options page</a> for information.
Author: Oliver Schl&ouml;be
Author URI: http://www.schloebe.de/
Text Domain: reveal-ids-for-wp-admin-25
Domain Path: /languages

Copyright 2008-2015 Oliver Schlöbe (email : scripts@schloebe.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * The main plugin file
 *
 * @package WordPress_Plugins
 * @subpackage RevealIDsForWPAdmin
 */


/**
 * Define the plugin version
 */
define("RIDWPA_VERSION", "1.4.6.1");

/**
 * Define the plugin path slug
 */
define("RIDWPA_PLUGINPATH", "/" . plugin_basename( dirname(__FILE__) ) . "/");

/**
 * Define the plugin full url
 */
define("RIDWPA_PLUGINFULLURL", trailingslashit(plugins_url( null, __FILE__ )) );

/**
 * Define the plugin full dir
 */
define("RIDWPA_PLUGINFULLDIR", WP_PLUGIN_DIR . RIDWPA_PLUGINPATH );


/**
 * Define the global var RIDWPAISWP30, returning bool if WP 3.0 or higher is running
 */
define('RIDWPAISWP30', version_compare($GLOBALS['wp_version'], '2.9.999', '>'));


/**
* The RevealIDsForWPAdmin class
*
* @package 		WordPress_Plugins
* @subpackage 	RevealIDsForWPAdmin
* @since 		1.3.0
* @author 		scripts@schloebe.de
*/
class RevealIDsForWPAdmin {

	/**
 	* The RevealIDsForWPAdmin class constructor
 	* initializing required stuff for the plugin
 	*
	* PHP 5 Constructor
 	*
 	* @since 		1.3.0
 	* @author 		scripts@schloebe.de
 	*/
	function __construct() {
		$this->textdomain_loaded = false;

		if ( !RIDWPAISWP30 ) {
			add_action('admin_notices', array(&$this, 'require_wpversion_message'));
			return;
		}

		register_activation_hook(__FILE__, array(&$this, 'on_activate'));

		add_action('plugins_loaded', array(&$this, 'load_textdomain'));
		add_action('admin_init', array(&$this, 'init'));

		add_action('admin_head', array(&$this, 'add_css'));

		add_action('admin_menu', array(&$this, 'add_option_menu'));
		add_action('admin_menu', array(&$this, 'default_settings'));
	}



	/**
 	* The RevealIDsForWPAdmin class constructor
 	* initializing required stuff for the plugin
 	*
	* PHP 4 Compatible Constructor
 	*
 	* @since 		1.3.0
 	* @author 		scripts@schloebe.de
 	*/
	function RevealIDsForWPAdmin() {
		$this->__construct();
	}



	/**
 	* Initialize and load the plugin stuff
 	*
 	* @since 		1.3.0
 	* @uses 		$pagenow
 	* @author 		scripts@schloebe.de
 	*/
	function init() {
		global $wpversion, $pagenow;
		if ( !function_exists("add_action") ) return;

		if( $pagenow == 'options-general.php' && isset( $_GET['page'] ) && $_GET['page'] == 'reveal-ids-for-wp-admin-25/reveal-ids-for-wp-admin-25.php' )
			require_once(dirname (__FILE__) . '/' . 'authorplugins.inc.php');

		add_filter('manage_media_columns', array(&$this, 'column_add'));
		add_action('manage_media_custom_column', array(&$this, 'column_value'), 10, 2);

		add_filter('manage_link-manager_columns', array(&$this, 'column_add'));
		add_action('manage_link_custom_column', array(&$this, 'column_value'), 10, 2);

		add_action('manage_edit-link-categories_columns', array(&$this, 'column_add'));
		add_filter('manage_link_categories_custom_column', array(&$this, 'column_return_value'), 10, 3);

		foreach( get_taxonomies() as $taxonomy ) {
			add_action("manage_edit-${taxonomy}_columns", array(&$this, 'column_add'));
			add_filter("manage_${taxonomy}_custom_column", array(&$this, 'column_return_value'), 10, 3);
			if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') )
				add_filter("manage_edit-${taxonomy}_sortable_columns", array(&$this, 'column_add_clean') );
		}

		foreach( get_post_types() as $ptype ) {
			add_action("manage_edit-${ptype}_columns", array(&$this, 'column_add'));
			add_filter("manage_${ptype}_posts_custom_column", array(&$this, 'column_value'), 10, 3);
			if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') )
				add_filter("manage_edit-${ptype}_sortable_columns", array(&$this, 'column_add_clean') );
		}

		add_action('manage_users_columns', array(&$this, 'column_add'));
		add_filter('manage_users_custom_column', array(&$this, 'column_return_value'), 10, 3);
		if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') )
			add_filter("manage_users_sortable_columns", array(&$this, 'column_add_clean') );

		add_action('manage_edit-comments_columns', array(&$this, 'column_add'));
		add_action('manage_comments_custom_column', array(&$this, 'column_value'), 10, 2);
		if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') )
			add_filter("manage_edit-comments_sortable_columns", array(&$this, 'column_add_clean') );
	}


	/**
	 * Set default settings upon plugin activation
	 *
	 * @since 1.3.0
	 * @author scripts@schloebe.de
	 */
	function on_activate() {
		$this->default_settings();
	}


	/**
	 * Adds a bit of CSS
	 *
	 * @since 1.3.0
	 * @author scripts@schloebe.de
	 */
	function add_css() {
		echo "\n" . '<style type="text/css">
	table.widefat th.column-ridwpaid {
		width: 70px;
	}

	table.widefat td.column-ridwpaid {
		word-wrap: normal;
	}
	</style>' . "\n";
	}


	/**
 	* Add the new 'ID' column
 	*
 	* @since 		1.3.0
 	* @author 		scripts@schloebe.de
 	*/
	function column_add($cols) {
		$cols['ridwpaid'] = '<abbr style="cursor:help;" title="' . __('Enhanced by Reveal IDs Plugin', 'reveal-ids-for-wp-admin-25') . '">' . __('ID') . '</abbr>';
		return $cols;
	}


	/**
 	* Add the new 'ID' column without any HTMLy clutter
 	*
 	* @since 		1.4.0
 	* @author 		scripts@schloebe.de
 	*/
	function column_add_clean($cols) {
		$cols['ridwpaid'] = __('ID');
		return $cols;
	}


	/**
 	* Echo the ID for the column
 	*
 	* @since 		1.3.0
 	* @author 		scripts@schloebe.de
 	*/
	function column_value($column_name, $id) {
		if ($column_name == 'ridwpaid') echo $id;
	}


	/**
 	* Return the ID for the column
 	*
 	* @since 		1.3.0
 	* @author 		scripts@schloebe.de
 	*/
	function column_return_value($value, $column_name, $id) {
		if ($column_name == 'ridwpaid') $value = $id;
		return $value;
	}



	/**
 	* Initialize and load the plugin textdomain
 	*
 	* @since 		1.3.0
 	* @author 		scripts@schloebe.de
 	*/
	function load_textdomain() {
		if($this->textdomain_loaded) return;
		load_plugin_textdomain('reveal-ids-for-wp-admin-25', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		$this->textdomain_loaded = true;
	}


	/**
	 * Adds the plugin's default settings
	 *
	 * @since 1.3.0
	 * @author scripts@schloebe.de
	 */
	function default_settings() {
		if( get_option("ridwpa_post_ids_enable") ) {
			delete_option("ridwpa_post_ids_enable");
		}
		if( get_option("ridwpa_page_ids_enable") ) {
			delete_option("ridwpa_page_ids_enable");
		}
		if( get_option("ridwpa_link_ids_enable") ) {
			delete_option("ridwpa_link_ids_enable");
		}
		if( get_option("ridwpa_cat_ids_enable") ) {
			delete_option("ridwpa_cat_ids_enable");
		}
		if( get_option("ridwpa_media_ids_enable") ) {
			delete_option("ridwpa_media_ids_enable");
		}
		if( get_option("ridwpa_user_ids_enable") ) {
			delete_option("ridwpa_user_ids_enable");
		}
		if( get_option("ridwpa_tag_ids_enable") ) {
			delete_option("ridwpa_tag_ids_enable");
		}
		if( get_option("ridwpa_reassigned_075_options") ) {
			delete_option("ridwpa_reassigned_075_options");
		}
		if( get_option("ridwpa_reassigned_115_options") ) {
			delete_option("ridwpa_reassigned_115_options");
		}
		if( !get_option("ridwpa_version") ) {
			add_option("ridwpa_version", RIDWPA_VERSION);
		}
		if( get_option("ridwpa_version") != RIDWPA_VERSION ) {
			update_option("ridwpa_version", RIDWPA_VERSION);
		}
	}


	/**
	 * Adds the plugin's options page
	 *
	 * @since 1.3.0
	 * @author scripts@schloebe.de
	 */
	function add_option_menu() {
		if ( current_user_can('switch_themes') && function_exists('add_submenu_page') ) {
			$menutitle = __('Reveal IDs', 'reveal-ids-for-wp-admin-25');

			add_submenu_page('options-general.php', __('Reveal IDs', 'reveal-ids-for-wp-admin-25'), $menutitle, 'manage_options', __FILE__, array(&$this, 'options_page'));
		}
	}


	/**
	 * Adds content to the plugin's options page
	 *
	 * @since 1.3.0
	 * @author scripts@schloebe.de
	 */
	function options_page() {
	?>

	<div class="wrap">
		<h2>
        <?php _e('Reveal IDs', 'reveal-ids-for-wp-admin-25'); ?>
      	</h2>
		<div id="poststuff" class="ui-sortable">

			<div id="ridwpa_plugins_box" class="postbox if-js-open">
		      	<h3>
		        	<?php _e('More of my WordPress plugins', 'reveal-ids-for-wp-admin-25'); ?>
		      	</h3>
				<table class="form-table">
		 		<tr>
		 			<td>
		 				<?php _e('You may also be interested in some of my other plugins:', 'reveal-ids-for-wp-admin-25'); ?>
						<p id="authorplugins-wrap"><input id="authorplugins-start" value="<?php _e('Show other plugins by this author inline &raquo;', 'reveal-ids-for-wp-admin-25'); ?>" class="button-secondary" type="button"></p>
						<div id="authorplugins-wrap">
							<div id='authorplugins'>
								<div class='authorplugins-holder full' id='authorplugins_secondary'>
									<div class='authorplugins-content'>
										<ul id="authorpluginsul">

										</ul>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
		 				<?php _e('More plugins at: <a class="button rbutton" href="http://www.schloebe.de/portfolio/" target="_blank">www.schloebe.de</a>', 'reveal-ids-for-wp-admin-25'); ?>
		 			</td>
		 		</tr>
				</table>
			</div>

			<div id="ridwpa_help_box" class="postbox">
		      	<h3>
		        	<?php _e('Help', 'reveal-ids-for-wp-admin-25'); ?>
		      	</h3>
				<table class="form-table">
		 		<tr>
		 			<td>
		 				<strong><?php _e('All options to enable/disable the display of IDs have been removed in version 1.3.0! If you would like to hide the ID column on a specific panel, just remove it in the options panel.', 'reveal-ids-for-wp-admin-25'); ?></strong>
					</td>
		 		</tr>
				</table>
			</div>

		</div>
 	</div>
	<?php
	}



	/**
 	* Checks for the version of WordPress,
 	* and adds a message to inform the user
 	* if required WP version is less than 3.0
 	*
 	* @since 		1.3.0
 	* @author 		scripts@schloebe.de
 	*/
	function require_wpversion_message() {
		echo "<div class='error fade'><p>" . sprintf(__("<strong>Reveal IDs</strong> 1.3.0 and above require at least WordPress 3.0! If you're still using a WP version prior to 3.0, please <a href='%s'>use Reveal IDs version 1.2.7</a>! Consider updating to the latest WP version for your own safety!", 'reveal-ids-for-wp-admin-25'), 'http://downloads.wordpress.org/plugin/reveal-ids-for-wp-admin-25.1.2.7.zip') . "</p></div>";
	}

}

if ( class_exists('RevealIDsForWPAdmin') && is_admin() ) {
	$RevealIDsForWPAdmin = new RevealIDsForWPAdmin();
}
?>