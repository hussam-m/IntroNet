<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Table
 *
 * @author Chakshu
 */
class Table {
    //put your code here
      private $table_id;
        private $name;
       
         public function __construct($table_id) {
        $this->table_id=$table_id;
    }
    
    public function _table($name) {
        $this->name=$name;
    }
}
