<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

include_once __DIR__ . '/initTest.php';

class SqlStatementTest extends PHPUnit_Framework_TestCase{
    function test1(){
        $parser = new \Hitar\Common\SqlStatementParser('id=%i');
        $this->assertEquals([PDO::PARAM_INT], $parser->getTypes());
        $this->assertEquals('id=?', $parser->getPrepareSql());
    }
}