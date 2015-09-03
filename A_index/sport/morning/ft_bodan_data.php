<?php
header('Content-type: text/json; charset=utf-8');
include('../../include/filter.php');
include_once("../include/pd_user_json.php");
include_once("../../include/public_config.php");

include_once("../common/function.php");
$this_page	=	0; //当前页
if(intval(@$_GET["CurrPage"])>0) $this_page	=	@$_GET["CurrPage"];
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

$sql		=	"select id from bet_match where Match_Type=0 and Match_IsShowbd=1 and Match_CoverDate>now() and Match_Bd21>0 ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
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

    $sql	=	"select match_name from bet_match where Match_Type=0 and Match_IsShowbd=1 and Match_CoverDate>now() and Match_Bd21>0 group by match_name";
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
	$sql	=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_Bd10, Match_Bd20, Match_Bd21, Match_Bd30, Match_Bd31, Match_Bd32, Match_Bd40, Match_Bd41, Match_Bd42, Match_Bd43, Match_Bd00, Match_Bd11, Match_Bd22, Match_Bd33, Match_Bd44, Match_Bdup5, Match_Bdg10, Match_Bdg20, Match_Bdg21, Match_Bdg30, Match_Bdg31, Match_Bdg32, Match_Bdg40, Match_Bdg41, Match_Bdg42, Match_Bdg43 FROM bet_match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
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
		$json["db"][$i]["Match_Bd10"]		=	$rows["Match_Bd10"];     ///////////     5
		$json["db"][$i]["Match_Bd20"]		=	$rows["Match_Bd20"];     ///////////     6
		$json["db"][$i]["Match_Bd21"]		=	$rows["Match_Bd21"];     ///////////     7
		$json["db"][$i]["Match_Bd30"]		=	$rows["Match_Bd30"];     ///////////     8
		$json["db"][$i]["Match_Bd31"]		=	$rows["Match_Bd31"];     ///////////     9
		$json["db"][$i]["Match_Bd32"]		=	$rows["Match_Bd32"];     ///////////     10
		$json["db"][$i]["Match_Bd40"]		=	$rows["Match_Bd40"];     ///////////     11
		$json["db"][$i]["Match_Bd41"]		=	$rows["Match_Bd41"];     ///////////     12
		$json["db"][$i]["Match_Bd42"]		=	$rows["Match_Bd42"];     ///////////     13
		$json["db"][$i]["Match_Bd43"]		=	$rows["Match_Bd43"];     ///////////     14
		$json["db"][$i]["Match_Bd00"]		=	$rows["Match_Bd00"];     ///////////     15
		$json["db"][$i]["Match_Bd11"]		=	$rows["Match_Bd11"];     ///////////     16
		$json["db"][$i]["Match_Bd22"]		=	$rows["Match_Bd22"];     ///////////     17
		$json["db"][$i]["Match_Bd33"]		=	$rows["Match_Bd33"];     ///////////     18
		$json["db"][$i]["Match_Bd44"]		=	$rows["Match_Bd44"];     ///////////     19
		$json["db"][$i]["Match_Bdup5"]		=	$rows["Match_Bdup5"];     ///////////     20
		$json["db"][$i]["Match_Bdg10"]		=	$rows["Match_Bdg10"];     ///////////     21
		$json["db"][$i]["Match_Bdg20"]		=	$rows["Match_Bdg20"];     ///////////     22
		$json["db"][$i]["Match_Bdg21"]		=	$rows["Match_Bdg21"];     ///////////     23
		$json["db"][$i]["Match_Bdg30"]		=	$rows["Match_Bdg30"];     ///////////     24
		$json["db"][$i]["Match_Bdg31"]		=	$rows["Match_Bdg31"];     ///////////     25
		$json["db"][$i]["Match_Bdg32"]		=	$rows["Match_Bdg32"];     ///////////     26
		$json["db"][$i]["Match_Bdg40"]		=	$rows["Match_Bdg40"];     ///////////     27
		$json["db"][$i]["Match_Bdg41"]		=	$rows["Match_Bdg41"];     ///////////     28
		$json["db"][$i]["Match_Bdg42"]		=	$rows["Match_Bdg42"];     ///////////     29
		$json["db"][$i]["Match_Bdg43"]		=	$rows["Match_Bdg43"];     ///////////     30
		$i++;
	}
}
echo $callback."(".json_encode($json).");";
?>