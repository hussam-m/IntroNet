<?php
require_once '../Participant.php';


class OneToManyAlgrithm{
    /**
     * This function build schedules
     * @param Participant[] $participants
     * @param Poster[]      $posters
     * @param int           $rounds     number of rounds
     * @return array        schedules   a list of schedules (each participant have one schedule)
     */
    public static function build($participants,$posters,$rounds){
        // sort posters based on number of selections
        $sortedPosters = self::sortPosters($posters, $participants);
        foreach($participants as $participant){
            for($round=0; $round < $rounds; $round++)
            {
                //get poster that participant wants to visit
                $poster = $posters[$participant->preferences[$round]];
                // if poster is not full
                if($poster->isFull()==FALSE ){
                    //add the poster to participant's schedule
                    $poster->add($participant);
                }else{
                    // does the participant have more preferences?
                    if(count($participant->preferences)>$round){
                        // choose the next preference
                        $poster = $posters[$participant->preferences[$round+1]];
                    } else{
                        // find the poster that is less selected and not selected by this participant
                        
                    }
                }
            }
        }   
    }
    
    /**
     * This function sorts posters based on the number of participants who select it
     * The top of the list is the poster that less been selected.
     * @param Poster[] $posters
     * @param Participant[] $participants
     * @return Poster[] $sortedPosters
     */
    private static function sortPosters($posters,$participants){
        $p= array_fill(1, count($posters)-1, 0);
        foreach($participants as $participant){
            foreach($participant->preferences as $preference){
                $p[$preference]++;
                echo '</br>p='.$preference.' v='.$p[$preference];
            }
        }
        
        var_dump($p);
        asort($p);
        var_dump($p);
//        for($i=1;$i<count($p);$i++){
//            $sortedPosters[$i]=$posters[$p[$i]];
//        }
        $i=0;
        foreach ($p as $key => $value) {
            $sortedPosters[$i]=$posters[$key-1];
            echo '</br>key='.$key.' i='.$i;
            $i++;
        }
        
        //$sortedPosters = $p;
        return $sortedPosters;
    }
    
    public static function testSortPosters(){
         $posters = [1,2,3,4];
         
         $participants=[];
         $participants[0] = new Participant([2,1,4]);
         $participants[1] = new Participant([1,4,2]);
         $participants[2] = new Participant([2,3,4]);
         
         echo 'Posters:';
         //var_dump($posters);
         echo implode($posters,',');

         $sortedPosters = self::sortPosters($posters,$participants);
         echo '</br>Sorted Posters:';
         //var_dump($sortedPosters);
         echo implode($sortedPosters,',');
         var_dump($sortedPosters);

         
    }
    
}

OneToManyAlgrithm::testSortPosters();