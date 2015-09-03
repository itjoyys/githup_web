<?php
include('../../include/filter.php');
include_once("../../include/private_config.php");
include_once("../../include/public_config.php");
/*
$sql="select * from site_notice where (sid = 0 or sid = '".SITEID."') and notice_cate='s_p'";
$where='';
if(@$_GET['date']==1){
	$where.=" and notice_date<'".date("Y-m-d",(time()+3600*24))."' and notice_date>='".date("Y-m-d",time())."'";
}elseif(@$_GET['date']==2){
	$where.=" and notice_date<'".date("Y-m-d",time())."' and notice_date>='".date("Y-m-d",(time()-3600*24))."'";
}elseif(@$_GET['date']==3){
	$where.=" and notice_date<'".date("Y-m-d",(time()-3600*24))."'";
}
if(@$_GET['title']){
	$where.=" and (notice_title like '%".$_GET['title']."%' or notice_content like '%".$_GET['title']."%')";
}
$sql.=$where." order by notice_date desc limit 0,30";
$query=$mysqlt->query($sql);
$notice='';
while($row = $query->fetch_array()){
	$notice[]=$row;
}*/
$sql="select * from site_notice where (sid = 0 or sid = '".SITEID."') and notice_cate='s_p'";
$where='';
if(@$_GET['date']==1){
    $where.=" and notice_date<'".date("Y-m-d",(time()+3600*24))."' and notice_date>='".date("Y-m-d",time())."'";
}elseif(@$_GET['date']==2){
    $where.=" and notice_date<'".date("Y-m-d",time())."' and notice_date>='".date("Y-m-d",(time()-3600*24))."'";
}elseif(@$_GET['date']==3){
    $where.=" and notice_date<'".date("Y-m-d",(time()-3600*24))."'";
}
if(@$_GET['title']){
    $where.=" and (notice_title like '%".$_GET['title']."%' or notice_content like '%".$_GET['title']."%')";
}
$sql.=$where." order by notice_date desc limit 0,30";

$query=$mysqli->query($sql);
while($row = $query->fetch_array()){
    $notice[]=$row;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>History</title>
<link rel="stylesheet" href="../public/css/mem_body_ft.css" type="text/css">
<link rel="stylesheet" href="../public/css/scroll.css" type="text/css">

<script type="text/javascript">
var select_date='-4';
var t_page='3';
var page_no='';
var langx='zh-cn';
var fField='';
</script>
<script language="JavaScript" src="public/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" src="public/js/scroll_history.js"></script>
<script language="JavaScript" src="public/js/zh-tw.js"></script>

</head>
<body id="MSG" class="bodyset" onload="init_scroll()" ;="" onkeydown="checkKey(event.keyCode)">

<table border="0" cellpadding="0" cellspacing="0" id="box">
	<tbody>
		<tr>
			<td class="top"><h1><em>公告栏</em></h1></td>
		</tr>
		<tr>
			<td class="mem his_top">
				<div>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="menu_set">
						<tbody>
							<tr class="table_main_settings_tr" >
								<td id="page_no2">
									<span id="pg_txt"></span> <span style="display: none;" id="t_pge"></span>
									<span onclick="chg_date(&#39;all&#39;);"><a href="announcement.php?date=0"><font id="all" color="#ffffff" class="scr_on">全部</font></a></span>
									/ <span onclick="chg_date(0);"><a href="announcement.php?date=1"><font id="today" color="#ffffff" class="scr_out">今日</font></a></span>
									/ <span onclick="chg_date(-1);"><a href="announcement.php?date=2"><font id="yesterday" color="#ffffff" class="scr_out">昨日</font></a></span>
									/ <span onclick="chg_date(-2);"><a href="announcement.php?date=3"><font id="before" color="#ffffff" class="scr_out">昨日之前</font></a></span>
								</td>
								<td class="search">
								<form action="announcement.php" method="get">
								<input type="text" id="findField" name="title" value="" class="ccroll_input">
								<input type="submit" id="findbutton" name="" value="搜寻" class="ccroll_btn">
								</td>
								</form>
								<td class="rsu_refresh"><div onclick="reload_var();"><font id="refreshTime"></font></div></td>
						   </tr>
						</tbody>
					</table>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<table border="1" cellpadding="0" cellspacing="0" id="box" style="border:#ccc">
	<tbody>
		<tr>
			<td class="mem his_body">
				<table border="1" cellspacing="0" cellpadding="0" class="game" style="border:#ccc">
					<tbody><tr>
						<th width="40">序号</th>
						<th width="70">日期</th>
						<th align="left" class="info">公告内容</th>
					</tr>

				<?if(is_array($notice)){foreach($notice as $v){
                    $i++;?>
					<tr class="b_rig color_bg2"  style="border-bottom:1px solid #999;">
						<td width="40" class="m_cen"><?=$i?></td>
						<td width="70" class="m_cen"><?=substr($v['notice_date'],0,10)?></td>
						<td class="m_lef" style="padding:5px 0px 5px 5px;"><?=$v['notice_content']?></td>
					</tr>
					<?}}?>
				</tbody></table>
			</td>
		</tr>
		<tr style="border-left:0px; border-right:0px;"><td id="foot" style="border-left:0px; border-right:0px;"><b>&nbsp;</b></td></tr>
	</tbody>
</table>





</body></html>