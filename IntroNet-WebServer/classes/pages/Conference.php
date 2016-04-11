<?php

require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';
require_once './classes/components/Form.php';

class Conference extends Page {
        
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "Conference";
        
        
        $form = new Form("Event");
        
        
        $form->addInput(Input::textInput("Conference Name", "Conference Name", $defaultValue='', $required=True));
        
        $form->addInput(Input::createGroupInput([
            Input::dateInput("eventStart","Registration Start Date"),
            Input::timeInput("eventStartTime","Registration Start Time")
            ])); 
        $form->addInput(Input::createGroupInput([
            Input::dateInput("eventDeadline","Registration Deadline Date"),
            Input::timeInput("eventDeadlineTime","Registration Deadline Time")
            ])); 
        $body->addToCenter($form);

    }
}
        



