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
        $events = Database::get("Event");
        if(isset($events)){
            $table = new HtmlTable();
            $table->setHead(["#","Name","Info"]);
            foreach ($events as $id => $event)
                $table->addRow([$id,'<a href="?page=Event&event='.$id.'">'.$event->name.'</a>',$event->address]);
            $body->addToCenter($table);
        }else
            $body->addToCenter (new Message("There is no event to show",  Message::INFO));
        
        
    }
}
