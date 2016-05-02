<?php
//define('__ROOT__', dirname(dirname(__FILE__))); 
////require('../../vendor/autoload.php' );
////php_error\reportErrors();
//
//
//require_once __ROOT__.'/Participant.php';
//require_once __ROOT__.'/Poster.php';

class OneToManyAlgorithm {

    /**
     * This function build schedules using participants' preferences
     * @param Poster[]      $posters        a list of posters
     * @param Participant[] $participants   a list of participants
     * @param Event         $event          The event to build the schedule for
     * @return array        schedules       a list of schedules (each participant have one schedule)
     */
    public static function build($posters, $participants,$event) {
        //var_dump ($event);
        //var_dump ($posters);
        //var_dump($participants);
        
        //set poster id as key for posters array
        $p=$posters;
        $posters = array();
        foreach ($p as $po){
            $posters[$po->poster_id] = $po;
        }
        
        if($event->type != Event::ONETOMANY)
            throw new AlgorithmException("OneToManyAlgorithm can handle only OneToMany Events");
        
        $rounds = (int) $event->rounds;
        //var_dump($rounds);
        if ($rounds > count($posters))
            throw new AlgorithmException("Can not have rounds more than the number of posters");


        // Note: maybe it's better to swap inner loop with the outer loop
        for ($round = 0; $round < $rounds; $round++) {
            // TODO: resort $participants for each round
            uasort($participants, function($a, $b) {
                return $a->getWeight() - $b->getWeight();
            });
            //var_dump($participants);
            foreach ($participants as $participant) {

                // sort posters based on number of selections
                $sortedPosters = self::sortPosters($posters, $participants, $round, $participant, $event);
                //echo "<br/> sortedPosters= " . implode($sortedPosters, ',');

                $posterSelected = FALSE;
                //get poster that participant wants to visit
                if(array_key_exists($round, $participant->getPreferences($event)))
                    $poster = $posters[$participant->getPreferences($event)[$round]];
                else
                    $poster=null;
                
                $step = 0;
                while (!$posterSelected) {
                    //echo '</br>current preference=' . $participant->getPreferences($event)[$round];
                    //echo ' - current round=' . $round;
                    //echo ' - current participant=' . $participant;
                    //echo ' - current poster=' . $poster;
                    // if poster is not full
                    //echo "poster $poster is full?" . $poster->isRoundFull($round);
                    if (isset($poster) && $poster->isRoundFull($round) === FALSE) {
                        //add the poster to participant's schedule
                        //echo "No </br> add participant( $participant ) to poster ( $poster ) at round $round";
                        $poster->add($participant, $round);
                        $posterSelected = TRUE;

                        // swap preferences in case if we used another preference
                        if ($step != 0) {
                            //echo "<br/> Start Swap " . implode($participant->getPreferences($event), ',');
                            $temp = $participant->getPreferences($event)[$round];
                            $participant->getPreferences($event)[$round] = $participant->getPreferences($event)[$round + $step];
                            $participant->getPreferences($event)[$round + $step] = $temp;
                            //echo "<br/> End Swap " . implode($participant->getPreferences($event), ',');
                        }
                        // lower preference's weight if they get his/her preference
                        if ($participant->hasPreference($poster->getId())) {
                            $participant->setWeight($participant->getWeight() + ($rounds - 1) * 10);
                        }
                    } else {
                        //echo " Yes";

                        // does the participant have more preferences?
                        if (count($participant->getPreferences($event)) > $round + $step + 1) {
                            $step++;
                            //echo "test";
                            //echo "<br/> #preferences=".count($participant->getPreferences($event))." r+s=". ($round + $step);
                            //echo "<p style='color:red'>participant have another preferences";
                            // choose the next preference
                            $poster = $posters[$participant->getPreferences($event)[$round + $step]];
                            //echo "<br/> $poster is the selected</p>";
                        } else {
                            $step = 0;
                            
                            // if there is no poster can this participant join
                            if (!isset($sortedPosters) || count($sortedPosters) == 0) {
                                //self::buildSchedules($posters, $rounds);
                                //echo '<h3 style="color:red">Swap</h3>';
//                                $sortedPosters = self::sortPosterDESC($posters, $rounds);
//                                foreach ($sortedPosters as $key => $p) {
//                                    $lowP = $p->getParticipantsLW($round);
//                                    if(!$p->hasParticipant($participant)){
//                                        
//                                        break;
//                                    }
//                                }
//                                continue;
                                // get a poster with an empty spot
                                $ePoster;
                                foreach ($posters as $key => $po) {
                                    if (!$po->isRoundFull($round)) {
                                        $ePoster = $po;
                                        break;
                                    }
                                }
                                if (isset($ePoster)) {
                                    // find the lowest participant that is not in poster ePoster
                                    $sPoster;
                                    $sParticipant;
                                    foreach ($posters as $key => $po) {
                                        if ($po->hasParticipant($participant))
                                            continue;
                                        $pa = $po->getParticipantsLW($round);
                                        if (!$ePoster->hasParticipant($pa)) {
                                            $sPoster = $po;
                                            $sParticipant = $pa;
                                            //echo "<br/> pa = $pa";
                                        }
                                    }
                                    if (isset($sParticipant)) {
                                        $sPoster->remove($sParticipant, $round);
                                        $ePoster->add($sParticipant, $round);
                                        $sPoster->add($participant, $round);
                                        $posterSelected = TRUE;
                                        echo "Participant $sParticipant was swap with $participant in round $round";
                                    } else
                                        throw new AlgorithmException("no solution! (cannot swap) participant=$participant poster=$poster round=$round ");
                                } else
                                    throw new AlgorithmException("no solution! participant=$participant poster=$poster round=$round ");
                            } else {


                                //echo "<br/> sortedPosters= " . implode($sortedPosters, ',');
                                // find the poster that is less selected and not selected by this participant
                                //if (isset($sortedPosters)) {
                                    foreach ($sortedPosters as $key => $p) {
                                        // if poster is not full and the current participant is not assigned to it
                                        //echo "<br/> key= $key p =$p";
                                        if (!$p->isRoundFull($round) && !$p->hasParticipant($participant)) {
                                            //echo "<br/>p=" . $p;
                                            // assign the current participant to this poster
                                            $poster = $p; //
                                            //end the for loop
                                            break;
                                        }
                                    }
//                                } else {
//                                    self::buildSchedules($posters, $rounds);
//                                    var_dump($sortedPosters);
//
//                                    throw new Exception("list of sorted poster is empty!");
//                                }
                            }
                            // if no poster
                        }
                        // improve the weight of this participant since they didn't get their first choise
                        //$participant->setWeight($participant->getWeight() - 1);
                    }
                    if ($step > 100)
                        throw new AlgorithmException("No solution [steps=$step]");
                }
            }
        }
        //self::buildSchedules($posters, $rounds);
        // balancing
        $allPosterSelected = FALSE;

        //echo "<br/> sortedPosters= " . implode($sortedPosters, ',');
        //var_dump($sortedPosters);
        while (!$allPosterSelected) {
            $allPosterSelected = TRUE;
            foreach ($posters as $key => $poster) {
                for ($round = 0; $round < $rounds; $round++) {
                    if ($poster->isRoundEmpty($round)) {
                        $allPosterSelected = FALSE;
                        $sortedPosters = self::sortPosterDESC($posters, $rounds);
                        $posterH = current($sortedPosters[$round]);

                        //get the Participant assigned with posterH with lowest weight but not assigned to the current poster
                        //echo "<br/> round $round poster $key";
                        //echo "<br/> " . implode($sortedPosters[$round], ',');
                        //echo "<br/> " . current($sortedPosters[$round]);
                        $participant = $posterH->getParticipantsLW($round, $poster);
                        $poster->add($participant, $round);
                        $posterH->remove($participant, $round);

                        // resort the sorted posters
                        //$sortedPosters = self::sortPosterDESC($posters, $rounds);
                        //echo "<br/> sortedPosters= " . implode($sortedPosters, ',');
                        // improve the weight of this participant since they didn't get their first choise
                        $participant->setWeight($participant->getWeight() - 2);
                    }
                }
            }
            //self::buildSchedules($posters, $rounds);
        }
        return $posters;
    }

    /**
     * This function sorts posters based on the number of participants who select it
     * The top of the list is the poster that less been selected.
     * @param Poster[] $posters
     * @param Participant[] $participants
     * @param int $round current round
     * @param Event $event 
     * @return Poster[] $sortedPosters
     */
    private static function sortPosters($posters, $participants, $round, $participant,$event) {
        //$rounds = $event->rounds;
        $p = []; // array_fill(1, count($posters) - 1, 0);
        
        
        foreach ($participants as $pa) {
            
            $preferences = $pa->getPreferences($event);
            //echo 'preferences participant='.$pa->name;
            //var_dump($preferences);
            foreach ($preferences as $preference) {
                //echo 'p='.$preference;
                // does this poster exisit?
                //var_dump($posters);
                if (isset($posters[$preference])) {
                    //echo '<br/>',"trying to assgin poster $preference to participant ".$pa->name;
                    if (!$posters[$preference]->isRoundFull($round) && !$posters[$preference]->hasParticipant($participant)) {
                        if(isset($p[$preference]))
                            $p[$preference] ++;
                        else
                            $p[$preference]=1;
                        //echo '</br>p=' . $preference . ' v=' . $p[$preference];
                    }
                } else
                    throw new AlgorithmException("Poster $preference does not exist");
            }
        }
        
        foreach ($posters as $key => $poster) {
            //echo ' e='.$poster->getParticipantsAtRound($round);
            if (!$poster->hasParticipant($participant) && !$poster->isRoundFull($round))
                if(isset ($p[$key]))
                    $p[$key]+= $poster->NumberOfParticipantsInRound($round);
                else
                    $p[$key]= $poster->NumberOfParticipantsInRound($round);
        }

        //echo '<h4>Sort Posters</h4>';
        //var_dump($p);
        asort($p);
        //echo 'asort($p)';
        //var_dump($p);
//        for($i=1;$i<count($p);$i++){
//            $sortedPosters[$i]=$posters[$p[$i]];
//        }
        $i = 0;
        foreach ($p as $key => $value) {
            $sortedPosters[$i] = $posters[$key];
            //echo '</br>key=' . $key . ' i=' . $i;
            $i++;
        }

        //$sortedPosters = $p;
        return $sortedPosters;
    }

    // TODO: needs to be sotred for each round
    private static function sortPosterDESC($posters, $rounds) {
        //echo "</br> start sorting DESC - { rounds = $rounds }";
        $p = [];
        for ($round = 0; $round < $rounds; $round++) {
            $p[$round] = [];
            foreach ($posters as $key => $poster) {
                $p[$round][$key - 1] = $poster->getTotalWeight($round);
            }
            //echo "</br>p=[" . implode($p[$round], ',') . "] r= $round";
            arsort($p[$round]);
            //echo "</br>p=[" . implode($p[$round], ',') . "] r= $round";
            $i = 0;
            foreach ($p[$round] as $key => $value) {
                $sortedPosters[$round][$i] = $posters[$key + 1];
                //echo '</br>key=' . $key . ' i=' . $i;
                $i++;
            }
        }
        return $sortedPosters;
    }

    static function buildSchedules($posters, $rounds) {
        echo "<hr/>";
        for ($round = 0; $round < $rounds; $round++) {
            echo "<h3>Round $round </h3><dl>";

            foreach ($posters as $key => $poster) {
                echo "<dt>Poster $key </dt>";
                echo "<dd>" . implode($poster->getParticipantsAtRound($round), ',') . "</dd>";
            }

            echo "</dl>";
        }
        echo '<hr/>';
    }

    public static function testSortPosters() {
        $posters = [1, 2, 3, 4];

        $participants = [];
        $participants[0] = new Participant([2, 1, 4]);
        $participants[1] = new Participant([1, 4, 2]);
        $participants[2] = new Participant([2, 3, 4]);

        echo 'Posters:';
        //var_dump($posters);
        echo implode($posters, ',');

        $sortedPosters = self::sortPosters($posters, $participants);
        echo '</br>Sorted Posters:';
        //var_dump($sortedPosters);
        echo implode($sortedPosters, ',');
        var_dump($sortedPosters);
    }

    public static function testBuild() {
        $rounds = 2;
        $max = 3;
        $posters = [
            '1' => new Poster(1, $rounds, $max),
            '2' => new Poster(2, $rounds, $max),
            '3' => new Poster(3, $rounds, $max)];

        $participants = [];
        $participants[0] = new Participant(1, [2, 1]);
        $participants[1] = new Participant(2, [2, 3]);
        $participants[2] = new Participant(3, [2, 1]);

        echo 'Posters:';
//            var_dump($posters);
        echo implode($posters, ',');

        $schedules = self::build($posters, $participants, $rounds);

//            echo '</br>Schedules:';
//            var_dump($schedules);
        echo '</br>Schedules:';
        echo implode($schedules, ',');

        self::buildSchedules($schedules, $rounds);
    }

    public static function testBuild2() {
        $rounds = 2;
        $max = 3;
        $min = 1;
        $posters = [
            '1' => new Poster(1, $rounds, $max, $min),
            '2' => new Poster(2, $rounds, $max, $min),
            '3' => new Poster(3, $rounds, $max, $min),
            '4' => new Poster(4, $rounds, $max, $min),
            '5' => new Poster(5, $rounds, $max, $min),
        ];

        $participants = [];
        $participants[] = new Participant(1, [2, 1]);
        $participants[] = new Participant(2, [2, 1]);
        $participants[] = new Participant(3, [2, 1]);
        $participants[] = new Participant(4, [2, 1]);
        $participants[] = new Participant(5, [2, 1]);
        $participants[] = new Participant(6, [2, 1]);
        $participants[] = new Participant(7, [2, 1]);
        $participants[] = new Participant(8, [2, 1]);

        echo 'Posters:';
//            var_dump($posters);
        echo implode($posters, ',');

        $schedules = self::build($posters, $participants, $rounds);

//            echo '</br>Schedules:';
//            var_dump($schedules);
        echo '</br>Schedules:';
        echo implode($schedules, ',');

        self::buildSchedules($schedules, $rounds);
    }

    public static function testBuild3($numberOfPosters, $numberOfParticipants, $rounds) {
        //$numberOfPosters = 10;
        //$numberOfParticipants =30;
        //$rounds = 3;

        echo "numberOfPosters $numberOfPosters <br/>";
        echo "numberOfParticipants $numberOfParticipants <br/>";
        echo "rounds $rounds <br/>";

        $max = ceil($numberOfParticipants / $numberOfPosters);
        $min = 1;
        $posters = [];
        for ($i = 1; $i <= $numberOfPosters; $i++)
            $posters[$i] = new Poster($i, $rounds, $max, $min);

        $participants = [];
        for ($i = 1; $i <= $numberOfParticipants; $i++)
            $participants[$i] = new Participant($i, [2, 1, 3, 5]);


        //echo 'Posters:';
//            var_dump($posters);
        //echo implode($posters, ',');

        $schedules = self::build($posters, $participants, $rounds);

//            echo '</br>Schedules:';
//            var_dump($schedules);
        //echo '</br>Schedules:';
        //echo implode($schedules, ',');

        self::buildSchedules($schedules, $rounds);

        echo '<hr/><h3>List of participants</h3>';
        foreach ($participants as $participant_id => $participant) {
            $i = 0;
            foreach ($posters as $poster_id => $poster) {
                if ($poster->hasParticipant($participant) && $participant->hasPreference($poster->getId())) {
                    $i++;
                }
            }
            echo "<br/>p= $participant_id v= $i";
        }
    }

}

//OneToManyAlgrithm::testSortPosters();
//echo '</br>-----------------------</br>';

//for($i=0;$i=10;$i++)
//OneToManyAlgorithm::testBuild3(rand(3, 20), rand(10, 100), rand(1, 5));
class AlgorithmException extends Exception{}