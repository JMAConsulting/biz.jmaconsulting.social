<?php
/**
 * Sets the RSVP to "Going" on a corresponding Facbeook event.
 */
class CRM_Civisocial_Page_Facebook_RSVPEvent extends CRM_Core_Page {

  public function run() {
    $session = CRM_Core_Session::singleton();
    $oap = new CRM_Civisocial_OAuthProvider();

    if (isset($_GET['thankyou_url'])) {
      $session->set('thankyou_url', rawurldecode(CRM_Utils_Array::value('thankyou_url', $_GET)));
    }

    if (!isset($_GET['event_id']) || !$oap->isLoggedIn() || 'facebook' != $session->get('civisocial_oauth_provider')) {
      $this->redirect();
    }
    $eventId = CRM_Utils_Array::value('event_id', $_GET);

    // Check if the event map exists
    $params = array(
      'event_id' => $eventId,
    );
    $defaults = array();
    CRM_Civisocial_BAO_FacebookEvent::retrieve($params, $defaults);
    if (empty($defaults)) {
      // Event doesn't exist
      $this->redirect();
    }

    // Check if the Facebook user is authorized
    $facebook = new CRM_Civisocial_OAuthProvider_Facebook($session->get('facebook_access_token'));
    if (!$facebook->isAuthorized()) {
      $this->redirect();
    }

    // Check if the facebook user has RSPV permission
    $deniedPermissions = $facebook->checkPermissions(array('rsvp_event'));
    if (!empty($deniedPermissions)) {
      $rsvpRequested = $session->get('rsvp_permission_requested');
      if ($rsvpRequested) {
        // RSVP permission was requested but denied
        $session->set('rsvp_permission_requested', NULL);
        $this->redirect();
      }
      else {
        // Request for permission
        // Redirect back to this page after permission is granted
        $currentUrl = CRM_Utils_System::url("civicrm/civisocial/event/rsvpfacebookevent?event_id={$eventId}", NULL, TRUE);
        $session->set('civisocial_redirect', $currentUrl);
        $session->set('rsvp_permission_requested', TRUE);
        CRM_Utils_System::redirect($facebook->getLoginUri(array('rsvp_event')));
      }
    }

    $session->set('rsvp_permission_requested', NULL);

    $facebookEventId = $defaults['facebook_event_id'];
    if ($facebook->get($facebookEventId)) {
      // Event exists. Set the RSPV to 'Attending
      $response = $facebook->post("{$facebookEventId}/attending");
      if ($response) {
        $session->set('facbeook_rsvp_set', TRUE);
      }
    }

    $this->redirect();
  }

  private function redirect() {
    $session = CRM_Core_Session::singleton();
    $oap = new CRM_Civisocial_OAuthProvider();

    $confirmUrl = $session->get('thankyou_url');
    if ($confirmUrl) {
      $session->set('thankyou_url', NULL);
      CRM_Utils_System::redirect($confirmUrl);
    }
    else {
      $oap->redirect();
    }
  }

}
