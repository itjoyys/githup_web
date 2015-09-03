<?php
header('Content-type: text/json; charset=utf-8');
include_once("../../include/config.php");
include_once("../../include/public_config.php");
include_once("../../common/function.php");
$callback="";
$callback=@$_GET['callback'];
$this_page	=	0; //当前页
if(intval($_GET["CurrPage"])>0) $this_page	=	$_GET["CurrPage"];
$this_page++;
$bk			=	50; //每页显示多少条记录
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

$sql		=	"select id from bet_match WHERE Match_Type=0 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and match_date!='".date("m-d",strtotime("-12 hours"))."' ".$sqlwhere.' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn';
$query		=	$mysqls->query($sql);
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
	
	$sql	=	"select match_name from bet_match WHERE Match_Type=0 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) group by match_name";
	$query	=	$mysqls->query($sql);
	$i		=	0;
	$lsm	=	'';
	while($row = $query->fetch_array()){
		$lsm	.=	urlencode($row['match_name']).'|';
		$i++;
	}
	$json["lsm"]=	rtrim($lsm,'|');
	$json["dh"]	=	ceil($i/2)*30; //窗口高度 px 值
	
	//赛事数据
	$sql	=	"SELECT Match_ID, Match_HalfId, Match_Date, Match_Time, Match_Master, Match_Guest, Match_RGG, Match_Name, Match_IsLose, Match_BzM, Match_BzG, Match_BzH, Match_DxDpl, Match_DxXpl, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_Type,  Match_Bmdy, Match_BHo,  Match_Bdpl, Match_Bgdy, Match_BAo, Match_Bxpl, Match_Bhdy, Match_BRpk, Match_Hr_ShowType, Match_Bdxpk,Match_DsDpl, Match_DsSpl FROM Bet_Match where id in($id) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";
	$query	=	$mysqls->query($sql);
	$i		=	0;
	while($rows = $query->fetch_array()){ 
		$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];     ///////////  0
		$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];     ///////////   1
		$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];     ///////////    2
		$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];     ///////////     3
		$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
		if ($rows["Match_IsLose"]==1){
			$mdate.= "<br><font color='#FF0000'>滚球</font>";
		}
		$json["db"][$i]["Match_Date"]		=	$mdate;     ///////////               4
		$rows["Match_BzM"]<>""?$a=$rows["Match_BzM"]:$a=0;
		$json["db"][$i]["Match_BzM"]		=	$a;     ///////////       5
		double_format($rows["Match_Ho"])<>""?$b=double_format($rows["Match_Ho"]):$b=0;
		$json["db"][$i]["Match_Ho"]			=	$b;     ///////////6
		$rows["Match_DxDpl"]<>""?$c=$rows["Match_DxDpl"]:$c=0;
		$json["db"][$i]["Match_DxDpl"]		=	$c;     ///////////7
		//$rows["Match_DsDpl"]<>""?$d=$rows["Match_DsDpl"]:$d=0;
		//$json["db"][$i]["Match_DsDpl"]		=	$d;     ///////////8
		$rows["Match_BzG"]<>""?$e=$rows["Match_BzG"]:$e=0;
		$json["db"][$i]["Match_BzG"]		=	$e;     ///////////9
		$rows["Match_Ao"]<>""?$f=$rows["Match_Ao"]:$f=0;
		$json["db"][$i]["Match_Ao"]			=	$f;     ///////////10
		$rows["Match_DxXpl"]<>""?$g=$rows["Match_DxXpl"]:$g=0;
		$json["db"][$i]["Match_DxXpl"]		=	$g;     ///////////11
		//$rows["Match_DsSpl"]<>""?$h=$rows["Match_DsSpl"]:$h=0;
		//$json["db"][$i]["Match_DsSpl"]		=	$h;     ///////////12
		$rows["Match_BzH"]<>""?$k=$rows["Match_BzH"]:$k=0;
		$json["db"][$i]["Match_BzH"]		=	$k;     ///////////13
		$rows["Match_RGG"]<>""?$j=$rows["Match_RGG"]:$j=0;
		$json["db"][$i]["Match_RGG"]		=	$j;     ///////////14
		$rows["Match_DxGG"]<>""?$m="O".$rows["Match_DxGG"]:$m=0;
		$json["db"][$i]["Match_DxGG1"]		=	$m;     ///////////15
		$json["db"][$i]["Match_ShowType"]	=	$rows["Match_ShowType"];/////////16
		$rows["Match_DxGG"]<>""?$n="U".$rows["Match_DxGG"]:$n=0;
		$json["db"][$i]["Match_DxGG2"]		=	$n;     ///////////17
		$json["db"][$i]["Match_Bmdy"]		=	$rows["Match_Bmdy"];     ///////////     5
		$json["db"][$i]["Match_BHo"]		=	$rows["Match_BHo"];     ///////////     6
		$json["db"][$i]["Match_Bdpl"]		=	$rows["Match_Bdpl"];     ///////////     7
		$json["db"][$i]["Match_Bgdy1"]		=	$rows["Match_Bgdy"];     ///////////     8
		$json["db"][$i]["Match_Bgdy2"]		=	$rows["Match_Bgdy"];     ///////////     9
		$json["db"][$i]["Match_BAo"]		=	$rows["Match_BAo"];     ///////////     10
		$json["db"][$i]["Match_Bxpl"]		=	$rows["Match_Bxpl"];     ///////////     11
		$json["db"][$i]["Match_Bhdy1"]		=	$rows["Match_Bhdy"];     ///////////     12
		$json["db"][$i]["Match_Bhdy2"]		=	$rows["Match_Bhdy"];     ///////////     13
		$json["db"][$i]["Match_BRpk"]		=	$rows["Match_BRpk"];     ///////////     14
		$json["db"][$i]["Match_Bdxpk1"]		=	"O".$rows["Match_Bdxpk"];     ///////////     15
		$json["db"][$i]["Match_Hr_ShowType"]=	$rows["Match_Hr_ShowType"];     ///////////     16
		$json["db"][$i]["Match_Bdxpk2"]		=	"U".$rows["Match_Bdxpk"];     ///////////     17
		$rows["Match_DsDpl"]<>""?$d=$rows["Match_DsDpl"]:$d=0;
		$json["db"][$i]["Match_DsDpl"]		=	$d;     
		$rows["Match_DsSpl"]<>""?$h=$rows["Match_DsSpl"]:$h=0;
		$json["db"][$i]["Match_DsSpl"]		=	$h;  
		$i++;
	}
}
echo $callback."(".json_encode($json).");";
?>