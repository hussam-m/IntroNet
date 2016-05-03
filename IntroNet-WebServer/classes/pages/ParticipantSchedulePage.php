<?php

class ParticipantSchedulePage extends Page {

    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $participant = Participant::getParticipant($_GET['p']);
        $body->addToTop(new CustomHTML("
            <div class='page-header'>
                <h1> " . $participant->fname . " " . $participant->lname . " Schedule</h1>
            </div>
        "));
        $conference = Conference::getConference($participant->conference_id);

        foreach ($conference->getEvents() as $event) {

            $schedule = $participant->getSchedule($event);

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
                        $table->addRow(array($row->roundNumber, $row->table_name + 1));
                    }
                }
                $body->addToCenter($table);
            } else {
                $body->addToCenter(new CustomHTML("<h5>You are not registered to this event.</h5>"));
            }
        }

        $submenu->addLink("Print schedule", "javascript:window.print()");
        //$submenu->addLink("Send schedule to my email", "#");
    }

}
