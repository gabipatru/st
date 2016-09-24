<?php

/*
 * This class is used for nested transactions
 */

class NestedPDO extends PDO {
	// Database drivers that support SAVEPOINTs.
	protected static $savepointTransactions = array("pgsql", "mysql");

	// The current transaction level.
	protected $transLevel = 0;

	protected function nestable() {
		return in_array($this->getAttribute(PDO::ATTR_DRIVER_NAME), self::$savepointTransactions);
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
	static private $oPDO = null;
	static private $bDebug = false;
	
	/*
	 * This function sets the debug mode to a given level
	 */
	public static function setDebug($bDebug) {
		self::$bDebug = $bDebug;
	}
	
	/*
	 * This function connects to the database
	 */
	public static function connect($host = DB_HOST, $database = DB_DATABASE, $user = DB_USER, $pass = DB_PASS) {
		self::$oPDO = new NestedPDO('mysql:host='.$host.';dbname='.$database, $user, $pass);
		
		// set error reporting if debug is active
		if (DEBUGGER_AGENT) {
			self::$oPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		}
	}
	
	/*
	 * Prepares a statement and executes it, returns the executed statement for fetching
	 */
	public static function query($sql, $mParams = null) {
		if (!$sql && DEBUGGER_AGENT) {
			trigger_error('No query sent!');
		}
		
		// if class debug is active, print the query and parameters
		if (self::$bDebug === true ) {
			trigger_error($sql);
			echo'<pre>';print_r($mParams);echo'</pre>';
		}
		
		// prepare the statement
		$oStmt = self::$oPDO->prepare($sql);
		
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
	public static function queryInsert($sql, $mParams = null) {
		self::query($sql, $mParams);
		return self::lastInsertId();
	}
	
	/*
	 * Special function for select that returns an fetche associative array
	 */
	public static function querySelect($sql, $mParams = null) {
		$oStmt = self::query($sql, $mParams);
		return self::fetchAssoc($oStmt);
	}
	
	/*
	 * Get the next insert id
	 */
	public static function nextId($sTableName) {
		$result = self::query("SHOW TABLE STATUS LIKE '$sTableName'");
		$row = db::fetchAssoc($result);
		if ($row['Auto_increment']) {
			return $row['Auto_increment'];
		}
		return false;
	}
	
	/*
	 * Get the numbers of rows in a executed statement
	 */
	public static function rowCount($oStmt) {
		return $oStmt->rowCount();
	}
	
	/*
	 * Get the id of the last row inserted in the database
	 */
	public static function lastInsertId() {
		return self::$oPDO->lastInsertId();
	}
	
	/*
	 * Fetch the rows associatively
	 */
	public static function fetchAssoc(&$oStmt) {
		if ($oStmt) {
			return $oStmt->fetch(PDO::FETCH_ASSOC);
		}
		return false;
	}

	/*
	 * Compose a set of sql condition and an array of params based on an input array
	 */
	static function filters($filters, $prefix = '') {
		$whereCondition = " 1=1";
		$aParams = array();
		$sPrefixStr = '';
		if ($prefix) {
			$sPrefixStr = '.'.$prefix;
		}
		foreach ($filters as $field => $value) {
			if (is_scalar($value)) {
				$whereCondition .= " AND ".$sPrefixStr.$field." = ? ";
				$aParams[] = $value;
			}
			elseif (is_null($value) || empty($value) || $value === '') {
				$whereCondition .= " AND ".$sPrefixStr.$field." IS NULL ";
			}
			elseif (is_array($value)) {
				$markers = $value;
				$aParams = $value;
				foreach($markers as $key => $value) {
					$markers[$key] = '?';
				}
				$whereCondition .= " AND ".$sPrefixStr.$field." IN (".implode(',', $markers).") ";
			}
		}
		return array($whereCondition, $aParams);
	}
	
	/*
	 * TRANSACTION FUNCTION: Begin a transaction
	 */
	public static function startTransaction() {
		self::$oPDO->beginTransaction();
	}
	
	/*
	 * TRANSACTION FUNCTION: Commit a transaction
	 */
	public static function commitTransaction() {
		self::$oPDO->commit();
	}
	
	/*
	 * TRANSACTION FUNCTION: Rollback a transaction
	 */
	public static function rollbackTransaction() {
		self::$oPDO->rollBack();
	}
	
	/*
	 * TRANSACTION FUNCTION: transaction level - how many transactions are nested
	 */
	public static function transactionLevel() {
		return self::$oPDO->getTransactionLevel();
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
		
		$sql = "SELECT name FROM _locks WHERE name = ?";
		self::query($sql, array($sLockName));
		return true;
	}
}