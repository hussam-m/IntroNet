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
    public static function getConference($id){
        $conference = Database::getObject("Conference"," conference_id = $id ");
        return $conference;
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
