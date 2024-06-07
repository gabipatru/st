<?php

// This function returns links for the website

function href_website($sName, $sData = '')
{
    $View = View::getSingleton();
    
    if (strstr($sName, '/') !== false) {
        $aPath = explode('/', $sName);
        $sName = $aPath[0];
    }
    switch ($sName) {
        case 'website':
            switch ($aPath[1]) {
                case 'homepage':
                    return HTTP_MAIN . '/website/homepage.html';
                case 'category':
                    $id = current($sData);
                    $name = $View->urlFormat(current(array_keys($sData)));
                    return HTTP_MAIN . "/website/category/$name/$id.html";
                case 'series':
                    $id = current($sData);
                    $name = $View->urlFormat(current(array_keys($sData)));
                    return HTTP_MAIN . "/website/series/$name/$id.html";
                case 'contact':
                    return HTTP_MAIN . '/website/contact.html';
                case 'save_language':
                    return HTTP_MAIN . '/website/save_language.html' . ($sData ? '?referrer=' . urldecode($sData) : '');
            }
            break;
        case 'user':
            switch ($aPath[1]) {
                case 'login':
                    return  HTTP_MAIN . '/user/login.html' . ($sData ? "?return=" . urlencode($sData) : '');
                case 'logout':
                    return HTTP_MAIN . '/user/logout.html' . ($sData ? "?return=" . urlencode($sData) : '');
                case 'create_account':
                    return HTTP_MAIN . '/user/newuser.html';
                case 'confirm':
                    return HTTP_MAIN . '/user/confirm.html?code=' . $sData;
                case 'forgot_passwd':
                    return HTTP_MAIN . '/user/forgot_password.html';
                case 'reset_passwd':
                    return HTTP_MAIN . '/user/reset_password.html?code=' . $sData;
            }
    }
}

function href_admin($sName, $sData = '')
{
    $mvc = mvc::getSingleton();
    
    if (strstr($sName, '/') !== false) {
        $aPath = explode('/', $sName);
        $sName = $aPath[0];
    }
    switch ($sName) {
        case 'dashboard':
            switch ($aPath[1]) {
                case 'stats':
                    return HTTP_MAIN . '/admin/dashboard/stats.html';
            }
            break;
        case 'categories':
            switch ($aPath[1]) {
                case 'list':
                    return HTTP_MAIN . '/admin/categories/list_categories.html';
                case 'edit':
                    return HTTP_MAIN . '/admin/categories/edit.html' . ($sData ? '?category_id=' . $sData : '');
                case 'delete':
                    return HTTP_MAIN . '/admin/categories/delete.html' . ($sData ? "?category_id=$sData&token=" . $mvc->securityGetToken() : '');
            }
        case 'series':
            switch ($aPath[1]) {
                case 'list':
                    return HTTP_MAIN . '/admin/series/list_series.html';
                case 'edit':
                    return HTTP_MAIN . '/admin/series/edit.html' . ($sData ? '?series_id=' . $sData : '');
                case 'delete':
                    return HTTP_MAIN . '/admin/series/delete.html' . ($sData ? "?series_id=$sData&token=" . $mvc->securityGetToken() : '');
            }
        case 'groups':
            switch ($aPath[1]) {
                case 'list':
                    return HTTP_MAIN . '/admin/groups/list_groups.html';
                case 'edit':
                    return HTTP_MAIN . '/admin/groups/edit.html' . ($sData ? '?group_id=' . $sData : '');
                case 'delete':
                    return HTTP_MAIN . '/admin/groups/delete.html' . ($sData ? "?group_id=$sData&token=" . $mvc->securityGetToken() : '');
            }
        case 'surprises':
            switch ($aPath[1]) {
                case 'list':
                    return HTTP_MAIN . '/admin/surprises/list_surprises.html';
                case 'edit':
                    return HTTP_MAIN . '/admin/surprises/edit.html' . ($sData ? '?surprise_id=' . $sData : '');
                case 'delete':
                    return HTTP_MAIN . '/admin/surprises/delete.html' . ($sData ? "?surprise_id=$sData&token=" . $mvc->securityGetToken() : '');
            }
        case 'config':
            switch ($aPath[1]) {
                case 'list_items':
                    return HTTP_MAIN . '/admin/config/list_items.html';
                case 'add':
                    return HTTP_MAIN . '/admin/config/add.html';
            }
            break;
        case 'cache':
            switch ($aPath[1]) {
                case 'list_cache':
                    return HTTP_MAIN . '/admin/cache/list_cache.html';
                case 'memcached':
                    return HTTP_MAIN . '/admin/cache/memcached.html';
                case 'elasticsearch':
                    return HTTP_MAIN . '/admin/cache/elasticsearch.html';
                case 'delete_elastic_index':
                    return HTTP_MAIN . '/admin/cache/delete_elastic_index.html' . ($sData ? '?index_name=' . $sData . '&token=' . $mvc->securityGetToken() : '');
                case 'reindex_elastic':
                    return HTTP_MAIN . '/admin/cache/reindex_elastic.html' . ($sData ? '?referrer=' . urldecode($sData) : '');
            }
        case 'users':
            switch ($aPath[1]) {
                case 'list_users':
                    return HTTP_MAIN . '/admin/users/list_users.html';
            }
        case 'email':
            switch ($aPath[1]) {
                case 'list_menu':
                    return HTTP_MAIN . '/admin/email/list_menu.html';
                case 'email_queue':
                    return HTTP_MAIN . '/admin/email/email_queue.html';
                case 'email_log':
                    return HTTP_MAIN . '/admin/email/email_log.html';
            }
        case 'user_groups':
            switch ($aPath[1]) {
                case 'list':
                    return HTTP_MAIN . '/admin/user_groups/list_user_groups.html';
                case 'edit':
                    return HTTP_MAIN . '/admin/user_groups/edit.html' . ($sData ? '?user_group_id=' . $sData : '');
                case 'delete':
                    return HTTP_MAIN . '/admin/user_groups/delete.html' . ($sData ? "?user_group_id=$sData&token=" . $mvc->securityGetToken() : '');
            }
        case 'cron':
            switch ($aPath[1]) {
                case 'list_items':
                    return HTTP_MAIN . '/admin/cron/list_crons.html';
                case 'list_run':
                    return HTTP_MAIN . '/admin/cron/list_run.html' . ($sData ? '?cron_id=' . $sData : '');
            }
    }
}
