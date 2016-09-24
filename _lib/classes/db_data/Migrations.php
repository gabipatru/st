<?php

/**
 * Allow running migrations when a page is loaded
 */

class Migrations extends dbDataModel {
    const TABLE_NAME    = '_migrations';
    const ID_FIELD      = 'migration_id';
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
        parent::__construct($table, $id, $status);
    }
    
    public function onAdd($insertId) {
        return true;
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
    
    /**
     * Find the migrations which have to be run and run them
     */
    public function runMigrations() {
        // load current mirations status
        require_once(CONFIG_DIR . '/migrations.php');
        
        // load database version for all migrations
        $aDatabaseVersion = $this->simpleGet();
        if (!$aDatabaseVersion) {
            $this->deployMigrations('migrations', '000');
        }
        
        // check for new migrations
        foreach ($aMigrationsConfig as $migrationName => $latestVersion) {
            foreach ($aDatabaseVersion as $migration_id => $aMigration) {
                if ($aMigration['name'] == $migrationName && $aMigration['version'] != $latestVersion) {
                    $this->deployMigrations($migrationName, $aMigration['version']);
                }
            }
        }
    }
    
    /**
     * Run the migrations
     */
    private function deployMigrations($migrationName, $currentVersion) {
        $folder = MIGRATIONS_DIR . '/' . $migrationName;
        $nextMigrationName = $this->getNextMigration($currentVersion);
        
        db::startTransaction();
        
        // while there are migrations, run them
        while (file_exists($folder . '/' . $this->getNextMigration($currentVersion) . '.php')) {
            // never run the previous migration
            $executedMigrations = '';
            if (isset($migrationSql)) {
                unset($migrationSql);
            }
            
            $nextMigrationName = $this->getNextMigration($currentVersion);
            require_once($folder . '/' . $nextMigrationName . '.php');
            if (empty($migrationSql)) {
                die('Failed to run migration ' . $migrationName . ' : ' . $nextMigrationName);
            }

            // run the migration(s)
            if (is_array($migrationSql)) {
                foreach ($migrationSql as $mig) {
                    $r = db::query($mig);
                    if (!$r) {
                        die('Failed to run migration '.$mig);
                    }
                    $executedMigrations .= $mig;
                }
            }
            else {
                $r = db::query($migrationSql);
                if (!$r) {
                    die('Failed to run migration '.$mig);
                }
                $executedMigrations = $migrationSql;
            }
            
            // migration ran, update the database version
            if ($nextMigrationName === '001') {
                $r = $this->Add(array('version' => $nextMigrationName, 'name' => $migrationName));
                if (!$r) {
                    die('Failed to add migration name : ' . $migrationName);
                }
                $migrationId = db::lastInsertId();
            }
            else {
                $filters = array('name' => $migrationName);
                $aMigration = $this->singleGet($filters);
                if (!$aMigration) {
                    die('Failed to fetch migration name : ' . $migrationName);
                }
                $r = $this->Edit($aMigration['migration_id'], array('version' => $nextMigrationName));
                if (!$r) {
                    die('Failed to update migration version : ' . $migrationName);
                }
                $migrationId = $aMigration['migration_id'];
            }
            
            // update the migrations log table
            $this->updateMigrationLog($executedMigrations, $migrationId);
            
            $currentVersion = $nextMigrationName;
        }
        
        db::commitTransaction();
    }
    
    /**
     * Update the migrations log
     */
    private function updateMigrationLog($sql, $migrationId) {
        $data = array('query' => $sql, 'migration_id' => $migrationId);
        $oMigrationLog = new MigrationsLog();
        $r = $oMigrationLog->Add($data);
        if (!$r) {
            log_message('migrations.log', 'Failed to update migrations log with query '>$sql);
            return false;
        }
        return true;
    }
    
    /**
     * Return the next version fo the migrations based on the current version
     */
    private function getNextMigration($currentVersion) {
        $nextVersion = (int) $currentVersion;
        $nextVersion++;
        if ($nextVersion < 10) {
            return '00' . $nextVersion;
        }
        if ($nextVersion < 100) {
            return '0' . $nextVersion;
        }
        return (string) $nextVersion;
    }
}