<?php

class Invitation {
    private $participants;
    private $message;
    public function __construct($participants,$message) {
        foreach ($participants as $id){
            echo $id;
            $participant= Participant::getParticipant($id);
            if($participant->invitation == "" )
                $participant->setInvitation(Invitation::random_password());
            echo ',e="'.$participant->invitation.'"';
        }
    }
    
    /**
     * Send the Invitation to Participant's email
     */
    public function send() {
        
    }

    //https://hughlashbrooke.com/2012/04/23/simple-way-to-generate-a-random-password-in-php/
    public static function random_password($length = 8) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!#$@";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

}
