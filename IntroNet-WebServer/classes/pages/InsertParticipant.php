<?php
class InsertParticipant extends Page {
    const UserType = "Planner";
    public function callBack($data, $action, \PageBody &$body) {
        //$body->addToTop(new Message("Please complete all the details", Message::DANGER));
       if(isset($data['insert'])){
            $conference = $data["conference"];
            $fname = Validation::validate($data["firstName"], Validation::NAME);
            $lname = Validation::validate($data["lastName"], Validation::NAME);
            $email = Validation::validate($data["email"], Validation::EMAIL);
            $organisation= $data['organisation'];
            $phone = $data['contactNo'];
            $disability = isset($data['disability']);
            $vip = isset($data['vip']);
            Participant::addParticipant($conference, $fname, $lname, $email, $organisation, $disability, $vip,$phone);
       }
        //
        //var_dump($data);
    }

    
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "Insert Participant";
        // get a list of conferences
        $conferences = Conference::getConferences();
        
        // form to select a conference
        $form1 = new Form("insertParticipant");
        $form1->autoSubmit= TRUE;
        $form1->keepData= TRUE;
        $form1->addInput(Input::selectInput("conference", "Conference", $conferences));
        $body->addToCenter($form1);
        
        // get selected conference
        if(isset($_POST['conference']))
            $conference = Conference::getConference ($_POST['conference']);
        else
            $conference = $conferences[0];
        
        $form = new Form("insertParticipant");
        $form->addInput(Input::selectInput("organisation", "Organisation", $conference->getOrganisations()));
        $form->addInput(Input::createGroupInput(array(
            Input::textInput("firstName","First Name","",TRUE),
            Input::textInput("lastName","Last Name","",TRUE)
        )));
        $form->addInput(Input::createGroupInput(array(
            Input::textInput("email","Email Address","",TRUE),
            Input::textInput("contactNo","Contact Number")
        )));
        $form->addInput(Input::createGroupInput(array(
            Input::checkBox("disability", "Handicapped?"),
            Input::checkBox("vip", "VIP")
        )));
        $form->addInput(Input::hiddenInput("insert", "true"));
        $form->addInput(Input::hiddenInput("conference", $conference->id));
    
        
    
        $body->addToCenter($form);
    }
}


