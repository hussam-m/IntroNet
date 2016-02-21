<?php
require_once 'Page.php';
require_once './classes/components/Message.php';
/**
 * RegistrationPage
 * This is where the user can register for an event
 * @author hussam
 */
class RegistrationPage extends Page {
    
    public function __construct($menu) {
        parent::__construct($menu,"Registration");
    }
    protected function build(PageBody &$body, SubMenu &$submenu) {
        
    }
}
