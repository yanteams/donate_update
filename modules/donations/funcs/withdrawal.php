<?php
/**
 * @Project TMS HOLDINGS
 * @Author TMS HOLDINGS (contact@tms.vn)
 * @Copyright (C) 2021 TMS HOLDINGS. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 01/01/2021 09:47
 */
if($user_info['userid']==''){
	                nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users' . '&' . NV_OP_VARIABLE . '=login');

}
    $query3 = 'SELECT count(*) as dem FROM ' . $db_config['prefix'] . '_' . $module_data . '_acount_nganhang WHERE userid=' . $user_info['userid'];
    $row3 = $db->query($query3)->fetch();
	while ($row3)
        {
			if($row3['dem'] ==0){
                nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=acount');

			}else{
if ($nv_Request->isset_request('ajax_action', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
    $content = 'NO_' . $id;
    if ($new_vid > 0)     {
        $sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal WHERE id!=' . $id . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch())
        {
            ++$weight;
            if ($weight == $new_vid) ++$weight;             $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal SET weight=' . $weight . ' WHERE id=' . $row['id'];
            $db->query($sql);
        }
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal SET weight=' . $new_vid . ' WHERE id=' . $id;
        $db->query($sql);
        $content = 'OK_' . $id;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
	$row['userid']=$user_info['userid'];
	
 // Xác định số tiền trong ví của tài khoản thực hiện đầu tư
    $sql1 = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_acount_nganhang where userid=".$user_info['userid']."";
    $sth1 = $db->prepare($sql1);
    $sth1->execute();
    $acount_nganhang = $sth1->fetch();
	
    $row['money_out'] = $nv_Request->get_title('money_out', 'post', '');
	$row['money_out'] = floatval(str_replace(',', '', $row['money_out']));
	    $row['money_unit'] = $nv_Request->get_title('money_unit', 'post', '');

 // Xác định số tiền trong ví của tài khoản thực hiện đầu tư
    $sql = "SELECT money_total,money_unit FROM " . $db_config['prefix'] . "_" . "wallet_money where userid=".$user_info['userid']."";

    $sth = $db->prepare($sql);
    $sth->execute();
    $money_total_info = $sth->fetch();

    $row['transaction_info'] = $nv_Request->get_textarea('transaction_info', '', NV_ALLOWED_HTML_TAGS);
	if($row['transaction_info'] == ''){
		$row['transaction_info'] =0;
	}
  if (empty($row['money_out'])) {
        $error[] = $lang_module['error_required_money_out'];
    }elseif(($row['money_out'] > $money_total_info['money_total'])&&($row['money_unit'] == $money_total_info['money_unit'])){
        $error[] = $lang_module['error_required_money'];
	}

    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $row['transaction_time'] = NV_CURRENTTIME	;
				$sql='INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal (idtaikhoan, money_out,money_unit, transaction_time, transaction_info, weight, status) VALUES (:idtaikhoan, :money_out,:money_unit, :transaction_time, :transaction_info, :weight, :status)';
				               $weight = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal')->fetchColumn();
                $weight = intval($weight) + 1;
				$data_insert=array();
               $data_insert['transaction_time']= $row['transaction_time'];
               $data_insert['idtaikhoan']= $acount_nganhang['id'];
               $data_insert['money_out']= $row['money_out'];
               $data_insert['money_unit']= $row['money_unit'];
               $data_insert['transaction_info']= $row['transaction_info'];
                $data_insert['weight']=  $weight;
                $data_insert['status']= 0;
				$id_withdrawal=$db->insert_id($sql,'id',$data_insert);
            } 
  
            if ($id_withdrawal>0) {
                $nv_Cache->delMod($module_name);
                if (empty($row['id'])) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Withdrawal', ' ', $user_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Withdrawal', 'ID: ' . $row['id'], $user_info['userid']);
                }
				// Xác định tài khoản tác động
				$sql = "SELECT userid, username, first_name, last_name, email FROM " . NV_USERS_GLOBALTABLE . " WHERE userid=:userid";
				$sth = $db->prepare($sql);
				$sth->bindParam(':userid', $row['userid'], PDO::PARAM_STR);
				$sth->execute();
				$row['money_out2']=number_format($row['money_out']);
				$row['transaction_time2'] = date('d/m/Y H:i:s',$row['transaction_time']);
				$account_info = $sth->fetch();
				require_once NV_ROOTDIR . '/modules/wallet/wallet.class.php';
				$wallet = new nukeviet_wallet();
				$message = 'Tài khoản'.' '.$account_info['username'].' đã yêu cầu rút tiền với số tiền'.' '.$row['money_out2'].' '.' Hệ thống xử lý thành công, bạn vui lòng đợi 1-2 ngày tiền được chuyển vào tài khoản không tính thứ 7, chủ nhật và ngày lễ';
				$checkUpdate = $wallet->update($row['money_out'], 'VND', $row['userid'], $message);
				$sql_update=$db->query('UPDATE '.MODULE_WALLET.'_transaction  SET transaction_status=1 where id='.$checkUpdate);
				$sql_withdrawal_update=$db->query('UPDATE '.MODULE_WALLET.'_withdrawal  SET transaction_id='.$checkUpdate.' where id='.$id_withdrawal);
                nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=historyexchange');
            }
        } catch(PDOException $e) {
            trigger_error($e->getMessage());
            die($e->getMessage()); //Remove this line after checks finished
        }
    }
} elseif ($row['id'] > 0) {die(abc);
    $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
	// Xác định số tiền trong ví của tài khoản thực hiện đầu tư
    $sql = "SELECT money_total,money_unit FROM " . $db_config['prefix'] . "_" . "wallet_money where userid=".$user_info['userid']."";

    $sth = $db->prepare($sql);
    $sth->execute();
    $money_total_info = $sth->fetch();
	$row['money_in']=number_format($money_total_info['money_total']).' '.$money_total_info['money_unit'];

} else {
    $row['id'] = 0;
    $row['userid'] = 0;
    $row['money_out'] = '0';
    $row['transaction_info'] = '';
	    $row['money_unit'] = '';

	// Xác định số tiền trong ví của tài khoản thực hiện đầu tư
    $sql = "SELECT money_total,money_unit FROM " . $db_config['prefix'] . "_" . "wallet_money where userid=".$user_info['userid']."";

    $sth = $db->prepare($sql);
    $sth->execute();
    $money_total_info = $sth->fetch();
	$row['money_in']=number_format($money_total_info['money_total']).' '.$money_total_info['money_unit'];
}

$row['transaction_info'] = nv_htmlspecialchars(nv_br2nl($row['transaction_info']));


$q = $nv_Request->get_title('q', 'post,get');

// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get')) {
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from('' . $db_config['prefix'] . '_' . $module_data . '_withdrawal');

    if (!empty($q)) {
        $db->where('userid LIKE :q_userid OR acount LIKE :q_acount OR information LIKE :q_information OR money_out LIKE :q_money_out OR transaction_time LIKE :q_transaction_time OR transaction_info LIKE :q_transaction_info OR status LIKE :q_status');
    }
    $sth = $db->prepare($db->sql());

    if (!empty($q)) {
        $sth->bindValue(':q_userid', '%' . $q . '%');
        $sth->bindValue(':q_acount', '%' . $q . '%');
        $sth->bindValue(':q_information', '%' . $q . '%');
        $sth->bindValue(':q_money_out', '%' . $q . '%');
        $sth->bindValue(':q_transaction_time', '%' . $q . '%');
        $sth->bindValue(':q_transaction_info', '%' . $q . '%');
        $sth->bindValue(':q_status', '%' . $q . '%');
    }
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('*')
        ->order('weight ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());

    if (!empty($q)) {
        $sth->bindValue(':q_userid', '%' . $q . '%');
        $sth->bindValue(':q_acount', '%' . $q . '%');
        $sth->bindValue(':q_information', '%' . $q . '%');
        $sth->bindValue(':q_money_out', '%' . $q . '%');
        $sth->bindValue(':q_transaction_time', '%' . $q . '%');
        $sth->bindValue(':q_transaction_info', '%' . $q . '%');
        $sth->bindValue(':q_status', '%' . $q . '%');
    }
    $sth->execute();
}

$xtpl = new XTemplate('withdrawal.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

$xtpl->assign('Q', $q);

if ($show_view) {
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    if (!empty($q)) {
        $base_url .= '&q=' . $q;
    }
    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.view.generate_page');
    }
    $number = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
    while ($view = $sth->fetch()) {
        for($i = 1; $i <= $num_items; ++$i) {
            $xtpl->assign('WEIGHT', array(
                'key' => $i,
                'title' => $i,
                'selected' => ($i == $view['weight']) ? ' selected="selected"' : ''));
            $xtpl->parse('main.view.loop.weight_loop');
        }
        $xtpl->assign('CHECK', $view['status'] == 1 ? 'checked' : '');
        $view['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }
    $xtpl->parse('main.view');
}

foreach ($global_array_money_sys as $money_sys) {
    $money_unit = array(
        'key' => $money_sys['code'],
        'title' => $money_sys['code'],
        'selected' => $money_sys['code'] == $row['money_unit'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('MONEY_UNIT', $money_unit);
    $xtpl->parse('main.money_unit');
}


if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['withdrawal'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
		}
		}