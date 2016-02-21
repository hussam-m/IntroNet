<?php
require_once './classes/components/Component.php';
require_once './classes/components/Form.php';

abstract class Page {

    private $visible = true;
    private $must_login = false;
    private $visible_to = [];
    private $theme = "default";
    protected $keywords = [];
    protected $description = "";
    private $center_width = 6;
    private $body;
    private $mainMenu;
    private $subMenu;
    private $pageName;
    
    public function __construct($menu,$pageName='') {
        if($pageName!='')
            $this->pageName = ' - '.$pageName;
        if (is_a($menu, "Menu"))
            $this->mainMenu = $menu;
        $this->body = new PageBody();
        $this->subMenu = new SubMenu();
        
        if($_POST)
            $this->callBack ($_POST, "post", $this->body);

        $this->build($this->body,$this->subMenu);
    }

    //abstract  public function pageConstruct($menu);
    abstract protected function build(PageBody &$body, SubMenu &$submenu);
    public function callBack($data, $action,PageBody &$body){}

    function printPage($title,$user) {
        //if the page have a submenu add it to the left side
        if(!$this->subMenu->isEmpty())
            $this->body->addSubMenu ($this->subMenu);
        
        $this->center_width = 12;
        if ($this->body->hasLeft())
            $this->center_width-=3;
        if ($this->body->hasRight())
            $this->center_width-=3;
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <meta name="description" content="<?= $this->description ?>">
                <meta name="keywords" content="<?= implode(",", $this->keywords) ?>">
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
                <title><?= $title . $this->pageName ?></title>

                <!-- CSS -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
                <link rel="stylesheet" href="css/style.css" >
                <link rel="stylesheet" href="css/bootstrap-datepicker.css" >
                <link rel="stylesheet" href="css/bootstrap-clockpicker.css" >
                <link rel="stylesheet" href="css/bootstrap-tokenfield.css" >
                <link rel="stylesheet" href="css/tokenfield-typeahead.css" >
                
                <!-- JS -->
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.js"></script>
                <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
                <script src="js/bootstrap-datepicker.min.js"></script>
                <script src="js/bootstrap-clockpicker.min.js"></script>
                <script src="js/bootstrap-tokenfield.min.js"></script>
            
            </head>
            <body>
                <nav class="navbar navbar-default">
                    <div class="container">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#"><?= $title ?></a>
                        </div>
                        <?php if ($user != null) : ?>
<!--                            <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link">Hussam Almoharb</a></p>-->
                            <div class="dropdown navbar-text navbar-right">
                                Signed in as
                                <a href="#" class="dropdown-toggle navbar-link" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                  <?=$user->name?>
                                  <span class="caret"></span>
                                </a>
                            
<!--                                <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Hussam Almoharb <span class="caret"></span></a></p>-->
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                  <li><a href="#">Profile</a></li>
                                  <li role="separator" class="divider"></li>
                                  <li><a href="?logout">Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="?page=login" role="button" class="btn btn-default navbar-btn navbar-right">Sign in</a>
                            <?php endif; ?>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <?php $this->mainMenu->build() ?>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="container">
                <div class="row">
                    <div id="top" class="col-md-12">
                        <?php
                        foreach ($this->body->getTop() as $component) {
                            echo $component;
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                        <?php if ($this->body->hasLeft()) : ?>
                        <div id="left" class="col-md-3">
                            <?php
                            foreach ($this->body->getLeft() as $component) {
                                echo $component;
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                    <div id="center" class="col-md-<?= $this->center_width ?>">
                        <?php
                        foreach ($this->body->getCenter() as $component) {
                            echo $component;
                        }
                        ?>
                    </div>
                        <?php if ($this->body->hasRight()) : ?>
                        <div id="right" class="col-md-3">
                            <?php
                            foreach ($this->body->getRight() as $component) {
                                echo $component;
                            }
                            ?>
                        </div>
        <?php endif; ?>
                </div>
                <div class="row">
                    <div id="bottom" class="col-md-12">
                        <?php
                        foreach ($this->body->getBottom() as $component) {
                            echo $component;
                        }
                        ?>
                    </div>
                </div>

                <?php
                //$this->printBody()
                //var_dump($this->components);
                ?>
            </div>
            <footer class="footer">
                <div class="container">
                    <p class="text-muted">footer content here.</p>
                </div>
            </footer>
        </body>
        </html>
        <?php
    }

}

// Body Class
//namespace Page{
class PageBody {

//page layout
    const TOP = 0;
    const LEFT = 1;
    const RIGHT = 2;
    const CENTER = 3;
    const BOTTOM = 4;

    private $components = [[/* top */], [/* left */], [/* right */], [/* center */], [/* bottom */]];

    function addToTop(Component $componet) {
        $this->components[PageBody::TOP][] = $componet;
    }

    function addToCenter(Component $componet) {
        $this->components[PageBody::CENTER][] = $componet;
    }

    function addToLeft(Component $componet) {
        $this->components[PageBody::LEFT][] = $componet;
    }

    function addToRight(Component $componet) {
        $this->components[PageBody::RIGHT][] = $componet;
    }

    function addToBottom(Component $componet) {
        $this->components[PageBody::BOTTOM][] = $componet;
    }
    
    function addSubMenu(SubMenu $submenu){
        array_unshift($this->components[PageBody::LEFT],$submenu);
    }

    function getTop() {
        return $this->components[PageBody::TOP];
    }

    function getCenter() {
        return $this->components[PageBody::CENTER];
    }

    function getLeft() {
        return $this->components[PageBody::LEFT];
    }

    function getRight() {
        return $this->components[PageBody::RIGHT];
    }

    function getBottom() {
        return $this->components[PageBody::BOTTOM];
    }

    function hasLeft() {
        return (!empty($this->components[PageBody::LEFT]));
    }

    function hasRight() {
        return (!empty($this->components[PageBody::RIGHT]));
    }

}


class SubMenu extends Component{
    private $links = [];
    public function addLink($label,$link,$active=false){
        $this->links[]= ["label"=>$label,"link"=>$link,"active"=>$active];
    }
    public function addSplitter(){
        $this->links[] = 'Splitter';
    }
    public function build(){
        if($this->isEmpty())
            return '';
        
        $html='<div class="list-group">';
        foreach ($this->links as $link){
            if($link=='Splitter')
                $html.= '</div><div class="list-group">';
            else
                $html.='<a href="'.$link['link'].'" class="list-group-item '.($link['active']?'active':'').' ">'.$link['label'].'</a>';
        }
        $html.='</div>';
        echo $html;
    }
    public function isEmpty(){
        return empty($this->links);
    }
}
//}
?>
