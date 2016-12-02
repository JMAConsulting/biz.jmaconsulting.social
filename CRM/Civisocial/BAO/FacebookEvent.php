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
class CRM_Civisocial_BAO_FacebookEvent extends CRM_Civisocial_DAO_FacebookEvent {

  /**
   * Retrieve the facebook event map.
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
    $fbEvent = new CRM_Civisocial_DAO_FacebookEvent();
    $fbEvent->copyValues($params);
    if ($fbEvent->find(TRUE)) {
      CRM_Core_DAO::storeValues($fbEvent, $defaults);
      return $fbEvent;
    }
    return NULL;
  }

  /**
   * Create facebook event map.
   *
   * @param array $params
   */
  public static function create($params) {
    $op = empty($params['id']) ? 'create' : 'edit';
    CRM_Utils_Hook::pre($op, 'FacebookEvent', CRM_Utils_Array::value('id', $params), $params);
    $fbEvent = new CRM_Civisocial_DAO_FacebookEvent();
    $fbEvent->copyValues($params);
    $fbEvent->save();
    CRM_Utils_Hook::post($op, 'FacebookEvent', $fbEvent->id, $fbEvent);
    return $fbEvent;
  }

}
