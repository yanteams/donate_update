<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

$page_title = $lang_module['edit_donate'];

$xtpl = new XTemplate( $op.".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$error = "";

$id = $nv_Request->get_int ('id', 'get','');

   $main_donate = array();
   $result = $db->query( "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_details WHERE id = '" . $id . "'");
   while ( $row = $result->fetch() )
   {
      $main_donate[] = array (
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

if ( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
    $row['amount'] = $nv_Request->get_title('amount', 'post', '');
	$row['name'] = $nv_Request->get_title('name', 'post', '');
	$row['email'] = $nv_Request->get_title('email', 'post', '');
	$row['phone'] = $nv_Request->get_title('phone', 'post', '');
	$row['ghichu'] = $nv_Request->get_title('ghichu', 'post', '');
	$row['anonymous'] = $nv_Request->get_int('anonymous', 'post', '');
	// $row['paymentid'] = generateRandomString(20);
	$row['status'] = 1;
	$row['created_time'] = NV_CURRENTTIME;
    
    if(isset($id))
    {
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
        } elseif ($row['amount'] <= 0) {
            $error[] = $lang_module['error_required_add_amount_money'];
        }
        else
        {
            $stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_details SET amount = :amount, name = :name, email = :email, phone = :phone, ghichu = :ghichu, anonymous = :anonymous, status = :status, created_time = :created_time WHERE id =' . $id );
			// $stmt->bindParam(':paymentid', $row['paymentid'], PDO::PARAM_STR);
			$stmt->bindParam(':amount', $row['amount'], PDO::PARAM_INT);
			$stmt->bindParam(':name', $row['name'], PDO::PARAM_STR);
			$stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $row['phone'], PDO::PARAM_STR);
			$stmt->bindParam(':ghichu', $row['ghichu'], PDO::PARAM_STR);
			$stmt->bindParam(':anonymous', $row['anonymous'], PDO::PARAM_INT);
			$stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
			$stmt->bindParam(':created_time', $row['created_time'], PDO::PARAM_INT);
            
			$stmt->execute();
                                      
            if($stmt->rowCount())
            {
                Header("Location:" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main_donate");
            }
            else
            {
                die();
            }
        }
    }
}

$ac = $nv_Request->get_string ('ac', 'get','');
$id = $nv_Request->get_int ('id', 'get','');

if($ac == 'del')
{
    $result = $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_details WHERE id = '".$id."' ");
    // $result = $db->query($check);
    Header("Location:" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main_donate");

   
}

$url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $id;
$xtpl->assign( 'ACTION', $url );

foreach ($main_donate AS $row)
{
    $xtpl->assign('ROW', $row);
}

$xtpl->assign( 'ERROR', $error );


$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );