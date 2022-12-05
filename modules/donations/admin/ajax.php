<?php
/**
 * @Project TMS HOLDINGS
 * @Author TMS HOLDINGS (contact@tms.vn)
 * @Copyright (C) 2021 TMS HOLDINGS. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 01/01/2021 09:47
 */
    $mod = $nv_Request->get_string('mod', 'post, get','');
if($mod == 'userid'){
		$sql2 = "SELECT userid, username, first_name, last_name, email FROM " . NV_USERS_GLOBALTABLE . " WHERE username LIKE '%".$_GET['q']."%' ";
		    $ketqua_userid = $db->query($sql2);
			while($row_userid = $ketqua_userid->fetch()){
			        $json[] = ['id'=>$row_userid['userid'], 'text'=>$row_userid['username']];
			}
    echo json_encode($json);
}


