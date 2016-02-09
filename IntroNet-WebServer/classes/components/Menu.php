<?php
require_once 'Component.php';


class Menu extends Component {

    private $links=[];
    
    public function addLink($name,$page){
        $this->links[] = ["name"=>$name,"page"=>$page];
    }
    
    public function build() {

    echo '<ul class="nav navbar-nav navbar-left">';
    if($this->links!=null)
        foreach ($this->links as $link) {
            echo '<li><a href="?page='.$link["page"].'">'.$link["name"].'</a></li>';
        }
    echo '</ul>';
    }

}
