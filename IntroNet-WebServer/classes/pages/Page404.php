<?php


/**
 * Description of 404
 *
 * @author hussam
 */
class Page404 extends Page {
    protected function build(\PageBody &$body, \SubMenu &$submenu) {
        $pageName = $_GET['page'];
        $html = new CustomHTML('
<div class="jumbotron">
  <h1>404</h1>
  <p>Page "'.$pageName.'" does not exist!</p>
  <p><a href="?page=home" class="btn btn-primary btn-lg" role="button">Go Back to Home Page</a></p>
</div>
');
        
        $body->addToCenter($html);
    }

//put your code here
}
