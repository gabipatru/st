<?php

define('LOG_EMAIL_TABLE_NAME', 'log_email');
define('LOG_EMAIL_ID_FIELD', 'id');

class LogEmail extends dbDataModel {
	function __construct($table = LOG_EMAIL_TABLE_NAME, $id = LOG_EMAIL_ID_FIELD, $status = '') {
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
}