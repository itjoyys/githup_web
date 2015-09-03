<?php
header('Content-type: text/json; charset=utf-8');
include_once("../../include/config.php");
include_once("../include/pd_user_json.php");
include_once("../../include/public_config.php");
include_once("../common/function.php");
$this_page	=	0; //当前页
if(intval($_GET["CurrPage"])>0) $this_page	=	$_GET["CurrPage"];
$this_page++;
$bk			=	40; //每页显示多少条记录
$sqlwhere	=	''; //where 条件
$id			=	''; //ID字符串
$i			=	1; //记录总个数
$start		=	($this_page-1)*$bk+1; //本页记录开始位置
$end		=	$this_page*$bk; //本页记录结束位置
//页数统计
if(@$_GET["leaguename"]<>""){
	$leaguename	 =	explode("$",urldecode($_GET["leaguename"]));
	$v			 =	(count($leaguename)>1 ? 'and (' : 'and' );
	$sqlwhere	.=	" $v match_name='".$leaguename[0]."'";
	for($is=1; $is<count($leaguename)-1; $is++){
		$sqlwhere.=	" or match_name='".$leaguename[$is]."'";
	}
	$sqlwhere	.=	(count($leaguename)>1 ? ')' : '' );
}

$sql		=	"select id from tennis_Match where Match_Type=10 and Match_Bd21>0 and  Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) ".$sqlwhere.' order by Match_CoverDate,match_name';
$query		=	$mysqli->query($sql);
while($row	=	$query->fetch_array()){
	if($i  >= $start && $i <= $end){
		$id	=	$row['id'].','.$id;
	}
	$i++;
}
if($i == 1){ //未查找到记录
	$json["dh"]	=	0;
	$json["fy"]["p_page"] = 0; 
}else{
	$id			=	rtrim($id,',');
	$json["fy"]["p_page"] 	= ceil($i/$bk); //总页数
	$json["fy"]["page"] 	= $this_page-1;
	
	$sql	=	"select Match_Name from tennis_Match where Match_Type=10 and Match_Bd21>0 and  Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) group by match_name";
	$query	=	$mysqli->query($sql);
	$i		=	0;
	$lsm	=	'';
	while($row = $query->fetch_array()){
		$lsm	.=	urlencode($row['match_name']).'|';
		$i++;
	}
	$json["lsm"]=	rtrim($lsm,'|');
	$json["dh"]	=	ceil($i/2)*30; //窗口高度 px 值
	
	//赛事数据
	$sql	=	"select Match_Name,Match_Time,Match_Date,Match_IsLose,Match_Master,Match_ID,Match_Bd20,Match_Bd21,Match_Bd31,Match_Bd32,Match_Guest from tennis_Match where id in($id) order by Match_CoverDate,match_name";
	$query	=	$mysqli->query($sql);
	$i		=	0;
	while($rows = $query->fetch_array()){ 
		$json["db"][$i]["Match_ID"]		=	$rows["Match_ID"];
		$json["db"][$i]["Match_Name"]	=	$rows["Match_Name"];
		$json["db"][$i]["Match_Date"]	=	$rows["Match_Date"];
		$json["db"][$i]["Match_Time"]	=	$rows["Match_Time"];
		$json["db"][$i]["Match_IsLose"]	=	$rows["Match_IsLose"];
		$json["db"][$i]["Match_Master"]	=	$rows["Match_Master"];
		$json["db"][$i]["Match_Bd20"]	=	$rows["Match_bd20"];
		$json["db"][$i]["Match_Bd21"]	=	$rows["Match_Bd21"];
		$json["db"][$i]["Match_Bd30"]	=	$rows["Match_Bd30"];
		$json["db"][$i]["Match_Guest"]	=	$rows["Match_Guest"];
		$json["db"][$i]["Match_Bdg20"]	=	$rows["Match_Bdg20"];
		$json["db"][$i]["Match_Bdg21"]	=	$rows["Match_Bdg21"];
		$json["db"][$i]["Match_Bdg30"]	=	$rows["Match_Bdg30"];
		$json["db"][$i]["Match_Bd31"]	=	$rows["Match_Bd31"];
		$json["db"][$i]["Match_Bd32"]	=	$rows["Match_Bd32"];
		$json["db"][$i]["Match_Bdg31"]	=	$rows["Match_Bdg31"];
		$json["db"][$i]["Match_Bdg32"]	=	$rows["Match_Bdg32"];
		$i++;
	}
}
echo $callback."(".json_encode($json).");";
?>