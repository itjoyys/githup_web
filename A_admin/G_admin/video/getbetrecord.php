<?php
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);
if(!@$_SESSION['agent_id']){
  echo  $data='{"result":true,"data":{"Code":1}}';
    exit;
}
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once dirname(__FILE__) . "/../../lib/video/Games.class.php";

$Company = trim(@$_GET["Company"]);

$g_type_arr = array("og", "ag", "mg", "ct", "bbin","lebo");

if (!in_array($Company, $g_type_arr)) {
    $data['Error']='未知的游戏';
    exit(json_encode($data));
}

$loginname = @$_REQUEST['UserName'];
$orderId = @$_REQUEST['OrderId'];
$videotype = @$_REQUEST['VideoType'];
$gametype = @$_REQUEST['GameType'];
$s_time = @$_REQUEST['S_Time'].' 00:00:00';
$e_time = @$_REQUEST['E_Time'].' 23:59:59';
$agentid = @$_REQUEST['agentid'];
$Page=intval(@$_REQUEST['Page']);
$Page_Num=intval(@$_REQUEST['Page_Num']);

if ($Page<=0 )$Page=1;
if ($Page_Num<=0)$Page_Num=20;

$games = new Games();

    $agentid=$_SESSION['agent_id'];
    $data = $games->GetBetRecord($Company, $loginname, $orderId, $videotype,$gametype, $s_time, $e_time,$agentid, $Page, $Page_Num);


echo $data;