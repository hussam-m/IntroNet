<?php

class PageDirectory {

    // this list have all the pages that can be accessed
    private static $directory = [
        // 
        "home" => ["class" => "HomePage", "file" => "homePage.php"],
        "404" => ["class" => "Page404", "file" => "Page404.php"],
        "login" => ["class" => "LoginPage", "file" => "LoginPage.php"],
        "Test" => ["class" => "TestPage", "file" => "TestPage.php"],
        "Setting" => ["class" => "SettingPage", "file" => "SettingPage.php"],
        
        // user pages
        
        
        //admin pages
        "NewEvent" => ["class" => "NewEventPage", "file" => "NewEventPage.php"],
        "EventList" => ["class" => "EventListPage", "file" => "EventListPage.php"],
        "Event" => ["class" => "EventPage", "file" => "EventPage.php"],
        "ControlPanal" => ["class" => "ControlPanalPage", "file" => "ControlPanalPage.php"],
        "Timer" => ["class" => "TimerPage", "file" => "TimerPage.php"],
        "AssignWeight" => ["class" => "AssignWeightPage", "file" => "AssignWeight.php"],
        "Conference" => ["class" => "Conference", "file" => "Conference.php"],
        "Customize" => ["class" => "CustomizeParticipantsSchedule", "file" => "CustomizeParticipant'sSchedule.php"],
        
        "Mypage" => ["class" => "MyPage", "file" => "MyPage.php"],
        
    ];

    public static function getPage($name,$menu,$user) {
        if(array_key_exists($name,self::$directory))
            $page = self::$directory[$name];
        else
            $page = null;
        $pageObject = null;

            if($page==null)
                throw new Exception("Page ".$name." does not exist");
            
            if(file_exists('classes/pages/' . $page["file"]))
                require_once 'classes/pages/' . $page["file"];
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
