<?php
 //用户如果处于离线状态，则不允许投注
if(empty($_SESSION['uid']))
{
	echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
	echo '<script type="text/javascript">top.location.href="/";</script>';
	exit;
}
include_once dirname(__FILE__) . '/../include/site_config.php';
include_once dirname(__FILE__) . "/../wh/site_state.php";
GetSiteStatus($SiteStatus,1,'lottery',1);
//判断玩法是否关闭
if(is_numeric($_GET['type'])){
	include("../include/config.php");
	include("../include/public_config.php");

    $g_type = trim(@$_GET["g_type"]);

    // if(@$_GET['type']==7){

    //     echo '<script type="text/javascript">alert("玩法维护中，请选择其他玩法");</script>';
    //     echo '<script type="text/javascript">top.location.href="/index.php?a=lottery";</script>';
    //     exit;
    // }
	switch ($_GET['type']) {
		case '1':
			$type_name = "fc_3d";
			break;
		case '2':
			$type_name = "pl_3";
			break;
		case '3':
			$type_name = "cq_ssc";
			break;
		case '4':
			$type_name = "bj_8";
			break;
		case '5':
			$type_name = "bj_10";
			break;
		case '6':
			$type_name = "liuhecai";
			break;
		case '7':
			$type_name = "gd_ten";
			break;
		case '8':
			$type_name = "cq_ten";
			break;
		case '10':
			$type_name = "tj_ssc";
			break;
		case '11':
			$type_name = "jx_ssc";
			break;
		case '12':
			$type_name = "xj_ssc";
			break;
		case '13':
			$type_name = "js_k3";
			break;
		case '14':
			$type_name = "jl_k3";
			break;
		default:
			# code...
			break;
	}

	$is_lock = M("fc_state",$db_config)->field("name")->where("state = 0 and type = '" . $type_name . "'")->select();
	if($is_lock){
		echo '<script type="text/javascript">alert("玩法维护中，请选择其他玩法");</script>';
		echo '<script type="text/javascript">top.location.href="/index.php?a=lottery";</script>';
		exit;
	}
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>彩票</title>
<noscript>
	<meta http-equiv="refresh" content="0;url=./error.php">
</noscript>
<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
<link rel="stylesheet" href="./public/css/xp.css" type="text/css">
<style>
html, body, *html,div {
	width:100%;
	height:100%;
	margin:0px;
	padding:0px;
}
#Content {
	position:absolute;
	bottom:0;
	left:0;
	right:0;
	z-index:3;
	width:100%;
	height:100%;
}
</style>

<script type="text/javascript">
	function ResWin(){
	window.moveTo(0,0) ;
	window.resizeTo(screen.availWidth,screen.availHeight);
}
</script>
<script language="javascript" type="text/javascript">
if (self != top){
	top.location.href = self.location.href;
}
</script>
</head>
<body style="margin:0px;padding:0px;overflow:hidden;">

<div id="Content">
<iframe name="SI_func" id="SI_func" src="./main.php?type=<?=$_GET['type']?>" scrolling="NO" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%"></iframe>
</div>


</body></html>