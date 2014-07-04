<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

namespace Hitar;

class ConnectException extends \Exception{
    
}

class Connection {
    /**
     * 
     * @return \Doctrine\DBAL\Connection
     */
    static function createConnection(){
        try{
            return \Doctrine\DBAL\DriverManager::getConnection(DatabaseManager::getCurrentDatabase());
        }  catch (\Doctrine\DBAL\DBALException $ex){
            /* @var $ex \Doctrine\DBAL\DBALException */
            throw new ConnectException('Failed to connect the database:' . $ex->getMessage());
        }
    }
    
}