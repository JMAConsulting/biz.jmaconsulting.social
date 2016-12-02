<?php

class CRM_Civisocial_OAuthProvider_Facebook extends CRM_Civisocial_OAuthProvider {

  /**
   * Short name (alias) for OAuth provider
   *
   * @var string
   */
  private $alias = 'facebook';

  /**
   * Construct Facebook OAuth object
   *
   * @param string $accessToken
   *   Preobtained access token. Makes the OAuth Provider ready
   *   to make requests.
   */
  public function __construct($accessToken = NULL) {
    $this->apiUri = 'https://graph.facebook.com/v2.6';
    $this->getApiCredentials($this->alias);
    $this->token = $accessToken;
  }

  /**
   * Authorization URI that user will be redirected to for login
   *
   * @param array $permissions
   *   Permissions to be requested
   * @params bool $reRequest
   *   Facebook requires that app specifies if it is rerequest
   *   or it won't show the login dialog
   *
   * @return string | bool
   * @todo Check if requests have been reviewed by Facebook
   */
  public function getLoginUri($permissions = array(), $reRequest = FALSE) {
    $uri = 'https://www.facebook.com/dialog/oauth';
    $params = array(
      'client_id' => $this->apiKey,
      'redirect_uri' => $this->getCallbackUri($this->alias),
    );
    if (empty($permissions)) {
      $params['scope'] = implode(',', array_merge($this->getBasicPermissions(), $this->getExtraPermissions()));
    }
    else {
      $params['scope'] = implode(',', array_merge($this->getBasicPermissions(), $permissions));
    }
    if ($reRequest) {
      $params['auth_type'] = 'rerequest';
    }
    return $uri . "?" . http_build_query($params);
  }

  /**
   * Minimum permissions required to use the login
   */
  public function getBasicPermissions() {
    return array(
      'public_profile',
      'email',
    );
  }

  /**
   * Extra recommended permissions
   * 'rsvp_events' and 'publish_actions' require to be reviewed by
   * Facebook before the app can request it
   */
  public function getExtraPermissions() {
    return array(
      'user_likes',
      'rsvp_event',
      'publish_actions',
    );
  }

  /**
   * Process authentication information returned by OAuth provider after login
   */
  public function handleCallback() {
    parent::handleCallback();

    $session = CRM_Core_Session::singleton();

    // Check if the user denied acccess
    // @todo: Put deny handling in the base class as well
    if (isset($_GET['error']) && $_GET['error'] = 'access_denied') {
      $this->redirect(TRUE);
    }

    // Facebook sends a code to the callback url, this is further used to acquire
    // access token from facebook, which is needed to get all the data from facebook
    if (!isset($_GET['code'])) {
      exit("Invalid request.");
    }

    // Make an API request to obtain Access Token
    // GET params
    $params = array(
      'client_id' => $this->apiKey,
      'client_secret' => $this->apiSecret,
      'code' => CRM_Utils_Array::value('code', $_GET),
      'redirect_uri' => $this->getCallbackUri($this->alias),
    );
    $response = $this->get('oauth/access_token', $params);
    $accessToken = CRM_Utils_Array::value('access_token', $response);

    // Get long-lived access token
    // Long-lived token live upto 60 days.
    $params = array(
      'grant_type' => 'fb_exchange_token',
      'client_id' => $this->apiKey,
      'client_secret' => $this->apiSecret,
      'fb_exchange_token' => $accessToken,
    );
    $response = $this->get('oauth/access_token', $params);
    $accessToken = CRM_Utils_Array::value('access_token', $response);

    // Check if all basic perimissions have been granted
    $this->setAccessToken($accessToken);
    $deniedPermissions = $this->checkPermissions($this->getBasicPermissions());
    if (!empty($deniedPermissions)) {
      CRM_Utils_System::redirect($this->getLoginUri($deniedPermissions, TRUE));
      // @todo: It would be better if we inform first (eg. You need to provide
      //      email to continue) and then provide a link to re-authorize
    }

    // Authentication is successful. Fetch user profile
    if ($this->isAuthorized()) {
      $this->saveSocialUser($this->alias, $this->getUserProfile(), $accessToken);
    }
    else {
      // Start over
      CRM_Utils_System::redirect($this->getLoginUri());
    }
  }

  /**
   * Check if the user is logged into Facebook
   *
   * @return bool
   */
  public function isLoggedIn() {
    $session = CRM_Core_Session::singleton();
    $oAuthProvider = $session->get('civisocial_oauth_provider');
    if ($oAuthProvider && $oAuthProvider == $this->alias) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check if the user is connected to Facebook and authorized.
   * It can also be used to validate access tokens after setting one.
   *
   * @return bool
   */
  public function isAuthorized() {
    if ($this->token && !empty($this->userProfile)) {
      return TRUE;
    }
    $response = $this->get('me?fields=id,first_name,last_name,name,locale,gender,email');
    if (!$response) {
      return FALSE;
    }
    $this->userProfile = array(
      'id'          => CRM_Utils_Array::value('id', $response),
      'first_name'  => CRM_Utils_Array::value('first_name', $response),
      'last_name'   => CRM_Utils_Array::value('last_name', $response),
      'name'   => CRM_Utils_Array::value('name', $response),
      'gender'      => CRM_Utils_Array::value('gender', $response),
      'locale'      => CRM_Utils_Array::value('locale', $response),
      'email'       => CRM_Utils_Array::value('email', $response),
      'profile_url' => "https://www.facebook.com/{$response['id']}",
      'picture_url' => "https://graph.facebook.com/{$response['id']}/picture",
    );
    return TRUE;
  }

  /**
   * Check if all passed permissions have beeen granted
   *
   * @param array $permissions
   *   Permissions to check if they have been granted
   *
   * @return array
   *   An array of permissions that were denied
   */
  public function checkPermissions($permissions = array()) {
    $grantedPermissions = $this->getGrantedPermissions();
    if (count($permissions) > count($grantedPermissions)) {
      return FALSE;
    }
    CRM_Core_Error::debug_var('grantedPermissions', $grantedPermissions);
    return array_diff($permissions, $grantedPermissions);
  }

  /**
   * Get a list of granted permissions
   *
   * @return array | bool
   *   FALSE if authorization fails
   */
  public function getGrantedPermissions() {
    $response = $this->get('me/permissions');
    if ($response) {
      $grantedPermissions = array();
      foreach ($response['data'] as $permission) {
        if ($permission['status'] == 'granted') {
          $grantedPermissions[] = $permission['permission'];
        }
      }
      return $grantedPermissions;
    }
    return FALSE;
  }

  /**
   * Make a HTTP request
   *
   * @param string $url
   *   API request URL
   * @param string $method
   *   HTTP request method
   * @param array $postParams
   *   POST parameters
   * @param array $getParams
   *   GET parameters
   *
   * @return array
   *   Response to API request
   */
  public function http($url, $method, $postParams = array(), $getParams = array()) {
    if ($this->token) {
      $getParams['access_token'] = $this->token;
    }
    $responseJson = parent::http($url, $method, $postParams, $getParams);
    $response = json_decode($responseJson, TRUE);
    if (isset($response['error'])) {
      if ($response['error']['type'] == 'OAuthException') {
        // Invalid access token
        return FALSE;
      }
      elseif ($response['error']['type'] == 'GraphMethodException') {
        // Unsupported get/post request
        return FALSE;
      }
      else {
        // Non-access token related error.
        exit($response['error']['message']);
      }
    }
    else {
      return $response;
    }
  }

}
