<?php
require_once 'Page.php';

class HomePage extends Page {
    
    public function __construct() {
        parent::__construct("Home");
    }
    
    protected function printBody() {
        //HTML code
        ?>
<h3>This is the home page</h3>
<p><a href="?page=login">Login</a></p>
        <?php
    }
}
