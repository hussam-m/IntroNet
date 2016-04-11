<?php
require_once 'components/Menu.php';
/**
 * Create the main menu for the user
 *
 * @author hussam
 */
class MainMenu extends Menu {
    public function __construct($userType = 'guest') {
        switch ($userType){
            case 'admin':
                $this->createAdminMenu();
                break;
            case 'user':
                $this->createUserMenu();
                break;
            default :
                $this->createGuestMenu();   
        }
    }
    
    
    private function createAdminMenu(){
        $this->addLink("Control Panal", "ControlPanal");
        
        $conferenceMenu = new Menu();
        $conferenceMenu->addLink("New Conference", "Conference");
        $conferenceMenu->addLink("View All Conferences", "ConferenceList");
        $conferenceMenu->addLink("Send Invitation", "send");
        $this->addsubMenu("Conference", $conferenceMenu);
        
        $eventMenu = new Menu();
        $eventMenu->addLink("New Event", "NewEvent");
        $eventMenu->addLink("View All Events", "EventList");
        $this->addsubMenu("Events", $eventMenu);
        
        $participantMenu = new Menu();
        $participantMenu->addLink("New Participant", "NewParticipant");
        $participantMenu->addLink("View All Participant", "ParticipantList");
        $this->addsubMenu("Participants", $participantMenu);
        
        $this->addLink("Timer", "Timer");
        
        $this->addLink("Setting", "Setting");
        
        
    }
    private function createUserMenu(){
        $this->addLink("Home", "home");
        $this->addLink("View all Events", "ViewEvent");
    }
    private function createGuestMenu(){
        $this->addLink("Home", "home");
    }
}
