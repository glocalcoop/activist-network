<?php
/*
Plugin Name: Form Manager
Plugin URI: http://www.campbellhoffman.com/form-manager/
Description: Create custom forms; download entered data in .csv format; validation, required fields, custom acknowledgments;
Version: 1.7.0
Author: Campbell Hoffman
Author URI: http://www.campbellhoffman.com/
Text Domain: wordpress-form-manager
License: GPL2

  Copyright 2011 Campbell Hoffman

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License (GPL v2) only.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$fm_oldIncludePath = get_include_path();
set_include_path( dirname( __FILE__ ) . '/' );

global $fm_currentVersion;
$fm_currentVersion = 		"1.7.0";

global $fm_DEBUG;
$fm_DEBUG = 				false;

global $fm_forceRinstall;
$fm_forceReinstall =		false;

// flags for other plugins we want to integrate with
global $fm_SLIMSTAT_EXISTS;
global $fm_MEMBERS_EXISTS;

// API globals, the API is not an object.
global $fm_globals;
$fm_globals = array();

$fm_SLIMSTAT_EXISTS = 		false;
$fm_MEMBERS_EXISTS = 		false;

/**************************************************************/
/******* HOUSEKEEPING *****************************************/

//make sure the page wasn't accessed directly
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();	
}
// only WP 3.0+
if ( version_compare( get_bloginfo( 'version' ), '3.0.0', '<' ) ) 
	wp_die( 
		__('Form Manager requires WordPress version 3.0 or higher', 'wordpress-form-manager') 
		);
	
// only PHP 5.2+
if ( version_compare(PHP_VERSION, '5.2.0', '<' ) ) 
	wp_die( 
		__('Form Manager requires PHP version 5.2 or higher', 'wordpress-form-manager') 
		);

// only MySQL 5.0.3 or greater
if(isset($wpdb))
	if ( version_compare($wpdb->db_version(), '5.0.3', '<' ) ) 
		wp_die( 
			__('Form manager requires MySQL version 5.0.3 or higher', 'wordpress-form-manager')
			);

include 'helpers.php';
include 'db.php';
include 'display.php';
include 'template.php';
include 'email.php';
include 'formdefinition.php';

/**************************************************************/
/******* PLUGIN OPTIONS ***************************************/

$optionDefaults = array(
	'fm-shortcode' 			=> 'form',
	'fm-enable-mce-button' 	=> 'YES',
	'fm-file-method' 		=> 'auto',
	'fm-file-name-format' 	=> '%filename% (m-d-y-h-i-s)',
	'fm-email-send-method' 	=> 'wp_mail',
	'fm-allowed-tags' 		=> '<a><abbr><acronym><b><bdo><blockquote><br><caption><center><cite><code><col><colgroup><dd><del><dfn><div><dl><dt><em><h1><h2><h3><h4><h5><h6><hr><i><img><ins><kbd><legend><li><ol><p><pre><q><s><samp><span><strike><strong><sub><sup><table><tbody><td><tfoot><th><thead><tr><tt><u><ul>',
	'fm-nonce-check' 		=> 'YES',
	'fm-shortcode-scripts' 	=> 'NO',
	'fm-disable-css' 		=> 'NO',
	'fm-strip-tags'			=> 'YES',
	'fm-disable-cache'		=> 'NO', 		// as of 1.6.46: fresh installs will have this value set to 'YES', in the fm_install function.
);

foreach ( $optionDefaults as $key=>$val ){
	if ( get_option( $key ) === false )
		update_option( $key, $val );
}
	
update_option( "fm-forms-table-name", 			"fm_forms" );
update_option( "fm-items-table-name", 			"fm_items" );
update_option( "fm-settings-table-name", 		"fm_settings" );
update_option( "fm-templates-table-name", 		"fm_templates" );
update_option( "fm-data-table-prefix", 			"fm_data" );
update_option( "fm-query-table-prefix", 		"fm_queries" );
update_option( "fm-default-form-template", 		"fm-form-default.php" );
update_option( "fm-default-summary-template", 	"fm-summary-default.php" );
update_option( "fm-temp-dir", 					"tmp" );
update_option( "fm-data-shortcode", 			"formdata" );

global $wpdb;
global $fmdb;
global $fm_display;
global $fm_templates;

load_plugin_textdomain(
	'wordpress-form-manager', 
	false, 
	dirname( plugin_basename( __FILE__ ) ) . '/languages/' 
	);

$fmdb = fm_db_class::Construct( $wpdb->prefix.get_option( 'fm-forms-table-name' ),
	$wpdb->prefix.get_option( 'fm-items-table-name' ),
	$wpdb->prefix.get_option( 'fm-settings-table-name' ),
	$wpdb->prefix.get_option( 'fm-templates-table-name' ),
	$wpdb->dbh
	);
	
// if there was a problem, register the error page and exit
if ( $fmdb == null ){

	function fm_showErrorPage(){ include 'pages/error.php'; }
	function fm_registerErrorPage(){
		add_object_page(
			__("Forms", 'wordpress-form-manager'), 
			__("Forms", 'wordpress-form-manager'),
			apply_filters( 'fm_main_capability', 'manage_options' ), 
			'fm-admin-main', 
			'fm_showErrorPage',
			plugins_url( '/mce_plugins/formmanager.png', __FILE__ )
			);
	}
	add_action( 'admin_menu', 'fm_registerErrorPage' );
	
	return;
}

///////////////////////////////////////////////////////////////////////////////////

$fm_display = new fm_display_class();
$fm_templates = new fm_template_manager();
				
/**************************************************************/
/******* DATABASE SETUP ***************************************/

function fm_install() {
	global $fmdb;
	global $fm_currentVersion;
	
	$freshInstall = ( get_option( 'fm-version', 'none' ) == 'none' );
	
	// default settings for new options differ for fresh vs upgraded plugin versions
	if ( $freshInstall === true ){
		update_option( 'fm-disable-cache', 'YES' );
	}
	
	if ( get_option( 'fm-version' ) == '1.5.29' ) {
		$fmdb->fixItemMeta();
	}
	
	update_option( 'fm-last-version', get_option( 'fm-version' ) );
	
	//from any version before 1.4.0; must be done before the old columns are removed
	$fmdb->convertAppearanceSettings();
	
	//initialize the database
	$fmdb->setupFormManager();

	// covers updates from 1.3.0 
	$q = "UPDATE `{$fmdb->formsTable}` " .
		"SET `behaviors` = 'reg_user_only,display_summ,single_submission' " .
		"WHERE `behaviors` = 'reg_user_only,no_dup'";
	$fmdb->query($q);
	
	$q = "UPDATE `{$fmdb->formsTable}` " .
		"SET `behaviors` = 'reg_user_only,display_summ,edit' " .
		"WHERE `behaviors` = 'reg_user_only,no_dup,edit'";
	$fmdb->query($q);
	
	//updates from 1.4.10 and previous
	$fmdb->fixTemplatesTableModified();
	
	// covers versions up to and including 1.3.10
	$fmdb->fixCollation();		
	
	$fmdb->updateDataTables();
	
	$fmdb->fixDBTypeBug();
	
	$fmdb->fixDateValidator();
	
	$fmdb->fixFormEmailOptions();
		
	update_option( 'fm-version', $fm_currentVersion );			
}  
register_activation_hook( __FILE__, 'fm_install' );

//uninstall - delete the table(s). 
function fm_uninstall() {
	global $fmdb;	
	$fmdb->removeFormManager();
	
	delete_option( 'fm-shortcode' );
	delete_option( 'fm-forms-table-name' );
	delete_option( 'fm-items-table-name' );
	delete_option( 'fm-settings-table-name' );
	delete_option( 'fm-templates-table-name' );
	delete_option( 'fm-data-table-prefix' );
	delete_option( 'fm-query-table-prefix' );
	delete_option( 'fm-default-form-template' );
	delete_option( 'fm-default-summary-template' );
	delete_option( 'fm-version' );
	delete_option( 'fm-temp-dir' );
	delete_option( 'fm-data-shortcode' );
	delete_option( 'fm-enable-mce-button' );
	delete_option( 'fm-file-method' );
	delete_option( 'fm-file-name-format' );
	delete_option( 'fm-allowed-tags' );
	delete_option( 'fm-nonce-check' );
	delete_option( 'fm-strip-tags' );
	delete_option( 'fm-shortcode-scripts' );
	delete_option( 'fm-disable-css' );
	delete_option( 'fm-disable-cache' );
}
register_uninstall_hook( __FILE__, 'fm_uninstall' );


/**************************************************************/
/******* HOUSEKEEPING *****************************************/

//delete .csv files on each login
add_action( 'wp_login', 'fm_cleanCSVData' );
function fm_cleanCSVData() {
	$fmTmpDir = get_option( "fm-temp-dir", false );
	if ( $fmTmpDir !== false && trim( $fmTmpDir ) != "" ){
		$dirName = @dirname( __FILE__ ) . "/" . $fmTmpDir;
		$dir = @opendir( $dirName );
		while ( $fname = @readdir( $dir ) ) {
			if ( file_exists( $dirName . "/" . $fname ) ) {
				@unlink( $dirName . "/" . $fname );
			}			
		}
		@closedir( $dir );
	}
}

/**************************************************************/
/******* INIT, SCRIPTS & CSS **********************************/

add_action( 'admin_init', 'fm_adminInit' );
function fm_adminInit() {
	global $fm_SLIMSTAT_EXISTS;
	global $fm_templates;
	global $plugin_page;
	
	if ( get_option( 'slimstat_secret' ) !==  false ) {
		$fm_SLIMSTAT_EXISTS = true;
	}
	
	$fm_templates->initTemplates();
	
	$isFMPage = strrpos($plugin_page, 'fm-');
	if( $isFMPage !== false && $isFMPage == 0 ) {
		
		wp_register_style( 'form-manager-css', plugins_url( '/css/style.css', __FILE__ ) );
		wp_enqueue_style( 'form-manager-css' );	
		
	}
}

add_action('admin_enqueue_scripts', 'fm_adminEnqueueScripts', 10, 1);
function fm_adminEnqueueScripts( ) {
	global $plugin_page;
	global $fm_currentVersion;
	
	$isFMPage = strrpos($plugin_page, 'fm-');
	
	if( $isFMPage !== false && $isFMPage == 0 ) {
		
		wp_enqueue_script(
			'form-manager-js',
			plugins_url( '/js/scripts.js', __FILE__ ),
			array( 'scriptaculous' )
			);	
		
		wp_localize_script(
			'form-manager-js', 
			'fm_I18n', 
			array(
				'save_with_deleted_items' => 
					__("There may be (data) associated with the form item(s) you removed.  Are you sure you want to save?", 'wordpress-form-manager'),
				'unsaved_changes' => 
					__("Any unsaved changes will be lost. Are you sure?", 'wordpress-form-manager'),
				'click_here_to_download' => 
					__("Click here to download", 'wordpress-form-manager'),
				'there_are_no_files' => 
					__("There are no files to download", 'wordpress-form-manager'),
				'unable_to_create_zip' => 
					__("Unable to create .ZIP file", 'wordpress-form-manager'),
				'move_button' => 
					__("Move", 'wordpress-form-manager'),
				'delete_button' => 
					__("Delete", 'wordpress-form-manager'),
				'enter_items_separated_by_commas' => 
					__("Enter items separated by commas", 'wordpress-form-manager'),
				'hide_button' => 
					__("Hide", 'wordpress-form-manager'),
				'show_button' => 
					__("Show", 'wordpress-form-manager'),
				'add_test' => 
					__("Add Test", 'wordpress-form-manager'),
				'add_item' => 
					__("Add Item", 'wordpress-form-manager'),
				'applies_to' => 
					__("Applies to", 'wordpress-form-manager'),
				'and_connective' => 
					__("AND", 'wordpress-form-manager'),
				'or_connective' => 
					__("OR", 'wordpress-form-manager'),
				'choose_a_rule_type' => 
					__("(Choose a rule type)",'wordpress-form-manager'),
				'only_show_elements_if' => 
					__("Only show elements if...", 'wordpress-form-manager'),
				'show_elements_if' => 
					__("Show elements if...", 'wordpress-form-manager'),
				'hide_elements_if' => 
					__("Hide elements if...", 'wordpress-form-manager'),
				'only_require_elements_if' =>				
					__("Only require elements if...", 'wordpress-form-manager'),
				'require_elements_if' => 
					__("Require elements if", 'wordpress-form-manager'),
				'do_not_require_elements_if' => 				
					__("Do not require elements if", 'wordpress-form-manager'),
				'always' =>
					__("Always", 'wordpress-form-manager'),
				'never' =>
					__("Never", 'wordpress-form-manager'),
				'empty_test' => 
					__("...", 'wordpress-form-manager'),
				'equals' => 
					__("equals", 'wordpress-form-manager'),
				'does_not_equal' => 
					__("does not equal", 'wordpress-form-manager'),
				'is_less_than' => 
					__("is less than", 'wordpress-form-manager'),
				'is_greater_than' => 
					__("is greater than",'wordpress-form-manager'),
				'is_lt_or_equal_to' => 
					__("is less than or equal to", 'wordpress-form-manager'),
				'is_gt_or_equal_to' => 
					__("is greater than or equal to", 'wordpress-form-manager'),
				'is_empty' => 
					__("is empty", 'wordpress-form-manager'),
				'is_not_empty' => 
					__("is not empty", 'wordpress-form-manager'),
				'is_checked' => 
					__("is checked", 'wordpress-form-manager'),
				'is_not_checked' => 
					__("is not checked", 'wordpress-form-manager')
				)
			);
	}
}

add_action( 'init', 'fm_userInit' );
function fm_userInit() {
	global $fm_currentVersion;
	global $fm_forceReinstall;
	
	//check if there was a stealth update, or if none of the database tables were found
	$ver = get_option( 'fm-version' );	
	if ( $ver != $fm_currentVersion || $fm_forceReinstall === true ) {
		fm_install();
	}
	
	////////////////////////////////////////////////////////////
	
	$fm_oldIncludePath = get_include_path();
	set_include_path( dirname( __FILE__ ) . '/' );

	include 'settings.php';
	
	set_include_path( $fm_oldIncludePath );

	////////////////////////////////////////////////////////////

	fm_init_members_integration();
	
	wp_enqueue_script(
		'form-manager-js-user',
		plugins_url( '/js/userscripts.js', __FILE__ )
		);
	wp_localize_script(
		'form-manager-js-user',
		'fm_user_I18n',
		array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
		);
	
	if ( get_option('fm-disable-css') != "YES" ){
		wp_register_style( 'form-manager-css', plugins_url( '/css/style.css', __FILE__ ) );
		wp_enqueue_style( 'form-manager-css' );
	}
	
	////////////////////////////////////////////////////////////
	
	fm_handleFormSubmission();
}

function fm_handleFormSubmission(){
	global $fmdb;
	global $fm_globals;
	
	// process a submission if there was one
	if ( isset($_POST['fm_id']) ){
		$formInfo = $fmdb->getForm($_POST['fm_id']);
		$formInfo['behaviors'] = fm_helper_parseBehaviors($formInfo['behaviors']);
		
		$fm_globals['form_info'][$_POST['fm_id']] = $formInfo;
		
		$postData = fm_processPost( $formInfo );
		if($postData !== false)
			$fm_globals['post_data'][$_POST['fm_id']] = $postData;
	}
}

/**************************************************************/
/******* ADMIN PAGES ******************************************/

add_action( 'admin_menu', 'fm_setupAdminMenu' );
function fm_setupAdminMenu() {
	global $fmdb;
	
	$pages[] = add_object_page(
		__("Forms", 'wordpress-form-manager'), 
		__("Forms", 'wordpress-form-manager'),
		apply_filters( 'fm_main_capability', 'manage_options' ), 
		'fm-admin-main', 
		'fm_showMainPage',
		plugins_url( '/mce_plugins/formmanager.png', __FILE__ )
		);
		
	$pages[] = add_submenu_page(
		'fm-admin-main',
		__("Edit", 'wordpress-form-manager'),
		__("Edit", 'wordpress-form-manager'),
		apply_filters( 'fm_main_capability', 'manage_options' ),
		'fm-edit-form',
		'fm_showEditPage'
		);
		
	$pages[] = add_submenu_page(
		'fm-admin-main',
		__("Settings", 'wordpress-form-manager'),
		__("Settings", 'wordpress-form-manager'),
		apply_filters( 'fm_settings_capability', 'manage_options' ),
		'fm-global-settings',
		'fm_showSettingsPage'
		);
		
	$pages[] = add_submenu_page(
		'fm-admin-main',
		__("Advanced Settings", 'wordpress-form-manager'),
		__("Advanced Settings", 'wordpress-form-manager'),
		apply_filters( 'fm_settings_advanced_capability', 'manage_options' ),
		'fm-global-settings-advanced',
		'fm_showSettingsAdvancedPage'
		);
	
	foreach ( $pages as $page ) {
		add_action( 'admin_head-' . $page, 'fm_adminHeadPluginOnly' );
	}
	
	$pluginName = plugin_basename( __FILE__ );
	add_filter( 'plugin_action_links_' . $pluginName, 'fm_pluginActions' );
}

function fm_pluginActions( $links ) { 
	$settings_link = 
		'<a href="' . get_admin_url( null, 'admin.php' ) . "?page=fm-global-settings".'">' .
		__('Settings', 'wordpress-form-manager') . '</a>';
	array_unshift( $links, $settings_link );
	
	return $links;
}	

add_action( 'admin_head', 'fm_adminHead' );
function fm_adminHead() {
	global $submenu;	
	global $fmdb;
	
	$toUnset = array( 'fm-edit-form' );
	
	if ( isset( $submenu[ 'fm-admin-main' ] ) && is_array( $submenu[ 'fm-admin-main' ] ) ) {		
		foreach ( $submenu[ 'fm-admin-main' ] as $index => $submenuItem ) {
			if ( in_array( $submenuItem[ 2 ], $toUnset, true ) ) {
				unset( $submenu[ 'fm-admin-main' ][ $index ] );	
			}
		}
	}
}

//only show this stuff when viewing a plugin page, since some of it is messy
function fm_adminHeadPluginOnly() {
	global $fm_controls;
	//show the control scripts
	fm_showControlScripts();
	foreach ( $fm_controls as $control ) {
		$control->showScripts();
	}
}

function fm_showEditPage()				{ 	include 'pages/editform.php'; }
function fm_showEditAdvancedPage()		{	include 'pages/editformadv.php'; }
function fm_showDataPage()				{	include 'pages/formdata.php'; }
function fm_showMainPage()				{	include 'pages/main.php'; }
function fm_showSettingsPage()			{	include 'pages/editsettings.php'; }
function fm_showSettingsAdvancedPage()	{	include 'pages/editsettingsadv.php'; }

function fm_showSubmissionDataTopLevel(){	
	global $submenu;
	echo '<pre>'.print_r($submenu,true).'</pre>';
}

// capabilities

function fm_init_members_integration() {
	global $fm_MEMBERS_EXISTS;
	
	if ( class_exists( 'Members_Load' ) ) {
		$fm_MEMBERS_EXISTS = true;
		
		add_filter( 'fm_main_capability', 			'fm_main_capability');
		add_filter( 'fm_forms_capability', 			'fm_forms_capability');
		add_filter( 'fm_forms_advanced_capability', 'fm_forms_advanced_capability');
		add_filter( 'fm_data_capability', 			'fm_data_capability');
		add_filter( 'fm_settings_capability', 		'fm_settings_capability');
		add_filter( 'fm_settings_advanced_capability', 'fm_settings_advanced_capability');
		
		add_filter( 'members_get_capabilities', 	'fm_add_members_capabilities' ); 
	}
}

function fm_main_capability( $cap ) 			{ return 'form_manager_main'; }
function fm_forms_capability( $cap ) 			{ return 'form_manager_forms'; }
function fm_forms_advanced_capability( $cap ) 	{ return 'form_manager_forms_advanced'; }
function fm_data_capability( $cap ) 			{ return 'form_manager_data'; }
function fm_settings_capability( $cap ) 		{ return 'form_manager_settings'; }
function fm_settings_advanced_capability( $cap ) { return 'form_manager_settings_advanced'; }

function fm_add_members_capabilities( $caps ) {
	$caps[] = 'form_manager_main';
	$caps[] = 'form_manager_forms';
	$caps[] = 'form_manager_delete_forms';
	$caps[] = 'form_manager_add_forms';
	$caps[] = 'form_manager_forms_advanced';
	$caps[] = 'form_manager_data';
	$caps[] = 'form_manager_settings';
	$caps[] = 'form_manager_settings_advanced';
	
	$caps[] = 'form_manager_edit_data';
	$caps[] = 'form_manager_data_summary';
	$caps[] = 'form_manager_data_summary_edit';
	$caps[] = 'form_manager_data_options';
	$caps[] = 'form_manager_data_csv';
	$caps[] = 'form_manager_data_csv_all';
	$caps[] = 'form_manager_delete_data';
	$caps[] = 'form_manager_nicknames';
	$caps[] = 'form_manager_conditions';
	
	return $caps;
}

include 'ajax.php';

/**************************************************************/
/******* SHORTCODES *******************************************/

add_shortcode( get_option( 'fm-shortcode' ), 'fm_shortcodeHandler' );
function fm_shortcodeHandler( $atts ) {
	if ( !isset( $atts[ 0 ] ) ) {
		return sprintf(
			__("Form Manager: shortcode must include a form slug.  For example, something like '%s'", 'wordpress-form-manager'), 
			"[form form-1]"
			);
	}
	return fm_doFormBySlug( $atts[ 0 ] );
}

add_shortcode( get_option( 'fm-data-shortcode' ), 'fm_dataShortcodeHandler' );
function fm_dataShortcodeHandler( $atts ) {
	if ( !isset( $atts[ 0 ] ) ) {
		return sprintf(
			__("Form Manager: shortcode must include a form slug.  For example, something like '%s'", 'wordpress-form-manager'), 
			"[formdata form-1]"
			);
	}
	$formSlug = $atts[ 0 ];
	
	$options = $atts;
	
	$showTable = false;
	foreach ( $atts as $att )
		if ( $att == 'table' )
			$showTable = true;
			
	$atts = shortcode_atts(
		array(
			'orderby' => 'timestamp',
			'order' => 'desc',
			'dataperpage' => 30,
			'template' => 'summary',
			'row_height' => 'auto',
			'row_class' => '',
			'col_width' => 'auto',
			'col_class' => '',
			'show' => '',
			'hide' => '',
			'showprivate' => '',
			), 
		$atts
		);
		
	if ( $showTable ) {
		return fm_doDataTableBySlug(
			$formSlug,
			$atts[ 'template' ],
			$atts[ 'orderby' ],
			$atts[ 'order' ],
			$atts[ 'dataperpage' ],
			$options
			);
	} else {
		return fm_doDataListBySlug(
			$formSlug,
			$atts[ 'template' ],
			$atts[ 'orderby' ],
			$atts[ 'order' ],
			$atts[ 'dataperpage' ],
			$options
			);
	}
	
}

/**************************************************************/
/******* HELPERS **********************************************/

//allow scheduling of clearing the temporary directory

function fm_deleteTemporaryFiles( $filename ) {
	$fmTmpDir = get_option( "fm-temp-dir", false );
	if ( $fmTmpDir !== false && trim( $fmTmpDir ) != "" ){
		$dir = dirname( __FILE__ ) . "/" . $fmTmpDir;
		if ( $handle = opendir( $dir ) ) {
			while ( ( $file = readdir( $handle ) ) !== false ) {
				if ( $file != "."
					&& $file != ".."
					&& is_file( $dir . "/" . $file ) ) {
					unlink( $dir . "/" . $file );
				}
			}
			closedir( $handle );		
		}
	}
}
add_action( 'fm_delete_temporary_file', 'fm_deleteTemporaryFiles' );

/**************************************************************/

include 'api.php';

/* ANDREA : include php for create TinyMCE Button */
if( get_option( 'fm-enable-mce-button' ) == "YES" ) {
	include 'tinymce.php';
}

/**************************************************************/
/******* SUBMISSION ACTIONS ***********************************/

add_action( 'fm_form_submission', 'fm_extraSubmissionActions' );
function fm_extraSubmissionActions( $info ){
	$formInfo = $info['form'];
	$postData = $info['data'];

	//send emails
	fm_helper_sendEmail($formInfo, $postData);
	
	//publish post
	if($formInfo['publish_post'] == 1){				
		fm_helper_publishPost($formInfo, $postData);
	}
}

//set the include path back to whatever it was before:
set_include_path( $fm_oldIncludePath );

?>