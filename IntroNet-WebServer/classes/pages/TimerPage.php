<?php
/**
 * Description of TimerPage
 *
 * @author hussam
 */
class TimerPage extends Page{
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $event= Event::getEvents("where TIMESTAMP(`startDate`,`startTime`) > now() order by startDate, startTime LIMIT 0 , 1")[0];
        $html = new CustomHTML("Event=$event->name");
        $body->addToCenter($html);
        
    }
}
