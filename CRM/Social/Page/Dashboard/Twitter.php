<?php

class CRM_Social_Page_Dashboard_Twitter extends CRM_Core_Page {

  public function run() {
    // @todo: This is redundant code. FIX THIS. Perhaps by making a LoginHelper
    // class

    $session = CRM_Core_Session::singleton();
    $this->assign('currentUrl', rawurlencode(CRM_Utils_System::url(ltrim($_SERVER['REQUEST_URI'], '/'), NULL, TRUE, NULL, FALSE)));

    $twitterId = $session->get('twitter_id');
    $twitterAccessToken = $session->get('twitter_access_token');
    if ($twitterId && $twitterAccessToken) {
      // Connected to Twitter
      $twitter = new CRM_Social_OAuthProvider_Twitter($twitterAccessToken);
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

    CRM_Core_Resources::singleton()->addStyleFile('biz.jmaconsulting.social', 'templates/res/css/social.css', 0, 'html-header');
    CRM_Core_Resources::singleton()->addStyleFile('biz.jmaconsulting.social', 'templates/res/css/dashboard.css', 0, 'html-header');
    CRM_Core_Resources::singleton()->addScriptFile('biz.jmaconsulting.social', 'templates/res/js/dashboard.js', 0);
    CRM_Core_Resources::singleton()->addScriptFile('biz.jmaconsulting.social', 'templates/res/js/dashboard-twitter.js', 1);
    parent::run();
  }

}
