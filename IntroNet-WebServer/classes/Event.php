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
    private $id;
    private $name;
    private $datetime;
    public function __construct($name, $datetime) {
        $this->name=$name;
        $this->datetime=$datetime;
    }
    public function getName()
    {
        
    }
    public function setName()
    {
        
    }
    public function getDateTime()
    {
        
    }
    public function setDateTime($Datetime)
    {
        
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
}
