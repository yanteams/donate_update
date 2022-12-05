<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['config'];

$xtpl = new XTemplate( $op.".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$result = $db->query( "SELECT config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='".NV_LANG_DATA."' AND module='".$module_data."'" );
	$module_config = array();
	while( list( $c_config_name, $c_config_value ) = $result->fetch( 3 ) )
	{
		$module_config[$c_config_name] = $c_config_value;
	}

if ( $nv_Request->isset_request( 'config', 'post' ) )
{
    $array_config['toplip'] = $nv_Request->get_int ('toplip', 'post','');
    $array_config['per_page'] = $nv_Request->get_int ('per_page', 'post','');
    $array_config['search'] = $nv_Request->get_int ('search', 'post','');
    
     foreach ( $array_config as $config_name => $config_value )
    {
        $db->query( "REPLACE INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES('" . NV_LANG_DATA . "', '" . $module_name  . "', '" . $config_name  . "', '" . $config_value  . "')" );
    }
	$nv_Cache->delMod('config');
    $nv_Cache->delMod($module_name);


    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=config" );
    die();
}

if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}
$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . 'Content.txt';

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$bodytext = $nv_Request->get_string( 'bodytext', 'post','' );
	file_put_contents( $content_file, $bodytext );

	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	die();
}

$bodytext = "";
if( file_exists( $content_file ) )
{
	$bodytext = file_get_contents( $content_file );
	$bodytext = nv_editor_br2nl( $bodytext );
}

$is_edit = $nv_Request->get_int( 'is_edit', 'get', 0 );
if( empty( $bodytext ) ) $is_edit = 1;

if( $is_edit )
{
	if( ! empty( $bodytext ) ) $bodytext = nv_htmlspecialchars( $bodytext );
	
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
	
	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$data = nv_aleditor( "bodytext", '99%', '300px', $bodytext );
	}
	else
	{
		$data = "<textarea style=\"width: 99%\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"8\">" . $bodytext . "</textarea>";
	}
	
	$xtpl->assign( 'DATA', $data );
	
	$xtpl->parse( 'main.edit' );
}
else
{	
	$xtpl->assign( 'DATA', $bodytext );
	$xtpl->assign( 'URL_EDIT', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;is_edit=1" );
	
	$xtpl->parse( 'main.data' );
}

// $xtpl->assign( 'CHECK', $module_config['toplip'] == 1 ? " checked=\"checked\"" : "" );
// $xtpl->assign( 'CHECK_S', $module_config['search'] == 1 ? " checked=\"checked\"" : "" );


    $xtpl->assign( 'CONF', $module_config );


$url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "";
$xtpl->assign( 'ACTION', $url );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
