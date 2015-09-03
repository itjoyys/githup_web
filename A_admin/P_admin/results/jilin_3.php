<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../include/public_config.php");
include("auto_class.php");

$type=14;
$map='';

$b=M("c_auto_".$type."",$db_config);
//时间检索
$date=$_GET['date'];
if(empty($date)) $date=date('Y-m-d');
if(!empty($date)){
    $map="datetime like '%".$date."%'";
}

if(!empty($_GET['date'])&&$_GET['qishu']){
  $map.=" and";
}
//echo $map;
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
   <div  class="input_002">吉林快3开奖管理</div>
   <div  class="con_menu">
          <!--  彩票开奖结果公共检索 -->
    <?php require("lottery_com_search.php");?>
  </div>
  </div>
<div id="pageMain">

    <table width="100%" border="0" cellpadding="5" cellspacing="1"  class="m_tab" style="margin-top:5px;" bgcolor="#798EB9">   
            <tr class="m_title_over_co" >
              <td align="center"><strong>彩票类别</strong></td>
              <td align="center"><strong>彩票期号</strong></td>
              <td align="center"><strong>开奖时间</strong></td>
              <td align="center"><strong>第一球</strong></td>
              <td align="center"><strong>第二球</strong></td>
              <td align="center"><strong>第三球</strong></td>    
              <td align="center"><strong>和值</strong></td>
          </tr>
<?php

$data = $b->field("*")->where($map)->order("qishu desc")->limit($limit)->select();
if(!empty($data)){
foreach ($data as $key => $rows) {
		$color = "#FFFFFF";
	  $over	 = "#EBEBEB";
	 	$out	 = "#ffffff";
		$hm 		= array();
		$hm[]		= $rows['ball_1'];
		$hm[]		= $rows['ball_2'];
		$hm[]		= $rows['ball_3'];
?>
      <tr align="center" onMouseOver="this.style.backgroundColor='#f6e9e9'" onMouseOut="this.style.backgroundColor='#ffffff'" style="background-color:#FFFFFF; line-height:20px;">
        <td height="25" align="center" valign="middle">吉林快3</td>
        <td align="center" valign="middle"><?=$rows['qishu']?></td>
        <td align="center" valign="middle"><?=$rows['datetime']?></td>
        <td align="center" valign="middle">
           <div class="ball_r"><?=$rows['ball_1']?></div>
        </td>
        <td align="center" valign="middle"> <div class="ball_r"><?=$rows['ball_2']?></div></td>
        <td align="center" valign="middle"> <div class="ball_r"><?=$rows['ball_3']?></div></td>
        <td><?=FC3D_Auto($hm,0)?> / <?=FC3D_Auto($hm,12)?> / <?=FC3D_Auto($hm,13)?></td>
        </tr>
<?php
	}
 }
?>
    </table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>