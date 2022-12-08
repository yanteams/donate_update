<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$sys_info['disable_classes'] = [];
$sys_info['disable_functions'] = [];
$sys_info['ini_set_support'] = true;
$sys_info['supports_rewrite'] = 'rewrite_mode_apache';
$sys_info['zlib_support'] = true;
$sys_info['mb_support'] = true;
$sys_info['iconv_support'] = true;
$sys_info['allowed_set_time_limit'] = true;
$sys_info['os'] = 'WINDOWS NT';
$sys_info['fileuploads_support'] = true;
$sys_info['curl_support'] = true;
$sys_info['ftp_support'] = true;
$sys_info['string_handler'] = 'mb';
$sys_info['support_cache'] = [];
$sys_info['php_compress_methods'] = ['deflate' => 'gzdeflate', 'gzip' => 'gzencode', 'x-gzip' => 'gzencode', 'compress' => 'gzcompress', 'x-compress' => 'gzcompress'];
$sys_info['server_headers'] = ['server' => 'Apache/2.4.54 (Win64) OpenSSL/1.1.1p PHP/7.4.30','content-type' => 'text/html; charset=UTF-8'];
$sys_info['is_http2'] = false;
$sys_info['http_only'] = false;
$sys_info['https_only'] = false;
$sys_info['ip6_support'] = true;

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.gc_maxlifetime', '3600');
ini_set('track_errors', '1');
ini_set('user_agent', 'NV4');

$serverInfoUpdated = true;
$iniSaveTime = 1670398830;
