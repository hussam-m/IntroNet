<?php
require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';
require_once './classes/components/Form.php';

class AssignWeightPage extends Page {
    public function init(&$css, &$js, &$angularjs) {
        $angularjs=TRUE;
    }
        
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "AssignWeight";
        
        
        $form = new Form("AssignWeight");
        $message = new Message("Update Assign Weight", Message::WARNING);
        $body->addToTop($message);
        
        $form->addInput(Input::selectInput("listOfParticipant", "List of Participants", array("Rania Alkhazaali", "Chakshu")));
        
        //$message = new Message("Select Weight", Message::WARNING);
        $form->addInput(Input::checklist("VIP", "Are you VIP?", array(array(1,"VIP")), $min=0, False));
        $inputA = Input::textInput("weight", "Others");
        $inputA->showOn='VIP=="1"';
        $form->addInput($inputA);
        
        
        //$form->addInput(Input::textInput("Weight", "Others", " ", True, True));
        $body->addToCenter($form);

    }
}
        

