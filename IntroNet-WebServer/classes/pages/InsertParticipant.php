<?php
require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';

//$fnameErr = $emailErr = $lnameErr = $contactnoErr = "";

/**
 * Description of newPHPClass
 *
 * @author hussam
 */
class InsertParticipant extends Page {
    
    public function callBack($data, $action, \PageBody &$body) {
        $body->addToTop(new Message("Please complete all the details", Message::DANGER));
    }

    
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "Insert Participant";
       
        $form = new Form("Participant");
        $form->addInput(Input::textInput("firstName","First Name"));
    
      
        $form->addInput(Input::textInput("lastName","Last Name"));
        $form->addInput(Input::textInput("email","Email Address"));
        $form->addInput(Input::textInput("contactNo","Contact Number"));
        $form->addInput(Input::selectInput("disability", "Are you handicapped?", array("Yes", "No")));
        
    
        
    
        $body->addToCenter($form);
    }
}


