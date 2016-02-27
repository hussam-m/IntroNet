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



    $page = null;
    $main_menu = null;
    if (isset($_SESSION["user"])) {
        $user = json_decode($_SESSION["user"]);
        $main_menu = new MainMenu($user->type);
    } else {
        $main_menu = new MainMenu();
    }

    if (!isset($_GET['page'])) {
        require_once 'classes/pages/homePage.php';
        $page = new HomePage($main_menu);
    } else {
        $page = PageDirectory::getPage($_GET['page'], $main_menu);
    }


    if ($page != null) {
        $page->printPage("IntroNet", $user);
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