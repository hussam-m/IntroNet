<?php

/**
 * Description of Table
 *
 * @author Chakshu
 */
class Table {
    //put your code here
    public $id;
    public $name;
    
    public $rounds=array();


    public function __construct($table_id=-1) {
        if($table_id>-1){
            $this->id=$table_id;
        $this->name="Table".$table_id;
        }
    }
    
    public function setName($name) {
        $this->name=$name;
    }
    public function meething($round,$p1,$p2,$p3=FALSE) {
        if($p3)
            $this->rounds[$round]=array($p1,$p2,$p3);
        else
            $this->rounds[$round]=array($p1,$p2);
    }
}
