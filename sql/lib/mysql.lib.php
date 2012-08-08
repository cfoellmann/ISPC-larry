<?php

/*
Copyright (c) 2007, Till Brehm, projektfarm Gmbh
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice,
      this list of conditions and the following disclaimer in the documentation
      and/or other materials provided with the distribution.
    * Neither the name of ISPConfig nor the names of its contributors
      may be used to endorse or promote products derived from this software without
      specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

class db
{
	var $dbHost = "";		  // hostname of the MySQL server
	var $dbName = "";		  // logical database name on that server
	var $dbUser = "";		  // database authorized user
	var $dbPass = "";		  // user's password
	var $dbCharset = "";      // what charset comes and goes to mysql: utf8 / latin1
	var $linkId = 0;		  // last result of mysql_connect()
	var $queryId = 0;		  // last result of mysql_query()
	var $record	= array();	  // last record fetched
    var $autoCommit = 1;      // Autocommit Transactions
	var $currentRow;		  // current row number
	var $errorNumber = 0;	  // last error number
	var $errorMessage = "";	  // last error message
	var $errorLocation = "";  // last error location
	var $show_error_messages = false;

	// constructor
	function db()
	{
		global $conf;
		$this->dbHost = $conf['db_host'];
		$this->dbName = $conf['db_database'];
		$this->dbUser = $conf['db_user'];
		$this->dbPass = $conf['db_password'];
		$this->dbCharset = $conf['db_charset'];
		//$this->connect();
	}

	// error handler
	function updateError($location)
	{
		$this->errorNumber = mysql_errno();
		$this->errorMessage = mysql_error();
		$this->errorLocation = $location;
		if($this->errorNumber && $this->show_error_messages)
		{
			echo('<br /><b>'.$this->errorLocation.'</b><br />'.$this->errorMessage);
			flush();
		}
	}

	function connect()
	{
		if($this->linkId == 0)
		{
			$this->linkId = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);

			if(!$this->linkId)
			{
				$this->updateError('DB::connect()<br />mysql_connect');
				return false;
			}
			$this->queryId = @mysql_query('SET NAMES '.$this->dbCharset, $this->linkId);
		}
		return true;
	}

	function query($queryString)
	{
		if(!$this->connect())
		{
			return false;
		}
		if($this->dbName != '') {
			if(!mysql_select_db($this->dbName, $this->linkId))
			{
				$this->updateError('DB::connect()<br />mysql_select_db');
				return false;
			}
		}
		$this->queryId = @mysql_query($queryString, $this->linkId);
		$this->updateError('DB::query('.$queryString.')<br />mysql_query');
		if(!$this->queryId)
		{
			return false;
		}
		$this->currentRow = 0;
		return $this->queryId;
	}

	// returns all records in an array
	function queryAllRecords($queryString)
	{
		if(!$this->query($queryString))
		{
			return false;
		}
		$ret = array();
		while($line = $this->nextRecord())
		{
			$ret[] = $line;
		}
		return $ret;
	}

	// returns one record in an array
	function queryOneRecord($queryString)
	{
		if(!$this->query($queryString) || $this->numRows() == 0)
		{
			return false;
		}
		return $this->nextRecord();
	}

	// returns the next record in an array
	function nextRecord()
	{
        $this->record = mysql_fetch_assoc($this->queryId);
		$this->updateError('DB::nextRecord()<br />mysql_fetch_array');
		if(!$this->record || !is_array($this->record))
		{
			return false;
		}
		$this->currentRow++;
		return $this->record;
	}

	// returns number of rows returned by the last select query
	function numRows()
	{
		return mysql_num_rows($this->queryId);
	}
	
	function affectedRows()
	{
		return mysql_affected_rows($this->linkId);
	}
	
	// returns mySQL insert id
	function insertID()
	{
		return mysql_insert_id($this->linkId);
	}
    
    // Check der variablen
	// deprecated, now use quote
    function check($formfield)
    {
        return $this->quote($formfield);
    }
	
	// Check der variablen
    function quote($formfield)
    {
        return mysql_real_escape_string($formfield);
    }
	
	// Check der variablen
    function unquote($formfield)
    {
        return stripslashes($formfield);
    }
	
	function toLower($record) {
		if(is_array($record)) {
			foreach($record as $key => $val) {
				$key = strtolower($key);
				$out[$key] = $val;
			}
		}
	return $out;
	}
   
   
   function insert($tablename,$form,$debug = 0)
   {
     if(is_array($form)){
       foreach($form as $key => $value) 
	    {
	    $sql_key .= "$key, ";
        $sql_value .= "'".$this->check($value)."', ";
  		 }
   	$sql_key = substr($sql_key,0,strlen($sql_key) - 2);
    $sql_value = substr($sql_value,0,strlen($sql_value) - 2);
    
   	$sql = "INSERT INTO $tablename (" . $sql_key . ") VALUES (" . $sql_value .")";
   
  		 if($debug == 1) echo "SQL-Statement: ".$sql."<br><br>";
  		 $this->query($sql);
  		 if($debug == 1) echo "mySQL Error Message: ".$this->errorMessage;
      }
   }
   
   function update($tablename,$form,$bedingung,$debug = 0)
   {
   
     if(is_array($form)){
       foreach($form as $key => $value) 
	    {
	    $insql .= "$key = '".$this->check($value)."', ";
  		 }
   	        $insql = substr($insql,0,strlen($insql) - 2);
   	        $sql = "UPDATE $tablename SET " . $insql . " WHERE $bedingung";
  		 if($debug == 1) echo "SQL-Statement: ".$sql."<br><br>";
  		 $this->query($sql);
  		 if($debug == 1) echo "mySQL Error Message: ".$this->errorMessage;
       }
   }
   
   function closeConn() {
   
   }
   
   function freeResult() {
   
   
   }
   
   function delete() {
   
   }
   
   function Transaction($action) {
   //action = begin, commit oder rollback
   
   }
   
   /*
   $columns = array(action =>   add | alter | drop
                    name =>     Spaltenname
                    name_new => neuer Spaltenname, nur bei 'alter' belegt
                    type =>     42go-Meta-Type: int16, int32, int64, double, char, varchar, text, blob
                    typeValue => Wert z.B. bei Varchar
                    defaultValue =>  Default Wert
                    notNull =>   true | false
                    autoInc =>   true | false
                    option =>   unique | primary | index)
   
   
   */
   
   function createTable($table_name,$columns) {
   $index = "";
   $sql = "CREATE TABLE $table_name (";
   foreach($columns as $col){
        $sql .= $col["name"]." ".$this->mapType($col["type"],$col["typeValue"])." ";
   
        if($col["defaultValue"] != "") {
			if($col["defaultValue"] == "NULL" or $col["defaultValue"] == "NOT NULL") {
				$sql .= "DEFAULT ".$col["defaultValue"]." ";
			} else {
				$sql .= "DEFAULT '".$col["defaultValue"]."' ";
			}
			
		} elseif($col["defaultValue"] != false) {
			$sql .= "DEFAULT '' ";
		}
		if($col["defaultValue"] != "NULL" && $col["defaultValue"] != "NOT NULL") {
        	if($col["notNull"] == true) {
            	$sql .= "NOT NULL ";
        	} else {
            	$sql .= "NULL ";
        	}
		}
        if($col["autoInc"] == true) $sql .= "auto_increment ";
        $sql.= ",";
        // key Definitionen
        if($col["option"] == "primary") $index .= "PRIMARY KEY (".$col["name"]."),";
        if($col["option"] == "index") $index .= "INDEX (".$col["name"]."),";
        if($col["option"] == "unique") $index .= "UNIQUE (".$col["name"]."),";
   }
   $sql .= $index;
   $sql = substr($sql,0,-1);
   $sql .= ")";
   
   $this->query($sql);
   return true;
   }
   
   /*
   $columns = array(action =>   add | alter | drop
                    name =>     Spaltenname
                    name_new => neuer Spaltenname, nur bei 'alter' belegt
                    type =>     42go-Meta-Type: int16, int32, int64, double, char, varchar, text, blob
                    typeValue => Wert z.B. bei Varchar
                    defaultValue =>  Default Wert
                    notNull =>   true | false
                    autoInc =>   true | false
                    option =>   unique | primary | index)
   
   
   */
   function alterTable($table_name,$columns) {
   $index = "";
   $sql = "ALTER TABLE $table_name ";
   foreach($columns as $col){
        if($col["action"] == 'add') {
            $sql .= "ADD ".$col["name"]." ".$this->mapType($col["type"],$col["typeValue"])." ";
        } elseif ($col["action"] == 'alter') {
            $sql .= "CHANGE ".$col["name"]." ".$col["name_new"]." ".$this->mapType($col["type"],$col["typeValue"])." ";
        } elseif ($col["action"] == 'drop') {
            $sql .= "DROP ".$col["name"]." ";
        }
        if($col["action"] != 'drop') {  
        if($col["defaultValue"] != "") $sql .= "DEFAULT '".$col["defaultValue"]."' ";
        if($col["notNull"] == true) {
            $sql .= "NOT NULL ";
        } else {
            $sql .= "NULL ";
        }
        if($col["autoInc"] == true) $sql .= "auto_increment ";
        $sql.= ",";
        // key Definitionen
        if($col["option"] == "primary") $index .= "PRIMARY KEY (".$col["name"]."),";
        if($col["option"] == "index") $index .= "INDEX (".$col["name"]."),";
        if($col["option"] == "unique") $index .= "UNIQUE (".$col["name"]."),";
        }
   }
   $sql .= $index;
   $sql = substr($sql,0,-1);
   
   //die($sql);
   $this->query($sql);
   return true;
   }
   
   function dropTable($table_name) {
   $this->check($table_name);
   $sql = "DROP TABLE '". $table_name."'";
   return $this->query($sql);
   }
   
   // gibt Array mit Tabellennamen zur�ck
   function getTables($database_name = '') {
   	
		if($database_name == ''){
            $database_name = $this->dbName;
        }
        $result = mysql_query("SHOW TABLES FROM `$database_name`");
        $tb_names = array();
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $tb_names[$i] = mysql_tablename($result, $i);
        }
        return $tb_names;       
   }
   
   // gibt Feldinformationen zur Tabelle zur�ck
   /*
   $columns = array(action =>   add | alter | drop
                    name =>     Spaltenname
                    name_new => neuer Spaltenname, nur bei 'alter' belegt
                    type =>     42go-Meta-Type: int16, int32, int64, double, char, varchar, text, blob
                    typeValue => Wert z.B. bei Varchar
                    defaultValue =>  Default Wert
                    notNull =>   true | false
                    autoInc =>   true | false
                    option =>   unique | primary | index)
   
   
   */
   
   function tableInfo($table_name) {
   
   global $go_api,$go_info;
   // Tabellenfelder einlesen
    
    if($rows = $go_api->db->queryAllRecords("SHOW FIELDS FROM ".$table_name)){
    foreach($rows as $row) {
        $name = $row[0];
        $default = $row[4];
        $key = $row[3];
        $extra = $row[5];
        $isnull = $row[2];
        $type = $row[1];
    
        
        $column = array();
    
        $column["name"] = $name;
        //$column["type"] = $type;
        $column["defaultValue"] = $default;
        if(stristr($key,"PRI")) $column["option"] = "primary";
        if(stristr($isnull,"YES")) {
            $column["notNull"] = false;
        } else {
           $column["notNull"] = true; 
        }
        if($extra == 'auto_increment') $column["autoInc"] = true;
        
        
        // Type in Metatype umsetzen
        
        if(stristr($type,"int(")) $metaType = 'int32';
        if(stristr($type,"bigint")) $metaType = 'int64';
        if(stristr($type,"char")) {
            $metaType = 'char';
            $tmp_typeValue = explode('(',$type);
            $column["typeValue"] = substr($tmp_typeValue[1],0,-1);  
        }
        if(stristr($type,"varchar")) {
            $metaType = 'varchar';
            $tmp_typeValue = explode('(',$type);
            $column["typeValue"] = substr($tmp_typeValue[1],0,-1);  
        }
        if(stristr($type,"text")) $metaType = 'text';
        if(stristr($type,"double")) $metaType = 'double';
        if(stristr($type,"blob")) $metaType = 'blob';
        
        
        $column["type"] = $metaType;
        
    $columns[] = $column;
    }
        return $columns;
    } else {
        return false;
    }
    
    
    //$this->createTable('tester',$columns);
    
    /*
    $result = mysql_list_fields($go_info["server"]["db_name"],$table_name);
    $fields = mysql_num_fields ($result);
    $i = 0;
    $table = mysql_field_table ($result, $i);
    while ($i < $fields) {
        $name  = mysql_field_name  ($result, $i);
        $type  = mysql_field_type  ($result, $i);
        $len   = mysql_field_len   ($result, $i);
        $flags = mysql_field_flags ($result, $i);
        print_r($flags);
        
        $columns = array(name => $name,
                    type =>     "",
                    defaultValue =>  "",
                    isnull =>   1,
                    option =>   "");
        $returnvar[] = $columns;
        
        $i++;
    }
    */
    
    
   
   }
   
   function mapType($metaType,$typeValue) {
   global $go_api;
   $metaType = strtolower($metaType);
   switch ($metaType) {
   case 'int16':
        return 'smallint';
   break;
   case 'int32':
        return 'int';
   break;
   case 'int64':
        return 'bigint';
   break;
   case 'double':
        return 'double';
   break;
   case 'char':
        return 'char';
   break;
   case 'varchar':
        if($typeValue < 1) die("Datenbank Fehler: F�r diesen Datentyp ist eine L�ngenangabe notwendig.");
        return 'varchar('.$typeValue.')';
   break;
   case 'text':
        return 'text';
   break;
   case 'blob':
        return 'blob';
   break;
   }
   }
	
}

?>
