<?php
/* HTTP FUNCTIONS */
function http_redir($url) {
	header('Location:'.$url);
	exit();
}

function http_not_found() {
	header("Location:".HTTP_BASE_URL."404.php");
	exit();
}

function url_format($str) {
	//$str = filter_var($str, FILTER_SANITIZE_URL);
	$str = str_replace(' ', '-', $str);
	$str = str_replace('_', '-', $str);
	return $str;
}
?>