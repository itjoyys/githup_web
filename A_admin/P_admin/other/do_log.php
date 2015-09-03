<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

$map="site_id = '".SITEID."'";

//日期条件
if (!empty($_GET['date_start']) && !empty($_GET['date_end'])) {
	$_GET['date_start'] = $_GET['date_start'].' 00:00:00';
	$_GET['date_end'] = $_GET['date_end'].' 23:59:59';
	$map.=" and log_time>"."'".$_GET['date_start']."' and log_time<="."'".$_GET['date_end']."'";
	$start_date = $_GET['date_start'];
	$end_date = $_GET['date_end'];
}else{
	$start_date = $end_date = date('Y-m-d');
	$map.=" and log_time like '".$start_date."%'";
}

//账号检索
if (!empty($_GET['username'])) {
	$admin_id=M('sys_admin',$db_config)->field('uid')->where("login_name like '%".$_GET['username']."%'")->find();
		if($map!=''&&$admin_id['uid']!=''){
			$map.=" and uid = ".$admin_id['uid'];
		}
}

$u = M('sys_log',$db_config);
$log_id= $u->field('log_id')->where($map)->order('log_id DESC')-> select();
$sum = count($log_id);
$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
$totalPage=ceil($sum/$pagenum); //计算出总页数

$startCount=($CurrentPage-1)*$pagenum; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$pagenum;

$sys_log=$u->where($map)->limit($limit)->order('log_id DESC')->select();
// p($sys_log);
$page = $u->showPage($totalPage,$CurrentPage);;
?>
<?php $title="操作记录"; require("../common_html/header.php");?>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
<div id="con_wrap">
<div class="input_002">操作記錄</div>
<div class="con_menu">
<form id="myFORM" action="" method="get" name="FrmData">
	&nbsp;&nbsp;日期：
	<input type="text" id="date_start" value="<?=substr($start_date, 0,10)?>" name="date_start"  onClick="WdatePicker()"  class="za_text Wdate">
	--
	<input type="text" id="date_end"  value="<?=substr($end_date, 0,10)?>" name="date_end" onClick="WdatePicker()" class="za_text Wdate">
	&nbsp;&nbsp;操作者：
	<input name="username" id="username" value="<?=$_GET['username']?>" type="text" class="za_text">
	<input type="SUBMIT" value="確定" class="za_button">
     每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
    <option value="20" <?=select_check(20,$pagenum)?>>20条</option>
    <option value="30" <?=select_check(30,$pagenum)?>>30条</option>
    <option value="50" <?=select_check(50,$pagenum)?>>50条</option>
    <option value="100" <?=select_check(100,$pagenum)?>>100条</option>
  </select>
  &nbsp;<?=$page?>


</form></div>
</div>
<div class="content">
	<table class="m_tab" cellspacing="0" cellpadding="0" width="100%" border="0" style="display:none;">
	  <tbody><tr class="m_title_over_co">
		<td height="70" align="center"><font color="#3B2D1B">未有資料。</font></td>
	  </tr>
	</tbody></table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="m_tab" bgcolor="#3B2D1B" style="display:;">
			<tbody>
			<tr class="m_title">
			    <td width="40">ID</td>
				<td width="150">日期</td>
				<td width="40">操作者</td>
				<td>記錄</td>				
			</tr>

			<?foreach($sys_log as $v){?>
			<tr class="m_rig" align="left">
			    <td align="center"><?=$v['log_id']?></td>
				<td align="center"><?=$v['log_time']?></td>
				<td align="center"><?=$v['login_name']?></td>
				<td align="center" nowrap=""><?=$v['log_info']?>(<font style="color:red;"><?=$v['log_ip']?><font>)</td>
			</tr>
			<?}?>	
				
	</tbody></table>
</div>
<?php 
	if(empty($sys_log)){
?>
	<div style="float:left;text-align:center;width:100%; margin:10px 0 0 0; font-size:13px;">暂无消息</div>	
<?php } ?>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>