<?php
class mysqlDB {

	var $connection;
	var $params;
	var $fields = array();
	var $EOF = 0;
    var $FetchMode = 'assoc';
	var $result;
	var $debug = false;
    public $last_sql = "";
    public static $mysqlDB = null;
    public static $db_default = null;
    public static $db_config = null;

    public static function setDBDefault($db_default){
        self::$db_default = $db_default;
    }

    public static function setDBConfig($db_config){
        self::$db_config = $db_config;
    }

    public static function getInstance($db_name=""){
        if(!$db_name) $db_name = self::$db_default;
        if(self::$mysqlDB==null||!isset(self::$mysqlDB[$db_name])){
            $hash = new Flexihash();
            $hash->addTargets(array_keys(self::$db_config[$db_name]));
            self::$mysqlDB[$db_name] = new mysqlDB();
            self::hashConnect($hash,self::$db_config[$db_name],$db_name);
        }else{
            self::$mysqlDB[$db_name]->ping();
        }
        return self::$mysqlDB[$db_name];
    }

    public static function hashConnect(&$hash,$configs,$db_name){
        $config_key = $hash->lookup(rand());
        $conf = $configs[$config_key];
        if($db_name!==$conf['db_name'])
            throw new Exception("db config error,DB:$db_name key:$config_key db_name must equal to '$db_name';");
        //此服务器连接不通，换下一个
        if(!self::$mysqlDB[$db_name]->connect($conf)){
            echo "$db_name:$config_key:connect failure;";
            $hash->removeTarget($config_key);
            $targets = $hash->getAllTargets();
            if(empty($targets))
                throw new Exception("all DB:$db_name connect failure,no one available;");
            self::hashConnect($hash,$configs,$db_name);
        }else{
            return true;
        }
    }

	function connect($params){
        $this->params = $params;
		$this->connection = @mysql_connect($params['db_host'],$params['db_user'],$params['db_password']);// OR die("<b>kDB Error:</b> Connecting to MySQL failed,please contact to your administrator");
        if(!$this->connection){
            return false;
        }
        $db_charset = !empty($params['db_charset'])?$params['db_charset']:"utf8";
		mysql_query("set names $db_charset",$this->connection);
		if(!empty($params['db_name'])) {
			return mysql_select_db($params['db_name'],$this->connection) or die("<b>kDB Error:</b> Database {$params['db_name']} does not exists, please contact to your administrator");
		}
	}

    function get_tablePrefix(){
        if(isset($this->params['tablePrefix']))
            return $this->params['tablePrefix'];
    }

	function ping(){
		if(!mysql_ping($this->connection)){
			mysql_close($this->connection);
			$this->connect($this->params);
		} 
	}

	function db_close() {
		@mysql_close($this->connection,$this->connection);
	}
	
	function Close() {
		unset($this->result);
	}

	function is_write($query){
		$query = trim($query);
		if(stripos($query, 'insert')===0 || stripos($query, 'update')===0 || stripos($query, 'delete')===0 || stripos($query, 'create')===0 || stripos($query, 'drop')===0 || stripos($query, 'grant')===0 || stripos($query, 'load')===0 || stripos($query, 'source')===0 || stripos($query, 'alter')===0)
			return true;
		else
			return false;
	}

	function query($query){
        $this->last_sql = $query;
		if($this->debug) $stime=microtime(true);
		$Query = mysql_query($query,$this->connection);
		if($this->debug) $GLOBALS['QueryLog'][count($GLOBALS['QueryLog'])] = array('time'=>microtime(true)-$stime,'query'=>$query);
		return $Query;
	}

	function setDebug($debug)
	{
		$this->debug = $debug;
	}

	function Execute($query)
	{
		$this->result = $this->query($query);
		if($this->result) {
			if($this->FetchMode == 'num') {
				if($this->fields = mysql_fetch_array($this->result, MYSQL_NUM))
					$this->EOF = 0;
				else
					$this->EOF = 1;
			} elseif($this->FetchMode == 'assoc') {
				if($this->fields = mysql_fetch_array($this->result, MYSQL_ASSOC))
					$this->EOF = 0;
				else
					$this->EOF = 1;
			} else {
				if($this->fields = mysql_fetch_array($this->result))
					$this->EOF = 0;
				else
					$this->EOF = 1;
			}
		} else {
			$this->EOF = 1;
		}
		return $this;
	}
	
	function MoveNext() 
	{
		if($this->FetchMode == 'num') {
			if($this->fields = mysql_fetch_array($this->result, MYSQL_NUM))
				$this->EOF = 0;
			else
				$this->EOF = 1;
		} elseif($this->FetchMode == 'assoc') {
			if($this->fields = mysql_fetch_array($this->result, MYSQL_ASSOC))
				$this->EOF = 0;
			else
				$this->EOF = 1;
		} else {
			if($this->fields = mysql_fetch_array($this->result))
				$this->EOF = 0;
			else
				$this->EOF = 1;
		}
	}

	function getRow($query)
	{
		$Query = $this->query($query);
        if(!$Query) return array();
		$Query = mysql_fetch_array($Query, MYSQL_ASSOC);
		return $Query;
	}

    /*
     * 批量查询
     * return array()
     */
    function getAll($query,$key='',$key1='') //for adodb
    {
        $result = $this->Execute($query);
        $return = array();
        while(!$result->EOF) {
            $row=$result->fields;
            if(empty($key)) $return[]=$row;
            elseif(empty($key1)) $return[$row[$key]]=$row;
            else $return[$row[$key]][$row[$key1]]=$row;

            $result->MoveNext();
        }
        return $return;
    }
	
	function FetchRow()
	{
		return mysql_fetch_array($this->result, MYSQL_ASSOC);
	}

	function RecordCount()
	{
		return mysql_num_rows($this->result);
	}

	function fetch_array($query) {
		$Query = mysql_fetch_array($query);
		return $Query;
	}

	function selectLimit($query, $start = NULL, $offset = NULL)
	{
		if(empty($offset) && empty($start))
			$query = $query;
		elseif(empty($offset) && !empty($start))
			$query = $query." LIMIT $start";
		elseif(!empty($offset) && !empty($start))
			$query = $query." LIMIT $start, $offset";

		$this->result =  $this->query($query);

		if($this->result) {
			$this->fields = mysql_fetch_array($this->result, MYSQL_ASSOC);
			$this->EOF = 0;
		} else {
			$this->EOF = 1;
		}
		return $this;
	}

    function free_result($query) {
    	mysql_free_result($query);
    }

	function Insert_ID() {
		return mysql_insert_id($this->connection);
	}

	function errormsg($connection='')
	{
		if(!$connection)	$connection = $this->connection;
		$result["message"] = mysql_error($connection);
		$result["code"] = mysql_errno($connection);
		return $result;
	}

	function error($connection='') {
		if(!$connection)	$connection = $this->connection;
		return mysql_error($connection);
	}

	function errno($connection='') {
		if(!$connection)	$connection = $this->connection;
		return mysql_errno($connection);
	}

	function escape_string($string)
	{
		return  mysql_real_escape_string($string) ;
	}

	function SetFetchMode($mode)
	{
		$this->FetchMode = $mode;
	}

	function FieldCount()
	{
		return mysql_num_fields($this->result);
	}

	function info()
	{
		return mysql_get_server_info($this->connection);
	}

    function begin()
    {
        mysql_query("START TRANSACTION");
    }

    function commit()
    {
        mysql_query("COMMIT");
    }

    function rollback()
    {
        mysql_query("START ROLLBACK");
    }

}

?>