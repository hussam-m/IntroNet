<?php

/**
 * RegistrationPage
 * This is where the user can register for an event
 * @author hussam
 */
class RegistrationPage extends Page {

    public function init(&$css, &$js, &$angularjs) {
        $angularjs = TRUE;
    }

    //private $user = null;
    private $participant = null;

    protected function build(PageBody &$body, SubMenu &$submenu) {
        if (count($_POST) == 0)
            $this->login($body, $submenu);
        
        $body->addToCenter(new CustomHTML('<script> $("nav").hide() </script>'));
    }

    private function login(PageBody &$body) {
        $conference_id = $_GET['conference'];// | $_POST['conference'];
        if (!isset($conference_id))
            throw new Exception("conference not found");
        $conference = Conference::getConference($conference_id);
        $c = new CustomHTML("
                    <div class='jumbotron'>
                      <h1> $conference->name </h1>
                      <p>Please enter your email and password to complete registration process</p>
                   
                    ");
        $body->addToCenter($c);
        $loginForm = new Form("registration&conference=".$conference_id);
        $loginForm->addInput(Input::textInput("email", "Email", '', TRUE));
        $loginForm->addInput(Input::textInput("password", "Password", '', TRUE));
        $loginForm->addInput(Input::hiddenInput("conference", $conference->conference_id));
        $loginForm->addInput(Input::hiddenInput("login", TRUE));
        $body->addToCenter($loginForm);
        $body->addToCenter(new CustomHTML("</div>"));
    }

    private function registration(PageBody &$body, $user, $data) {
        /* @var $conference  Conference */
        $conference = Conference::getConference($data['conference']);
        $events = $conference->getEvents();
        $organisations = $conference->getOrganisations();
        
        //var_dump($organisations);
        $body->addToCenter(new CustomHTML("<h3>Hello $user->fname,</h3>"));
        
        $body->addToCenter(new CustomHTML('
        <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#registration" aria-controls="registration" role="tab" data-toggle="tab">Registration</a></li>
    <li role="presentation"><a href="#participants" aria-controls="participants" role="tab" data-toggle="tab">Participants</a></li>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="registration">
        '));
        
        
        $body->addToCenter(new CustomHTML("<h4>What events would you like to participate in?</h4>"));
        $form = new Form("registration");

        foreach ($events as $key => $event) {
            $section = $form->newSection(Input::checkBox("event" . $event->event_id, "") . $event->name . "  <small>".$event->getStartDay()."  From $event->startTime to ".$event->getEndTime()."</small>");
            if ($event->type == 1) // one to one
                $input = Input::checklist("event" . $event->event_id . "list", "Who you want to meet?", $organisations);
            else if ($event->type == 2) // one to many
                $input = Input::checklist("event" . $event->event_id . "list", "Preferences", $event->getPosters() );

            $input->showOn = "event" . $event->event_id;
            $section->addInput($input);
        }

//        $section = $form->newSection(Input::checkBox("event1", "")."Poster Parade <small>From 11:00 to 11:30</small>");
//        //$section->addInput(Input::checkBox("event1", ""));
//        $input=Input::checklist("event1list", "Preferences", [[1,"Poster 1"],[2,"Poster 2"],[3,"Poster 3"],[4,"Poster 4"]]);
//        $input->showOn="event1";
//        $section->addInput($input);
//        
//        $section = $form->newSection(Input::checkBox("event2","")."Meeting <small>From 11:00 to 11:30</small>");
//        //$section->addInput();
//        $input=Input::checklist("event2list", "Who you want to meet?", [[1,"BSU"],[2,"VT"],[3,"UF"]]);
//        $input->showOn="event2";
//        $section->addInput($input);
//        
//        $section = $form->newSection(Input::checkBox("event3", ""));
//        //$section->addInput();

        $section = $form->newSection("Extra Information <small>(optional)</small>");
        $section->addInput(Input::textInput("icebreaker", "Icebreaker Question"));
        $section->addInput(Input::textareaInput("bio", "Write a short biography about yourself"));
        $section->addInput(Input::checkBox("disability", "Are you handicapped?"));
        $section->addInput(Input::hiddenInput("conference", $conference->conference_id));
        $section->addInput(Input::hiddenInput("participant", $user->id));
        
        $body->addToCenter($form);
        
        $body->addToCenter(new CustomHTML('</div><div role="tabpanel" class="tab-pane" id="participants">'));
        
        $table = new HtmlTable();
        $table->setHead(array("Name","organisation"));
        /* @var $participants Participant[] */
        $participants = Database::getObjects("Participant","","SELECT Participant.fname as fname,Participant.lname as lname, Organisation.name as organisation From Participant,Organisation Where Participant.conference_id=$conference->conference_id AND Organisation.organisation_id=Participant.organisation ORDER By lname" );
        foreach ($participants as $key => $participant) {
            $table->addRow(array($participant->name,$participant->organisation));
        }
        $body->addToCenter($table);

        $body->addToCenter(new CustomHTML('</div></div>'));
    }

    public function callBack($data, $action, \PageBody &$body) {
        if(isset($data['login'])){
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = filter_input(INPUT_POST, 'password');
            if ($email && $password) {
                $participant = Participant::login($email,$password);
                //var_dump($participant);
                if ($participant) {
                    $this->participant = $participant;
                } else {
                    $body->addToTop(new Message("Wrong Email or Password!", Message::DANGER));
                }
            } else {
                $body->addToTop(new Message("Invalid Email or Password!", Message::DANGER));
            }
        }
        if (!$this->participant) {
            if(isset($data['participant'])){
                // register participant to events
                //var_dump($data);
                $participant = Participant::getParticipant($data['participant']);
                //var_dump($participant);
                $conference = Conference::getConference($data["conference"]);
                $events = $conference->getEvents();
                $registered = FALSE;
                //var_dump($events);
                foreach ($events as $event) {
                    $ok=TRUE;
                    $registered = $registered || $event->isRegistered($participant->id);
                    if(key_exists("event".$event->event_id, $data)){
                        //echo $event->event_id;
                        $preferences=array();
                        if(key_exists("event".$event->event_id."list", $data)){
                            $preferences=  $data["event".$event->event_id."list"];
                        }
                        //echo 'p=';
                        //var_dump($preferences);
                        //echo 'register';
                        $ok = $ok && $event->register( $participant->id , $preferences );
                    }
                }
                if($ok)
                    $body->addToTop(new Message("<h3>Thank you</h3> The event planner will send you and email with the event information soon", Message::SUCCESS));
                else if($registered)
                    $body->addToTop(new Message("<h3>Registration Warning</h3>You are already registered!</br>If you wish to change your registration, please contact the event planner", Message::WARNING));
                else
                    $body->addToTop(new Message("<h3>Registration Error</h3> Sorry, Something went wrong! Please try again later or ask for support to fix this issue", Message::DANGER));

            } else
                $this->login($body);
        } else {
            $this->registration($body, $this->participant, $data);
        }
    }

}
