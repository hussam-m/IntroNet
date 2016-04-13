<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Event
 *
 * @author raniaalkhazaali
 */
class Event {
    //put your code here
    public $event_id;
    public $name;
    public $startDate;
    public $startTime;
    public $endDate;
    public $endTime;
    public $rounds;
    public $roundLength;
    public $type;


//    public function __construct($name, $datetime) {
//        $this->name=$name;
//        $this->datetime=$datetime;
//    }
    
    public static function create($name,$startDate,$startTime,$endDate,$endTime) {
        $event = new Event;
        try{
            $event->name= Validation::validate($name, Validation::NAME);
            $event->startDate= Validation::validate($name, Validation::DATE);
            $event->startTime= Validation::validate($name, Validation::TIME);
            $event->endDate= Validation::validate($name, Validation::DATE);
            $event->endTime= Validation::validate($name, Validation::TIME);
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
        return date("H:i", strtotime($this->endTime));
    }
    public function getCountDown(){
        return date("m/d/Y H:i", strtotime($this->endDate." ".$this->endTime)-strtotime($this->startDate." ".$this->startTime));
    }
    public function getStartDay(){
        return date("F, jS", strtotime($this->startDate));
    }
    public function getType(){
        return $this->type==1?"One to One":"One to Many";
    }
    public function getNumberOfParticipant(){
        return rand(10,200); // this code for testing
    }
    public function getNumberOfParticipantion(){
        return rand(1,100); // this code for testing
    }

    


    public function isRegister($Participant)
    {
        
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
    public function allParticipants()
    {
        
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
}
