<?php

/**
 *  function main is the start point of IntroNet
 *  @param boolean $debug_mode Turning on the debug mode displys system info and error messages
 */
function main($debug_mode) {
    if ($debug_mode) {
        $start = microtime(true); //For testing the page speed
        echo 'Current PHP version: ' . phpversion();
        if (version_compare(phpversion(), '5.0.0', '<')) {
            die('Current PHP version is old. Please instal PHP5 or higher');
        }
    }



    session_start();

// User Logout
    if (isset($_GET["logout"])) {
        session_destroy();
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php?page=home';
        header("Location: http://$host$uri/$extra");
        exit;
        //echo 'Loged out';
    }

    require_once './classes/MainMenu.php';
    require_once './classes/PageDirectory.php';
    require_once './classes/User.php';
    require_once './classes/Planner.php';
    require_once './classes/pages/Page.php';

    
    $page = null;
    $main_menu = null;
    $user=null;
    Page::setTitle("IntroNet"); // the name of the website
    
    if (isset($_SESSION["user"])) {
        $user = unserialize($_SESSION["user"]);
        $main_menu = new MainMenu($user->type);
    } else {
        $user = new User(); // guest user
        $main_menu = new MainMenu();
    }
    
    /** @todo replace $_Get['page'] with $_SERVER['PATH_INFO']  */
    //print_r( explode('/',$_SERVER['PATH_INFO']) );
    // get the page from the PageDirectory
    if (!isset($_GET['page'])) {
        $page = PageDirectory::getPage("home", $main_menu,$user);
    } else {
        $page = PageDirectory::getPage($_GET['page'], $main_menu,$user);
    }


    if ($page != null) {
        $user->openPage($page);
        //$page->printPage("IntroNet", $user);
    } else {
        echo "Error: no page to display!";
    }


//for testing
    if ($debug_mode) {
        $end = microtime(true);
        echo "Loading the page took " . ($end - $start) . "ms";
    }
}

// start IntroNet
main(false);

?>