<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../include/public_config.php");

//注单取消审核

$gid		=	intval($_GET["gid"]);
$count		=	0;
$sql		=	"select `status`,cg_count from k_bet_cg_group where `status` in(1,3) and gid=$gid limit 1";
$query		=	$mysqlt->query($sql);
$rows 		=	$query->fetch_array();
$count		=	$rows['cg_count'];

if($rows["status"] == 1){
	$sql	=	"update k_user,k_bet_cg_group set k_user.money=k_user.money-k_bet_cg_group.win-k_bet_cg_group.bet_money*0.01,k_bet_cg_group.status=0,k_bet_cg_group.win=0,k_bet_cg_group.update_time=null,k_bet_cg_group.cg_count=(select count(*) from k_bet_cg where gid=k_bet_cg_group.gid) where k_user.uid=k_bet_cg_group.uid and k_bet_cg_group.gid=$gid";
}elseif($rows["status"]==3){
	$sql	=	"update k_user,k_bet_cg_group set k_user.money=k_user.money-k_bet_cg_group.win,k_bet_cg_group.status=0,k_bet_cg_group.win=0,k_bet_cg_group.update_time=null,k_bet_cg_group.cg_count=(select count(*) from k_bet_cg where gid=k_bet_cg_group.gid) where k_user.uid=k_bet_cg_group.uid and k_bet_cg_group.gid=$gid";
}

$mysqlt->autocommit(FALSE);
$mysqlt->query("BEGIN"); //事务开始
try{
	$mysqlt->query($sql);
	$q1		=	$mysqlt->affected_rows;
	$sql	=	"update k_bet_cg set status=0 where gid=$gid"; //输，所有该组都需要重新审核
	$mysqlt->query($sql);
	$q2		=	$mysqlt->affected_rows;
	if($q1==2 && $q2==$count){
		$mysqlt->commit(); //事务提交
		$msg=	"操作成功";
	}else{
		$mysqlt->rollback(); //数据回滚
		$msg=	"操作失败";
	}
}catch(Exception $e){
	$mysqlt->rollback(); //数据回滚
	$msg	=	"操作失败";
}

message($msg,$_SERVER["HTTP_REFERER"]);
?>