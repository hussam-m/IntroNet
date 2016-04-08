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
class EventPage extends Page {
    
    private $event;
    public function callBack($data, $action, PageBody &$body) {
        $body->addToTop(new Message("Event was created",  Message::SUCCESS));
        $this->event= new stdClass(); // for testing - should be Event class
        $this->event->name = $data['eventName'];
        $this->event->address = $data['eventAddress'];
        
        // save event
        $this->event->id = Database::insert("Event", $this->event);
    }

    protected function build(PageBody &$body, SubMenu &$submenu) {
        
        $subPage = isset($_GET['subpage'])?$_GET['subpage']:'';
        
        // get event
        if(isset($_GET['event'])){
            $this->event = Event::getEvent($_GET['event']);
        } 
        
        // if event does not exist, show error message
        if($this->event==null){
            $body->addToTop(new Message("No Event",  Message::DANGER));
            return;
        }
        
        // set the name of the page
        $this->pageName = $this->event->name;
        
  
        $submenu->addLink("Event Details", "?page=Event&event=".$this->event->Event_id,$subPage=='');
        $submenu->addLink("Update Event", "?page=Event&event=".$this->event->Event_id."&subpage=update",$subPage=='update');
        $submenu->addSplitter();
        $submenu->addLink("Send Email Invitation", "?page=Event&event=".$this->event->Event_id."&subpage=send",$subPage=='send');
        $submenu->addLink("Add VIP Participant", "#");
        $submenu->addSplitter();
        $submenu->addLink("Show All Participants", "#",false,false,100);
        $submenu->addLink("Show Missing Participants", "#",false,false,90);
        $submenu->addLink("Show Event Attendances", "#",false,false,10);
        $submenu->addSplitter();
        $submenu->addLink("Build Schedule", "#");
        $submenu->addLink("Send Schedule", "#");
        $submenu->addLink("Start Timer", "#");
        $submenu->addSplitter();
        $submenu->addDangerLink("Delete Event", "#");
        
        $body->addToTop(new CustomHTML("
            <div class='page-header'>
                <h1> ".$this->event->name ."</h1>
            </div>
        "));
        
        if($subPage=='')
            $body->addToCenter(new CustomHTML("
                <dl class='dl-horizontal' style='font-size:18px'>
                    <dt>Name</dt>
                    <dd>".$this->event->name."</dd>
                    <dt>Start Date</dt>
                    <dd>".$this->event->getStartDate()."</dd>
                    <dt>Start Time</dt>
                    <dd>".$this->event->getStartTime()."</dd>
                    <dt>End Date</dt>
                    <dd>".$this->event->getEndDate()."</dd>
                    <dt>End Time</dt>
                    <dd>".$this->event->getEndTime()."</dd>
                    <dt>Event Type</dt>
                    <dd>".$this->event->getType()."</dd>
                </dl>
            "));
        else if($subPage=='update'){
            $form = new Form("Event");
            $form->addInput(Input::textInput("eventName", "Event Name",$this->event->name));
            $form->addInput(Input::textareaInput("eventAddress", "Event Address",$this->event->address));
            $body->addToCenter($form);
        }
        else if($subPage=='send'){
            $form = new Form("Event");
            $form->addInput(Input::tokenInput("emails", "Send To:"));
            $form->addInput(Input::textInput("subject", "Subject","Invitation to ".$this->event->name));
            $form->addInput(Input::textareaInput("message", "Message"));
            $body->addToCenter($form);
        }
//        $table = new HtmlTable();
//        $table->addRow(["Name",  $this->event->name]);
//        $table->addRow(["Address",  $this->event->address]);
//        
//        $body->addToCenter($table);
        
    }
}
