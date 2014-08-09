<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

namespace Hitar;

class Connection {
    
    private static $connection;


    /**
     * 
     * @return \Doctrine\DBAL\Connection
     */
    static function createConnection(){
        try{
            return \Doctrine\DBAL\DriverManager::getConnection(DatabaseManager::getCurrentDatabase());
        }  catch (\Doctrine\DBAL\DBALException $ex){
            /* @var $ex \Doctrine\DBAL\DBALException */
            Exception::failToConnectDatabase($ex->getMessage());
        }
    }
    
    static function getConnection(){
        if(!self::$connection){
            self::$connection = self::createConnection();
        }
        return self::$connection;
    }
    
}