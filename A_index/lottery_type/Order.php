<?php
header('Content-Type:text/html; charset=utf-8');
include_once dirname(__FILE__) . '/../include/site_config.php';
include_once dirname(__FILE__) . "/../wh/site_state.php";
GetSiteStatus($SiteStatus,1,'lottery',1);
include_once("../include/config.php");
$private_db = $db_config;
include ("../include/public_config.php");
include ("./include/ball_name.php");
include ("./include/order_info.php");
include ("./include/function.php");
include_once("../class/user.php");
//var_dump($_POST);var_dump($_GET);exit;


$uid = $_SESSION["uid"];
$userinfo =	user::getinfo($uid);
$username = $_SESSION["username"];
$uid=intval($uid);
$qishu = $_REQUEST['qishu'];

//清空所有POST数据为空的表单
$datas = array_filter($_POST);
$type_y = $_GET['type_y'];
//获取清空后的POST键名
$names = array_keys($datas);
// p($names);
// p($datas);exit;
//定义一个字符串变量
$text	  = '';
$text_top = '<table border="0" cellspacing="1" cellpadding="0" class="order_info"><tr><th>彩票种类</th><th>下注期号</th><th>彩票玩法</th><th>下注内容</th><th>下注金额</th><th>赔率</th><th>可赢金额</th></tr>';
$text_con = '';
$text_end = '</table>';

//用户如果处于离线状态，则不允许投注


		if(empty($_SESSION['uid']))
		{
			echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
			echo '<script type="text/javascript">window.parent.parent.topFrame.location.reload();</script>';
			exit;
		}
//-----------------------------------------------------------------------------------------------------------------

		$cpfsbl=0.1;//彩票反水比例

//广东快乐十分
if ($_GET['type']==1 ){
	$gtype='广东快乐十分';
	$type_y="7";//最近交易参数
	//获取期数
	/**************************************************/
}
//重庆时时彩
if ( $_GET['type']==2||$_GET['type']==9||$_GET['type']==10||$_GET['type']==11||$_GET['type']==12 ){
    switch ($_GET['type']) {
        case 2:
            $gtype = '重庆时时彩';
            $type_y = "3"; // 最近交易参数;
            break;
        case 9:
            $gtype = '上海时时彩';
            $type_y = "9"; // 最近交易参数;
            break;
        case 10:
            $gtype = '天津时时彩';
            $type_y = "10"; // 最近交易参数;
            break;
        case 11:
            $gtype = '江西时时彩';
            $type_y = "11"; // 最近交易参数;
            break;
       case 12:
            $gtype = '新疆时时彩';
            $type_y = "12"; // 最近交易参数;
            break;
        default:
            ;
            break;
    }

	/**************************************************/

}



//北京赛车PK拾
if ( $_GET['type']==3 ){
	$gtype='北京赛车PK拾';
	$type_y="5";//最近交易参数
	//获取期数

}
//重庆快乐十分
if ( $_GET['type']==4 ){
	$gtype='重庆快乐十分';
	$type_y="8";//最近交易参数
	//获取期数
/******************************************************************/
}
//福彩3D
if ( $_GET['type']==5 ){
	$gtype='福彩3D';
	$type_y="1";//最近交易参数
	//获取期数
	//$qishu	= lottery_qishu($_GET['type']);
}

//快3
if ( $_GET['type']==13 || $_GET['type']==14){
	if($_GET['type']==14){
		$gtype='吉林快3';
		$type_y="14";//最近交易参数
	}else{
		$gtype='江苏快3';
		$type_y="13";
	}
/******************************************************************/
}

//排列三
if ( $_GET['type']==6 ){
	$gtype='排列三';
	$type_y="2";//最近交易参数
	//获取期数
	//$qishu	= lottery_qishu($_GET['type']);
/******************************************************************/

}

//北京快乐8
if ( $_GET['type']==8 ){
	$gtype='北京快乐8';
	$type_y="4";//最近交易参数
	//获取期数
	//$qishu	= lottery_qishu($_GET['type']);
	$qiu;
	//选二~选五检查
	$names_s=array();
	for($ii=0; $ii < count($names); $ii++ )
	{
			$qiu1	= explode("_",$names[$ii]);
			if($qiu1[0]!='ball'){continue;}
			// if($qiu[1] > 1  && $qiu[1]<6)
			$names_s[]=$names[$ii];
	}

	if(count($names_s)>0){
		$qiu	= explode("_",$names_s[0]);
// 		if($qiu[1]==2 && count($names_s)!=2){
// 			echo '<script type="text/javascript">alert("【选二】请选择二个号码！","投注失败");</script>';
// 			exit;
// 		}else if($qiu[1]==3 && count($names_s)!=3){
// 			echo '<script type="text/javascript">alert("【选三】请选择三个号码！","投注失败");</script>';
// 			exit;
// 		}else if($qiu[1]==4 && count($names_s)!=4){
// 			echo '<script type="text/javascript">alert("【选四】请选择四个号码！","投注失败");</script>';
// 			exit;
// 		}else if($qiu[1]==5 && count($names_s)!=5){
// 			echo '<script type="text/javascript">alert("【选五】请选择五个号码！","投注失败");</script>';
// 			exit;
// 		}
	}
	$qiufunction = $qiu;
}




$text = $text_top.$text_con.$text_end;




function str_leng($str){ //取字符串长度
	mb_internal_encoding("UTF-8");
	return mb_strlen($str)*12;
}

?>

<?php

if(!empty($_GET['action_h']) && $_GET['action_h'] == 'ok'){
// var_dump($_GET);


if($_GET['type_h']=="1"){
	$type_h='广东快乐十分';
}elseif ($_GET['type_h']=="2") {
	$type_h='重庆时时彩';
}elseif ($_GET['type_h']=="9") {
	$type_h='上海时时彩';
}elseif ($_GET['type_h']=="10") {
	$type_h='天津时时彩';
}elseif ($_GET['type_h']=="11") {
	$type_h='江西时时彩';
}elseif ($_GET['type_h']=="12") {
	$type_h='新疆时时彩';
}elseif ($_GET['type_h']=="3") {
	$type_h='北京赛车PK拾';
}elseif ($_GET['type_h']=="4") {
	$type_h='重庆快乐十分';
}elseif ($_GET['type_h']=="5") {
	$type_h='福彩3D';
}elseif ($_GET['type_h']=="6") {
	$type_h='排列三';
}elseif ($_GET['type_h']=="8") {
	$type_h='北京快乐8';
}elseif ($_GET['type_h']=="13") {
	$type_h='江苏快3';
}elseif ($_GET['type_h']=="14") {
	$type_h='吉林快3';
}

if($_REQUEST['type_h']==1)exit('<script type="text/javascript">alert("广东快乐十分已经封盘，禁止下注！");</script>');
 ?>


<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<link rel="stylesheet" href="public/css/mem_order_ft.css" type="text/css">
</head>
<body>
<script type="text/javascript">
parent.parent.k_memr.location.reload();
 </script>
	<div class="ord">
    <div class="title"><h1><?=$type_h ?></h1></div>

    <div class="main">
      <div class="fin_title">
          <p class="fin_acc">成功提交注单！</p>
          <!--p class="fin_uid"></p-->
          <p class="error" style="display: none;"></p>
         </div>
             <?php
                $wanfa =explode('_', $_GET['wanfa']);
                $odds =explode('_', $_GET['odds']);
                $money = explode('_', $_GET['money']);;
                foreach ($wanfa as $k =>$v){ ?>
		        <p class="fin_team">  <?=$_GET['wanfa_h'] ?>


          <?=$v;   ?>@<strong><?=$odds[$k]?></strong></p>
		 <p class="fin_amount">交易金额：<span class="fin_gold"><?=$money[$k] ?></span></p>
		 <?php } ?>
		 <p class="fin_amount">总交易金额：<span class="fin_gold"><?=$_GET['money_h'] ?></span></p>
          </div>


    <div class="betBox">
      <input type="button" name="PRINT" value="确定" onClick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="print">
      <input type="button" name="FINISH" value="关闭" onClick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="close">
    </div>

  </div>
</body>
</html>

<?php }else{
	$vsqishu = dq_qishu($_GET['type'],$db_config);
	$fengpan = get_fengpan($_GET['type'],$db_config);
	if($vsqishu==-1){
		echo '<script type="text/javascript">tip("已经封盘，禁止下注！");</script>';
		exit;
	}
	if($_GET['type']==3 || $_GET['type']==8){
	if($_REQUEST['qishu']!=$vsqishu || $fengpan){

	    echo '<script type="text/javascript">alert("下注已过期，请重新下注");</script>';
	    echo '<script type="text/javascript">parent.location.href="main_left.php?type_y='.$type_y.'"</script>';
	    exit;
	}
	}else{
			if($_REQUEST['qishu']!=$vsqishu){

	    echo '<script type="text/javascript">alert("下注已过期，请重新下注");</script>';
	    echo '<script type="text/javascript">parent.location.href="main_left.php?type_y='.$type_y.'"</script>';
	    exit;
	}
		}
	//判断会员账户额度是否大于总投注额度
	$allmoney = 0;
	for ($i = 0; $i < count($datas)-1; $i++){
		$qiu	= explode("_",$names[$i]);
		if($qiu[0]!='ball'){continue;}
		$allmoney += $datas[''.$names[$i].''];
	}

	$edu = user_money($username,$allmoney,0);
	if($edu==-1){
		echo '<script type="text/javascript">alert("您的账户额度不足进行本次投注，请充值后在进行投注！","投注失败");</script>';
		exit;
	}
    addlottery_bet($datas, $names, $qishu, $gtype, $type_y,$private_db,$ball_name_s, $ball_name, $ball_name_zh,$ball_name_h,$ball_name_f,$ball_name_g,$qiufunction,$names_s);
} ?>

