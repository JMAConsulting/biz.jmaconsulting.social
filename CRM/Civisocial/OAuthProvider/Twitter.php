<?php
require_once 'CRM/Civisocial/OAuthProvider/OAuth/OAuth.php';

class CRM_Civisocial_OAuthProvider_Twitter extends CRM_Civisocial_OAuthProvider {

  /**
   * Short name (alias) for OAuth provider
   *
   * @var string
   */
  private $alias = "twitter";

  /**
   * Construct Twitter OAuth object
   *
   * @param string $accessToken
   *   Preobtained access token. Makes the OAuth Provider ready
   *   to make requests.
   */
  public function __construct($accessToken = NULL) {
    $this->apiUri = 'https://api.twitter.com/1.1';
    $this->getApiCredentials($this->alias);

    // Twitter, why you no upgrade to OAuth 2.0?
    $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
    $this->consumer = new OAuthConsumer($this->apiKey, $this->apiSecret);

    if ($accessToken && isset($accessToken['oauth_token']) && isset($accessToken['oauth_token_secret'])) {
      $this->token = new OAuthConsumer($accessToken['oauth_token'], $accessToken['oauth_token_secret']);
    }
  }

  /**
   * Authorization URI that user will be redirected to for login
   *
   * @return string|bool
   */
  public function getLoginUri() {
    $tempCredentials = $this->getRequestToken($this->getCallbackUri($this->alias));
    $session = CRM_Core_Session::singleton();
    $session->set('oauth_token', $tempCredentials['oauth_token']);
    $session->set('oauth_token_secret', $tempCredentials['oauth_token_secret']);

    return $this->getAuthorizeURL($tempCredentials['oauth_token']);
  }

  /**
   * Process information returned by OAuth provider after login
   */
  public function handleCallback() {
    parent::handleCallback();

    $session = CRM_Core_Session::singleton();

    // Check if the user denied acccess
    if (isset($_GET['denied'])) {
      $this->redirect(TRUE);
    }

    // Get temporary credentials from the session
    $requestToken = array();
    $requestToken['oauth_token'] = $session->get('oauth_token');
    $requestToken['oauth_token_secret'] = $session->get('oauth_token_secret');

    // If the oauth_token is not what we expect, bail
    if (isset($_REQUEST['oauth_token']) && $requestToken['oauth_token'] !== $_REQUEST['oauth_token']) {
      // Not a valid callback.
      $this->redirect();
    }

    $this->token = new OAuthConsumer($requestToken['oauth_token'], $requestToken['oauth_token_secret']);

    // Request Access Token from twitter
    $accessToken = $this->getAccessToken($_REQUEST['oauth_verifier']);
    unset($accessToken['user_id']);
    unset($accessToken['screen_name']);
    unset($accessToken['x_auth_expires']);

    // Remove no longer needed request tokens
    $session->set('oauth_token', NULL);
    $session->set('oauth_token_secret', NULL);
    //@todo: Can't I UNSET using Session class?

    $this->token = new OAuthConsumer($accessToken['oauth_token'], $accessToken['oauth_token_secret']);

    if ($this->isAuthorized()) {
      $this->saveSocialUser($this->alias, $this->getUserProfile(), $accessToken);
    }
    else {
      // Start over
      CRM_Utils_System::redirect($this->getLoginUri());
    }
  }

  /**
   * Check if the user is logged into Twitter
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
   * Get if the user is connected to OAuth provider and authorized
   *
   * @return bool
   */
  public function isAuthorized() {
    if ($this->token && !empty($this->userProfile)) {
      return TRUE;
    }
    $getParams = array('include_email' => 'true');
    $response = $this->get('account/verify_credentials', $getParams);
    if (200 == $this->httpCode) {
      $this->userProfile = array(
        'id'          => CRM_Utils_Array::value('id', $response),
        'first_name'  => CRM_Utils_Array::value('name', $response),
        'last_name'   => NULL,
        'name'  => CRM_Utils_Array::value('name', $response),
        'gender'      => NULL,
        'locale'      => CRM_Utils_Array::value('lang', $response),
        'email'       => CRM_Utils_Array::value('email', $response),
        'profile_url' => 'https://twitter.com/' . CRM_Utils_Array::value('screen_name', $response),
        'picture_url' => CRM_Utils_Array::value('profile_image_url', $response),
        'followers_count' => CRM_Utils_Array::value('followers_count', $response),
        'friends_count' => CRM_Utils_Array::value('friends_count', $response),
        'favourites_count' => CRM_Utils_Array::value('favourites_count', $response),
      );
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Set Access Token to the current OAuthProvider object to be able to make
   * API requests. Validity of the passed access token should be checked
   * using isAuthorized() method.
   *
   * @param array $accessToken
   */
  public function setAccessToken($accessToken) {
    $this->token = new OAuthConsumer($accessToken['oauth_token'], $accessToken['oauth_token_secret']);
  }

  /**
   * Check if the connected app has certain permission.
   * Requires isAuthorized() have been called first.
   *
   * @param array $permissions
   *   Possible values: read, write, directmessages
   *
   * @return bool
   *   FALSE if one or more permssions have not been granted or
   *   the request failed
   *
   * @todo: A permission string has more than one permissions
   *       eg. read-write has read and write permission
   */
  public function checkPermissions($permissions) {
    $header = $this->getHeader();
    $accessLevel = $header['x_access_level'];
    foreach ($permissions as $permission) {
      if (FALSE === strpos($accessLevel, $permission)) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Get a request_token from Twitter.
   *
   * @param string $oauthCallback
   *   URI that will be redirected to after the user authorizes the app
   *
   * @return array
   *   A key/value array containing oauth_token and oauth_token_secret
   */
  public function getRequestToken($oauthCallback) {
    $params = array();
    $params['oauth_callback'] = $oauthCallback;
    $request = $this->oAuthRequest('https://api.twitter.com/oauth/request_token', 'GET', $params);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * Get the authorize URL
   *
   * @param mixed $requestToken
   *   Request token obtained from Twitter
   * @param bool $silentSignIn
   *   If FALSE the user will see 'Authorize App' screen regardless if they
   *   they have previously authorized the app.
   *
   * @return string
   */
  public function getAuthorizeURL($requestToken, $silentSignIn = TRUE) {
    if (is_array($requestToken)) {
      $requestToken = $requestToken['oauth_token'];
    }
    if ($silentSignIn) {
      return "https://api.twitter.com/oauth/authenticate?oauth_token={$requestToken}";
    }
    else {
      return "https://api.twitter.com/oauth/authorize?oauth_token={$requestToken}";
    }
  }

  /**
   * Exchange request token and secret for an access token and
   * secret, to sign API calls.
   *
   * @param string $oauthVerifier
   *   OAuth Verifier string provided by Twitter
   *
   * @return array
   *   OAuth token and secret
   */
  public function getAccessToken($oauthVerifier) {
    $params = array();
    $params['oauth_verifier'] = $oauthVerifier;
    $request = $this->oAuthRequest('https://api.twitter.com/oauth/access_token', 'GET', $params);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * GET wrapper for HTTP request
   *
   * @param $node
   *   API node
   * @param $getParams
   *   GET parameters
   *
   * @return array
   *   Response to API request
   */
  public function get($node, $getParams = array()) {
    $response = $this->oAuthRequest($node, 'GET', $getParams);
    return json_decode($response, TRUE);
  }

  /**
   * POST wrapper for HTTP request
   *
   * @param string $node
   *   API node
   * @param array $postParams
   *   POST parameters
   * @param array $getParams
   *   To match base class's method declaration
   *
   * @return array
   *   Response to API request
   */
  public function post($node, $postParams = array(), $getParams = array()) {
    $response = $this->oAuthRequest($node, 'POST', $postParams);
    return json_decode($response, TRUE);
  }

  /**
   * DELETE wrapper for oAuthRequest.
   *
   * @param string $node
   *   Twitter REST API node
   * @param array $params
   *   Parameters to REST API
   *
   * @return array
   *   Response from Twitter REST API
   */
  public function delete($node, $params = array()) {
    $response = $this->oAuthRequest($node, 'DELETE', $params);
    return json_decode($response, TRUE);
  }

  /**
   * Format and sign an OAuth / API request
   *
   * @param string $node
   *   Twitter REST API node
   * @param array $params
   *   Parameters to REST API
   *
   * @return array
   *   Response from Twitter REST API
   */
  private function oAuthRequest($node, $method, $params) {
    if (strrpos($node, 'https://') !== 0 && strrpos($node, 'http://') !== 0) {
      $url = "{$this->apiUri}/{$node}.json";
    }
    else {
      $url = $node;
    }
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $params);
    $request->sign_request($this->sha1_method, $this->consumer, $this->token);
    switch ($method) {
      case 'GET':
        return $this->http($request->to_url(), 'GET');

      default:
        return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
    }
  }

}
