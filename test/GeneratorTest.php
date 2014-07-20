<?php

/**
 * 
 * @version 1.0
 * @author Kason Yang <i@kasonyang.com>
 */
include_once __DIR__ . '/initTest.php';

class GeneratorTest extends PHPUnit_Framework_TestCase {
    
    function testId(){
        $tb = TableExample::table();
        $example = new TableExample();
        $example->name = 'hehe';
        $tb->insert($example);
        $this->assertEquals(36, strlen($example->id));
    }
}
