<?php
/**
 * Connects to the organization's Facebook page for social insight and administrative purposes
 */
class CRM_Civisocial_Page_Facebook_ConnectPage extends CRM_Core_Page {

  public function run() {
    // @todo: Create a FacebookPage class extending Facebook class
    if (isset($_GET['continue'])) {
      $this->saveRedirect(rawurldecode(CRM_Utils_Array::value('continue', $_GET)));
    }

    $session = CRM_Core_Session::singleton();

    // Check if Facebok app credentials are set
    $isEnabled = civicrm_api3('setting', 'getvalue', array('name' => 'enable_facebook'));
    if (!$isEnabled) {
      $session->setStatus(
        ts('To connect Facebook page, please enable Facebook and set App Credentials.'),
        ts('Facebook not enabled'),
        ts('error')
      );
      $currentUrl = rawurlencode(CRM_Utils_System::url(ltrim($_SERVER['REQUEST_URI'], '/'), NULL, TRUE, NULL, FALSE));
      $redirectUrl = CRM_Utils_System::url("civicrm/admin/civisocial/appcredentials?continue={$currentUrl}", NULL, TRUE);
      CRM_Utils_System::redirect($redirectUrl);
    }

    $facebook = new CRM_Civisocial_OAuthProvider_Facebook();
    $accessToken = $session->get('facebook_access_token');
    if ($accessToken) {
      $facebook->setAccessToken($accessToken);
      if (!$facebook->isAuthorized()) {
        $this->getPermissions();
      }
    }
    else {
      $this->getPermissions();
    }

    // User is logged in and authorized. Check if s/he has granted
    // necessary permissions.
    $deniedPermissions = $facebook->checkPermissions(array('manage_pages', 'publish_pages'));
    if (!empty($deniedPermissions)) {
      $permRequested = $session->get('page_perms_requested');
      if ($permRequested) {
        // Page permissions were requested but denied
        $session->set('page_perms_requested', NULL);
        $session->set('facebook_page_perms_denied', TRUE);
        $this->redirect();
      }
      else {
        // Request for permission
        $session->set('page_perms_requested', TRUE);
        $this->getPermissions($deniedPermissions);
      }
    }

    // All required permissions have been granted
    if (isset($_POST['page_id'])) {
      // All set. Get page access token
      $pageId = CRM_Utils_Array::value('page_id', $_POST);
      $response = $facebook->get("{$pageId}?fields=access_token");
      if ($response && isset($response['access_token'])) {
        // Page access token retrieval successfull
        $accessToken = $response['access_token'];
        $session->set('facebook_page_access_token', $response['access_token']);
        $session->set('facebook_page_id', $pageId);
      }
      $session->set('facebook_page_list_requested', NULL);
      $this->redirect();
    }
    else {
      $session->set('facebook_page_list_requested', TRUE);
      $currentUrl = rawurlencode(CRM_Utils_System::url('civicrm/admin/civisocial/network/connect/facebookpage', NULL, TRUE));
      CRM_Utils_System::redirect(CRM_Utils_System::url("civicrm/admin/civisocial/network/connect/facebookpage/list?continue={$currentUrl}", NULL, TRUE));
    }
  }

  /**
   * @param string $redirectUrl
   *   URL to be redirected to after connecting to facebook page.
   */
  private function saveRedirect($redirectUrl) {
    $session = CRM_Core_Session::singleton();
    $session->set('connectfacebookpage_redirect', $redirectUrl);
  }

  /**
   * Redirect to saved URL if any
   */
  private function redirect() {
    $session = CRM_Core_Session::singleton();
    $redirectUrl = $session->get('connectfacebookpage_redirect');
    if ($redirectUrl) {
      $session->set('connectfacebookpage_redirect', NULL);
    }
    else {
      $redirectUrl = CRM_Utils_System::url('', NULL, TRUE);
    }
    CRM_Utils_System::redirect($redirectUrl);
  }

  /**
   * Get necessary permissions to connect to and manage page
   */
  private function getPermissions($permissions = array()) {
    $facebook = new CRM_Civisocial_OAuthProvider_Facebook();
    $currentUrl = CRM_Utils_System::url('civicrm/admin/civisocial/network/connect/facebookpage', NULL, TRUE);
    $facebook->saveRedirect($currentUrl);
    $facebook->setSkipLogin(TRUE);
    if (empty($permissions)) {
      CRM_Utils_System::redirect($facebook->getLoginUri(array('manage_pages', 'publish_pages')));
    }
    else {
      CRM_Utils_System::redirect($facebook->getLoginUri($permissions));
    }
  }

}
