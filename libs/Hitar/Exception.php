<?php

/**
 * 
 * @author Kason Yang <i@kasonyang.com>
 */

namespace Hitar;

class ConnectException extends \Exception{
    
}

class Exception{
    static function noPrimaryKey(){
        throw new self('Primary key undefinded！');
    }
    
    static function noTableName(){
        throw new self('Table name undefinded!');
    }
    
    static function noConnection(){
        throw new self('No connection!');
    }
    
    static function failToConnectDatabase($message){
        throw new ConnectException('Failed to connect the database:' . $message);
    }
}