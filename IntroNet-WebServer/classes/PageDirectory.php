<?php

class PageDirectory {

    // this list have all the pages that can be accessed
    private static $directory = array(
        // 
        "home" => array("class" => "HomePage", "file" => "HomePage.php"),
        "404" => array("class" => "Page404", "file" => "Page404.php"),
        "login" => array("class" => "LoginPage", "file" => "LoginPage.php"),
        "Test" => array("class" => "TestPage", "file" => "TestPage.php"),
        "Setting" => array("class" => "SettingPage", "file" => "SettingPage.php"),
        
        // Participant pages
        "registration" => array("class" => "RegistrationPage", "file" => "RegistrationPage.php"),
        "schedule" => array("class" => "ParticipantSchedulePage", "file" => "ParticipantSchedulePage.php"),
        
        
        //admin pages
        "NewEvent" =>       array("class" => "NewEventPage", "file" => "NewEventPage.php"),
        "EventList" =>      array("class" => "EventListPage", "file" => "EventListPage.php"),
        "Event" =>          array("class" => "EventPage", "file" => "EventPage.php"),
        "ControlPanal" =>   array("class" => "ControlPanalPage", "file" => "ControlPanalPage.php"),
        "Timer" =>          array("class" => "TimerPage", "file" => "TimerPage.php"),
        "AssignWeight" =>   array("class" => "AssignWeightPage", "file" => "AssignWeight.php"),
        "NewConference" =>  array("class" => "NewConferencePage", "file" => "NewConferencePage.php"),
        "Conference" =>     array("class" => "ConferencePage", "file" => "ConferencePage.php"),
        "Customize" =>      array("class" => "CustomizeParticipantsSchedule", "file" => "CustomizeParticipant'sSchedule.php"),
        "insertParticipant" => array("class" => "InsertParticipant", "file" => "InsertParticipant.php"),
        "Mypage" =>         array("class" => "MyPage", "file" => "MyPage.php"),
        "orginizeTable" =>  array("class" => "OrginizeTable", "file" => "OrginizeTable.php"),
        "ParticipantList" => array("class" => "ParticipantListPage", "file" => "ParticipantListPage.php"),
        "ConferenceList" => array("class" => "ConferenceListPage", "file" => "ConferenceListPage.php"),
        
        "SchedulePage"  =>  array("class" => "SchedulePage", "file" => "SchedulePage.php")
    );
/**
 * This function gets the information the page
 * @param string $name this is the name of the page
 * @param string $menu this is the menu for the user
 * @param string $user this is the user
 * @return \class this function returns the page for the user
 * @throws Exception
 */
    public static function getPage($name,$menu,$user) {
        if(array_key_exists($name,self::$directory))
            $page = self::$directory[$name];
        else
            $page = null;
        $pageObject = null;

            if($page==null)
                throw new Exception("Page ".$name." does not exist");
            
            if(file_exists(__DIR__.'/pages/' . $page["file"]))
                require_once __DIR__.'/pages/' . $page["file"];
            else
                throw new Exception("File ".$page["file"]." does not exist");
            
            $class = $page["class"];
            if(class_exists($class))
                $pageObject = new $class($user,$menu);
            else
                throw new Exception("Class ".$class." does not exist");
            

        return $pageObject;
    }
       
}
?>
