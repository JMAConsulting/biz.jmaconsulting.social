<?php

require_once 'civisocial.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function civisocial_civicrm_config(&$config) {
  _civisocial_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * git s * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function civisocial_civicrm_xmlMenu(&$files) {
  _civisocial_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function civisocial_civicrm_install() {
  _civisocial_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function civisocial_civicrm_uninstall() {
  _civisocial_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function civisocial_civicrm_enable() {
  _civisocial_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function civisocial_civicrm_disable() {
  _civisocial_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function civisocial_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _civisocial_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function civisocial_civicrm_managed(&$entities) {
  _civisocial_civix_civicrm_managed($entities);
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
function civisocial_civicrm_caseTypes(&$caseTypes) {
  _civisocial_civix_civicrm_caseTypes($caseTypes);
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
function civisocial_civicrm_angularModules(&$angularModules) {
  _civisocial_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function civisocial_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _civisocial_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Include the settings page in civicrm navigation menu.
 */
function civisocial_civicrm_navigationMenu(&$params) {
  $maxID = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_navigation") + 300;
  $administerMenuID = CRM_Core_DAO::getFieldValue('CRM_Core_BAO_Navigation', 'Administer', 'id', 'name');

  $params[] = array(
    'attributes' => array(
      'label'      => 'Social',
      'name'       => 'Social',
      'url'        => NULL,
      'permission' => NULL,
      'operator'   => NULL,
      'separator'  => NULL,
      'parentID'   => NULL,
      'navID'      => 0,
      'active'     => 1,
    ),
    'child' => array(
      '1' => array(
        'attributes' => array(
          'label'      => 'Dashboard',
          'name'       => 'Dashboard',
          'url'        => 'civicrm/civisocial/dashboard',
          'permission' => 'access CiviReport',
          'operator'   => NULL,
          'separator'  => 1,
          'parentID'   => 0,
          'navID'      => 1,
          'active'     => 1,
        ),
      ),
      '2' => array(
        'attributes' => array(
          'label'      => 'Facebook',
          'name'       => 'Facebook',
          'url'        => 'civicrm/civisocial/dashboard/facebook',
          'permission' => 'access CiviReport',
          'operator'   => NULL,
          'separator'  => NULL,
          'parentID'   => 0,
          'navID'      => 1,
          'active'     => 1,
        ),
      ),
      '3' => array(
        'attributes' => array(
          'label'      => 'Twitter',
          'name'       => 'Twitter',
          'url'        => 'civicrm/civisocial/dashboard/twitter',
          'permission' => 'access CiviReport',
          'operator'   => NULL,
          'separator'  => NULL,
          'parentID'   => 0,
          'navID'      => 1,
          'active'     => 1,
        ),
      ),
    ),
  );

  $params[$administerMenuID]['child'][$maxID + 1] = array(
    'attributes' => array(
      'label' => 'CiviSocial',
      'name' => 'CiviSocial',
      'url' => NULL,
      'permission' => 'administer CiviReport',
      'operator' => NULL,
      'separator' => NULL,
      'parentID' => $administerMenuID,
      'navID' => $maxID + 1,
      'active' => 1,
    ),
    'child' => array(
      '1' => array(
        'attributes' => array(
          // @todo: Better name?
          'label' => 'App Credentials',
          'name' => 'App Credentials',
          'url' => 'civicrm/admin/civisocial/appcredentials',
          'permission' => 'administer CiviReport',
          'operator' => NULL,
          'separator' => NULL,
          'parentID' => $maxID + 1,
          'navID' => 1,
          'active' => 1,
        ),
      ),
      '2' => array(
        'attributes' => array(
          // @todo: Better name?
          'label' => 'Social Networks',
          'name' => 'Social Newtorks',
          'url' => 'civicrm/admin/civisocial/networks',
          'permission' => 'administer CiviReport',
          'operator' => NULL,
          'separator' => NULL,
          'parentID' => $maxID + 1,
          'navID' => 2,
          'active' => 1,
        ),
      ),
    ),
  );
}

function civisocial_civicrm_buildForm($formName, &$form) {
  $session = CRM_Core_Session::singleton();
  $currentUrl = rawurlencode(CRM_Utils_System::url(ltrim($_SERVER['REQUEST_URI'], '/'), NULL, TRUE, NULL, FALSE));
  $fbEventEnabled = civicrm_api3('Setting', 'getvalue', array('name' => 'integrate_facebook_events'));

  if ('CRM_Event_Form_ManageEvent_EventInfo' == $formName) {
    if ($fbEventEnabled) {
      // Check if connected to facebook
      $fbAccessToken = $session->get('facebook_access_token');
      if ($fbAccessToken) {
        $facebook = new CRM_Civisocial_OAuthProvider_Facebook($fbAccessToken);
        if ($facebook->isAuthorized()) {
          $form->assign('fbConnected', TRUE);

          // Add facebook event field to the form.
          $form->add('text', 'facebook_event_url', ts('Facebook Event URL'));

          if (CRM_Core_Action::UPDATE == $form->getAction()) {
            // Load the saved value of the facebook event field
            $params = array(
              'event_id' => $form->get('id'),
            );

            $defaults = array();
            CRM_Civisocial_BAO_FacebookEvent::retrieve($params, $defaults);
            $eventUrl = "https://www.facebook.com/events/{$defaults['facebook_event_id']}/";
            if (!empty($defaults)) {
              $form->setDefaults(array('facebook_event_url' => $eventUrl));
            }
          }
        }
        else {
          $form->assign('fbConnected', FALSE);
        }
      }
      else {
        $form->assign('fbConnected', FALSE);
      }

      CRM_Core_Region::instance('page-body')->add(array(
        'template' => 'OAuthProvider/Facebook/FacebookEventURLField.tpl',
      ));
      CRM_Core_Resources::singleton()->addScriptFile('org.civicrm.civisocial', 'templates/res/js/facebook-event.js');

    }

    $form->assign('currentUrl', $currentUrl);
    $form->assign('fbEventEnabled', ($fbEventEnabled) ? TRUE : FALSE);
  }
  elseif ('CRM_Event_Form_Registration_Confirm' == $formName) {
    if ($fbEventEnabled) {
      $facebook = new CRM_Civisocial_OAuthProvider_Facebook();
      if ($facebook->isLoggedIn()) {
        // Check if the Facebook user is authorized
        $facebook->setAccessToken($session->get('facebook_access_token'));
        if ($facebook->isAuthorized()) {
          // Check if the facebook event map exists
          // $form->get('eventId') didn't work.
          $eventId = $form->_eventId;
          $params = array(
            'event_id' => $form->get('id'),
          );
          $defaults = array();
          CRM_Civisocial_BAO_FacebookEvent::retrieve($params, $defaults);

          if (!empty($defaults)) {
            $form->add('checkbox', 'facebook_rsvp_event', ts('RSVP on Facebook'));
            CRM_Core_Region::instance('page-body')->add(array(
              'template' => 'OAuthProvider/Facebook/RegistrationConfirm.tpl',
            ));
          }
        }
      }
    }
  }
  elseif ('CRM_Event_Form_Registration_ThankYou' == $formName) {
    $rsvpSet = $session->get('facbeook_rsvp_set');
    if ($fbEventEnabled && $rsvpSet) {
      $facebook = new CRM_Civisocial_OAuthProvider_Facebook();
      if ($facebook->isLoggedIn()) {
        $facebook->setAccessToken($session->get('facebook_access_token'));
        if ($facebook->isAuthorized()) {
          // Check if the facebook event map exists
          // $form->get('eventId') didn't work.
          $eventId = $form->_eventId;
          $params = array(
            'event_id' => $eventId,
          );
          $defaults = array();
          CRM_Civisocial_BAO_FacebookEvent::retrieve($params, $defaults);
          if (!empty($defaults)) {
            // Facebook event map exists
            // Get facebook event information
            $facebookEventId = $defaults['facebook_event_id'];
            $facebookEvent = $facebook->get($facebookEventId);
            if ($facebookEvent) {
              $smarty = CRM_Core_Smarty::singleton();
              $smarty->assign('facebook_event_name', $facebookEvent['name']);
              $smarty->assign('facebook_event_url', "https://www.facebook.com/{$facebookEventId}/");
              CRM_Core_Region::instance('page-body')->add(array(
                'template' => 'OAuthProvider/Facebook/RegistrationThankYou.tpl',
              ));
              $session->set('facebook_rsvp_set', NULL);
            }
          }
        }
      }
    }
  }

  $loggedInUserId = $session->get('userID');
  if (!$loggedInUserId) {
    autofillForm($formName, $form);
  }
}

/**
 * Validate Facbeook Event Id field.
 */
function civisocial_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  $session = CRM_Core_Session::singleton();
  if ('CRM_Event_Form_ManageEvent_EventInfo' == $formName) {
    // Server side validation
    if (isset($form->_submitValues['facebook_event_url']) && !empty($form->_submitValues['facebook_event_url'])) {
      // Adding Facebook event requires user to be logged in to Facebook
      // or connected to Facebook page
      $fbAccessToken = $session->get('facebook_access_token');
      if ($fbAccessToken) {
        $facebook = new CRM_Civisocial_OAuthProvider_Facebook($fbAccessToken);
        if ($facebook->isAuthorized()) {
          // Check if the reocord for the given event already exists.
          $fbEventUrl = $form->_submitValues['facebook_event_url'];
          $matches = array();
          preg_match('#^(?:https?://)?(?:www\.)?facebook\.com/events/(\d+)/?$#', $fbEventUrl, $matches);
          if (empty($matches)) {
            $errors['facebook_event_url'] = ts('Please enter a valid Facebook event URL');
          }
          else {
            // Check if the event exists
            $matches = array();
            preg_match('#(?:https?://)?(?:www\.)?facebook\.com/events/(\d+)/?#', $fbEventUrl, $matches);
            $fbEventId = $matches[1];
            $response = $facebook->get($fbEventId);
            if (!$response || !array_key_exists('start_time', $response)) {
              $errors['facebook_event_url'] = ts('The event either doesn\'t exist or is not set to public.');
            }
          }
        }
        else {
          $session->setStatus(ts('Please login with Facebook with to integrate Facebook event.'), ts('Not logged in with Facebook'), 'error');
          $errors['facebook_event_url'] = '';
        }
      }
      else {
        $session->setStatus(ts('Please login with Facebook with to integrate Facebook event.'), ts('Not logged in with Facebook'), 'error');
        $errors['facebook_event_url'] = '';
      }
    }
  }
}

function civisocial_civicrm_preProcess($formName, &$form) {
  if ('CRM_Event_Form_Registration_ThankYou' == $formName) {
    // Check if user chose to RSVP on Facebook
    $session = CRM_Core_Session::singleton();
    $rsvpEvent = $session->get('facebook_rsvp_event');
    if ($rsvpEvent) {
      $eventId = $form->get('id');
      $qfKey = $form->get('qfKey');
      $thankyouUrl = rawurlencode(CRM_Utils_System::url("civicrm/event/register?_qf_ThankYou_display=true&qfKey={$qfKey}", NULL, TRUE));
      $redirectUrl = CRM_Utils_System::url("civicrm/civisocial/event/rsvpfacebookevent?event_id={$eventId}&thankyou_url={$thankyouUrl}", NULL, TRUE);

      $session->set('facebook_rsvp_event', NULL);
      CRM_Utils_System::redirect($redirectUrl);
    }
  }
}

function civisocial_civicrm_postProcess($formName, &$form) {
  if ('CRM_Event_Form_ManageEvent_EventInfo' == $formName) {
    // Save Facebook Event ID to the database
    if (isset($form->_submitValues['facebook_event_url']) && !empty($form->_submitValues['facebook_event_url'])) {
      // Check if the reocord for the given event already exists.
      $fbEventUrl = $form->_submitValues['facebook_event_url'];
      $matches = array();
      preg_match('#(?:https?://)?(?:www\.)?facebook\.com/events/(\d+)/?#', $fbEventUrl, $matches);
      $fbEventId = $matches[1];

      $params = array(
        'event_id' => $form->get('id'),
      );
      $defaults = array();
      CRM_Civisocial_BAO_FacebookEvent::retrieve($params, $defaults);

      if (!empty($defaults)) {
        // Record already exists
        $params['id'] = $defaults['id'];
      }
      $params['facebook_event_id'] = $fbEventId;
      CRM_Civisocial_BAO_FacebookEvent::create($params);
    }
  }
  elseif ('CRM_Event_Form_Registration_Confirm' == $formName) {
    // Check if user chose to RSVP on Facebook
    if (isset($form->_submitValues['facebook_rsvp_event'])) {
      $session = CRM_Core_Session::singleton();
      $session->set('facebook_rsvp_event', TRUE);
    }
  }
}

/**
 * Autofill public forms if already logged in. Include social buttons
 * otherwise.
 */
function autofillForm($formName, &$form) {
  // Don't include social buttons on Admin/Settings forms
  // Admin page filters
  $ignorePatterns = array(
    '/Form.*Settings/',
    '/Admin.*Form/',
    '/Form.*Search/',
    '/Contact.*Form/',
    '/Activity.*Form/',
    '/Group_Form/',
    '/Contribute.*Form(?!.*Contribution_Main)/',
    '/Event.*Form(?!.*Registration_Register)/',
    '/Member.*Form/',
    '/Campaign.*Form(?!.*Petition_Signature)/',
    '/Custom_Form/',
    '/Case_Form/',
    '/Grant_Form/',
    '/PCP_Form/',
    '/Price_Form/',
    '/UF_Form_Field/',
  );

  foreach ($ignorePatterns as $pattern) {
    if (preg_match($pattern, $formName)) {
      return;
    }
  }

  $session = CRM_Core_Session::singleton();
  $smarty = CRM_Core_Smarty::singleton();

  CRM_Core_Resources::singleton()->addStyleFile('org.civicrm.civisocial', 'templates/res/css/civisocial.css', 0, 'html-header');

  $currentUrl = rawurlencode(CRM_Utils_System::url(ltrim($_SERVER['REQUEST_URI'], '/'), NULL, TRUE, NULL, FALSE));
  $smarty->assign('currentUrl', $currentUrl);

  $oap = new CRM_Civisocial_OAuthProvider();
  $oAuthProvider = $oap->isLoggedIn();
  if ($oAuthProvider) {
    // User is connected to some social network
    $token = $session->get("{$oAuthProvider}_access_token");
    $className = "CRM_Civisocial_OAuthProvider_" . ucwords($oAuthProvider);
    $oap = new $className($token);

    // Check if the user is still authorized
    if ($oap->isAuthorized()) {
      $oAuthUser = $oap->getUserProfile();
      $smarty->assign("oAuthProvider", $oAuthProvider);
      $smarty->assign("name", $oAuthUser['name']);
      $smarty->assign("profileUrl", $oAuthUser['profile_url']);
      $smarty->assign("pictureUrl", $oAuthUser['picture_url']);

      CRM_Core_Region::instance('page-header')->add(array(
        'template' => "LoggedIn.tpl",
      ));
      CRM_Core_Region::instance('page-body')->add(array(
        'template' => "LoggedIn.tpl",
      ));

      // Populate fields
      $defaults = array();
      $formFields = array(
        'first_name',
        'last_name',
        'email',
      );
      $elements = array_keys($form->_elementIndex);
      foreach ($formFields as $formField) {
        $matches = preg_grep("/{$formField}/", $elements);
        foreach ($matches as $elementName) {
          $defaults[$elementName] = $oAuthUser[$formField];
        }
      }

      $form->setDefaults($defaults);
      return;
    }
    else {
      // User is not authorized because access token expired or
      // the user revoked permissions to the app
      // Logout so that user can login again
      $oap->logout();
    }
  }

  // User is not connected to any network
  CRM_Core_Region::instance('page-header')->add(array(
    'template' => "SocialButtons.tpl",
  ));
  CRM_Core_Region::instance('page-body')->add(array(
    'template' => "SocialButtons.tpl",
  ));
}
