<?php

require_once '../Participant.php';
require_once '../Poster.php';

class OneToManyAlgorithm {

    /**
     * This function build schedules using participants' preferences
     * @param Poster[]      $posters        a list of posters
     * @param Participant[] $participants   a list of participants
     * @param int           $rounds         number of rounds
     * @return array        schedules       a list of schedules (each participant have one schedule)
     */
    public static function build($posters, $participants, $rounds) {
        if ($rounds > count($posters))
            throw new Exception("Can not have rounds more than the number of posters");

        
        // Note: maybe it's better to swap inner loop with the outer loop
        foreach ($participants as $participant) {
            for ($round = 0; $round < $rounds; $round++) {
                // TODO: resort $participants for each round
                
                // sort posters based on number of selections
                $sortedPosters = self::sortPosters($posters, $participants,$round,$participant);
                //echo "<br/> sortedPosters= " . implode($sortedPosters, ',');
                
                $posterSelected = FALSE;
                //get poster that participant wants to visit
                $poster = $posters[$participant->preferences[$round]];
                $step = 0;
                while (!$posterSelected) {
                    echo '</br>current preference=' . $participant->preferences[$round];
                    echo ' - current round=' . $round;
                    echo ' - current participant=' . $participant;
                    echo ' - current poster=' . $poster;
                    // if poster is not full
                    echo "poster $poster is full?" . $poster->isRoundFull($round);
                    if ($poster->isRoundFull($round) === FALSE) {
                        //add the poster to participant's schedule
                        echo "No </br> add participant( $participant ) to poster ( $poster ) at round $round";
                        $poster->add($participant, $round);
                        $posterSelected = TRUE;

                        // swap preferences in case if we used another preference
                        if ($step != 0) {
                            echo "<br/> Start Swap " . implode($participant->preferences, ',');
                            $temp = $participant->preferences[$round];
                            $participant->preferences[$round] = $participant->preferences[$round + $step];
                            $participant->preferences[$round + $step] = $temp;
                            echo "<br/> End Swap " . implode($participant->preferences, ',');
                        }
                    } else {
                        echo " Yes";
                        // does the participant have more preferences?
                        
                        if (count($participant->preferences) > $round + $step+1) {
                            $step++;
                            //echo "test";
                            //echo "<br/> #preferences=".count($participant->preferences)." r+s=". ($round + $step);
                            echo "<p style='color:red'>participant have another preferences";
                            // choose the next preference
                            $poster = $posters[$participant->preferences[$round + $step]];
                            echo "<br/> $poster is the selected</p>";
                        } else {
                            // if there is no poster can this participant join
                            if(!isset($sortedPosters)){
                                self::buildSchedules($posters, $rounds);
//                                $sortedPosters = self::sortPosterDESC($posters, $rounds);
//                                foreach ($sortedPosters as $key => $p) {
//                                    $lowP = $p->getParticipantsLW($round);
//                                    if(!$p->hasParticipant($participant)){
//                                        
//                                        break;
//                                    }
//                                }
//                                continue;
                                throw new Exception("no solution!");
                            }
                            $step=0;
                            echo "<br/> sortedPosters= " . implode($sortedPosters, ',');
                            // find the poster that is less selected and not selected by this participant
                            foreach ($sortedPosters as $key => $p) {
                                // if poster is not full and the current participant is not assigned to it
                                echo "<br/> key= $key p =$p";
                                if (!$p->isRoundFull($round) && !$p->hasParticipant($participant)) {
                                    echo "<br/>p=".$p;
                                    // assign the current participant to this poster
                                    $poster = $p; //
                                    //end the for loop
                                    break;
                                }
                            }
                            
                            // if no poster
                            
                        }
                        // improve the weight of this participant since they didn't get their first choise
                        $participant->setWeight($participant->getWeight()-2);
                    }
                    if($step>20)
                        throw new Exception("No solution");
                }
            }
        }
        self::buildSchedules($posters, $rounds);
        // balancing
        $allPosterSelected=FALSE;
        
        //echo "<br/> sortedPosters= " . implode($sortedPosters, ',');
        //var_dump($sortedPosters);
        while(!$allPosterSelected){
            $allPosterSelected = TRUE;
        foreach ($posters as $key => $poster) {
            for ($round = 0; $round < $rounds; $round++) {
                if ($poster->isRoundEmpty($round)) {
                    $allPosterSelected=FALSE;
                    $sortedPosters = self::sortPosterDESC($posters, $rounds);
                    $posterH = current($sortedPosters[$round]);

                    //get the Participant assigned with posterH with lowest weight but not assigned to the current poster
                    echo "<br/> round $round poster $key";
                    echo "<br/> " . implode($sortedPosters[$round], ',');
                    echo "<br/> " . current($sortedPosters[$round]);
                    $participant = $posterH->getParticipantsLW($round, $poster);
                    $poster->add($participant, $round);
                    $posterH->remove($participant, $round);

                    // resort the sorted posters
                    //$sortedPosters = self::sortPosterDESC($posters, $rounds);
                    //echo "<br/> sortedPosters= " . implode($sortedPosters, ',');
                    
                    // improve the weight of this participant since they didn't get their first choise
                    $participant->setWeight($participant->getWeight()-2);
                }
            }
        }
        self::buildSchedules($posters, $rounds);
        }
        return $posters;
    }

    /**
     * This function sorts posters based on the number of participants who select it
     * The top of the list is the poster that less been selected.
     * @param Poster[] $posters
     * @param Participant[] $participants
     * @return Poster[] $sortedPosters
     */
    private static function sortPosters($posters, $participants,$round,$participant) {
        $p = [];// array_fill(1, count($posters) - 1, 0);
        foreach ($participants as $participant) {
            foreach ($participant->preferences as $preference) {
                if(!$posters[$preference]->isRoundFull($round)){
                    $p[$preference] ++;
                    echo '</br>p=' . $preference . ' v=' . $p[$preference];
                }
            }
        }
        
        foreach ($posters as $key=> $poster) {
            //echo ' e='.$poster->getParticipantsAtRound($round);
            if(!$poster->hasParticipant($participant) && !$poster->isRoundFull($round))
                $p[$key]+= $poster->NumberOfParticipantsInRound($round);
        }

        echo '<h4>Sort Posters</h4>';
        var_dump($p);
        asort($p);
        var_dump($p);
//        for($i=1;$i<count($p);$i++){
//            $sortedPosters[$i]=$posters[$p[$i]];
//        }
        $i = 0;
        foreach ($p as $key => $value) {
            $sortedPosters[$i] = $posters[$key];
            echo '</br>key=' . $key . ' i=' . $i;
            $i++;
        }

        //$sortedPosters = $p;
        return $sortedPosters;
    }

    // TODO: needs to be sotred for each round
    private static function sortPosterDESC($posters, $rounds) {
        echo "</br> start sorting DESC - { rounds = $rounds }";
        $p = [];
        for ($round = 0; $round < $rounds; $round++) {
            $p[$round] = [];
            foreach ($posters as $key => $poster) {    
                $p[$round][$key-1] = $poster->getTotalWeight($round);
            }
            echo "</br>p=[" . implode($p[$round], ',') . "] r= $round";
            arsort($p[$round]);
            echo "</br>p=[" . implode($p[$round], ',') . "] r= $round";
            $i = 0;
            foreach ($p[$round] as $key => $value) {
                $sortedPosters[$round][$i] = $posters[$key + 1];
                echo '</br>key=' . $key . ' i=' . $i;
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
        $posters = ['1' => new Poster(1, $rounds, $max),
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
            '1' => new Poster(1, $rounds, $max,$min),
            '2' => new Poster(2, $rounds, $max,$min),
            '3' => new Poster(3, $rounds, $max,$min),
            '4' => new Poster(4, $rounds, $max,$min),
            '5' => new Poster(5, $rounds, $max,$min),
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
    
        public static function testBuild3() {
        $rounds = 3;
        $max = 10;
        $min = 1;
        $posters = [];
        for($i=1;$i<=10;$i++)
            $posters[$i] = new Poster($i, $rounds, $max,$min);
        
        $participants = [];
        for($i=1;$i<=100;$i++)
            $participants[$i] = new Participant($i, [2, 1,3]);


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

}

//OneToManyAlgrithm::testSortPosters();
echo '</br>-----------------------</br>';
OneToManyAlgorithm::testBuild3();
