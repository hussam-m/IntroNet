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

    public function getConferences($options=""){
        $conferences = Database::getObjects("Conference",$options);
        return $conferences;
    }
}