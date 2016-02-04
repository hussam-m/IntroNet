<?php
$page=null;

if($_GET['page']=='' || $_GET['page']=='home'){
    require_once 'classes/pages/homePage.php';
    $page = new HomePage();
}

if($_GET['page']=='login'){
    require_once 'classes/pages/LoginPage.php';
    $page = new LoginPage();
}

if($page!=null){
    $page->printPage("IntroNet");
}
 else {
     echo "Error: no page to display!";
}
?>