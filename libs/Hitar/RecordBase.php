<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

namespace Hitar;

abstract class RecordBase {
    
    private $primary_keys;
    
    /**
     * 
     * @return \Hitar\Table | false
     */
    private function getTable() {
        $pks = [];
        foreach($this->primary_keys as $key){
            if(!isset($this->{$key})){
                return false;
            }
            $pks[$key] = $this->{$key};
        }
        return self::table()->where($pks);
    }

    /**
     * 
     * @return \Hitar\Table
     */
    static function table(){
        return  new Table(get_called_class());
    }
    
    /**
     * 
     * @param array $kvs
     * @return RecordBase|false
     */
    static function get($kvs){
        $tb = self::table();
        $result = $tb->where($kvs)->select();
        if($result){
            return $result[0];
        }else{
            return FALSE;
        }
    }


    /**
     * 构造函数
     * @param array $pks_array 主键名-值关联数组
     * @param array $data_array 非主键列名-值数组，若此参数缺省，构造函数会自动
     * 读取数据表，以取得非主键列的值
     */
    function __construct() {
        $this->primary_keys = self::table()->getPrimaryKeys();
    }

    /**
     * 保存数据到数据库，若有赋值，系统会自动调用此函数
     * @param bool $auto_create
     * @return boolean
     */
    function save($auto_create = true) {
        $tb = $this->getTable();
        if($tb === false){
            $exist = FALSE;
        }else{
            $exist = $tb->count() > 0;
        }
        if($exist){
            return $tb->update($this) > 0;
        }else{
            if($auto_create){
                return self::table()->insert($this) > 0;
            }else{
                return false;
            }
        }
    }

    /**
     * 删除记录
     * @return boolean
     */
    function delete() {
        $tb = $this->getTable();
        if($tb !== FALSE){
            return $tb->delete() > 0;
        }else{
            return false;
        }
    }

    /**
     * 以关联数组的形式赋值
     * @param array $data_array 需要赋值的键-值数组
     * @return RecorBase
     */
    function assign($data_array) {
        foreach ($data_array as $k => $v) {
            $this->{$k} = $v;
        }
        return $this;
    }

}
