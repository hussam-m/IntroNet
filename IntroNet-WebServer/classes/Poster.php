<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Poster
 *
 * @author Chakshu
 */
class Poster {
    //put your code here
    private $poster_id;
    private $name;
    
    public function __construct($poster_id) {
        $this->poster_id=$poster_id;
    }
    
    public function __poster($name) {
        $this->name=$name;
    }
 
     
}
