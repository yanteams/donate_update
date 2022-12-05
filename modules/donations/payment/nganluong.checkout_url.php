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

$array_return_ws = array(
    'error' => '',
    'statuscode' => 0
);

require_once (NV_ROOTDIR . "/modules/" . $module_file . '/payment/nganluong.config.php');
require_once (NV_ROOTDIR . "/modules/" . $module_file . '/payment/nganluong.nusoap.php');
require_once (NV_ROOTDIR . "/modules/" . $module_file . '/payment/nganluong.microcheckout.class.php');

$items[0] = array(
    'item_name' => sprintf($lang_module['info_order_payment'], $row_transaction['customer_name'], $global_config['site_name']),
    'item_quanty' => 1,
    'item_amount' => $row_transaction['money_total']
);

$return_url = NV_MAIN_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=complete&act=sucses&payment=nganluong';
$cancel_url = NV_MAIN_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=complete&act=cancel&payment=nganluong';
// $receiver = '';
$inputs = array(
    'receiver' => RECEIVER,
    'order_code' => $row_transaction['id'], // ma id tai bang transction
    'amount' => $row_transaction['money_total'],
    'currency_code' => 'vnd',
    'tax_amount' => '0',
    'discount_amount' => '0',
    'fee_shipping' => '0',
    'request_confirm_shipping' => '0',
    'no_shipping' => '1',
    'return_url' => $return_url,
    'cancel_url' => $cancel_url,
    'language' => 'vi',
    'token' => '',
    'items' => $items
);
$link_checkout = '';
$obj = new NL_MicroCheckout(MERCHANT_ID, MERCHANT_PASS, URL_WS);
$result = $obj->setExpressCheckoutPayment($inputs);

if ($result != false) {
    if ($result['result_code'] == '00') {
        $array_return_ws['statuscode'] = 200;
        $array_return_ws['link'] = $result['link_checkout'];
    } else {
        $array_return_ws['statuscode'] = $result['result_code'];
        $array_return_ws['error'] = $result['result_description'];
    }
} else {
    $array_return_ws['statuscode'] = 400;
    $array_return_ws['error'] = 'Lỗi kết nối tới cổng thanh toán Ngân Lượng';
}
