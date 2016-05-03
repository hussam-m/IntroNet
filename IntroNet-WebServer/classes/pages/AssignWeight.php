<?php

class AssignWeightPage extends Page {

    const UserType = "Planner";

    public function init(&$css, &$js, &$angularjs) {
        $angularjs = TRUE;
    }

    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "Assign Weight";

        if (isset($_GET['p']))
            $p = $_GET['p'];
        else if (isset($_POST['p']))
            $p = $_POST['p'];
        else
            throw new Exception("No Participant");

        $participant = Participant::getParticipant($p);
        //var_dump($participant);
        $body->addToCenter(new CustomHTML("<h3>Change the weight of {$participant->name}</h3>"));
        $form = new Form("AssignWeight");
        //$message = new Message("Update Assign Weight", Message::WARNING);
        //$body->addToTop($message);
        $form->addInput(Input::checkBox("vip", "VIP"));
        //$form->addInput(Input::selectInput("listOfParticipant", "List of Participants", array("Rania Alkhazaali", "Chakshu")));
        //$message = new Message("Select Weight", Message::WARNING);
        //$form->addInput(Input::checklist("VIP", "Are you VIP?", array(array(1,"VIP")), $min=0, False));
        $inputA = Input::textInput("weight", "Weight", $participant->weight);
        $inputA->showOn = '!vip';
        $form->addInput($inputA);
        $form->addInput(Input::hiddenInput("p", $participant->id));


        $body->addToCenter($form);
    }
    public function callBack($data, $action, \PageBody &$body) {
        if(isset($data["vip"]))
           $weight=-1;
        else
            $weight=$data["weight"];
        
        Database::update("Participant", "weight=$weight", "participant_id=".$data["p"]);
    }

}
