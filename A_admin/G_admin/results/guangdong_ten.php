<?php
include_once("../../include/config.php");
include("../common/login_check.php");
include("../../include/public_config.php");
include("auto_class.php");
include_once("../../lib/class/model.class.php");
$type=1;
$map='';
$b=M("c_auto_".$type."",$db_config);
//时间检索
if(!empty($_GET['date'])){
    $map="datetime like '%".$_GET['date']."%'";
}

if(!empty($_GET['date'])&&$_GET['qishu']){
  $map.=" and";
}

//期数
if($_GET['qishu']){
  $map.= " qishu='$_GET[qishu]'";
}


$count=$b->field("id")->where($map)->count();//获得记录总数

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
// $id	=	0;
// if($_GET['id'] > 0){
// 	$id	=	$_GET['id'];
// }
// if($_REQUEST['page']==''){
// 	$_REQUEST['page']=1;
// }
// if($_GET["action"]=="add" && $id==0){ 
// 	$qishu		=	$_GET["qishu"];
// 	$datetime	=	$_GET["datetime"];
// 	$ball_1		=	$_GET["ball_1"];
// 	$ball_2		=	$_GET["ball_2"];
// 	$ball_3		=	$_GET["ball_3"];
// 	$ball_4		=	$_GET["ball_4"];
// 	$ball_5		=	$_GET["ball_5"];
// 	$ball_6		=	$_GET["ball_6"];
// 	$ball_7		=	$_GET["ball_7"];
// 	$ball_8		=	$_GET["ball_8"];
// 	$sql		=	"insert into c_auto_1(qishu,datetime,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8) values (".$qishu.",'".$datetime."',".$ball_1.",".$ball_2.",".$ball_3.",".$ball_4.",".$ball_5.",".$ball_6.",".$ball_7.",".$ball_8.")";
// 	$mysqli->query($sql);
// }elseif($_GET["action"]=="edit" && $id>0){
// 	$qishu		=	$_GET["qishu"];
// 	$datetime	=	$_GET["datetime"];
// 	$ball_1		=	$_GET["ball_1"];
// 	$ball_2		=	$_GET["ball_2"];
// 	$ball_3		=	$_GET["ball_3"];
// 	$ball_4		=	$_GET["ball_4"];
// 	$ball_5		=	$_GET["ball_5"];
// 	$ball_6		=	$_GET["ball_6"];
// 	$ball_7		=	$_GET["ball_7"];
// 	$ball_8		=	$_GET["ball_8"];
// 	$sql		=	"update c_auto_1 set qishu=".$qishu.",datetime='".$datetime."',ball_1=".$ball_1.",ball_2=".$ball_2.",ball_3=".$ball_3.",ball_4=".$ball_4.",ball_5=".$ball_5.",ball_6=".$ball_6.",ball_7=".$ball_7.",ball_8=".$ball_8." where id=".$id."";
// 	$mysqli->query($sql);
// }
?>
<?php require("../common_html/header.php");?>
<!-- <link rel="stylesheet" href="../images/css/admin_style_1.css" type="text/css" media="all" />
<link rel="stylesheet" href="../public/css/xp.css" type="text/css" media="all" />
<link rel="stylesheet" href="../images/css/admin_style_1.css" type="text/css" media="all" /> -->
<script language="javascript" src="/js/jquery.js"></script>
<script language="javascript">
function check_submit(){
	if($("#qishu").val()==""){
		alert("请填写开奖期数");
		$("#qishu").focus();
		return false;
	}
	if($("#datetime").val()==""){
		alert("请填写开奖时间");
		$("#datetime").focus();
		return false;
	}
	if($("#ball_1").val()==""){
		alert("请选择第一球开奖号码");
		$("#ball_1").focus();
		return false;
	}
	if($("#ball_2").val()==""){
		alert("请选择第二球开奖号码");
		$("#ball_2").focus();
		return false;
	}
	if($("#ball_3").val()==""){
		alert("请选择第三球开奖号码");
		$("#ball_3").focus();
		return false;
	}
	if($("#ball_4").val()==""){
		alert("请选择第四球开奖号码");
		$("#ball_4").focus();
		return false;
	}
	if($("#ball_5").val()==""){
		alert("请选择第五球开奖号码");
		$("#ball_5").focus();
		return false;
	}
	if($("#ball_6").val()==""){
		alert("请选择第六球开奖号码");
		$("#ball_6").focus();
		return false;
	}
	if($("#ball_7").val()==""){
		alert("请选择第七球开奖号码");
		$("#ball_7").focus();
		return false;
	}
	if($("#ball_8").val()==""){
		alert("请选择第八球开奖号码");
		$("#ball_8").focus();
		return false;
	}
	return true;
}
         //下拉选项自动跳转
$(document).ready(function(){
   $("#cp_cate").change(function(){
       var cp_url=$("#cp_cate").val();
       window.location.href=cp_url; 
   });
})
</script>
</head>
<body>
  <div  id="con_wrap">
   <div  class="input_002">广东快乐十分开奖管理</div>
   <div  class="con_menu">
    <?php require("lottery_com_search.php");?>
  </div>
  </div>
<div id="pageMain">
  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td valign="top">
    <table width="100%" border="0" cellpadding="5" cellspacing="1" class="m_tab" style="margin-top:5px;" bgcolor="#798EB9">   
            <tr class="m_title_over_co">
              <td align="center"><strong>彩票类别</strong></td>
              <td align="center"><strong>彩票期号</strong></td>
              <td align="center"><strong>开奖时间</strong></td>
              <td align="center"><strong>第一球</strong></td>
              <td align="center"><strong>第二球</strong></td>
              <td align="center"><strong>第三球</strong></td>
              <td align="center"><strong>第四球</strong></td>
        <td height="25" align="center"><strong>第五球</strong></td>
        <td align="center"><strong>第六球</strong></td>
        <td align="center"><strong>第七球</strong></td>
        <td align="center"><strong>第八球</strong></td>
        <td align="center"><strong>总和</strong></td>
        <td align="center"><strong>龙虎</strong></td>
   <!--     <td align="center">结算</td>
        <td align="center"><strong>操作</strong></td>  -->
          </tr>
<?php

$data = $b->field("*")->where($map)->order(" qishu desc")->limit($limit)->select();
if(!empty($data)){
foreach ($data as $key => $rows) {
	$time	=	time();
	
		$color = "#FFFFFF";
	  	$over	 = "#EBEBEB";
	 	$out	 = "#ffffff";
		$hm 		= array();
		$hm[]		= BuLing($rows['ball_1']);
		$hm[]		= BuLing($rows['ball_2']);
		$hm[]		= BuLing($rows['ball_3']);
		$hm[]		= BuLing($rows['ball_4']);
		$hm[]		= BuLing($rows['ball_5']);
		$hm[]		= BuLing($rows['ball_6']);
		$hm[]		= BuLing($rows['ball_7']);
		$hm[]		= BuLing($rows['ball_8']);
/* 		if($rows['ok']==1){
			$ok = '<a href="js_1.php?qi='.$rows['qishu'].'" title="重新结算"><font color="#FF0000">已结算</font></a>';
		}else{
			$ok = '<a href="js_1.php?qi='.$rows['qishu'].'" title="点击结算"><font color="#0000FF">未结算</font></a>';
		} */
?>
      <tr align="center" onMouseOver="this.style.backgroundColor='<?=$over?>'" onMouseOut="this.style.backgroundColor='<?=$out?>'" style="background-color:<?=$color?>; line-height:20px;">
        <td height="25" align="center" valign="middle">广东快乐十分</td>
        <td align="center" valign="middle"><?=$rows['qishu']?></td>
        <td align="center" valign="middle"><?=$rows['datetime']?></td>
        <td align="center" valign="middle"> <div class="ball_r"><?=$rows['ball_1']?></div></td>
        <td align="center" valign="middle"> <div class="ball_r"><?=$rows['ball_2']?></div></td>
        <td align="center" valign="middle"><div class="ball_r"><?=$rows['ball_3']?></div></td>
        <td align="center" valign="middle"><div class="ball_r"><?=$rows['ball_4']?></div></td>
        <td align="center" valign="middle"><div class="ball_r"><?=$rows['ball_5']?></div></td>
        <td align="center" valign="middle"><div class="ball_r"><?=$rows['ball_6']?></div></td>
        <td align="center" valign="middle"><div class="ball_r"><?=$rows['ball_7']?></div></td>
        <td align="center" valign="middle"><div class="ball_r"><?=$rows['ball_8']?></div></td>
        <td><?=G10_Auto($hm,1)?> / <?=G10_Auto($hm,2)?> / <?=G10_Auto($hm,3)?> / <?=G10_Auto($hm,4)?></td>
        <td><?=G10_Auto($hm,5)?></td>
     <!--   <td><?=$ok?></td>
        <td><a href="?id=<?=$rows["id"]?>&page=<?=$_REQUEST['page']?>">编辑</a></td>  -->
        </tr>
      <?php      
	}
echo "<tr style='background-color:#FFFFFF;'>
        <td colspan='15' align='center' valign='middle'>$pageStr</td>
    </tr>";	
}else{
?>
	<tr style="background-color:#FFFFFF;">
        <td colspan="15" align="center" valign="middle">暂无数据</td>
    </tr>
<?}?>
    </table></td>
    </tr>
  </table>
</div>
</body>
</html>