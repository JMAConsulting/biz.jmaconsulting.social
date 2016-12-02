<?php
class CRM_Civisocial_BAO_CivisocialUserTest extends CiviUnitTestCase {

  /**
   * Clean up after tests.
   */
  public function tearDown() {
    parent::tearDown();
  }

  /**
   * Test create method (create and update modes).
   */
  public function testCreate() {
    $contactId = $this->individualCreate();

    // Create social user
    $params = array(
      'contact_id' => $contactId,
      'social_user_id' => 123456,
      'access_token' => '7f550a9f4c44173a37664d938f1355f0f92a47a7',
      'oauth_provider' => 'facebook',
      'created_date' => date('YmdHis'),
    );

    $socialUser = CRM_Civisocial_BAO_CivisocialUser::create($params);
    $this->assertEquals($contactId, $socialUser->contact_id, 'Check for contact id  creation.');

    // Update Access token
    $ids = array('id' => $socialUser->id);
    $params['access_token'] = 'aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d';

    $socialUser = CRM_Civisocial_BAO_CivisocialUser::create($params, $ids);

    $this->assertEquals($params['access_token'], $socialUser->access_token, 'Check for Access Token updation.');
  }

  public function testRetrieve() {
    $contactId = $this->individualCreate();

    // Create social user
    $params = array(
      'contact_id' => $contactId,
      'social_user_id' => 123456,
      'access_token' => '7f550a9f4c44173a37664d938f1355f0f92a47a7',
      'oauth_provider' => 'facebook',
      'created_date' => date('YmdHis'),
    );

    $socialUser = CRM_Civisocial_BAO_CivisocialUser::create($params);

    $params2 = array(
      'social_user_id' => $socialUser->social_user_id,
      'oauth_provider_id' => 'facebook',
    );

    $defaults = array();
    CRM_Civisocial_BAO_CivisocialUser::retrieve($params2, $defaults);
    foreach ($params as $key => $value) {
      if ($key != 'created_date') {
        $this->assertEquals($value, $defaults[$key], 'Check for CivisocialUser retrieval.');
      }
    }
  }

  /**
   * Test contact create method
   */
  public function testContactCreate() {
    // Test case #1: Email already exists
    $email = substr(md5(rand()), 0, 10) . '@civicrm.org';
    $params = array(
      'contact_type' => 'Individual',
      'email' => $email,
    );
    $result = civicrm_api3('Contact', 'create', $params);
    $contactId = $result['id'];

    $contactId2 = CRM_Civisocial_BAO_CivisocialUser::createContact($params);

    $this->assertEquals($contactId, $contactId2, 'Check contact creation.');

    // Test case #2: Email doesn't exist
    do {
      $email = substr(md5(rand()), 0, 10) . '@civicrm.org';
      $result = civicrm_api3('Contact', 'get', array('email' => 'rajb.dilip@gmail.comm'));
    } while ($result['count'] != 0);

    $params = array(
      'contact_type' => 'Individual',
      'email' => $email,
    );

    $contactId = CRM_Civisocial_BAO_CivisocialUser::createContact($params);
    $this->assertTrue(!is_null($contactId));
  }

  /**
   * Test socialUserExists() method.
   */
  public function testSocialUserExists() {
    $contactId = $this->individualCreate();
    $params = array(
      'contact_id' => $contactId,
      'social_user_id' => 123456,
      'access_token' => '7f550a9f4c44173a37664d938f1355f0f92a47a7',
      'oauth_provider' => 'facebook',
      'created_date' => date('YmdHis'),
    );

    $socialUser = CRM_Civisocial_BAO_CivisocialUser::create($params);
    // socialUserExists() returns contact ID if the user exists
    $contactId2 = CRM_Civisocial_BAO_CivisocialUser::socialUserExists($socialUser->social_user_id, $socialUser->oauth_provider);
    $this->assertEquals($contactId2, $contactId, 'Check socialUserExists().');
  }

}
