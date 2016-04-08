<?php

/**
 * Description of Validation
 *
 * @author hussam
 */
class Validation {
    const NAME = "/^[a-z][\sa-z0-9'-_]{2,20}$/i";
    const PASSWORD = "/^(?=.*[a-z])(?=.*[A-Z]).{6,12}$/i";
    const EMAIL = "/^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/i";
    // date formats: mm/dd/yyyy or m/d/yy or m.d.yyyy
    const DATE = "/^([0]?[1-9]|[1][0-2])[.\/-]([0]?[1-9]|[1|2][0-9]|[3][0|1])[.\/-]([0-9]{4}|[0-9]{2})$/i";
    const TIME = "/^([0-1][0-9]|[2][0-3]):([0-5][0-9])$/i"; //HH:MM
    
    public static function validate( $input,  $regex,$required = false){
        
        if(preg_match ( $regex, $input )=== FALSE)
            throw new Exception($input);
        else
            return $input;
    }
    
//    public static function validate(){
//        
//    }
    
//    public function fill($data) {
//        if(!isset($data))
//            throw new Exception("no Data");
//        
//        foreach ($data as $key => $value) {
//          $this->$key->data = $value;  
//        }
//        
//    }
}
