<?php
require_once 'Page.php';
//require_once './classes/forms/LoginForm.php';


class LoginPage extends Page {   
    
    public function __construct($menu) {
        parent::__construct("Login",$menu);
        
        $loginForm = new Form();
        $loginForm->addInput("email", "email", "Enter your Email:");
        $loginForm->addInput("password", "password", "Enter your Password:");
        
        $this->addComponent(Page::CENTER, $loginForm);
    }
    
    public function printBody() {
        //HTML code
        ?>
<b>This is the login page</b>
<p><a href="?page=home">Go to Home</a></p>
        <?php
//        $loginForm = new Form();
//        $loginForm->inputs[] = new Input("Email","email","Enter Email:");
//        $loginForm->buildForm();
        //$loginForm = new LoginForm();
        //$loginForm->buildForm();
        include './gui/loginForm.php';
        
    }

    public function callBack($data, $action) {
        
    }

}
