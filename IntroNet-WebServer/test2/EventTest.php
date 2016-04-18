<?php
//require_once 'LoginTest.php';
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../autoload.php';

use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;

/**
 * Description of EventTest
 *
 * @author hussam
 */
class EventTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;

    public function setUp() {
//        $capabilities = array(WebDriverCapabilityType::BROWSER_NAME => 'firefox');
//        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
    }

    public function tearDown() {
        //$this->webDriver->close();
    }
    
    public static function setUpBeforeClass(){
        $capabilities = array(WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        self::$webDriver2 = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
    }
    
    public static function tearDownAfterClass(){
        //if(isset(self::$webDriver2))
        self::$webDriver2->close();
    }
    

    protected $url = 'http://localhost:8000/';
    static $event;
    static $webDriver2;

    /**
     * @/dataProvider dataSet
     */
    public function testNewEventPage() {
        $this->webDriver = self::$webDriver2;
        
        // 1- login if needed
        if (count($this->webDriver->findElements(WebDriverBy::linkText("hussam"))) === 0)
            LoginTest::login($this, $this->webDriver);
        //$this->assertContains('IntroNet', $this->webDriver->getTitle());
        
        // 2- go to new event page
        $element = $this->webDriver->findElement(WebDriverBy::linkText('Events'));
        $element->click();
        $element = $this->webDriver->findElement(WebDriverBy::linkText('New Event'));
        $element->click();
        
        // should go to the new event page
        $this->assertContains('http://localhost:8000/index.php?page=NewEvent', $this->webDriver->getCurrentURL()); 
    
        //var_dump($event);
        //return array($this->webDriver,  $event);
        //self::$event=$event;
        self::$webDriver2= $this->webDriver;
    }
    
    /**
     * @dataProvider dataSet
     * @depends testNewEventPage
     * @param array $event
     */
    public function testSubmitEvent($event) {
        $this->testNewEventPage();
        //$event = self::$event;
        $webDriver = self::$webDriver2;
//        var_dump($data);
//        list($webDriver,$event) = $data;
        
        var_dump($event);
        //var_dump($webDriver);
       //$webDriver=$this->webDriver;
        //1- enter Event name
        $element = $webDriver->findElement(WebDriverBy::id('eventName'));
        $element->click();
        $webDriver->getKeyboard()->sendKeys($event['eventName']);

        //2- select Type (select "One to Many")
        $element = $webDriver->findElement(WebDriverBy::id('typeOfEvent'))->findElements( WebDriverBy::tagName('option') );
        $element[$event['typeOfEvent']]->click();
        
        //3- enter number of rounds
        $element = $webDriver->findElement(WebDriverBy::id('numberOfRounds'));
        $element->click();
        $webDriver->getKeyboard()->sendKeys($event['numberOfRounds']);
        
        //4- enter time Of Sessions
        $element = $webDriver->findElement(WebDriverBy::id('timeOfSessions'));
        $element->click();
        $webDriver->getKeyboard()->sendKeys($event['timeOfSessions']);

        //5- enter length Of Entire Event
        $element = $webDriver->findElement(WebDriverBy::id('lengthOfEntireEvent'));
        $element->click();
        $webDriver->getKeyboard()->sendKeys($event['lengthOfEntireEvent']);
        
        //6- enter eventDay
        $element = $webDriver->findElement(WebDriverBy::id('eventDay'));
        $element->click();
        $webDriver->getKeyboard()->sendKeys($event['eventDay']);
        
        //7- enter eventTime
        $element = $webDriver->findElement(WebDriverBy::id('eventTime'));
        $element->click();
        $webDriver->getKeyboard()->sendKeys($event['eventTime']);
        
        //8- posters-tokenfield
        $element = $webDriver->findElement(WebDriverBy::id('posters-tokenfield'));
        $element->click();
        $webDriver->getKeyboard()->sendKeys('poster 1');
        $webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
        $webDriver->getKeyboard()->sendKeys('poster 2');
        $webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
        $webDriver->getKeyboard()->sendKeys('poster 3');
        $webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
        $webDriver->getKeyboard()->sendKeys('poster 4');
        $webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
        $webDriver->getKeyboard()->sendKeys('poster 5');
        $webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
        $webDriver->getKeyboard()->sendKeys('poster 6');
        $webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
        $webDriver->getKeyboard()->sendKeys('poster 7');
        $webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
        $webDriver->getKeyboard()->sendKeys('poster 8');
        $webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
        
        //9- submit
        $element = $webDriver->findElement(WebDriverBy::cssSelector('input[type="submit"]'));
        $element->click();
        
        $this->assertContains('http://localhost:8000/index.php?page=NewEvent', $webDriver->getCurrentURL()); 
    }
    
    function dataSet(){
        // set of test cases
        $events=[];

        // goodEvent test case
        $events['goodEvent']= ['data'=>[
            'eventName'=>'Test Event',
            'typeOfEvent'=>1,
            'numberOfRounds'=>'5',
            'timeOfSessions'=>'10',
            'lengthOfEntireEvent'=>'50',
            'eventDay'=>'04/29/2016',
            'eventTime'=>'9:25',
            'posters'=>['poster 1','poster 2', 'poster 3']
        ]];
        
        // noNameEvent test case
        $events['noNameEvent']= $events['goodEvent'];
        $events['noNameEvent']['data']['eventName']='bad';
        
        return $events;
    }

}
