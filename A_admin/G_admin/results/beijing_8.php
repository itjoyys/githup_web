<?php
include_once("../../include/config.php");
include("../common/login_check.php");
include("../../include/public_config.php");
include("auto_class.php");

$type=8;
$map='';
$b=M("c_auto_8",$db_config);
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

?>
<?php require("../common_html/header.php");?>
 <link rel="stylesheet" href="../images/css/admin_style_1.css" type="text/css" media="all" />
<link rel="stylesheet" href="../public/css/xp.css" type="text/css" media="all" /> 
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
		alert("请选择第1球开奖号码");
		$("#ball_1").focus();
		return false;
	}
	if($("#ball_2").val()==""){
		alert("请选择第2球开奖号码");
		$("#ball_2").focus();
		return false;
	}
	if($("#ball_3").val()==""){
		alert("请选择第3球开奖号码");
		$("#ball_3").focus();
		return false;
	}
	if($("#ball_4").val()==""){
		alert("请选择第4球开奖号码");
		$("#ball_4").focus();
		return false;
	}
	if($("#ball_5").val()==""){
		alert("请选择第5球开奖号码");
		$("#ball_5").focus();
		return false;
	}
	if($("#ball_6").val()==""){
		alert("请选择第6球开奖号码");
		$("#ball_6").focus();
		return false;
	}
	if($("#ball_7").val()==""){
		alert("请选择第7球开奖号码");
		$("#ball_7").focus();
		return false;
	}
	if($("#ball_8").val()==""){
		alert("请选择第8球开奖号码");
		$("#ball_8").focus();
		return false;
	}
	if($("#ball_9").val()==""){
		alert("请选择第9球开奖号码");
		$("#ball_9").focus();
		return false;
	}
	if($("#ball_10").val()==""){
		alert("请选择第10球开奖号码");
		$("#ball_10").focus();
		return false;
	}
	if($("#ball_11").val()==""){
		alert("请选择第11球开奖号码");
		$("#ball_11").focus();
		return false;
	}
	if($("#ball_12").val()==""){
		alert("请选择第12球开奖号码");
		$("#ball_12").focus();
		return false;
	}
	if($("#ball_13").val()==""){
		alert("请选择第13球开奖号码");
		$("#ball_13").focus();
		return false;
	}
	if($("#ball_14").val()==""){
		alert("请选择第14球开奖号码");
		$("#ball_14").focus();
		return false;
	}
	if($("#ball_15").val()==""){
		alert("请选择第15球开奖号码");
		$("#ball_15").focus();
		return false;
	}
	if($("#ball_16").val()==""){
		alert("请选择第16球开奖号码");
		$("#ball_16").focus();
		return false;
	}
	if($("#ball_17").val()==""){
		alert("请选择第17球开奖号码");
		$("#ball_17").focus();
		return false;
	}
	if($("#ball_18").val()==""){
		alert("请选择第18球开奖号码");
		$("#ball_18").focus();
		return false;
	}
	if($("#ball_19").val()==""){
		alert("请选择第19球开奖号码");
		$("#ball_19").focus();
		return false;
	}
	if($("#ball_20").val()==""){
		alert("请选择第20球开奖号码");
		$("#ball_20").focus();
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
   <div  class="input_002">北京快乐8开奖管理</div>
   <div  class="con_menu">
   <!--  彩票开奖结果公共检索 -->
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
              <td align="center"><strong>1球</strong></td>
              <td align="center"><strong>2球</strong></td>
              <td align="center"><strong>3球</strong></td>
              <td align="center"><strong>4球</strong></td>
        <td height="25" align="center"><strong>5球</strong></td>
        <td align="center"><strong>6球</strong></td>
        <td align="center"><strong>7球</strong></td>
        <td align="center"><strong>8球</strong></td>
        <td align="center"><strong>9球</strong></td>
        <td align="center"><strong>10球</strong></td>
		<td align="center"><strong>11球</strong></td>
              <td align="center"><strong>12球</strong></td>
              <td align="center"><strong>13球</strong></td>
              <td align="center"><strong>14球</strong></td>
        <td height="25" align="center"><strong>15球</strong></td>
        <td align="center"><strong>16球</strong></td>
        <td align="center"><strong>17球</strong></td>
        <td align="center"><strong>18球</strong></td>
        <td align="center"><strong>19球</strong></td>
        <td align="center"><strong>20球</strong></td>
          </tr>
<?php
$data = $b->field("*")->where($map)->order(" qishu desc")->limit($limit)->select();


if(!empty($data)){
foreach ($data as $key => $rows) {


	
	$time	=	time();
	
		$color = "#FFFFFF";
	  	$over	 = "#EBEBEB";
	 	$out	 = "#ffffff";
?>
      <tr align="center" onMouseOver="this.style.backgroundColor='<?=$over?>'" onMouseOut="this.style.backgroundColor='<?=$out?>'" style="background-color:<?=$color?>; line-height:20px;">
        <td height="25" align="center" valign="middle">北京快乐8</td>
        <td align="center" valign="middle"><?=$rows['qishu']?></td>
        <td align="center" valign="middle"><?=$rows['datetime']?></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_1']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_2']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_3']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_4']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_5']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_6']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_7']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_8']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_9']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_10']?></div></td>
		 <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_11']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_12']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_13']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_14']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_15']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_16']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_17']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_18']?></div></td>
        <td align="center" valign="middle">  <div class="ball_r"><?=$rows['ball_19']?></div></td>
        <td align="center" valign="middle"> <div class="ball_r"><?=$rows['ball_20']?></div></td>

        </tr>
      <?php      
	}

}else{
?>
	<tr style="background-color:#FFFFFF;">
        <td colspan="25" align="center" valign="middle">暂无数据</td>
    </tr>
<?}?>
    </table></td>
    </tr>
  </table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>