<?php
/*ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);*/
include(dirname(__FILE__).'/../include/filter.php');
include_once("../include/private_config.php");
include_once("../lib/class/model.class.php");
 $site_id=SITEID;
$d=M('web_config',$db_config)->field('copy_right,web_name')->where("site_id='$site_id'")->find();
$data['CopyRight']='PkBet';
if($d){
    $data['CopyRight']=$d['copy_right'];
    $data['WebName']=$d['web_name'];
}
echo json_encode($data);
