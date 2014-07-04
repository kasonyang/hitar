<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

require_once __DIR__ . '/initTest.php';


class RecordTest extends PHPUnit_Framework_TestCase{
    function testRecord(){
        $this->assertEquals('test', Test::table()->getTableName());
        $this->assertEquals('Test', Test::table()->getTableAliasName());
        
    }
}