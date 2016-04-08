<?php

/**
 * Description of SettingPage
 *
 * @author hussam
 */
class SettingPage extends Page {
    public function callBack($data, $action, \PageBody &$body) {
        $body->addToTop(new Message("Data was saved", Message::SUCCESS));
        $_SESSION['db_host']=$_POST['db_host'];
        $_SESSION['db_name']=$_POST['db_name'];
        $_SESSION['db_user']=$_POST['db_user'];
        $_SESSION['db_password']=$_POST['db_password'];
    }
    
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $DbSettingForm = new Form("Setting");
        $DbSettingForm->addInput(Input::textInput("db_host", "Database Host",array_key_exists('db_host',$_SESSION)?$_SESSION['db_host']:''));
        $DbSettingForm->addInput(Input::textInput("db_name", "Database Name",$_SESSION['db_name']));
        $DbSettingForm->addInput(Input::textInput("db_user", "Database User",$_SESSION['db_user']));
        $DbSettingForm->addInput(Input::textInput("db_password", "Database Password",$_SESSION['db_password']));
        
        $body->addToCenter($DbSettingForm);
    }
}
