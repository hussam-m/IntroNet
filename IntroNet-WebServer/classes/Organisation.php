<?php

class Organisation {
    public $name;
    public $id;
    public function __construct() {
        if(isset($this->organisation_id))
            $this->id = $this->organisation_id;
    }
}
