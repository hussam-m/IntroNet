<?php

/**
 * Description of ConferencePage
 *
 * 
 */
class ConferencePage extends Page {
    private $conference;
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        // get event
        if(isset($_GET['conference'])){
            $this->conference = Conference::getConference($_GET['conference']);
        } 
        
        // if event does not exist, show error message
        if($this->conference==null){
            $body->addToTop(new Message("No Conference",  Message::DANGER));
            return;
        }
        
        $this->pageName = $this->conference->conference_name;
        $subPage = isset($_GET['subpage'])?$_GET['subpage']:'';
        
        $body->addToTop(new CustomHTML("
            <div class='page-header'>
                <h1> ".$this->conference->conference_name ."</h1>
            </div>
        "));
        
        if($subPage=='')
            $body->addToCenter(new CustomHTML("
                <dl class='dl-horizontal' style='font-size:18px'>
                    <dt>Name</dt>
                    <dd>".$this->conference->conference_name."</dd>
                    <dt>Registration Start Date</dt>
                    <dd>".$this->conference->registration_start_date."</dd>
                    <dt>Registration Start Time</dt>
                    <dd>".$this->conference->registeration_deadline_date."</dd>
                    <dt>Registration End Date</dt>
                    <dd>".$this->conference->registration_start_time."</dd>
                    <dt>Registration End Time</dt>
                    <dd>".$this->conference->registration_deadline_time."</dd>
                </dl>
            "));
        
        
        
    }

}
