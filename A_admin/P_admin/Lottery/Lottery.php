<?php
include_once("../../include/config.php");
include("../common/login_check.php");
check_quanxian("lottery");
include("../../include/mysqli.php");
if ($_REQUEST['date_s']!=''){
	$date_s	= $_REQUEST['date_s'];
}else{
	$date_s	= date('Y-m-d',time());
}
if ($_REQUEST['date_e']!=''){
	$date_e	= $_REQUEST['date_e'];
}else{
	$date_e	= date('Y-m-d',time());
}
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome</title>
<link rel="stylesheet" href="../images/css/admin_style_1.css" type="text/css" media="all" />
<script language="javascript" src="/js/jquery.js"></script>
<script language="javascript">
function check_submit(){
	if($("#date_s").val()==""){
		alert("请选择开始日期");
		$("#date_s").focus();
		return false;
	}
	if($("#date_e").val()==""){
		alert("请选择结束日期");
		$("#date_e").focus();
		return false;
	}
	return true;
}
</script>
<script language="JavaScript" src="/js/calendar.js"></script>
</head>
<body>
<div id="pageMain">
  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td valign="top">
      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="font12" bgcolor="#798EB9">
     <form name="form1" method="get" action="<?=$_SERVER["REQUEST_URI"]?>" onSubmit="return check();">
      <tr>
        <td align="center" bgcolor="#FFFFFF">日期范围：
          <input name="date_s" type="text" id="date_s" value="<?=$date_s?>" onClick="new Calendar(2008,2020).show(this);" size="10" maxlength="10" readonly="readonly" />
          ~
          <input name="date_e" type="text" id="date_e" value="<?=$date_e?>" onClick="new Calendar(2008,2020).show(this);" size="10" maxlength="10" readonly="readonly" />
          &nbsp;&nbsp;
          <input type="submit" name="Submit" value="搜索"></td>
      </tr>  
        </form>
    </table>
    <table width="100%" border="0" cellpadding="5" cellspacing="1" class="font12" style="margin-top:5px;" bgcolor="#798EB9">   
            <tr style="background-color:#3C4D82; color:#FFF">
              <td height="25" colspan="6" align="center"><strong>
              <?=$date_s?> 
              ~ <?=$date_e?> 彩票投注报表</strong></td>
            </tr>
            <tr style="background-color:#3C4D82; color:#FFF">
              <td height="25" align="center"><strong>会员账号</strong></td>
              <td align="center"><strong>注单数量</strong></td>
              <td align="center"><strong>总投注额</strong></td>
			  <td align="center"><strong>总反水</strong></td>
              <td align="center"><strong>输赢统计</strong></td>
              <td align="center"><strong>投注记录</strong></td>
          </tr>
<?php
$sql	=	"select username,count(id) as num,sum(money) as money,sum(win) as win,sum(fs) as fs from c_bet where addtime>='".$date_s." 00:00:00' and addtime<='".$date_e." 23:59:59' and js=1 group by username order by num desc";
$query	=	$mysqli->query($sql); 
$num_z = $money_z = $win_z = $fs_z=0;
while($rs = $query->fetch_array()){
	$color	= "#FFFFFF";
	$over	= "#EBEBEB";
	$out	= "#ffffff";
?>
      <tr align="center" onMouseOver="this.style.backgroundColor='<?=$over?>'" onMouseOut="this.style.backgroundColor='<?=$out?>'" style="background-color:<?=$color?>; line-height:20px;">
        <td height="25" align="center" valign="middle"><?=$rs['username']?></td>
        <td align="center" valign="middle"><?=$rs['num']?></td>
        <td align="center" valign="middle"><?=$rs['money']?></td>
		<td align="center" valign="middle"><?=round($rs['fs'],2)?></td>
        <td align="center" valign="middle"><?=round($rs['win'],2)?></td>
        <td><a href="../lottery/order.php?js=0,1&username=<?=$rs['username']?>&s_time=<?=$date_s?>&e_time=<?=$date_e?>">投注记录</a></td>
        </tr>
<?php
$num_z += $rs['num'];
$money_z += $rs['money'];
$win_z += round($rs['win'],2);
$fs_z += round($rs['fs'],2);
}
?>
	<tr style="background-color:#3C4D82; color: #FF0">
              <td height="25" align="center"><strong>总计</strong></td>
              <td align="center"><strong><?=$num_z?></strong></td>
              <td align="center"><strong><?=$money_z?></strong></td>
			  <td align="center"><strong><?=$fs_z?></strong></td>
              <td align="center"><strong><?=$win_z?></strong></td>
              <td></td>
          </tr>
	</table>  </td>
    </tr>
  </table>
</div>
</body>
</html>