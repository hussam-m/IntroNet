<?php

require_once 'User.php';


/**
 * Description of Participant
 *@property String $name This is the full name of the participant
 * @property String $fname First name of the Participant
 * @property String $lname Last name of the participant
 * @property int $phone contact number of the participant
 * @property String $preferences preferences of the participant
 * @property int $id ID of the participant
 * @property int $weight Describes the weight of the participant based on the priority
 * @property String $organization organization to which the participant belongs to
 */
class Participant extends User {
    public $fname;
    public $lname;
    public $phone;
    public $preferences;
    public $id;
    public $weight;
    public $organisation;
/**
 * @param _construct $id Checks whether the id is null or not
 * @param _construct $preferences Checks whether the preferences are null or not
 */
    public function __construct($id=null, $preferences=null) {
        $this->id = $id | $this->participant_id;
        $this->preferences=$preferences;
        //$this->weight=$id;
    }
   /** 
    * @param getWeight checks the weight of the participant
 */ 
    function getWeight() {
        return $this->weight;
    }
/**
 * @param setWeight $weight sets the weight of the participant
 */
    function setWeight($weight) {
        $this->weight=$weight;
    }
    
    /**
     * get Participant's Preferences
     * @param Event $event to get the preferences of the participant
     */
    public function getPreferences($event){
        if(isset($this->preferences))
            return $this->preferences;
        else
            return $this->preferences = Database::getObjects ("","" ,"SELECT preference FROM Preference Where participant_id=".$this->id." AND event_id=".$event->event_id);
    }
/**
 * 
 * @param hasPreference $preference checks whether the participant has a prefernce or not
 * @return $preference returns the preference of the participant
 */
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
    /**
     * @param getSchedule $event gets the schedule of the participant
     * @return $event returns the schedule of the participant which belongs to the specific event
     */
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
   /**
    * @param getParticipant $event gets all the participants which belong to this event
    * @return $event gets all the participants who belong to this event
    */
    public static function getParticipants($event){
        $participants = Database::getObjects("Participant","","Select fname,lname,phone,email, Organisation.name as organisation From Participant,Organisation where Organisation.organisation_id = Participant.organisation");
        return $participants;
    }
    /**
     *@param addParticipant $conference This is the name of the conference which the participant belongs to
 *@param addParticipant $fname This is the first name of the participant
   *@param addParticipant $lname Last name of the participant
 * @param addParticipant $email email id of the participant
 * @param addParticipant $organisation organization of the participant
 * @param addParticipant $disability disability status of the participant
 * @param addParticipant $vip whether the participant is VIP or not
 * @param addParticipant $phone contact number of the participant
     */
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
       // var_dump(func_get_args());
    }
 /**
  * 
  * @param setInvitation $invitation this fiunction helps in creating invitation for the participant
  * @return participant returns the participant to which the email was sent
  */
    public function setInvitation($invitation){
        Database::update("Participant", "invitation='$invitation'", " participant_id=".$this->id);
    } 
    /**
     * 
     * @param login $email
     * @param login $password
     * @return $email,$password this function helps in logging in
     */
    public static function login($email,$password){
        return Database::getObject("Participant", "email='$email' AND invitation='$password' AND invitation IS NOT NULL");
    }
    public function __get($name) {
        if($name=="name")
            return $this->fname." ".$this->lname;
            
    }
}
