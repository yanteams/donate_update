<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');
$array_table = [
    'cat',
    'tags',
    'tags_id'
];
$result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $module_data . "\_money%'");
$num_table = intval($result->rowCount());

$table = $db_config['prefix'] . '_' . $lang . '_' . $module_data;
$result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($table . '_%'));
while ($item = $result->fetch()) {
    $name = substr($item['name'], strlen($table) + 1);
    if (preg_match('/^' . $db_config['prefix'] . '\_' . $lang . '\_' . $module_data . '\_/', $item['name']) and (preg_match('/^([0-9]+)$/', $name) or in_array($name, $array_table, true) or preg_match('/^bodyhtml\_([0-9]+)$/', $name))) {
        $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $item['name'];
    }
}

$result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_comment'");
$rows = $result->fetchAll();
if (sizeof($rows)) {
    $sql_drop_module[] = 'DELETE FROM ' . $db_config['prefix'] . '_' . $lang . "_comment WHERE module='" . $module_name . "'";
}

$sql_drop_module = array();

$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "(
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    alias varchar(255) NOT NULL,
    catid int(11) NOT NULL DEFAULT '0',
    groupid int(11) NOT NULL DEFAULT '0',
    hometext mediumtext NOT NULL,
    bodytext text NOT NULL,
    admin_id mediumint(8) NOT NULL DEFAULT '0',
    addtime int(11) unsigned NOT NULL DEFAULT '0',
    edittime int(11) unsigned NOT NULL DEFAULT '0',
    exptime int(11) unsigned NOT NULL DEFAULT '0',
    code varchar(255) NOT NULL DEFAULT '',
    area double NOT NULL DEFAULT '0',
    size_v double unsigned NOT NULL DEFAULT '0',
    size_h double unsigned NOT NULL DEFAULT '0',
    price double NOT NULL DEFAULT '0',
    price_time tinyint(1) unsigned NOT NULL DEFAULT '0',
    money_unit char(3) NOT NULL,
    typeid smallint(4) unsigned NOT NULL DEFAULT '0',
    projectid mediumint(8) unsigned NOT NULL DEFAULT '0',
    way_id smallint(4) unsigned NOT NULL DEFAULT '0',
    legal_id smallint(4) unsigned NOT NULL DEFAULT '0',
    homeimgfile varchar(255) NOT NULL DEFAULT '',
    homeimgthumb tinyint(4) NOT NULL DEFAULT '0',
    homeimgalt varchar(255) NOT NULL,
    front double unsigned NOT NULL DEFAULT '0',
    road double unsigned NOT NULL DEFAULT '0',
    livingroom int(10) NOT NULL DEFAULT '1',
    bedroom int(10) NOT NULL DEFAULT '1',
    bathroom int(10) NOT NULL DEFAULT '1',
    furniture varchar(250) DEFAULT NULL,
    convenient varchar(250) DEFAULT NULL,
    structure tinytext NOT NULL,
    type tinytext NOT NULL,
    provinceid mediumint(4) unsigned NOT NULL DEFAULT '0',
    districtid mediumint(8) unsigned NOT NULL DEFAULT '0',
    wardid mediumint(8) unsigned NOT NULL DEFAULT '0',
    address varchar(255) NOT NULL,
    maps tinytext NOT NULL,
    floor int(11) unsigned NOT NULL DEFAULT '1',
    num_room int(11) unsigned NOT NULL DEFAULT '1',
    inhome tinyint(1) unsigned NOT NULL DEFAULT '0',
    allowed_comm tinyint(1) unsigned NOT NULL DEFAULT '0',
    hitstotal mediumint(8) unsigned NOT NULL DEFAULT '0',
    hits_phone mediumint(8) unsigned NOT NULL DEFAULT '0',
    showprice tinyint(2) NOT NULL DEFAULT '0',
    contact_fullname varchar(150) NOT NULL,
    contact_email varchar(100) NOT NULL,
    contact_phone varchar(20) NOT NULL,
    contact_address varchar(255) NOT NULL,
    prior int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Ưu tiên',
    ordertime int(11) unsigned NOT NULL DEFAULT '0',
    is_queue tinyint(1) unsigned NOT NULL DEFAULT '0',
    status_admin tinyint(1) unsigned NOT NULL DEFAULT '1',
    status tinyint(1) NOT NULL DEFAULT '1',
    admin_duyet tinyint(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (id),
    KEY catid (catid),
    KEY admin_id (admin_id)
  ) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_details (
 id int(11) NOT NULL AUTO_INCREMENT,
 paymentid varchar(255) NOT NULL,
 amount int(11) NOT NULL,
 name varchar(255) NOT NULL,
 email varchar(255) NOT NULL,
 phone varchar(255) NOT NULL,
 ghichu varchar(255) NOT NULL,
 anonymous int(11) NOT NULL,
 status int(11) NOT NULL DEFAULT 1,
 created_time int(11) NOT NULL COMMENT 'Ngày khởi tạo giao dịch',
 money_net double NOT NULL COMMENT 'Số tiền thành viên thực hiện giao dịch',
 paid_status int(11) NOT NULL,
 paid_id int(11) NOT NULL,
 paid_time int(11) NOT NULL,
 paid_data varchar(255) NOT NULL,
 transaction_id int(11) NOT NULL,
 transaction_status varchar(255) NOT NULL,
 transaction_time int(11) NOT NULL,
 transaction_data varchar(255) NOT NULL,
 PRIMARY KEY (id)
) ENGINE=MyISAM;";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_admins(
  userid mediumint(8) unsigned NOT NULL DEFAULT '0',
  provinceid mediumint(4) NOT NULL DEFAULT '0',
  admin tinyint(4) NOT NULL DEFAULT '0',
  add_item tinyint(4) NOT NULL DEFAULT '0',
  pub_item tinyint(4) NOT NULL DEFAULT '0',
  edit_item tinyint(4) NOT NULL DEFAULT '0',
  del_item tinyint(4) NOT NULL DEFAULT '0',
  app_item tinyint(4) NOT NULL DEFAULT '0',
  UNIQUE KEY userid (userid,provinceid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block(
  bid smallint(5) unsigned NOT NULL,
  id int(11) unsigned NOT NULL,
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  exptime int(11) unsigned NOT NULL DEFAULT '0',
  refresh_time_last int(11) unsigned NOT NULL DEFAULT '0',
  refresh_time_next int(11) unsigned NOT NULL DEFAULT '0',
  refresh_lasttime int(11) unsigned NOT NULL DEFAULT '0',
  weight int(11) unsigned NOT NULL,
  UNIQUE KEY bid (bid,id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block_cat(
  bid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  useradd tinyint(1) unsigned NOT NULL DEFAULT '0',
  prior smallint(5) unsigned NOT NULL DEFAULT '0',
  adddefault tinyint(4) NOT NULL DEFAULT '0',
  numbers smallint(5) NOT NULL DEFAULT '10',
  title varchar(250) NOT NULL DEFAULT '',
  alias varchar(250) NOT NULL DEFAULT '',
  image varchar(255) DEFAULT '',
  description varchar(255) DEFAULT '',
  color varchar(10) NOT NULL,
  groups smallint(5) NOT NULL,
  weight smallint(5) NOT NULL DEFAULT '0',
  keywords text,
  add_time int(11) NOT NULL DEFAULT '0',
  edit_time int(11) NOT NULL DEFAULT '0',
  cron_time int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (bid),
  UNIQUE KEY title (title),
  UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories(
  id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  parentid smallint(5) unsigned NOT NULL,
  title varchar(250) NOT NULL,
  alias varchar(250) NOT NULL,
  description text ,
  groups_view varchar(255) DEFAULT '',
  lev smallint(4) unsigned NOT NULL DEFAULT '0',
  sort smallint(4) unsigned NOT NULL DEFAULT '0',
  numsub smallint(4) unsigned NOT NULL DEFAULT '0',
  subid varchar(255) NOT NULL,
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY alias (alias)
) ENGINE=MyISAM";


$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags(
  tid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  numnews mediumint(8) NOT NULL DEFAULT '0',
  alias varchar(250) NOT NULL DEFAULT '',
  image varchar(255) DEFAULT '',
  description text ,
  keywords varchar(255) DEFAULT '',
  PRIMARY KEY (tid),
  UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags_id(
  id int(11) NOT NULL,
  tid mediumint(9) NOT NULL,
  keyword varchar(65) NOT NULL,
  UNIQUE KEY sid (id,tid),
  KEY tid (tid)
) ENGINE=MyISAM";

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
      discount double NOT NULL DEFAULT '0' COMMENT 'Phí cho nhà cung cấp dịch vụ, phần này chỉ làm đối số để thống kê',
      discount_transaction double NOT NULL DEFAULT '0' COMMENT 'Phí giao dịch',
      images_button varchar(255) NOT NULL DEFAULT '',
      bodytext mediumtext NOT NULL,
      term mediumtext NOT NULL,
      currency_support varchar(255) NOT NULL DEFAULT '' COMMENT 'Các loại tiền tệ hỗ trợ thanh toán',
      allowedoptionalmoney tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Cho phép thanh toán số tiền tùy ý hay không',
      active_completed_email tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Kích hoạt gửi email thông báo các giao dịch chưa hoàn thành',
      active_incomplete_email tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Kích hoạt gửi email thông báo các giao dịch đã hoàn thành',
      PRIMARY KEY (payment)
    ) ENGINE=INNODB";

    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_payment_discount(
      payment varchar(100) NOT NULL DEFAULT '' COMMENT 'Cổng thanh toán',
      revenue_from double NOT NULL DEFAULT '0' COMMENT 'Doanh thu từ: Quan hệ lớn hơn hoặc bằng',
      revenue_to double NOT NULL DEFAULT '0' COMMENT 'Doanh thu đến: Quan hệ nhỏ hơn',
      provider varchar(10) NOT NULL DEFAULT '0' COMMENT 'Nhà cung cấp',
      discount double NOT NULL DEFAULT '0' COMMENT 'Mức phí %',
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
      created_time int(11) NOT NULL DEFAULT '0' COMMENT 'Ngày khởi tạo giao dịch',
      status tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Tác động: 1 cộng tiền, -1 trừ tiền',
      money_unit char(3) NOT NULL DEFAULT '',
      money_total double NOT NULL DEFAULT '0' COMMENT 'Số tiền thực cập nhật vào tài khoản thành viên',
      money_net double NOT NULL DEFAULT '0' COMMENT 'Số tiền thành viên thực hiện giao dịch',
      money_discount double NOT NULL DEFAULT '0' COMMENT 'Phí doanh nghiệp phải trả cho nhà cung cấp dịch vụ',
      money_revenue double NOT NULL DEFAULT '0' COMMENT 'Lợi nhuận mà doanh nghiệp đạt được',
      userid int(11) NOT NULL DEFAULT '0' COMMENT 'ID thành viên có tài khoản được tác động',
      adminid int(11) NOT NULL DEFAULT '0' COMMENT 'ID admin thực hiện giao dịch, nếu có giá trị này sẽ không tính vào doanh thu khi thống kê',
      order_id int(11) NOT NULL DEFAULT '0' COMMENT 'ID giao dịch nếu là thanh toán các đơn hàng từ module khác',
      customer_id int(11) NOT NULL DEFAULT '0' COMMENT 'ID người thực hiện giao dịch (Nạp tiền vào tài khoản)',
      customer_name varchar(255) NOT NULL DEFAULT '',
      customer_email varchar(255) NOT NULL DEFAULT '',
      customer_phone varchar(255) NOT NULL DEFAULT '',
      customer_address varchar(255) NOT NULL DEFAULT '',
      customer_info text NOT NULL,
      transaction_id varchar(255) NOT NULL DEFAULT '',
      transaction_type smallint(5) NOT NULL DEFAULT '-1' COMMENT 'Loại giao dịch',
      transaction_status int(11) NOT NULL DEFAULT '0' COMMENT 'Trạng thái giao dịch được quy ước chuẩn theo module',
      transaction_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian thực hiện thanh toán giao dịch',
      transaction_info text NOT NULL,
      transaction_data text NOT NULL,
      payment varchar(50) NOT NULL DEFAULT '' COMMENT 'Cổng thanh toán sử dụng',
      provider varchar(50) NOT NULL DEFAULT '' COMMENT 'Nhà cung cấp thẻ sử dụng nếu như đây là cổng thanh toán nạp thẻ, nếu không cần bỏ trống',
      tokenkey varchar(32) NOT NULL DEFAULT '',
      is_expired tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0: Chưa hết hạn, 1: Hết hạn',
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

    // Các đơn hàng từ module khác
    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_orders (
      id int(11) NOT NULL AUTO_INCREMENT,
      order_mod varchar(100) NOT NULL DEFAULT '' COMMENT 'Module title của module thực hiện đơn hàng',
      order_id varchar(100) NOT NULL DEFAULT '' COMMENT 'ID đơn hàng',
      order_message text NOT NULL COMMENT 'Message gửi cho cổng thanh toán',
      order_object varchar(250) NOT NULL DEFAULT '' COMMENT 'Đối tượng thanh toán ví dụ: Giỏ hàng, sản phẩn, ứng dụng...',
      order_name varchar(250) NOT NULL DEFAULT '' COMMENT 'Tên đối tượng',
      money_amount double NOT NULL DEFAULT '0' COMMENT 'Số tiền thanh toán',
      money_unit varchar(3) NOT NULL DEFAULT '' COMMENT 'Loại tiền tệ',
      secret_code varchar(50) NOT NULL DEFAULT '' COMMENT 'Mã bí mật của mỗi đơn hàng, không trùng lặp',
      url_back text NOT NULL COMMENT 'Dữ liệu trả về khi thanh toán xong',
      url_admin text NOT NULL COMMENT 'Url trang quản trị đơn hàng',
      add_time int(11) NOT NULL DEFAULT '0',
      update_time int(11) NOT NULL DEFAULT '0',
      paid_status varchar(100) NOT NULL DEFAULT '' COMMENT 'Trạng thái giao dịch',
      paid_id varchar(50) NOT NULL DEFAULT '' COMMENT 'ID giao dịch',
      paid_time int(11) NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật của status kia',
      PRIMARY KEY (id),
      UNIQUE KEY order_key (order_mod, order_id),
      UNIQUE KEY secret_code (secret_code),
      KEY paid_status(paid_status)
    ) ENGINE=INNODB";

    // Phân quyền theo nhóm đối tượng
    $sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_admin_groups (
      gid smallint(4) NOT NULL AUTO_INCREMENT,
      group_title varchar(100) NOT NULL DEFAULT '' COMMENT 'Tên nhóm',
      add_time int(11) NOT NULL DEFAULT '0',
      update_time int(11) NOT NULL DEFAULT '0',
      is_wallet tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem và cập nhật ví tiền',
      is_vtransaction tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem giao dịch',
      is_mtransaction tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem và xử lý giao dịch',
      is_vorder tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem các đơn hàng kết nối',
      is_morder tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem và xử lý các đơn hàng kết nối',
      is_exchange tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền quản lý tỷ giá',
      is_money tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền quản lý tiền tệ',
      is_payport tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền quản lý các cổng thanh toán',
      is_configmod tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền thiết lập cấu hình module',
      is_viewstats tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Quyền xem thống kê',
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

    // Phân quyền cho từng admin
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
      userid int(11) NOT NULL DEFAULT '0' COMMENT 'ID thành viên nếu có',
      log_ip varchar(64) NOT NULL DEFAULT '' COMMENT 'Địa chỉ IP',
      log_data mediumtext NULL DEFAULT NULL COMMENT 'Dữ liệu dạng json_encode',
      request_method varchar(20) NOT NULL DEFAULT '' COMMENT 'Loại truy vấn',
      request_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian log',
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
}
