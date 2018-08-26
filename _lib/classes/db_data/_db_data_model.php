<?php

/*
 * This class is used to implement basic operations on a database table
 */

abstract class dbDataModel {
    private $tableName;
    private $idField;
    private $statusField;
    
    protected $db;
    
    function __construct($table, $id, $status) {
        $this->tableName = $table;
        $this->idField = $id;
        $this->statusField = $status;
        
        $this->db = db::getSingleton();
    }
    
    /*
     * The abstract functions: re-declare all of these when you extend
     */
    abstract protected function onBeforeAdd($oItem);
    abstract protected function onAdd($insertId);
    
    abstract protected function onBeforeEdit($iId, $oItem);
    abstract protected function onEdit($iId, $res);
    
    abstract protected function onBeforeDelete($iId);
    abstract protected function onDelete($iId);
    
    abstract protected function onSetStatus($iId);
    
    abstract protected function onBeforeGet($filters, $options);
    abstract protected function onGet(Collection $oCollection): bool;
    
    /*
     * Some get functions for class data
     */
    public function getTableName() {
        return $this->tableName;
    }
    
    public function getIdField() {
        return $this->idField;
    }
    
    public function getStatusField() {
        return $this->statusField;
    }
    
    /*
     * Special function to help translating field names from collection to table
     */
    protected function columnNames($aData) {
        if (!is_array($aData)) {
            return false;
        }
        
        $aColumns = array_flip($this->aFields);
        
        foreach ($aColumns as $realColumnName => $value) {
            $aColumns[$realColumnName] = str_replace('_', '', $realColumnName);
            if (!array_key_exists($aColumns[$realColumnName], $aData)) {
               unset($aColumns[$realColumnName]);
            }
        }
        
        return $aColumns;
    }
    
    /*
     * Add function - adds data to the database
     */
    public function Add($oItem) {
        if (!is_object($oItem)) {
            return false;
        }
        
        // call abstract function onBeforeAdd
        $r = $this->onBeforeAdd($oItem);
        if (!$r) {
            return false;
        }
        
        // compose the SQL strings
        $sFields = '';
        $sValues = '';
        $aParams = array();
        $aMarkers = array();
        $aData = get_object_vars($oItem);
        
        $aFields = array_keys($this->columnNames($aData));
        $sFields = implode('`,`', $aFields);
        $sFields = '`' . $sFields . '`';
        foreach ($aData as $field => $value) {
            $aParams[] = $value;
            $aMarkers[] = '?';
        }
        $sValues = implode(',', $aMarkers);
        
        // execute the query
        $sql = "INSERT INTO `".$this->getTableName() ."`"
                ." (".$sFields.")"
                ." VALUES (".$sValues.")";
        $res = $this->db->query($sql, $aParams);
        if (!$res || $res->errorCode() != '00000') {
            return false;
        }
        $iLastId = $this->db->lastInsertId();

        // call abstract function onAdd
        return $this->onAdd($iLastId);
    }
    
    /*
     * EDIT function - performs updates in the database
     */
    public function Edit($iId, $oItem) {
        if (!is_object($oItem)) {
            return false;
        }
        if (!$iId) {
            return false;
        }
        
        // call abstract function onBeforeAdd
        $r = $this->onBeforeEdit($iId, $oItem);
        if (!$r) {
            return false;
        }
        
        // compose the SQL strings
        $sFields = '';
        $sValues = '';
        $aParams = array();
        $aData = get_object_vars($oItem);
        
        $aFields = array_keys($this->columnNames($aData));
        foreach ($aFields as $key => $value) {
            $aFields[$key] = $value . ' = ?';
        }
        foreach ($aData as $key => $value) {
            $aParams[] = $value;
        }
        $sFields = implode(',', $aFields);
        $aParams[] = $iId;
        
        // execute the query
        $sql = "UPDATE `".$this->getTableName()."` SET "
                .$sFields
                .' WHERE '.$this->getIdField()." = ?";
        $res = $this->db->query($sql, $aParams);
        if ($res->errorCode() != '00000') {
            return false;
        }
        
        return $this->onEdit($iId, $res);
    }
    
    /*
     * DELETE function - deletes from the database
     */
    public function Delete($iId) {
        if (!$iId) {
            return false;
        }
        
        // call pre-delete function
        $r = $this->onBeforeDelete($iId);
        if (!$r) {
            return false;
        }
        
        // delete the item
        $sql = "DELETE FROM `".$this->getTableName() ."`"
                ." WHERE ".$this->getIdField()." = ?";
        $res = $this->db->query($sql, array($iId));
        if ($res->errorCode() != '00000') {
            return false;
        }
        
        // call post delete function
        $r = $this->onDelete($iId);
        
        if ($r) {
            return $this->db->rowCount($res);
        }
        return false;
    }
    
    /*
     * STATUS functions - checge the status to onlin / offline, etc
     */
    public function setStatus($iId, $mNewStatus) {
        if (!$iId) {
            return false;
        }
        
        // run the query
        $sql = "UPDATE `".$this->getTableName()."` SET "
                .$this->getStatusField()." = ? "
                ." WHERE ".$this->getIdField()." = ?";
        $res = $this->db->query($sql, array($mNewStatus, $iId));
        if ($res->errorCode() != '00000') {
            return false;
        }
        
        $r = $this->onSetStatus($iId, $mNewStatus);
        if (!$r) {
            return false;
        }
        return true;
    }
    
    /*
     * Count function - count the rows that match a given criteria
     */
    public function Count($filters = array(), $options = array()) {
        if (!is_array($filters)) {
            return false;
        }
        if (!is_array($options)) {
            return false;
        }
        
        list($whereCondition, $aParams) = $this->db->filters($filters);
        list($searchCondition, $searchParams) = $this->db->searchFilter($options);
        
        if (count($searchParams) > 0 && !empty($searchCondition)) {
            $whereCondition .= $searchCondition;
            $aParams = array_merge($aParams, $searchParams);
        }
        
        $sql = "SELECT COUNT(*) AS cnt FROM `".$this->getTableName() ."`"
                ." WHERE ".$whereCondition;
        $row = $this->db->querySelect($sql, $aParams);
        if (isset($row['cnt'])) {
            return $row['cnt'];
        }
        return false;
    }
    
    /*
     * Get function - fetch data from database
     */
    public function Get($filters = array(), $options = array()) {
        if (!is_array($filters)) {
            return new Collection();
        }
        if (!is_array($options)) {
            return new Collection();
        }
        
        $r = $this->onBeforeGet($filters, $options);
        if (!$r) {
            return new Collection();
        }
        
        if (empty($options['per_page']) && empty($options['page'])) {
            $iNrItems = 0;
        }
        else {
            $iNrItems = $this->Count($filters, $options);
        }
        
        list($whereCondition, $aParams) = $this->db->filters($filters);
        list($searchCondition, $searchParams) = $this->db->searchFilter($options);
        
        if (count($searchParams) > 0 && !empty($searchCondition)) {
            $whereCondition .= $searchCondition;
            $aParams = array_merge($aParams, $searchParams);
        }

        // ordering
        $sOrder = '';
        if (!empty ($options['order_field']) && !empty($options['order_type']) && in_array($options['order_field'], $this->aFields)) {
            $sOrder = " ORDER BY ".$options['order_field']." ".$options['order_type'];
        }
        
        // paging
        $sLimit = '';
        if (!empty($options['per_page']) && !empty($options['page'])) {
            $offset = ($options['page'] - 1) * $options['per_page'];
            $limit = $options['per_page'];
            $sLimit = " LIMIT ".$offset.', '.$limit;
        }
        
        // select the data
        $sql = "SELECT * FROM `".$this->getTableName() ."`"
                ." WHERE ".$whereCondition
                .$sOrder
                .$sLimit;
        $res = $this->db->query($sql, $aParams);
        if (!$res || $res->errorCode() != '00000') {
            return new Collection();
        }
        
        // compute the max page
        $iMaxPage = 0;
        if ($iNrItems > 0 && !empty($options['per_page']) && !empty($options['page'])) {
            $iMaxPage = floor($iNrItems / $options['per_page']);
            if ($iNrItems % $options['per_page'] != 0) {
                $iMaxPage++;
            }
        }
        
        // if the iNrItems is 0, when we are just interested in the number of fetched rows
        if ($iNrItems == 0) {
            $iNrItems = $this->db->rowCount($res);
        }
        
        $oCollection = new Collection();
        while ($row = $this->db->fetchAssoc($res)) {
            $oCollection->add($row[$this->getIdField()], $row);
        }
        
        $oCollection->setMaxPage($iMaxPage);
        $oCollection->setItemsNo($iNrItems);
        
        $r = $this->onGet($oCollection);
        if (!$r) {
            return new Collection();
        }
        
        return $oCollection;
    }
    
    /*
     * Some wrappers for Get
     */
    public function singleGet($filters = array(), $options = array()) {
        $oCollection = $this->Get($filters, $options);
        return $oCollection->getItem();
    }
    
}