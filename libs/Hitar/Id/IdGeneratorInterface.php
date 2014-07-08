<?php

/**
 * 
 * @version 1.0
 * @author Kason Yang <i@kasonyang.com>
 */

namespace Hitar\Id;

interface IdGeneratorInterface {
    /**
     * 
     * @param \Doctrine\DBAL\Connection $connection
     * @return mixed 返回ID值
     */
    function generate($connection);
    
}
