<?php

/**
 * Component is ......
 *
 * @author hussam
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
