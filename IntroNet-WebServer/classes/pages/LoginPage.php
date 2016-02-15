<?php
require_once 'Page.php';
//require_once './classes/forms/LoginForm.php';


class LoginPage extends Page {   
    
    public function __construct($menu) {
        parent::__construct("Login",$menu);
        
    }
    

    public function callBack($data, $action,PageBody &$body) {
        
    }

    protected function build(PageBody &$body,SubMenu &$submenu) {
        $loginForm = new Form();
        $loginForm->addInput("email", "email", "Enter your Email:");
        $loginForm->addInput("password", "password", "Enter your Password:");
        
        $body->addToCenter($loginForm);
    }

}
