<?php

/**
 * Description of SettingPage
 *
 * @author hussam
 */
class SettingPage extends Page {
    public function callBack($data, $action, \PageBody &$body) {
        $_SESSION['db_host']=$_POST['db_host'];
        $_SESSION['db_name']=$_POST['db_name'];
        $_SESSION['db_user']=$_POST['db_user'];
        $_SESSION['db_password']=$_POST['db_password'];
        
        //Save the change in config.php
        $str=file_get_contents('config.php');
        $str=str_replace('theme="'.$GLOBALS['config']['theme'].'"', 'theme="'.$_POST['theme'].'"',$str);
        //write the entire string to the file
        file_put_contents('config.php', $str);
        header("Refresh:0; url=index.php?page=Setting&save=true");
        $body->addToTop(new Message("Saving new setting ....", Message::INFO));
        $this->hasBody = FALSE;
    }
    
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        if(isset($_GET['save']) && $_GET['save'] === 'true')
            $body->addToTop(new Message("Data was saved", Message::SUCCESS));
        
        $DbSettingForm = new Form("Setting");
        $DbSettingForm->addInput(Input::textInput("db_host", "Database Host",array_key_exists('db_host',$_SESSION)?$_SESSION['db_host']:''));
        $DbSettingForm->addInput(Input::textInput("db_name", "Database Name",$_SESSION['db_name']));
        $DbSettingForm->addInput(Input::textInput("db_user", "Database User",$_SESSION['db_user']));
        $DbSettingForm->addInput(Input::textInput("db_password", "Database Password",$_SESSION['db_password']));
        
        
        $DbSettingForm->addInput(Input::selectInput("theme", "Theme", $GLOBALS['config']['themes'],$GLOBALS['config']['theme']));
        $body->addToCenter($DbSettingForm);
    }
}
