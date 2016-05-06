<?php

require_once 'Component.php';

/**
 * @property string $content This is the content of the message in the invitation
 * @property string $messageType This is the type of the message in the invitation
 */
class Message extends Component {

    private $content;
    private $messageType;

    const SUCCESS = 'success';
    const INFO = 'info';
    const WARNING = 'warning';
    const DANGER = 'danger';
/**
 * This function can create the message content and type that has to be sent to theparticipant
 * @param _construct $content Content for the message in the invitation
 * @param _construct $messageType Type of the message to be sent
 */
    public function __construct($content, $messageType='info') {
        $this->content = $content;
        $this->messageType = $messageType;
    }

    public function build() {
        echo '<div class="alert alert-' . $this->messageType . ' alert-dismissible" role="alert">'
        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
        . $this->content . ' </div>';
    }

}
