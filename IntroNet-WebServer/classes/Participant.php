<?php

require_once 'User.php';


/**
 * Description of Participant
 *
 * @author Sandeep
 * @property String $name Full name of the Participant
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
        $this->id = $id | $this->participant_id;
        $this->preferences=$preferences;
        //$this->weight=$id;
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
    
    /**
     * get Participant's Preferences
     * @param Event $event
     */
    public function getPreferences($event){
        if(isset($this->preferences))
            return $this->preferences;
        else
            return $this->preferences = Database::getObjects ("","" ,"SELECT preference FROM Preference Where participant_id=".$this->id." AND event_id=".$event->event_id);
    }

    public function hasPreference($preference){
        return array_search($preference, $this->preferences)!== FALSE;
    }
    
    /**
     * 
     * @param int $id Participant's id
     * @return Participant
     */
    public static function getParticipant($id) {
        return Database::getObject("Participant", "participant_id=$id");
    }
    
    public function getSchedule(Event $event) {
        if ($event->type == Event::ONETOMANY)
            return Database::getObjects("Schedule","", "SELECT Schedule.roundNumber as roundNumber, Poster.name as poster_name FROM Schedule ,Meeting_Poster,Poster WHERE Schedule.participant_id=$this->participant_id AND Schedule.event_id=$event->event_id AND Schedule.schedule_id=Meeting_Poster.schedule_id AND Poster.poster_id=Meeting_Poster.poster_id ORDER BY  roundNumber");
        else{
            //SELECT Schedule.roundNumber as roundNumber, IFNULL(`Table`.table_name, Meeting_Table.table_number) as table_name FROM Schedule,Meeting_Table,`Table` WHERE participant_id=3 AND Schedule.event_id=5 AND Schedule.schedule_id=Meeting_Table.schedule_id AND Meeting_Table.table_number=`Table`.table_number ORDER BY  roundNumber
            return Database::getObjects("Schedule","", "SELECT Schedule.roundNumber as roundNumber, Meeting_Table.table_number as table_name FROM Schedule,Meeting_Table WHERE participant_id=$this->participant_id AND event_id=$event->event_id AND Schedule.schedule_id=Meeting_Table.schedule_id ORDER BY  roundNumber");
        }
    }
    
    public function __toString() {
        return $this->name."";
    }
    
    public static function getParticipants($event){
        $participants = Database::getObjects("Participant","","Select fname,lname,phone,email, Organisation.name as organisation From Participant,Organisation where Organisation.organisation_id = Participant.organisation");
        return $participants;
    }
    public static function addParticipant($conference,$fname,$lname,$email,$organisation,$disability,$vip,$phone="NULL"){
        $participants = Database::insert("Participant",array(
            "fname"=>"'$fname'",
            "lname"=>"'$lname'",
            "email"=>"'$email'",
            "conference_id"=>$conference,
            "organisation"=>$organisation,
            "disabled"=>(int) $disability,
            //"phone"=>$phone
        ));
        //return $participants;
        var_dump(func_get_args());
    }
    
    public function setInvitation($invitation){
        Database::update("Participant", "invitation='$invitation'", " participant_id=".$this->id);
    } 
    public static function login($email,$password){
        return Database::getObject("Participant", "email='$email' AND invitation='$password' AND invitation IS NOT NULL");
    }
    
    public function __get($name) {
        if($name=="name")
            return $this->fname." ".$this->lname;
            
    }
}
