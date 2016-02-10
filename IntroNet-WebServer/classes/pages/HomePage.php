<?php
require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';

class HomePage extends Page {
    
    public function __construct($menu) {
        parent::__construct("Home",$menu);
        $this->description="Testing Home Page";
        $this->keywords=["Test","Home","IntroNet"];
        
        $c= new CustomHTML ('
<div class="jumbotron">
  <h1>This is the home page!</h1>
  <p>Join S2ERC and register now</p>
  <p><a href="?page=login" class="btn btn-primary btn-lg" role="button">Register</a></p>
</div>
');
        $this->addComponent(Page::CENTER, $c);
        //echo "component added";
        $this->addComponent(Page::TOP, new Message("This is the home page",  Message::WARNING));
        $this->addComponent(Page::TOP, new Message("You have to login",  Message::DANGER));

    }
    
    protected function printBody() {
        //HTML code
        ?>
<h3>This is the home page</h3>
<p><a href="?page=login">Login</a></p>
        <?php
    }

    public function callBack($data, $action) {
        
    }

}
