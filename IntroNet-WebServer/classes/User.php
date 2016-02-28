<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Sandeep
 */
class User {
    //put your code here
    public $id;
    public $email;
    
    /**
    * This function gets the user details from the database
    * @param String $email the user email
    * @param String $password the login password 
    * @return User returns an object of type user or null if the password or email is wrong
    */
   function login($email, $password){
       $user = null;
       // for testing
       if($email=='hussam' && $password=='1234'){
           $user = new Planner();
           $user->name="Admin";
           $user->type="admin";
           $user->id=55;
           
       }
       
       return $user;
   }
   
   function logout()
   {
       echo 'Successfully Logged Out';
   }
   
   function resetPassword($email)
   {
       
   }
   
   /**
    * This function opens a web page
    * @param Page $page
    */
   public function openPage(Page $page){
       $page->printPage($this);
   }
   
    /**
    * This function gets the user details from the database
    * @param int $id the user email
    * @return User returns an object of type user or null if user doesn't exist
    */
   public static function getUser($id){
       
   }
      
}

