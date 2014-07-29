<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

namespace Hitar;

class DatabaseManager{
    
    private static $database_list = [];
    
    private static $current_database_name = null;
    
    static function addDatabase($name,$config){
        self::$database_list[$name] = $config;
    }
    
    
    static function getCurrentDatabase(){
        if(!self::$current_database_name){
            self::$current_database_name = 
                    self::$database_list ? 
                    array_keys(self::$database_list)[0]
                    : NULL;
        }
        if(self::$current_database_name){
            return self::$database_list[self::$current_database_name];
        }else{
            Exception::noConnection();
        }
    }
    
    static function getDatabase($name){
        if(isset(self::$database_list[$name])){
            return self::$database_list[$name];
        }else{
            Exception::noConnection();
        }
    }
    
    
    static function selectDatabase($name){
        if(isset(self::$database_list[$name])){
            self::$current_database_name = $name;
        }else{
            Exception::noConnection();
        }
    }
    
}