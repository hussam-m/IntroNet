<?php
/**
 * @property string $name this is the name of the organization
 * @property int $id this is the id for the conference
 */
class Organisation {
    public $name;
    public $id;
    public function __construct() {
        if(isset($this->organisation_id))
            $this->id = $this->organisation_id;
    }
}
