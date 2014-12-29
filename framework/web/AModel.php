<?php

/**
 * 切换DB，请重写
 * 静态变量：public static $db_name = "ol_zhuanqu";
 * 静态方法：public static function getDbName();
 */
abstract class AModel extends CModel{

    public $prefix = '';
    public $table;
    public $pk = '';
    public $_fileds = array();
    public $last_sql = '';

    public function init(){
        $pre = $this->get_tablePrefix();
        $this->prefix = $pre;
        $this->setTableName();
    }

    //子类想重置table_name时，请重写setTableName方法并调用parent.setTableName();
    public function setTableName($table_name=""){
        $table_name = $table_name?$table_name:strtolower(get_class($this));
        $this->table = $this->prefix.$table_name;
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function setPk($pk="id"){
        $this->pk = $pk;
    }

    public function getPk(){
        return $this->pk;
    }

    //表的字段，新增与修改时会核对字段
    public function setField($fileds=""){
        if($fileds==""||empty($fileds)){
            $fileds = $this->getAll("DESC $this->table;");
            $fileds = array_map(create_function('$val','return $val["Field"];'), $fileds);
        }
        if(is_string($fileds)) $fileds = explode(",",$fileds);
        $this->_fileds = $fileds;
    }

    public function getField(){
        if(empty($this->_fileds))
            $this->setField();
        return $this->_fileds;
    }


    public function findByPk($pk,$field='*'){
        return $this->getRow("select $field from $this->table where $this->pk=$pk ;");
    }

    public function findAll($field='*'){
        return $this->getAll("select $field from $this->table ;");
    }

    public function findByAttributes($attributes,$field='*'){
        $where = $this->_getWhereByArray($attributes);
        return $this->getRow("select $field from $this->table $where ;");
    }

    public function findAllByAttributes($attributes,$field='*'){
        $where = $this->_getWhereByArray($attributes);
        return $this->getAll("select $field from $this->table $where ;");
    }

    public function findBySql($sql){
        return $this->getRow($sql);
    }

    public function findAllBySql($sql){
        return $this->getAll($sql);
    }

    public function countAll(){
        $sql = "select count(*) as total from $this->table;";
        $total = $this->getRow($sql);
        return $total['total'];
    }

    public function countByAttributes($attributes){
        $where = $this->_getWhereByArray($attributes);
        $sql = "select count(*) as total from $this->table $where;";
        $total = $this->getRow($sql);
        return $total['total'];
    }

    public function countBySql($sql){
        $total = $this->getRow($sql);
        return $total;
    }

    public function updateByPk($pk,$params){
        $fields = "";
        foreach($params as $key=>$ar){
            $fields .= ",".$key."='".$ar."'";
        }
        trim($fields,",");
        return $this->query("update $this->table set $fields where $this->pk=$pk ;");
    }

    public function updateByAttributes($attributes,$params=array()){
        $where = $this->_getWhereByArray($attributes);

        $fields = "";
        foreach($params as $key=>$ar){
            $fields .= ",".$key."='".$ar."'";
        }
        trim($fields,",");

        return $this->query("update $this->table set $fields $where ;");
    }

    public function deleteByPk($pk){
        return $this->query("delete from $this->table where $this->pk=$pk ;");
    }

    public function deleteByAttributes($attributes){

        $where = $this->_getWhereByArray($attributes);
        return $this->query("delete from $this->table $where ;");
    }


    public function insert($arr=array(),$table=""){
        if(empty($arr)) return false;
        if(!$table) $table = $this->table;

        foreach($arr as $k=>$li){
            if(!in_array($k,$this->_fileds)) unset($arr[$k]);
        }

        if(!get_magic_quotes_gpc()) $arr = array_map("addslashes",$arr);
        $keys = array_keys($arr);
        $values = array_values($arr);
        $key_str = implode($keys,",");
        $var_str = implode($values,"','");
        $sql = "insert into $table($key_str) value('$var_str');";
        if($this->query($sql))
            return $this->Insert_ID();
        else return false;
    }

    public function update($wheres=array(),$arr=array(),$table=""){
        if(empty($arr)||empty($wheres)) return false;
        if(!$table) $table = $this->table;

        $update = "";
        if(!get_magic_quotes_gpc()) $arr = array_map("addslashes",$arr);
        foreach($arr as $k=>$li){
            if(in_array($k,$this->_fileds)) $update .= "$k='$li',";
        }
        $update = trim($update,",");

        $where = "";
        if(!get_magic_quotes_gpc()) $wheres = array_map("addslashes",$wheres);
        foreach($wheres as $key=>$val){
            if(is_string($key)) $where .= $where==""?" $key='$val'":" and $key='$val'";
            else $where .= $where==""?" $val":" and $val";
        }
        if($where) $where = "where ".$where;

        $sql = "update $table set $update $where;";
        if($this->query($sql)) return true;
        else return false;
    }

    public function _getWhereByArray($wheres){
        if(is_string($wheres)) return "where ".$wheres;
        $other = "";
        if(isset($wheres['order'])&&$wheres['order']){
            $other .= " order by ".$wheres['order'];
            unset($wheres['order']);
        }
        if(isset($wheres['limit'])&&$wheres['limit']){
            $other .= " limit ".$wheres['limit'];
            unset($wheres['limit']);
        }
        $where = "where 1=1";
        foreach($wheres as $key=>$ar){
            $where .= " and ".$key."='".$ar."'";
        }
        return $where.$other;
    }

}