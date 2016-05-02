<?php

class AssignWeightPage extends Page {
    const UserType = "Planner";
    public function init(&$css, &$js, &$angularjs) {
        $angularjs=TRUE;
    }
        
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "Assign Weight";
        
        
        $participant = Participant::getParticipant($_GET['p']);
        //var_dump($participant);
        $form = new Form("AssignWeight");
        //$message = new Message("Update Assign Weight", Message::WARNING);
        //$body->addToTop($message);
        $form->addInput(Input::checkBox("vip", "VIP") );
        //$form->addInput(Input::selectInput("listOfParticipant", "List of Participants", array("Rania Alkhazaali", "Chakshu")));
        
        //$message = new Message("Select Weight", Message::WARNING);
        //$form->addInput(Input::checklist("VIP", "Are you VIP?", array(array(1,"VIP")), $min=0, False));
        $inputA = Input::textInput("weight", "Weight",$participant->weight);
        $inputA->showOn='!vip';
        $form->addInput($inputA);
        
        
        $body->addToCenter($form);

    }
}
        

