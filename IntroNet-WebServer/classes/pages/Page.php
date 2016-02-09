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

    //page layout
    const TOP = 0;
    const LEFT = 1;
    const RIGHT = 2;
    const CENTER = 3;
    const BOTTOM = 4;
    const TOPMENU = 5;

    private $components = [[/* top */], [/* left */], [/* right */], [/* center */], [/* bottom */], /* topmenu */ null];

    //private  $pageName;
    public function __construct($pageName, $menu) {
        $this->pageName = $pageName;
        if (is_a($menu, "Menu"))
            $this->components[Page::TOPMENU] = $menu;
    }

    public function addComponent($location, $componet) {
        //if(is_a($componet,"Componet"))
        //if($location>=0 && $location<=5)

        $this->components[$location][] = $componet;
        //var_dump( $this->components);
    }

    // Force Extending class to define this method
    abstract protected function printBody();

    abstract public function callBack($data, $action);

    function printPage($title) {
        $this->center_width+=6;
        if (empty($this->componets[Page::LEFT]))
            $this->center_width+=3;
        if (empty($this->componets[Page::RIGHT]))
            $this->center_width+=3;
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <meta name="description" content="<?= $this->description ?>">
                <meta name="keywords" content="<?= implode(",", $this->keywords) ?>">
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
                <title><?= $title . " - " . $this->pageName ?></title>

                <!-- CSS -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
                <link rel="stylesheet" href="css/style.css" >

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
                            <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link">Hussam Almoharb</a></p>
                        <?php else: ?>
                            <a href="?page=login" role="button" class="btn btn-default navbar-btn navbar-right">Sign in</a>
                        <?php endif; ?>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <?php $this->components[Page::TOPMENU]->build() ?>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="container">
                <div class="row">
                    <div id="top" class="col-md-12"></div>
                </div>
                <div class="row">
                    <?php if (!empty($this->componets[Page::LEFT])) : ?>
                        <div id="left" class="col-md-3"></div>
                    <?php endif; ?>
                    <div id="center" class="col-md-<?= $this->center_width ?>">
                        <?php
                        foreach ($this->components[Page::CENTER] as $component) {
                            echo $component;
                        }
                        ?>
                    </div>
                    <?php if (!empty($this->componets[Page::RIGHT])) : ?>
                        <div id="right" class="col-md-3"></div>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div id="bottom" class="col-md-12"></div>
                </div>

                <?php
                //$this->printBody()
                //var_dump($this->components);
                ?>
            </div>
            <footer class="footer">
                <div class="container">
                    <p class="text-muted">Place sticky footer content here.</p>
                </div>
            </footer>
        </body>
        </html>
        <?php
    }

}
?>
