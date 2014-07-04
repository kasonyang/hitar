<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

namespace Hitar;

abstract class RecordBase {

    private $exist = null;
    
    private $where_kvs = [];
    
    /**
     * 
     * @return \Hitar\Table
     */
    private function getTable() {
        $tb = self::table();
        $tb->where($this->where_kvs);
        return $tb;
    }

    /**
     * 
     * @return \Hitar\Table
     */
    static function table(){
        return  new Table(get_called_class());
    }
    
    /**
     * 构造函数
     * @param array $pks_array 主键名-值关联数组
     * @param array $data_array 非主键列名-值数组，若此参数缺省，构造函数会自动
     * 读取数据表，以取得非主键列的值
     * @throws HitarRecordConstructException
     */
    function __construct($pks_array, $data_array = null) {
        if ($data_array === null) {
            if(!$pks_array){
                throw new \Exception('pks invalid');
            }
            $this->where_kvs = $pks_array;
        }else{
            $pks = self::table()->getPrimaryKeys();
            if(!$pks){
                throw new \Exception('Primary undefined');
            }
            foreach ($pks as $p){
                $this->where_kvs[$p] = $data_array[$p];
            }
        }
        if(!$data_array){
            $data_array = $this->fetchData();
        }
        $this->exist = $data_array ? TRUE : FALSE;
        $pks_array and $this->assign($pks_array);
        $data_array and $this->assign($data_array);
    }
    
    private function fetchData(){
        $ret = $this->getTable()->selectData(1);
        return $ret ? $ret[0] : [];
    }
    
    /**
     * 重新读取数据数据库，以获得最新的数据，调用此函数后，以前的赋值将被覆盖
     */
    function refresh() {
        $data = $this->fetchData();
        $this->exist = $data ? TRUE : FALSE;
        $this->assign($data);
    }

    /**
     * 保存数据到数据库，若有赋值，系统会自动调用此函数
     * @param bool $AutoCreate
     * @return boolean
     */
    function save($AutoCreate = true) {
        if ($this->exist()) {
            return $this->getTable()->update($this) > 0;
        } elseif ($AutoCreate) {
            return $this->getTable()->insert($this) > 0;
        } else {
            return false;
        }
    }

    /**
     * 删除记录
     * @return boolean
     */
    function delete() {
        return $this->getTable()->delete();
    }

    /**
     * 检查相应的记录是否存在
     * @return bool
     */
    function exist() {
        return $this->exist;
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
