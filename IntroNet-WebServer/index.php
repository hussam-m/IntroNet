<?php

//require('./vendor/autoload.php' );
require('autoload.php' );

/**
 *  function main is the start point of IntroNet
 *  @param boolean $debug_mode Turning on the debug mode displys system info and error messages
 */
function main($debug_mode) {
    $warning = array();
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
        if (error_reporting()) {
            $warning[] = "<b>ERROR</b> [$severity] $message<br />\n"
                    . "  Fatal error on line $lineno in file $filename"
                    . ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            if(function_exists('debug_backtrace')){
                //print "backtrace:\n";
                $backtrace = debug_backtrace();
                //array_shift($backtrace);
                $trace="<h3>Error Backtrace:</h3>";
                foreach($backtrace as $i=>$l){
                    $trace .= "[$i] in function <b>".@$l['class'].$l['function']."</b>";
                    if(@$l['file']) $trace .= " in <b>{$l['file']}</b>";
                    if(@$l['line']) $trace .= " on line <b>{$l['line']}</b>";
                    $trace .= "</br>\n";
                }
                $warning[]=$trace;
            }
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


    session_set_cookie_params(3600); // 1 hour
    session_start();

// User Logout
    if (isset($_GET["logout"])) {
        session_destroy();
        login();
        //echo 'Loged out';
    }

//    require_once './classes/MainMenu.php';
//    require_once './classes/PageDirectory.php';
//    require_once './classes/User.php';
//    require_once './classes/Planner.php';
//    require_once './classes/pages/Page.php';


    $page = null;
    $main_menu = null;
    $user = null;
    date_default_timezone_set('UTC');
    Page::setTitle("IntroNet"); // the name of the website

    if (isset($_SESSION["user"])) {
        $user = unserialize($_SESSION["user"]);
        $main_menu = new MainMenu($user->type);
    } else {
        $user = new User(); // guest user
        $main_menu = new MainMenu();
    }

    if (isset($_SERVER['PATH_INFO'])) {
        $path = explode('/', $_SERVER['PATH_INFO']);
        //print_r($path);
        if ($path[1] == "data" && $user->type == "admin") {
            if($path[2] == "conference"){
                if($_GET["get"]=="participants"){
                    $p = Conference::getParticipants($_GET["id"]);
                    echo json_encode($p);
                }
            }
        } else if ($path[1] == "send" && $user->type == "admin") {
            //echo "p=".json_encode(explode(",",$_POST['participants']));
            //echo " ,m=".json_encode($_POST['message']);
            $invitation = new Invitation(explode(",",$_POST['participants']),$_POST['message']);
            //$invitation->send();
        } else {
            echo json_encode("error");
        }

        return;
    }

    /** @todo replace $_Get['page'] with $_SERVER['PATH_INFO']  */
    //print_r( explode('/',$_SERVER['PATH_INFO']) );
    // get the page from the PageDirectory
    if (!isset($_GET['page'])) {
        $page = PageDirectory::getPage("home", $main_menu, $user);
    } else {
        try {
            $page = PageDirectory::getPage($_GET['page'], $main_menu, $user);
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
            echo $exc->getMessage();
            var_dump($exc->getTraceAsString());
            try {
                $page = PageDirectory::getPage('404', $main_menu, $user);
            } catch (Exception $ex) {
                echo " Error: Page '" . $_GET['page'] . "' not found!";
            }
        }
    }
    try {
        if (count($GLOBALS['warning']) != 0)
            $page->addWarning($GLOBALS['warning']);
        $user->openPage($page);
    } catch (Exception $exc) {
        echo " Error: Page '" . $_GET['page'] . "' not found!";
        //echo $exc->getTraceAsString();
    }



//for testing
    if ($debug_mode) {
        $end = microtime(true);
        echo "Loading the page took " . ($end - $start) . "ms";
    }
}
function login(){
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php?page=login';
    header("Location: http://$host$uri/$extra");
    exit;
}

// start IntroNet
main(FALSE);
?>