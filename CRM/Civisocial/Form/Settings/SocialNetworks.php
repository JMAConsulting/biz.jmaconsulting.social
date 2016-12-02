<?php
/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Civisocial_Form_Settings_SocialNetworks extends CRM_Core_Form {
  private $_settingFilter = array('group' => 'socialnetworks');

  private $_submittedValues = array();
  private $_settings = array();

  /**
   * Preprocess the form.
   */
  public function preProcess() {
    CRM_Utils_System::setTitle(ts('Social Networks'));
    $session = CRM_Core_Session::singleton();

    $fbPermsDenied = $session->get('facebook_page_perms_denied');
    if ($fbPermsDenied) {
      $session->set('facebook_page_perms_denied', NULL);
      $session->setStatus(ts('Required permissions to connect page were denied.'), ts('Couldn\'t connect to Facebook page'), 'error');
    }
  }

  /**
   * Build the settings form
   */
  public function buildQuickForm() {
    $settings = $this->getFormSettings();
    foreach ($settings as $name => $setting) {
      if (isset($setting['quick_form_type'])) {
        $add = 'add' . $setting['quick_form_type'];
        if ($add == 'addElement') {
          $this->$add($setting['html_type'], $name, ts($setting['title']), CRM_Utils_Array::value('html_attributes', $setting, array()));
        }
        else {
          $this->$add($name, ts($setting['title']));
        }
        $this->assign("{$setting['description']}_description", ts('description'));
      }
    }
    $this->addButtons(array(
            array(
              'type' => 'submit',
              'name' => ts('Save'),
              'isDefault' => TRUE,
            ),
            array(
              'type' => 'cancel',
              'name' => ts('Cancel'),
            ),
          )
        );

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());

    $session = CRM_Core_Session::singleton();
    $currentUrl = rawurlencode(CRM_Utils_System::url('civicrm/admin/civisocial/networks', NULL, TRUE));

    // FACEBOOK PAGE
    $pageId = $session->get('facebook_page_id');
    $fbAccessToken = $session->get('facebook_page_access_token');
    if ($pageId && $fbAccessToken) {
      // Connected to page
      $facebook = new CRM_Civisocial_OAuthProvider_Facebook($fbAccessToken);
      // Check if token is still valid
      $pageInfo = $facebook->get("{$pageId}?fields=name,picture");
      if ($pageInfo) {
        // Token valid
        // exit('valid');
        $this->assign('facebookPageConnected', TRUE);
        $this->assign('facebookPageName', $pageInfo['name']);
        $this->assign('facebookPageUrl', "https://www.facebook.com/{$pageId}/");
        $this->assign('facebookPagePicture', $pageInfo['picture']['data']['url']);
      }
      else {
        // Remove the stored access token. A new token needs to be retrieved.
        $session->set('facebook_page_access_token', NULL);
        $session->set('facebook_page_id', NULL);
      }
    }

    // TWITTER
    $twitterId = $session->get('twitter_id');
    $twitterAccessToken = $session->get('twitter_access_token');
    if ($twitterId && $twitterAccessToken) {
      // Connected to Twitter
      $twitter = new CRM_Civisocial_OAuthProvider_Twitter($twitterAccessToken);
      // Check if token is still valid
      if ($twitter->isAuthorized()) {
        // Token valid
        $twitterInfo = $twitter->getUserProfile();
        $this->assign('twitterConnected', TRUE);
        $this->assign('twitterName', $twitterInfo['name']);
        $this->assign('twitterUrl', $twitterInfo['profile_url']);
        $this->assign('twitterPicture', $twitterInfo['picture_url']);
      }
      else {
        // Remove the stored access token. A new token needs to be retrieved.
        $session->set('twitter_access_token', NULL);
        $session->set('twitter_id', NULL);
      }
    }

    $this->assign('currentUrl', $currentUrl);

    CRM_Core_Resources::singleton()->addStyleFile('org.civicrm.civisocial', 'templates/res/css/civisocial.css', 0, 'html-header');
    CRM_Core_Resources::singleton()->addScriptFile('org.civicrm.civisocial', 'templates/res/js/social-networks-setting.js');
    parent::buildQuickForm();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons". These
    // items don't have labels. We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

  /**
   * Get the settings we are going to allow to be set on this form.
   *
   * @return array
   */
  public function getFormSettings() {
    if (empty($this->_settings)) {
      $settings = civicrm_api3('setting', 'getfields', array('filters' => $this->_settingFilter));
    }
    // $extraSettings = civicrm_api3('setting', 'getfields', array('filters' => array('group' => 'accountsync')));
    // $settings = $settings['values'] + $extraSettings['values'];
    return $settings['values'];
  }

  /**
   * Get the settings we are going to allow to be set on this form.
   *
   * @return array
   */
  public function saveSettings() {
    $settings = $this->getFormSettings();
    $values = array_intersect_key($this->_submittedValues, $settings);
    return (civicrm_api3('setting', 'create', $values));
  }

  /**
   * Set defaults for form.
   *
   * @see CRM_Core_Form::setDefaultValues()
   */
  public function setDefaultValues() {
    $existing = civicrm_api3('setting', 'get', array('return' => array_keys($this->getFormSettings())));
    $defaults = array();
    $domainID = CRM_Core_Config::domainID();
    foreach ($existing['values'][$domainID] as $name => $value) {
      $defaults[$name] = $value;
    }
    return $defaults;
  }

}
