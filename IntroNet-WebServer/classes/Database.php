<?php

/**
 * Description of Database
 *
 * @author hussam
 */
class Database {
    function connect() {
    
    }
    public static function  get($table) {
        if(isset($_SESSION["db"])){
            //var_dump (json_decode($_SESSION["db"]));
            $db = json_decode($_SESSION["db"]);
            return $db->$table;
        }
        else
            return null;
    }
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
    public static function set($object,$date) {
        if(isset($_SESSION["db"]))
            $db = json_decode($_SESSION["db"]);
        else
            $db = [];
        $db[$object]=$data;
        $_SESSION["db"] = json_encode($db);
    }
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
    
}
