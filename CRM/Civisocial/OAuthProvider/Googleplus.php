<?php
class CRM_Civisocial_OAuthProvider_Googleplus extends CRM_Civisocial_OAuthProvider {

  /**
   * Short name (alias) for OAuth provider
   *
   * @var string
   */
  private $alias = 'googleplus';

  /**
   * Construct Google OAuth object
   *
   * @param string $accessToken
   *   Preobtained access token. Makes the OAuth Provider ready
   *   to make requests.
   */
  public function __construct($accessToken = NULL) {
    $this->apiUri = 'https://www.googleapis.com/oauth2/v3';
    $this->getApiCredentials($this->alias);
    $this->token = $accessToken;
  }

  /**
   * Authorization URI that user will be redirected to for login
   *
   * @param array $permissions
   *   Permissions to be requested
   *
   * @return string | bool
   */
  public function getLoginUri($permissions = array()) {
    $uri = 'https://accounts.google.com/o/oauth2/auth';

    $params = array(
      'response_type' => 'code',
      'client_id' => $this->apiKey,
      'redirect_uri' => $this->getCallbackUri($this->alias),
    );
    if (empty($permissions)) {
      // Google OAuth doesn't allow you to choose which permisions
      // to allow. So, extra permissions are not requested.
      $params['scope'] = implode(' ', $this->getBasicPermissions());
    }
    else {
      $params['scope'] = implode(' ', $permissions);
    }

    // URL decode because Google wants space intact in scope parameter
    return urldecode($uri . "?" . http_build_query($params));
  }

  /**
   * Minimum permissions required to use the login
   *
   * @return array
   */
  public function getBasicPermissions() {
    return array(
      'https://www.googleapis.com/auth/plus.login',
      'https://www.googleapis.com/auth/plus.me',
      'https://www.googleapis.com/auth/userinfo.profile',
      'https://www.googleapis.com/auth/userinfo.email',
    );
  }

  /**
   * Extra recommended permissions
   *
   * @return array
   *
   * @todo: Create an interface to ask these permissions or do we force
   *   users to grant all access in the beginning.
   */
  public function getExtraPermissions() {
    return array(
      'https://www.googleapis.com/auth/plus.stream.write',
    );
  }

  /**
   * Process information returned by OAuth provider after login
   */
  public function handleCallback() {
    parent::handleCallback();

    $session = CRM_Core_Session::singleton();

    // Check if the user denied acccess
    if (isset($_GET['error']) && $_GET['error'] = 'access_denied') {
      $this->redirect(TRUE);
    }

    // Google sends a code to the callback url, this is further used to acquire
    // access token from Google, which is needed to get all the data from Google
    if (!isset($_GET['code'])) {
      exit("Invalid request.");
    }

    // Make an API request to obtain Access Token
    // POST params
    $params = array(
      'client_id' => $this->apiKey,
      'client_secret' => $this->apiSecret,
      'code' => CRM_Utils_Array::value('code', $_GET),
      'redirect_uri' => $this->getCallbackUri($this->alias),
      'grant_type' => 'authorization_code',
    );

    $response = $this->post('token', $params);
    $accessToken = CRM_Utils_Array::value('access_token', $response);
    $this->token = $accessToken;

    // @todo: Get long-lived token

    // Authentication is successful. Fetch user profile
    if ($this->isAuthorized()) {
      $this->saveSocialUser($this->alias, $this->getuserProfile(), $accessToken);
    }
    else {
      // Start over
      CRM_Utils_System::redirect($this->getLoginUri());
    }
  }

  /**
   * Check if the user is logged into Google Plus
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
   * Check if the user is connected to Google and authorized.
   * It can also be used to validate access tokens after setting one.
   *
   * @return bool
   */
  public function isAuthorized() {
    if ($this->token && !empty($this->userProfile)) {
      return TRUE;
    }
    $response = $this->get('userinfo');
    if (!$response) {
      return FALSE;
    }
    $this->userProfile = array(
      'id'          => CRM_Utils_Array::value('sub', $response),
      'first_name'  => CRM_Utils_Array::value('given_name', $response),
      'last_name'   => CRM_Utils_Array::value('family_name', $response),
      'name'   => CRM_Utils_Array::value('name', $response),
      'gender'      => CRM_Utils_Array::value('gender', $response),
      'locale'      => CRM_Utils_Array::value('locale', $response),
      'email'       => CRM_Utils_Array::value('email', $response),
      'profile_url' => CRM_Utils_Array::value('profile', $response),
      'picture_url' => CRM_Utils_Array::value('picture', $response),
    );
    return TRUE;
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
    $getParams['alt'] = 'json';
    if ($this->token) {
      $getParams['access_token'] = $this->token;
    }
    $responseJson = parent::http($url, $method, $postParams, $getParams);
    $response = json_decode($responseJson, TRUE);
    if (isset($response['error'])) {
      if ($response['error'] == 'invalid_token' || $response['error'] == 'invalid_request') {
        // Invalid access token
        // @todo: Log error
        return FALSE;
      }
      else {
        // Non-access token related error.
        exit($response['error'] . '<br/>' . $response['error_description']);
      }
    }
    else {
      return $response;
    }
  }

}
