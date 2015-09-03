<?php
header('Content-type: text/json; charset=utf-8');
include_once("../../include/pd_user_json.php");
include_once("../../include/public_config.php");
include_once("../../common/function.php");
include_once("../../include/config.php");
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

$sql		=	"select id from bet_match where Match_Type=1 and Match_IsShowt=1 and Match_Total01Pl>0 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR)".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
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
	
	$sql	=	"select match_name from bet_match where Match_Type=1 and Match_IsLose=1 and Match_IsShowt=1 and Match_Total01Pl>0 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) group by match_name";
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
	$sql	=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_BzM, Match_Total01Pl, Match_Total23Pl, Match_Total46Pl, Match_Total7upPl, Match_BzG, Match_BzH FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	$query	=	$mysqli->query($sql);
	$i		=	0;
	while($rows = $query->fetch_array()){
		$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];     ///////////  0
		$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];     ///////////   1
		$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];     ///////////    2
		$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];     ///////////     3
		$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
		if ($rows["Match_IsLose"]==1){
			$mdate.= "<br><font color=red>滾球</font>";
		}
		$json["db"][$i]["Match_Date"]		=	$mdate;     ///////////               4
		$json["db"][$i]["Match_BzM"]		=	$rows["Match_BzM"];     ///////////  5
		$json["db"][$i]["Match_Total01Pl"]	=	$rows["Match_Total01Pl"];     ///////////   6
		$json["db"][$i]["Match_Total23Pl"]	=	$rows["Match_Total23Pl"];     ///////////    7
		$json["db"][$i]["Match_Total46Pl"]	=	$rows["Match_Total46Pl"];     ///////////     8
		$json["db"][$i]["Match_Total7upPl"]	=	$rows["Match_Total7upPl"];     ///////////   9
		$json["db"][$i]["Match_BzG"]		=	$rows["Match_BzG"];     ///////////    10
		$json["db"][$i]["Match_BzH"]		=	$rows["Match_BzH"];     ///////////     11
		$i++;
	}
}
echo $callback."(".json_encode($json).");";
exit;
?>