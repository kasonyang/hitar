<?php

/**
 * 
 * @version 1.0
 * @author Kason Yang <i@kasonyang.com>
 */

namespace Hitar;

class FieldType {

    private $parameters;
    private $dbal_type;

    function __construct($parameters, $dbal_type) {
        $this->parameters = $parameters;
        $this->dbal_type = $dbal_type;
    }

    /**
     * 
     * @return \Doctrine\DBAL\Types\Type
     */
    function getDbalType() {
        return $this->dbal_type;
    }

    function getParameters() {
        return $this->parameters;
    }

}
