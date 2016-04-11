<?php
require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';
require_once './classes/components/Form.php';

class AssignWeightPage extends Page {
        
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "AssignWeight";
        
        
        $form = new Form("Event");
        $message = new Message("Update Assign Weight", Message::WARNING);
        $body->addToTop($message);
        
        $form->addInput(Input::selectInput("listOfParticipant", "List of Participants", array("Rania Alkhazaali", "Chakshu")));
        
        $form->addInput(Input::textInput("Weight", "AssignWeight", $defaultValue='', $required=True, $disabled=True));
        $body->addToCenter($form);

    }
}
        
