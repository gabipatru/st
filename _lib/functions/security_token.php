<?php

// token expires in 1 hour
define('TOKEN_EXPIRATION', 180);

function securityGetToken() {
	if (isset($_SESSION['security_token']['value'])) {
		return $_SESSION['security_token']['value'];
	}
	else {
		return md5(rand(0, 100));
	}
}

function securityCheckToken($sToken) {
	if ($sToken == securityGetToken()) {
		return true;
	}
	else {
		return false;
	}
}

function securityUpdateToken() {
	// check if the token needs updating
	if (isset($_SESSION['security_token']['time']) && $_SESSION['security_token']['time'] + TOKEN_EXPIRATION > time()) {
		$_SESSION['security_token']['time'] = time();
		return;
	}

	// update the token
	$_SESSION['security_token'] = array();
	$_SESSION['security_token']['value'] = md5(time());
	$_SESSION['security_token']['time'] = time();
}

?>