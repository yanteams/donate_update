<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License GNU/GPL version 3 or any later version
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$id = $nv_Request->get_int('id', 'post,get', 0);
$contents = "NO_" . $id;
$logvalue = "";
if ($id > 0) {
    $query = "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE id=" . $id;
    $db->query($query);
    $contents = "OK_" . $id;
    $logvalue = $id;
} else {
    $listall = $nv_Request->get_string('listall', 'post,get');
    $logvalue = $listall;
    $array_id = explode(',', $listall);
    $array_id = array_map("intval", $array_id);
    foreach ($array_id as $id) {
        if ($id > 0) {
            $sql = "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_exchange WHERE id=" . $id;
            $result = $db->query($sql);
        }
    }
    $contents = "OK_0";
}

nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Exchange', $logvalue, $admin_info['userid']);

$nv_Cache->delMod($module_name);
echo $contents;
