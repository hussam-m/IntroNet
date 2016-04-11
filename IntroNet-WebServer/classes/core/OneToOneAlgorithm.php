<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
//require('../../vendor/autoload.php' );
//php_error\reportErrors();


require_once __ROOT__.'/Participant.php';
require_once __ROOT__.'/Poster.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of algorithm
 *
 * @author Sandeep
 */
class OneToOneAlgorithm {
    //put your code here
    /**
     * This function build schedules
     @param Participant[] $participants
     * @param Poster[]      $posters
     * @param int           $rounds     number of rounds
     * @return array        schedules   a list of schedules (each participant have one schedule)
     * 
     */
    
    public static function build($participants,$rounds){
        //create Set A and Set B
       // $participants = create_function(SetA, "SetA");
        //$participants = create_function(SetB, "SetB");
       
        $i=0;
        for($i; $i<count($participants)/2; $i++){
            $setA[]=$participants[$i];  
        }
        for($i; $i<count($participants); $i++){
            $setB[]=$participants[$i];  
        }
        
        

        $size= count($participants)%2;
          
//        foreach($participants as $participant){
//            for($round=0; $round < $rounds; $round++)
//            {
//                // if participant is disabled
//                if ($participant=="disabled"){
//                    $setA[] = $participant;
//                }
//                else{
//                    $setB[] = $participant;
//                    
//                }
//            }
//        }

            
//                if(count($setA)>=$size){
//                    $setB[] = $participant;
//                }
//                else{
//                    $setA[] = $participant;
//                }
                
               //if the size is odd then the last element is added to the setb
                   if(count($participants)%2==1) { 
                   $a = end($setB);
                    $setB[] = $a;
                     echo $a;
                    }
                //swap     
               
                 $sample = array(
                    0 => 'SetA',
                    1 => 'SetB'
                    );
                    $temp = $sample[0];
                    $sample[1] = $temp;
                    echo $sample[0];
                    echo $sample[1];
                    
                 
                /*else{
                    //these are the dummy vaariables I think would swap
                    $p = array_unshift($setB,"setA","setB");
                    $q = array_unshift($setB,"setA","setB");
                    array_unshift($p,$q);
                }*/
                
               for($round=0; $round < $rounds; $round++)
            {
             //here since the last element is a we are adding it to the first   
                   $b = $setB[count($setB)-1];
                 array_unshift($setB, $b);
                 unset($setB[count($setB)-1]);
                 echo implode(',', $setB)."<br/>";
               
               } 
            
        }   
    }
    $participant = [];
   $participant[0] = new Participant(1,[]);
   $participant[1] = new Participant(2,[]);
   $participant[2] = new Participant(3,[]);   
   $participant[3] = new Participant(4,[]);
   $participant[4] = new Participant(5,[]);
   $participant[5] = new Participant(6,[]);
   
   
   
   $rounds = 5;
OneToOneAlgorithm::build($participant,$rounds);


