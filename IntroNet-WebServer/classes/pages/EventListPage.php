<?php
require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';
require_once './classes/components/HtmlTable.php';
require_once './classes/Database.php';

/**
 * Description of newPHPClass
 *
 * @author hussam
 */
class EventListPage extends Page {
    public function __construct($menu) {
        parent::__construct($menu,"Event List");
    }
    
    protected function build(PageBody &$body, SubMenu &$submenu) {
        
        $submenu->addLink("Add new Event", "#");
        $submenu->addLink("Show Event", "#");
        $submenu->addLink("Edit", "#");
        $submenu->addLink("Delete", "#");

        
        $table = new HtmlTable();
        $table->setHead(["#","Name","Info"]);
        
        $events = Database::get("Event");
        foreach ($events as $id => $event)
            $table->addRow([$id,'<a href="?page=Event&event='.$id.'">'.$event->name.'</a>',$event->address]);
        //$table->addRow(["2","Test2","Test Data"]);
        //$table->addRow(["3","Test3","Test Data"]);
        
        $body->addToCenter($table);
    }
}
