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
        $this->addLink("View All Events", "EventList");
        $this->addLink("Create New Event", "NewEvent");
    }
    private function createUserMenu(){
        $this->addLink("Home", "home");
        $this->addLink("View all Events", "ViewEvent");
    }
    private function createGuestMenu(){
        $this->addLink("Home", "home");
    }
}
