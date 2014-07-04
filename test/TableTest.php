<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

include_once __DIR__ . '/initTest.php';
include_once __DIR__ . '/RecordTest.php';

class TableTest extends PHPUnit_Framework_TestCase{
    function testTable(){
        $tb = Test::table();

        $sm = $tb->getConnection()->getSchemaManager();
        $schema = $sm->createSchema();
        $new_tb =$schema->createTable('test');
        $new_tb->addColumn('nickname','string',['length' => 100]);
        $sqls = $schema->toSql($tb->getConnection()->getDatabasePlatform());
        foreach($sqls as $q){
            $tb->getConnection()->exec($q);
        }

        $tb->insert(['nickname' => 'z3']);
        
        $this->assertEquals(1, $tb->count());
        
        $this->assertEquals([['nickname' => 'z3']], $tb->selectData());
        
        $update = $tb->update(['nickname' => 'w5']);
        $this->assertEquals(1, $update);
        
        $this->assertEquals([['nickname' => 'w5']], $tb->selectData());
        
        $tb_new = clone $tb;
        
        $tb_new->where('nickname=%s', ['w5']);
        //$this->assertEquals('SELEC', $tb->buildSelect());
        $this->assertEquals([['nickname' => 'w5']], $tb_new->selectData());
        $tb_new->where(['nickname' =>'w5']);
        //$this->assertEquals('SELEC', $tb->buildSelect());
        $this->assertEquals([['nickname' => 'w5']], $tb_new->selectData());
        $this->assertEquals(1, $tb_new->update(['nickname' => 'z3'])) ;
        $this->assertEquals([['nickname' => 'z3']], $tb->selectData());
        
        $obj_list = $tb->select();
        $obj = $obj_list[0];
        /* @var $obj Test */
        $this->assertEquals(TRUE, $obj->exist());
        $this->assertEquals('z3', $obj->nickname);
        
        $obj->nickname = 'li4';
        $save_ret = $obj->save();
        $this->assertEquals(true, $save_ret);
        $this->assertEquals([['nickname' => 'li4']], $tb->selectData());
        $tb->delete();
        
        $test = new Test(['nickname' => 'hehe']);
        $this->assertEquals(TRUE, $test->save());
        $this->assertEquals('hehe', Test::table()->selectData()[0]['nickname']);
        $test->delete();
        
        $this->assertEquals([], $tb->selectData());
        
        $this->assertEquals('SELECT * FROM test Test', $tb->buildSelect());
        $this->assertEquals('SELECT * FROM test Test LIMIT 10', $tb->buildSelect(10));
        $this->assertEquals('SELECT * FROM test Test LIMIT 10 OFFSET 10', $tb->buildSelect(10,10));
        
    }
    
}