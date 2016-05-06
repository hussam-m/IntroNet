<?php

/**
 * @property int $id This is the id for the table
 * @property string $name This is the name of the table
 */
class Table {
    //put your code here
    public $id;
    public $name;
    
    public $rounds=array();

/**
 * 
 * @param __construct $table_id this is the functiojn to create the table
 */
    public function __construct($table_id=-1) {
        if($table_id>-1){
            $this->id=$table_id;
        $this->name="Table".$table_id;
        }
    }
    /**
     * This is the function to set the name of the table
     * @param setName $name
     */
    public function setName($name) {
        $this->name=$name;
    }
    /**
     * This is the function for meeting
     * @param meething $round
     * @param meething $p1
     * @param meething $p2
     * @param meething $p3
     */
    public function meething($round,$p1,$p2,$p3=FALSE) {
        if($p3)
            $this->rounds[$round]=array($p1,$p2,$p3);
        else
            $this->rounds[$round]=array($p1,$p2);
    }
}
