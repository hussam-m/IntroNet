<?php
require_once 'Page.php';


class LoginPage extends Page {   
    
    public function __construct() {
        parent::__construct("Login");
    }
    
    public function printBody() {
        //HTML code
        ?>
<b>This is the login page</b>
<p><a href="?page=home">Go to Home</a></p>

        <?php
    }

}
