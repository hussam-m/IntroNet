<?php

class NewEventPage extends Page {

    const UserType = "Planner";

    public function init(&$css, &$js, &$angularjs) {
        $angularjs = TRUE;
    }

    public function callBack($data, $action, \PageBody &$body) {
        //$body->addToTop(new Message("Please fill all the information", Message::DANGER));
        // get number of rounds  
        $rounds = @$data['numberOfRounds'];
        //get Length of the Sessions and Breaks
        $session = @$data['timeOfSessions'];
        //get the length of entire event
        $eventLength = @$data['lengthOfEntireEvent'];
        // get number of posters
        $posterCount = count(explode(",", $data['posters']));

        //calculate length of event
        if ($rounds != "")
            if ($session != "")
                $eventLength = (int) ($rounds * $session);

        //calculate length of session
        if ($eventLength != "")
            if ($rounds != "")
                $session = ((int) $eventLength) / ((int) $rounds);

        //calculate number of rounds
        if ($session != "")
            if ($eventLength != "")
                $rounds = ((int) $eventLength) / ((int) $session);


        $event_id = Database::insert("Event", array(
                    "name"          => "'{$data["eventName"]}'",
                    "startDate"     => "'".date('Y-m-d', strtotime(str_replace('-', '/',  $data["eventDay"])))."'",
                    "startTime"     => "'{$data["eventTime"]}'",
                    "type"          => ($data["typeOfEvent"] == "One to One" ? 1 : 2),
                    "conference_id" => $data["conference"],
                    "rounds"        => $rounds,
                    "roundLength"   => $session
                        ), "event_id");

        if ($event_id) {
            if ($data["typeOfEvent"] == "One to Many") {
                $posters = explode(",", $data["posters"]);
                foreach ($posters as $poster) {
                    Database::insert("Poster", array(
                        "name"      => "'$poster'",
                        "event_id"  => $event_id
                    ));
                }
            }
        }

        //$body->addToTop(new Message("Number of Rounds is $rounds", Message::SUCCESS));
        //$body->addToTop(new Message("time Of Sessions is $session", Message::SUCCESS));
        //$body->addToTop(new Message("length Of Entire Event is $Event", Message::SUCCESS));
        //An algorithm to get the minimum number of participant    
        if ($data['typeOfEvent'] == "One to One") {
            $minParticipant = $rounds * 2;
        } else if ($data['typeOfEvent'] === "One to Many") {
            $minParticipant = $posterCount * $rounds;
        }
        $body->addToTop(new Message("<h3>Event was created</h3>minimum number of participants is $minParticipant "
                . "<a href='?page=Event&event=$event_id'>Click Here to View Event's Details</a>", Message::SUCCESS));
    }

    protected function build(PageBody &$body, SubMenu &$submenu) {
        $this->pageName = "New Event";

        $form = new Form("NewEvent");

        $conferences = Conference::getConferences();
        //var_dump($conferences);
        $form->addInput(Input::selectInput("conference", "Conference", $conferences));
        $form->addInput(Input::textInput("eventName", "Event Name", '', TRUE));


        $form->addInput(Input::selectInput("typeOfEvent", "Type Of Event", array("One to One", "One to Many")));

        $section = $form->newSection("Fill two fields and the third one will be calculated");
        $section->addInput(Input::createGroupInput(array(
                    Input::textInput("numberOfRounds", "Number of Rounds", "", True),
                    Input::textInput("timeOfSessions", "Length of the Sessions and Breaks", "", True),
                    Input::textInput("lengthOfEntireEvent", "Length of The Entire Event", "", True),
        )));


        $form->addInput(Input::createGroupInput(array(
                    Input::dateInput("eventDay", "Event Date", "", TRUE),
                    Input::timeInput("eventTime", "Event Start Time", "", TRUE)
        )));

        $posters = Input::tokenInput("posters", "Posters");
        $posters->showOn = 'typeOfEvent=="One to Many"';
        $form->addInput($posters);

        $body->addToCenter($form);

        $body->addToBottom(new CustomHTML(
                '
                    <script>
                        $("#numberOfRounds, #timeOfSessions, #lengthOfEntireEvent").keyup(function() {
                            if($("#numberOfRounds").val()!="" && $("#timeOfSessions").val()!=""){
                                $("#lengthOfEntireEvent").popover("hide");
                                $("#lengthOfEntireEvent").prop( "disabled", true );
                                $("#lengthOfEntireEvent").prop( "placeholder",$("#numberOfRounds").val() * $("#timeOfSessions").val());
                                return;
                            }else if( $("#lengthOfEntireEvent").prop( "disabled") == true ) {
                                $("#lengthOfEntireEvent").prop( "disabled", false );
                                $("#lengthOfEntireEvent").prop( "placeholder","");
                            }
                            
                            if($("#numberOfRounds").val()!="" && $("#lengthOfEntireEvent").val()!=""){
                                $("#timeOfSessions").prop( "disabled", true );
                                $("#timeOfSessions").prop( "placeholder",$("#lengthOfEntireEvent").val() / $("#numberOfRounds").val());
                                return;
                            }else if( $("#timeOfSessions").prop( "disabled") == true ) {
                                $("#timeOfSessions").prop( "disabled", false );
                                $("#timeOfSessions").prop( "placeholder","");
                            }
                            
                            if($("#lengthOfEntireEvent").val()!="" && $("#timeOfSessions").val()!=""){
                                $("#numberOfRounds").prop( "disabled", true );
                                $("#numberOfRounds").prop( "placeholder",Math.floor($("#lengthOfEntireEvent").val() / $("#timeOfSessions").val())).trigger("change");;
                                return;
                            }else if( $("#numberOfRounds").prop( "disabled") == true ) {
                                $("#numberOfRounds").prop( "disabled", false );
                                $("#numberOfRounds").prop( "placeholder","");
                            }
                            
                        });
                        $("#numberOfRounds").change(function(){
                            if(parseInt($("#numberOfRounds").prop("placeholder")) < 1 ){
                                $("#lengthOfEntireEvent").popover({"trigger": "manual", "html":"true", "title":"Error","content":"The <b>length of entireEvent</b> cannot be less than the <b>length of the session</b>","placement":"top"}).popover("show");
                            }
                            else
                                $("#lengthOfEntireEvent").popover("hide");
          
                        });
                    </script>
                '
        ));
    }

}
