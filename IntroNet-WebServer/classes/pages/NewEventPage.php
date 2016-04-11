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
    
    public function callBack($data, $action, \PageBody &$body) {
        $body->addToTop(new Message("Please fill all the information", Message::DANGER));
    }

    
    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "New Event";
        
        $form = new Form("Event");
        $form->addInput(Input::textInput("eventName","Event Name"),$defaultValue='',$required=TRUE);
        
        $form->addInput(Input::selectInput("typeOfEvent", "Type Of Event", array("One to One", "One to Many")));
        
        $form->addInput(Input::textInput("numberOfRounds","Number of Rounds"));
        $form->addInput(Input::textInput("timeOfSessions","Length of the Sessions and Breaks"));
        $form->addInput(Input::textInput("lengthOfEntireEvent","Length of The Entire Event"));
        
        $form->addInput(Input::textInput("minimumNumberOfParticipant","Minimum Number of Participants"));
        $form->addInput(Input::createGroupInput([
            Input::dateInput("eventDay","Event Date"),
            Input::timeInput("eventTime","Event Start Time")
            ]));
        $form->addInput(Input::tokenInput("organizations","Organizations"));
        $form->addInput(Input::tokenInput("posters","Posters"));
        
        //$Validation=  Validation::validate("eventName", $regex='', $required=TRUE);
         
        $body->addToCenter($form);
    }
}

