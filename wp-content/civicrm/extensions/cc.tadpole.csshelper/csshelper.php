<?php

require_once 'csshelper.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function csshelper_civicrm_config(&$config) {
  _csshelper_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function csshelper_civicrm_xmlMenu(&$files) {
  _csshelper_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function csshelper_civicrm_install() {
  _csshelper_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function csshelper_civicrm_uninstall() {
  _csshelper_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function csshelper_civicrm_enable() {
  _csshelper_civix_civicrm_enable();
  civicrm_api3('Setting', 'create', array('disable_core_css' => 1,));
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function csshelper_civicrm_disable() {
  _csshelper_civix_civicrm_disable();
  civicrm_api3('Setting', 'create', array('disable_core_css' => 0,));
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function csshelper_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _csshelper_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function csshelper_civicrm_managed(&$entities) {
  _csshelper_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function csshelper_civicrm_caseTypes(&$caseTypes) {
  _csshelper_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function csshelper_civicrm_angularModules(&$angularModules) {
_csshelper_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function csshelper_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _csshelper_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function csshelper_civicrm_preProcess($formName, &$form) {

}

*/

/*Enqueue default CiviCRM CSS in admin.  Create a filter to allow themes and other plugins to overrride */
if ( ! function_exists( 'civi_wp' ) ) {
}
else {

  add_action('admin_enqueue_scripts', 'csshelper_register_admin_civicrm_styles');
};

function csshelper_register_admin_civicrm_styles() {
  $tc_civi_css_admin = (plugin_dir_url('civicrm')  . 'civicrm/civicrm/css/civicrm.css');
  $tc_civi_css_admin = apply_filters('tc_civicss_override_admin', $tc_civi_css_admin);
  wp_enqueue_style ('tad_admin_civicrm',  $tc_civi_css_admin );
}

/*Enqueue custom CiviCRM CSS in front end of site.  Create a filter to allow themes and other plugins to overrride */
if ( ! function_exists( 'civi_wp' ) ) {
}
else {
  add_action('wp_print_styles', 'csshelper_register_civicrm_styles', 110);
};

function csshelper_register_civicrm_styles() {
  $tc_ext_url = CRM_Core_Resources::singleton()->getUrl('cc.tadpole.csshelper');
  $tc_civi_css = ( $tc_ext_url  . 'css/civicrm.css') ;
  $tc_civi_css = apply_filters ( 'tc_civicss_override' ,  $tc_civi_css ) ;
  wp_enqueue_style ('tad_civicrm', $tc_civi_css );
}