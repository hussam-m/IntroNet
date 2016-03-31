<?php

require_once 'User.php';


/**
 * Description of Participant
 *
 * @author Sandeep
 */
class Participant extends User {
    public $fname;
    public $lname;
    public $phone;
    public $preferences;
    
    public function __construct($preferences) {
        $this->preferences=$preferences;
    }
    function register($eventid)
    {
        
    }
    function attend($eventid)
    {
        
    }
    function leave($eventid)
    {
        
    }
    function joinTable($tableid)
    {
        
    }
    function visitPoster($posterid)
    {
        
    }
}
