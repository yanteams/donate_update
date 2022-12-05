<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 09 Jan 2014 10:18:48 GMT
 */

if( !defined( 'NV_IS_MOD_NVTOOLS' ) )
	die( 'Stop!!!' );

if( $nv_Request->isset_request( 'submit_copy', 'post,get' ) && $nv_Request->isset_request( 'lang','post,get' ) )
{
	$lang = $nv_Request->get_string( 'lang', 'post,get', '' );
	
	$result = $db->query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'" );
	while( $item = $result->fetch( ) )
	{
		if( strpos( $item['name'], 'nv4_vi_' ) !== false )
		{
			echo $item['name'] . '<br>' . strpos( 'nv4_', $item['name'] ) . '<br>';
			$table_lang = str_replace( 'nv4_vi_', 'nv4_' . $lang . '_', $item['name'] );
			$db->query( 'DROP TABLE IF EXISTS ' . $table_lang );
			$db->query( 'CREATE TABLE ' . $table_lang . ' LIKE ' . $item['name'] );
	
			$db->query( 'INSERT INTO ' . $table_lang . ' SELECT * FROM ' . $item['name'] );
	
		}
	}
	$db->query( "DELETE FROM nv4_config WHERE lang='" . $lang . "'" );
	
	//config
	$_sql = "SELECT * FROM nv4_config WHERE lang='vi'";
	$_query = $db->query( $_sql );
	while( $row = $_query->fetch( ) )
	{
		$db->query( "INSERT INTO nv4_config(lang, module, config_name, config_value) 
		VALUES ('" . $lang . "'," . $db->quote( $row['module'] ) . "," . $db->quote( $row['config_name'] ) . "," . $db->quote( $row['config_value'] ) . ")" );
	}
	echo 'nv4_config';
	echo 'Thực hiện xong';
	die();
}
else
{
	$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	$re = $db->query( 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1 AND lang!=\'vi\' ');
	$lang_value = array( );
	while( list( $lang_i ) = $re->fetch( 3 ) )
	{
		$xtpl->assign( 'LANG', $lang_i );
		$xtpl->parse( 'main.option' );
	}
	
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
