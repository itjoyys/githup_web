<?php
header('Content-type: text/json; charset=utf-8');
include('../../include/filter.php');
include_once("../include/pd_user_json.php");
include_once("../../include/public_config.php"); 
include_once("../common/function.php");
$this_page	=	0; //当前页
$json["dh"]	=	0;
$json["fy"]["p_page"] = 0;
echo $callback."(".json_encode($json).");";
exit;
if(intval($_GET["CurrPage"])>0) $this_page	=	$_GET["CurrPage"];
$this_page++;
$bk			=	1000; //每页显示多少条记录
$sqlwhere	=	''; //where 条件
$id			=	''; //ID字符串
$i			=	1; //记录总个数
$start		=	($this_page-1)*$bk+1; //本页记录开始位置
$end		=	$this_page*$bk; //本页记录结束位置
//页数统计
if(@$_GET["leaguename"]<>""){
	$leaguename	 =	explode("$",urldecode($_GET["leaguename"]));
	$v			 =	(count($leaguename)>1 ? 'and (' : 'and' );
	$sqlwhere	.=	" $v x_title='".$leaguename[0]."'";
	for($is=1; $is<count($leaguename)-1; $is++){
		$sqlwhere.=	" or x_title='".$leaguename[$is]."'";
	}
	$sqlwhere	.=	(count($leaguename)>1 ? ')' : '' );
}

$sql		=	"select x_id from t_guanjun where match_type=1 and match_coverdate>now() and x_result is null ".$sqlwhere.' order by  match_coverdate,match_name,x_id';
$query		=	$mysqli->query($sql);
while($row	=	$query->fetch_array()){
	if($i  >= $start && $i <= $end){
		$id	=	$row['x_id'].','.$id;
	}
	$i++;
}
if($i == 1){ //未查找到记录
	$json["dh"]	=	0;
	$json["fy"]["p_page"] = 0; 
}else{
	$id			=	rtrim($id,',');
	$json["fy"]["p_page"] 	=	ceil($i/$bk); //总页数
	$json["fy"]["page"] 	=	$this_page-1;
	
	$sql	=	"select x_title from t_guanjun where match_type=1 and match_coverdate>now() and x_result is null group by x_title";
	$query	=	$mysqli->query($sql);
	$i		=	0;
	$lsm	=	'';
	while($row = $query->fetch_array()){
		$lsm	.=	urlencode($row['x_title']).'|';
		$i++;
	}
	$json["lsm"]=	rtrim($lsm,'|');
	$json["dh"]	=	ceil($i/2)*30; //窗口高度 px 值
	
	//赛事数据
	$sql	=	"SELECT match_id, x_title, x_id, match_name, match_date, match_time FROM t_guanjun where x_id in($id) order by match_coverdate,match_name,x_id";
	$query	=	$mysqli->query($sql);
	$i		=	0;
	while($rows = $query->fetch_array()){ 
		$tid		=	"";
		$team_name	=	"";
		$point		=	"";
		$match_id	=	"";
		$sql		=	"select tid, team_name, point, match_id from t_guanjun_team where xid=".$rows["x_id"]." order by tid desc";
		$query1		=	$mysqli->query($sql);
		while($ttrs	=	$query1->fetch_array()){
			$tid		.=	$ttrs["tid"].",";
			$team_name	.=	$ttrs["team_name"].",";
			$point		.=	$ttrs["point"].",";
			$match_id	.=	$ttrs["match_id"].",";
		}
		$json["db"][$i]["Match_ID"]				=	$rows["match_id"];     ///////////  0
		$json["db"][$i]["x_title"]				=	$rows["x_title"];     ///////////   1
		$json["db"][$i]["x_id"]					=	$rows["x_id"];
		$json["db"][$i]["Match_Name"]			=	$rows["match_name"];     ///////////     3
		$json["db"][$i]["Match_Date"]			=	$rows["match_date"]."<br/>".$rows["match_time"];
		$json["db"][$i]["team_name"]			=	$team_name;     ///////////     5
		$json["db"][$i]["point"]				=	$point; 
		$json["db"][$i]["tid"]					=	$tid;
		$i++;
	}
}
echo $callback."(".json_encode($json).");";
?>