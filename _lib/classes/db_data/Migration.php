<?php

/**
 * Allow running migrations when a page is loaded
 */

class Migration extends DbData {
    
    use Log;
    
    const TABLE_NAME    = '_migration';
    const ID_FIELD      = 'migration_id';
    
    protected $aFields = array(
        'migration_id',
        'name',
        'version'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = '') {
        parent::__construct($table, $id, $status);
    }
    
    /**
     * Load the migration sql from a file and return it
     */
    protected function fetchMigrationSQL($folder, $migrationName) {
        // No file exists checking.
        // We don't want the execution to continue if there are migration issues
        require($folder . '/' . $migrationName . '.php');
        if (empty($migrationSql)) {
            die('Failed to run migration ' . $folder . ' : ' . $migrationName);
        }
        
        return $migrationSql;
    }
    
    /**
     * Check if migration file exists
     */
    protected function checkMigrationFile($folder, $migrationName) {
        return file_exists($folder . '/' . $migrationName . '.php');
    }
    
    /**
     * Get the tables in the database
     */
    public function getTables() {
        $sql = "SHOW TABLES";
        $res = $this->db->query($sql);
        
        if (!$res || $res->errorCode() != '00000') {
            return new Collection();
        }
        
        $oCollection = new Collection();
        $i=0;
        while ($row = $this->db->fetchAssoc($res)) {
            $oCollection->add($i, $row);
            $i++;
        }
        
        return $oCollection;
    }
    
    /**
     * Find the migrations which have to be run and run them
     */
    public function runMigrations($migrations = null) {
        // load current mirations status
        require(CONFIG_DIR . '/migration.php');
        
        // get the database tables
        $Tables = $this->getTables();
        
        // lock everything to make sure only one migration runs at one time
        $this->db->startTransaction();
        if (count($Tables) > 0) {
            $this->db->lock_transaction('migrations');
            // load database version for all migrations
            $oDatabaseVersion = $this->Get();
        }
        else {
            $oDatabaseVersion = new Collection();
        }

        // load migration state from DB
        if (!count($oDatabaseVersion)) {
            $this->deployMigrations('migrations', '000');
            
            $filters = [];
            // we may have to run only some migrations
            if (is_array($migrations) && count($migrations) > 0) {
                $filters = ['name' => $migrations];
            }
            $oDatabaseVersion = $this->Get($filters);
        }

        // create array with migration names from database
        $aDatabaseMigrationNames = array();
        foreach ($oDatabaseVersion as $oMigration) {
            $aDatabaseMigrationNames[] = $oMigration->getName();
        }
        
        // check if there are any new migration groups
        foreach ($aMigrationsConfig as $migrationName => $latestVersion) {
            // we must deply the migration if it is not set up in the db
            if (!in_array($migrationName, $aDatabaseMigrationNames)) {
                // but if we must deply only some migrations, extra checks are made
                if (! is_array($migrations) || count($migrations) == 0) {
                    $this->deployMigrations($migrationName, '000');
                }
                elseif (is_array($migrations) && count($migrations) > 0 && in_array($migrationName, $migrations)) {
                    $this->deployMigrations($migrationName, '000');
                }
            }
        }
        
        // check for new migrations
        foreach ($aMigrationsConfig as $migrationName => $latestVersion) {
            foreach ($oDatabaseVersion as $oMigration) {
                if ($oMigration->getName() == $migrationName) {
                    if ($oMigration->getVersion() != $latestVersion) {
                        $this->deployMigrations($migrationName, $oMigration->getVersion());
                    }
                    break;
                }
            }
        }
        
        $this->db->commitTransaction();
    }
    
    /**
     * Run the migrations
     */
    private function deployMigrations($migrationName, $currentVersion) {
        $folder = MIGRATIONS_DIR . '/' . $migrationName;
        $nextMigrationName = $this->getNextMigration($currentVersion);

        // while there are migrations, run them
        while ($this->checkMigrationFile($folder, $this->getNextMigration($currentVersion))) {
            // never run the previous migration
            $executedMigrations = '';
            if (isset($migrationSql)) {
                unset($migrationSql);
            }
            
            $nextMigrationName = $this->getNextMigration($currentVersion);
            
            $migrationSql = $this->fetchMigrationSQL($folder, $nextMigrationName);

            $timeStart = microtime(true);

            // run the migration(s)
            if (is_array($migrationSql)) {
                foreach ($migrationSql as $mig) {
                    $r = $this->db->query($mig);
                    if (!$r) {
                        die('Failed to run migration '.$mig);
                    }
                    $executedMigrations .= $mig;
                }
            }
            else {
                $r = $this->db->query($migrationSql);
                if (!$r) {
                    die('Failed to run migration '.$mig);
                }
                $executedMigrations = $migrationSql;
            }
            
            // migration ran, update the database version
            if ($nextMigrationName === '001') {
                
                $oItem = new SetterGetter();
                $oItem->setName($migrationName);
                $oItem->setVersion($nextMigrationName);
                
                $r = $this->Add($oItem);
                if (!$r) {
                    die('Failed to add migration name : ' . $migrationName);
                }
                $migrationId = $this->db->lastInsertId();
            }
            else {
                $filters = array('name' => $migrationName);
                $oMigration = $this->singleGet($filters);
                if (! $oMigration instanceof SetterGetter) {
                    die('Failed to fetch migration name : ' . $migrationName);
                }

                $oItem = new SetterGetter();
                $oItem->setVersion($nextMigrationName);
                
                $r = $this->Edit($oMigration->getMigrationId(), $oItem);
                if (!$r) {
                    die('Failed to update migration version : ' . $migrationName);
                }
                $migrationId = $oMigration->getMigrationId();
            }
            
            $timeEnd = microtime(true);
            $time = $timeEnd - $timeStart;
            
            // update the migrations log table
            $this->updateMigrationLog($executedMigrations, $migrationId, $time);
            
            $currentVersion = $nextMigrationName;
        }
    }
    
    /**
     * Update the migrations log
     */
    private function updateMigrationLog($sql, $migrationId, $time) {
        $oItem = new SetterGetter();
        $oItem->setMigrationId($migrationId);
        $oItem->setQuery($sql);
        $oItem->setDuration($time);
        
        $oMigrationLog = new MigrationLog();
        $r = $oMigrationLog->Add($oItem);
        if (!$r) {
            $this->logMessage('Failed to update migrations log with query '>$sql);
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