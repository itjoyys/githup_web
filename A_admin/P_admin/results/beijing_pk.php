<?php
include_once("../../include/config.php");
include("../common/login_check.php");
include("../../include/public_config.php");
include("auto_class.php");

$type=3;
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
		alert("请选择冠军车号");
		$("#ball_1").focus();
		return false;
	}
	if($("#ball_2").val()==""){
		alert("请选择亚军车号");
		$("#ball_2").focus();
		return false;
	}
	if($("#ball_3").val()==""){
		alert("请选择第三名车号");
		$("#ball_3").focus();
		return false;
	}
	if($("#ball_4").val()==""){
		alert("请选择第四名车号");
		$("#ball_4").focus();
		return false;
	}
	if($("#ball_5").val()==""){
		alert("请选择第五名车号");
		$("#ball_5").focus();
		return false;
	}
	if($("#ball_6").val()==""){
		alert("请选择第六名车号");
		$("#ball_6").focus();
		return false;
	}
	if($("#ball_7").val()==""){
		alert("请选择第七名车号");
		$("#ball_7").focus();
		return false;
	}
	if($("#ball_8").val()==""){
		alert("请选择第八名车号");
		$("#ball_8").focus();
		return false;
	}
	if($("#ball_9").val()==""){
		alert("请选择第九名车号");
		$("#ball_9").focus();
		return false;
	}
	if($("#ball_10").val()==""){
		alert("请选择第十名车号");
		$("#ball_10").focus();
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
   <div  class="input_002">北京赛车PK拾开奖管理</div>
   <div  class="con_menu">
    <?php require("lottery_com_search.php");?>
  </div>
  </div>
<div id="pageMain">
  <table width="1350px" height="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td valign="top">
    <table width="100%" border="0" cellpadding="5" cellspacing="1" class="m_tab" style="margin-top:5px;" bgcolor="#798EB9">   
            <tr class="m_title_over_co">
              <td align="center"><strong>彩票类别</strong></td>
              <td align="center"><strong>彩票期号</strong></td>
              <td align="center"><strong>开奖时间</strong></td>
              <td align="center"><strong>冠军</strong></td>
              <td align="center"><strong>亚军</strong></td>
              <td align="center"><strong>第三名</strong></td>
              <td align="center"><strong>第四名</strong></td>
        <td height="25" align="center"><strong>第五名</strong></td>
        <td align="center"><strong>第六名</strong></td>
        <td align="center"><strong>第七名</strong></td>
        <td align="center"><strong>第八名</strong></td>
        <td align="center"><strong>第九名</strong></td>
        <td align="center"><strong>第十名</strong></td>
  
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
        <td height="25" align="center" valign="middle">北京赛车PK拾</td>
        <td align="center" valign="middle"><?=$rows['qishu']?></td>
        <td align="center" valign="middle"><?=$rows['datetime']?></td>
        <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_1']?></div></td>
        <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_2']?></div></td>
        <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_3']?></div></td>
       <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_4']?></div></td>
        <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_5']?></div></td>
        <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_6']?></div></td>
        <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_7']?></div></td>
        <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_8']?></div></td>
        <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_9']?></div></td>
        <td align="center" valign="middle" style="margin:0;padding:0px;"><div class="ball_r" style="width:65px;"><?=$rows['ball_10']?></div></td>
   
        </tr>
      <?php      
	}

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
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>