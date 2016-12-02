<?php
/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Civisocial_Form_Settings_AppCredentials extends CRM_Core_Form {
  private $_settingFilter = array('group' => 'appcredentials');

  private $_submittedValues = array();
  private $_settings = array();

  /**
   * Preprocess the form.
   */
  public function preProcess() {
    CRM_Utils_System::setTitle(ts('App Credentials'));

    if (isset($_GET['continue'])) {
      $session = CRM_Core_Session::singleton();
      $session->set('appcredentials_redirect', CRM_Utils_Array::value('continue', $_GET));
    }

    $submitValues =& $this->_submitValues;
    if (!empty($submitValues)) {
      $aliases = array(
        'facebook',
        'googleplus',
        'twitter',
      );
      foreach ($aliases as $alias) {
        $submitValues["{$alias}_api_key"] = trim($submitValues["{$alias}_api_key"]);
        $submitValues["{$alias}_api_secret"] = trim($submitValues["{$alias}_api_secret"]);
      }
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
    parent::buildQuickForm();
  }

  // public function validate() {
  //   parent::validate();
  //   $submitValues =& $this->_submitValues;
  //   if (!empty($submitValues)) {
  //     $aliases = array(
  //       'facebook',
  //       'googleplus',
  //       'twitter',
  //     );
  //     foreach ($aliases as $alias) {
  //       if ($submitValues["enable_{$alias}"]) {
  //         if (empty($submitValues["{$alias}_api_key"])) {
  //           $this->_errors["{$alias}_api_key"] = "This is required.";
  //         }
  //         if (empty($submitValues["{$alias}_api_secret"])) {
  //           $this->_errors["{$alias}_api_secret"] = "This is required.";
  //         }
  //       }
  //     }
  //   }
  // }

  /**
   * Process the form submission.
   */
  public function postProcess() {
    $this->_submittedValues = $this->exportValues();
    if ($this->saveSettings()) {
      $session = CRM_Core_Session::singleton();
      $redirectUrl = $session->get('appcredentials_redirect');
      if ($redirectUrl) {
        $session->set('appcredentials_redirect', NULL);
        CRM_Utils_System::redirect($redirectUrl);
      }
      else {
        CRM_Core_Session::setStatus(ts('App Credentials have been saved.'), ts('Saved'), 'success');
      }
    }
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
