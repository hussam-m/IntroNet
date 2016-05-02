<?php

class ParticipantSchedulePage extends Page {
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $participant = Participant::getParticipant($_GET['p']);
        //var_dump($participant);
        
        $schedule = $participant->getSchedule();
        //var_dump($schedule);
        
        $body->addToTop(new CustomHTML("
            <div class='page-header'>
                <h1> ".$participant->fname." ".$participant->lname ." Schedule</h1>
            </div>
        "));
        
        $table= new HtmlTable();
        $table->setHead( array("Event","Round") );
        
        foreach ($schedule as $key => $row) {
            $table->addRow(array($row->event_id, $row->roundNumber ));
        }
        
        
        $body->addToCenter($table);
        
        $submenu->addLink("Print schedule", "javascript:window.print()");
        $submenu->addLink("Send schedule to my email", "#");
    }
}
