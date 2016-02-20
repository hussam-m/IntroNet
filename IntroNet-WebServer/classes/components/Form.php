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
            echo '<div class="form-group">';
            Input::buildInput($input);
            echo '</div>';
        }
        echo '<input type="submit" class="btn btn-default">';
        echo '</form>';
        
        // active all datepickers
        // 
        echo '<script>$(".datepicker").datepicker();</script>';
        // active all timepickers
        // http://weareoutman.github.io/clockpicker/
        echo '<script>$(".timepicker").clockpicker();</script>';
    }
}

class Input{
//    public $name;
//    public $type;
//    public $label;
//    public $required = false;
//    public $disabled = false;
//    public $regex = "";
    
    public static function createGroupInput($inputs){
        $group = new self();
        $group->type = "group";
        $group->inputs = $inputs;
        return $group;
    }
    
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
    static function timeInput($name,$label,$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"time","name"=>$name,"label"=>$label,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function selectInput($name,$label,$options=[],$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"list","name"=>$name,"label"=>$label,"options"=>$options,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    
    
    static function buildInput(Input $input){
        if($input->type == 'group')
        {
            //echo '<div class="form-inline">';
            echo '<div style="margin: 0 -15px" class="row">';
            foreach ($input->inputs as $i){
//                echo '<div class="form-horizontal">';
                echo '<div class="form-group col-md-'.(12/count($input->inputs)).'">';
                Input::buildInput($i);
                echo '</div>';
            }
            echo '</div>';
            return;
        }
        
       echo '<label for="'.$input->name.'">'.$input->label.'</label>'.
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
                echo ' <div class="input-group date datepicker">';
                echo '<input type="text" id="'.$input->name.'" name="'.$input->name.'" class="form-control"/>';
                echo '<span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span></div>';
                
            }
            else if($input->type=='time'){
                echo ' <div class="input-group date timepicker" data-autoclose="true">';
                echo '<input type="text" id="'.$input->name.'" name="'.$input->name.'" class="form-control"/>';
                echo '<span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span></div>';
                
            }
    }
}
