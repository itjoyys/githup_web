<?php
header('Content-type: text/json; charset=utf-8');
include(dirname(__FILE__).'../include/filter.php');
include(dirname(__FILE__).'../include/login_check.php');

include_once("../include/private_config.php");
include ("../lib/class/model.class.php");
include_once("set.php");
$bet_money	=	$_REQUEST['bet_money'];
$dz			=	$dz_db["串关"];
$dc			=	$dc_db["串关"];
if(!$dz || $dz=="") $dz	=	$dz_db['未定义'];
if(!$dc || $dc=="") $dc	=	$dc_db['未定义'];
if($bet_money<=$dz){
    $s_t	=	strftime("%Y-%m-%d",time())." 00:00:00";
	$e_t	=	strftime("%Y-%m-%d",time())." 23:59:59";
	$sql	=	"select sum(bet_money) as s from `k_bet_cg_group` where uid='".$_SESSION["uid"]."' and site_id='".SITEID."' and bet_time>='$s_t' and bet_time<='$e_t' and `status`!=3"; //无效跟平手不当成投注
	$query	=	$mysqlt->query($sql); //取出串关当天总下注金额
	$rs		=	$query->fetch_array();
	if(!$rs['s'] || $rs['s']=="null") $rs['s']=0;
	if(($rs['s']+$bet_money)<=$dc){
	    $json['result']				=	"ok";
		$_SESSION["check_action"]	=	'true'; //防软件打水
	}else{
	    $json['result']				=	"您本次交易：".$bet_money."\n串关当天限额：".$dc."\n您今天已交易：".$t['s']."\n本次允许交易：".($dc-$rs['s']);
	}
}else{
    if($_SESSION["gid"]) $json['result']="您本次交易：".$bet_money."\n串关单注限额：".$dz;
	else $json['result']="wdl"; //用户未登陆
}

echo json_encode($json);
exit;
?>