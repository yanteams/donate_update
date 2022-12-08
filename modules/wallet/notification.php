<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License GNU/GPL version 3 or any later version
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    die('Stop!!!');
}

$lang_siteinfo = nv_get_lang_module($mod);

$data['title'] = sprintf($lang_siteinfo['notification_payport_ipn_alert'], $data['content']['payment'], $data['content']['ip'], nv_date('H:i d/m/Y', $data['content']['time']));
$data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=payport&amp;payment=' . $data['content']['payment'];
