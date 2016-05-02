<?php


/**
 *  
 *
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
        $this->eventLength = $this->rounds * $this->roundLength;
        $this->endTime = strtotime("+".$this->eventLength." minutes",strtotime($this->startTime));
    }
    
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
    public function getStartDate(){
        return date("m/d/Y", strtotime($this->startDate));
    }
    public function getStartTime(){
        return date("H:i", strtotime($this->startTime));
    }
    public function getEndDate(){
        return date("m/d/Y", strtotime($this->endDate));
    }
    public function getEndTime(){
        return  date("H:i",$this->endTime);
    }
    public function getCountDown(){
        return date("m/d/Y H:i", strtotime($this->endDate." ".$this->endTime)-strtotime($this->startDate." ".$this->startTime));
    }
    public function getStartDay(){
        return date("F, jS", strtotime($this->startDate));
    }
    public function getType(){
        return $this->type==Event::ONETOONE?"One to One":"One to Many";
    }
    public function getNumberOfConferenceParticipant(){
        return Database::count("Participant", "WHERE conference_id=".$this->conference_id);
    }
    public function getNumberOfParticipantion(){
        return Database::count("Registration", "WHERE event_id=".$this->event_id);
    }

    


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
    public function getNumberOfParticipants(){
        return Database::count("Participant",",Registration Where Participant.participant_id=Registration.participant_id AND event_id =".$this->event_id);
    }

    public function getConferenceName() {
        $conference = Database::getObject("Conference","Conference_id=".$this->conference_id);
        return $conference->name;
    }

    public static function getEvents($options="") {
        //$events =[];
        //Database::get("Select Event_name, start_date, start_time, end_date, end_time FROM EVENT");
        $events = Database::getObjects("Event",$options);
        return $events;
    }
    public static function getEvent($id,$options="") {
        $event = Database::getObject("Event","Event_id=$id ".$options);
        return $event;
    }
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
