<?php

/**
 * @Project TMS Holdings
 * @Author VINADES., JSC (kid.apt@gmail.com)
 * @Copyright (C) 2016 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 29, 2010  10:42:00 PM
 */
if (! defined('NV_IS_MOD_WALLET'))
    die('Stop!!!');

require_once (NV_ROOTDIR . "/modules/" . $module_file . '/payment/' . $payment . '.config.php');
require_once (NV_ROOTDIR . "/modules/" . $module_file . '/payment/' . $payment . '.nusoap.php');
require_once (NV_ROOTDIR . "/modules/" . $module_file . '/payment/' . $payment . '.microcheckout.class.php');

// khai bao
$obj = new NL_MicroCheckout(MERCHANT_ID, MERCHANT_PASS, URL_WS);

$client_payment_return = array(
    'status' => 0, // status = 200 => khong co loi
    'message_error' => ''
);

if ($obj->checkReturnUrlAuto()) {
    $inputs = array(
        'token' => $obj->getTokenCode()
    );
    
    $result_data_payment = $obj->getExpressCheckout($inputs);
    if ($result_data_payment != false) {
        if ($result_data_payment['result_code'] != '00') {
            $client_payment_return['message_error'] = $result_data_payment['result_code'] . ' (' . $result_data_payment['result_description'] . ') ';
        } else {
            $client_payment_return['status'] = 200;
        }
    } else {
        $client_payment_return['message_error'] = sprintf($lang_module['error_connect_payment_service'], $row_payment['paymentname']);
    }
} else {
    $client_payment_return['message_error'] = sprintf($lang_module['error_connect_payment_service'], $row_payment['paymentname']);
}