<?php

require_once 'Page.php';
require_once './classes/components/CustomHTML.php';
require_once './classes/components/Message.php';

class TestPage extends Page {

//    public function __construct($menu) {
//        parent::__construct( $menu,"Test");
//    }
    public function init(&$css, &$js, &$angularjs) {
        $angularjs=TRUE;
    }

    protected function build(PageBody &$body,SubMenu &$submenu) {
        $form = new Form("Test");
        //$form->addInput("list1", "list", "Exsample List:", ["1", "2", "3"]);
        $form->addInput(Input::textInput("input1", "Enter your name: ","",TRUE));
        $form->addInput(Input::selectInput("list1", "Example List:", ["A", "B", "C"]));
        
        $inputA = Input::textInput("showA", "show when A");
        $inputA->showOn='list1=="A"';
        
        $inputB = Input::textInput("hideA", "show when B");
        $inputB->showOn='list1=="B"';
        
        $form->addInput(Input::textInput("input1", "{{ list1 }}"));
        
        $form->addInput(Input::checklist("posters", "Chose Posters to see", 
                [[1,"UML"],[2,"Testing"],[3,"IOT"],[4,"UML"],[5,"Testing"],[6,"IOT"],[7,"UML"],[8,"Testing"],[9,"IOT"]],5));
        
        $form->addInput(Input::checklist("org", "Chose org to see", 
                [[1,"UML"],[2,"Testing"],[3,"IOT"],[4,"UML"],[5,"Testing"],[6,"IOT"],[7,"UML"],[8,"Testing"],[9,"IOT"]]));
        
        $form->addInput($inputA);
        $form->addInput($inputB);
        
        //$form->addInput(Input::dateInput("date", "choose a date: "));
        $left = new CustomHTML('
<div class="list-group">
  <a href="#" class="list-group-item active">Home</a>
  <a href="#" class="list-group-item">Profile</a>
  <a href="#" class="list-group-item">Messages</a>
</div>');
        
        $submenu->addLink("Home", "?page=home");
        $submenu->addLink("Login", "?page=login");
        $submenu->addLink("Top", "#top");
        $submenu->addLink("Down", "#down");
        $right = new CustomHTML('
<div class="panel panel-default">
  <div class="panel-heading">Panel heading without title</div>
  <div class="panel-body">
    Panel content {{ list1 }}
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
        
            

    }

    public function callBack($data, $action,PageBody &$body) {
        
        if(isset($data['posters']))
            foreach ($data['posters'] as $poster) {
                        $body->addToTop(new Message("the value of posters= ".$poster, Message::SUCCESS));
            }
        $body->addToTop(new Message("the value of list1= ".$data['list1'], Message::SUCCESS));
    }

}
