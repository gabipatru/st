<?php
/*
 * This version of the class works with prepared statements and nested transactions
 * 
*/

class db {
    
    use Singleton;

    // The current transaction level.
    protected $transLevel = 0;
    
    private $oPDO = null;
    private $bDebug = false;
    private $iQueriesNo = 0;
    private $aQueriesRun = [];
    
    /**
     * These functions are setters ang getters for the debug variable
     */
    public function setDebug($bDebug) {
        $this->bDebug = $bDebug;
    }
    
    public function getDebug() {
        return $this->bDebug;
    }
    
    /**
     * These functions operate with the number of queries run by a script
     */
    private function setQueriesNo(int $nr) {
        $this->iQueriesNo = $nr;
    }
    
    private function incrementQueriesNo() {
        $this->iQueriesNo = $this->iQueriesNo + 1;
    }
    
    public function getQueriesNo(): int
    {
        return $this->iQueriesNo;
    }
    
    /*
     * These functions operate with the run queries array
     */
    private function addRunQuery(string $sql) 
    {
        $this->aQueriesRun[] = $sql;
    }
    
    public function getRunQueries(): array
    {
        return $this->aQueriesRun;
    }
    
    /*
     * This function connects to the database
     */
    public function connect($host = DB_HOST, $database = DB_DATABASE, $user = DB_USER, $pass = DB_PASS) {
        try {
            $this->oPDO = pg_connect("host=$host user=$user password=$pass dbname=$database");
        }
        catch (Exception $e) {
            throw new Exception('Database connection error');
        }
    }

    /*
     * Disconnect from the database
     */
    public function disconnect()
    {
        pg_close($this->oPDO);
    }
    
    /*
     * Prepares a statement and executes it, returns the executed statement for fetching
     */
    public function query($sql, $mParams = []) {
        if (!$sql && DEBUGGER_AGENT) {
            trigger_error('No query sent!');
        }
        
        // if class debug is active, print the query and parameters
        if ($this->bDebug === true ) {
            echo $sql;
            echo'<pre>';print_r($mParams);echo'</pre>';
        }

        try {
            $result = pg_query_params($this->oPDO, $sql, $mParams);
        }
        catch (Exception $e) {
            $result = null;
        }
        
        $this->incrementQueriesNo();
        $this->addRunQuery($sql);
        
        if ($result) {
            return $result;
        }
        else {
            if (DEBUGGER_AGENT) {
                trigger_error(print_r($mParams, 1), E_USER_WARNING);
                trigger_error($sql, E_USER_WARNING);
            }
            return false;
        }
    }
    
    /*
     * Special function for insert that returns the last insert id
     */
    public function queryInsert($sql, $mParams = null) {
        $this->query($sql, $mParams);
        return $this->lastInsertId();
    }
    
    /*
     * Special function for select that returns an fetches associative array
     */
    public function querySelect($sql, $mParams = null) {
        $oStmt = $this->query($sql, $mParams);
        return $this->fetchAssoc($oStmt);
    }
    
    /*
     * Get the next insert id
     */
    public function nextId($sTableName, $iIdColumn) {
        $result = $this->query("SELECT nextval(pg_get_serial_sequence('$sTableName', '$iIdColumn'))"
                                    ." AS next_id");
        $row = $this->fetchAssoc($result);
        if ($row['next_id']) {
            return $row['next_id'];
        }
        return false;
    }
    
    /*
     * Get the numbers of rows in a executed statement
     */
    public function rowCount($result) {
        return pg_num_rows($result);
    }

    /**
     * Get the  number of affected rows
     */
    public function affectedRows($result)
    {
        return pg_affected_rows($result);
    }
    
    /*
     * Get the id of the last row inserted in the database
     */
    public function lastInsertId($sTableName, $iIdColumn) {
        $result = $this->query("SELECT currval(pg_get_serial_sequence('$sTableName', '$iIdColumn'))"
            ." AS next_id");
        $row = $this->fetchAssoc($result);
        if ($row['next_id']) {
            return $row['next_id'];
        }
        return false;
    }
    
    /*
     * Fetch the rows associatively
     */
    public function fetchAssoc(&$result) {
        if ($result) {
            return pg_fetch_assoc($result);
        }
        return false;
    }

    /*
     * Compose a set of sql condition and an array of params based on an input array
     */
    public function filters($filters, $prefix = '') {
        $whereCondition = " 1=1";
        $aParams = array();
        $sPrefixStr = '';
        if ($prefix) {
            $sPrefixStr = '.'.$prefix;
        }

        $i = 1;
        foreach ($filters as $field => $value) {
            if (is_scalar($value)) {
                $whereCondition .= " AND ".$sPrefixStr.$field." = $".$i;
                $aParams[] = $value;
                $i++;
            }
            elseif (is_null($value) || empty($value) || $value === '') {
                $whereCondition .= " AND ".$sPrefixStr.$field." IS NULL ";
            }
            elseif (is_array($value)) {
                $markers = $value;
                $aParams = array_merge($aParams, $value);
                foreach($markers as $key => $value) {
                    $markers[$key] = '$'.$i;
                    $i++;
                }
                $whereCondition .= " AND ".$sPrefixStr.$field." IN (".implode(',', $markers).") ";
            }
        }
        return array($whereCondition, $aParams);
    }
    
    /*
     * Compose the search part of a query based on some fields provided in the params
     */
    public function searchFilter($options) {
        // sanity check
        if (!is_array($options) || empty($options['search']) || !is_array($options['search_fields'])) {
            return array('', array());
        }
        if (count($options['search_fields']) == 0) {
            return array('', array());
        }
        
        $searchSql = ' AND';
        $aSearch = array();
        $aParams = array();

        $i = 1;
        foreach ($options['search_fields'] as $field) {
            $aSearch[] = "$field LIKE $".$i;
            $aParams[] = '%' . $options['search'] . '%';
            $i++;
        }
        
        $searchSql .= '(' . implode(' OR ', $aSearch) . ')';

        return array($searchSql, $aParams);
    }
    
    /*
     * TRANSACTION FUNCTION: Begin a transaction
     */
    public function startTransaction() {
        if ($this->transLevel == 0) {
            $this->query("BEGIN");
        }
        else {
            $this->query("SAVEPOINT ". $this->getSavepointName());
        }

        $this->transLevel++;
    }
    
    /*
     * TRANSACTION FUNCTION: Commit a transaction
     */
    public function commitTransaction() {
        if ($this->transLevel > 0) {
            $this->transLevel--;
        }

        $this->query("COMMIT");
    }
    
    /*
     * TRANSACTION FUNCTION: Rollback a transaction
     */
    public function rollbackTransaction() {
        if ($this->transLevel > 0) {
            $this->transLevel--;
        }

        if ($this->transLevel == 0) {
            $this->query("ROLLBACK");
        }
        else {
            $this->query("ROLLBACK TO SAVEPOINT ".$this->getSavepointName());
        }
    }
    
    /*
     * TRANSACTION FUNCTION: transaction level - how many transactions are nested
     */
    public function transactionLevel() {
        return $this->transLevel;
    }
    
    /*
     * TRANSACTION FUNCTION: transaction lock - lock a transaction.
     * Must have a table called _locks with id and name columns
     * 
     */
    public function lock_transaction($sLockName) {
        if (!$sLockName) {
            return false;
        }
        
        // check if the lock exists
        if (! $this->checkLock($sLockName)) {
            $this->addLock($sLockName);
        }
        
        $sql = "SELECT name FROM _locks WHERE name = $1 FOR UPDATE";
        $this->query($sql, [$sLockName]);
        return true;
    }
    
    /*
     * Check if a transaction lock exists
     */
    private function checkLock(string $sLockName) :bool
    {
        $sql = "SELECT name FROM _locks WHERE name = $1";
        $res = $this->query($sql, [$sLockName]);
        if ($this->rowCount($res) > 0) {
            return true;
        }
        
        return false;
    }
    
    /*
     * Add a transaction lock
     */
    private function addLock(string $sLockName) 
    {
        $sql = "INSERT INTO _locks (name) VALUES ('$sLockName')";
        $this->query($sql);
    }

    private function getSavepointName()
    {
        switch ($this->transLevel) {
            case 1:
                return 'A';
            case 2:
                return 'B';
            case 3:
                return 'C';
            case 4:
                return 'D';
            case 5:
                return 'E';
            case 6:
                return 'F';
            case 7:
                return 'G';
            case 8:
                return 'H';
            case 9:
                return 'I';
            case 10:
                return 'J';
            default:
                return '';
        }
    }
}