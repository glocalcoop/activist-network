<?php

class CRM_Genderselfidentify_BAO_Gender {

  /**
   * @return int
   * @throws \CiviCRM_API3_Exception
   */
  public static function getCustomFieldId() {
    static $id;
    if (!$id) {
      $result = civicrm_api3('CustomField', 'getsingle', array(
        'return' => 'id',
        'custom_group_id' => 'Genderselfidentify',
        'name' => 'Gender_Other',
      ));
      $id = $result['id'];
    }
    return $id;
  }

  /**
   * @param string $ret
   * @return int
   * @throws \CiviCRM_API3_Exception
   */
  public static function otherOption($ret = 'value') {
    static $option;
    if (!$option) {
      $option = civicrm_api3('OptionValue', 'getsingle', array('option_group_id' => 'gender', 'name' => 'Other'));
    }
    return $option[$ret];
  }

  /**
   * Returns string representation of contact's gender
   *
   * @param int $contactId
   * @return string
   * @throws \CiviCRM_API3_Exception
   */
  public static function get($contactId) {
    if (!$contactId) {
      return '';
    }
    $contact = civicrm_api3('Contact', 'getsingle', array(
      'return' => array('gender_id'),
      'id' => $contactId,
    ));
    // Our api wrapper will have done all the work, just return it
    return $contact['gender'];
  }

  /**
   * @param string $input
   * @return int
   * @throws \CiviCRM_API3_Exception
   */
  public static function match($input) {
    $input = trim($input);
    if ($input) {
      $genderOptions = civicrm_api3('contact', 'getoptions', array('field' => 'gender_id'));
      $genderOptions = $genderOptions['values'];
      if (is_numeric($input) && isset($genderOptions[$input])) {
        return $input;
      }
      foreach ($genderOptions as $key => $label) {
        if (strtolower($input) == substr(strtolower($label), 0, strlen($input))) {
          return $key;
        }
      }
      return CRM_Genderselfidentify_BAO_Gender::otherOption();
    }
    return '';
  }
}