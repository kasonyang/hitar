<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */
include_once __DIR__ . '/initTest.php';
include_once __DIR__ . '/RecordTest.php';

class OrmAnnotationTest extends PHPUnit_Framework_TestCase{
    function test1(){
        $annotation = new Hitar\Common\OrmAnnotation('\\TableExample');
        $this->assertEquals('tableexample', $annotation->getTableName());
        $this->assertEquals(['id'], $annotation->getPrimaryKeys());
    }
}


