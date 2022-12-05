<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_DONATIONS', true);
require_once NV_ROOTDIR . "/modules/" . $module_name . '/global.functions.php';
$count_op = sizeof( $array_op );

	if( ! empty( $array_op ) and $op == "main" )
	{	
		$op = "main";
		if( $count_op == 1 )
		{
			$array_page = explode( "-", $array_op[0] );
			
			$id = intval( $array_page[0] );
			
			$number = strlen( $id ) + 1;
			$alias_url = substr( $array_op[0], 0, -$number );
			
			if( $id > 0 and $alias_url != "" )
			{
				$op = "detail";
			}
		}
	}