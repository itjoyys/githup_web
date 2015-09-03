<?php
include_once("../../include/config.php");
include("../../include/mysqli.php");
$type = $_REQUEST['type'];
$qihao = $_REQUEST['qihao'];
$kaipan = $_REQUEST['kaipan'];
$fengpan = $_REQUEST['fengpan'];
$kaijiang = $_REQUEST['kaijiang'];
if($type=='post'){
	$sql =	"insert into c_opentime_4(qishu,kaipan,fengpan,kaijiang) values (".$qihao.",'".$kaipan."','".$fengpan."','".$kaijiang."')";
	$mysqli->query($sql);
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style type="text/css">
body,td,th {
	font-size: 14px;
	font-family: "宋体";
}
/* 分页样式 */
.page{ height:20px; list-style:none; padding:2px 10px; margin:2px; }
.page li{ text-align:center; float:left; line-height:16px; border:1px solid #9DDCFD; margin-right:3px;}
.page li a,
.page li span{ background:#E0F3FE url(images/btn_bg.gif) repeat-x 0 bottom; color:#09F; padding:0 5px; margin:1px; float:left; }
.page li a:hover,
.page .curr span{ background:#9DDCFD; color:#069; }
.page .curr{ border-color:#24AFFB; font-weight:bold; }
.page .stop{ border-color:#CCC; }
.page .stop span{ background-position:0 0; color:#888; }
.page .stop span input{ width:20px; height:12px; line-height:12px; padding:0; margin:0 2px 1px; border:1px solid #CCC; text-align:center; }

/* 错误提示框样式 */
fieldset.errlog{ border:2px solid #E83131; padding:4px; }
fieldset.errlog legend{ line-height:18px; margin-left:15px; background:#FFECEC url(images/warn.gif) no-repeat 3px center; padding:3px 5px 0 20px; color:#C00; font-size:13px; font-weight:bold; border:2px solid #E83131; }
fieldset.errlog label{ margin-top:3px; padding:0 5px; border:1px solid #F60; display:block; line-height:20px; }
fieldset.errlog .tip{ color:#F00; background-color:#FFC; }
fieldset.errlog .msg{ color:#036; background-color:#D9F2FF; border-color:#60C8FF; }
fieldset.errlog .sql{ color:#090; background-color:#E1FFE1; border-color:#438743; }
fieldset.errlog .log{ color:#666; background-color:#F8F8F8; border-color:#BBB; }
</style>
</head>
<body>
<?php
$kaipans = date("H:i:s",strtotime($kaipan)+600);
$fengpans = date("H:i:s",strtotime($fengpan)+600);
$kaijiangs = date("H:i:s",strtotime($kaijiang)+600);
?>
<table width="300" border="0" cellpadding="5" cellspacing="1" bgcolor="#000000">
<form id="form1" name="form1" method="post" action="?type=post"><tr>
    <td width="80" align="right" bgcolor="#FFFFFF">期号：</td>
    <td align="left" bgcolor="#FFFFFF"><input name="qihao" type="text" id="qihao" value="<?=$qihao+1?>" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFFF">开盘时间：</td>
    <td align="left" bgcolor="#FFFFFF"><input name="kaipan" type="text" id="kaipan" value="<?=$kaipans?>" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFFF">封盘时间：</td>
    <td align="left" bgcolor="#FFFFFF"><input name="fengpan" type="text" id="fengpan" value="<?=$fengpans?>" /></td>
  </tr>
  <tr>
    <td align="right" bgcolor="#FFFFFF">开奖时间：</td>
    <td align="left" bgcolor="#FFFFFF"><input name="kaijiang" type="text" id="kaijiang" value="<?=$kaijiangs?>" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center" bgcolor="#FFFFFF"><input type="submit" value="提交" /></td>
  </tr></form>
</table>
</body>
</html>