<?php
/*ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);*/
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");  
header('Content-type: text/json; charset=utf-8');
include('../../include/filter.php');
include_once("../include/pd_user_json.php");
include_once("../../include/private_config.php");
include_once("../../include/redis_config.php");
include_once("../include/function_cj.php");
include("../include/function.php");
$d=RedisZQGQ();

$lasttime=$d['lasttime'];
$zqgq=$d['zqgq'];
$count=count($d['zqgq']);

if(time()-$lasttime > 40){ //超时
    $json["dh"]	=	0;
    $json["fy"]["p_page"]	=	0;
    echo $callback."(".json_encode($json).");";
    exit;
/*    include("../../cache/zqgq.php");
    if(zqgq_cj()){

        include("../../cache/zqgq.php"); //重新载入
    }else{

        $json["dh"]	=	0;
        $json["fy"]["p_page"] = "0";
        echo $callback."(".json_encode($json).");";
        exit;
    }*/
}

$bk			=	400; //每页显示记录总个数
$this_page	=	0; //当前页
if(intval($_GET["CurrPage"])>0) $this_page	=	$_GET["CurrPage"];
$this_page++;
$start		=	($this_page-1)*$bk; //本页记录开始位置，数组从0开始
$end		=	$this_page*$bk-1; //本页记录结束位置，数组从0开始，所以要减1

//页数统计
$match_names = array();
$info_array  = array();
if(isset($zqgq) && !empty($zqgq)){
	$zqcount = count($zqgq);
	//for($i=0; $i<$zqcount; $i++){
//		$rows[] = $zqgq[$i];      ////保留所有的数据
//		$match_names[] = $zqgq[$i]['Match_Name'];    ////只保留联赛名
//	}
	foreach($zqgq as $key=>$value){
		$rows[] = $value;//$zqgq[$i];      ////保留所有的数据
		$match_names[] = $value['Match_Name'];    ////只保留联赛名
	}
}

$match_name_array = array_values(array_unique($match_names));
if(@$_GET["leaguename"]<>""){
	$match_name	=	explode("$",urldecode($_GET["leaguename"]));
	$nums_arr	=	count($rows);
	for($i=0; $i<$nums_arr; $i++){
		if(in_array($rows[$i]["Match_Name"],$match_name)){
			$info1[] = $rows[$i];
		}
	}
	$rows = $info1;
}

$count_num = count($rows);

if($count_num == "0"){
	$json["dh"]	=	0;
	$json["fy"]["p_page"]	=	0;

}
else
{
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
        if(strpos('ceshi'.$rows[$b]["Match_Name"],'测试')===false){
            $json["db"][$i]["Match_ID"]			=	$rows[$b]["Match_ID"];     		   //  0
            $json["db"][$i]["Match_Master"]		=	$rows[$b]["Match_Master"];         //  1
            $json["db"][$i]["Match_Guest"]		=	$rows[$b]["Match_Guest"];          //  2
            $json["db"][$i]["Match_Name"]		=	$rows[$b]["Match_Name"];     	   //  3
            $json["db"][$i]["Match_Time"]		=	$rows[$b]["Match_Time"];           //  4
            $json["db"][$i]["Match_Ho"]			=	NOnull($rows[$b]["Match_Ho"]);     //  5
            $json["db"][$i]["Match_DxDpl"]		=	NOnull($rows[$b]["Match_DxDpl"]);  //  6
            $json["db"][$i]["Match_BHo"]		=	NOnull($rows[$b]["Match_BHo"]);    //  7
            $json["db"][$i]["Match_Bdpl"]		=	NOnull($rows[$b]["Match_Bdpl"]);   //  8
            $json["db"][$i]["Match_Ao"]			=	NOnull($rows[$b]["Match_Ao"]);     //  9
            $json["db"][$i]["Match_DxXpl"]		=	NOnull($rows[$b]["Match_DxXpl"]);  //  10
            $json["db"][$i]["Match_BAo"]		=	NOnull($rows[$b]["Match_BAo"]);    //  11
            $json["db"][$i]["Match_Bxpl"]		=	NOnull($rows[$b]["Match_Bxpl"]);   //  12
            $json["db"][$i]["Match_RGG"]		=	$rows[$b]["Match_RGG"];    //  13
            $json["db"][$i]["Match_BRpk"]		=	$rows[$b]["Match_BRpk"];    // 14
            $json["db"][$i]["Match_ShowType"]	=	$rows[$b]["Match_ShowType"]; // 15
            $json["db"][$i]["Match_Hr_ShowType"]=	$rows[$b]["Match_Hr_ShowType"]; // 16
            $json["db"][$i]["Match_DxGG"]		=	"O".$rows[$b]["Match_DxGG"];     	// 17
            $json["db"][$i]["Match_Bdxpk"]		=	"O".$rows[$b]["Match_Bdxpk"];      //   18
            $json["db"][$i]["Match_DxGG1"]		=	"U".$rows[$b]["Match_DxGG"];        //  19
            $json["db"][$i]["Match_Bdxpk2"]		=	"U".$rows[$b]["Match_Bdxpk"];     	//  20
            $json["db"][$i]["Match_HRedCard"]	=	NOkong($rows[$b]["Match_HRedCard"]); // 21
            $json["db"][$i]["Match_GRedCard"]	=	NOkong($rows[$b]["Match_GRedCard"]); // 22
            $json["db"][$i]["Match_NowScore"]	=	NOkong($rows[$b]["Match_NowScore"]); // 23
            $json["db"][$i]["Match_BzM"]		=	NOkong($rows[$b]["Match_BzM"]);     //  24
            $json["db"][$i]["Match_BzG"]		=	NOkong($rows[$b]["Match_BzG"]);     //  25
            $json["db"][$i]["Match_BzH"]		=	NOkong($rows[$b]["Match_BzH"]);     //  26
            $json["db"][$i]["Match_Bmdy"]		=	NOkong($rows[$b]["Match_Bmdy"]);    //  27
            $json["db"][$i]["Match_Bgdy"]		=	NOkong($rows[$b]["Match_Bgdy"]);    //  28
            $json["db"][$i]["Match_Bhdy"]		=	NOkong($rows[$b]["Match_Bhdy"]);    //  29
            $i++;
        }

	}

}

function NOnull($str){
	return $str>0?sprintf("%.2f",$str):0;
}
function NOkong($str2){
	return $str2<>""?$str2:0;
}
echo $callback."(".json_encode($json).");";
?>