<?php

class CRM_Civisocial_Page_Dashboard extends CRM_Core_Page {

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

    // Check if twitter page is connected
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
        $this->assign('twitterFollowersCount', $twitterInfo['followers_count']);
        $this->assign('twitterFriendsCount', $twitterInfo['friends_count']);
        $this->assign('twitterFavoritesCount', $twitterInfo['favourites_count']);
      }
    }

    CRM_Core_Resources::singleton()->addStyleFile('org.civicrm.civisocial', 'templates/res/css/civisocial.css', 0, 'html-header');
    CRM_Core_Resources::singleton()->addStyleFile('org.civicrm.civisocial', 'templates/res/css/dashboard.css', 0, 'html-header');
    CRM_Core_Resources::singleton()->addScriptFile('org.civicrm.civisocial', 'templates/res/js/dashboard.js');
    parent::run();
  }

}
