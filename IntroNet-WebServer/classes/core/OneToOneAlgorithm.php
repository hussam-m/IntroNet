<?php


class OneToOneAlgorithm {

    /**
     * This function build One to One schedules
     * @param Participant[] $participants
     * @param Event           $event     number of rounds
     * @return array        schedule   a list of schedules (each participant have one schedule)
     * 
     */
    public static function build($participants, $event) {
        //var_dump($participants);
        $rounds = $event->rounds;
        
        if($rounds > count($participants)/2)
            throw new AlgorithmException("<h4>Algorithm Exception for event [$event->name]</h4> Number of participants should be at least double the number of rounds");


            /* @var $tables Table[] */
        $tables = array();
        
        //create Set A and Set B
        $setA = array();
        $setB = array();
        // $participants = create_function(SetA, "SetA");
        //$participants = create_function(SetB, "SetB");

        $i = 0;
        for ($i; $i < floor(count($participants) / 2) ; $i++) {
            $setA[] = $participants[$i];
            $tables[] = new Table($i);
        }
        for ($i; $i < count($participants)-count($participants) % 2; $i++) {
            $setB[] = $participants[$i];
        }



        $size = count($participants) % 2;

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
        $odd=FALSE;
        if (count($participants) % 2 == 1) {
            $odd = end($participants);
            //$setB[] = $odd;
            //echo $odd;
        }
        



//        var_dump($setA);
//        var_dump($setB);
        /* else{
          //these are the dummy vaariables I think would swap
          $p = array_unshift($setB,"setA","setB");
          $q = array_unshift($setB,"setA","setB");
          array_unshift($p,$q);
          } */

        $schedule= array();
        for ($round = 0; $round < $rounds; $round++) {
            $oddLocation = $rounds-($round+1);
            //echo 'round '.$round;
            //here since the last element is a we are adding it to the first   
            $b = $setB[count($setB) - 1];
            array_unshift($setB, $b);
            unset($setB[count($setB) - 1]);
            //echo implode(',', $setB) . "<br/>";
            $meeting=array();
            foreach ($setA as $key => $value) {
                //echo '$setA[$key]';
                //var_dump($setA[$key]);
//                if( $odd && $oddLocation==$key)
//                    var_dump($setA[$key]->name." meets ".$setB[$key]->name." and ".$odd);
//                else 
//                    var_dump($setA[$key]->name." meets ".$setB[$key]->name);
                
                if( $odd && $oddLocation==$key)
                   //$meeting[]= array($setA[$key],$setB[$key],$odd);
                    $tables[$key]->meething ($round, $setA[$key], $setB[$key], $odd);
                else 
                    //$meeting[]= array($setA[$key],$setB[$key]);
                    $tables[$key]->meething ($round, $setA[$key], $setB[$key]);
            }
            
            //$schedule[]=$meeting;
        }
        //var_dump($setA);
        //var_dump($setB);
        return $tables;
    }

}

//    $participant = [];
//   $participant[0] = new Participant(1,[]);
//   $participant[1] = new Participant(2,[]);
//   $participant[2] = new Participant(3,[]);   
//   $participant[3] = new Participant(4,[]);
//   $participant[4] = new Participant(5,[]);
//   $participant[5] = new Participant(6,[]);
//   
//   
//   
//   $rounds = 5;
//OneToOneAlgorithm::build($participant,$rounds);


