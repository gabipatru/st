<?php

/*
 * This class is used for nested transactions
 */

class NestedPDO extends PDO {
    // Database drivers that support SAVEPOINTs.
    protected $savepointTransactions = ["pgsql", "mysql"];

    // The current transaction level.
    protected $transLevel = 0;

    protected function nestable() {
        return in_array($this->getAttribute(PDO::ATTR_DRIVER_NAME), $this->savepointTransactions);
    }

    public function beginTransaction() {
        if($this->transLevel == 0 || !$this->nestable()) {
            parent::beginTransaction();
        } else {
            $this->exec("SAVEPOINT LEVEL{$this->transLevel}");
        }

        $this->transLevel++;
    }

    public function commit() {
        $this->transLevel--;

        if($this->transLevel == 0 || !$this->nestable()) {
            parent::commit();
        } else {
            $this->exec("RELEASE SAVEPOINT LEVEL{$this->transLevel}");
        }
    }

    public function rollBack() {
        $this->transLevel--;

        if($this->transLevel == 0 || !$this->nestable()) {
            parent::rollBack();
        } else {
            $this->exec("ROLLBACK TO SAVEPOINT LEVEL{$this->transLevel}");
        }
    }
    
    public function getTransactionLevel() {
        return $this->transLevel;
    }
}

/*
 * This version of the class works with prepared statements.
 * 
*/

class db {
    
    use Singleton;
    
    private $oPDO = null;
    private $bDebug = false;
    
    /*
     * This function sets the debug mode to a given level
     */
    public function setDebug($bDebug) {
        $this->bDebug = $bDebug;
    }
    
    public function getDebug() {
        return $this->bDebug;
    }
    
    /*
     * This function connects to the database
     */
    public function connect($host = DB_HOST, $database = DB_DATABASE, $user = DB_USER, $pass = DB_PASS) {
        $this->oPDO = new NestedPDO('mysql:host='.$host.';dbname='.$database, $user, $pass);
        
        // set error reporting if debug is active
        if (DEBUGGER_AGENT) {
            $this->oPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        }
    }
    
    /*
     * Prepares a statement and executes it, returns the executed statement for fetching
     */
    public function query($sql, $mParams = null) {
        if (!$sql && DEBUGGER_AGENT) {
            trigger_error('No query sent!');
        }
        
        // if class debug is active, print the query and parameters
        if ($this->bDebug === true ) {
            trigger_error($sql);
            echo'<pre>';print_r($mParams);echo'</pre>';
        }
        
        // prepare the statement
        $oStmt = $this->oPDO->prepare($sql);
        
        // execute the statement
        $oStmt->execute($mParams);
        if ($oStmt->errorCode() == '00000') {
            return $oStmt;
        }
        else {
            if (DEBUGGER_AGENT) {
                trigger_error($sql);
                echo'<pre>';print_r($mParams);echo'</pre>';
                echo '<pre>';print_r($oStmt->errorInfo());echo '</pre>';
            }
            return false;
        }
        
        return $oStmt;
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
    public function nextId($sTableName) {
        $result = $this->query("SHOW TABLE STATUS LIKE '$sTableName'");
        $row = $this->fetchAssoc($result);
        if ($row['Auto_increment']) {
            return $row['Auto_increment'];
        }
        return false;
    }
    
    /*
     * Get the numbers of rows in a executed statement
     */
    public function rowCount($oStmt) {
        return $oStmt->rowCount();
    }
    
    /*
     * Get the id of the last row inserted in the database
     */
    public function lastInsertId() {
        return $this->oPDO->lastInsertId();
    }
    
    /*
     * Fetch the rows associatively
     */
    public function fetchAssoc(&$oStmt) {
        if ($oStmt) {
            return $oStmt->fetch(PDO::FETCH_ASSOC);
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
        foreach ($filters as $field => $value) {
            if (is_scalar($value)) {
                $whereCondition .= " AND `".$sPrefixStr.$field."` = ? ";
                $aParams[] = $value;
            }
            elseif (is_null($value) || empty($value) || $value === '') {
                $whereCondition .= " AND `".$sPrefixStr.$field."` IS NULL ";
            }
            elseif (is_array($value)) {
                $markers = $value;
                $aParams = array_merge($aParams, $value);
                foreach($markers as $key => $value) {
                    $markers[$key] = '?';
                }
                $whereCondition .= " AND `".$sPrefixStr.$field."` IN (".implode(',', $markers).") ";
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
        foreach ($options['search_fields'] as $field) {
            $aSearch[] = "`$field` LIKE ?";
            $aParams[] = '%' . $options['search'] . '%';
        }
        
        $searchSql .= '(' . implode(' OR ', $aSearch) . ')';

        return array($searchSql, $aParams);
    }
    
    /*
     * TRANSACTION FUNCTION: Begin a transaction
     */
    public function startTransaction() {
        $this->oPDO->beginTransaction();
    }
    
    /*
     * TRANSACTION FUNCTION: Commit a transaction
     */
    public function commitTransaction() {
        $this->oPDO->commit();
    }
    
    /*
     * TRANSACTION FUNCTION: Rollback a transaction
     */
    public function rollbackTransaction() {
        $this->oPDO->rollBack();
    }
    
    /*
     * TRANSACTION FUNCTION: transaction level - how many transactions are nested
     */
    public function transactionLevel() {
        return $this->oPDO->getTransactionLevel();
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
        
        $sql = "SELECT name FROM _locks WHERE name = ? FOR UPDATE";
        $this->query($sql, array($sLockName));
        return true;
    }
}