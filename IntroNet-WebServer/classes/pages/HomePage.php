<?php

require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';

class HomePage extends Page {

    public function __construct($menu) {
        parent::__construct($menu,"Home");
        $this->description = "Testing Home Page";
        $this->keywords = ["Test", "Home", "IntroNet"];
    }


    protected function build(PageBody &$body,SubMenu &$submenu) {
        $c = new CustomHTML('
<div class="jumbotron">
  <h1>This is the home page!</h1>
  <p>Join S2ERC and register now</p>
  <p><a href="?page=login" class="btn btn-primary btn-lg" role="button">Register</a></p>
</div>
');
        $body->addToCenter($c);

        $body->addToTop(new Message("This is the home page", Message::WARNING));
        $body->addToTop(new Message("You have to login", Message::DANGER));
    }

}
