<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * @property string $name this is the name of the posters
 * @property string[] $participants this is the list of participants who selected the posters
 * @property int $max this is the maximum number
 * @property int min this is the minimum number
 * @property int[] $rounds this is the number of rounds
 */
class Poster {
    //put your code here
//    private $id;
    public $name;
    public $participants=array();
    public $max;
    private $min;
    public $rounds=array();


//    public function __construct($poster_id,$rounds,$max,$min=1) {
//        $this->id=$poster_id;
//        $this->max = $max;
//        $this->min = $min;
//        for($i=0;$i<$rounds;$i++)
//            $this->rounds[$i] =[];
//    }
    
  /**
   * This function is to add participant
   * @param add $participant this is to add the participant
   * @param int $round this is the round information
   * @return participant returns accepted if the participant is added.
   */  
    public function add($participant,$round){
        if(!$this->isRoundFull($round)){
            $this->rounds[$round][]=$participant;
            $this->participants[]=$participant;
            return true;
        }
        return false;
    }
    /**
     * This function checks whether the posters has the participants or not
     * @param hasParticipant $participant
     * @return participant
     */
    public function hasParticipant($participant){
        return array_search($participant, $this->participants)!== FALSE;
    }
    /**
     * This function checks whether the round is empty or not
     * @param isRoundEmpty $round
     * @return round
     */
    public function isRoundEmpty($round) {
        return count($this->rounds[$round])<$this->min;
    }
    /**
     * This function checks whether the round is full or not
     * @param int $round
     * @return isRoundFull whether the round if full or not
     */
    public function isRoundFull($round) {
        //var_dump($this->rounds);
        return count($this->rounds[$round])>=$this->max;
        //return count($this->rounds[$round])>$round;
    }
    /**
     * @param NumberOfParticipants this is the number of participants
     * @return participants returns the number of participants
     */
    public function NumberOfParticipants() {
        return count($this->participants);
    }
     /**
     * @param NumberOfParticipantsInRound $round this is the number of participants in the round
     * @return participants returns the number of participants
     */
    public function NumberOfParticipantsInRound($round) {
        return count($this->rounds[$round]);
    }
    /**
     * @param getParticipantsAtRound $round this is the number of participants in the specific round
     * @return participants returns the number of participants
     */
    public function getParticipantsAtRound($round){
        return $this->rounds[$round];
    }
     /**
     * @param getTotalWeight $round this is the number of participants weight
     * @return participants returns the total weight of participants
     */
    public function getTotalWeight($round){
        $weight=0;
        foreach ($this->rounds[$round] as $participant) {
            $weight+=$participant->getWeight();
        }
        return $weight;
    }
    /** 
     * @param getLowestWeight $round
     * @return $round returs the round of the lowest weight of the round
     */
    public function getLowestWeight($round){
        $weight=0;
        foreach ($this->rounds[$round] as $participant) {
            $weight= max($weight, $participant->getWeight());
        }
        return $weight;
    }

/**
 * 
 * @param getParticipantsLW $round
 * @param getParticipantsLW $poster
 * @return participants returns the lowest weight of the participants
 */
    public function getParticipantsLW($round,$poster=NULL) {
        echo "<p> get participant with lowest weight in round $round for poster $this";
        $participant= $this->rounds[$round][0];
        echo "<br/>".$participant;
        foreach ($this->rounds[$round] as $key => $p) {
            if($p->getWeight() > $participant->getWeight()){
                $participant = $p;
            }
        }
        echo "<br/> participant with lowest weight is ".$participant."</p>";
        return $participant;
    }
    /**
     * This function removes the participant from the round
     * @param remove $participant
     * @param remove $round
     */
    public function remove($participant,$round){
        // remove participant
        unset($this->participants[array_search($participant, $this->participants)]);
        unset($this->rounds[$round][array_search($participant, $this->rounds[$round])]);
        // reorder array
        $this->participants = array_values($this->participants);
        $this->rounds[$round] = array_values($this->rounds[$round]);
    }
    /**
     * 
     * @param getId this functions fets the id of the participant
     */
    public function getId() {
        return $this->id;
    }
    
   /**
    * @param _toString this function converts the string
    */
    public function __toString() {
        $str="posterid=".$this->id." rounds{";
        foreach ($this->rounds as $key => $round) {
            $str.="r $key [";
            $str.=implode($round,',');
            $str.="],";
        }
                $str.="}";
        return $str; 
    }
 /**
  * This function gets the name
  * @param __get $name
  * @return $name
  */
     public function __get($name) {
        switch ($name) {
            case "name":
                return $this->name;
            case "id":
                return $this->poster_id;
            default:
                throw new Exception($name." does not exist!");
        }
    }
     
}
