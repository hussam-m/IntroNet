<?php

class Email {
    public function Send($subject,$message,$email){
        mail($email, $subject, $message, $headers);
    }
}
