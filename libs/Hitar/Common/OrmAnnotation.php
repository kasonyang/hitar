<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

namespace Hitar\Common;

class OrmAnnotation{
    
    private $fields;
    
    private $primary_keys = [];
    
    private $table_name;
    
    private $alias_name;


    /**
     * 
     * @param string $param_str
     * @return array
     */
    private function parseParamValue($param_str){
        return json_decode('{' + $param_str + '}');
    }
    
    private function getFieldByTagVaule($tag_value){
        $left_delim = strpos($tag_value, '(');
        if($left_delim !== FALSE){
            $type = substr($tag_value, 0, $left_delim);
            $param_str = substr($tag_value, $left_delim + 1, -1);
            $params = $this->parseParamValue($param_str);
        }else{
            $type = $tag_value;
            $params = [];
        }
        return \Doctrine\DBAL\Types\Type::getType($type);
    }

    private function initFieldsAndPks($class){
        $field_objects = [];
        $pks = [];
        $comment = new \PhpComment\Comment($class);
        $tags = $comment->getAttributeTags();
        foreach($tags as $p => $t){
            /* @var $t Tags */
            if($field_values = $t->get('field')){
               $field_objects[$p] = $this->getFieldByTagVaule($field_values[0]);
               if($t->get('primary')){
                   $pks[] = $p;
               }
            }
        }
        $this->fields = $field_objects;
        $this->primary_keys = $pks;
    }
    
    /**
     * 
     * @param string|object $class
     */
    function __construct($class) {
        $this->initFieldsAndPks($class);
        $comment = new \PhpComment\Comment($class);
        $tag = $comment->getClassTag();
        $table_info = explode(' ', $tag->get('table')[0])  ;
        $this->table_name = $table_info[0];
        $this->alias_name = isset($table_info[1]) ? $table_info[1] : '';
    }
    
    /**
     * 
     * @return array field_name => object
     */
    function getFields(){
        return $this->fields;
    }
    
    function getTableName(){
        return $this->table_name;
    }
    
    function getTableAliasName(){
        return $this->alias_name;
    }
    
    /**
     * 
     * @return array
     */
    function getPrimaryKeys(){
        return $this->primary_keys;
    }
    
}