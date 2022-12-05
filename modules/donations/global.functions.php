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


$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories ORDER BY parentid, weight ASC';
$array_cat = $nv_Cache->db($sql, 'id', $module_name);


/**
* nv_number_format()
*
* @param mixed $number            
* @param integer $decimals            
* @return
*
*/
function check_exits($id_tinrao, $mang)
{
  foreach ($mang as $row) {
      if ($row['id'] == $id_tinrao)
          return true;
  }
  return false;
}

function nv_number_format($number, $decimals = 0)
{
  global $array_config;
  
  $str = number_format($number, $decimals, $array_config['dec_point'], $array_config['thousands_sep']);
  
  return $str;
}

/**
* nv_setcats()
*
* @param mixed $list2            
* @param mixed $id            
* @param mixed $list            
* @param integer $m            
* @param integer $num            
* @return
*
*/
function nv_setcats($list2, $id, $list, $m = 0, $num = 0)
{
  ++ $num;
  $defis = '';
  for ($i = 0; $i < $num; ++ $i) {
      $defis .= '--';
  }
  
  if (isset($list[$id])) {
      $list2[$id]['subcat'] = array();
      foreach ($list[$id] as $value) {
          if ($value['id'] != $m) {
              $list2[$value['id']] = $value;
              $list2[$value['id']]['name'] = '|' . $defis . '&gt; ' . $list2[$value['id']]['name'];
              if ($value['parentid']) {
                  $list2[$id]['subcat'][] = $value['id'];
              }
              if (isset($list[$value['id']])) {
                  $list2 = nv_setcats($list2, $value['id'], $list, $m, $num);
              }
          }
      }
  }
  return $list2;
}

/**
* nv_listcats()
*
* @param mixed $parentid            
* @param integer $m            
* @return
*
*/
function nv_listcats($parentid = 0, $m = 0, $inarray = array())
{
  global $db, $module_data;
  
  $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories ORDER BY parentid, weight ASC';
  $result = $db->query($sql);
  $list = array();
  while ($row = $result->fetch()) {
      
      if (! empty($inarray) and ! in_array($row['id'], $inarray)) {
          continue;
      }
      
      $list[$row['parentid']][] = array(
          'id' => (int) $row['id'],
          'parentid' => (int) $row['parentid'],
          'title' => $row['title'],
          'alias' => $row['alias'],
          'description' => $row['description'],
          'groups_view' => ! empty($row['groups_view']) ? explode(',', $row['groups_view']) : array(
              6
          ),
          'weight' => (int) $row['weight'],
          'status' => $row['status'],
          'name' => $row['title'],
          'selected' => $parentid == $row['id'] ? ' selected="selected"' : ''
      );
  }
  
  if (empty($list)) {
      return $list;
  }
  
  $list2 = array();
  foreach ($list[0] as $value) {
      if ($value['id'] != $m) {
          $list2[$value['id']] = $value;
          if (isset($list[$value['id']])) {
              $list2 = nv_setcats($list2, $value['id'], $list, $m);
          }
      }
  }
  return $list2;
}







// PAYMENT
function get_display_money($amount, $digis = 2, $dec_point = ',', $thousan_step = '.')
{
    $amount = number_format($amount, intval($digis), $dec_point, $thousan_step);
    $amount = rtrim($amount, '0');
    $amount = rtrim($amount, $dec_point);
    return $amount;
}

/**
 * get_db_money()
 *
 * @param mixed $amount
 * @param mixed $currency
 * @return
 */
function get_db_money($amount, $currency)
{
    if ($currency == 'VND') {
        return round($amount);
    } else {
        return round($amount, 2);
    }
}

/**
 * Cập nhật hết hạn các giao dịch
 * @return boolean
 */
function nvUpdateTransactionExpired()
{
    global $module_config, $module_name, $db, $module_data, $nv_Cache, $db_config;
    $exp_setting = $module_config[$module_name]['transaction_expiration_time'];
    if (empty($exp_setting)) {
        return true;
    }
    $since_timeout = NV_CURRENTTIME - ($exp_setting * 3600);

    // Cho hết hạn các đơn hàng đã quá hạn
    $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_transaction SET is_expired=1 WHERE (transaction_status=0 OR transaction_status=1) AND created_time<=" . $since_timeout);

    // Tìm kiếm thời gian hết hạn tiếp theo
    $next_update_time = $db->query("SELECT MIN(created_time) FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE (transaction_status=0 OR transaction_status=1) AND created_time>" . $since_timeout)->fetchColumn();
    if ($next_update_time > 0) {
        $next_update_time += ($exp_setting * 3600);
    }
    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value=" . $db->quote($next_update_time) . " WHERE lang=" . $db->quote(NV_LANG_DATA) . " AND module=" . $db->quote($module_name) . " AND config_name='next_update_transaction_time'");

    $nv_Cache->delMod($module_name);
    $nv_Cache->delMod('settings');
}

$global_array_color_month = array(
    1 => '#DC143C',
    2 => '#8B4789',
    3 => '#4B0082',
    4 => '#27408B',
    5 => '#33A1C9',
    6 => '#2F4F4F',
    7 => '#008B45',
    8 => '#556B2F',
    9 => '#CD950C',
    10 => '#CD6600',
    11 => '#EE5C42',
    12 => '#EE0000',
);

// Tiền tệ hệ thống sử dụng
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_money_sys";
$global_array_money_sys = $nv_Cache->db($sql, 'code', $module_name);

// Các cổng thanh toán đang kích hoạt
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_payment WHERE active = 1 ORDER BY weight ASC';
$global_array_payments = $nv_Cache->db($sql, 'payment', $module_name);

$global_array_transaction_status = [
    0 => $lang_module['transaction_status0'],
    1 => $lang_module['transaction_status1'],
    2 => $lang_module['transaction_status2'],
    3 => $lang_module['transaction_status3'],
    4 => $lang_module['transaction_status4'],
    5 => $lang_module['transaction_status5'],
    6 => $lang_module['transaction_status6']
];

$global_array_transaction_type = [
    '0' => $lang_module['status_sub0'],
    '1' => $lang_module['status_sub1'],
    '2' => $lang_module['status_sub2'],
    '4' => $lang_module['status_sub4']
];

if (!empty($module_config[$module_name]['next_update_transaction_time']) and $module_config[$module_name]['next_update_transaction_time'] <= NV_CURRENTTIME) {
    // Cập nhật lại trạng thái hết hạn các giao dịch
    nvUpdateTransactionExpired();
}
