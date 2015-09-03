<?php
header('Content-type: text/json; charset=utf-8');
include_once("../../include/config.php");
include_once("../../include/public_config.php");
include_once("../../common/function.php");
$callback="";
$callback=@$_GET['callback'];
$bk			=	500;
$p_page		=	"";
$sqlwhere	=	"";
$dstart		=	date("Y-m-d")." 00:00:00";
$dend		=	date("Y-m-d")." 23:59:59";
//页数统计
$sql		=	"select x_id,x_title from t_guanjun where match_type=1 and x_result!='' ";
if(@$_GET["leaguename"]<>""){
	$dstart	=	$_GET["leaguename"]." 00:00:00";
	$dend	=	$_GET["leaguename"]." 23:59:59";
	$json["timename"]	=	$_GET["leaguename"];
	
	if($_GET["leaguename"] < date("Y-m-d")){
		$json["tday"][0]=	date("Y-m-d",strtotime($_GET["leaguename"])-86400);
		$json["tday"][1]=	date("Y-m-d",strtotime($_GET["leaguename"])+86400);
	}else{
		$json["leaguename"] = date("Y-m-d",strtotime("-12 hour")-86400);
		$json["tday"][0]=	"no";
	}
}

$sqlwhere	=	" and (match_coverdate >='".$dstart."' and match_coverdate <='".$dend."')";
$group		=	" group by match_name";

$mysqls->query($sql.$sqlwhere);
$count_num	=	$mysqls->affected_rows;
$query		=	$mysqls->query($sql.$group); 
$i			=	1;
while($i<=$count_num){
	$p_page++;
	$i+=$bk;
}
if($count_num=="0"){
	$json["fy"]["p_page"] = 0; 
}else{
	$json["fy"]["p_page"] = $p_page;
	//联赛名字
	while($t =	$query->fetch_array()){
		$x	.=	$t["x_title"]."$".urlencode($t["x_title"])."|";
	}
	$json["dh"] = $x;
	//赛事数据
	$sql		=	"SELECT tg.*,GROUP_CONCAT(tt.tid order by tid) as tid,GROUP_CONCAT(tt.team_name order by tid) as team_name,GROUP_CONCAT(tt.point order by tid) as point,GROUP_CONCAT(tt.match_id order by tid) as mid FROM t_guanjun tg,t_guanjun_team tt";
	$sqlwhere	=	" where tg.match_type=1 and tg.x_id=tt.xid and tg.x_result!='' ";
	$sqlorder	=	" group by tg.match_name order by tg.match_coverdate,tg.match_name,tg.x_id";
	if($_GET["leaguename"]<>""){
		$dstart	=	$_GET["leaguename"]." 00:00:00";
		$dend	=	$_GET["leaguename"]." 23:59:59";
	}
	$sqlwhere	.=	" and (tg.match_coverdate >='".@$dstart."' and tg.match_coverdate <='".@$dend."')";
	$sql		 =	$sql.$sqlwhere.$sqlorder;
	 //获取记录总数
	$pre_count 	=	$bk;
	intval(@$_GET["CurrPage"])>0?$this_page=@$_GET["CurrPage"]:$this_page=0;
	intval(@$_GET["CurrPage"])>0?$json["fy"]["page"]=@$_GET["CurrPage"]:$json["fy"]["page"]=0;
	$sql		.=	" limit ".$this_page*$pre_count.",".$pre_count."";
	$query		 =	$mysqls->query($sql);
	$i			 =	0;
	while($rows	 =	$query->fetch_array()){ 
		$json["db"][$i]["Match_ID"]				=	$rows["match_id"];     ///////////  0
		$json["db"][$i]["x_title"]				=	$rows["x_title"];     ///////////   1
		$json["db"][$i]["x_id"]					=	$rows["x_id"];
		$json["db"][$i]["Match_Name"]			=	$rows["match_name"];     ///////////     3
		$json["db"][$i]["Match_Date"]			=	$rows["match_date"]."<br>".$rows["match_time"];
		$json["db"][$i]["x_result"]				=	$rows["x_result"]; 
		$json["db"][$i]["team_name"]			=	$rows["team_name"];     ///////////     5
		$json["db"][$i]["tid"]					=	$rows["tid"];
		$i++;
	}
}
echo $callback."(".json_encode($json).");";
?>