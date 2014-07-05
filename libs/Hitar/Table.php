<?php

/**
 * 
 * @author kasonyang <i@kasonyang.com>
 */

namespace Hitar;

class Table{
    
    protected $alias;
    
    protected $table_name;


    /**
     * @var \Doctrine\DBAL\Query\QueryBuilder Description
     */
    protected $queryBuilder;
    
    /**
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    private $field;
    
    private $fields = [];
    
    private $primary_keys = [];
    
    private $orm_class;
    
    private $where_statement = '';
    
    private $where_params = [];

    private $where_types = [];
    /**
     * 
     * @return \Doctrine\DBAL\Connection
     */
    function getConnection(){
        if(!$this->connection){
            $this->connection = Connection::createConnection();
        }
        return $this->connection;
    }
    
    private function fetchDataFromOrmObject($object){
        $data = [];
        foreach($this->fields as $k => $f){
            /* @var $f \Doctrine\DBAL\Types\Type */
            $d = $object->{$k};
            $data[$k] = $d;
        }
        return $data;
    }
    
    private function getTypes(){
        $types = [];
        foreach($this->fields as $f){
            /* @var $f \Doctrine\DBAL\Types\Type */
            $types[] = $f->getBindingType();
        }
        return $types;
    }
    
    /**
     * 
     * @param string $orm orm类名或实例
     */
    function __construct($orm) {
        $this->orm_class = $orm;
        $annotation = new Common\OrmAnnotation($orm);
        $this->table_name = $annotation->getTableName();
        if(!$this->table_name){
            throw new \Exception('Table name undefined');
        }
        $this->alias = $annotation->getTableAliasName();
        $this->fields = $annotation->getFields();
        $this->primary_keys = $annotation->getPrimaryKeys();
        $this->queryBuilder = new \Doctrine\DBAL\Query\QueryBuilder($this->getConnection());
        $this->queryBuilder->select('*');
    }
    
    /**
     * 
     * @param string $field
     */
    function field($field){
        $this->field = $field;
    }
    
    /**
     * 
     * @param string $field_name
     */
    function addGroup($field_name){
        $this->queryBuilder->addGroupBy($field_name);
    }
    
    /**
     * 
     * @param string $field_name
     * @param string $mode set of "asc","desc"
     */
    function addOrder($field_name,$mode = 'asc'){
        $this->queryBuilder->addOrderBy($field_name, $mode);
    }
    
    /**
     * 
     * @param string $where where字串
     * @param array $params
     */
    function where($where,$params = []){
        if(is_array($where)){
            $and = $this->queryBuilder->expr()->andX();
            foreach($where as $k => $v){
                $and->add($this->queryBuilder->expr()->eq($k, '?'));
                $params [] = $v;
            }
            $where = $and;
        }
        $stmt = new Common\SqlStatementParser($where);
        $this->where_types = $stmt->getTypes();
        $this->where_params = $params;
        $this->where_statement = $stmt->getPrepareSql();
        return $this;
    }
    
    /*
    function andWhere($where){
        $this->queryBuilder->andWhere($where);
    }
    
    function orWhere($where){
        $this->queryBuilder->orWhere($where);
    }
     * 
     */
    
    /**
     * 
     * @param string $having
     */
    function andHaving($having){
        $this->queryBuilder->andHaving($having);
    }
    
    /**
     * 
     * @param string $having
     */
    function orHaving($having){
        $this->queryBuilder->orHaving($having);
    }
    
    /**
     * 
     * @param Table $table
     * @param string $condition
     */
    function join($table,$condition = NULL){
        $this->queryBuilder->join($this->alias, $table->getTableName(), $table->getTableAliasName(), $condition);
    }
    
    /**
     * 
     * @param Table $table
     * @param string $condition
     */
    function leftJoin($table,$condition = NULL){
        $this->queryBuilder->leftJoin($this->alias, $table->getTableName(), $table->getTableAliasName(),$condition);
    }
    
    /**
     * 
     * @param Table $table
     * @param string $condition
     */
    function rightJoin($table,$condition = NULL){
        $this->queryBuilder->rightJoin($this->alias, $table->getTableName(), $table->getTableAliasName(), $condition);
    }
    
    /**
     * 
     * @param Table $table
     * @param string $condition
     */
    function innerJoin($table,$condition = NULL){
        $this->queryBuilder->innerJoin($this->alias, $table->getTableName(), $table->getTableAliasName(), $condition);
    }
    
    /**
     * 
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private function getQueryBuilder(){
        $qb = clone $this->queryBuilder;
        if($this->where_statement){
            $qb->where($this->where_statement);
            $params = $this->where_params;
            $types = $this->where_types;
            if($params){
                $qb->setParameters($params,$types);
            }
        }
        return $qb;
    }
    
    /**
     * 
     * @param int $limit
     * @param int $offset
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    private function getSelectQueryBuilder($limit = NULL,$offset = NULL){
        $builder = $this->getQueryBuilder();
        $builder->select($this->field);
        $builder->from($this->table_name, $this->alias);
        if($limit !== null){
            $builder->setMaxResults($limit);
        }
        if($offset !== NULL){
            $builder->setFirstResult($offset);
        }
        return $builder;
    }

    /**
     * 
     * @param string $field
     * @return int
     */
    function count($field = '*'){
        $builder = $this->getQueryBuilder();
        $builder->select($this->getConnection()->getDatabasePlatform()->getCountExpression($field) . ' AS TOTAL');
        $builder->from($this->table_name, $this->alias);
        $stmt = $builder->execute();
        return intval($stmt->fetch()['TOTAL']);
    }

    /**
     * 
     * @param int $limit
     * @param int $offset
     * @return string Description
     */
    function buildSelect($limit=NULL,$offset=NULL){
        $builder = $this->getSelectQueryBuilder($limit,$offset);
        return $builder->getSQL();
    }
    
    /**
     * 
     * @param int $limit
     * @param int $offset
     * @return array
     */
    function selectData($limit=NULL,$offset = NULL){
        $builder = $this->getSelectQueryBuilder($limit,$offset);
        $stmt = $builder->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * 
     * @param int $limit
     * @param int $offset
     * @return array array of RecordBase
     */
    function select($limit=NULL,$offset = NULL){
        $objs = [];
        $data = $this->selectData($limit,$offset);
        $orm_class = $this->orm_class;
        foreach($data as $d){
            $objs[] = new $orm_class(NULL,$d);
        }
        return $objs;
    }
    
    /**
     * 
     * @param RecordBase|array  $record
     * @return int 影响的行数
     */
    function insert($record){
        $conn = $this->getConnection();
        if(is_object($record)){
            $data = $this->fetchDataFromOrmObject($record);
            $type = $this->getTypes();
        }else{
            $data = $record;
            $type = [];
        }
        return $conn->insert($this->table_name, $data, $type);
    }
    
    /**
     * 
     * @param string|null $seq_name
     * @return int
     */
    function lastInsertId($seq_name = null){
        return $this->getConnection()->lastInsertId($seq_name);
    }
    
    /**
     * 
     * @param array|object $data
     * @return string Description
     */
    function update($data){
        $builder = new \Doctrine\DBAL\Query\QueryBuilder($this->getConnection());
        $params = [];
        $param_types = [];
        if(is_object($data)){
            $data = $this->fetchDataFromOrmObject($data);
            $types = $this->getTypes();
        }
        $data_index = 0;
        foreach($data as $k => $v){
            $builder->set($k, "?");
            $params[] = $v;
            $param_types[] = isset($types[$data_index]) ? $types[$data_index] : \PDO::PARAM_STR;
        }
        if($this->where_statement){
            $builder->where($this->where_statement);
            if($this->where_params){
                $params = array_merge($params,$this->where_params);
            }
            if($this->where_types){
                $param_types = array_merge($param_types, $this->where_types);
            }
        }
        $builder->setParameters($params, $param_types);
        $builder->update($this->table_name);
        return $builder->execute();
    }
    
    /**
     * 
     * @param int $limit
     * @return string Description
     */
    function delete(){
        $qb = $this->getQueryBuilder();
        $qb->delete($this->table_name);
        return $qb->execute();
    }
    
    /**
     * 
     * @return array array of string
     */
    function getPrimaryKeys(){
        return $this->primary_keys;
    }
    
    /**
     * 
     * @return string
     */
    function getTableName(){
        return $this->table_name;
    }
    
    /**
     * 
     * @return string
     */
    function getTableAliasName(){
        return $this->alias;
    }
}