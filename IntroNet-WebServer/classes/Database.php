<?php

/**
 * Database is to handle the connetion between the database server and the IntroNet classes
 *
 * @author hussam
 */
class Database {
    
    /**
     * Establish the connection with the database server
     */
    private static function connect() {
        try{
        $connection = new PDO('mysql:host='.$_SESSION['db_host'].
                ';dbname='.$_SESSION['db_name'].';charset=utf8', $_SESSION['db_user'], $_SESSION['db_password']);
        
        return $connection;
        }
        catch (Exception $e){
            throw new DatabaseException("No database connection! Go to Setting to setup the Database");
        }
        return FALSE;
    }
    
    /**
     * Gets the entire table form the database
     * @param String $table the table name
     * @return table
     */
    public static function  get($table) {
        if(isset($_SESSION["db"])){
            //var_dump (json_decode($_SESSION["db"]));
            $db = json_decode($_SESSION["db"]);
            return $db->$table;
        }
        else
            return null;
    }
    
    public static function getObjects($name,$options="") {
        //session_start();
        $data = [];
        $connection = self::connect();
        $STH = $connection->query("Select * FROM ".$name." ".$options);
        if($STH){
            $STH->setFetchMode(PDO::FETCH_CLASS, 'Event');

            while($obj = $STH->fetch()) {
                $data[]=$obj;
            }
            return $data;
        }
        else
            return FALSE;
    }
    
        public static function getObject($name,$where) {
        //session_start();
        $connection = self::connect();
        $STH = $connection->query("Select * FROM ".$name." WHERE ".$where);
        if($STH){
            $STH->setFetchMode(PDO::FETCH_CLASS, 'Event');
            return $STH->fetch();
        }
        else
            return FALSE;
    }
    
    /**
     * Gets a row form a table
     * @param type $table The table name
     * @param type $row the id of the row
     * @return Array
     */
    public static function  getRow($table,$row) {
        if(isset($_SESSION["db"])){
            //var_dump (json_decode($_SESSION["db"]));
            $db = json_decode($_SESSION["db"]);
            //if(isset($db->$table))
                $rows = $db->$table;
                //var_dump($rows[$row]);
                return $rows[$row];
        }
        else
        return null;
    }
    
    /**
     * Inserting a new row in a table
     * @param String $table name of the database table
     * @param String $data the new row's data the needs to be inseated
     * @return boolean
     */
    public static function insert($table,$data) {
        // only for testing
        if(isset($_SESSION["db"]))
            $db = json_decode($_SESSION["db"]);
        else
            $db = new stdClass();
//        if(!isset($db))
//            $db = [];
        if(!isset($db->$table))
            $db->$table=[];
        array_push($db->$table, $data);
        $_SESSION["db"] = json_encode($db);
        
        //return id
        return count($db->$table)-1;
    }
    
    /**
     * Deletes a row from a table
     * @param String $table The name of the table where the row is
     * @param String $id The id of the row that needed to be deleted
     * 
     * @return boolean True if the row was deleted, False if the row wasn't deleted
     * @todo implementing the function delete
     */
    public static function delete($table,$id){
        
    }
    
}
//require_once 'Event.php';
////var_dump($_SESSION);
//Database::getObject("Event");
class DatabaseException extends Exception{}
class DatabaseQueryException extends Exception{}