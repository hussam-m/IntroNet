<?php
abstract class Page {
    
    //private  $pageName;
    public function __construct($pageName) {
        $this->pageName=$pageName;
    }
    
    // Force Extending class to define this method
    abstract protected function printBody();  
    
    function printPage($title){
        ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$title." - ".$this->pageName?></title>
    </head>
    <body>
        <?php $this->printBody() ?>
    </body>
</html>
        <?php
    }
}

?>
