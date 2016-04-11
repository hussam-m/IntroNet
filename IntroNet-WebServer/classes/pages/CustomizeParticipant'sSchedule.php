<?php

require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';
require_once './classes/components/Form.php';

class CustomizeParticipantsSchedule extends Page {
        
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "CustomizeParticipantsSchedule";
        
        
        $form = new Form("Customize");
        $message = new Message("Customize Participant's Schedule", Message::WARNING);
        $body->addToTop($message);
        
        $form->addInput(Input::selectInput("listOfParticipant", "List of Participants", array("Rania Alkhazaali", "Chakshu")));
        $form->addInput(Input::checklist("preference", "Preference", array("Poster1", "Poster2", "Poster3"), $min=1, $disabled=false));
        $form->addInput(Input::checklist("categoryOfPeople", "Category Of People", array("Org1", "Org2", "Org3"), $min=1, $disabled=false));
        
        $body->addToCenter($form);

    }
}
        


