<?php
class CRM_Civisocial_BAO_FacebookEvenTest extends CiviUnitTestCase {

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
    $result = civicrm_api3('Event', 'create', array(
      'event_type_id' => "Conference",
      'title' => "Test Event ",
      'start_date' => 20180101,
    ));

    $eventId = $result['id'];

    // Create social user
    $params = array(
      'event_id' => $eventId,
      'facebook_event_id' => 123456,
    );

    $fbEventMap = CRM_Civisocial_BAO_FacebookEvent::create($params);
    $this->assertEquals($eventId, $fbEventMap->event_id, 'Check for Facebook event map creation.');

    // Update Access token
    $ids = array('id' => $fbEventMap->id);
    $params['facebook_event_id'] = 456789;

    $fbEventMap = CRM_Civisocial_BAO_FacebookEvent::create($params, $ids);
    $this->assertEquals($params['facebook_event_id'], $fbEventMap->facebook_event_id, 'Check for Facebook event id updation.');
  }

  public function testRetrieve() {
    $result = civicrm_api3('Event', 'create', array(
      'event_type_id' => "Conference",
      'title' => "Test Event ",
      'start_date' => 20180101,
    ));

    $eventId = $result['id'];

    // Create social user
    $params = array(
      'event_id' => $eventId,
      'facebook_event_id' => 123456,
    );

    $fbEventMap = CRM_Civisocial_BAO_FacebookEvent::create($params);

    // Retrieve
    $params = array(
      'id' => $fbEventMap->id,
      'facebook_event_id' => 123456,
    );
    $fbEventMap2 = CRM_Civisocial_BAO_FacebookEvent::retrieve($params);
    $this->assertEquals($params['facebook_event_id'], $fbEventMap2->facebook_event_id, 'Check for Facebook event id retrieval.');
  }

}
