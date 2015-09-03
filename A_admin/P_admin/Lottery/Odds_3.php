<?php
header('Content-Type:text/html; charset=utf-8');
include_once("../../include/config.php");
include_once("../common/login_check.php");
check_quanxian("lottery");
include("../../include/mysqli.php");
$type = $_REQUEST['type'];
$save = $_REQUEST['save'];
if($type==''){$type=11;}
$type=='1' ? $se1 = '#FF0' : $se1 = '#FFF';
$type=='2' ? $se2 = '#FF0' : $se2 = '#FFF';
$type=='3' ? $se3 = '#FF0' : $se3 = '#FFF';
$type=='4' ? $se4 = '#FF0' : $se4 = '#FFF';
$type=='5' ? $se5 = '#FF0' : $se5 = '#FFF';
$type=='6' ? $se6 = '#FF0' : $se6 = '#FFF';
$type=='7' ? $se7 = '#FF0' : $se7 = '#FFF';
$type=='8' ? $se8 = '#FF0' : $se8 = '#FFF';
$type=='9' ? $se9 = '#FF0' : $se9 = '#FFF';
$type=='10' ? $se10 = '#FF0' : $se10 = '#FFF';
$type=='11' ? $se11 = '#FF0' : $se11 = '#FFF';
switch ($type){
	case 1:
	$lh = '1V10';
	break;
	case 2:
	$lh = '2V9';
	break;
	case 3:
	$lh = '3V8';
	break;
	case 4:
	$lh = '4V7';
	break;
	case 5:
	$lh = '5V6';
	break;
	default:
  	$lh = '';
} 
if($save=='ok'){
	if($type<6){
		$sql	=	"update c_odds_3 set h1=".$_REQUEST['Num_1'].",h2=".$_REQUEST['Num_2'].",h3=".$_REQUEST['Num_3'].",h4=".$_REQUEST['Num_4'].",h5=".$_REQUEST['Num_5'].",h6=".$_REQUEST['Num_6'].",h7=".$_REQUEST['Num_7'].",h8=".$_REQUEST['Num_8'].",h9=".$_REQUEST['Num_9'].",h10=".$_REQUEST['Num_10'].",h11=".$_REQUEST['Num_11'].",h12=".$_REQUEST['Num_12'].",h13=".$_REQUEST['Num_13'].",h14=".$_REQUEST['Num_14'].",h15=".$_REQUEST['Num_15'].",h16=".$_REQUEST['Num_16']." where type='ball_".$type."'";
		$mysqli->query($sql);
		message("赔率修改完毕！","odds_3.php?type=".$type."");
		exit;
	}
	if($type>5 && $type<11){
		$sql	=	"update c_odds_3 set h1=".$_REQUEST['Num_1'].",h2=".$_REQUEST['Num_2'].",h3=".$_REQUEST['Num_3'].",h4=".$_REQUEST['Num_4'].",h5=".$_REQUEST['Num_5'].",h6=".$_REQUEST['Num_6'].",h7=".$_REQUEST['Num_7'].",h8=".$_REQUEST['Num_8'].",h9=".$_REQUEST['Num_9'].",h10=".$_REQUEST['Num_10'].",h11=".$_REQUEST['Num_11'].",h12=".$_REQUEST['Num_12'].",h13=".$_REQUEST['Num_13'].",h14=".$_REQUEST['Num_14']." where type='ball_".$type."'";
		$mysqli->query($sql);
		message("赔率修改完毕！","odds_3.php?type=".$type."");
		exit;
	}
	if($type==11){
		$sql	=	"update c_odds_3 set h1=".$_REQUEST['Num_1'].",h2=".$_REQUEST['Num_2'].",h3=".$_REQUEST['Num_3'].",h4=".$_REQUEST['Num_4'].",h5=".$_REQUEST['Num_5'].",h6=".$_REQUEST['Num_6'].",h7=".$_REQUEST['Num_7'].",h8=".$_REQUEST['Num_8'].",h9=".$_REQUEST['Num_9'].",h10=".$_REQUEST['Num_10'].",h11=".$_REQUEST['Num_11'].",h12=".$_REQUEST['Num_12'].",h13=".$_REQUEST['Num_13'].",h14=".$_REQUEST['Num_14'].",h15=".$_REQUEST['Num_15'].",h16=".$_REQUEST['Num_16'].",h17=".$_REQUEST['Num_17'].",h18=".$_REQUEST['Num_18'].",h19=".$_REQUEST['Num_19'].",h20=".$_REQUEST['Num_20'].",h21=".$_REQUEST['Num_21']." where type='ball_".$type."'";
		$mysqli->query($sql);
		message("赔率修改完毕！","odds_3.php?type=".$type."");
		exit;
	}

}
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome</title>
<link rel="stylesheet" href="../images/css/admin_style_1.css" type="text/css" media="all" />
</head>
<script type="text/javascript" charset="utf-8" src="/js/jquery.js" ></script>
<script type="text/javascript">
//读取当前期数盘口赔率与投注总额
function loadinfo(){
	$.get("get_odds_3.php", {type : <?=$type?>}, function(data)
		{
			for(var key in data.oddslist){
			odds = data.oddslist[key];
			$("#Num_"+key).val(odds);
			}
		}, "json");
}
function UpdateRate(num ,i){
	$.get("updaterate_3.php", {type : <?=$type?> ,num : num ,i : i}, function(data)
		{
			odds = data.oddslist[num];
			xodds = $("#Num_"+num).val();
			if(odds != xodds){
				$("#Num_"+num).css("color","red");
			}
			$("#Num_"+num).val(odds);
		}, "json");
}
</script>
<body>
<div id="pageMain">
  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="font12">
         <tr>
<td><span class="lans"><a href="odds_1.php" style="color:#FFF" title="广东快乐十分赔率管理">广东快乐十分赔率</a></span><span class="lans"><a href="odds_2.php" style="color:#FFF" title="重庆时时彩赔率管理">重庆时时彩赔率</a></span><span class="lans"><a href="odds_4.php" style="color:#FFF" title="重庆快乐十分赔率管理">重庆快乐十分赔率</a></span><span class="hongs"><a href="odds_3.php" style="color:#FFF" title="北京PK拾赔率管理">北京PK拾赔率</a></span><span class="lans"><a href="odds_5.php" style="color:#FFF" title="福彩3D">福彩3D</a></span><span class="lans"><a href="odds_6.php" style="color:#FFF" title="排列三">排列三</a></span><span class="lans"><a href="odds_8.php" style="color:#FFF" title="北京快乐8">北京快乐8</a></span></td>       
 </tr>
      </table>
    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="font12" bgcolor="#798EB9" style="margin-top:5px;">
      <tr>
        <td align="center" bgcolor="#3C4D82" style="color:#FFF">
        <a href="?type=11" style="color:<?=$se11?>; font-weight:bold;">冠亚军和</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=1" style="color:<?=$se1?>; font-weight:bold;">第一名</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=2" style="color:<?=$se2?>; font-weight:bold;">第二名</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=3" style="color:<?=$se3?>; font-weight:bold;">第三名</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=4" style="color:<?=$se4?>; font-weight:bold;">第四名</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=5" style="color:<?=$se5?>; font-weight:bold;">第五名</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=6" style="color:<?=$se6?>; font-weight:bold;">第六名</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=7" style="color:<?=$se7?>; font-weight:bold;">第七名</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=8" style="color:<?=$se8?>; font-weight:bold;">第八名</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=9" style="color:<?=$se9?>; font-weight:bold;">第九名</a>&nbsp;&nbsp;-&nbsp;&nbsp;
        <a href="?type=10" style="color:<?=$se10?>; font-weight:bold;">第十名</a></td>
        </tr>   
    </table>
        <?php if($type<11){?>
                    <table border="0"align="center" cellspacing="1" cellpadding="5" width="100%" class="font12" style="margin-top:5px;" bgcolor="#798EB9">
                    <form name="form1" method="post" action="?type=<?=$type?>&save=ok">
                        <tr style="background-color:#3C4D82; color:#FFF">
                          <td width="50" height="22" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                          <td width="50" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                          <td width="50" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                          <td width="50" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                          <td width="50" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                        </tr>
                        <tr>
                          <td height="28" align="center"bgcolor="#FFFFFF">一号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('1','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_1" id="Num_1" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('1','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">二号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('2','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_2" id="Num_2" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('2','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">三号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('3','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_3" id="Num_3" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('3','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">四号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('4','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_4" id="Num_4" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('4','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">五号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('5','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_5" id="Num_5" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('5','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="28" align="center"bgcolor="#FFFFFF">六号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('6','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_6" id="Num_6" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('6','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">七号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('7','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_7" id="Num_7" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('7','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">八号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('8','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_8" id="Num_8" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('8','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">九号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('9','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_9" id="Num_9" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('9','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">十号车</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('10','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_10" id="Num_10" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('10','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="28" align="center"bgcolor="#FFFFFF">大</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('11','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_11" id="Num_11" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('11','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">小</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('12','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_12" id="Num_12" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('12','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">单</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('13','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_13" id="Num_13" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('13','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">双</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('14','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_14" id="Num_14" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('14','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td colspan="2" align="center"bgcolor="#FFFFFF">&nbsp;</td>
                        </tr>
                        <tr<? if($type>5){echo ' style="display:none"';}?>>
                          <td height="28" align="center"bgcolor="#FFFFFF"><?=$lh?>龙</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('15','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_15" id="Num_15" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('15','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF"><?=$lh?>虎</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('16','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_16" id="Num_16" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('16','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td colspan="6" align="center"bgcolor="#FFFFFF">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="28" colspan="10" align="center"bgcolor="#FFFFFF"><input type="submit"  class="submit80" name="Submit" value="确认修改" /></td>
                        </tr></form>
</table><?php }else if($type==11){?><table border="0"align="center" cellspacing="1" cellpadding="5" width="100%" class="font12" style="margin-top:5px;" bgcolor="#798EB9">
                    <form name="form1" method="post" action="?type=<?=$type?>&save=ok">
                        <tr style="background-color:#3C4D82; color:#FFF">
                          <td width="80" height="22" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                          <td width="80" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                          <td width="80" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                          <td width="80" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                          <td width="80" align="center"><strong>号码</strong></td>
                          <td align="center"><strong>当前赔率</strong></td>
                        </tr>
                        <tr>
                          <td height="28" align="center"bgcolor="#FFFFFF">冠亚和3</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('1','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_1" id="Num_1" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('1','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和4</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('2','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_2" id="Num_2" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('2','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和5</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('3','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_3" id="Num_3" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('3','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和6</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('4','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_4" id="Num_4" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('4','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和7</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('5','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_5" id="Num_5" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('5','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="28" align="center"bgcolor="#FFFFFF">冠亚和8</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('6','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_6" id="Num_6" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('6','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和9</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('7','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_7" id="Num_7" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('7','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和10</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('8','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_8" id="Num_8" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('8','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和11</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('9','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_9" id="Num_9" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('9','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和12</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('10','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_10" id="Num_10" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('10','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="28" align="center"bgcolor="#FFFFFF">冠亚和13</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('11','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_11" id="Num_11" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('11','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和14</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('12','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_12" id="Num_12" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('12','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和15</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('13','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_13" id="Num_13" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('13','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和16</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('14','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_14" id="Num_14" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('14','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和17</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('15','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_15" id="Num_15" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('15','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td height="28" align="center"bgcolor="#FFFFFF">冠亚和18</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('16','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_16" id="Num_16" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('16','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚和19</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('17','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_17" id="Num_17" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('17','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td colspan="6" align="center"bgcolor="#FFFFFF">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="28" align="center"bgcolor="#FFFFFF">冠亚大</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('18','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_18" id="Num_18" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('18','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚小</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('19','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_19" id="Num_19" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('19','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">冠亚单</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('20','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_20" id="Num_20" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('20','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td align="center"bgcolor="#FFFFFF">双</td>
                          <td align="center"bgcolor="#FFFFFF"><table border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('21','0');"><img src="../Images/bvbv_02.gif" width="19" height="17" /></a></td>
                              <td align="center"><input class="input1" maxlength="6" size="4" value="0" name="Num_21" id="Num_21" /></td>
                              <td align="center"><a style="cursor:hand" onClick="UpdateRate('21','1');"><img src="../Images/bvbv_01.gif" width="19" height="17" /></a></td>
                            </tr>
                          </table></td>
                          <td colspan="2" align="center"bgcolor="#FFFFFF">&nbsp;</td>
                        </tr>
                        <tr>
                          <td height="28" colspan="10" align="center"bgcolor="#FFFFFF"><input type="submit"  class="submit80" name="Submit" value="确认修改" /></td>
                        </tr></form>
                </table><?php }?></td>
    </tr>
  </table>
</div>
<script type="text/javascript">loadinfo();</script> 
</body>
</html>