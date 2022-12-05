<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    'name' => 'Donations',
    "modfuncs" => "main,pay,complete,money,exchange,historyexchange,recharge",
    'change_alias' => 'main,detail,search,list',
    "submenu" => "main,money,exchange,historyexchange",
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.3.03',
    'date' => 'Mon, 5 Dec 2022 11:04:36 GMT',
    'author' => 'YAN (admin@yansupport.com)',
    'uploads_dir' => array($module_name),
    'note' => ''
);
