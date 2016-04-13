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
    public $id;
    public $weight;
    public $organisation;

    public function __construct($id=null, $preferences=null) {
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
    
    public static function getParticipants($event){
        $participants = Database::getObjects("Participant","","Select fname,lname, Organisation.name as organisation From Participant,Organisation where Organisation.organisation_id = Participant.organisation");
        return $participants;
    }
}
