<?php
require_once 'Component.php';

class Form extends Component {
    
    private $inputs=[];
    //private $horizontal=false;
    private $page_url; // page to callback
    public function __construct($page_url) {
        $this->page_url=$page_url;
    }
    
    public function addInput(Input $input)
    {
        $this->inputs[]= $input;
    }
    
//    public function addInput($name,$type,$label,$required=false,$regex="")
//    {
//        $this->inputs[]= ["name"=>$name,"type"=>$type,"label"=>$label];
//    }
//    public function addList($name,$label,$options=[],$required=false){
//                $this->inputs[]= ["name"=>$name,"type"=>"list","label"=>$label,"options"=>$options];
//    }
    public function addButton($name,$onClick){
        
    }
    

    public function build() {
        echo '<form class="form" action="index.php?page='.$this->page_url.'" method="post">';
        foreach ($this->inputs as $input) {
            echo '<div class="form-group">
    <label for="'.$input->name.'">'.$input->label.'</label>'.
    (($input->type=='text' || $input->type=='email' || $input->type=='password')?(      
        '<input type="'.$input->type.'" class="form-control" id="'.$input->name.'" name="'.$input->name.'" placeholder="'.$input->label.'" >'
    ):($input->type=='list'?( 
                '<select class="form-control" id="'.$input->name.'" name="'.$input->name.'">'.
                    call_user_func(function($o) {
                        $html='';
                        foreach ($o as $option) {
                            $html.='<option>'.$option.'</option>';
                        }
                        return $html;},$input->options )
                .'</select>'
                    ):''));    
            
            
            if($input->type=='textarea')
                echo '<textarea class="form-control" id="'.$input->name.'" name="'.$input->name.'" rows="3"></textarea>';
            else if($input->type=='date'){
                echo '<input id="'.$input->name.'" name="'.$input->name.'" class="form-control">';
                echo '<script>$("#'.$input->name.'").datepicker();</script>';
            }
            echo '</div>';
        }
        echo '<input type="submit" class="btn btn-default">';
        echo '</form>';
    }
}

class Input{
//    public $name;
//    public $type;
//    public $label;
//    public $required = false;
//    public $disabled = false;
//    public $regex = "";
    
    private static function createInput($conf)
    {
        $input = new self();
        $input->name = $conf->name;
        $input->label = $conf->label;
        $input->type = $conf->type;
        $input->required = $conf->required;
        $input->disabled = $conf->disabled;
        $input->regex = $conf->regex;
        
        if(isset($conf->options))
           $input->options = $conf->options;
        
        return $input;
    }
    static function textInput($name,$label,$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"text","name"=>$name,"label"=>$label,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function textareaInput($name,$label,$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"textarea","name"=>$name,"label"=>$label,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function dateInput($name,$label,$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"date","name"=>$name,"label"=>$label,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function selectInput($name,$label,$options=[],$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"list","name"=>$name,"label"=>$label,"options"=>$options,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
}
