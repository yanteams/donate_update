<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YAN <admin@yansupport.com>
 * @Copyright (C) 2022 YAN. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 05 Dec 2022 11:04:36 GMT
 */
if( ! defined( 'NV_IS_MOD_DONATIONS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );


	
$notice = "";
$thongke = "";
$key = "";
    $result = $db->query( "SELECT config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='".NV_LANG_DATA."' AND module='".$module_data."'" );
	$module_config = array();
	while( list( $c_config_name, $c_config_value ) = $result->fetch( 3 ) )
	{
		$module_config[$c_config_name] = $c_config_value;
	}
//Lay danh sach cac don vi
$madvi = $nv_Request->get_int( 'madvi', 'post,get', '' );
$dv = 0;
$donvi = getDonvi();
foreach($donvi as $list_dv)
{
    $xtpl->assign('LIST_DV', $list_dv);
    $xtpl->assign('SELECT', $list_dv['madvi'] == $madvi ? "selected=\"selected\"" : "");
    //dem so luong don vi hien co
    $dv ++;
    $xtpl->parse( 'main.dv' );
}

$page = $nv_Request->get_int('page', 'get', 0 );

//Bat tat tim kiem
if( $module_config['search'] == 1 )
{
    if ( $nv_Request->isset_request( 'sub_search', 'post' ) )
    {
        $key =  $nv_Request->get_string('key', 'post','');
    }
    else
    {
        $key = $nv_Request->get_string ('key', 'get','');
    }
    if($page == 0)
    {
        $xtpl->parse( 'main.search' );
    }
}

if($madvi == 0)
{
    $sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE 1=1 AND hoten like '%" . $key . "%'";
    $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;        
}
else
{
    $sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE madvi = " . $madvi . "";
    $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;madvi=" . $madvi;        
}

if($key != "")
{
    $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" 
                . NV_NAME_VARIABLE . "=" . $module_name . "&amp;key=" . $key . "";
}

$sql1 = "SELECT * " . $sql;

$result1 = $db->query( $sql1 ); 


$all_page  = $result1->rowCount();

if($madvi == 0)
{
    $sql2 = "SELECT cb.id, cb.hoten, cb.ngsinh, cb.gtinh, cb.avt, cb.diachi, cb.email, dv.tendonvi, cv.tenchucvu FROM " 
       . NV_PREFIXLANG . "_" . $module_data .
       " cb INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_donvi dv
       INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_chucvu cv                  
       ON cb.madvi = dv.madvi AND cb.macvu1 = cv.macvu WHERE 1 = 1 AND cb.hoten like '%" . $key . "%' LIMIT " . $page . ", " . $module_config['per_page'] . "";
}
else
{
    $sql2 = "SELECT cb.id, cb.hoten, cb.ngsinh, cb.gtinh, cb.avt, cb.diachi, cb.email, dv.tendonvi, cv.tenchucvu FROM " 
       . NV_PREFIXLANG . "_" . $module_data .
       " cb INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_donvi dv
       INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_chucvu cv                  
       ON cb.madvi = dv.madvi AND cb.macvu1 = cv.macvu WHERE cb.madvi = " . $madvi . " LIMIT " . $page . ", " . $module_config['per_page'];
}
$query2 = $db->query( $sql2 );
$data = array();

while ( $row = $query2->fetch() )
{
        $row['gtinh'] == 1 ? $row['gtinh'] = $lang_module['female'] : $row['gtinh'] = $lang_module['male'];
        
        $data[] = array(
        "id" => $row['id'],
        "hoten" => $row['hoten'],
        "ngsinh" => $row['ngsinh'],
        "gtinh" => $row['gtinh'],
        "avt" => $row['avt'],
        "diachi" => $row['diachi'],
        "email" => $row['email'],
        "tendonvi" => $row['tendonvi'],
        "tenchucvu" => $row['tenchucvu']
        );
    if($madvi != 0)
    {
        $notice = $lang_module['notice_donvi'] . "\"" .$row['tendonvi'] . "\"";
        $thongke = sprintf($lang_module['thongke_dv'], $all_page, $row['tendonvi']);
    }    
    else
    {        
        if($key == "")
        {
            $f = 0;
            $thongke = sprintf($lang_module['thongke'], $all_page, $dv);
            //Lay noi dung thong bao
            $bodytext = "";
            $content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . 'Content.txt';
            if( file_exists( $content_file ) )
            {
            	$bodytext = file_get_contents( $content_file );
            }
            $xtpl->assign('BODYTEXT', $bodytext);
        }
        else
        {
            $notice = sprintf($lang_module['searchok'], $all_page, $key);
            //echo $key; exit();
        }
    }
    
}
if(empty($data))
{
    $notice = $lang_module['no_search'];
}



$generate_page = nv_generate_page( $base_url, $all_page, $module_config['per_page'], $page );
$xtpl->assign( 'PAGE', $generate_page );

$sql = "SELECT tendonvi, gt_dv FROM " . NV_PREFIXLANG . "_" . $module_data . "_donvi WHERE madvi = " . $madvi;
$result = $db->query($sql);
while( $row = $result->fetch())
{
    $xtpl->assign('GT_DV', $row['gt_dv']);
    if($all_page == 0)
    {
        $notice = $lang_module['no_cb'] . "\"" .$row['tendonvi'] . "\"";
    }
}

foreach( $data AS $cbdoan )
{
	if( empty( $cbdoan['avt'] ) )
	{
		$cbdoan['avt'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/cbdoan/no-image.jpg";
	}
	$cbdoan['ngsinh'] = nv_date( 'd/m/Y', $cbdoan['ngsinh'] );
    $xtpl->assign('CBDOAN', $cbdoan);
    

	$xtpl->assign( 'DETAIL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name  . "&amp;" . NV_OP_VARIABLE . "=detail/" . $cbdoan['id']."-".change_alias($cbdoan['hoten']));
	
            
    //Bat tat dia chi tai toplip
    if(!empty($cbdoan['diachi']))
    {
        $xtpl->parse( 'main.table.loop.toplip.diachi' );
    }
    
    //Bat tat toplip
    if( $module_config['toplip'] == 1 )
    {
        $xtpl->parse( 'main.table.loop.toplip' );
    }
    
    $xtpl->parse( 'main.table.loop' );        
}

if($all_page > 0)
{
    $xtpl->parse( 'main.table' );
}

$xtpl->assign('NOTICE', $notice);  
$xtpl->assign('THONGKE', $thongke);
$xtpl->assign( 'ACTION', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
