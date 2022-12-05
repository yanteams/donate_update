<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function get_list_donate()
{
    global $db, $lang_module, $module_data;
    
    $show_list_donate = array();
   
   $result = $db->query( "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_details");
   while ( $row = $result->fetch() )
   {
      $show_list_donate[] = array (
        'id' => $row['id'],
        'amount' => $row['amount'] = number_format($row['amount']),
        'name' => $row['name'] = $row['name'] ? $row['name'] : '--',
        'email' => $row['email'] = $row['email'] ? $row['email'] : '--',
        'phone' => $row['phone'] = $row['phone'] ? $row['phone'] : '--',
        'ghichu' => $row['ghichu'] = $row['ghichu'] ? $row['ghichu'] : '--',
        'anonymous' => $row['anonymous'] = ($row['anonymous'] == 1) ? $lang_module['anonymous_status1'] : $lang_module['anonymous_status2'],
        'status' => $row['status'] = ($row['status'] == 1) ? $lang_module['transaction_status4'] : $lang_module['transaction_status0'],
        'created_time' => $row['created_time'] = date("H:i d/m/Y", $row['created_time'])
			
      );
   }
    return $show_list_donate;
}

// function get_list_donate($bid)
// {
//     global $db, $lang_module, $module_name, $module_data, $op, $module_file, $global_config;
    
//     $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
//     $xtpl->assign('LANG', $lang_module);
//     $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
//     $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
//     $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
//     $xtpl->assign('MODULE_NAME', $module_name);
//     $xtpl->assign('OP', $op);
//     $xtpl->assign('BID', $bid);
 
//     $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_details';
//     $array_block = $db->query($sql)->fetchAll();
    
//     $num = sizeof($array_block);
//     if ($num > 0) {
//         foreach ($array_block as $row) {
//             $xtpl->assign('ROW', array(
//                 'id' => $row['id'],
//                 'amount' => $row['amount'] = number_format($row['amount']),
//                 'name' => $row['name'] = $row['name'] ? $row['name'] : '--',
//                 'email' => $row['email'] = $row['email'] ? $row['email'] : '--',
//                 'phone' => $row['phone'] = $row['phone'] ? $row['phone'] : '--',
//                 'ghichu' => $row['ghichu'] = $row['ghichu'] ? $row['ghichu'] : '--',
//                 'anonymous' => $row['anonymous'] = ($row['anonymous'] == 1) ? $lang_module['anonymous_status1'] : $lang_module['anonymous_status2'],
//                 'status' => $row['status'] = ($row['status'] == 1) ? $lang_module['transaction_status4'] : $lang_module['transaction_status0'],
//                 'created_time' => $row['created_time'] = date("H:i d/m/Y", $row['created_time'])
//             ));
            
//             $xtpl->parse('main.loop');
//         }
        
//         $xtpl->parse('main');
//         $contents = $xtpl->text('main');
//     } else {
//         $contents = '&nbsp;';
//     }
    
//     return $contents;
// // }
// $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_xxx";
// $result = $db->query( $sql );
// $num_row = $result->rowCount();
// $xtpl->assign( 'COUNT_XX', $lang_module['xxx'] . $num_row );

