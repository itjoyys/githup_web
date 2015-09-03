<?php
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");  
header('Content-type: text/json; charset=utf-8');
include_once("../../include/config.php");
$callback="";
$callback=@$_GET['callback'];
include_once("../../cache/lqgq.php");
if(time()-$lasttime > 3){
	if($count == 0){ //没数据
		$json["dh"]	=	0;
		$json["fy"]["p_page"] = "0";
		echo $callback."(".json_encode($json).");";
		exit;
	}else{ //有数据重新采集一次，看是否有数据
		include_once("../../include/function_cj.php");
		if(lqgq_cj()){
			include("../../cache/lqgq.php"); //重新载入
		}else{
			$json["dh"]	=	0;
			$json["fy"]["p_page"] = "0";
			echo $callback."(".json_encode($json).");";
			exit;
		}
	}
}
$bk			=	50; //每页显示记录总个数
$this_page	=	0; //当前页
if(intval($_GET["CurrPage"])>0) $this_page	=	$_GET["CurrPage"];
$this_page++;
$start		=	($this_page-1)*$bk; //本页记录开始位置，数组从0开始
$end		=	$this_page*$bk-1; //本页记录结束位置，数组从0开始，所以要减1

$match_names=	array();
$info_array	=	array();

if(isset($lqgq) && !empty($lqgq)){
	$zqcount = count($lqgq);
	for($i=0; $i<$zqcount; $i++){
		$rows[] = $lqgq[$i];      ////保留所有的数据
		$match_names[] = $lqgq[$i]['Match_Name'];    ////只保留联赛名
	}
}

$match_name_array = array_values(array_unique($match_names));
if(@$_GET["leaguename"]<>""){
	$leaguename = explode("$",urldecode($_GET["leaguename"]));
	$nums_arr = count($rows);
	for($i=0; $i<$nums_arr; $i++){
		if(in_array($rows[$i]["Match_Name"],$leaguename)){
			$info1[] = $rows[$i];
		}
	}
	$rows = $info1;
}


$count_num = count($rows);
if($count_num == "0"){
	$json["dh"]	=	0;
	$json["fy"]["p_page"]	=	0;
}else{		
	$json["fy"]["p_page"]	=	ceil($count_num/$bk); //总页数
	$json["fy"]["page"] 	=	$this_page-1;
	//联赛名字
	$i	=	0;
	$lsm=	'';
	foreach($match_name_array as $t){
		$lsm	.=	urlencode($t).'|';
		$i++;
	}
	$json["lsm"]=	rtrim($lsm,'|');
	$json["dh"]	=	ceil($i/2)*30; //窗口高度 px 值
	if($end > $count_num-1) $end = $count_num-1;
	$i	=	0;
	for($b=$start; $b<=$end; $b++){ 
		$json["db"][$i]["Match_ID"]			=	$rows[$b]["Match_ID"];
		$json["db"][$i]["Match_Master"]		=	$rows[$b]["Match_Master"];
		$json["db"][$i]["Match_Guest"]		=	$rows[$b]["Match_Guest"];
		$json["db"][$i]["Match_Name"]		=	$rows[$b]["Match_Name"];
		$json["db"][$i]["Match_Time"]		=	$rows[$b]["Match_Time"];
		$json["db"][$i]["Match_Ho"]			=	$rows[$b]["Match_Ho"];
		$json["db"][$i]["Match_DxDpl"]		=	$rows[$b]["Match_DxDpl"];
		$json["db"][$i]["Match_DsDpl"]		=	$rows[$b]["Match_DsDpl"];
		$json["db"][$i]["Match_Ao"]			=	$rows[$b]["Match_Ao"];
		$json["db"][$i]["Match_DxXpl"]		=	$rows[$b]["Match_DxXpl"];
		$json["db"][$i]["Match_DsSpl"]		=	$rows[$b]["Match_DsSpl"];
		$json["db"][$i]["Match_RGG"]		=	$rows[$b]["Match_RGG"];
		$json["db"][$i]["Match_DxGG1"]		=	"O".$rows[$b]["Match_DxGG"];
		$json["db"][$i]["Match_ShowType"]	=	$rows[$b]["Match_ShowType"];
		$json["db"][$i]["Match_DxGG2"]		=	"U".$rows[$b]["Match_DxGG"];
		$i++;
	}
}
echo $callback."(".json_encode($json).");";
?>