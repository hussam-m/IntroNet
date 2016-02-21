<?php
require_once 'Page.php';
require_once './classes/components/Message.php';
require_once './classes/User.php';

class LoginPage extends Page {   
    
    public function __construct($menu) {
        parent::__construct($menu,"Login");
        
    }
    

    public function callBack($data, $action,PageBody &$body) {
        // this code only for testing
        if($data["email"]=="hussam" && 
           $data["password"]=="1234")
        {
            // get user data
            $user = new User(); // 
            $user->name = 'hussam'; // only for testing
            $user->type = 'admin'; // only for testing
            
            // save user data
            $_SESSION['user']=json_encode($user);
            
            // redirct the user to the home page
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php?page=home';
            header("Location: http://$host$uri/$extra");
            exit;
        }
        else
            $body->addToTop(new Message("Wrong password or email", Message::DANGER));
    }

    protected function build(PageBody &$body,SubMenu &$submenu) {
        $submenu->addLink("Sign in", "#",TRUE);
        $submenu->addLink("Sign up", "#");
        $submenu->addLink("Forget Password", "#");
        
        $loginForm = new Form("login");
        $loginForm->addInput(Input::textInput("email", "Enter your Email:"));
        $loginForm->addInput(Input::textInput("password", "Enter your Password:"));
        
        $body->addToCenter($loginForm);
    }

}
