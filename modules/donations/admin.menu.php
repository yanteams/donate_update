<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_ADMIN'))
    die('Stop!!!');

$submenu['add_donate'] = $lang_module['add_donate'];
// $submenu['edit_donate'] = $lang_module['edit_donate'];
// $submenu['view_donate'] = $lang_module['view_donate'];
$submenu['main_donate'] = $lang_module['details_list'];
$submenu['cat'] = $lang_module['categories'];
// $submenu['main_news'] = $lang_module['content_list'];

if (! function_exists('nv_array_province_admin')) {

    function nv_array_province_admin($module_data)
    {
        global $db_slave;
        
        $array_province_admin = array();
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins ORDER BY userid ASC';
        $result = $db_slave->query($sql);
        
        while ($row = $result->fetch()) {
            $array_province_admin[$row['userid']][$row['provinceid']] = $row;
        }
        
        return $array_province_admin;
    }
}

$is_refresh = false;
$array_province_admin = nv_array_province_admin($module_data);

if (! empty($module_info['admins'])) {
    $module_admin = explode(',', $module_info['admins']);
    foreach ($module_admin as $userid_i) {
        if (! isset($array_province_admin[$userid_i])) {
            $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_admins (userid, provinceid, admin, add_item, pub_item, edit_item, del_item) VALUES (' . $userid_i . ', 0, 1, 1, 1, 1, 1)');
            $is_refresh = true;
        }
    }
}
if ($is_refresh) {
    $array_province_admin = nv_array_province_admin($module_data);
}

$admin_id = $admin_info['admin_id'];
$NV_IS_ADMIN_MODULE = false;
$NV_IS_ADMIN_FULL_MODULE = false;
if (defined('NV_IS_SPADMIN')) {
    $NV_IS_ADMIN_MODULE = true;
    $NV_IS_ADMIN_FULL_MODULE = true;
} else {
    if (isset($array_province_admin[$admin_id][0])) {
        $NV_IS_ADMIN_MODULE = true;
        if (intval($array_province_admin[$admin_id][0]['admin']) == 2) {
            $NV_IS_ADMIN_FULL_MODULE = true;
        }
    }
}



$submenu['tags'] = $lang_module['tags'];
$submenu['cat'] = $lang_module['cat_manage'];
$allow_func[] = 'cat';
$allow_func[] = 'tags';

global $nv_Cache, $global_array_admins, $global_array_admin_groups, $db_config;

$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_admin_groups ORDER BY group_title ASC";
$global_array_admin_groups = $nv_Cache->db($sql, 'gid', $module_name);

if (!function_exists('nv_wallet_array_admins')) {
    /**
     * nv_wallet_array_admins()
     *
     * @param mixed $module_data
     * @return
     */
    function nv_wallet_array_admins($module_data)
    {
        global $db_slave, $db_config;

        $array = array();
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_admins ORDER BY admin_id ASC';
        $result = $db_slave->query($sql);

        while ($row = $result->fetch()) {
            $array[$row['admin_id']] = $row;
        }

        return $array;
    }
}

$is_refresh = false;
$global_array_admins = nv_wallet_array_admins($module_data);

if (!empty($module_info['admins'])) {
    $module_admin = explode(',', $module_info['admins']);
    foreach ($module_admin as $userid_i) {
        if (!isset($global_array_admins[$userid_i])) {
            $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_admins (
                admin_id, gid, add_time, update_time
            ) VALUES (
                ' . $userid_i . ', 0, ' . NV_CURRENTTIME . ', 0
            )');
            $is_refresh = true;
        }
    }
}
if ($is_refresh) {
    $global_array_admins = nv_wallet_array_admins($module_data);
}

$IS_FULL_ADMIN = (defined('NV_IS_SPADMIN') or defined('NV_IS_GODADMIN')) ? true : false;
$PERMISSION_ADMIN = [];
if (isset($global_array_admins[$admin_info['admin_id']]) and isset($global_array_admin_groups[$global_array_admins[$admin_info['admin_id']]['gid']])) {
    $PERMISSION_ADMIN = $global_array_admin_groups[$global_array_admins[$admin_info['admin_id']]['gid']];
}

$allow_func = ['main'];

// Quyền xem và cập nhật ví tiền
if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_wallet'])) {
    $allow_func[] = 'addacount';
}

// Quyền xem giao dịch. Quản lý giao dịch check riêng trong function
if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_vtransaction']) or !empty($PERMISSION_ADMIN['is_mtransaction'])) {
    $allow_func[] = 'transaction';
    $allow_func[] = 'viewtransaction';
    $submenu['transaction'] = $lang_module['transaction'];
}

// Quyền tạo giao dịch
if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_mtransaction'])) {
    $allow_func[] = 'add_transaction';
    $submenu['add_transaction'] = $lang_module['add_transaction'];
}

// Quyền xem đơn hàng. Quản lý đơn hàng check riêng trong function
if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_vorder']) or !empty($PERMISSION_ADMIN['is_morder'])) {
    $allow_func[] = 'order-list';
    $submenu['order-list'] = $lang_module['order_manager'];
}

// Quyền quản lý tỉ giá
if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_exchange'])) {
    $allow_func[] = 'exchange';
    $allow_func[] = 'historyexchange';
    $allow_func[] = 'delrate';
    $submenu['exchange'] = $lang_module['exchange'];
    $submenu['historyexchange'] = $lang_module['historyexchange'];
}

// Quyền quản lý tiền tệ
if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_money'])) {
    $allow_func[] = 'money';
    $allow_func[] = 'delmoney';
    $submenu['money'] = $lang_module['mana_money'];
}

// Quyền quản lý các cổng thanh toán
if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_payport'])) {
    $allow_func[] = 'payport';
    $allow_func[] = 'sms';
    $allow_func[] = 'epay';
    $allow_func[] = 'nganluong';
    $allow_func[] = 'config_sms';
    $allow_func[] = 'config_payment';
    $allow_func[] = 'changepay';
    $allow_func[] = 'actpay';
    $submenu['payport'] = $lang_module['setup_payment'];
}

// Quyền cấu hình module
if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_configmod'])) {
    $allow_func[] = 'config';
    $submenu['config'] = $lang_module['config_module'];
}

//$submenu['config_sms'] = $lang_module['config_sms'];

// Quyền xem thống kê
if ($IS_FULL_ADMIN or !empty($PERMISSION_ADMIN['is_configmod'])) {
    $allow_func[] = 'statistics';
    $submenu['statistics'] = $lang_module['statistics'];
}

if ($IS_FULL_ADMIN) {
    $allow_func[] = 'permission';
    $allow_func[] = 'permission-groups';
    $allow_func[] = 'ipn-logs';
    $submenu['permission'] = $lang_module['permission'];
    $submenu['ipn-logs'] = $lang_module['ipnlog'];
}
