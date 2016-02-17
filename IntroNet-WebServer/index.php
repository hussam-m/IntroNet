<?php

// Turning on the debug mode displys system info and error messages
$debug_mode = false;
if ($debug_mode)
{
    $start = microtime(true); //For testing the page speed
    echo 'Current PHP version: ' . phpversion();
    if (version_compare(phpversion(), '5.0.0', '<')) {
        die('Current PHP version is old. Please instal PHP5 or higher');
    }
}


    
session_start();

// User Logout
if(isset($_GET["logout"])){
    session_destroy();
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php?page=home';
    header("Location: http://$host$uri/$extra");
    exit;
    //echo 'Loged out';
}

require_once './classes/components/Menu.php';
require_once './classes/PageDirectory.php';



$page = null;
//$main_menu = null;
if(isset($_SESSION["user"]))
    $user = $_SESSION["user"];
$menus = [];

// Main Menus
$menus["admin"] = new Menu();
$menus["admin"]->addLink("Home", "home");
//$menus["admin"]->addLink("Login", "login");

//define('IN_INDEX', true);
// make the page accessible only for the index.php
//defined('IN_INDEX') or die("Error: You cannot access this page");

if (!isset($_GET['page'])) {
    require_once 'classes/pages/homePage.php';
    $page = new HomePage($menus["admin"]);
} else {
        $page = PageDirectory::getPage($_GET['page'], $menus["admin"] /*TODO: menu need to be changed*/);
}



if ($page != null) {
    $page->printPage("IntroNet");
} else {
    echo "Error: no page to display!";
}


//for testing
if ($debug_mode) {
    $end = microtime(true);
    echo "Loading the page took " . ($end - $start) . "ms";
}
?>