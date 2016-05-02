<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
//$path = dirname(dirname(__FILE__)).'/IntroNet-WebServer/';// $_SERVER['DOCUMENT_ROOT'];
if(!defined('__ROOT__'))
    define('__ROOT__', __DIR__); 
//echo 'path='.__ROOT__;
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                //core
                'OneToManyAlgorithm'=> __ROOT__.'/classes/core/OneToManyAlgorithm.php',
                'OneToOneAlgorithm' => __ROOT__.'/classes/core/OneToOneAlgorithm.php',
                'Validation'        => __ROOT__.'/classes/Validation.php',
                'Database'          => __ROOT__.'/classes/Database.php',
                'Poster'            => __ROOT__.'/classes/Poster.php',
                'Timer'             => __ROOT__.'/classes/core/Timer.php',
                
                
                // database objects
                'Conference'    => __ROOT__.'/classes/Conference.php',
                'Event'         => __ROOT__.'/classes/Event.php',
                'User'          => __ROOT__.'/classes/User.php',
                'Planner'       => __ROOT__.'/classes/Planner.php',
                'Participant'   => __ROOT__.'/classes/Participant.php',
                'Schedule'      => __ROOT__.'/classes/Schedule.php',
                'Invitation'    => __ROOT__.'/classes/Invitation.php',
                'Organisation'  => __ROOT__.'/classes/Organisation.php',
                'Table'         => __ROOT__.'/classes/Table.php',
                
                // UI
                'Page'          => __ROOT__.'/classes/pages/Page.php',
                'MainMenu'      => __ROOT__.'/classes/MainMenu.php',
                'PageDirectory' => __ROOT__.'/classes/PageDirectory.php',
                
                //components
                'Component'     => __ROOT__.'/classes/components/Component.php',
                'CustomHTML'    => __ROOT__.'/classes/components/CustomHTML.php',
                'Message'       => __ROOT__. '/classes/components/Message.php',
                'HtmlTable'     => __ROOT__.'/classes/components/HtmlTable.php',
                'Form'          => __ROOT__.'/classes/components/Form.php',
                'Menu'          => __ROOT__.'/classes/components/Menu.php',
                );
        }
        if (isset($classes[$class])) {
            require $classes[$class];
        }
    },
    true,
    false
);

?>
