<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 4.7                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2016                                |
+--------------------------------------------------------------------+
| This file is a part of CiviCRM.                                    |
|                                                                    |
| CiviCRM is free software; you can copy, modify, and distribute it  |
| under the terms of the GNU Affero General Public License           |
| Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
|                                                                    |
| CiviCRM is distributed in the hope that it will be useful, but     |
| WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
| See the GNU Affero General Public License for more details.        |
|                                                                    |
| You should have received a copy of the GNU Affero General Public   |
| License and the CiviCRM Licensing Exception along                  |
| with this program; if not, contact CiviCRM LLC                     |
| at info[AT]civicrm[DOT]org. If you have questions about the        |
| GNU Affero General Public License or the licensing of CiviCRM,     |
| see the CiviCRM license FAQ at http://civicrm.org/licensing        |
+--------------------------------------------------------------------+
 */

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2016
 *
 * Civisocial User BAO class.
 */
class CRM_Civisocial_BAO_CivisocialUser extends CRM_Civisocial_DAO_CivisocialUser {

  /**
   * Retrieve the information about the social user
   *
   * @param array $params
   *   (reference ) an assoc array of name/value pairs.
   * @param array $defaults
   *   (reference ) an assoc array to hold the flattened values.
   *
   * @return array
   *   CRM_Batch_BAO_CivisocialUser object on success, null otherwise
   */
  public static function retrieve(&$params, &$defaults) {
    $civisocialUser = new CRM_Civisocial_DAO_CivisocialUser();
    $civisocialUser->copyValues($params);
    if ($civisocialUser->find(TRUE)) {
      CRM_Core_DAO::storeValues($civisocialUser, $defaults);
      return $civisocialUser;
    }
    return NULL;
  }

  /**
   * Create social user.
   *
   * @param array $params
   */
  public static function create($params) {
    $op = empty($params['id']) ? 'create' : 'edit';
    CRM_Utils_Hook::pre($op, 'CivisocialUser', CRM_Utils_Array::value('id', $params), $params);
    $civisocialUser = new CRM_Civisocial_DAO_CivisocialUser();
    $civisocialUser->copyValues($params);
    $civisocialUser->save();
    CRM_Utils_Hook::post($op, 'CivisocialUser', $civisocialUser->id, $civisocialUser);
    return $civisocialUser;
  }

  /**
   * Create contact
   *
   * @param array $userInfo
   *
   * @return int
   *   Contact ID of created or existing contact
   */
  public static function createContact($userInfo) {
    $email = CRM_Utils_Array::value("email", $userInfo);
    $contacts = civicrm_api3(
      'contact',
      'get',
      array("email" => $email)
    );

    if ($contacts["count"] == 0 || $email == NULL) {
      $result = civicrm_api3('Contact', 'create', $userInfo);
      return $result["id"];
    }
    else {
      $contactId = 0;
      foreach ($contacts["values"] as $key => $value) {
        $contactId = $key;
        // @todo: Update the contact with the new info
      }
      return $contactId;
    }
  }

  /**
   * Check if social media user already exists
   *
   * @param int $socialUserId
   *   ID provided by OAuthProvider. Eg. Facebook ID.
   * @param string $oauthProvider
   *   OAuthProvider alias. Eg. googleplus
   *
   * @return int|bool
   *   Returns contact_id of the social user if the user exits.
   *   FALSE otherwise
   */
  public static function socialUserExists($socialUserId, $oauthProvider) {
    $params = array(
      'social_user_id' => $socialUserId,
      'oauth_provider' => $oauthProvider,
    );
    $defaults = array();
    $result = self::retrieve($params, $defaults);

    if ($result) {
      return $defaults['contact_id'];
    }
    return FALSE;
  }

}
