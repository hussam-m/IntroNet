<?php

require_once 'Component.php';

/**
 * Description of Message
 *
 * @author 
 */
class Message extends Component {

    private $content;
    private $messageType;

    const SUCCESS = 'success';
    const INFO = 'info';
    const WARNING = 'warning';
    const DANGER = 'danger';

    public function __construct($content, $messageType) {
        $this->content = $content;
        $this->messageType = $messageType;
    }

    public function build() {
        echo '<div class="alert alert-' . $this->messageType . ' alert-dismissible" role="alert">'
        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
        . $this->content . ' </div>';
    }

}
