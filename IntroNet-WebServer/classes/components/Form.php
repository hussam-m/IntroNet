<?php
require_once 'Component.php';

class Form extends Component {
    
    private $inputs=[];
    private $horizontal=false;
    
    public function addInput($name,$type,$label,$options=[])
    {
        $this->inputs[]= ["name"=>$name,"type"=>$type,"label"=>$label,"options"=>$options];
    }

    public function build() {
        echo '<form class="form">';
        foreach ($this->inputs as $input) {
            echo '<div class="form-group">
    <label for="'.$input["name"].'">'.$input["label"].'</label>'.
    (($input["type"]=='text' || $input["type"]=='email' || $input["type"]=='password')?(      
        '<input type="'.$input["type"].'" class="form-control" id="'.$input["name"].'" name="'.$input["name"].'" placeholder="'.$input["label"].'" >'
    ):($input["type"]=='list'?( 
                '<select class="form-control">'.
                    call_user_func(function($o) {
                        $html='';
                        foreach ($o as $option) {
                            $html.='<option>'.$option.'</option>';
                        }
                        return $html;},$input['options'] )
                .'</select>'
                    ):''))    
            .'</div>';
        }
        echo '<input type="submit" class="btn btn-default">';
        echo '</form>';
    }
}
