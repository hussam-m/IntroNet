<?php
/**
 * This Page shows Event's information
 *
 * 
 */
class EventPage extends Page {

    const UserType = "Planner";

    private $event;

    public function callBack($data, $action, PageBody &$body) {
        if (array_key_exists("do", $data))
            switch ($data["do"]) {
                case "create": // create a new event
                    
                    $this->event = new stdClass(); // for testing - should be Event class
                    $this->event->name = $data['eventName'];

                    // save event
                    $this->event->id = Database::insert("Event", $this->event);
                    $body->addToTop(new Message("Event was created", Message::SUCCESS));
                    break;
                case "update": // update an event
                    $values = "name='".  Validation::validate($data['eventName'],  Validation::NAME) ."'";
                    $values .= ",startDate='".  Validation::validate($data['startDate'],  Validation::DATE) ."'";
                    $values .= ",startTime='".  Validation::validate($data['startTime'],  Validation::TIME) ."'";
                    $this->event->id = Database::update("Event", $values, "event_id=".$data['id']);
                    $body->addToTop(new Message("Event was Updated", Message::SUCCESS));
                    break;
            }
    }

    protected function build(PageBody &$body, SubMenu &$submenu) {

        $subPage = isset($_GET['subpage']) ? $_GET['subpage'] : '';

        // get event
        if (isset($_GET['event'])) {
            $this->event = Event::getEvent($_GET['event']);
        }

        // if event does not exist, show error message
        if ($this->event == null) {
            $body->addToTop(new Message("No Event", Message::DANGER));
            return;
        }

        // set the name of the page
        $this->pageName = $this->event->name;


        $submenu->addLink("Event Details", "?page=Event&event=" . $this->event->event_id, $subPage == '');
        $submenu->addLink("Update Event", "?page=Event&event=" . $this->event->event_id . "&subpage=update", $subPage == 'update');
        $submenu->addSplitter();
        $submenu->addLink("Send Email Invitation", "?page=Event&event=" . $this->event->event_id . "&subpage=send", $subPage == 'send');
        $submenu->addSplitter();
        $participants=$this->event->getNumberOfParticipants();
        $missing=$participants - $this->event->getNumberOfParticipantion();
        $submenu->addLink("Show All Participants", "?page=Event&event=" . $this->event->event_id . "&subpage=participants", $subPage == 'participants', false, $participants);
        $submenu->addLink("Show Missing Participants", "#", false, false, $missing);
        $submenu->addLink("Show Event Attendances", "#", false, false, 10);
        $submenu->addSplitter();
        $submenu->addLink("Build Schedule", "#");
        $submenu->addLink("Send Schedule", "#");
        $submenu->addLink("Start Timer", "#");
        $submenu->addSplitter();
        $submenu->addDangerLink("Delete Event", "#");

        $body->addToTop(new CustomHTML("
            <div class='page-header'>
                <h1> " . $this->event->name . "</h1>
            </div>
        "));

        if ($subPage == '')
            $body->addToCenter(new CustomHTML("
                <dl class='dl-horizontal' style='font-size:18px'>
                    <dt>Name</dt>
                    <dd>" . $this->event->name . "</dd>
                    <dt>Event Type</dt>
                    <dd>" . $this->event->getType() . "</dd>
                    <dt>Start Date</dt>
                    <dd>" . $this->event->getStartDate() . "</dd>
                    <dt>Start Time</dt>
                    <dd>" . $this->event->getStartTime() . "</dd>
                    <dt>End Time</dt>
                    <dd>" . $this->event->getEndTime() . "</dd>
                    
                        
                    <dt>Sessions</dt>
                    <dd>" . $this->event->rounds . "</dd>
                    <dt>Session Length</dt>
                    <dd>" . $this->event->roundLength . "</dd>
                    <dt>Event Length</dt>
                    <dd>" . $this->event->eventLength . "</dd>
                </dl>
            "));
        else if ($subPage == 'update') {
            $form = new Form("Event&event=".$this->event->event_id."&subpage=update");
            $form->addInput(Input::textInput("eventName", "Event Name", $this->event->name, TRUE));
            $form->addInput(Input::createGroupInput(array(
                        Input::dateInput("startDate", "Start Date", $this->event->getStartDate(), TRUE),
                        Input::timeInput("startTime", "Start Time", $this->event->getStartTime(), TRUE)
            )));

//            $section = $form->newSection("Fill two fields and the third one will be calculated");
//            $section->addInput(Input::createGroupInput(array(
//                Input::textInput("numberOfRounds","Number of Rounds","",True),
//                Input::textInput("timeOfSessions","Length of the Sessions and Breaks","",True),
//                Input::textInput("lengthOfEntireEvent","Length of The Entire Event","",True),
//            )));

            $form->addInput(Input::hiddenInput("id", $this->event->event_id));
            $form->addInput(Input::hiddenInput("do", "update"));
            $body->addToCenter($form);
        } else if ($subPage == 'send') {
            $form = new Form("Event");
            $form->addInput(Input::tokenInput("emails", "Send To:"));
            $form->addInput(Input::textInput("subject", "Subject", "Invitation to " . $this->event->name));
            $form->addInput(Input::textareaInput("message", "Message"));
            $body->addToCenter($form);
        } else if ($subPage=='participants'){
            $table= new HtmlTable();
            $table->setHead(array("First Name","Last Name","Email","Phone"));
            foreach ($this->event->getParticipants() as $key => $participant) {
                $table->addRow(array($participant->fname,$participant->lname,$participant->email,$participant->phone));
            }
            $body->addToCenter($table);
        }
    }

}
