<?php
require_once 'Component.php';

class Form extends Component {
    
    private $inputs=[];
    //private $horizontal=false;
    private $page_url; // page to callback
    public $autoSubmit = false;
    public $keepData = false;
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
            $hideOn = isset($input->hideOn)?" ng-hide='$input->hideOn' ":"";
            $showOn = isset($input->showOn)?" ng-show='$input->showOn' ":"";
            if($this->keepData)
                $input->defaultValue = @$_POST[$input->name];
            if($this->autoSubmit)
                $input->SubmitOnChange = true;
            echo '<div class="form-group" '.$hideOn.$showOn.'>';
            Input::buildInput($input);
            echo '</div>';
        }
        if(!$this->autoSubmit)
            echo '<input type="submit" class="btn btn-default">';
        echo '</form>';
        
        // active all datepickers
        // 
        echo '<script>$(".datepicker").datepicker();</script>';
        // active all timepickers
        // http://weareoutman.github.io/clockpicker/
        echo '<script>$(".timepicker").clockpicker();</script>';
        
        echo "
            <script>$('.tokenfield').tokenfield();</script>";
        echo "<script>
            $('form').submit(function(e) {
            var ref = $(this).find('[required]');
            $(ref).each(function(){
                if ( $(this).val() == '' )
                {
                    alert('Required field should not be blank.');
                    $(this).focus();
                    e.preventDefault();
                    return false;
                }
            });
            
            $('.checklist').each(function(){
                var min = $(this).attr('min');
                if($(this).find('input[type=\"checkbox\"]:checked').length < min ){
                    alert('You need to select at least '+min);
                    $(this).focus();
                    e.preventDefault();
                    return false;
                }
            });
            
            return true;
        });</script>";
        
    }
}

class Input{
//    public $name;
//    public $type;
//    public $label;
//    public $required = false;
//    public $disabled = false;
//    public $regex = "";
    public $hideOn;
    public $showOn;
    
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
        $input->placeholder = @$conf->placeholder ?: "";
        if(property_exists($conf,'defaultValue'))
            $input->defaultValue = $conf->defaultValue;
        if(property_exists($conf,'showOn'))
            $input->showOn = $conf->showOn;
        if(property_exists($conf,'hideOn'))
            $input->hideOn = $conf->hideOn;
        
        if(isset($conf->options))
           $input->options = $conf->options;
        
        $input->SubmitOnChange = false;
        
        return $input;
    }
    static function textInput($name,$label,$defaultValue='',$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"text","name"=>$name,"label"=>$label,"defaultValue"=>$defaultValue,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function textareaInput($name,$label,$defaultValue='',$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"textarea","name"=>$name,"label"=>$label,"defaultValue"=>$defaultValue,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function dateInput($name,$label,$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"date","name"=>$name,"label"=>$label,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function timeInput($name,$label,$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"time","name"=>$name,"label"=>$label,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function selectInput($name,$label,$options=[],$defaultValue=-1,$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"list","name"=>$name,"label"=>$label,"options"=>$options,"defaultValue"=>$defaultValue,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function tokenInput($name,$label,$required=false,$disabled=false,$regex="")
    {
        return self::createInput((object)array("type"=>"token","name"=>$name,"label"=>$label,"required"=>$required,"disabled"=>$disabled,"regex"=>$regex));
    }
    static function checklist($name,$label,$options=[],$min=0,$disabled=false)
    {
        return self::createInput((object)array("type"=>"checklist","name"=>$name,"label"=>$label,"options"=>$options,"required"=>$min,"disabled"=>$disabled,"regex"=>""));
    }
    static function checkBox($name,$label,$disabled=false)
    {
        return self::createInput((object)array("type"=>"checkbox","name"=>$name,"label"=>$label,"required"=>FALSE,"disabled"=>$disabled,"regex"=>""));
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
        
       echo '<label for="'.$input->name.'">'.$input->label.($input->required?" <span style='color:red'>*</span>":"").'</label>'.
    (($input->type=='text' || $input->type=='email' || $input->type=='password')?(      
        '<input type="'.$input->type.'" class="form-control" id="'.$input->name.'" name="'.$input->name.'" placeholder="'.$input->placeholder.'" value="'.$input->defaultValue.'" '.($input->required?'required':'').' >'
    ):($input->type=='list'?( 
                '<select class="form-control" id="'.$input->name.'" name="'.$input->name.'" ng-model="'.$input->name.'" '.($input->SubmitOnChange?'onchange="this.form.submit()"':"").' ng-init="'.$input->name.' = \''.(is_array($input->options)?$input->options[0][0]:$input->options[0]).'\'">'.
                    call_user_func(function($o,$i) {
                        $html='';
                        foreach ($o as $option) {
                            if(is_array($option))
                                $html.='<option value="'.$option[0].'" '.($option[0]==$i?'selected="selected"':'').' >'.$option[1].'</option>';
                            else
                                $html.='<option '.($option==$i?'selected="selected"':'').' >'.$option.'</option>';
                        }
                        return $html;},$input->options,  @$input->defaultValue?:-1)
                .'</select>'
                    ):''));    
            
            
            if($input->type=='textarea')
                echo '<textarea class="form-control" id="'.$input->name.'" name="'.$input->name.'" rows="3">'.$input->defaultValue.'</textarea>';
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
            else if($input->type=='token'){
                echo '<input type="text" id="'.$input->name.'" name="'.$input->name.'" class="form-control tokenfield"/>';
            }
            else if($input->type=='checkbox'){
                echo '<input type="checkbox" id="'.$input->name.'" name="'.$input->name.'" class="checkbox"/> <script> $("#'.$input->name.'").checkboxpicker(); </script>';
            }
            else if($input->type=='checklist'){
                echo "<div class='checklist' id='$input->name' min=$input->required tabindex='-1'>";
                if($input->required)
                    echo "<div class='count'>select $input->required or more <span>(0 selected)</span></div>";
                foreach ($input->options as $key => $option) {
                    echo '<div class="checkbox"><label><input type="checkbox" id="'.$input->name.$option[0].'" name="'.$input->name.'[]" value="'.$option[0].'"/>'.$option[1].'</label></div>';
                }
                echo "</div>";
                if($input->required)
                    echo "
                        <script>
                        $('#$input->name input[type=\"checkbox\"]' ).change(function() {
                            $('#$input->name .count span').text('('+$('#$input->name input[type=\"checkbox\"]:checked').length +' selected)');
                            if($('#$input->name input[type=\"checkbox\"]:checked').length < $input->required ){
                                $('#$input->name .count span').css('color','#f00');
                            }else{
                                //$('#$input->name .count').css('color','#333');
                                $('#$input->name .count span').css('color','green');
                                $('#$input->name .count span').text('✓');
                            }
                        });
                        </script>
                    ";
            }
    }
}
