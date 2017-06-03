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
				case 'contact':
				    return HTTP_MAIN.'/website/contact.html';
			}
			break;
		case 'user':
		    switch ($aPath[1]) {
		        case 'login':
		            return  HTTP_MAIN.'/user/login.html'. ($sData ? "?return=".urlencode($sData) : '');
		        case 'logout':
		        	return HTTP_MAIN.'/user/logout.html'. ($sData ? "?return=".urlencode($sData) : '');
		        case 'create_account':
		            return HTTP_MAIN.'/user/newuser.html';
		    }
	}
}

function href_admin($sName, $sData = '') {
    if (strstr($sName, '/') !== false) {
        $aPath = explode('/', $sName);
        $sName = $aPath[0];
    }
    switch ($sName) {
        case 'dashboard':
            switch ($aPath[1]) {
                case 'stats':
                    return HTTP_MAIN.'/admin/dashboard/stats.html';
            }
            break;
        case 'config':
            switch ($aPath[1]) {
                case 'list_items':
                    return HTTP_MAIN.'/admin/config/list_items.html';
                case 'add':
                    return HTTP_MAIN.'/admin/config/add.html';
            }
            break;
        case 'cache':
            switch ($aPath[1]) {
                case 'list_cache':
                    return HTTP_MAIN.'/admin/cache/list_cache.html';
                case 'memcached':
                    return HTTP_MAIN.'/admin/cache/memcached.html';
            }
        case 'users':
            switch ($aPath[1]) {
                case 'list_users':
                    return HTTP_MAIN.'/admin/users/list_users.html';
            }
    }
}

?>