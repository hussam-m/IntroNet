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
        parent::__construct("New Event",$menu);
    }
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $form = new Form("NewEvent");
        $form->addInput(Input::textInput("eventName","Event Name"));
        $form->addInput(Input::textareaInput("eventAddress","Address"));
        $form->addInput(Input::dateInput("eventDay","Day"));
        $form->addInput(Input::timeInput("eventTime","time"));
        $form->addInput(Input::dateInput("eventDeadline","Registration Deadline"));
        $form->addInput(Input::timeInput("eventDeadlineTime","Registration Deadline Time"));
        $form->addInput(Input::textInput("eventFee","Registration Fee"));
        $form->addInput(Input::textInput("eventStuFee","Student Fee"));
        $form->addInput(Input::textInput("organizations","Organizations"));
        
        $body->addToCenter($form);
    }
}
