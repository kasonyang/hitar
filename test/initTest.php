<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */
include_once __DIR__ . '/../vendor/autoload.php';

/**
 * @table test Test
 */
class Test extends \Hitar\RecordBase {

    /**
     * @primary
     * @field string(length:100)
     * @var string
     */
    public $nickname;

}

/**
 * @table tableexample TableExample
 */
class TableExample extends \Hitar\RecordBase {

    /**
     *
     * @field integer
     * @primary
     * @generator guid
     */
    public $id;

    /**
     *
     * @field string(length:100)
     */
    public $name;

}

$db_path = __DIR__ . '/test.sqlite';
if (file_exists($db_path)) {
    unlink($db_path);
}

\Hitar\DatabaseManager::addDatabase('test', [
    'driver' => 'pdo_sqlite',
    'user' => '',
    'password' => '',
    'path' => $db_path,
        //'memory'    =>  TRUE
]);
\Hitar\DatabaseManager::selectDatabase('test');

function init(){
        $tb = Test::table();

    $sm = $tb->getConnection()->getSchemaManager();
    $schema = $sm->createSchema();

    $new_tb = $schema->createTable('test');
    $new_tb->addColumn('nickname', 'string', ['length' => 100]);

    $new_tb2 = $schema->createTable('tableexample');
    $new_tb2->addColumn('id', 'string', ['length' => 100]);
    $new_tb2->addColumn('name', 'string', ['length' => 100]);

    $sqls = $schema->toSql($tb->getConnection()->getDatabasePlatform());
    foreach ($sqls as $q) {
        $tb->getConnection()->exec($q);
    }
}

init();
