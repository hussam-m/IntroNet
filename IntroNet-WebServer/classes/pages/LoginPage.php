<?php
require_once 'Page.php';

class LoginPage extends Page {   
    
    public function __construct($menu) {
        parent::__construct("Login",$menu);
        
    }
    

    public function callBack($data, $action,PageBody &$body) {
        
    }

    protected function build(PageBody &$body,SubMenu &$submenu) {
        $loginForm = new Form("login");
        $loginForm->addInput(Input::textInput("email", "Enter your Email:"));
        $loginForm->addInput(Input::textInput("password", "Enter your Password:"));
        
        $body->addToCenter($loginForm);
    }

}
