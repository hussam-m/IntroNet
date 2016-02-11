<?php

class PageDirectory {

    private static $directory = [
        "home" => ["class" => "HomePage", "file" => "homePage.php"],
        "login" => ["class" => "LoginPage", "file" => "LoginPage.php"],
        "Test" => ["class" => "TestPage", "file" => "TestPage.php"],
    ];

    public static function getPage($name,$menu) {
        $page = self::$directory[$name];
        $pageObject = null;
        try {
            if($page==null)
                throw new Exception("Page ".$name." does not exist");
            
            if(file_exists('classes/pages/' . $page["file"]))
                require_once 'classes/pages/' . $page["file"];
            else
                throw new Exception("File ".$page["file"]." does not exist");
            
            $class = $page["class"];
            if(class_exists($class))
                $pageObject = new $class($menu);
            else
                throw new Exception("Class ".$class." does not exist");
            
        } catch (Exception $e) {
            if ($debug_mode)
                echo "Load Class Page error: " . $e;
            $pageObject = null;
        }
        return $pageObject;
    }
       
}
?>
