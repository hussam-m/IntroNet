<?php

/**
 * @property int $event_id This is the id for the event
 * @property string $name This is the name for the event
 * @property date $startDate This is the startDate for the event
 * @property time $startTime This is the start time for the event
 * @property date $endDate This is the end date for the event
 * @property time $endTime This is the end time for the event
 * @property int $rounds This is the number of rounds for the event
 * @property int $roundLength This is the length of rounds for the event
 * @property int $eventLength This is the length of the entire event
 * @property string $type This is the type of event whether one-one or one-many
 * @property int $conference_id This is the conference_id to which this event belongs to 
 * 
 */
class Event {
    public $event_id;
    public $name;
    public $startDate;
    public $startTime;
    public $endDate;
    public $endTime;
    public $rounds;
    public $roundLength;
    public $eventLength;
    public $type;
    public $conference_id;


    const ONETOONE = 1;
    const ONETOMANY = 2;


//    public function __construct($name, $datetime) {
//        $this->name=$name;
//        $this->datetime=$datetime;
//    }
    public function __construct(){
        $this->id=  $this->event_id;
        $this->eventLength = $this->rounds * $this->roundLength;
        $this->endTime = strtotime("+".$this->eventLength." minutes",strtotime($this->startTime));
    }
    /**
     * This function creates new event
     * @param string $name This is the name of the event
     * @param date $startDate this is the start date of the event
     * @param time $startTime this is the start time of the event
     * @param date $endDate this is the end date of the event
     * @param time $endTime This is the end time of the event
     * @param string $type this is the type of the event whether it is one-one and one-many
     * @return \Event this returns the newly created event
     * 
     */
    public static function create($name,$startDate,$startTime,$endDate,$endTime,$type) {
        $event = new Event;
        try{
            $event->name= Validation::validate($name, Validation::NAME);
            $event->startDate= Validation::validate($startDate, Validation::DATE);
            $event->startTime= Validation::validate($startTime, Validation::TIME);
            $event->endDate= Validation::validate($endDate, Validation::DATE);
            $event->endTime= Validation::validate($endTime, Validation::TIME);
            $event->type = $type;
        } catch (Exception $e) {
            throw new Exception("invalid input",0,$e);
//return "";
        }
        return $event;
    }
    
    public function getName()
    {
        
    }
    public function setName()
    {
        
    }
    /**
     * This is the startDate of the event
     * @param date getStartDate this is start date of the event
     * @return date returns start date of the event
     */
    public function getStartDate(){
        return date("m/d/Y", strtotime($this->startDate));
    }
     /**
     * This is the starttime of the event
     * @param time getStartTime this is start time of the event
     * @return date returns start time of the event
     */
    public function getStartTime(){
        return date("H:i", strtotime($this->startTime));
    }
      /**
     * This is the End date of the event
     * @param date getEndDate this is end date of the event
     * @return date returns get end date of the event
     */
    public function getEndDate(){
        return date("m/d/Y", strtotime($this->endDate));
    }
      /**
     * This is the end time of the event
     * @param time getEndTime this is End time of the event
     * @return time returns End time of the event
     */
    public function getEndTime(){
        return  date("H:i",$this->endTime);
    }
    /**
     * This function shows the count down of the event
     * @param int getCountDown This gets the count down for the event
     * @return int returns count down for the event 
     */
    public function getCountDown(){
        return date("m/d/Y H:i", strtotime($this->endDate." ".$this->endTime)-strtotime($this->startDate." ".$this->startTime));
    }
    /**
     * This is the get the start day of the event
     * @param date getStartDay this function gets the start day of the event
     * @return time returns start day of the event
     */
    public function getStartDay(){
        return date("F, jS", strtotime($this->startDate));
    }
    /**
     * This is the function which gets the type of event
     * @param getType Gets the type of the event
     * @return string the type of the event whether one-one or one-many
     */
    public function getType(){
        return $this->type==Event::ONETOONE?"One to One":"One to Many";
    }
    /**
     * This is the function which gets the number of participants in the conference
     * @param getNumberOfConferenceParticipant Gets the number of participants in the conference
     * @return int number of participants in the conference
     */
    public function getNumberOfConferenceParticipant(){
        return Database::count("Participant", "WHERE conference_id=".$this->conference_id);
    }
     /**
     * This is the function which gets the total number of participants
     * @param getNumberOfParticipantion Gets the total number of registered participants
     * @return int total number of participants
     */
    public function getNumberOfParticipantion(){
        return Database::count("Registration", "WHERE event_id=".$this->event_id);
    }

    

     /**
     * This is the function which gets whether the participants are registered for the event or not
     * @param isRegistered Checks whether the participants is registered for the event or not
     * @return participant returns if the participants is registered, else returns null
     */
    public function isRegistered($participant_id){
        return (bool) Database::count("Registration", "WHERE participant_id=$participant_id AND event_id=".$this->event_id);
    }
    
    public function isAttended($Participant)
    {
        
    }
    public function addPoster($Poster)
    {
        
    }
    public function isLeft($Participant)
    {
        
    }
    public function missingParticipants()
    {
        
    }
    /**
     * @param string getParticipants this function gets all the participants who are registered for the event
     * @return participants this function returns the list of all participants who all are registered
     */
    public function getParticipants()
    {
        $participants = Database::getObjects("Participant", "", "
SELECT * 
FROM  Participant, Registration
WHERE
Registration.participant_id = Participant.participant_id
AND
Registration.event_id = ".$this->event_id);
        return $participants;
    }
    /*
     * @param int getNumberOfParticipants this function returns the list of participants who are registered for the event
     * @return int returns the number of participants who are registered for the event.
     */
    public function getNumberOfParticipants(){
        return Database::count("Participant",",Registration Where Participant.participant_id=Registration.participant_id AND event_id =".$this->event_id);
    }
/**
 * @param string getConferenceName this function returns the names of all conferences
 * @return object returns the names of the conference
 */
    public function getConferenceName() {
        $conference = Database::getObject("Conference","Conference_id=".$this->conference_id);
        return $conference->name;
    }

    /**
     * This function returns the list of all events
     * @param getEvents $options this returns the list of all events based on the events
     * @return string this function returns the list of all events
     */
    public static function getEvents($options="") {
        //$events =[];
        //Database::get("Select Event_name, start_date, start_time, end_date, end_time FROM EVENT");
        $events = Database::getObjects("Event",$options);
        return $events;
    }
    /**
     * This function returns the list of all events
     * @param getEvent $id this parameter gets the event id
     * @param getEvent $options gets the specific event based on the options
     * @return string this function returns the list of all events
     */
    public static function getEvent($id,$options="") {
        $event = Database::getObject("Event","Event_id=$id ".$options);
        return $event;
    }
    /**
     * @param getPosters $options
     * @return Posters this function gets all the posters present in the event
     */
    public function getPosters($options="") {
        /* @var $posters Poster[] */
        $posters = Database::getObjects("Poster","WHERE Event_id= $this->event_id ".$options);
        //var_dump($posters);
        $numberOfParticipants = $this->getNumberOfParticipants();
        $numberOfPosters= count($posters);
        foreach ($posters as $poster) {
            $poster->max = ceil($numberOfParticipants / $numberOfPosters);
            for($i=0;$i<$this->rounds;$i++)
                $poster->rounds[$i] = array();
        }
        return $posters;
    }
    /**
     * this is the function register function for the participants
     * @param int $Participant_id this is the participant id
     * @param string $preferences this is the preferences
     * @param string $icebreaker_question this is the ice breaker questions
     * @param string $biography this is the bio graphy
     * @return participant register the participant
     */
    public function register($Participant_id,$preferences,$icebreaker_question="NULL",$biography="NULL"){
        $ok=true;
        //var_dump("start=$ok");
        $ok= $ok && Database::insert("Registration",array(
            "event_id"          =>  $this->event_id,
            "participant_id"    =>  $Participant_id,
            "icebreaker_question"=> $icebreaker_question,
            "biography"         =>  $biography
        ));
        //var_dump("event=$ok");
        //var_dump($preferences);
        foreach ($preferences as $p) {
            $ok= $ok && Database::insert("Preference",array(
                "event_id"          =>  $this->event_id,
                "participant_id"    =>  $Participant_id,
                "preference"        =>  $p
            ));
            //var_dump("preference$p=$ok");
        }
        //var_dump("all=$ok");
        return $ok;  
    }
    
    
    /**
     * This function is used to buil the schedule fot the participant
     * @param int buildSchedule this function is used to get the schedule for the participant
     * @return schedule
     */
    function buildSchedule() {
        //echo $this->name;
        
        $participants = $this->getParticipants();
        
        
        if($this->type==self::ONETOMANY){
            $posters=$this->getPosters ();
            return OneToManyAlgorithm::build($posters, $participants, $this);
        }
        
        if($this->type==self::ONETOONE){
            return OneToOneAlgorithm::build($participants, $this);
        }
        
        
    }
}
