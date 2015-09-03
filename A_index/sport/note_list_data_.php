<?php

include_once("../include/config.php");
include_once("../include/public_config.php");
include_once ("../include/private_config.php");
include_once("../lib/class/model.class.php");
include_once("common/function.php");
/*ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);*/
$p=intval(@$_REQUEST['p']);
$action=intval(@$_REQUEST['action']);

$s['uid']=$_SESSION['uid'];
if($action==0){
    $s['is_jiesuan']='0';
    $s['status']='0';
}else{
    $s['is_jiesuan']='1';
}
$s['p']=$p;
$s['bet_money']='10';
$data=GetSportList($s);
//print_r($data);
echo json_encode($data);