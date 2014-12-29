<?php
echo md5("liqu");die();

abstract class AModel extends CModel{

    public $prefix = '';
    public $_table = '';
    public $_pk = '';
    public $_fileds = array();
    public $last_sql = '';

    public function init(){
        $this->prefix = $this->get_tablePrefix();
        if(isset($this->table)&&$this->table) $this->_table = $this->prefix.$this->table;
        else $this->_table = $this->setTableName();
        $this->setPk($this->pk);
        if(isset($this->fields)) $this->setField($this->fields);
    }

    //子类想重置table_name时，请重写setTableName方法并调用parent.setTableName();
    public function setTableName(){
        return $this->prefix.strtolower(get_class($this));
    }

    public function getTableName()
    {
        return $this->_table;
    }

    public function setPk($pk){
        $this->_pk = $pk?$pk:"id";
    }

    public function getPk(){
        return $this->_pk;
    }

    //表的字段，新增与修改时会核对字段
    public function setField($fileds=""){
        $fileds = $fileds?$fileds:"";
        if($fileds==""||empty($fileds)){
            if(!$this->_table) die("please set table name");
            $fileds = $this->getAll("DESC $this->_table;");
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
        return $this->getRow("select $field from $this->_table where $this->_pk=$pk ;");
    }

    public function findAll($field='*',$attributes=array()){
        $where = $this->_getWhereByArray($attributes);
        return $this->getAll("select $field from $this->_table $where ;");
    }

    public function findByAttributes($attributes,$field='*'){
        $where = $this->_getWhereByArray($attributes);
        return $this->getRow("select $field from $this->_table $where ;");
    }

    public function findAllByAttributes($attributes,$field='*'){
        $where = $this->_getWhereByArray($attributes);
        return $this->getAll("select $field from $this->_table $where ;");
    }

    public function findBySql($sql){
        return $this->getRow($sql);
    }

    public function findAllBySql($sql,$key=''){
        return $this->getAll($sql,$key);
    }

    public function countAll(){
        $sql = "select count(*) as total from $this->_table;";
        $total = $this->getRow($sql);
        return $total['total'];
    }

    public function countByAttributes($attributes){
        $where = $this->_getWhereByArray($attributes);
        $sql = "select count(*) as total from $this->_table $where;";
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
        return $this->query("update $this->_table set $fields where $this->_pk=$pk ;");
    }

    public function updateByAttributes($attributes,$params=array()){
        $where = $this->_getWhereByArray($attributes);

        $fields = "";
        foreach($params as $key=>$ar){
            $fields .= ",".$key."='".$ar."'";
        }
        trim($fields,",");

        return $this->query("update $this->_table set $fields $where ;");
    }

    public function deleteByPk($pk){
        return $this->query("delete from $this->_table where $this->_pk=$pk ;");
    }

    public function deleteByAttributes($attributes){

        $where = $this->_getWhereByArray($attributes);
        return $this->query("delete from $this->_table $where ;");
    }


    public function insert($arr=array(),$table=""){
        if(empty($this->_fileds)) die("you can not use this function until 'filed' _filed attributes;");
        if(empty($arr)) return false;
        if(!$table) $table = $this->_table;

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
        if(empty($this->_fileds)) die("you can not use this function until setting 'filed' attributes;");
        if(empty($arr)||empty($wheres)) return false;
        if(!$table) $table = $this->_table;

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
            if(is_int($key)){
                $where .= " and $ar";
                continue;
            }
            $where .= " and ".$key."='".$ar."'";
        }
        return $where.$other;
    }

}