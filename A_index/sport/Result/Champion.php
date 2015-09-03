<?php
include('../../include/filter.php');
$date	=	date('Y-m-d',strtotime("-12 hour"));
if(@$_GET['ymd']) $date	=	@$_GET['ymd'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome</title>
<script language="javascript" src="../../js/jquery.js"></script>
<script language="javascript" src="champion.js"></script>
<link href="../css/sports_right.css" rel="stylesheet" type="text/css" />
</head>
<body onload="loaded('<?=$date?>',0);">
<div class="top" style="height:25px; border:1px #ACACAC solid; margin-bottom:5px; background-color:#838383; line-height:25px;">
<div class="result_title"><span>冠军结果 >></span><?php for($i=0;$i<7;$i++){ $d	=	date('Y-m-d',strtotime("-12 hour")-$i*86400);$dd	=	date('m-d',strtotime("-12 hour")-$i*86400);?><li<?= $d==$date ? ' class="i"' : ''?>><?= $d==$date ? ' ' : '<a href="?ymd='.$d.'">'?><?=$dd?><?= $d==$date ? '' : '</a>'?></li><?php }?></div>
</div>
<div id="datashow">
<table border='0' cellspacing='1' cellpadding='0' bgcolor='#ACACAC' class='box'>
  <tr>
    <th width="100" align="center" bgcolor="#FFFFFF" style="line-height:25px;">比赛时间</th>
    <th align="center" bgcolor="#FFFFFF" style="line-height:25px;">项目</th>
    <th align="center" bgcolor="#FFFFFF" style="line-height:25px;">队伍</th>
    <th align="center" bgcolor="#FFFFFF" style="line-height:25px;">结果</th>
  </tr>
  <tr>
    <td height="100" colspan="4" align="center" bgcolor="#FFFFFF" style="line-height:25px;"><img src="../images/loading.gif" border="0" /><br />
      冠军赛果加载中......</td>
    </tr>
</table>
</div>
</body>
</html>