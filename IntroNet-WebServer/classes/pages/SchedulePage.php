<?php

class SchedulePage extends Page {
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $event_id = $_GET['event'];
        $schedule = Schedule::build($event_id);
        
        //$body->addToCenter(new CustomHTML( "password= ".Invitation::random_password() ));
    }
}
