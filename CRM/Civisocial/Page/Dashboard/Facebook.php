<?php

class CRM_Civisocial_Page_Dashboard_Facebook extends CRM_Core_Page {

  public function run() {
    // @todo: This is redundant code. FIX THIS. Perhaps by making a LoginHelper
    // class

    $session = CRM_Core_Session::singleton();
    $this->assign('currentUrl', rawurlencode(CRM_Utils_System::url(ltrim($_SERVER['REQUEST_URI'], '/'), NULL, TRUE, NULL, FALSE)));

    // Check if facebook page is connected
    $pageId = $session->get('facebook_page_id');
    $fbAccessToken = $session->get('facebook_page_access_token');
    if ($pageId && $fbAccessToken) {
      // Connected to page
      $facebook = new CRM_Civisocial_OAuthProvider_Facebook($fbAccessToken);
      // Check if token is still valid
      $pageInfo = $facebook->get("{$pageId}?fields=name,picture,fan_count,new_like_count");
      if ($pageInfo) {
        // Token valid
        $this->assign('facebookPageConnected', TRUE);
        $this->assign('facebookPageName', $pageInfo['name']);
        $this->assign('facebookPageUrl', "https://www.facebook.com/{$pageId}/");
        $this->assign('facebookPagePicture', $pageInfo['picture']['data']['url']);
        $this->assign('facebookFanCount', $pageInfo['fan_count']);
        $this->assign('facebookNewLikeCount', $pageInfo['new_like_count']);
      }
    }

    CRM_Core_Resources::singleton()->addStyleFile('org.civicrm.civisocial', 'templates/res/css/civisocial.css', 0, 'html-header');
    CRM_Core_Resources::singleton()->addStyleFile('org.civicrm.civisocial', 'templates/res/css/dashboard.css', 0, 'html-header');
    CRM_Core_Resources::singleton()->addScriptFile('org.civicrm.civisocial', 'templates/res/js/dashboard.js', 0);
    CRM_Core_Resources::singleton()->addScriptFile('org.civicrm.civisocial', 'templates/res/js/dashboard-facebook.js', 1);
    parent::run();
  }

}
