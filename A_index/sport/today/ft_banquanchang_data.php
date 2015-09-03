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

$sql		=	"select id from bet_match WHERE Match_Date='".date("m-d")."' and Match_CoverDate>now() and Match_BqMM>0 ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
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
	
	$sql	=	"select match_name from bet_match WHERE Match_Date='".date("m-d")."' and Match_CoverDate>now() and Match_BqMM>0 group by match_name";
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
	$sql	=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose, Match_BqMM, Match_BqMH,  Match_BqMG, Match_BqHM, Match_BqHH, Match_BqHG, Match_BqGM, Match_BqGH, Match_BqGG FROM bet_match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	$query	=	$mysqli->query($sql);
	$i		=	0;
	while($rows = $query->fetch_array()){
		$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];       //  0
		$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];   //  1
		$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];    //  2
		$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];     //  3
		$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
		if ($rows["Match_IsLose"]==1){
			$mdate.= "<br><font color=red>滾球</font>";
		}
		$json["db"][$i]["Match_Date"]		=	$mdate;     //               4
		$json["db"][$i]["Match_BqMM"]		=	NOnull($rows["Match_BqMM"]);  //  5
		$json["db"][$i]["Match_BqMH"]		=	NOnull($rows["Match_BqMH"]);      //  6
		$json["db"][$i]["Match_BqMG"]		=	NOnull($rows["Match_BqMG"]);      //  7
		$json["db"][$i]["Match_BqHM"]		=	NOnull($rows["Match_BqHM"]);      //  8
		$json["db"][$i]["Match_BqHH"]		=	NOnull($rows["Match_BqHH"]);      //  9
		$json["db"][$i]["Match_BqHG"]		=	NOnull($rows["Match_BqHG"]);      //  10
		$json["db"][$i]["Match_BqGM"]		=	NOnull($rows["Match_BqGM"]);      //  11
		$json["db"][$i]["Match_BqGH"]		=	NOnull($rows["Match_BqGH"]);      //  12
		$json["db"][$i]["Match_BqGG"]		=	NOnull($rows["Match_BqGG"]);      //  13
		$i++;
	}
}

function NOnull($str){
	return $str>0?sprintf("%.2f",$str):0;
}
echo $callback."(".json_encode($json).");";
?>