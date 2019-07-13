<?php

class EmailQueue extends DbData {
    const TABLE_NAME     = 'email_queue';
    const ID_FIELD       = 'email_queue_id';
    
    const CONFIG_MAX_SEND_ATTEMPTS  = '/Email/Email Sending/Number of tries';
    const CONFIG_EMAIL_AT_ONCE      = '/Email/Email Sending/Number of emails to send in one go';
    
    const STATUS_SENT           = 'sent';
    const STATUS_NOT_SENT       = 'not sent';
    const PRIORITY_HIGHEST      = 0;
    const PRIORITY_VERY_HIGH    = 2;
    const PRIORITY_HIGH         = 4;
    const PRIORITY_MEDIUM       = 10;
    const PRIORITY_LOW          = 20;
    
    protected $aFields = array(
        'email_queue_id',
        'too',
        'subject',
        'body',
        'priority',
        'status',
        'send_attempts',
        'created_at',
        'updated_at'
    );
    
    function __construct($table = self::TABLE_NAME, $id = self::ID_FIELD, $status = 'status') {
        parent::__construct($table, $id, $status);
    }
    
    /*
     * Get emails to be processed
     */
    public function GetEmailsToProcess() {
        $maxSendAttempts    = Config::configByPath(self::CONFIG_MAX_SEND_ATTEMPTS);
        $emailLimit         = Config::configByPath(self::CONFIG_EMAIL_AT_ONCE);
    
        $sql = "SELECT *"
              ." FROM email_queue"
              ." WHERE status = 'not sent'"
              ." AND send_attempts < ?"
              ." ORDER BY priority DESC"
              ." LIMIT ".$emailLimit;
        $res = $this->db->query($sql, [$maxSendAttempts]);
        if (! $res) {
            return new Collection();
        }
        $iNrItems = $this->db->rowCount($res);
    
        $oCollection = new Collection();
        while ($row = $this->db->fetchAssoc($res)) {
            $oCollection->add($row[$this->getIdField()], $row);
        }

        $oCollection->setItemsNo($iNrItems);
    
        return $oCollection;
    }
}