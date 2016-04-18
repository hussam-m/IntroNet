<?php
//require('./vendor/autoload.php' );
require('autoload.php' );




/**
 *  function main is the start point of IntroNet
 *  @param boolean $debug_mode Turning on the debug mode displys system info and error messages
 */
function main($debug_mode) {
    $warning=[];
    if ($debug_mode) {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        
        //php_error\reportErrors();
        $start = microtime(true); //For testing the page speed
        echo 'Current PHP version: ' . phpversion();
        if (version_compare(phpversion(), '5.0.0', '<')) {
            die('Current PHP version is old. Please instal PHP5 or higher');
        }
    }
    
    /**
    * custom error handler
    * @link http://stackoverflow.com/questions/5373780/how-to-catch-this-error-notice-undefined-offset-0 Source
    */
   set_error_handler(
   function ($severity, $message, $filename, $lineno) {
        global $warning;
        if (error_reporting() == 0) {
          return;
        }
        if (error_reporting() ){
            $warning[]= "<b>ERROR</b> [$severity] $message<br />\n"
              . "  Fatal error on line $lineno in file $filename"
              . ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
           return;
        }
        if (error_reporting() & $severity) {
          throw new ErrorException($message, 0, $severity, $filename, $lineno);
        }
   });

// load config
    global $config;
    if (file_exists("config.php")) {
        $config = parse_ini_file("config.php", true);
    } else {
        $config['theme'] = "Light";
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

//    require_once './classes/MainMenu.php';
//    require_once './classes/PageDirectory.php';
//    require_once './classes/User.php';
//    require_once './classes/Planner.php';
//    require_once './classes/pages/Page.php';

    
    $page = null;
    $main_menu = null;
    $user=null;
    date_default_timezone_set('UTC');
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
        try {
            $page = PageDirectory::getPage($_GET['page'], $main_menu,$user);
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
            try{
                $page = PageDirectory::getPage('404', $main_menu,$user);
            } catch (Exception $ex) {
                echo " Error: Page '".$_GET['page']."' not found!";
            }
        }
    }
    try {
        if(count($GLOBALS['warning'])!=0)
            $page->addWarning($GLOBALS['warning']);
        $user->openPage($page);
    } catch (Exception $exc) {
        echo " Error: Page '".$_GET['page']."' not found!";
        //echo $exc->getTraceAsString();
    }



//for testing
    if ($debug_mode) {
        $end = microtime(true);
        echo "Loading the page took " . ($end - $start) . "ms";
    }
}

// start IntroNet
main(FALSE);

?>