<?php
/*
--------------------------------------------------------------------------------
Plugin Name: BP Groups CiviCRM Sync
Plugin URI: https://github.com/christianwach/bp-groups-civicrm-sync
Description: A port of the Drupal civicrm_og_sync module for WordPress that enables two-way synchronisation between BuddyPress groups and CiviCRM groups.
Author: Christian Wach
Version: 0.2.1
Author URI: http://haystack.co.uk
Text Domain: bp-groups-civicrm-sync
Domain Path: /languages
Depends: CiviCRM
--------------------------------------------------------------------------------
*/



// set our version here
define( 'BP_GROUPS_CIVICRM_SYNC_VERSION', '0.2.1' );

// store reference to this file
if ( ! defined( 'BP_GROUPS_CIVICRM_SYNC_FILE' ) ) {
	define( 'BP_GROUPS_CIVICRM_SYNC_FILE', __FILE__ );
}

// store URL to this plugin's directory
if ( ! defined( 'BP_GROUPS_CIVICRM_SYNC_URL' ) ) {
	define( 'BP_GROUPS_CIVICRM_SYNC_URL', plugin_dir_url( BP_GROUPS_CIVICRM_SYNC_FILE ) );
}

// store PATH to this plugin's directory
if ( ! defined( 'BP_GROUPS_CIVICRM_SYNC_PATH' ) ) {
	define( 'BP_GROUPS_CIVICRM_SYNC_PATH', plugin_dir_path( BP_GROUPS_CIVICRM_SYNC_FILE ) );
}

// for debugging
define( 'BP_GROUPS_CIVICRM_SYNC_DEBUG', false );



/*
--------------------------------------------------------------------------------
BP_Groups_CiviCRM_Sync Class
--------------------------------------------------------------------------------
*/

class BP_Groups_CiviCRM_Sync {

	/**
	 * Properties
	 */

	// CiviCRM utilities class
	public $civi;

	// BuddyPress utilities class
	public $bp;

	// Admin utilities class
	public $admin;



	/**
	 * Initialises this object
	 *
	 * @return object
	 */
	function __construct() {

		// init loading process
		$this->initialise();

		// --<
		return $this;

	}



	//##########################################################################



	/**
	 * Do stuff on plugin init
	 *
	 * @return void
	 */
	public function initialise() {

		// use translation files
		add_action( 'plugins_loaded', array( $this, 'enable_translation' ) );

		// load our cloned CiviCRM utility functions class
		require( BP_GROUPS_CIVICRM_SYNC_PATH . 'bp-groups-civicrm-sync-civi.php' );

		// instantiate
		$this->civi = new BP_Groups_CiviCRM_Sync_CiviCRM( $this );

		// load our BuddyPress utility functions class
		require( BP_GROUPS_CIVICRM_SYNC_PATH . 'bp-groups-civicrm-sync-bp.php' );

		// instantiate
		$this->bp = new BP_Groups_CiviCRM_Sync_BuddyPress( $this );

		// load our Admin utility class
		require( BP_GROUPS_CIVICRM_SYNC_PATH . 'bp-groups-civicrm-sync-admin.php' );

		// instantiate
		$this->admin = new BP_Groups_CiviCRM_Sync_Admin( $this );

		// store references
		$this->civi->set_references( $this->bp, $this->admin );
		$this->bp->set_references( $this->civi, $this->admin );
		$this->admin->set_references( $this->bp, $this->civi );

	}



	/**
	 * Do stuff on plugin activation
	 *
	 * @return void
	 */
	public function activate() {

		// setup plugin admin
		$this->admin->activate();

	}



	/**
	 * Do stuff on plugin deactivation
	 *
	 * @return void
	 */
	public function deactivate() {

		// tear down plugin admin
		$this->admin->deactivate();

	}



	//##########################################################################



	/**
	 * Load translation files
	 * A good reference on how to implement translation in WordPress:
	 * http://ottopress.com/2012/internationalization-youre-probably-doing-it-wrong/
	 *
	 * @return void
	 */
	public function enable_translation() {

		// not used, as there are no translations as yet
		load_plugin_textdomain(

			// unique name
			'bp-groups-civicrm-sync',

			// deprecated argument
			false,

			// relative path to directory containing translation files
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'

		);

	}



} // class ends



// declare as global
global $bp_groups_civicrm_sync;

// init plugin
$bp_groups_civicrm_sync = new BP_Groups_CiviCRM_Sync;

// activation
register_activation_hook( __FILE__, array( $bp_groups_civicrm_sync, 'activate' ) );

// deactivation
register_deactivation_hook( __FILE__, array( $bp_groups_civicrm_sync, 'deactivate' ) );

// uninstall will use the 'uninstall.php' method when fully built
// see: http://codex.wordpress.org/Function_Reference/register_uninstall_hook



/**
 * Utility to add link to settings page
 *
 * @param array $links The existing links array
 * @param str $file The name of the plugin file
 * @return array $links The modified links array
 */
function bp_groups_civicrm_sync_plugin_action_links( $links, $file ) {

	// add settings link
	if ( $file == plugin_basename( dirname( __FILE__ ) . '/bp-groups-civicrm-sync.php' ) ) {

		// is this Network Admin?
		if ( is_network_admin() ) {
			$link = add_query_arg( array( 'page' => 'bp_groups_civicrm_sync_settings' ), network_admin_url( 'settings.php' ) );
		} else {
			$link = add_query_arg( array( 'page' => 'bp_groups_civicrm_sync_settings' ), admin_url( 'options-general.php' ) );
		}

		// add settings link
		$links[] = '<a href="' . $link . '">' . esc_html__( 'Settings', 'bp-groups-civicrm-sync' ) . '</a>';

	}

	// --<
	return $links;

}

// add filters for the above
add_filter( 'network_admin_plugin_action_links', 'bp_groups_civicrm_sync_plugin_action_links', 10, 2 );
add_filter( 'plugin_action_links', 'bp_groups_civicrm_sync_plugin_action_links', 10, 2 );



