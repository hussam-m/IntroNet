<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Poster
 *
 * @author Chakshu
 */
class Poster {
    //put your code here
    private $id;
    private $name;
    private $participants=[];
    private $max;
    private $min;
    private $rounds = [];


    public function __construct($poster_id,$rounds,$max,$min=1) {
        $this->id=$poster_id;
        $this->max = $max;
        $this->min = $min;
        for($i=0;$i<$rounds;$i++)
            $this->rounds[$i] =[];
    }
    
    
    public function add($participant,$round){
        if(!$this->isRoundFull($round)){
            $this->rounds[$round][]=$participant;
            $this->participants[]=$participant;
            return true;
        }
        return false;
    }
    
    public function hasParticipant($participant){
        return array_search($participant, $this->participants)!== FALSE;
    }
    
    public function isRoundEmpty($round) {
        return count($this->rounds[$round])<$this->min;
    }
    public function isRoundFull($round) {
        return count($this->rounds[$round])>=$this->max;
        //return count($this->rounds[$round])>$round;
    }
    
    public function NumberOfParticipants() {
        return count($this->participants);
    }
    public function NumberOfParticipantsInRound($round) {
        return count($this->rounds[$round]);
    }
    public function getParticipantsAtRound($round){
        return $this->rounds[$round];
    }
    public function getTotalWeight($round){
        $weight=0;
        foreach ($this->rounds[$round] as $participant) {
            $weight+=$participant->getWeight();
        }
        return $weight;
    }
    public function getLowestWeight($round){
        $weight=0;
        foreach ($this->rounds[$round] as $participant) {
            $weight= max($weight, $participant->getWeight());
        }
        return $weight;
    }


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
    
    public function remove($participant,$round){
        // remove participant
        unset($this->participants[array_search($participant, $this->participants)]);
        unset($this->rounds[$round][array_search($participant, $this->rounds[$round])]);
        // reorder array
        $this->participants = array_values($this->participants);
        $this->rounds[$round] = array_values($this->rounds[$round]);
    }
    
    // for testing
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
 
     
}
