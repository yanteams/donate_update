<?php

/**
 * @Project TMS HOLDINGS
 * @Author TMS HOLDINGS (contact@tms.vn)
 * @Copyright (C) 2021 TMS HOLDINGS. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 01/01/2021 09:47
 */
if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');
$mod = $nv_Request->get_string('mod', 'post, get','');

if($mod == 'check') {
	    $userid = $nv_Request->get_string('userid', 'post, get','');
    $dayfrom = $nv_Request->get_string('dayfrom', 'post, get','');

	if($dayfrom!=0){
		$dayfrom=strtotime($dayfrom);
		$dayfrom1=$dayfrom+(23*60*60)+59*60+59;
	}else{
		$dayfrom =0;
	}
    $dayto = $nv_Request->get_string('dayto', 'post, get','');

	if($dayto!=0){
		$dayto=strtotime($dayto);
		$dayto1=$dayto+(23*60*60)+59*60+59;
		
	}else{
		$dayto =0;
	}
		$array_userid_users = array();
$_sql = 'SELECT userid,username FROM '.NV_USERS_GLOBALTABLE.'';
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_userid_users[$_row['userid']] = $_row;
}
if($dayto == 0){
	$row1 = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal WHERE transaction_time between '.$dayfrom.' and '.$dayfrom1.'')->fetchAll();
}elseif($dayfrom == 0){
	$row1 = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal WHERE transaction_time between '.$dayto.' and '.$dayto1.'')->fetchAll();
}else{
		$row1 = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal WHERE transaction_time between '.$dayfrom.' and '.$dayto1.'')->fetchAll();
}
				$html .='
<form method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>'.$lang_module['userid'].'</th>
                    <th>'.$lang_module['acount'].'</th>
                    <th>'.$lang_module['information'].'</th>
                    <th>'.$lang_module['money_out'].'</th>
                    <th>'.$lang_module['transaction_time'].'</th>
                    <th>'.$lang_module['transaction_info'].'</th>
					<td>'.$lang_module['transaction_time_update'].' </td>
                    <td>'.$lang_module['userid_update'].' </td>
                    <th class="w100 text-center">'.$lang_module['active'].'</th>
                </tr>
            </thead>';
		foreach($row1 as $row){
				$row2 = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_acount_nganhang WHERE id='.$row['idtaikhoan'].'')->fetchAll();
				foreach($row2  as $row3){
						if($row3['userid'] == $userid){
								$row['userid'] = $array_userid_users[$row3['userid']]['username'];
								if($row['userid_update']==0){
									$row['userid_update']=$lang_module['update_status'];
								}else{
									$row['userid_update'] = $array_userid_users[$row['userid_update']]['username'];
								}
					 
								 if($row['transaction_time_update']==0){
									 $row['transaction_time_update']=$lang_module['update_status'];
								 }else{
									  $row['transaction_time_update']=date('d-m-Y H:i:s',$row['transaction_time_update']);
								 }
								$row['money_out']=number_format($row['money_out']).' '.$row['money_unit'].'';
								$row['transaction_time']=date('d-m-Y H:i:s',$row['transaction_time']);
								if($row['transaction_info'] == '0'){
									$row['transaction_info']='Hiện không có thông tin';
								}
								 if($row['status']!=0){
									  $check='disabled';
								  }else{		
										$check='';
								  }
								   if($row['status'] == 0){
										  $title ='Đang chờ xử lý';
									  }
									  else if($row['status'] == 1){
										 $title ='Đã chuyển khoản cho khách';
									  }else if($row['status'] == 2){
										 $title ='Khách hủy giao dịch';
									  }
							$html .='<tbody>
								<tr>
							   
								<td> '.$row['userid'].' </td>
								<td> '.$row3['acount'].' </td>
								<td> '.$row3['information'].' </td>
								<td> '.$row['money_out'].' </td>
								<td> '.$row['transaction_time'].' </td>
								<td> '.$row['transaction_info'].' </td>
								<td> '.$row['transaction_time_update'].' </td>
								<td> '.$row['userid_update'].' </td>

								<td style="width: 15%" id="hienthi">
									<select class="form-control" '.$check.' id="change_status_'.$row['id'].'" name="status" onchange="nv_change_status('.$row['id'].');">
										<option value="'.$row['status'].'">'. $title .'</option>
									</select>
								</td>
							</tr>
						</tbody>';
				}else if($userid == 0){
					$row['userid'] = $array_userid_users[$row3['userid']]['username'];
								if($row['userid_update']==0){
									$row['userid_update']=$lang_module['update_status'];
								}else{
									$row['userid_update'] = $array_userid_users[$row['userid_update']]['username'];
								}
					 
								 if($row['transaction_time_update']==0){
									 $row['transaction_time_update']=$lang_module['update_status'];
								 }else{
									  $row['transaction_time_update']=date('d-m-Y H:i:s',$row['transaction_time_update']);
								 }
								$row['money_out']=number_format($row['money_out']).' '.$row['money_unit'].'';
								$row['transaction_time']=date('d-m-Y H:i:s',$row['transaction_time']);
								if($row['transaction_info'] == '0'){
									$row['transaction_info']='Hiện không có thông tin';
								}
								 if($row['status']!=0){
									  $check='disabled';
								  }else{		
										$check='';
								  }
								   if($row['status'] == 0){
										  $title ='Đang chờ xử lý';
									  }
									  else if($row['status'] == 1){
										 $title ='Đã chuyển khoản cho khách';
									  }else if($row['status'] == 2){
										 $title ='Khách hủy giao dịch';
									  }
							$html .='<tbody>
								<tr>
							   
								<td> '.$row['userid'].' </td>
								<td> '.$row3['acount'].' </td>
								<td> '.$row3['information'].' </td>
								<td> '.$row['money_out'].' </td>
								<td> '.$row['transaction_time'].' </td>
								<td> '.$row['transaction_info'].' </td>
								<td> '.$row['transaction_time_update'].' </td>
								<td> '.$row['userid_update'].' </td>

								<td style="width: 15%" id="hienthi">
									<select class="form-control" '.$check.' id="change_status_'.$row['id'].'" name="status" onchange="nv_change_status('.$row['id'].');">
										<option value="'.$row['status'].'">'.$title.'</option>
									</select>
								</td>
							</tr>
						</tbody>';
				}
			}
		};
		$html .='</table>
    </div>
		</form>';
		 $contents1=array(
					"status" => "OK",
					"html"=>$html
					);
    echo json_encode($contents1);
	die();

}

// Change status
if ($nv_Request->isset_request('change_status', 'post, get')) {
    $id = $nv_Request->get_int('id', 'post, get', 0);
	$status= $nv_Request->get_int('status', 'post, get', 0);
    $content = 'NO_' . $id;

    $query = 'SELECT status,idtaikhoan,money_out,transaction_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal WHERE id=' . $id;
    $row = $db->query($query)->fetch();
	$row['date1']=NV_CURRENTTIME;
	$row['date']=date('d/m/Y H:i:s',$row['date1']);
		 // Xác định tài khoản tác động
			$sql = "SELECT userid FROM " . $db_config['prefix'] . "_" . $module_data . "_acount_nganhang  WHERE id=:idtaikhoan";
			$sth = $db->prepare($sql);
			$sth->bindParam(':idtaikhoan', $row['idtaikhoan'], PDO::PARAM_STR);
			$sth->execute();
			$account_info = $sth->fetch();
			
			$sql1 = "SELECT userid, username, first_name, last_name, email FROM " . NV_USERS_GLOBALTABLE . " WHERE userid=:userid";
			$sth2 = $db->prepare($sql1);
			$sth2->bindParam(':userid', $account_info['userid'], PDO::PARAM_STR);
			$sth2->execute();
			$account_info2 = $sth2->fetch();
        $query = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal SET status=' .$status . ',transaction_time_update='.$row['date1'].',userid_update='.$account_info2['userid'].' WHERE id=' . $id;
        $db->query($query);
        $content = 'OK_' . $id;
	
	if($status == 2){
		$sql_withdrawal_update=$db->query('UPDATE '.MODULE_WALLET.'_transaction  SET transaction_status =3 where id='.$row['transaction_id']);
		$message_status='Khách hủy giao dịch';
		 
			$row['money_out2']=number_format($row['money_out']);
			require_once NV_ROOTDIR . '/modules/wallet/wallet.class.php';
			$wallet = new nukeviet_wallet();
			$message = 'Hệ thống hủy giao dịch rút tiền của user'.' '.$account_info2['username'].' với số tiền'.' '.$row['money_out2'].' '.' vào ngày'.' '.$row['date'];
			$checkUpdate = $wallet->update($row['money_out'], 'VND', $account_info['userid'], $message,true);
			
	}else {
		$sql_withdrawal_update=$db->query('UPDATE '.MODULE_WALLET.'_transaction  SET transaction_status =4 where id='.$row['transaction_id']);
	}

 echo $content;
 die();
}

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

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $weight=0;
        $sql = 'SELECT weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal WHERE id =' . $db->quote($id);
        $result = $db->query($sql);
        list($weight) = $result->fetch(3);
        
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal  WHERE id = ' . $db->quote($id));
        if ($weight > 0)         {
            $sql = 'SELECT id, weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal WHERE weight >' . $weight;
            $result = $db->query($sql);
            while (list($id, $weight) = $result->fetch(3))
            {
                $weight--;
                $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal SET weight=' . $weight . ' WHERE id=' . intval($id));
            }
        }
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Withdrawal', 'ID: ' . $id, $admin_info['userid']);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $row['userid'] = $nv_Request->get_int('userid', 'post', 0);
    $row['acount'] = $nv_Request->get_title('acount', 'post', '');
    $row['information'] = $nv_Request->get_title('information', 'post', '');
    $row['money_out'] = $nv_Request->get_title('money_out', 'post', '');
    $row['transaction_info'] = $nv_Request->get_textarea('transaction_info', '', NV_ALLOWED_HTML_TAGS);

    if (empty($row['userid'])) {
        $error[] = $lang_module['error_required_userid'];
    } elseif (empty($row['acount'])) {
        $error[] = $lang_module['error_required_acount'];
    } elseif (empty($row['information'])) {
        $error[] = $lang_module['error_required_information'];
    } elseif (empty($row['money_out'])) {
        $error[] = $lang_module['error_required_money_out'];
    }

    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $row['transaction_time'] = 0;

                $stmt = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal (userid, acount, information, money_out, transaction_time, transaction_info, weight, status) VALUES (:userid, :acount, :information, :money_out, :transaction_time, :transaction_info, :weight, :status)');

                $stmt->bindParam(':transaction_time', $row['transaction_time'], PDO::PARAM_INT);
                $weight = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal')->fetchColumn();
                $weight = intval($weight) + 1;
                $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);

                $stmt->bindValue(':status', 1, PDO::PARAM_INT);


            } else {
                $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal SET userid = :userid, acount = :acount, information = :information, money_out = :money_out, transaction_info = :transaction_info WHERE id=' . $row['id']);
            }
            $stmt->bindParam(':userid', $row['userid'], PDO::PARAM_INT);
            $stmt->bindParam(':acount', $row['acount'], PDO::PARAM_STR);
            $stmt->bindParam(':information', $row['information'], PDO::PARAM_STR);
            $stmt->bindParam(':money_out', $row['money_out'], PDO::PARAM_STR);
            $stmt->bindParam(':transaction_info', $row['transaction_info'], PDO::PARAM_STR, strlen($row['transaction_info']));

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                if (empty($row['id'])) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Withdrawal', ' ', $admin_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Withdrawal', 'ID: ' . $row['id'], $admin_info['userid']);
                }

                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            }
        } catch(PDOException $e) {
            trigger_error($e->getMessage());
            die($e->getMessage()); //Remove this line after checks finished
        }
    }
} elseif ($row['id'] > 0) {
    $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_withdrawal WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
} else {
    $row['id'] = 0;
    $row['userid'] = 0;
    $row['acount'] = '0';
    $row['information'] = '0';
    $row['money_out'] = '0';
    $row['transaction_info'] = '';
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
        ->from('' . $db_config['prefix'] . '_' . $module_data . '_withdrawal t1')
		->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_acount_nganhang t2 ON t1.idtaikhoan = t2.id');
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

    $db->select('t1.id,t2.userid,t2.acount,t2.information,t1.weight,t1.status,t1.money_out,t1.money_unit,t1.transaction_time,t1.transaction_time_update,t1.userid_update,t1.transaction_info')
        ->order('t1.weight ASC')
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
$array_userid_users = array();
$_sql = 'SELECT userid,username FROM '.NV_USERS_GLOBALTABLE.'';
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_userid_users[$_row['userid']] = $_row;
}
$xtpl = new XTemplate('withdrawal.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
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

$xtpl->assign('Q', $q);

if ($show_view) {
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
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
		  for($m = 0; $m<3; ++$m) {

			  if($view['status']!=0){
				  $check='disabled';
			  }else{		
					$check='';
					}

			  if($m == 0){
				  $title ='Đang chờ xử lý';
			  }
			  else if($m == 1){
			  	 $title ='Đã chuyển khoản cho khách';
			  }else if($m == 2){
				 $title ='Khách hủy giao dịch';
			  }
            $xtpl->assign('status', array(
                'key' => $m,
                'title' => $title,
                'selected' => ($m == $view['status']) ? ' selected="selected"' : ''));
													  $xtpl->assign('check', $check);

            $xtpl->parse('main.view.loop.status_loop');
        }
		 $view['userid'] = $array_userid_users[$view['userid']]['username'];
		 if($view['userid_update']==0){
			 $view['userid_update']=$lang_module['update_status'];
		 }else{
			$view['userid_update'] = $array_userid_users[$view['userid_update']]['username'];
		 }
		 
		 if($view['transaction_time_update']==0){
			 $view['transaction_time_update']=$lang_module['update_status'];
		 }else{
			  $view['transaction_time_update']=date('d-m-Y H:i:s',$view['transaction_time_update']);
		 }
		$view['money_out']=number_format($view['money_out']).' '.$view['money_unit'].'';
		$view['transaction_time']=date('d-m-Y H:i:s',$view['transaction_time']);
		if($view['transaction_info'] == '0'){
			$view['transaction_info']='Hiện không có thông tin';
		}
        $xtpl->assign('CHECK', $view['status'] == 1 ? 'checked' : '');
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }
    $xtpl->parse('main.view');
}


if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['withdrawal'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
