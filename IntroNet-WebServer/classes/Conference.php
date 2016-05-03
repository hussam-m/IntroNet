<?php

/**
 * Description of Conference
 *
 * 
 */
class Conference {

    public $conference_id;
    public $conference_name;
    public $registration_start_date;
    public $registeration_deadline_date;
    public $registration_start_time;
    public $registration_deadline_time;

    public static function getConferences($options=""){
        $conferences = Database::getObjects("Conference",$options);
        return $conferences;
    }
    
    /**
     * 
     * @param int $id Conference id
     * @return Conference
     */
    public static function getConference($id){
        $conference = Database::getObject("Conference"," conference_id = $id ");
        return $conference;
    }

    public static function getParticipants($id){
        $sql="SELECT participant_id,fname,lname,phone,email, Organisation.name as organisation ,  IF(invitation IS NULL,'','✓') AS invitation, IF(disabled = 0,'','✓') AS disabled  ,

(CASE WHEN EXISTS (

SELECT *
FROM Event, Registration
WHERE 
Registration.participant_id = Participant.participant_id
AND Registration.event_id = Event.event_id
AND Event.conference_id =$id

)
THEN  '✓'
ELSE  ''
END
) AS registered
,
(CASE WHEN EXISTS (

SELECT *
FROM Event, Schedule
WHERE 
Schedule.participant_id = Participant.participant_id
AND Schedule.event_id = Event.event_id
AND Event.conference_id =$id

)
THEN  '✓'
ELSE  ''
END
) AS hasSchedule

FROM Participant, Organisation
WHERE Participant.conference_id =$id AND Organisation.organisation_id = Participant.organisation";
        //$participant = Database::getObjects("Participant","","Select participant_id,fname,lname,phone,email, Organisation.name as organisation From Participant,Organisation where Organisation.organisation_id = Participant.organisation AND conference_id = $id");
        $participant = Database::getObjects("Participant","",$sql);

        return $participant;
    }
    public function getNumberOfParticipants(){
        return Database::count("Participant"," Where conference_id =".$this->conference_id);
    }
    public function getNumberOfNewParticipants(){
        return Database::count("Participant"," Where invitation IS NULL AND conference_id =".$this->conference_id);
    }
    public function getNumberOfScheduleParticipants(){
        return Database::count("Participant",",Schedule,Event Where Schedule.participant_id = Participant.participant_id AND Schedule.event_id = Event.event_id AND  Event.conference_id =".$this->conference_id);
    }
    
    /**
     * 
     * @return Event[]
     */
    public function getEvents() {
        $events = Event::getEvents("Where conference_id=".$this->conference_id);
        return $events;
    }
    public function getOrganisations() {
        $organisations = Database::getObjects("Organisation","Where conference_id=".$this->conference_id);
        return $organisations;
    }
    
    public function __get($name) {
        switch ($name) {
            case "name":
                return $this->conference_name;
            case "id":
                return $this->conference_id;
            default:
                throw new Exception($name." does not exist!");
        }
    }
}
