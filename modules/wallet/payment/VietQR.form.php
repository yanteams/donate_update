<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License GNU/GPL version 3 or any later version
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_IS_MOD_WALLET') or !defined('NV_IS_VIETQR_FORM')) {
    die('Stop!!!');
}

$post['atm_sendbank'] = nv_substr($nv_Request->get_title('atm_sendbank', 'post', ''), 0, 250);
$post['atm_fracc'] = nv_substr($nv_Request->get_title('atm_fracc', 'post', ''), 0, 250);
$post['atm_time'] = nv_substr($nv_Request->get_title('atm_time', 'post', ''), 0, 250);
$post['atm_toacc'] = nv_substr($nv_Request->get_title('atm_toacc', 'post', ''), 0, 250);
$post['atm_recvbank'] = nv_substr($nv_Request->get_title('atm_recvbank', 'post', ''), 0, 250);
$post['atm_acq'] = $nv_Request->get_int('atm_acq', 'post', -1);
$post['atm_to_bank'] = '';
$post['atm_to_name'] = '';
$post['atm_to_account'] = '';
$post['transaction_data'] = '';

$post['atm_filedepute'] = '';
$post['atm_filedepute_key'] = '';
$post['atm_filebill'] = '';
$post['atm_filebill_key'] = '';
$post['vietqr_screenshots'] = '';
$post['vietqr_screenshots_key'] = '';

// Kiểm tra điều kiện gọi API VietQR
if (!isset($payment_config['acq_id'][$post['atm_acq']])) {
    $vietrq_error = $lang_module['atm_vietqr_error_acq'];
} elseif ($is_vietqr) {
    $post['atm_to_bank'] = $array_banks[$payment_config['acq_id'][$post['atm_acq']]]['name'];
    $post['atm_to_name'] = $payment_config['account_name'][$post['atm_acq']];
    $post['atm_to_account'] = $payment_config['account_no'][$post['atm_acq']];
}

$file_types_allowed = ['images', 'archives', 'documents', 'adobe'];
$upload = new NukeViet\Files\Upload($file_types_allowed, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
$upload->setLanguage($lang_global);

if (empty($atm_error)) {
    // Lấy các file đã lưu ở Session
    $array_session_file = [];
    if ($nv_Request->isset_request($module_data . '_atm_files', 'session')) {
        $atm_files = $nv_Request->get_string($module_data . '_atm_files', 'session', '');
        if (!empty($atm_files)) {
            $array_session_file = json_decode($crypt->decrypt($atm_files), true);
        }
    }

    // File chụp màn hình
    if (isset($_FILES['vietqr_screenshots']) and is_uploaded_file($_FILES['vietqr_screenshots']['tmp_name'])) {
        // Lưu file upload về thư mục tạm, xóa file tạm
        $upload_info = $upload->save_file($_FILES['vietqr_screenshots'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false);
        @unlink($_FILES['vietqr_screenshots']['tmp_name']);

        if (empty($upload_info['error'])) {
            // Đổi tên file upload được thành tên file bí mật, hủy bỏ đuôi file
            $new_basename = nv_genpass(6) . '.' . substr($upload_info['basename'], 0, 200);
            $new_filename = sha1($new_basename . $global_config['sitekey']);
            if (nv_copyfile($upload_info['name'], NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $new_filename)) {
                @chmod(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $new_filename, 0644);
                $file_key = nv_genpass(12);
                $array_session_file[$file_key] = [
                    'realname' => str_replace('-', ' ', nv_string_to_filename($_FILES['vietqr_screenshots']['name'])), // Tên file thật (người dùng donwload về, hiển thị)
                    'basename' => $new_basename // Tên file thật để xác định file lưu trên server
                ];
                $post['vietqr_screenshots'] = $array_session_file[$file_key]['realname']; // Tên file hiện tại
                $post['vietqr_screenshots_key'] = $file_key; // Khóa file hiện tại
            }
            nv_deletefile($upload_info['name']);
        } else {
            $atm_error = $lang_module['atm_error_recvbank'];
        }
    } else {
        // Lấy từ request
        $post['vietqr_screenshots_key'] = $nv_Request->get_title('vietqr_screenshots_key', 'post', '');
        if (isset($array_session_file[$post['vietqr_screenshots_key']])) {
            $post['vietqr_screenshots'] = $array_session_file[$post['vietqr_screenshots_key']]['realname'];
        } else {
            $post['vietqr_screenshots_key'] = $post['vietqr_screenshots'] = '';
        }
    }

    // Lưu session các file upload
    if (!empty($array_session_file)) {
        $nv_Request->set_Session($module_data . '_atm_files', $crypt->encrypt(json_encode($array_session_file)));
    }

    if (!isset($payment_config['acq_id'][$post['atm_acq']])) {
        $atm_error = $lang_module['vietqr_error_acq'];
    } elseif (empty($post['vietqr_screenshots'])) {
        $atm_error = $lang_module['vietqr_error_screenshots'];
    }
}