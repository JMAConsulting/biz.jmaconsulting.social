<?php
/**
 * List the facebook pages that user manages
 *
 * @todo : This class can be further optimized. A base class
 *  'CRM_Civisocial_Page_Facebook' * can check if logged and return
 *  Facebook object.
 */
class CRM_Civisocial_Page_Facebook_PageList extends CRM_Core_Page {

  public function run() {
    CRM_Utils_System::setTitle(ts('Select Facebook page'));

    if (isset($_GET['continue'])) {
      $this->saveRedirect(CRM_Utils_Array::value('continue', $_GET));
    }

    $session = CRM_Core_Session::singleton();
    $listRequested = $session->get('facebook_page_list_requested');
    if (!$listRequested) {
      $this->redirect();
    }

    $facebook = new CRM_Civisocial_OAuthProvider_Facebook();
    $accessToken = $session->get('facebook_access_token');
    if ($accessToken) {
      $facebook->setAccessToken($accessToken);
      if (!$facebook->isAuthorized()) {
        $this->redirect();
      }
    }
    else {
      $this->redirect();
    }

    // It is assumed that the required permisisons have been granted.
    // So, permissions are not checked here.
    // Get the list of pages the user manages
    $pageList = $facebook->get('me/accounts?fields=id,name&limit=50');
    if ($pageList) {
      $this->assign('pageList', $pageList['data']);
      $this->assign('postUrl', $this->redirectUrl);
      CRM_Core_Resources::singleton()->addStyleFile('org.civicrm.civisocial', 'templates/res/css/civisocial.css', 0, 'html-header');
    }
    else {
      $this->redirect();
    }
    parent::run();
  }

  /**
   * @param string $redirectUrl
   *   URL to be redirected to after connecting to facebook page.
   */
  private function saveRedirect($redirectUrl) {
    $this->redirectUrl = $redirectUrl;
  }

  /**
   * Redirect to saved URL if any
   */
  private function redirect() {
    if (isset($this->redirectUrl)) {
      $redirectUrl = $this->redirectUrl;
    }
    else {
      $redirectUrl = CRM_Utils_System::url('', NULL, TRUE);
    }
    CRM_Utils_System::redirect($redirectUrl);
  }

}
