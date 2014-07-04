<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

namespace Hitar\Common;

class SqlStatementParser {
    
    private static $type_map = [
            's' => \PDO::PARAM_STR,
            'i' => \PDO::PARAM_INT,
            'e' => \PDO::PARAM_STMT
    ];
    
    private $prepare_sql;
    
    private $types;
            
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
     * @return array of pdo常数
     */
    function getTypes(){
        return $this->types;
    }
}