<?php

require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';

class TestPage extends Page {

    public function __construct($menu) {
        parent::__construct("Test", $menu);
    }

    protected function build(PageBody &$body) {
        $form = new Form("Test");
        //$form->addInput("list1", "list", "Exsample List:", ["1", "2", "3"]);
        $form->addInput(Input::selectInput("list1", "list", "Example List:", ["A", "B", "C"]));
        $left = new CustomHTML('
<div class="list-group">
  <a href="#" class="list-group-item active">Home</a>
  <a href="#" class="list-group-item">Profile</a>
  <a href="#" class="list-group-item">Messages</a>
</div>');

        $right = new CustomHTML('
<div class="panel panel-default">
  <div class="panel-heading">Panel heading without title</div>
  <div class="panel-body">
    Panel content
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Panel title</h3>
  </div>
  <div class="panel-body">
    Panel content
  </div>
</div>');

        $body->addToLeft($left);
        $body->addToRight($right);
        $body->addToCenter($form);
        $body->addToTop(new Message("This is a test page", Message::INFO));
        
        if(isset($_POST['list1']))
            $body->addToTop(new Message("the value of list1=".$_POST['list1'], Message::SUCCESS));

    }

    public function callBack($data, $action,PageBody &$body) {
        
    }

}
