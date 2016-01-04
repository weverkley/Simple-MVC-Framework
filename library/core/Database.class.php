<?php
class Database {
    protected $_server, $_username, $_password, $_errorInfo;
    
    /**
    * Db name
    * 
    * @var mixed
    */
    public $dbName;
    
    /**
    * MySQL connection information
    *
    * @var resource
    */
    public $connection;
    
    /**
    * Result of last query
    *
    * @var resource
    */
    protected $_result;
    
    /**
     * Date and time
     *
     */
    const DATETIME = 'Y-m-d H:i:s';
    
    /**
     * Date
     *
     */
    const DATE = 'Y-m-d';
    
    /**
     * Constructor
     *
     * @param string $server MySQL server address
     * @param string $username Database username
     * @param string $password Database password
     * @param string $dbName Database name
     * @param boolean $persistant Is persistant connection
     * @param  boolean $connect_now Connect now
     * @return void
     */
    public function __construct($server = DB_HOST, $username = DB_USER, $password = DB_PASSWORD, $dbName = DB, $connect_now = true, $persistent = false, $pdoFlags = false){
        $this->_server   = $server;         // Host address
        $this->_username = $username;        // User
        $this->_password = $password;    // Password
        $this->dbName   = $dbName;        // Database         
       
        if ($connect_now){
            $this->connect($persistent, $pdoFlags);
        }
    }
   
    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct(){
        $this->close();
    }
    
    /**
     * Connect to the database
     *
     * @param boolean $persist Is persistant connection
     * @return boolean
     */
    public function connect($persistent = false, $pdoFlags = false){
        // if set to persistent connection
        if($persistent === true){
            $pdoFlags = ($pdoFlags !== false) ? array_merge($pdoFlags, PDO::ATTR_PERSISTENT) : PDO::ATTR_PERSISTENT;
        }

        $flags = $this->_ensurePdoFlags($pdoFlags);

        // Create new instance
        $dsn = "mysql:host={$this->_server}";
        try {
            // Add instance
            $this->connection = new Pdo($dsn, $this->_username, $this->_password, $flags);
        } catch (PDOException $e) {
            $this->_handleError($e,true);
            return false;
        }

        // select the db
        $this->selectDb($this->dbName);

       // if none of above processes work, return false
       return $this->connection;
    }    
    
    /**
    * Change the selected db
    * 
    * @param mixed $dbName
    */
    public function selectDb($dbName, $oneOff = false){
        if ($this->connection){
            // set the instance db name
            if($oneOff === false){
                $this->dbName = $dbName;
            }

            try
            {
                // use USE command to select db
                return $this->query("USE `{$dbName}`");
            } catch (PDOException $e){
                $this->_handleError($e);
            }
        }
        return false;
    }
    
    /**
     * Query the database
     *
     * @param string $queryStr SQL query string
     * @return resource MySQL result set
     */
    public function query($queryStr, $unbuffered=false){
      // set the result to false
      $result = false;
      try
      {
          // set buffer attribute
          $this->connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, !$unbuffered);

          $result = $this->connection->query($queryStr);
          $this->_result = $result;
      }catch(PDOException $e){
        $this->_handleError($e, true, "Query String: " . $queryStr);
      }
      return $result;
    }         
 
    /**
     * Update the database
     *
     * @param array $values 3D array of fields and values to be updated
     * @param string $table Table to update
     * @param string $where Where condition
     * @param string $limit Limit condition
     * @return boolean Result
     */
    public function update(array $values, $table, $where = false, $limit = false){
        if (count($values) < 0)
            return false;
            
        $fields = array();
        foreach($values as $field => $val)
            $fields[] = "`" . $field . "` = '" . $this->escapeString($val) . "'";

        $where = ($where) ? " WHERE " . $where : '';
        $limit = ($limit) ? " LIMIT " . $limit : '';

        if ($this->query("UPDATE " . $table . " SET " . implode($fields, ", ") . $where . $limit))
            return true;
        else
            return $this->_lastError();
    }  
    
    /**
     * Insert one new row
     *
     * @param array $values 3D array of fields and values to be inserted
     * @param string $table Table to insert
     * @return boolean Result
     */
    public function insert(array $values, $table){
        if (count($values) < 0)
            return false;
        
        foreach($values as $field => $val)
            $values[$field] = $this->escapeString($val);

        if ($this->query("INSERT INTO " . $table . " (`" . implode(array_keys($values), "`, `") . "`) VALUES ('" . implode($values, "', '") . "')"))
            return true;
        else
            return $this->_lastError();
    }   
   
    /**
     * Select
     *
     * @param mixed $fields Array or string of fields to retrieve
     * @param string $table Table to retrieve from
     * @param string $where Where condition
     * @param string $orderby Order by clause
     * @param string $limit Limit condition
     * @return array Array of rows
     */
    public function select($fields, $table, $where = false, $orderby = false, $order = false, $limit = false, $join = '', $groupby = ''){
        if (is_array($fields))
            $fields = "`" . implode($fields, "`, `") . "`";

        $orderby = ($orderby) ? " ORDER BY " . $orderby : '';
        $order = ($order) ? " ".$order." " : '';
        $where = ($where) ? " WHERE " . $where : '';
        $limit = ($limit) ? " LIMIT " . $limit : '';

        $result = $this->query("SELECT " . $fields . " FROM " . $table . " " .  $join . " " . $where . " " . $groupby . $orderby . $order . $limit);

        if ($this->numRows($result) > 0)
        {
            /*$rows = array();

            while ($r = $this->fetchAssoc())
                $rows[] = $r;*/
            return $this->fetchArray($result, 1);
            /*return $result->fetchAll(PDO::FETCH_ASSOC);*/
        } else
            return false;
    }
   
    /**
     * Selects one row
     *
     * @param mixed $fields Array or string of fields to retrieve
     * @param string $table Table to retrieve from
     * @param string $where Where condition
     * @param string $orderby Order by clause
     * @return array Row values
     */
    public function selectOne($fields, $table, $where = false, $orderby = false, $join = '', $groupby = ''){
        $result = $this->select($fields, $table, $where, $orderby, '1', $join, $groupby);

        return $result[0];
    }    
   
    /**
     * Selects one value from one row
     *
     * @param mixed $field Name of field to retrieve
     * @param string $table Table to retrieve from
     * @param string $where Where condition
     * @param string $orderby Order by clause
     * @return array Field value
     */
    public function selectOneValue($field, $table, $where = false, $orderby = false, $join = '', $groupby = '', $dbname= false){
        if($dbname){
            $this->selectDb($dbname, true);
        }
         
        $result = $this->selectOne($field, $table, $where, $orderby, $join, $groupby);

        $this->selectDb($dbname, false);
        
        return $result[$field];
    }
    
    /**
     * Delete rows
     *
     * @param string $table Table to delete from
     * @param string $where Where condition
     * @param string $limit Limit condition
     * @return boolean Result
     */
    public function delete($table, $where = false, $limit = 1){
        $where = ($where) ? "WHERE {$where}" : "";
        $limit = ($limit) ? "LIMIT {$limit}" : "";

        if ($this->query("DELETE FROM `{$table}` {$where} {$limit}"))
            return true;
        else
            return $this->_lastError();
    }
    
    /**
     * Fetch results by associative array
     */
    public function fetchArray($result = false, $resultType = 3){
        $this->_ensureResult($result);
        switch ($resultType) {
            case 1:
                // by field names only as array
                return $result->fetchAll(PDO::FETCH_ASSOC);
            case 2:
                // by field position only as array
                return $result->fetchAll(PDO::FETCH_NUM);
            case 3:
                // by both field name/position as array
                return $result->fetchAll();
            case 4:
                // by field names as object
                return $result->fetchAll(PDO::FETCH_OBJ);   
        }
    }   
    
    /**
     * Fetch results by associative array
     *
     * @param mixed $result Select query or MySQL result
     * @return array Row
     */
    public function fetchAssoc($result = false){
        $this->_ensureResult($result);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
   
    /**
     * Fetch results by enumerated array
     *
     * @param mixed $query Select query or MySQL result
     * @return array Row
     */
    public function fetchRow($result = false){
        $this->_ensureResult($result);
        return $result->fetchAll(PDO::FETCH_NUM);
    }    
    
    /**
    * Fetch result as field object
    * 
    * @param mixed $result
    * @return object
    */
    public function fetchField($result = false, $offset = 0){
        $this->_ensureResult($result);
        // Calculate max_length of all field in the resultset
        $rows = $result->fetchAll(PDO::FETCH_NUM);
        $counter = count($rows);
        $maxLength = 0;
        for ($i = 0; $i < $counter; $i++) {
            $len = strlen($rows[$i][$offset]);
            if ($len > $maxLength) {
                $maxLength = $len;
            }
        }
        return $this->_getAllColumnData($data, false, $maxLength);
     }      
    
    /**
     * Fetch result as object
     * 
     * @param mixed $result Select query or MySQL result  
     * @return object
     */
    public function fetchObject($result = false){
        $this->_ensureResult($result); 
        return $result->fetchAll(PDO::FETCH_OBJ);
     }
    
    /**
     * Fetch one row
     *
     * @param mixed $result Select query or MySQL result
     * @return array
     */
    public function fetchOne($result = false){
        $this->_ensureResult($result);
        list($ret) = $this->fetchRow($result);
        return $ret;
    }
    
    /**
    * Get the flags associated with the specified field in a result
    * 
    * The following flags are reported, if your version of MySQL is current enough to support them: "not_null", "primary_key", "unique_key", "multiple_key", "blob", "unsigned", "zerofill", "binary", "enum", "auto_increment" and "timestamp". 
    * 
    * @param mixed $result  Select query or MySQL result
    * @param mixed $field_offset The numerical field offset. The field_offset  starts at 0. If field_offset  does not exist, an error of level E_WARNING is also issued.
    * @return string Returns a string of flags associated with the result, or FALSE on failure. 
    * 
    */
    public function fieldFlags($result = false, $field_offset = 0){
        $this->_ensureResult($result); 
        $data = $result->getColumnMeta($field_offset);
        return $this->_getAllColumnData($data, true); 
    }
    
    /**
    * Returns the length of the specified field
    * 
    * @param mixed $result Select query or MySQL result
    * @param mixed $field_offset The numerical field offset. The field_offset  starts at 0. If field_offset  does not exist, an error of level E_WARNING is also issued.
    * @return int  The length of the specified field index on success, or FALSE on failure. 
    */
    public function fieldLen($result = false, $field_offset = 0){
        $this->_ensureResult($result);
        // Make sure it is an array
        if (!is_array($result)) {
            $result = (array) $result;
        }
        $set = array_map('strlen', $result);
        return $set[$field_offset];
    }
    
    /**
     * Fetch a field name in a result
     *
     * @param mixed $query Select query or MySQL result
     * @param int $offset Field offset
     * @return string Field name
     */
    public function fieldName($result = false, $field_offset = 0){
        $this->_ensureResult($result);
       $data = $result->getColumnMeta($field_offset);
       return $this->_mapPdoType($data['name']);
    }

    /**
     * Fetch all field names in a result
     *
     * @param mixed $result Select query or MySQL result
     * @return array Field names
     */
    public function fieldNameArray($result = false){
        $names = array();

        $field = $this->numFields($result);

        for ( $i = 0; $i < $field; $i++ ){
            $names[] = $this->fieldName($result, $i);
        }

        return $names;
    }
    
    /**
    * Fetch all field names in a result  
    * 
    * @param mixed $table
    * @param mixed $incTableName = false
    * @param mixed $backtick = '``'
    */
    public function fieldNameArrayByTable($table, $incTableName=false, $backtick='`'){
        $names = array();
        $query = "SELECT * FROM `{$table}` LIMIT 1";

        $result = $this->query($query);
        $field = $this->numFields($result);
        
        if($backtick===false){
            $backtick = '';
        }
        
        $table = ($incTableName) ? $backtick.$table.$backtick.'.' : '';
         
        for( $i = 0; $i < $field; $i++ ){
            $names[] = $table.$backtick.$this->fieldName($result, $i).$backtick;
        }
        return $names;
    }
    
    /**
    * Get name of the table the specified field is in
    * 
    * @param mixed $result  Select query or MySQL result
    * @param mixed $offset  The numerical field offset. The field_offset  starts at 0. If field_offset  does not exist, an error of level E_WARNING is also issued.
    * @return string  The name of the table on success. 
    */
    public function fieldTable($result = false, $field_offset = 0){
      $this->_ensureResult($result); 
      $data = $result->getColumnMeta($field_offset);
      return $data['table'];
    }
    
    /**
    * Get the type of the specified field in a result
    * 
    * @param mixed $result   Select query or MySQL result
    * @param mixed $field_offset   The numerical field offset. The field_offset  starts at 0. If field_offset  does not exist, an error of level E_WARNING is also issued.
    * @return string  The returned field type will be one of "int", "real", "string", "blob", and others as detailed in the http://dev.mysql.com/doc/
    */
    public function fieldType($result = false, $field_offset = 0){
       $this->_ensureResult($result);
       $data = $result->getColumnMeta($field_offset);
       return $this->_mapPdoType($data['native_type']);
    }
    
    /**
    * Gets the fields list from selected table and db
    * 
    * @param mixed $tableName The table name
    * @param mixed $dbName The DB name
    * @return resource
    */
    public function fieldsList($tableName, $dbName = false){
        if($dbName == false){
            $dbName = $this->dbName;
        }
            
        return $this->getFullColumnsInfo($tableName);
    }
   
    /**
     * Add escape characters for importing data
     *
     * @param string $str String to parse
     * @return string
     */
    public function escapeString($string){
        try {
            $string = $this->connection->quote($string);
            return substr($string, 1, -1);
        } catch (PDOException $e) {
            $this->_loadError($link, $e);
        }
        
        return false;
    }
   
    /**
     * Count number of rows in a result
     *
     * @param mixed $result Select query or MySQL result
     * @return int Number of rows
     */
    public function numRows($result){
        $this->_ensureResult($result);
        if (is_array($result)) {
            return count($result);
        }
        
        // Hard clone (cloning PDOStatements doesn't work)
        $query = $result->queryString;
        $cloned = $this->query($query);
        $data = $cloned->fetchAll();
        return count($data);
    }
   
    /**
     * Count number of fields in a result
     *
     * @param mixed $result Select query or MySQL result
     * @return int Number of fields
     */
    public function numFields($result){
        $this->_ensureResult($result);
        if (is_array($result)) {
            return count($result);
        }

        $data = $result->fetch(PDO::FETCH_NUM);
        return count($data);
    }
    
    /**
     * Get last inserted id of the last query
     */
    public function insertId(){
        return (int) $this->connection->lastInsertId();
    }
    
    /**
     * Get number of affected rows of the last query
     */
    public function affectedRows(){
        $result = $this->_ensureResult(false);
        return $result->rowCount();
    }
   
    /**
    * Gets the mysql client info in period (.) delimited
    * 
    * @return String: Version information from the database 
    */
    public function getClientInfo(){
        return $this->connection->getAttribute(PDO::ATTR_CLIENT_VERSION);
    }
    
    /**
    *   Gets the mysql client info in period (.) delimited
    */
    public function getServerInfo(){
        return $this->connection->getAttribute(PDO::ATTR_SERVER_VERSION);
    }
    
    /**
    * Get status information from SHOW STATUS in an associative array
    */
    public function getStatus($which="%"){
        $result = $this->query( "SHOW STATUS LIKE '{$which}'" );
        $status = array();
        while ( $row = $this->fetchObject( $result ) ) {
            $status[$row->Variable_name] = $row->Value;
        }
        return $status;
    }
    
    /**
    * Gets the total rows count of a table
    */
    public function getTableRows($table){
        $result = $this->query("SELECT COUNT(*) FROM {$table}");
        $row = $this->fetchOne($result);
        return $row;
    }    
    
    /**
     * Ping the server and try to reconnect if it there is no connection
     */
    public function ping(){
        try {
            // try to query anything
            $this->connection->query('SELECT 1');
        } catch (PDOException $e) {
            try {
                // Reconnect
                $set = $this->connect();
            } catch (PDOException $e) {
                $this->_loadError($e,false);
                return false;
            }

            // Select db if any
            if (isset($this->dbName)) {
                $set = $this->selectDb($this->dbName);
                
                if (!$set) {
                    return false;
                }
            }
        }
        
        return true;
    }        
   
    /**
     * Free the query result resoruce
     * http://www.php.net/manual/en/function.mysql-free-result.php
     */
    public function freeResult(&$result){
        if (is_array($result)) {
            $result = false;
            return true;
        }

        if (get_class($result) != 'PDOStatement') {
            return false;
        }

        return $result->closeCursor();
    }

    /**
     * Close the connection
     *
     * @return boolean
     */
    public function close(){
        if(isset($this->connection)){
            $this->connection = null;
            unset($this->connection);
            return true;
        }
        return false;
    }

    /**
    * Returns an array containing default values for each field in the table (key => value array).
    * Be aware that some fields (auto_increment, CURRENT_TIMESTAMP) may need to be set on INSERT. 
    * auto_increment fields will be set to 0  
    * CURRENT_TIMESTAMP fields will be set to the PHP current timestamp based on the time the function
    * call was made
    * 
    * 
    * @param mixed $cTable
    * @return string
    */
    public function getDefaultValues($table, $dbName = false){
      // Set up blank array
      $returnValue = array();
      // If $table was not passed in, return empty array
      if (empty($table)){
        return $returnValue;
      }

      if(empty($dbName)){
        $dbName = $this->dbName;
      }
      
      // Get the fields
      $result = $this->query("DESCRIBE `$dbName`.`{$table}`");
      $nbRows = $this->numRows($result);
      if (count($nbRows)==0) 
        return array();

      // Scan through each field and assign defaults:
      for($i=0; $i < $nbRows; $i++) {
        $val = $this->fetchAssoc($result);
        if ($val['Default'] AND $val['Default']=='CURRENT_TIMESTAMP') {
          $returnValue[$val['Field']] = date('Y-m-d H:i:s');      
        }
        if ($val['Extra'] AND $val['Extra']=='auto_increment') {
          $returnValue[$val['Field']] = 0;      
        }
        if ($val['Default'] AND $val['Default']!='CURRENT_TIMESTAMP') {
          $returnValue[$val['Field']] = $val['Default'];
        } else {
          if ($val['Null']=='YES') {
            $returnValue[$val['Field']] = NULL;
          } else {
            $type = $val['Type'];
            if (strpos($type,'(')!==false)
              $type = substr($type,0,strpos($type,'('));
            if (in_array($type,array('varchar','text','char','tinytext','mediumtext','longtext','set',
                                      'binary','varbinary','tinyblob','blob','mediumblob','longblob'))) {
              $returnValue[$val['Field']] = '';
            } elseif ($type=='datetime') {
              $returnValue[$val['Field']] = '0000-00-00 00:00:00';
            } elseif ($type=='date') {
              $returnValue[$val['Field']] = '0000-00-00';
            } elseif ($type=='time') {
              $returnValue[$val['Field']] = '00:00:00';
            } elseif ($type=='year') {
              $returnValue[$val['Field']] = '0000';
            } elseif ($type=='timestamp') {
              $returnValue[$val['Field']] = date('Y-m-d H:i:s');
            } elseif ($type=='enum') {
              $returnValue[$val['Field']] = 1;
            } else {  // Numeric:
              $returnValue[$val['Field']] = 0;
            }
          }  // end NOT NULL
        }  // end default check
      }  // end foreach loop 
      return $returnValue; 
    }
    
    /**
    * Gets the the column/field information from the specified table
    * 
    * @param mixed $table
    * @return array
    */
    public function describeTable($table, $dbName = false){
        if(empty($dbName)){
            $dbName = $this->dbName;
        }
        $result = $this->query("DESCRIBE `{$dbName}`.`{$table}`");
        $data = array();
        
        while($row = $this->fetchAssoc($result)){
            $data[$row['Field']] = $row;
        }
            
        return $data;
    }
    
    /**
    * Gets full column info from a table
    */
    public function getFullColumnsInfo($table, $dbName = false){

        if(empty($dbName)){
            $dbName = $this->dbName;
        }
        $result = $this->query("SHOW FULL COLUMNS FROM `{$dbName}`.`{$table}`");
        $data = array();
        
        while($row = $this->fetchAssoc($result)){
            $data[$row['Field']] = $row;
        }
            
        return $data;        
    }
    
    /**
    * Check if the datatable exists in the specified database
    * 
    * @param mixed $table The table name
    * @param mixed $database (Optional) The database name
    */
    public function tableExist($table, $database=false){
        if(empty($database)){
           $result = $this->query("SELECT DATABASE()");
           $row = $this->fetchArray($result);
           $database = $row[0];
        }
        $result = $this->query("SELECT `table_name` FROM `information_schema`.`tables` WHERE `table_schema` = '{$database}' AND `table_name` = '{$table}'");
        $numRows = $this->numRows($result);
        return $numRows > 0;
    }
    /**
     * Get all field data
     *
     * @param   array   $data
     * @param   boolean $simple
     * @param   int     $maxLength
     *
     * @return  object
     */
    protected function _getAllColumnData($data, $simple = false, $maxLength = 0){
        $type = $this->_mapPdoType($data['native_type']);

        // for zerofill/unsigned, we do a describe
        $query = $this->query("DESCRIBE `{$data['table']}` `{$data['name']}`");
        $typeInner = $this->fetchAssoc($query);

        // Flags
        if ($simple === true) {
            $string = in_array('not_null', $data['flags']) ? 'not_null' : 'null';
            $string .= in_array('primary_key', $data['flags']) ? ' primary_key' : '';
            $string .= in_array('unique_key', $data['flags']) ? ' unique_key' : '';
            $string .= in_array('multiple_key', $data['flags']) ? ' multiple_key' : '';

            $unSigned = strpos($typeInner['Type'], 'unsigned');
            if ($unSigned !== false) {
                $string .= ' unsigned';
            } else {
                $string .= strpos($typeInner['Type'], 'signed') !== false ? ' signed' : '';
            }

            $string .= strpos($typeInner['Type'], 'zerofill') !== false ? ' zerofill' : '';
            $string .= isset($typeInner['Extra']) ? ' ' . $typeInner['Extra'] : '';
            return $string;
        }

        $return = array (
            'name'          => $data['name'],
            'table'         => $data['table'],
            'def'           => $typeInner['Default'],
            'max_length'    => $maxLength,
            'not_null'      => in_array('not_null', $data['flags']) ? 1 : 0,
            'primary_key'   => in_array('primary_key', $data['flags']) ? 1 : 0,
            'multiple_key'  => in_array('multiple_key', $data['flags']) ? 1 : 0,
            'unique_key'    => in_array('unique_key', $data['flags']) ? 1 : 0,
            'numeric'       => ($type == 'int') ? 1: 0,
            'blob'          => ($type == 'blob') ? 1: 0,
            'type'          => $type,
            'unsigned'      => strpos($typeInner['Type'], 'unsigned') !== false ? 1 : 0,
            'zerofill'      => strpos($typeInner['Type'], 'zerofill') !== false ? 1 : 0,
        );
        
        return (object) $return;
    }
     /**
     * Map PDO::TYPE_* to MySQL Type
     *
     * @param int   $type   PDO::TYPE_*
     *
     * @return string
     */
    protected function _mapPdoType($type){
        // Types enum defined @ http://lxr.php.net/xref/PHP_5_4/ext/mysqlnd/mysqlnd_enum_n_def.h#271
        $type = strtolower($type);
        switch ($type) {
            case 'tiny':
            case 'short':
            case 'long':
            case 'longlong';
            case 'int24':
                return 'int';
            case 'null':
                return null;
            case 'varchar':
            case 'var_string':
            case 'string':
                return 'string';
            case 'blob':
            case 'tiny_blob':
            case 'long_blob':
                return 'blob';
            default:
                return $type;
        }
    }
   
    /**
     * Determine the data type of a query
     *
     * @param mixed $result Query string or MySQL result set
     * @return void
     */
    protected function _ensureResult(&$result){
        if ($result == false){
            $result = $this->_result;
        } else {
            if (gettype($result) !== 'resource' && is_string($result)){
                $result = $this->query($result);
            }
        }
    }

    /**
    * Ensure the PDO flags paramaters are correctly formed
    */
    protected function _ensurePdoFlags($flags){
        if ($flags == false || empty($flags)) {
            return array();
        }
        
        // Array it
        if (!is_array($flags)) {
            $flags = array($flags);
        }

        // refer to https://github.com/AzizSaleh/mysql/blob/master/MySQL.php
        $pdoParams = array();
        foreach ($flags as $flag) {
            switch ($flag)
            {
                // CLIENT_FOUND_ROWS (found instead of affected rows)
                case 2:
                    $params = array(PDO::MYSQL_ATTR_FOUND_ROWS => true);
                    break;
                // CLIENT_COMPRESS (can use compression protocol)
                case 32:
                    $params = array(PDO::MYSQL_ATTR_COMPRESS => true);
                    break;
                // CLIENT_LOCAL_FILES (can use load data local)
                case 128:
                    $params = array(PDO::MYSQL_ATTR_LOCAL_INFILE => true);
                    break;
                // CLIENT_IGNORE_SPACE (ignore spaces before '(')
                case 256:
                    $params = array(PDO::MYSQL_ATTR_IGNORE_SPACE => true);
                    break;
                // Persistent connection
                case 12:
                    $params = array(PDO::ATTR_PERSISTENT => true);
                    break;
            }
            
            $pdoParams[] = $params;
        }

         return $pdoParams;
    }

    protected function _handleError($e, $throw = true, $extraInfo = false){
        // Reset errors
        if ($e === false || is_null($e)) {
            $this->_errorInfo = array('error'=>"", 'errno'=>0);
            return;
        }
        // Set error
        $this->_errorInfo = array('error'=>$e->getMessage(), 'errno'=>$e->getCode());

        if($throw){
            $s = "<br />Error Code:" . $this->_errorInfo['errno'] . "<br /> Description: " . $this->_errorInfo['error'] . "<br />";
            if(!empty($extraInfo)){
                $s .= $extraInfo ."<br />";
            }
            trigger_error($s, E_USER_ERROR);
        }
    }


    /**
     * Get the error description from the last query
     *
     * @return string
     */
    protected function _lastError(){
        $error = '';
        
        if ($this->connection){
            $error = $this->connection->errorInfo();
        }
     
     return $error;
    }
    
    /**
     * Get the last error number
     */
    protected function _lastErrNo(){
        $error = '';
        
        if ($this->connection){
            $error = $this->connection->errorCode();
        }
     
     return $error;
    }    
}