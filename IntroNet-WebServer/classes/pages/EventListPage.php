<?php
require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';
require_once './classes/components/HtmlTable.php';
require_once './classes/Database.php';

/**
 * EventListPage shows a list of events that was added by the Planner
 * @category Page
 * @author hussam
 */
class EventListPage extends Page {
    
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "Event List";
        
        $submenu->addLink("Add new Event", "#");
        $submenu->addLink("Show Event", "#");
        $submenu->addLink("Edit", "#");
        $submenu->addLink("Delete", "#");

        
        
        // show a list of events on a table
        //$events = Database::get("Event");
        $events = Event::getEvents();
        if(isset($events) && count($events)>0){
            $table = new HtmlTable();
            $table->setHead(["#","Name","Start Date","End Date","Type"]);
            foreach ($events as $id => $event)
                $table->addRow([$id,'<a href="?page=Event&event='.$event->Event_id.'">'.$event->name.'</a>',$event->getStartDate(),$event->getEndDate(),$event->getType()]);
            $body->addToCenter($table);
        }else
            $body->addToCenter (new Message("There is no event to show",  Message::INFO));
        
        
    }
}
