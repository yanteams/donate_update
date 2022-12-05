<?php
header("Content-Type: text/html; charset=utf-8");
define('URL_WS', $payment_config['public_api_url']);
define('RECEIVER', $payment_config['receiver_pay']); // Email tài khoản Ngân Lượng
define('MERCHANT_ID', $payment_config['merchant_site']); // Mã kết nối
define('MERCHANT_PASS', $payment_config['secure_pass']); // Mật khẩu kết nối
