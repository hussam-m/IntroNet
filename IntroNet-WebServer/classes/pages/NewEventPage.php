<?php
require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';

/**
 * Description of newPHPClass
 *
 * @author hussam
 */
class NewEventPage extends Page {
    public function __construct($menu) {
        parent::__construct($menu,"New Event");
    }
    
    public function callBack($data, $action, \PageBody &$body) {
        $body->addToTop(new Message("Please fill all the information", Message::DANGER));
    }

    
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $form = new Form("Event");
        $form->addInput(Input::textInput("eventName","Event Name"));
        $form->addInput(Input::textareaInput("eventAddress","Address"));
        
        $form->addInput(Input::createGroupInput([
            Input::dateInput("eventDay","Day"),
            Input::timeInput("eventTime","time")
            ]));
        
        $form->addInput(Input::createGroupInput([
            Input::dateInput("eventDeadline","Registration Deadline"),
            Input::timeInput("eventDeadlineTime","Registration Deadline Time")
            ])); 
        
        $form->addInput(Input::textInput("eventFee","Registration Fee"));
        $form->addInput(Input::textInput("eventStuFee","Student Fee"));
        $form->addInput(Input::tokenInput("organizations","Organizations"));
        
        $body->addToCenter($form);
    }
}
