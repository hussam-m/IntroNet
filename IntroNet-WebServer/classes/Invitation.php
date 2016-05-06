<?php
/**
 * @property string $participants this is the list of participants who are invited
 * @property string $message This is the message which was sent for the invitation
 */
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
    
    public function send() {
        
    }
/**
 * @param string random_password this is the autogenerated password
 * @return string autogenerated password
 */
    //https://hughlashbrooke.com/2012/04/23/simple-way-to-generate-a-random-password-in-php/
    public static function random_password($length = 8) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!#$@";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

}
