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
            $dbConfig = @$GLOBALS['config']['database'];
            if(!$dbConfig) throw new Exception (10);
//                $host = $dbConfig['hosta'];
//                $name = $dbConfig['name'];
//                $username = $dbConfig['username'];
//                $password = $dbConfig['password'];
//        $connection = new PDO('mysql:host='.$_SESSION['db_host'].
//                ';dbname='.$_SESSION['db_name'].';charset=utf8', $_SESSION['db_user'], $_SESSION['db_password']);
        $connection = new PDO('mysql:host='.$dbConfig['host'].
                ';dbname='.$dbConfig['name'].';charset=utf8', $dbConfig['username'], $dbConfig['password']);
        
        return $connection;
        }
        catch (Exception $e){
            $dbError = " Please set the database configration at the config.php file";
            if($e->getMessage()==10)
                throw new DatabaseException("<h4>No Database connection</h4>".$dbError);
            if (!class_exists('PDO'))
                throw new DatabaseException("PDO is requried");
            else
                throw new DatabaseException("No database connection!");
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
    
    public static function getObjects($name,$options="",$sql="") {
        //session_start();
        if($sql=="") $sql ="Select * FROM `$name`";
        $data = array();
        $connection = self::connect();
        $STH = $connection->query($sql." ".$options);
        //var_dump($sql." ".$options);
        if($STH){
            if($name!="")
                $STH->setFetchMode(PDO::FETCH_CLASS, $name);
            else{                
                return $STH->fetchAll(PDO::FETCH_COLUMN);
            }

            while($obj = $STH->fetch()) {
                $data[]=$obj;
            }
            return $data;
        }
        else{
            var_dump($sql." ".$options);
            var_dump($connection->errorInfo());
            return FALSE;
        }
            
    }
    
    public static function getObject($name,$where) {
        //session_start();
        $connection = self::connect();
        $STH = $connection->query("Select * FROM ".$name." WHERE ".$where);
        if($STH){
            $STH->setFetchMode(PDO::FETCH_CLASS, $name);
            return $STH->fetch();
        }
        else
            return FALSE;
    }

    
    public static function count($name,$options="") {        
        $connection = self::connect();
        $STH = $connection->query("SELECT count(*) as total from $name ".$options);
        return $STH->fetchColumn();
    }
    
    /**
     * Inserting a new row in a table
     * @param String $table name of the database table
     * @param String $data the new row's data the needs to be inseated
     * @return boolean
     */
    public static function insert($table,Array $values,$id=Null) {
        $connection = self::connect();
        //var_dump($values);
        //var_dump("INSERT INTO $table (".implode(",", array_keys($values)).") VALUES (".implode(",", $values).")");
        $count = $connection->exec("INSERT INTO $table (".implode(",", array_keys($values)).") VALUES (".implode(",", $values).")");
        //echo 'id&cound=';
        //var_dump (isset($id) && $count);
        //echo ' id= '.$id;
        if($id && $count){
            //echo "s_id=".$connection->lastInsertId($id)." or ".$connection->lastInsertId();
            return $connection->lastInsertId($id);
        }
            
        /*
        if($count==FALSE)
            var_dump("INSERT INTO $table (".implode(",", array_keys($values)).") VALUES (".implode(",", $values).")");
                var_dump($connection->errorInfo());
        */ 
        /* Return number of rows that were inserted */
        return $count;
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
        $connection = self::connect();
        
        /* Delete all rows from the FRUIT table */
        $count = $connection->exec("DELETE FROM fruit WHERE colour = 'red'");

        /* Return number of rows that were deleted */
        return $count;
    }
    
    public static function update($name,$values,$id) {
        $connection = self::connect();
        
        /* update a row from the table */
        $count = $connection->exec("UPDATE $name SET $values WHERE $id");
        //echo "UPDATE $name SET $values WHERE $id";
        /* Return number of rows that were updated */
        return $count;
        
    }
    
}
//require_once 'Event.php';
////var_dump($_SESSION);
//Database::getObject("Event");
class DatabaseException extends Exception{}
class DatabaseQueryException extends Exception{}