<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

namespace Hitar\Common;

/**
 * SQL解析类
 */
class SqlStatementParser {
    
    /**
     *
     * @var array 类型映射数组
     */
    private static $type_map = [
            's' => \PDO::PARAM_STR,
            'i' => \PDO::PARAM_INT,
            'e' => \PDO::PARAM_STMT
    ];
    
    /**
     *
     * @var string
     */
    private $prepare_sql;
    
    /**
     *
     * @var array array of int
     */
    private $types;
            
    /**
     * 
     * @param string $sql sql语句，example：<code>"name=%s and age=%i"</code>
     */
    function __construct($sql) {
        $sql_new = '';
        $type = [];
        while (($pos=strpos($sql, '%')) !== FALSE){
            $type_str = substr($sql, $pos+1,1);
            if($type_str == '%'){
                $sql_new .= substr($sql, 0, $pos) . '%';
            }  else {
                $sql_new .= substr($sql, 0, $pos) . '?';
                $type[] = self::$type_map[$type_str];
            }
            $sql = substr($sql, $pos+2);
        }
        $sql_new .= $sql;
        $this->types = $type;
        $this->prepare_sql = $sql_new;
    }
    
    /**
     * 返回包含"?"的sql语句
     * @return string
     */
    function getPrepareSql(){
        return $this->prepare_sql;
    }
    
    /**
     * 返回语句里包含的参数类型
     * @return array array of pdo常数
     */
    function getTypes(){
        return $this->types;
    }
}