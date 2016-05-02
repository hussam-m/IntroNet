<?php

require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';
require_once './classes/components/Form.php';

class NewConferencePage extends Page {
    const UserType = "Planner";
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "Conference";
        
        
        $form = new Form("Conference");
        
        
        $form->addInput(Input::textInput("Conference Name", "Conference Name",'', True));
        
        $form->addInput(Input::createGroupInput(array(
            Input::dateInput("eventStart","Registration Start Date",'', True),
            Input::timeInput("eventStartTime","Registration Start Time",'', True)
            ))); 
        $form->addInput(Input::createGroupInput(array(
            Input::dateInput("eventDeadline","Registration Deadline Date",'', True),
            Input::timeInput("eventDeadlineTime","Registration Deadline Time",'', True)
            ))); 
        
        $form->addInput(Input::tokenInput("organizations","Organizations", True));
        $body->addToCenter($form);

    }
}
        



