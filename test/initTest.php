<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */
include_once __DIR__ . '/../vendor/autoload.php';

/**
 * @table test Test
 */
class Test extends \Hitar\RecordBase{

    /**
     * @primary
     * @field string(length:100)
     * @var string
     */
    public $nickname;
}

$db_path =  __DIR__ . '/test.sqlite';
if(file_exists($db_path)){
    unlink($db_path);
}

\Hitar\DatabaseManager::addDatabase('test', [
    'driver'    =>  'pdo_sqlite',
    'user'  =>  '',
    'password'  =>  '',
    'path'      =>  $db_path,
    //'memory'    =>  TRUE
]);
\Hitar\DatabaseManager::selectDatabase('test');
