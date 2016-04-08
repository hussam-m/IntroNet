<?php

require_once 'User.php';


/**
 * Description of Participant
 *
 * @author Sandeep
 */
class Participant extends User {
    private $fname;
    private $lname;
    private $phone;
    public $preferences;
    public $id;
    private $weight;

    public function __construct($id, $preferences) {
        $this->id = $id;
        $this->preferences=$preferences;
        $this->weight=$id;
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
    
    function getWeight() {
        return $this->weight;
    }
    function setWeight($weight) {
        $this->weight=$weight;
    }
    
    public function hasPreference($preference){
        return array_search($preference, $this->preferences)!== FALSE;
    }
    
    public function __toString() {
        return $this->id."";
    }
}
