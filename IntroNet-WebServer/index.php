<?php

$debug_mode = false;

require_once './classes/components/Menu.php';
require_once './classes/PageDirectory.php';

if ($debug_mode)
    $start = microtime(true); //For testing the page speed

$page = null;
//$main_menu = null;
$user = null;
$menus = [];

$menus["admin"] = new Menu();
$menus["admin"]->addLink("Home", "home");
//$menus["admin"]->addLink("Login", "login");

//define('IN_INDEX', true);
// make the page accessible only for the index.php
//defined('IN_INDEX') or die("Error: You cannot access this page");

if($_POST['callback']!='') {
    
}
else if ($_GET['page'] == '') {
    require_once 'classes/pages/homePage.php';
    $page = new HomePage($menus["admin"]);
} else {
        $page = PageDirectory::getPage($_GET['page'], $menus["admin"] /*TODO: menu need to be changed*/);
}

//if ($_GET['page'] == 'login') {
//    require_once 'classes/pages/LoginPage.php';
//    $page = new LoginPage($menus["admin"]);
//}



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