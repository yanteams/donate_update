<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License GNU/GPL version 3 or any later version
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

// if (!defined('NV_IS_MOD_WALLET'))
//     die('Stop!!!');

$OAuthTokenCredential = new \PayPal\Auth\OAuthTokenCredential($payment_config['clientid'], $payment_config['secret']);
$apiContext = new \PayPal\Rest\ApiContext($OAuthTokenCredential);
$apiContext->setConfig(array(
    'mode' => $payment_config['mode'] // live or sandbox
));

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;

$payer = new Payer();
$payer->setPaymentMethod("paypal");

$item1 = new Item();
$item1->setName($post['transaction_code'])
    ->setDescription($post['transaction_info'])
    ->setCurrency('USD')
    ->setQuantity(1)
    ->setSku("1")
    ->setPrice($post['money_net']);

$itemList = new ItemList();
$itemList->setItems(array($item1));

$details = new Details();
$details->setShipping(0)
    ->setTax(0)
    ->setSubtotal($post['money_net']);

$amount = new Amount();
$amount->setCurrency('USD')
    ->setTotal($post['money_net'])
    ->setDetails($details);

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription($post['transaction_info'])
    ->setInvoiceNumber($post['transaction_code']);

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl($post['ReturnURL'])
    ->setCancelUrl($post['ReturnURL'] . '&ucancel=1');

$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));

try {
    $payment->create($apiContext);
    $url = $payment->getApprovalLink();
} catch (PayPalConnectionException $ex) {
    $errorData = nv_object2array(json_decode($ex->getData()));
    $error = $errorData['error_description'];
}
