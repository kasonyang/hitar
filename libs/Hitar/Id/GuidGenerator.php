<?php

/**
 * 
 * @version 1.0
 * @author Kason Yang <i@kasonyang.com>
 */

namespace Hitar\Id;

class GuidGenerator implements IdGeneratorInterface {
    
    function __construct($params = array()) {
        
    }
    
    public function generate($connection) {
        $sql = 'SELECT ' . $connection->getDatabasePlatform()->getGuidExpression();
        return $connection->query($sql)->fetchColumn(0);
    }
    
    public function isGenerateAfterInsert() {
        return false;
    }
}
