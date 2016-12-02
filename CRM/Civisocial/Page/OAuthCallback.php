<?php
require_once 'CRM/Core/Page.php';
require_once 'CRM/Civisocial/BAO/CivisocialUser.php';
require_once 'CRM/Civisocial/OAuthProvider/Facebook.php';
require_once 'CRM/Civisocial/OAuthProvider/Googleplus.php';
require_once 'CRM/Civisocial/OAuthProvider/Twitter.php';

class CRM_Civisocial_Page_OAuthCallback extends CRM_Core_Page {

  public function run() {
    $path = CRM_Utils_System::currentPath();
    if (FALSE !== strpos($path, '..')) {
      exit("Fatal Error: the URL can't contain '..'. Please report the issue on the forum at civicrm.org");
    }
    $path = explode('/', $path);

    $OAuthProvider = CRM_Utils_Array::value(3, $path);
    if (!$OAuthProvider) {
      exit("BACKEND ERROR: No OAuth Provider found in request");
    }

    // Check if the OAuth Provider exists and is enabled
    // @todo: this is getting redundant. Maybe create a method in
    //			OAuthProvider class
    $isEnabled = civicrm_api3(
      "setting",
      "getvalue",
      array(
        "group" => "CiviSocial Account Credentials",
        "name" => "enable_{$OAuthProvider}",
      )
    );

    if (!$isEnabled) {
      exit("OAuth Provider either doesn't exist or is not enabled.");
    }

    // @todo: Do we still need to check if the class exists?
    $classname = "CRM_Civisocial_OAuthProvider_" . ucwords($OAuthProvider);
    $oap = new $classname();
    $oap->handleCallback();
  }

}
