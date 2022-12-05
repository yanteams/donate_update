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
// SESSION

// $post = [];
// $error = [];
$row = array();
$error = array();
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// $success = array(); 
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
	$row['amount'] = $nv_Request->get_title('amount', 'post', '');
	$row['name'] = $nv_Request->get_title('name', 'post', '');
	$row['email'] = $nv_Request->get_title('email', 'post', '');
	$row['phone'] = $nv_Request->get_title('phone', 'post', '');
	$row['ghichu'] = $nv_Request->get_title('ghichu', 'post', '');
	$row['anonymous'] = $nv_Request->get_int('anonymous', 'post', '');
	$row['paymentid'] = generateRandomString(20);
	$row['status'] = 1;
	$row['created_time'] = NV_CURRENTTIME;
	if (empty($row['amount'])) {
		$error[] = $lang_module['error_required_add_amount'];
	} elseif (empty($row['name'])) {
		$error[] = $lang_module['error_required_add_name'];
	}elseif (empty($row['email'])) {
		$error[] = $lang_module['error_required_add_email'];
	}elseif (empty($row['phone'])) {
		$error[] = $lang_module['error_required_add_phone'];
	}elseif (empty($row['ghichu'])) {
		$error[] = $lang_module['error_required_add_ghichu'];
	}elseif (empty($row['anonymous'])) {
		$error[] = $lang_module['error_required_add_anonymous'];
	// }elseif (empty($row['status'])) {
	// 	$error[] = $lang_module['error_required_add_status'];
    } elseif ($row['amount'] <= 0) {
        $error[] = $lang_module['error_required_add_amount_money'];
	}
	// }else{
    //     $success[] = 'Thêm giao dịch thành công';
	// }
	
	
	if (empty($error)) {
		try {
			if (empty($row['id'])) {

			$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_details (paymentid,amount, name, email, phone, ghichu, anonymous, status,created_time) VALUES (:paymentid,:amount, :name, :email, :phone, :ghichu, :anonymous, :status,:created_time)' );
			} else {
				$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_details SET amount = :amount,paymentid=:paymentid, name = :name, email = :email, phone = :phone, ghichu = :ghichu, anonymous = :anonymous, status = :status, created_time = :created_time');
			}
			$stmt->bindParam(':paymentid', $row['paymentid'], PDO::PARAM_STR);
			$stmt->bindParam(':amount', $row['amount'], PDO::PARAM_INT);
			$stmt->bindParam(':name', $row['name'], PDO::PARAM_STR);
			$stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $row['phone'], PDO::PARAM_STR);
			$stmt->bindParam(':ghichu', $row['ghichu'], PDO::PARAM_STR);
			$stmt->bindParam(':anonymous', $row['anonymous'], PDO::PARAM_INT);
			$stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
			$stmt->bindParam(':created_time', $row['created_time'], PDO::PARAM_INT);
            
			$exc = $stmt->execute();
            //
            //
			if ($exc) {
                $message = '';
				$nv_Cache->delMod($module_name);
				Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
				die();
			}
		} catch (PDOException $e) {
			trigger_error($e->getMessage());
			$error[] = $e->getMessage();
            //Remove this line after checks finished
		}
	}
elseif( $row['id'] > 0 )
{
	
	$row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_details WHERE id=' . $row['id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}else{
	$row['id'] = 0;
	$row['amount'] = '';
	$row['paymentid'] = '';
	$row['name'] = '';
	$row['email'] = '';
	$row['phone'] = '';
	$row['ghichu'] = '';
	$row['anonymous'] = '';
	$row['status'] = '';
}

}
$xtpl = new XTemplate($op.'.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);


if (!empty($error)) {
	$xtpl->assign('ERROR', implode('<br />', $error));
	$xtpl->parse('main.error');
}

// Export Code

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['add_donate'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';