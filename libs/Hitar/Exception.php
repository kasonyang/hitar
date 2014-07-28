<?php

/**
 * 
 * @author Kason Yang <i@kasonyang.com>
 */

namespace Hitar;

class Exception{
    static function noPrimaryKey(){
        throw new self('Primary key undefinded！');
    }
    
    static function noTableName(){
        throw new self('Table name undefinded!');
    }
}