<?php
require_once 'Component.php';

class Form extends Component {
    
    private $inputs=[];
    
    public function addInput($name,$type,$label)
    {
        $this->inputs[]= ["name"=>$name,"type"=>$type,"label"=>$label];
    }

    public function build() {
        echo '<form class="form-horizontal">';
        foreach ($this->inputs as $input) {
            echo '<div class="form-group">
    <label for="'.$input["name"].'">'.$input["label"].'</label>
    <input type="'.$input["type"].'" class="form-control" id="'.$input["name"].'" name="'.$input["name"].'" placeholder="'.$input["label"].'">
  </div>';
        }
        echo '<input type="submit" class="btn btn-default">';
        echo '</form>';
    }
}
