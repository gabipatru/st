<?php
define('MIGRATIONS_TABLE_NAME', '_migrations');
define('MIGRATIONS_ID_FIELD', 'migration_id');
define('MIGRATIONS_STATUS', 'status');

class Migrations extends dbDataModel {
    function __construct($table = MIGRATIONS_TABLE_NAME, $id = MIGRATIONS_ID_FIELD, $status = MIGRATIONS_STATUS) {
        parent::__construct($table, $id, $status);
    }
    
    public function onAdd($insertId) {
        $file_name = time();
        
        // create migration file
        if (file_exists(MIGRATIONS_DIR.'/'.$file_name.'.php')) {
            return false;
        }
                
        $fis = fopen(MIGRATIONS_DIR .'/'.$file_name.'.php', "w");
        if (!$fis) {
            return false;
        }
                
        fputs($fis, '<?php');
        fclose($fis);
        
        // update migration name
        $data = array('name' => $file_name);
        $r = $this->Edit($insertId, $data);
        
        if ($r) {
            return true;
        }
        else {
            return false;
        }
    }
    public function onEdit($iId, $res) {
        return true;
    }
    public function onSetStatus($iId) {
        return true;
    }
    public function onBeforeDelete($iId) {
        return true;
    }
    public function onDelete($iId) {
        return true;
    }
}