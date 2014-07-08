<?php

/**
 * 
 * @version 1.0
 * @author Kason Yang <i@kasonyang.com>
 */
class IdentityGenerator implements Hitar\Id\IdGeneratorInterface {
    
    private $sequence = null;
    
    function __construct($sequence = NULL) {
        $this->sequence = $sequence;
    }
    
    /**
     * 
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function generate($connection) {
        return (int)$connection->lastInsertId($this->sequence);
    }
}
