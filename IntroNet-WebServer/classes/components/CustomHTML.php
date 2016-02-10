<?php
//namespace GUI\components;

require_once 'Component.php';


/**
 * Description of newPHPClass
 *
 * @author hussam
 */
class CustomHTML extends Component {

    private $html;

    public function __construct($html) {
        $this->html = $html;
    }

    public function build() {
        echo $this->html;
    }

}
