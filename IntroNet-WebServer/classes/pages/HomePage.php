<?php

class HomePage extends Page {

    protected function build(PageBody &$body,SubMenu &$submenu) {
        $c = new CustomHTML('
<div class="jumbotron">
  <h1>IntroNet</h1>
  <p>Welcome to IntroNet.</p>
</div>
');
        $body->addToCenter($c);

    }

}
