<?php

require_once 'genderselfidentify.civix.php';

/**
 * Implements hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function genderselfidentify_civicrm_config(&$config) {
  _genderselfidentify_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function genderselfidentify_civicrm_xmlMenu(&$files) {
  _genderselfidentify_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function genderselfidentify_civicrm_install() {
  _genderselfidentify_civix_civicrm_install();
  _genderselfidentify_add_other_option();
}

/**
 * Implements hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function genderselfidentify_civicrm_uninstall() {
  _genderselfidentify_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function genderselfidentify_civicrm_enable() {
  _genderselfidentify_civix_civicrm_enable();
  _genderselfidentify_add_other_option();
}

/**
 * Implements hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function genderselfidentify_civicrm_disable() {
  _genderselfidentify_civix_civicrm_disable();
  civicrm_api3('OptionValue', 'create', array(
    'id' => CRM_Genderselfidentify_BAO_Gender::otherOption('id'),
    'is_reserved' => 0,
  ));
}

/**
 * Implements hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function genderselfidentify_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _genderselfidentify_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function genderselfidentify_civicrm_managed(&$entities) {
  _genderselfidentify_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function genderselfidentify_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _genderselfidentify_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_apiWrappers
 * 
 * @param array $wrappers
 * @param array $apiRequest
 */
function genderselfidentify_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  if (strtolower($apiRequest['entity']) == 'contact') {
    $wrappers[] = new CRM_Genderselfidentify_ContactAPIWrapper();
  }
}

/**
 * Implements hook_civicrm_buildForm
 * 
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function genderselfidentify_civicrm_buildForm($formName, &$form) {
  if (in_array($formName, array('CRM_Contact_Form_Contact', 'CRM_Contact_Form_Inline_Demographics', 'CRM_Profile_Form_Edit'))
  && $form->elementExists('gender_id')) {
    $form->removeElement('gender_id');
    $form->addElement('text', 'gender_id', ts('Gender'));
    if (!empty($form->_contactId)) {
      $form->setDefaults(array(
        'gender_id' => CRM_Genderselfidentify_BAO_Gender::get($form->_contactId),
      ));
    }
    // Hide custom field from contact edit screen since it is not editable
    if ($formName == 'CRM_Contact_Form_Contact') {
      CRM_Core_Resources::singleton()
        ->addStyle('#Genderselfidentify.crm-custom-accordion {display: none;}', 99, 'html-header');
    }
  }
}

/**
 * Implements hook_civicrm_pre
 * 
 * @param string $op
 * @param string $objectName
 * @param int|null $id
 * @param array $params
 */
function genderselfidentify_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName == 'Individual' && in_array($op, array('create', 'edit'))) {
    // $params['version'] indicates this is an api request, which we've already handled with api_v3_GenderselfidentifyAPIWrapper
    if (isset($params['gender_id']) && empty($params['version'])) {
      $input = trim($params['gender_id']);
      $params['gender_id'] = CRM_Genderselfidentify_BAO_Gender::match($input);

      // Can't just set `$params['custom_x'] = $input` because that would be too easy
      // For contact create
      $params['custom_' . CRM_Genderselfidentify_BAO_Gender::getCustomFieldId() . '_-1'] = $input;
      // For contact inline-edit
      $params += array('custom' => array());
      CRM_Core_BAO_CustomField::formatCustomField(CRM_Genderselfidentify_BAO_Gender::getCustomFieldId(), $params['custom'],
        $input, 'Individual', NULL, $id, FALSE, FALSE, TRUE
      );
    }
  }
}

/**
 * Implements hook_civicrm_pageRun
 * 
 * @param CRM_Core_Page $page
 */
function genderselfidentify_civicrm_pageRun(&$page) {
  $pageClass = get_class($page);

  // For contact summary view
  if (in_array($pageClass, array('CRM_Contact_Page_View_Summary', 'CRM_Contact_Page_Inline_Demographics'))) {
    $cid = $page->get_template_vars('id');
    if ($cid) {
      $page->assign('gender_display', htmlspecialchars(CRM_Genderselfidentify_BAO_Gender::get($cid)));
    }
    // Hide custom field from contact summary since its value is incorporated in the demographics pane
    CRM_Core_Resources::singleton()
      ->addStyle('.customFieldGroup.Genderselfidentify {display: none;}', 99, 'html-header');
  }

  // For profile listings
  elseif ($pageClass == 'CRM_Profile_Page_Listings') {
    $genderRow = NULL;
    $columnHeaders = $page->get_template_vars('columnHeaders');
    if ($columnHeaders) {
      foreach ($columnHeaders as $num => $col) {
        if (CRM_Utils_Array::value('field_name', $col) === 'gender_id') {
          $genderRow = $num;
        }
      }
    }
    if ($genderRow) {
      $rows = $page->get_template_vars('rows');
      if ($rows) {
        $other = CRM_Genderselfidentify_BAO_Gender::otherOption('label');
        foreach ($rows as &$row) {
          if ($row[$genderRow] == $other) {
            // Dammit, no cid in row, have to parse it from the view link in the last column
            preg_match('#[&?;]id=(\d+)#', $row[count($row)-1], $matches);
            if (!empty($matches[1])) {
              $row[$genderRow] = htmlspecialchars(CRM_Genderselfidentify_BAO_Gender::get($matches[1]));
            }
          }
        }
        $page->assign('rows', $rows);
      }
    }
  }

  // For profile view
  elseif (in_array($pageClass, array('CRM_Profile_Page_View', 'CRM_Profile_Page_Dynamic'))) {
    $profileFields = $page->get_template_vars('profileFields');
    $row = $page->get_template_vars('row');
    foreach ($profileFields as $key => &$field) {
      if ($key == 'gender_id') {
        $row[$field['label']] = $field['value'] = htmlspecialchars(CRM_Genderselfidentify_BAO_Gender::get($page->get_template_vars('cid')));
        $page->assign('row', $row);
        $page->assign('profileFields', $profileFields);
        break;
      }
    }
  }
}

/**
 * Implements hook_civicrm_searchColumns
 * 
 * @param string $objectName
 * @param array $headers
 * @param array $rows
 * @param $selector
 */
function genderselfidentify_civicrm_searchColumns($objectName, &$headers, &$rows, &$selector) {
  if (strtolower($objectName) == 'contact') {
    $other = CRM_Genderselfidentify_BAO_Gender::otherOption('label');
    foreach ($rows as &$row) {
      if (isset($row['gender_id']) && $row['gender_id'] == $other && !empty($row['contact_id'])) {
        $row['gender_id'] = htmlspecialchars(CRM_Genderselfidentify_BAO_Gender::get($row['contact_id']));
      }
    }
  }
}

/**
 * Add "Other" gender option if it doesn't exist
 * Ensure it is enabled and reserved if it already exists
 *
 * @throws \CiviCRM_API3_Exception
 */
function _genderselfidentify_add_other_option() {
  $options = civicrm_api3('OptionValue', 'get', array('option_group_id' => 'gender'));
  $maxValue = 1;
  foreach ($options['values'] as $lastOption) {
    if ($lastOption['name'] === 'Other') {
      // Make sure it is enabled and reserved
      if (empty($lastOption['is_active']) || empty($lastOption['is_reserved'])) {
        civicrm_api3('OptionValue', 'create', array(
          'id' => $lastOption['id'],
          'is_active' => 1,
          'is_reserved' => 1,
        ));
      }
      return;
    }
    if ($lastOption['value'] > $maxValue) {
      $maxValue = $lastOption['value'];
    }
  }
  // We're still here, so "Other" option needs to be added
  civicrm_api3('OptionValue', 'create', array(
    'option_group_id' => 'gender',
    'name' => 'Other',
    'label' => ts('Other'),
    'value' => $maxValue + 1,
    'weight' => $lastOption['weight'] + 1,
    'is_active' => 1,
    'is_reserved' => 1,
  ));
}