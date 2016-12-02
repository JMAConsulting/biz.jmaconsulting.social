<?php
/**
 * Connects to the organization's Facebook page for social insight and administrative purposes
 */
class CRM_Civisocial_Page_Twitter_Connect extends CRM_Core_Page {

  public function run() {
    if (isset($_GET['continue'])) {
      $this->saveRedirect(rawurldecode(CRM_Utils_Array::value('continue', $_GET)));
    }

    $session = CRM_Core_Session::singleton();
    // Check if Twitter is enabled and credentials are set
    $isEnabled = civicrm_api3('setting', 'getvalue', array('name' => 'enable_twitter'));
    if (!$isEnabled) {
      $session->setStatus(
        ts('To connect Twitter, please enable Twitter and set App Credentials.'),
        ts('Twitter not enabled'),
        ts('error')
      );
      $currentUrl = rawurlencode(CRM_Utils_System::url(ltrim($_SERVER['REQUEST_URI'], '/'), NULL, TRUE, NULL, FALSE));
      $redirectUrl = CRM_Utils_System::url("civicrm/admin/civisocial/appcredentials?continue={$currentUrl}", NULL, TRUE);
      CRM_Utils_System::redirect($redirectUrl);
    }

    $currentUrl = CRM_Utils_System::url('civicrm/admin/civisocial/network/connect/twitter', NULL, TRUE);
    $twitter = new CRM_Civisocial_OAuthProvider_Twitter();

    $accessToken = $session->get('twitter_access_token');
    if ($accessToken) {
      $twitter->setAccessToken($accessToken);
      if (!$twitter->isAuthorized()) {
        $this->getAccessToken();
      }
    }
    else {
      $this->getAccessToken();
    }

    // User is logged in and authorized. Check if s/he has granted
    // write permisison.
    if (!$twitter->checkPermissions(array('write'))) {
      $session->setStatus(ts('The app doesn\'t have WRITE permission. Please reconfigure your app to allow write access and try again.'), ts('Couldn\'t connect Twitter'), 'error');
      $this->redirect();
    }

    $userProfile = $twitter->getUserProfile();
    $twitterId = $userProfile['id'];
    $session->set('twitter_id', $twitterId);
    $this->redirect();
  }

  /**
   * @param  string $redirectUrl
   *   URL to be redirected to after connecting to facebook page.
   */
  private function saveRedirect($redirectUrl) {
    $session = CRM_Core_Session::singleton();
    $session->set('connecttwitter_redirect', $redirectUrl);
  }

  /**
   * Redirect to saved URL if any
   */
  private function redirect() {
    $session = CRM_Core_Session::singleton();
    $redirectUrl = $session->get('connecttwitter_redirect');
    if ($redirectUrl) {
      $session->set('connecttwitter_redirect', NULL);
    }
    else {
      $redirectUrl = CRM_Utils_System::url('', NULL, TRUE);
    }
    CRM_Utils_System::redirect($redirectUrl);
  }


  private function getAccessToken() {
    $twitter = new CRM_Civisocial_OAuthProvider_Twitter();
    $currentUrl = CRM_Utils_System::url('civicrm/admin/civisocial/network/connect/twitter', NULL, TRUE);
    $twitter->saveRedirect($currentUrl);
    $twitter->setSkipLogin(TRUE);
    CRM_Utils_System::redirect($twitter->getLoginUri());
  }

}
