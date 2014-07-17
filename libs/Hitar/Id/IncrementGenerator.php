<?php

/**
 * 
 * @version 1.0
 * @author Kason Yang <i@kasonyang.com>
 */

namespace Hitar\Id;

class IncrementGenerator implements IdGeneratorInterface {
    
    function __construct($params = []) {
        
    }
    
    /**
     * 
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function generate($connection) {
        return (int)$connection->lastInsertId();
    }
    
    public function isGenerateAfterInsert() {
        return TRUE;
    }
}
