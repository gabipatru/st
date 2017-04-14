<?php

/*
 * This class is used to hold data fetched from the database
 */

class Collection extends SetterGetter implements Countable, IteratorAggregate {
    private $data = array();
    
    public function count() {
        return count($this->data);
    }
    
    public function getIterator() {
        return new ArrayIterator($this->data);
    }
    
    /*
     * Create a new item in the collection
     */
    public function add($id, $row) {
        if (!is_array($row)) {
            return false;
        }
        
        $oItem = new SetterGetter();
        
        // set up the item
        foreach ($row as $key => $value) {
            $key = str_replace('_', '', $key);
            $key = 'set'.$key;
            
            $oItem->$key($value);
        }
        
        $this->data[$id] = $oItem;
    }
    
    public function getById($id) {
        if (!isset($this->data[$id])) {
            return null;
        }
        
        return $this->data[$id];
    }
    
    public function getItem() {
        return current($this->data);
    }
    
    /*
     * To and from array - convert array of object to array of arrays and backwards
     */
    public function toArray() {
        $aData = array();
        foreach ($this->data as $key => $oItem) {
            $aData[$key] = get_object_vars($oItem);
        }
        
        return $aData;
    }
    
    public function fromArray($aData) {
        foreach ($aData as $key => $row) {
            $this->add($key, $row);
        }
    }
}