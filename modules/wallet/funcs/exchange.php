<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License GNU/GPL version 3 or any later version
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_IS_MOD_WALLET'))
    die('Stop!!!');

// if (!defined('NV_IS_USER')) {
//     $redirect = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op, true);
//     nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_redirect_encrypt($redirect));
// }

$page_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$canonicalUrl = getCanonicalUrl($page_url);

$contents = nv_wallet_money_sys($global_array_money_sys);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
