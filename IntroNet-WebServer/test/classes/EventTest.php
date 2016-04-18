<?php

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-04-17 at 00:40:40.
 */
class EventTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Event
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Event;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Event::create
     * @todo   Implement testCreate().
     */
    public function testCreate() {
        $name="Test Event";
        $startDate = "10/4/2016";
        $startTime = "8:00";
        $endDate = "10/8/2016";
        $endTime = "13:00";
        $type   = Event::ONETOONE;
        $event = Event::create($name, $startDate, $startTime, $endDate, $endTime,$type);
        $this->assertInstanceOf('Event',$event);
        return $event;
    }

    /**
     * @covers Event::getName
     * @todo   Implement testGetName().
     */
    public function testGetName() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::setName
     * @todo   Implement testSetName().
     */
    public function testSetName() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::getStartDate
     * @depends testCreate
     */
    public function testGetStartDate(Event $event) {
        //var_dump($event);
        date_default_timezone_set('UTC');
        $this->assertEquals('10/04/2016', $event->getStartDate());
    }

    /**
     * @covers Event::getStartTime
     * @depends testCreate
     */
    public function testGetStartTime(Event $event) {
        date_default_timezone_set('UTC');
        $this->assertEquals('08:00', $event->getStartTime());
    }

    /**
     * @covers Event::getEndDate
     * @depends testCreate
     */
    public function testGetEndDate(Event $event) {
        date_default_timezone_set('UTC');
        $this->assertEquals('10/08/2016', $event->getEndDate());
    }

    /**
     * @covers Event::getEndTime
     * @depends testCreate
     */
    public function testGetEndTime(Event $event) {
        date_default_timezone_set('UTC');
        $this->assertEquals('13:00', $event->getEndTime());
    }

    /**
     * @covers Event::getCountDown
     * @todo   Implement testGetCountDown().
     */
    public function testGetCountDown() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::getStartDay
     * @depends testCreate
     */
    public function testGetStartDay(Event $event) {
        date_default_timezone_set('UTC');
        $this->assertEquals('October, 4th', $event->getStartDay());
    }

    /**
     * @covers Event::getType
     * @depends testCreate
     */
    public function testGetType(Event $event) {
        $this->assertEquals('One to One', $event->getType());
    }

    /**
     * @covers Event::getNumberOfParticipant
     * @depends testCreate
     */
    public function testGetNumberOfParticipant(Event $event) {
        //$this->assertEquals(0, $event->getNumberOfParticipant());
    }

    /**
     * @covers Event::getNumberOfParticipantion
     * @todo   Implement testGetNumberOfParticipantion().
     */
    public function testGetNumberOfParticipantion() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::isRegister
     * @todo   Implement testIsRegister().
     */
    public function testIsRegister() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::isAttended
     * @todo   Implement testIsAttended().
     */
    public function testIsAttended() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::addPoster
     * @todo   Implement testAddPoster().
     */
    public function testAddPoster() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::isLeft
     * @todo   Implement testIsLeft().
     */
    public function testIsLeft() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::missingParticipants
     * @todo   Implement testMissingParticipants().
     */
    public function testMissingParticipants() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::allParticipants
     * @todo   Implement testAllParticipants().
     */
    public function testAllParticipants() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::getEvents
     * @todo   Implement testGetEvents().
     */
    public function testGetEvents() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Event::getEvent
     * @todo   Implement testGetEvent().
     */
    public function testGetEvent() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}