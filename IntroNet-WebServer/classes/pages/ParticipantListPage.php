<?php

/**
 * Description of ParticipantListPage
 *
 * @author hussam
 */
class ParticipantListPage extends Page {
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $events = Event::getEvents();
        $form = new Form("ParticipantList");
        //var_dump($events);
        $eventlist = [];
        foreach ($events as $key =>  $event) {
            //echo $event->name;
            $eventlist[$key] = [$event->event_id,$event->name];
        }
        //var_dump($eventlist);
        $form->addInput(Input::selectInput("event", "Select an Event", $eventlist));
        $form->autoSubmit= TRUE;
        $form->keepData = TRUE;
        $body->addToTop($form);
    }
    
    public function callBack($data, $action, \PageBody &$body) {
        $table = new HtmlTable();
        $table->setHead(["First Name","Last Name","Organisation"]);
        $participants = Participant::getParticipants(0);
        //$body->addToCenter(new Message(var_dump($participants)));
        foreach ($participants as $key => $participant) {
            $table->addRow([$participant->fname,$participant->lname,$participant->organisation]);
        }
        $body->addToCenter($table);
    }
}
