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

function href_admin($sName, $sData = '') {
    if (strstr($sName, '/') !== false) {
        $aPath = explode('/', $sName);
        $sName = $aPath[0];
    }
    switch ($sName) {
        case 'config':
            switch ($aPath[1]) {
                case 'list_items':
                    return HTTP_MAIN.'/admin/config/list_items.html';
                    break;
                case 'add':
                    return HTTP_MAIN.'/admin/config/add.html';
                    break;
            }
            break;
    }
}

?>