<?php
/*
 * This class is used for communicating a json response (success or error) to a ajax request
 */

class ajax_json {
	private static $response = null;
	
	public static function success() {
		self::$response = array('response' => 'success');
	}
	
	public static function error($msg = null) {
		self::$response = array('response' => 'error');
		if ($msg) {
		    self::$response['error_message'] = __($msg);
		}
	}
	
	public static function output_json() {
		header('Content-Type: application/json');
		echo json_encode(self::$response);
	}
}