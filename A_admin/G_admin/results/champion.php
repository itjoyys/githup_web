<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../include/public_config.php");


$sql  = "select x_id from t_guanjun where x_id>0";

$sqlwhere   = '';
$_GET['date']=!empty($_GET['date'])?$_GET['date']:date('Y-m-d');
$_GET['type']=!empty($_GET['type'])?$_GET['type']:3;

if(isset($_GET["type"])){
  if($_GET["type"] < 3) $sqlwhere .= " and match_type=".$_GET["type"];
}
if(@$_GET["x_title"]){
  $sqlwhere .=  " and x_title='".$_GET["x_title"]."'";
}
if(@$_GET['date']){
  $sqlwhere .=  " and match_coverdate like('".$_GET['date']."%')";
}

$sql    .=  $sqlwhere;
$sql    .=  " order by match_coverdate desc,x_id desc";

$query    = $mysqli->query($sql);
$sum    = $mysqli->affected_rows; //总页数


//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($sum/$perNumber); //计算出总页数
$thisPage=isset($_GET['page'])?$_GET['page']:1;
$startCount=($thisPage-1)*$perNumber; //分页开始,

?>
<?php $title="冠军"; require("../common_html/header.php");?>
<script language="javascript">
function go(value)
{
location.href=value;
}
</script>
<style type="text/css">
.STYLE2 {font-size: 12px}
body {
	FONT-SIZE: 12px;
	margin-left: 4px;
	margin-top: 0px;
	margin-right: 0px;
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px; SCROLLBAR-FACE-COLOR: #59D6FF; PADDING-BOTTOM: 0px; SCROLLBAR-HIGHLIGHT-COLOR: #ffffff; SCROLLBAR-SHADOW-COLOR: #ffffff; SCROLLBAR-3DLIGHT-COLOR: #007BC6; SCROLLBAR-DARKSHADOW-COLOR: #007BC6; SCROLLBAR-ARROW-COLOR: #007BC6; PADDING-TOP: 0px; SCROLLBAR-TRACK-COLOR: #009ED2;
}
td{font:13px/120% "宋体";padding:0px;}
a{
	color:#F37605;
	text-decoration: none;
}
.m_tab td, .m_tab_2 td {
 padding: 0; 
}
.t-title{background:url(../images/06.gif);height:24px;}
.t-tilte td{font-weight:800;}
</STYLE>
  <link rel="stylesheet" type="text/css" href="../public/css/mem_body_result.css">
   <link rel="stylesheet" type="text/css" href="../public/css/mem_body_ft.css">
   <script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
</HEAD>

<body>
<script language="JavaScript" src="../../js/calendar.js"></script>
<div  id="con_wrap">
   <div  class="input_002">冠军管理</div>
   <div  class="con_menu">
     <form  name="form1" method="GET" action="#" id="myFORM">
       <a href="football.php" target="_self">足球</a> 
      <a href="basketball.php" target="_self">籃球</a> 
      <a href="tennis.php" target="_self">網球</a> 
      <a href="volleyball.php" target="_self">排球</a> 
      <a href="basebal.php" target="_self">棒球</a> 
      <a href="champion.php" target="_self">冠军</a> 
   
           <select name="type" id="type">
            <option value="2" <?=$_GET['type']==2 ? 'selected' : ''?>>金融项目</option>
            <option value="1" <?=$_GET['type']==1 ? 'selected' : ''?>>冠军项目</option>
            <option value="3" <?=$_GET['type']==3 ? 'selected' : ''?>>全部项目</option>
          </select></td>
          <td width="154">日期：
            <input name="date" type="text" id="date" value="<?=@$_GET['date']?>"  onClick="WdatePicker()" size="10" maxlength="10" readonly="readonly" class="Wdate" /></td>
            <td width="246">联赛名：
              <input name="x_title" type="text" id="x_title" size="20" value="<?=@$_GET["x_title"]?>">
              <label></label></td>
          
          <input name="find" type="submit" class="za_button" id="find" value="查找"/>
            每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
    <option value="20" <?=select_check(20,$perNumber)?>>20条</option>
    <option value="30" <?=select_check(30,$perNumber)?>>30条</option>
    <option value="50" <?=select_check(50,$perNumber)?>>50条</option>
    <option value="100" <?=select_check(100,$perNumber)?>>100条</option>
  </select>
  &nbsp;頁數：
 <select id="page" name="page" class="za_select"> 
  <?php  

    for($i=1;$i<=$totalPage;$i++){
      if($i==$thisPage){
        echo  '<option value="'.$i.'" selected>'.$i.'</option>';
      }else{
        echo  '<option value="'.$i.'">'.$i.'</option>';
      }  
    } 
   ?>
  </select> <?=$totalPage ;?> 頁&nbsp;<br>
      </form>
  </div>
  </div>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC" style="float:left">
   
  <tr>
    <td height="24" nowrap bgcolor="#FFFFFF">
      <table width="100%" border="1" bgcolor="#FFFFFF" bordercolor="#96B697" cellspacing="0" cellpadding="0" style="border-collapse: collapse; color: #225d9c;" class="m_tab" width="100%" >
      <tr class="m_title_over_co" align="center" >
        <td width="36" ><strong>编号</strong></td>
        <td width="359"><strong>赛事项目名称</strong></td>
        <td width="36" ><strong>数量</strong></td>
        <td width="156" ><strong>封盘时间</strong></td>
        <td width="256" ><strong>比赛结果</strong></td>
        <td width="156" ><strong>添加时间</strong></td><!--
        <td width="106" ><strong>操作</strong></td>-->
        </tr>

<?php


$xid		=	'';
$i			=	1; //记录 uid 数
$start		=	($thisPage-1)*20+1;
$end		=	$thisPage*20;
while($row = $query->fetch_array()){
  if($i >= $start && $i <= $end){
	$xid .=	$row['x_id'].',';
  }
  if($i > $end) break;
  $i++;
}
if($xid){
	$xid	=	rtrim($xid,',');
	$sql	=	"select * from t_guanjun left join (select count(*) as num,xid from t_guanjun_team group by xid) as t on t_guanjun.x_id=t.xid where x_id in($xid) order by match_coverdate desc,x_id desc";
    $query	=	$mysqli->query($sql);
	while($rows = $query->fetch_array()){
      	?>
	        <tr align="center"  class="m_cen" onMouseOver="this.style.backgroundColor='#f6e9e9'" onMouseOut="this.style.backgroundColor='#ffffff'" style="background-color:#FFFFFF;">
	          <td height="40" ><?=$rows["x_id"]?></td>
              <td><strong><?=$rows["match_name"]?></strong>
			  <span style="color:#FF6600"><?=$rows["x_title"]?></span></td>
              <td><?=intval($rows["num"])?></td>
              <td><?=$rows["match_coverdate"]?></td>
              <td><? if($rows["x_result"]=="") echo "暂无结果"; else {?>
			  <font style="color:#FF0000"><?=$rows["x_result"]?></font>
			  <?}?>
			  </td>
              <td><?=$rows["add_time"]?></td>
             <?php /*<td><a href="set_result.php?id=<?=$rows["x_id"]?>">设置结果</a></td>*/ ?>
        </tr>  	
<?php
	}
}
?>
    </table></td>
  </tr>
 
</table>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>