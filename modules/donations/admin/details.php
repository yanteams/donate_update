<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_details  WHERE id = ' . $db->quote($id));
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete Details', 'ID: ' . $id, $admin_info['userid']);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $row['paymentid'] = $nv_Request->get_textarea('paymentid', '', NV_ALLOWED_HTML_TAGS);
    $row['amount'] = $nv_Request->get_int('amount', 'post', 0);
    $row['name'] = $nv_Request->get_title('name', 'post', '');
    $row['email'] = $nv_Request->get_title('email', 'post', '');
    $row['phone'] = $nv_Request->get_title('phone', 'post', '');
    $row['ghichu'] = $nv_Request->get_title('ghichu', 'post', '');
    $row['anonymous'] = $nv_Request->get_int('anonymous', 'post', 0);
    $row['status'] = $nv_Request->get_int('status', 'post', 0);
    $row['created_time'] = $nv_Request->get_int('created_time', 'post', 0);
    $row['money_net'] = $nv_Request->get_title('money_net', 'post', '');
    $row['paid_status'] = $nv_Request->get_int('paid_status', 'post', 0);
    $row['paid_id'] = $nv_Request->get_int('paid_id', 'post', 0);
    $row['paid_time'] = $nv_Request->get_int('paid_time', 'post', 0);
    $row['paid_data'] = $nv_Request->get_title('paid_data', 'post', '');
    $row['transaction_id'] = $nv_Request->get_int('transaction_id', 'post', 0);
    $row['transaction_status'] = $nv_Request->get_title('transaction_status', 'post', '');
    $row['transaction_time'] = $nv_Request->get_int('transaction_time', 'post', 0);
    $row['transaction_data'] = $nv_Request->get_title('transaction_data', 'post', '');

    if (empty($row['amount'])) {
        $error[] = $lang_module['error_required_amount'];
    } elseif (empty($row['name'])) {
        $error[] = $lang_module['error_required_name'];
    } elseif (empty($row['email'])) {
        $error[] = $lang_module['error_required_email'];
    } elseif (empty($row['phone'])) {
        $error[] = $lang_module['error_required_phone'];
    }

    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_details (paymentid, amount, name, email, phone, ghichu, anonymous, status, created_time, money_net, paid_status, paid_id, paid_time, paid_data, transaction_id, transaction_status, transaction_time, transaction_data) VALUES (:paymentid, :amount, :name, :email, :phone, :ghichu, :anonymous, :status, :created_time, :money_net, :paid_status, :paid_id, :paid_time, :paid_data, :transaction_id, :transaction_status, :transaction_time, :transaction_data)');
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_details SET paymentid = :paymentid, amount = :amount, name = :name, email = :email, phone = :phone, ghichu = :ghichu, anonymous = :anonymous, status = :status, created_time = :created_time, money_net = :money_net, paid_status = :paid_status, paid_id = :paid_id, paid_time = :paid_time, paid_data = :paid_data, transaction_id = :transaction_id, transaction_status = :transaction_status, transaction_time = :transaction_time, transaction_data = :transaction_data WHERE id=' . $row['id']);
            }
            $stmt->bindParam(':paymentid', $row['paymentid'], PDO::PARAM_STR, strlen($row['paymentid']));
            $stmt->bindParam(':amount', $row['amount'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $row['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
            $stmt->bindParam(':phone', $row['phone'], PDO::PARAM_STR);
            $stmt->bindParam(':ghichu', $row['ghichu'], PDO::PARAM_STR);
            $stmt->bindParam(':anonymous', $row['anonymous'], PDO::PARAM_INT);
            $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
            $stmt->bindParam(':created_time', $row['created_time'], PDO::PARAM_INT);
            $stmt->bindParam(':money_net', $row['money_net'], PDO::PARAM_STR);
            $stmt->bindParam(':paid_status', $row['paid_status'], PDO::PARAM_INT);
            $stmt->bindParam(':paid_id', $row['paid_id'], PDO::PARAM_INT);
            $stmt->bindParam(':paid_time', $row['paid_time'], PDO::PARAM_INT);
            $stmt->bindParam(':paid_data', $row['paid_data'], PDO::PARAM_STR);
            $stmt->bindParam(':transaction_id', $row['transaction_id'], PDO::PARAM_INT);
            $stmt->bindParam(':transaction_status', $row['transaction_status'], PDO::PARAM_STR);
            $stmt->bindParam(':transaction_time', $row['transaction_time'], PDO::PARAM_INT);
            $stmt->bindParam(':transaction_data', $row['transaction_data'], PDO::PARAM_STR);

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                if (empty($row['id'])) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Details', ' ', $admin_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Details', 'ID: ' . $row['id'], $admin_info['userid']);
                }
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            }
        } catch(PDOException $e) {
            trigger_error($e->getMessage());
            die($e->getMessage()); //Remove this line after checks finished
        }
    }
} elseif ($row['id'] > 0) {
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_details WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
} else {
    $row['id'] = 0;
    $row['paymentid'] = '';
    $row['amount'] = 0;
    $row['name'] = '';
    $row['email'] = '';
    $row['phone'] = '';
    $row['ghichu'] = '';
    $row['anonymous'] = 0;
    $row['status'] = 1;
    $row['created_time'] = 0;
    $row['money_net'] = '';
    $row['paid_status'] = 0;
    $row['paid_id'] = 0;
    $row['paid_time'] = 0;
    $row['paid_data'] = '';
    $row['transaction_id'] = 0;
    $row['transaction_status'] = '';
    $row['transaction_time'] = 0;
    $row['transaction_data'] = '';
}

$row['paymentid'] = nv_htmlspecialchars(nv_br2nl($row['paymentid']));


$q = $nv_Request->get_title('q', 'post,get');

// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get')) {
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from('' . NV_PREFIXLANG . '_' . $module_data . '_details');

    if (!empty($q)) {
        $db->where('amount LIKE :q_amount OR name LIKE :q_name OR email LIKE :q_email OR phone LIKE :q_phone');
    }
    $sth = $db->prepare($db->sql());

    if (!empty($q)) {
        $sth->bindValue(':q_amount', '%' . $q . '%');
        $sth->bindValue(':q_name', '%' . $q . '%');
        $sth->bindValue(':q_email', '%' . $q . '%');
        $sth->bindValue(':q_phone', '%' . $q . '%');
    }
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('*')
        ->order('id DESC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());

    if (!empty($q)) {
        $sth->bindValue(':q_amount', '%' . $q . '%');
        $sth->bindValue(':q_name', '%' . $q . '%');
        $sth->bindValue(':q_email', '%' . $q . '%');
        $sth->bindValue(':q_phone', '%' . $q . '%');
    }
    $sth->execute();
}

$xtpl = new XTemplate('details.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
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
        $view['number'] = $number++;
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

$page_title = $lang_module['details'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
