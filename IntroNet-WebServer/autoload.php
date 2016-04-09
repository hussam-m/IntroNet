<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
//$path = dirname(dirname(__FILE__)).'/IntroNet-WebServer/';// $_SERVER['DOCUMENT_ROOT'];
define('__ROOT__', dirname(dirname(__FILE__)).'/IntroNet-WebServer/'); 
//echo 'path='.__ROOT__;
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                //core
                'OneToManyAlgorithm' => __ROOT__.'/classes/core/OneToManyAlgorithm.php',
                'Validation'    => __ROOT__.'/classes/Validation.php',
                'Database'    => __ROOT__.'/classes/Database.php',
                
                // database objects
                'Event'     => __ROOT__.'/classes/Event.php',
                'User'      => __ROOT__.'/classes/User.php',
                
                // pages
                'Page'          => __ROOT__.'/classes/pages/Page.php',
                
                //components
                'CustomHTML'    => __ROOT__.'/classes/components/CustomHTML.php',
                'Message'       => __ROOT__. '/classes/components/Message.php',
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