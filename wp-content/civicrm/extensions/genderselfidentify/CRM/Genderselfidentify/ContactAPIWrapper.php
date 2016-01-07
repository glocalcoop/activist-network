<?php
class CRM_Genderselfidentify_ContactAPIWrapper implements API_Wrapper {
  /**
   * @inheritdoc
   */
  public function fromApiInput($apiRequest) {
    $params =& $apiRequest['params'];
    if ($apiRequest['action'] == 'create') {
      if (isset($params['gender_id'])) {
        $customGenderField = 'custom_' . CRM_Genderselfidentify_BAO_Gender::getCustomFieldId();
        if (!trim($params['gender_id'])) {
          $params[$customGenderField] = 'null';
        }
        else {
          $params[$customGenderField] = trim($params['gender_id']);
          $params['gender_id'] = CRM_Genderselfidentify_BAO_Gender::match($params['gender_id']);
          // Set to "Other"
          if ($params['gender_id'] === NULL) {
            $params['gender_id'] = CRM_Genderselfidentify_BAO_Gender::otherOption();
          }
        }
      }
    }
    // If gender is specified in return params, we need to fetch the custom field as well.
    elseif ($apiRequest['action'] == 'get') {
      // Old-school syntax
      if (!empty($params['return.gender_id']) || !empty($params['return.gender'])) {
        $params['return.gender_id'] = 1;
        $params['return.custom_' . CRM_Genderselfidentify_BAO_Gender::getCustomFieldId()] = 1;
      }
      if (!empty($params['return'])) {
        // Unfortunately the api accepts this param as an array or a string
        $return = is_string($params['return']) ? explode(',', str_replace(' ,', ',', $params['return'])) : $params['return'];
        if (in_array('gender', $return) && !in_array('gender_id', $return)) {
          $return[] = 'gender_id';
        }
        if (in_array('gender_id', $return)) {
          $customGenderField = 'custom_' . CRM_Genderselfidentify_BAO_Gender::getCustomFieldId();
          if (!in_array($customGenderField, $return)) {
            $return[] = $customGenderField;
          }
        }
        if (is_string($params['return'])) {
          $return = implode(',', $return);
        }
        $params['return'] = $return;
      }
    }
    return $apiRequest;
  }

  /**
   * @inheritdoc
   */
  public function toApiOutput($apiRequest, $result) {
    if ($apiRequest['action'] == 'get' && !empty($result['values'])) {
      foreach ($result['values'] as &$contact) {
        $this->fixContactGender($contact);
      }
    }
    return $result;
  }

  /**
   * Sets the "gender" field on a contact to the option label if it is a standard option,
   * or the contents of the custom field if it is "Other"
   *
   * @param array $contact
   */
  private function fixContactGender(&$contact) {
    $customGenderField = 'custom_' . CRM_Genderselfidentify_BAO_Gender::getCustomFieldId();
    $other = CRM_Genderselfidentify_BAO_Gender::otherOption();
    if (array_key_exists('gender_id', $contact) && array_key_exists($customGenderField, $contact)) {
      $contact['gender'] = !empty($contact['gender']) && ($contact['gender_id'] != $other || !strlen($contact[$customGenderField])) ? $contact['gender'] : $contact[$customGenderField];
    }
    elseif (!empty($contact['gender_id']) && $contact['gender_id'] == $other) {
      $contact['gender'] = CRM_Genderselfidentify_BAO_Gender::get($contact['id']);
    }
  }
}