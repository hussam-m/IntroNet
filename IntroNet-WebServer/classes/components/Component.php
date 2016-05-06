<?php

/**
 * @property string $type This is the type of component
 * @property int $id This is the id of the component
 */
abstract class Component {
    //put your code here
    public  $type;
    public  $id;
    
    /*
     *  build the body of the component
     */
    abstract function build();
    
    /*
     *  return the build as string
     */
    public function __toString() {
        $this->build();
        return "";
    }
}
