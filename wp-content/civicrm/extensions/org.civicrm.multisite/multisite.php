<?php

require_once 'multisite.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function multisite_civicrm_config(&$config) {
  _multisite_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function multisite_civicrm_xmlMenu(&$files) {
  _multisite_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function multisite_civicrm_install() {
  return _multisite_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function multisite_civicrm_uninstall() {
  return _multisite_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function multisite_civicrm_enable() {
  return _multisite_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function multisite_civicrm_disable() {
  return _multisite_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function multisite_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _multisite_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function multisite_civicrm_managed(&$entities) {
  return _multisite_civix_civicrm_managed($entities);
}

/**
 *  Implementation of hook_civicrm_validate_form
 *  Make parents optional for administrators when
 *  organization id is set
 *
 * Validation of forms. This hook was introduced in v4.2
 * @param string $formName - Name of the form being validated, you will typically switch off this value.
 * @param array  $fields - Array of name value pairs for all 'POST'ed form values
 * @param array  $files - Array of file properties as sent by PHP POST protocol
 * @param object   $form - Reference to the civicrm form object. This is useful if you want to retrieve any values that we've constructed in the form
 * @param array   $errors - Reference to the errors array. All errors will be added to this array
 * Returns true if form validates successfully, otherwise array with input field names as keys and error message strings as values
*/
function multisite_civicrm_validateForm( $formName, &$fields, &$files, &$form, &$errors ){
  if((!isset($fields['organization_id']) && !empty($form->_entityId))) {
    try{
      //$fields['group_organization']
      $fields['group_organization'] = civicrm_api3('group_organization', 'getvalue', array('group_id' => $form->_entityId, 'return' => 'id'));
      $form->setElementError('parents', NULL);
    }
    catch (Exception $e) {
    }
  }
  if(!empty($fields['organization_id']) || !empty($fields['group_organization'])) {
    $form->setElementError('parents', NULL);
  }
}


/**
 * Implemtation of hook civicrm_pre
 * @param string $op
 * @param string $objectName
 * @param integer $id
 * @param array $params
 */
function multisite_civicrm_pre( $op, $objectName, $id, &$params ){
  // allow setting of org instead of parent
  if($objectName == 'Group'){
    if(empty($params['parents'])){
      // if parents left empty we need to fill organization_id (if not filled)
      // and set no parent. We don't want Civi doing this on our behalf
      // as we assume admin users can make sensible choices on nesting
      // & the default should be the org link
      $params['no_parent'] = 1;
    }
    if(empty($params['organization_id'])){
      $params['organization_id'] = _multisite_get_domain_organization(TRUE);
    }
  }
}


/**
 *  * Implementation of hook_civicrm_post
 *
 * Current implemtation assumes shared user table for all sites -
 * a more sophisticated version will be able to cope with a combination of shared user tables
 * and separate user tables
 *
 * @param string $op
 * @param string $objectName
 * @param integer $objectId
 * @param object $objectRef
 */
function multisite_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($op == 'edit' && $objectName == 'UFMatch') {
    static $updating = FALSE;
    if ($updating) {
      return; // prevent recursion
    }
    $updating = TRUE;
    $ufs = civicrm_api('uf_match', 'get', array(
      'version' => 3,
      'contact_id' => $objectRef->contact_id,
      'uf_id' => $objectRef->uf_id,
      'id' => array(
        '!=' => $objectRef->id
      )
    ));
    foreach ($ufs['values'] as $ufMatch) {
      civicrm_api('UFMatch', 'create', array(
        'version' => 3,
        'id' => $ufMatch['id'],
        'uf_name' => $objectRef->uf_name
      ));
    }
  }
}
/**
 * Implements ACLGroup hook
 * aclGroup function returns a list of groups which are either children of the
 * domain group id or connected to the same organisation as the domain Group ID
 * @param string $type
 * @param integer $contactID
 * @param string $tableName
 * @param array $allGroups
 * @param array $currentGroups
 */
function multisite_civicrm_aclGroup($type, $contactID, $tableName, &$allGroups, &$currentGroups) {
  // only process saved search
  if ($tableName != 'civicrm_saved_search') {
    return;
  }
  $groupID = _multisite_get_domain_group();
  if(!$groupID){
    return;
  }
  if(!CRM_Core_Permission::check('list all groups in domain') && !_multisite_add_permissions($type)){
    return;
  }
  $currentGroups = _multisite_get_all_child_groups($groupID, FALSE);
  $currentGroups = array_merge($currentGroups, _multisite_get_domain_groups($groupID));
  if(!empty($allGroups)) {
    //all groups is empty if we really mean all groups but if a filter like 'is_disabled' is already applied
    // it is populated, ajax calls from Manage Groups will leave empty but calls from New Mailing pass in a filtered list
    $currentGroups = array_intersect($currentGroups, array_flip($allGroups));
  }
}

/**
 *
 * @param string $type
 * @param array $tables tables to be included in query
 * @param array $whereTables tables required for where portion of query
 * @param integer $contactID contact for whom where clause is being composed
 * @param string $where Where clause The completed clause will look like
 *   (multisiteGroupTable.group_id IN ("1,2,3,4,5") AND multisiteGroupTable.status IN ('Added') AND contact_a.is_deleted = 0)
 *   where the child groups are groups the contact is potentially a member of
 *
 */
function multisite_civicrm_aclWhereClause($type, &$tables, &$whereTables, &$contactID, &$where) {
  if (! $contactID) {
    return;
  }
  if(!_multisite_add_permissions($type)){
    return;
  }
  $groupID = _multisite_get_domain_group();
  if(!$groupID){
    return;
  }
  $childOrgs = _multisite_get_all_child_groups($groupID);
  if (!empty($childOrgs)) {
    $groupTable = 'civicrm_group_contact';
    $groupTableAlias = 'multisiteGroupTable';
    $tables[$groupTableAlias] = $whereTables[$groupTableAlias] = "
      LEFT JOIN {$groupTable} $groupTableAlias ON contact_a.id = {$groupTableAlias}.contact_id
    ";
    $deletedContactClause = CRM_Core_Permission::check('access deleted contacts') ? '' : 'AND contact_a.is_deleted = 0';
    $where = "(multisiteGroupTable.group_id IN (" . implode(',', $childOrgs) . ") AND {$groupTableAlias}.status IN ('Added') $deletedContactClause)";
  }
}

function multisite_civicrm_tabs(&$tabs, $contactID ) {
  $enabled = civicrm_api3('setting', 'getvalue', array('group' => 'Multi Site Preferences', 'name' => 'multisite_custom_tabs_restricted'));
  if(!$enabled) {
    return;
  }
  $tabs_visible = civicrm_api3('setting', 'getvalue', array('group' => 'Multi Site Preferences', 'name' => 'multisite_custom_tabs_enabled'));

  foreach( $tabs as $id => $tab ) {
    if (stristr($tab['id'], 'custom_')) {
     $tab_id = str_replace('custom_', '', $tab['id']);
     if (!in_array($tab_id, $tabs_visible)) {
       unset($tabs[$id]);
     }
   }
 }
}

/**
 * invoke permissions hook
 * note that permissions hook is now permission hook
 * @param array $permissions
 */
function multisite_civicrm_permissions(&$permissions){
  multisite_civicrm_permission($permissions);
}

/**
 * invoke permissions hook
 * @param array $permissions
 */
function multisite_civicrm_permission(&$permissions){
  $prefix = ts('CiviCRM Multisite') . ': ';
  $permissions = $permissions + array(
    'view all contacts in domain' => $prefix . ts('view all contacts in domain'),
    'edit all contacts in domain' => $prefix . ts('edit all contacts in domain'),
    'list all groups in domain' => $prefix . ts('list all groups in domain'),
  );
}

/**
 * Implementation of hook_civicrm_config
 */
function multisite_civicrm_alterSettingsFolders(&$metaDataFolders = NULL){
  _multisite_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Get all groups that are children of the parent group
 * (iterate through all levels)
 *
 * @param integer $groupID
 * @param boolean $includeParent
 * @return array:child groups
 */
function _multisite_get_all_child_groups($groupID, $includeParent = TRUE) {
  static $_cache = array();

  if (! array_key_exists($groupID, $_cache)) {
    $childGroups = &CRM_Core_BAO_Cache::getItem('descendant groups for an org', $groupID);

    if (empty($childGroups)) {
      $childGroups = array();

      $query = "
SELECT children
FROM   civicrm_group
WHERE  children IS NOT NULL
AND    id IN ";

      if (! is_array($groupID)) {
        $groupIDs = array(
          $groupID
        );
      }

      while (! empty($groupIDs)) {
        $groupIDString = implode(',', $groupIDs);

        $realQuery = $query . " ( $groupIDString )";
        $dao = CRM_Core_DAO::executeQuery($realQuery);
        $groupIDs = array();
        while ($dao->fetch()) {
          if ($dao->children) {
            $childIDs = explode(',', $dao->children);
            foreach ($childIDs as $childID) {
              if (! array_key_exists($childID, $childGroups)) {
                $childGroups[$childID] = 1;
                $groupIDs[] = $childID;
              }
            }
          }
        }
      }

      CRM_Core_BAO_Cache::setItem($childGroups, 'descendant groups for an org', $groupID);
    }
    $_cache[$groupID] = $childGroups;
  }

  if ($includeParent || CRM_Core_Permission::check('administer Multiple Organizations')) {
    return array_keys(array(
      $groupID => 1
    ) + $_cache[$groupID]);
  }
  else {
    return array_keys($_cache[$groupID]);
  }
}
/**
 * Get groups linked to the domain via the group organization
 * being shared with the domain group
 * @return NULL|integer $groupID
 */
function _multisite_get_domain_groups($groupID) {
  $sql = " SELECT o2.group_id as group_id
           FROM civicrm_group_organization o
           INNER JOIN civicrm_group_organization o2 ON o.organization_id = o2.organization_id
           AND o.group_id = $groupID AND o2.group_id <> $groupID
      ";
  $dao = CRM_Core_DAO::executeQuery($sql);
  $groups = array();
  while($dao->fetch()) {
    $groups[] = (int) $dao->group_id;
  }
  return $groups;
}

/**
 *
 * @return NULL|integer $groupID
 */
function _multisite_get_domain_group($permission = 1) {
    $groupID = CRM_Core_BAO_Domain::getGroupId();
    if(empty($groupID) || !is_numeric($groupID)){
      /* domain group not defined - we could let people know but
       * it is acceptable for some domains not to be in the multisite
      * so should probably check enabled before we spring an error
      */
      return NULL;
    }
    // We will check for the possiblility of the acl_enabled setting being deliberately set to 0
    if($permission){
     $aclsEnabled = civicrm_api('setting', 'getvalue', array(
      'version' => 3,
      'name' => 'multisite_acl_enabled',
      'group' => 'Multi Site Preferences')
     );
     if(is_numeric($aclsEnabled) && !$aclsEnabled){
       return NULL;
     }
    }

    return $groupID;
  }
/**
 * get organization of domain group
 */
  function _multisite_get_domain_organization($permission = True){
    $groupID =  _multisite_get_domain_group($permission);
    if(empty($groupID)){
      return FALSE;
    }
    return civicrm_api('group_organization', 'getvalue', array(
        'version' => 3,
        'group_id' => $groupID,
        'return' => 'organization_id',
      )
    );
  }

  /**
   * Should we be adding ACLs in this instance. If we don't add them the user
   * will not be able to see anything. We check if the install has the permissions
   * hook implemented correctly & if so only allow view & edit based on those.
   *
   * Otherwise all users get these permissions added (4.2 vs 4.3 / other CMS issues)
   *
   * @param integer $type type of operation
   */
  function _multisite_add_permissions($type){
    $hookclass = 'CRM_Utils_Hook';
    if(!method_exists($hookclass, 'permissions') && !method_exists($hookclass, 'permission')){
      // ie. unpatched 4.2 so we can't check for extra declared permissions
      // & default to applying this to all
      return TRUE;
    }
    if($type == 'group'){
      // @fixme only handling we have for this at the moment
      return TRUE;
    }
    // extra check to make sure that hook is properly implemented
    // if not we won't check for it. NB view all contacts in domain is enough checking
    $declaredPermissions = CRM_Core_Permission::basicPermissions();
    if(!array_key_exists('view all contacts in domain', $declaredPermissions)){
      return TRUE;
    }

    if(CRM_ACL_BAO_ACL::matchType($type, 'View') &&
      CRM_Core_Permission::check('view all contacts in domain')) {
      return TRUE;
    }

    if(CRM_ACL_BAO_ACL::matchType($type, 'Edit') &&
      CRM_Core_Permission::check('edit all contacts in domain')) {
      return TRUE;
    }
    return FALSE;
  }
  /**
   * Implements buildForm hook
   * http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
   * @param string $formName
   * @param object $form reference to the form object
   */
  function multisite_civicrm_buildForm( $formName, &$form ){

    if($formName == 'CRM_Group_Form_Edit'){
      _multisite_alter_form_crm_group_form_edit($formName, $form);
    }
  }
  /**
  * Called from buildForm hook
  * http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
  * @param string $formName
  * @param object $form reference to the form object
  */
  function _multisite_alter_form_crm_group_form_edit($formName, &$form){
    if(isset($form->_defaultValues['parents'])){
      $parentOrgs = civicrm_api('group_organization', 'get', array(
        'version' => 3,
        'group_id' => $form->_defaultValues['parents'],
        'return' => 'organization_id',
        'sequential' => 1,
        )
      );
      if($parentOrgs['count'] ==1){
        $groupOrg = $parentOrgs['values'][0]['organization_id'];
        $defaults['organization_id'] = $groupOrg;
        $defaults['organization'] = civicrm_api('contact', 'getvalue', array(
          'version' => 3,
          'id' => $groupOrg,
          'return' => 'display_name',
        ));
        $defaults['parents'] = "";
        $form->setDefaults($defaults);
      }
    }
    unset($form->_required[2]);
    unset($form->_rules['parents']);
  }

  /**
   * Implements hook_civicrm_alterAPIPermissions
   * http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterAPIPermissions
   * @param string $entity
   * @param string $action
   * @param array &$params
   * @param array &$permisions
   */
  function multisite_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
    $domain_id = CRM_Core_Config::domainID();
    if ($domain_id !== 1) {
      $entities = array('address', 'email', 'phone', 'website', 'im', 'loc_block',
        'entity_tag', 'note', 'relationship', 'group_contact',
      );

      foreach($entities as $entity) {
        $permissions[$entity]['default'] = array(
          'access CiviCRM',
          'edit all contacts in domain',
        );
        
        $permissions[$entity]['get'] = array(
          'access CiviCRM',
          'view all contacts in domain',
        );
      }

      $permissions['relationship']['delete'] = array(
        'access CiviCRM',
        'edit all contacts in domain',
      );

      $permissions['contact']['update'] = array(
        'access CiviCRM',
        'edit all contacts in domain',
      );
    }
  }
