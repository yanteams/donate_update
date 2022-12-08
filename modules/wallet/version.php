<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License GNU/GPL version 3 or any later version
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    "name" => "Quyên góp quỹ từ thiện",
    "modfuncs" => "main,pay,complete,money,exchange,historyexchange,recharge",
    "submenu" => "main,money,exchange,historyexchange",
    "is_sysmod" => 1,
    "virtual" => 1,
    'version' => '4.5.02',
    'date' => 'Mon, 5 Dec 2022 11:04:36 GMT',
    "author" => "YAN (admin@yansupport.com)",
    "uploads_dir" => array($module_name),
    "note" => "Quản lý quỹ quyên góp"
);
