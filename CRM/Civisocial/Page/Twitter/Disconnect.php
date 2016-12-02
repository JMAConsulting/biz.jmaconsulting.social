<?php
/**
 * Connects to the organization's Facebook page for social insight and administrative purposes
 */
class CRM_Civisocial_Page_Twitter_Disconnect extends CRM_Core_Page {

  public function run() {
    $session = CRM_Core_Session::singleton();
    $oap = new CRM_Civisocial_OAuthProvider();
    $twitterId = $session->get('twitter_id');
    if ($twitterId) {
      // Remove twitter access token only if user is not logged in to twitter
      $oAuthProvider = $oap->isLoggedIn();
      if (!$oAuthProvider || 'twitter' != $oAuthProvider) {
        $session->set('twitter_access_token', NULL);
      }
      $session->set('twitter_id', NULL);
    }

    if (isset($_GET['continue'])) {
      $redirectUrl = rawurldecode(CRM_Utils_Array::value('continue', $_GET));
    }
    else {
      $redirectUrl = CRM_Utils_System::url('', NULL, TRUE);
    }
    CRM_Utils_System::redirect($redirectUrl);
  }

}
