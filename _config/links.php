<?php

// This function returns links for the website

function href_website($sName, $sData = '') {
	if (strstr($sName, '/') !== false) {
		$aPath = explode('/', $sName);
		$sName = $aPath[0];
	}
	switch ($sName) {
		case 'website':
			switch ($aPath[1]) {
				case 'homepage':
					return HTTP_MAIN.'/website/homepage.html';
					break;
			}
			break;
	}
}

?>