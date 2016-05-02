<?php

/**
 * This is a webpage for Conference
 *  @property Conference $conference The confernce object to be used in the page
 * 
 */
class ConferencePage extends Page {

    const UserType = "Planner";

    private $conference;

    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        // get event
        if (isset($_GET['conference'])) {
            $this->conference = Conference::getConference($_GET['conference']);
        }

        // if event does not exist, show error message
        if ($this->conference == null) {
            $body->addToTop(new Message("No Conference", Message::DANGER));
            return;
        }

        $this->pageName = $this->conference->conference_name;
        $subPage = isset($_GET['subpage']) ? $_GET['subpage'] : '';

        $body->addToTop(new CustomHTML("
            <div class='page-header hidden-print'>
                <h1> " . $this->conference->conference_name . "</h1>
            </div>
        "));

        if ($subPage == '')
            $body->addToCenter(new CustomHTML("
                <dl class='' style='font-size:18px'>
                    <dt>Conference Name</dt>
                    <dd>" . $this->conference->conference_name . "</dd></br>
                    <dt>Registration Start Date</dt>
                    <dd>" . $this->conference->registration_start_date . "</dd></br>
                    <dt>Registration Start Time</dt>
                    <dd>" . $this->conference->registeration_deadline_date . "</dd></br>
                    <dt>Registration End Date</dt>
                    <dd>" . $this->conference->registration_start_time . "</dd></br>
                    <dt>Registration End Time</dt>
                    <dd>" . $this->conference->registration_deadline_time . "</dd></br>
                </dl>
            "));
        elseif ($subPage == 'PrintAllSchedule') {
            $this->printAllSchedule($body);
        } elseif ($subPage == "BuildSchedule") {
            $this->buildSchedule($body);
        }

        $submenu->addLink("Conference Details", "?page=Conference&conference=" . $this->conference->conference_id, $subPage == '');
        $submenu->addLink("Update Conference", "?page=Conference&conference=" . $this->conference->conference_id . "&subpage=update", $subPage == 'update');
        $submenu->addSplitter();
        $submenu->addLink("Send Email Invitation", "?page=Conference&conference=" . $this->conference->conference_id . "&subpage=send", $subPage == 'send');
        $submenu->addLink("Add Participant", "#");
        $submenu->addSplitter();
        $submenu->addLink("All Participants", "#", false, false, $this->conference->getNumberOfParticipants());
        $submenu->addLink("New Participants", "#", false, false, $this->conference->getNumberOfNewParticipants());
        $submenu->addLink("Participants with no schedule", "#", false, false, $this->conference->getNumberOfParticipants() - $this->conference->getNumberOfScheduleParticipants());
        $submenu->addLink("Conference Attendances", "#", false, false, 0);
        $submenu->addSplitter();

        $submenu->addLink("Build Schedule", "?page=Conference&subpage=BuildSchedule&conference=" . $this->conference->conference_id, $subPage == "BuildSchedule");
        $submenu->addLink("Send Schedule", "#");
        $submenu->addLink("Print All Schedule", "?page=Conference&subpage=PrintAllSchedule&conference=" . $this->conference->conference_id, $subPage == 'PrintAllSchedule');
        $submenu->addSplitter();
        $submenu->addDangerLink("Delete Conference", "#");
    }

    private function printAllSchedule(PageBody &$body) {

        /* @var $events Event[]  */
        $events = $this->conference->getEvents();

        /* @var $participants Participant[]  */
        $participants = Conference::getParticipants($_GET['conference']);

        foreach ($participants as $p) {
            $body->addToCenter(new CustomHTML("
                <div class='page-header visible-print-block'>
                    <h1> " . $this->conference->conference_name . /* " ".$this->conference->registration_start_date. */"</h1>
                </div>
                "));

            $body->addToCenter(new CustomHTML("<h2>" . $p->name . "</h2>"));
            //echo $p->name, "</br>";
            foreach ($events as $event) {


                // echo $event->name;
                $schedule = $p->getSchedule($event);
                //var_dump($schedule);

                $body->addToCenter(new CustomHTML("<h3>$event->name</h3>"));
                if (count($schedule) > 0) {
                    $table = new HtmlTable();

                    if ($event->type == Event::ONETOMANY) {
                        $table->setHead(array("Round", "Poster"));
                        foreach ($schedule as $row) {
                            $table->addRow(array($row->roundNumber, $row->poster_name));
                        }
                    } else if ($event->type == Event::ONETOONE) {
                        $table->setHead(array("Round", "Table"));
                        foreach ($schedule as $row) {
                            $table->addRow(array($row->roundNumber, $row->table_name+1));
                        }
                    }
                    $body->addToCenter($table);
                } else {
                    $body->addToCenter(new CustomHTML("<h5>You are not registered to this event.</h5>"));
                }
            }

            $body->addToCenter(new CustomHTML("<div class='new-page'></div>"));
        }


        $body->addToBottom(new CustomHTML('
<script>
    window.print();
</script>        
        '));
    }

    private function buildSchedule(PageBody &$body) {

        /* @var $events Event[]  */
        $events = $this->conference->getEvents();

        try {
            foreach ($events as $event) {
                $schedule = $event->buildSchedule();
                $body->addToCenter(new CustomHTML("<h2>" . $event->name . "</h2>"));
                $table = new HtmlTable();

                if ($event->type == Event::ONETOMANY)
                    $tableHead = array("Poster");
                else
                    $tableHead = array("Table");

                for ($i = 1; $i <= $event->rounds; $i++)
                    $tableHead[$i] = "Round " . $i;

                $table->setHead($tableHead);
//            echo '$schedule';
                //var_dump($schedule);

                foreach ($schedule as $key => /* @var $poster Poster */ $poster) {
                    //echo '$poster';
                    //var_dump($poster->rounds);
                    if ($event->type == Event::ONETOMANY)
                        $table->addRow(array_merge(array($poster->name), $poster->rounds));
                    else
                        $table->addRow(array_merge(array($poster->name), $poster->rounds));
                }
                $body->addToCenter($table);

                foreach ($schedule as $key => /* @var $poster Poster */ $poster) {
                    //var_dump($poster->rounds);
                    foreach ($poster->rounds as $key => $round) {
                        foreach ($round as /* @var $participant Participant */ $participant) {
                            //echo $poster->name," round $key ",$participant->name,"</br>";
                            $schedule_id = FALSE;

                            $schedule_id = Database::insert("Schedule", array(
                                        "event_id" => $event->event_id,
                                        "participant_id" => $participant->id,
                                        "roundNumber" => $key + 1
                                            ), "schedule_id");
                            //var_dump($schedule_id);

                            if ($schedule_id) {
                                if ($event->type == Event::ONETOMANY) {
                                    //var_dump(
                                            Database::insert("Meeting_Poster", array(
                                                "schedule_id" => $schedule_id,
                                                "poster_id" => $poster->id
                                            ));
                                    //);
                                } else {
                                    //var_dump(
                                            Database::insert("Meeting_Table", array(
                                                "schedule_id" => $schedule_id,
                                                "table_number" => $poster->id
                                            ));
                                    //);
                                }
                            }
                        }
                    }
                }
            }
        } catch (AlgorithmException $e) {
            $body->addToTop(new Message($e->getMessage(), Message::DANGER));
        }
    }

}
