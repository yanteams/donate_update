<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

// SESSION
if (!defined('NV_IS_FILE_ADMIN'))
	die('Stop!!!');
if (defined('NV_EDITOR')) {
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
$xtpl = new XTemplate( $op.".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$url_add = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add_donate";

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_details";
$result = $db->query($sql);
$num = $result->rowCount();

if($num != 0)
{
    $show_list_donate = get_list_donate();
    foreach ($show_list_donate AS $row)
    {
        $xtpl->assign( 'ROW', $row);
        $xtpl->assign( 'EDIT', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=edit_donate&ac=edit&id=" .$row['id']);
        $xtpl->assign( 'DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=edit_donate&ac=del&id=" .$row['id']);
        $xtpl->parse('main.loop');
    }
    
    $xtpl->assign( 'BACK', $url_add);
    
    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    header("Location: $url_add");
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';