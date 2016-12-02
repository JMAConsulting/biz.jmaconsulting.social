<?php
/**
 * Connects to the organization's Facebook page for social insight and administrative purposes
 */
class CRM_Civisocial_Page_Facebook_DisconnectPage extends CRM_Core_Page {

  public function run() {
    $session = CRM_Core_Session::singleton();
    $pageId = $session->get('facebook_page_id');
    if ($pageId) {
      // Clear settings and sessions
      $session->set('facebook_page_access_token', NULL);
      $session->set('facebook_page_id', NULL);
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
