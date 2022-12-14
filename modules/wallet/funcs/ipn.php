<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License GNU/GPL version 3 or any later version
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_IS_MOD_WALLET')) {
    die('Stop!!!');
}

/*
 * Ghi log request
 */
try {
    $array_insert = [
        'userid' => defined('NV_IS_USER') ? $user_info['userid'] : 0,
        'log_ip' => NV_CLIENT_IP,
        'log_data' => [],
        'request_method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '',
        'user_agent' => NV_USER_AGENT
    ];
    if (!empty($_GET)) {
        $array_insert['log_data']['get'] = $_GET;
    }
    if (!empty($_POST)) {
        $array_insert['log_data']['post'] = $_POST;
    }
    $array_insert['log_data'] = json_encode($array_insert['log_data']);
    $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_ipn_logs (
        userid, log_ip, log_data, request_method, request_time, user_agent
    ) VALUES (
        :userid, :log_ip, :log_data, :request_method, " . NV_CURRENTTIME . ", :user_agent
    )";
    $sth = $db->prepare($sql);
    $sth->bindParam(':userid', $array_insert['userid'], PDO::PARAM_INT);
    $sth->bindParam(':log_ip', $array_insert['log_ip'], PDO::PARAM_STR);
    $sth->bindParam(':log_data', $array_insert['log_data'], PDO::PARAM_STR, strlen($array_insert['log_data']));
    $sth->bindParam(':request_method', $array_insert['request_method'], PDO::PARAM_STR);
    $sth->bindParam(':user_agent', $array_insert['user_agent'], PDO::PARAM_STR, strlen($array_insert['user_agent']));
    $sth->execute();
    unset($array_insert, $sth);
} catch (Exception $exception) {
    trigger_error(print_r($exception, true));
}

$payment = $nv_Request->get_title('payment', 'get', '');
if (!isset($global_array_payments[$payment]) or !file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.ipn_get.php')) {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
}

// C??c bi???n d??ng cho c???ng thanh to??n
$row_payment = $global_array_payments[$payment];
$payment_config = unserialize(nv_base64_decode($row_payment['config']));
$payment_config['paymentname'] = $row_payment['paymentname'];
$payment_config['domain'] = $row_payment['domain'];

// N???u c?? l???i th?? ?????t v??o bi???n n??y
$error = '';

// D??? li???u tr??? v??? ?????t v??o bi???n n??y
$responseData = [
    'ordertype' => '', // Ki???u giao d???ch: pay l?? thanh to??n c??c ????n h??ng kh??c, recharge l?? n???p ti???n v??o v??
    'orderid' => '', // Ki???u text, ID c???a giao d???ch ???????c l??u tr?????c v??o CSDL d??ng ????? c???p nh???t thanh to??n
    'transaction_id' => '', // Ki???u text, ID giao d???ch tr??n c???ng thanh to??n
    'transaction_status' => 0, // Ki???u s???, tr???ng th??i giao d???ch quy chu???n
    'transaction_time' => 0, // Ki???u s???, th???i gian giao d???ch
    'transaction_data' => '' // Ki???u text, c?? th??? l?? serialize array
];

// G???i file x??? l?? d??? li???u tr??? v???
require NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".ipn_get.php";

// Th??ng tin tr??? v??? ????? c???ng thanh to??n x??? l?? ti???p (Xu???t th??ng tin cho b??n c???ng thanh to??n)
/**
 * Quy chu???n:
 * 99 -> L???i kh??ng x??c ?????nh
 * 0 => Kh??ng t??m th???y giao d???ch trong CSDL
 * 1 => Giao d???ch ???? ???????c x??? l?? tr?????c ????
 * 2 => Kh??ng th??? c???p nh???t tr???ng th??i giao d???ch
 * 4 => C???p nh???t tr???ng th??i giao d???ch th??nh c??ng
 * 5 => S??? ti???n Kh??ng h???p l???
 */
$walletReturnCode = 99;

// Ki???m tra ????n h??ng
if ($responseData['ordertype'] == 'pay') {
    // L???y giao d???ch ???? l??u v??o CSDL tr?????c ????
    $stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE id = :id");
    $stmt->bindParam(':id', $responseData['orderid'], PDO::PARAM_STR);
    $stmt->execute();
    $transaction = $stmt->fetch();
    if (empty($transaction)) {
        // Kh??ng t??m th???y giao d???ch
        $walletReturnCode = 0;
    } else {
        // C??c ????n h??ng
        $stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE id = :id");
        $stmt->bindParam(':id', $transaction['order_id'], PDO::PARAM_STR);
        $stmt->execute();
        $order_info = $stmt->fetch();
        if (empty($order_info)) {
            // Kh??ng t??m th???y giao d???ch
            $walletReturnCode = 0;
        } else {
            // Giao d???ch ???? ???????c x??? l??
            if ($order_info['paid_status'] != 0 or $transaction['transaction_status'] != 0) {
                // Giao d???ch ???? ???????c x??? l??
                $walletReturnCode = 1;
            } elseif (floatval($order_info['money_amount']) != $responseData['amount']) {
                // S??? ti???n kh??ng h???p l???
                $walletReturnCode = 5;

                // C???p nh???t tr???ng th??i th???t b???i
                $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
                    transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = 6,
                    transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
                WHERE id = ' . $transaction['id'];
                $db->exec($sql);
            } else {
                // C???p nh???t l???i giao d???ch
                $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
                    transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = ' . $responseData['transaction_status'] . ',
                    transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
                WHERE id = ' . $transaction['id'];
                if (!$db->exec($sql)) {
                    $walletReturnCode = 2;
                } else {
                    $check = $db->exec("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET
                        paid_status=" . $responseData['transaction_status'] . ",
                        paid_id=" . $db->quote(vsprintf('GD%010s', $transaction['id'])) . ",
                        paid_time=" . $responseData['transaction_time'] . "
                    WHERE id=" . $order_info['id']);
                    if (!$check) {
                        $walletReturnCode = 2;
                    } else {
                        $nv_Cache->delMod($module_name);
                        $walletReturnCode = 4;
                    }
                }
            }
        }
    }
} else {
    // N???p ti???n v??o t??i kho???n
    $stmt = $db->prepare("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE id = :id");
    $stmt->bindParam(':id', $responseData['orderid'], PDO::PARAM_STR);
    $stmt->execute();
    $order_info = $stmt->fetch();
    if (empty($order_info)) {
        // Kh??ng t??m th???y giao d???ch
        $walletReturnCode = 0;
    } else {
        // Giao d???ch ???? ???????c x??? l??
        if ($order_info['transaction_status'] != 0) {
            // Giao d???ch ???? ???????c x??? l??
            $walletReturnCode = 1;
        } elseif (floatval($order_info['money_net']) != $responseData['amount']) {
            // S??? ti???n kh??ng h???p l???
            $walletReturnCode = 5;

            // C???p nh???t l???i giao d???ch th???t b???i
            $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
                transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = 6,
                transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
            WHERE id = ' . $order_info['id'];
            $db->exec($sql);
        } else {
            $sql = 'UPDATE ' . $db_config['prefix'] . "_" . $module_data . '_transaction SET
                transaction_id = ' . $db->quote($responseData['transaction_id']) . ', transaction_status = ' . $responseData['transaction_status'] . ',
                transaction_time = ' . $responseData['transaction_time'] . ', transaction_data = ' . $db->quote($responseData['transaction_data']) . '
            WHERE id = ' . $order_info['id'];

            if (!$db->exec($sql)) {
                $walletReturnCode = 2;
            } else {
                $walletReturnCode = 4;

                // C???p nh???t s??? ti???n v??o t??i kho???n t???i ????y
                if ($responseData['transaction_status'] == 4) {
                    $check = nv_wallet_money_in($order_info['userid'], $order_info['money_unit'], $order_info['money_total']);
                    if (!$check) {
                        $walletReturnCode = 2;
                    }
                }

                $nv_Cache->delMod($module_name);
            }
        }
    }
}

// G???i file tr??? k???t qu??? cho c???ng thanh to??n
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.ipn_res.php')) {
    require NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".ipn_res.php";
}

nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
