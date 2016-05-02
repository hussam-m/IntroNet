<?php

class Schedule {
    public static function get($event_id){
        
    }
    public static function build($event_id){
        //$event = new Event($event_id);
        $event = Event::getEvent($event_id);
        //var_dump($event);
        $participants= $event->getParticipants();
        //var_dump($participants);
        $posters= $event->getPosters();
        var_dump($posters);
    }
}
