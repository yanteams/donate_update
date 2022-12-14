<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License GNU/GPL version 3 or any later version
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');
$sql_drop_module = [];
//PAYMENT
$result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $module_data . "\_money%'");
$num_table = intval($result->rowCount());

if (empty($num_table)) {
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_epay_log";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_exchange";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_exchange_log";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_money";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_money_sys";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_payment";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_payment_discount";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_smslog";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_transaction";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_orders";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_admin_groups";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_admins";
    $sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $module_data . "_ipn_logs";

    $sql_create_module = $sql_drop_module;
    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_epay_log(
      id int(11) NOT NULL AUTO_INCREMENT,
      time int(11) NOT NULL DEFAULT '0',
      telco char(3) NOT NULL DEFAULT '',
      code varchar(30) NOT NULL DEFAULT '',
      userid int(11) NOT NULL DEFAULT '0',
      status tinyint(4) NOT NULL DEFAULT '0',
      SessionID varchar(255) NOT NULL DEFAULT '',
      money_card int(11) NOT NULL DEFAULT '0',
      money_site int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (id),
      KEY userid (userid),
      KEY time (time),
      KEY telco (telco,code)
    ) ENGINE=INNODB";

    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_exchange(
      id int(10) unsigned NOT NULL AUTO_INCREMENT,
      money_unit char(3) NOT NULL DEFAULT '',
      than_unit char(3) NOT NULL DEFAULT '',
      exchange_from double NOT NULL DEFAULT '1',
      exchange_to double NOT NULL DEFAULT '1',
      time_update int(11) NOT NULL DEFAULT '0',
      status tinyint(4) NOT NULL DEFAULT '0',
      PRIMARY KEY (id),
      UNIQUE KEY type (money_unit,than_unit)
    ) ENGINE=INNODB";

    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_exchange_log(
      log_id int(11) NOT NULL AUTO_INCREMENT,
      money_unit char(3) NOT NULL DEFAULT '',
      than_unit char(3) NOT NULL DEFAULT '',
      exchange_from double NOT NULL DEFAULT '1',
      exchange_to double NOT NULL DEFAULT '1',
      time_begin int(11) NOT NULL DEFAULT '0',
      time_end int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (log_id)
    ) ENGINE=INNODB";

    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_money(
      userid int(11) NOT NULL DEFAULT '0',
      created_time int(11) NOT NULL DEFAULT '0',
      created_userid int(11) NOT NULL DEFAULT '0',
      status tinyint(4) NOT NULL DEFAULT '0',
      money_unit char(3) NOT NULL DEFAULT '',
      money_in double NOT NULL DEFAULT '0',
      money_out double NOT NULL DEFAULT '0',
      money_total double NOT NULL DEFAULT '0',
      note text NOT NULL,
      tokenkey varchar(32) NOT NULL DEFAULT '',
      UNIQUE KEY userid (userid,money_unit)
    ) ENGINE=INNODB";

    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_money_sys(
      id int(10) unsigned NOT NULL AUTO_INCREMENT,
      code char(3) NOT NULL DEFAULT '',
      currency varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (id)
    ) ENGINE=INNODB";

    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_payment(
      payment varchar(100) NOT NULL DEFAULT '',
      paymentname varchar(255) NOT NULL DEFAULT '',
      domain varchar(255) NOT NULL DEFAULT '',
      active tinyint(4) NOT NULL DEFAULT '0',
      weight int(11) NOT NULL DEFAULT '0',
      config text NOT NULL,
      discount double NOT NULL DEFAULT '0' COMMENT 'Ph?? cho nh?? cung c???p d???ch v???, ph???n n??y ch??? l??m ?????i s??? ????? th???ng k??',
      discount_transaction double NOT NULL DEFAULT '0' COMMENT 'Phi?? giao di??ch',
      images_button varchar(255) NOT NULL DEFAULT '',
      bodytext mediumtext NOT NULL,
      term mediumtext NOT NULL,
      currency_support varchar(255) NOT NULL DEFAULT '' COMMENT 'C??c lo???i ti???n t??? h??? tr??? thanh to??n',
      allowedoptionalmoney tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Cho ph??p thanh to??n s??? ti???n t??y ?? hay kh??ng',
      active_completed_email tinyint(1) NOT NULL DEFAULT '0' COMMENT 'K??ch ho???t g???i email th??ng b??o c??c giao d???ch ch??a ho??n th??nh',
      active_incomplete_email tinyint(1) NOT NULL DEFAULT '0' COMMENT 'K??ch ho???t g???i email th??ng b??o c??c giao d???ch ???? ho??n th??nh',
      PRIMARY KEY (payment)
    ) ENGINE=INNODB";

    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_payment_discount(
      payment varchar(100) NOT NULL DEFAULT '' COMMENT 'C???ng thanh to??n',
      revenue_from double NOT NULL DEFAULT '0' COMMENT 'Doanh thu t???: Quan h??? l???n h??n ho???c b???ng',
      revenue_to double NOT NULL DEFAULT '0' COMMENT 'Doanh thu ?????n: Quan h??? nh??? h??n',
      provider varchar(10) NOT NULL DEFAULT '0' COMMENT 'Nh?? cung c???p',
      discount double NOT NULL DEFAULT '0' COMMENT 'M???c ph?? %',
      UNIQUE KEY payment (payment,revenue_from,revenue_to,provider)
    ) ENGINE=INNODB";

    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_smslog(
      id int(12) unsigned NOT NULL AUTO_INCREMENT,
      User_ID varchar(15) NOT NULL DEFAULT '',
      Service_ID varchar(15) NOT NULL DEFAULT '',
      Command_Code varchar(160) NOT NULL DEFAULT '',
      Message varchar(160) NOT NULL DEFAULT '',
      Request_ID varchar(160) NOT NULL DEFAULT '',
      set_time int(11) NOT NULL DEFAULT '0',
      active tinyint(5) NOT NULL DEFAULT '0',
      client_ip varchar(25) NOT NULL DEFAULT '',
      PRIMARY KEY (id),
      KEY User_ID (User_ID),
      KEY set_time (set_time)
    ) ENGINE=INNODB";

    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_transaction(
      id int(11) NOT NULL AUTO_INCREMENT,
      created_time int(11) NOT NULL DEFAULT '0' COMMENT 'Ng??y kh???i t???o giao d???ch',
      status tinyint(4) NOT NULL DEFAULT '1' COMMENT 'T??c ?????ng: 1 c???ng ti???n, -1 tr??? ti???n',
      money_unit char(3) NOT NULL DEFAULT '',
      money_total double NOT NULL DEFAULT '0' COMMENT 'S??? ti???n th???c c???p nh???t v??o t??i kho???n th??nh vi??n',
      money_net double NOT NULL DEFAULT '0' COMMENT 'S??? ti???n th??nh vi??n th???c hi???n giao d???ch',
      money_discount double NOT NULL DEFAULT '0' COMMENT 'Ph?? doanh nghi???p ph???i tr??? cho nh?? cung c???p d???ch v???',
      money_revenue double NOT NULL DEFAULT '0' COMMENT 'L???i nhu???n m?? doanh nghi???p ?????t ???????c',
      userid int(11) NOT NULL DEFAULT '0' COMMENT 'ID th??nh vi??n c?? t??i kho???n ???????c t??c ?????ng',
      adminid int(11) NOT NULL DEFAULT '0' COMMENT 'ID admin th???c hi???n giao d???ch, n???u c?? gi?? tr??? n??y s??? kh??ng t??nh v??o doanh thu khi th???ng k??',
      order_id int(11) NOT NULL DEFAULT '0' COMMENT 'ID giao d???ch n???u l?? thanh to??n c??c ????n h??ng t??? module kh??c',
      customer_id int(11) NOT NULL DEFAULT '0' COMMENT 'ID ng?????i th???c hi???n giao d???ch (N???p ti???n v??o t??i kho???n)',
      customer_name varchar(255) NOT NULL DEFAULT '',
      customer_email varchar(255) NOT NULL DEFAULT '',
      customer_phone varchar(255) NOT NULL DEFAULT '',
      customer_address varchar(255) NOT NULL DEFAULT '',
      customer_info text NOT NULL,
      customer_anonymous int(11) NOT NULL,
      customer_company text NOT NULL,
      transaction_id varchar(255) NOT NULL DEFAULT '',
      transaction_type smallint(5) NOT NULL DEFAULT '-1' COMMENT 'Lo???i giao d???ch',
      transaction_status int(11) NOT NULL DEFAULT '0' COMMENT 'Tr???ng th??i giao d???ch ???????c quy ?????c chu???n theo module',
      transaction_time int(11) NOT NULL DEFAULT '0' COMMENT 'Th???i gian th???c hi???n thanh to??n giao d???ch',
      transaction_info text NOT NULL,
      transaction_data text NOT NULL,
      payment varchar(50) NOT NULL DEFAULT '' COMMENT 'C???ng thanh to??n s??? d???ng',
      provider varchar(50) NOT NULL DEFAULT '' COMMENT 'Nh?? cung c???p th??? s??? d???ng n???u nh?? ????y l?? c???ng thanh to??n n???p th???, n???u kh??ng c???n b??? tr???ng',
      tokenkey varchar(32) NOT NULL DEFAULT '',
      is_expired tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0: Ch??a h???t h???n, 1: H???t h???n',
      PRIMARY KEY (id),
      KEY userid (userid),
      KEY adminid (adminid),
      KEY customer_id (customer_id),
      KEY created_time (created_time),
      KEY customer_name (customer_name(191)),
      KEY customer_email (customer_email(191)),
      KEY transaction_type (transaction_type),
      KEY is_expired (is_expired)
    ) ENGINE=INNODB";

    // C??c ????n h??ng t??? module kh??c
    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_orders (
      id int(11) NOT NULL AUTO_INCREMENT,
      order_mod varchar(100) NOT NULL DEFAULT '' COMMENT 'Module title c???a module th???c hi???n ????n h??ng',
      order_id varchar(100) NOT NULL DEFAULT '' COMMENT 'ID ????n h??ng',
      order_message text NOT NULL COMMENT 'Message g???i cho c???ng thanh to??n',
      order_object varchar(250) NOT NULL DEFAULT '' COMMENT '?????i t?????ng thanh to??n v?? d???: Gi??? h??ng, s???n ph???n, ???ng d???ng...',
      order_name varchar(250) NOT NULL DEFAULT '' COMMENT 'T??n ?????i t?????ng',
      money_amount double NOT NULL DEFAULT '0' COMMENT 'S??? ti???n thanh to??n',
      money_unit varchar(3) NOT NULL DEFAULT '' COMMENT 'Lo???i ti???n t???',
      secret_code varchar(50) NOT NULL DEFAULT '' COMMENT 'M?? b?? m???t c???a m???i ????n h??ng, kh??ng tr??ng l???p',
      url_back text NOT NULL COMMENT 'D??? li???u tr??? v??? khi thanh to??n xong',
      url_admin text NOT NULL COMMENT 'Url trang qu???n tr??? ????n h??ng',
      add_time int(11) NOT NULL DEFAULT '0',
      update_time int(11) NOT NULL DEFAULT '0',
      paid_status varchar(100) NOT NULL DEFAULT '' COMMENT 'Tr???ng th??i giao d???ch',
      paid_id varchar(50) NOT NULL DEFAULT '' COMMENT 'ID giao d???ch',
      paid_time int(11) NOT NULL DEFAULT '0' COMMENT 'Th???i gian c???p nh???t c???a status kia',
      PRIMARY KEY (id),
      UNIQUE KEY order_key (order_mod, order_id),
      UNIQUE KEY secret_code (secret_code),
      KEY paid_status(paid_status)
    ) ENGINE=INNODB";

    // Ph??n quy???n theo nh??m ?????i t?????ng
    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_admin_groups (
      gid smallint(4) NOT NULL AUTO_INCREMENT,
      group_title varchar(100) NOT NULL DEFAULT '' COMMENT 'T??n nh??m',
      add_time int(11) NOT NULL DEFAULT '0',
      update_time int(11) NOT NULL DEFAULT '0',
      is_wallet tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n xem v?? c???p nh???t v?? ti???n',
      is_vtransaction tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n xem giao d???ch',
      is_mtransaction tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n xem v?? x??? l?? giao d???ch',
      is_vorder tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n xem c??c ????n h??ng k???t n???i',
      is_morder tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n xem v?? x??? l?? c??c ????n h??ng k???t n???i',
      is_exchange tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n qu???n l?? t??? gi??',
      is_money tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n qu???n l?? ti???n t???',
      is_payport tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n qu???n l?? c??c c???ng thanh to??n',
      is_configmod tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n thi???t l???p c???u h??nh module',
      is_viewstats tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quy???n xem th???ng k??',
      PRIMARY KEY (gid),
      UNIQUE KEY group_title (group_title),
      KEY is_wallet (is_wallet),
      KEY is_vtransaction (is_vtransaction),
      KEY is_mtransaction (is_mtransaction),
      KEY is_vorder (is_vorder),
      KEY is_morder (is_morder),
      KEY is_exchange (is_exchange),
      KEY is_money (is_money),
      KEY is_payport (is_payport),
      KEY is_configmod (is_configmod),
      KEY is_viewstats (is_viewstats)
    ) ENGINE=INNODB";

    // Ph??n quy???n cho t???ng admin
    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_admins (
      admin_id mediumint(8) NOT NULL,
      gid smallint(4) NOT NULL,
      add_time int(11) NOT NULL DEFAULT '0',
      update_time int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (admin_id),
      KEY gid (gid)
    ) ENGINE=INNODB";

    // Ghi log IPN Request
    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_ipn_logs (
      id int(11) NOT NULL AUTO_INCREMENT,
      userid int(11) NOT NULL DEFAULT '0' COMMENT 'ID th??nh vi??n n???u c??',
      log_ip varchar(64) NOT NULL DEFAULT '' COMMENT '?????a ch??? IP',
      log_data mediumtext NULL DEFAULT NULL COMMENT 'D??? li???u d???ng json_encode',
      request_method varchar(20) NOT NULL DEFAULT '' COMMENT 'Lo???i truy v???n',
      request_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Th???i gian log',
      user_agent text NULL DEFAULT NULL,
      PRIMARY KEY (id),
      KEY userid (userid),
      KEY log_ip (log_ip),
      KEY request_method (request_method),
      KEY request_time (request_time)
    ) ENGINE=INNODB";
}

$sql = "SELECT * FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang ='" . $lang . "' AND module='" . $module_name . "'";
$result = $db->query($sql);
if ($result->rowCount() == 0) {
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allow_smsNap', '0')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'smsConfigNap_keyword', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'smsConfigNap_port', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'smsConfigNap_prefix', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'smsConfigNap', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'minimum_amount', 'a:2:{s:3:\"VND\";s:46:\"10000,20000,50000,100000,200000,500000,1000000\";s:3:\"USD\";s:22:\"5,10,20,50,100,200,500\";}')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'payport_content', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'recharge_rate', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allow_exchange_pay', '1')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'transaction_expiration_time', '0')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'next_update_transaction_time', '0')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'accountants_emails', '')";
    $sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . "(lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'captcha_type', 'captcha')";
}
