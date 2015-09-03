
  <meta charset="UTF-8">

<? 
// if(!defined('PHPYOU')) {
// 	exit('非法进入');
// }
include(dirname(__FILE__)."./../../include/private_config.php");
//用户如果处于离线状态，则不允许投注
if(empty($_SESSION['uid']))
{
  echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
  echo '<script type="text/javascript">top.location.href="/";</script>';
  exit;
}

﻿

// $result=$mysqlt->query("select sum(sum_m) as sum_mm from c_bet where qishu='".$Current_Kithe_Num."' and  username='".$_SESSION['username']."' and class1='过关'   order by id desc"); 
// $ka_guanuserkk1=$result->fetch_array(); 
// $sum_mm=$ka_guanuserkk1[0];

// $ggpz=ka_config(8);
// $XF=19;
// $R=12;
?>



<link rel="stylesheet" href="./public/css/xp.css">
<style type="text/css">
<!--
body,td,th {
	font-size: 9pt;
}
.STYLE3 {color: #FFFFFF}
.STYLE4 {color: #000}
.STYLE2 {}
-->
</style></HEAD>
<SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';} 
if(window.location.host!=top.location.host){top.location=window.location;} 
window.onload=function(){
    document.getElementById("LAYOUTFORM").submit();
  }
</SCRIPT>


<!-- <SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';}
if(window.location.host!=top.location.host){top.location=window.location;}
window.setTimeout("self.location='liuhecai.php?action=left'", 60000);
</SCRIPT> -->

<SCRIPT language=javascript>var count_win=false;
function CheckKey(){
if(event.keyCode == 13) return true;
if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("下注金额仅能输入数字!!"); return false;}
}

function SubChk(){
if(document.all.gold.value==''){
document.all.gold.focus();
alert("请输入下注金额!!");
return false;
}

// var zmnh;
// var zmnh1;
// zmnh1=Math.round(document.all.gold.value * document.all.ioradio.value /1000);
// zmnh=<?=$ggpz?>;
// zmnh2=Math.round(zmnh/zmnh1 );

// if (Math.round(document.all.gold.value * document.all.ioradio.value /1000)><?=$ggpz?>){document.all.gold.focus();
// alert('你最多只可以下:'+Math.round(zmnh/(document.all.ioradio.value/1000))+'!!!');
// return false;}


document.all.btnCancel.disabled = true;
document.all.btnSubmit.disabled = true;
document.LAYOUTFORM.submit();
}
function CountWinGold(){
if(document.all.gold.value==''){
document.all.gold.focus();
alert('未输入下注金额!!!');
}else{
document.all.pc.innerHTML=Math.round(document.all.gold.value * document.all.ioradio.value /1000 - document.all.gold.value);
var zmnh;
var zmnh1;
zmnh1=Math.round(document.all.gold.value * document.all.ioradio.value /1000);
zmnh=<?=$ggpz?>;
zmnh2=Math.round(zmnh/zmnh1 );

// if (Math.round(document.all.gold.value * document.all.ioradio.value /1000)><?=$ggpz?>){document.all.gold.focus();
// alert('你最多只可以下:'+Math.round(zmnh/(document.all.ioradio.value/1000))+'!!!');}
count_win=true;
}
}
function CountWinGold1(){
if(document.all.gold.value==''){
document.all.gold.focus();
alert('未输入下注金额!!!');
}else{
document.all.pc.innerHTML=Math.round(document.all.gold.value * document.all.ioradio.value) - document.all.gold.value;
count_win=true;
}
}
</SCRIPT>
<!-- <noscript>
<iframe scr=″*.htm″></iframe>
</noscript> -->
<body style="display:none">
<div style="display:none;">
<table height="13" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody><tr>
          <td id="left_1" style="text-align:center; font-size:16px; font-weight:bold"><!-- <img src="/images/b002.jpg"> --></td>
        </tr>
      </tbody></table>


										  
	<form action="liuhecai.php?action=k_tangg&class2=过关" method="post"  onSubmit="return false" name="LAYOUTFORM" id="LAYOUTFORM" >									  
<TABLE class="Tab_backdrop" cellSpacing=0 cellPadding=0 width="100%" border=0>
<tr>
    <td valign="top" class="Left_Place">
        <TABLE class=t_list cellSpacing=1 cellPadding=0 width=100% border=0>
          <tr>
            <td height="25" colspan="2" align="center" bgcolor="#504a16"><span class="STYLE3">确认下注</span></td>
          </tr>
 
        <tr>
          <td width="35%" height="25" colspan="2"  style="LINE-HEIGHT: 22px" align="center" bgcolor="#FFFFFF"><?
		  $z=0;
$rate2=1;
for ($t=1;$t<=18;$t=$t+1){
if ($_POST['game'.$t]!=""){

$z=$z+1;
$rate_id=$_POST['game'.$t]+619;

$rate2=$rate2*ka_bl($rate_id,"rate");
?>
              <FONT color=#cc0000><FONT color=#cc0000><?=ka_bl($rate_id,"class2")?>&nbsp;<?=ka_bl($rate_id,"class3")?></FONT> @ <FONT
color=#ff0000><B><?=ka_bl($rate_id,"rate")?></B></FONT></FONT>&nbsp;&nbsp;&nbsp;&nbsp;
            <INPUT  type=hidden value="<?=$rate_id?>" name=rate_id<?=$t?>>
           
            <br>
              <? }
			  }?></td>
        </tr>
        <tr>
          <td height="25" colspan="2"  bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><span class="STYLE4"> 模式&nbsp;:&nbsp;</span>
              <select name="select">
                <option >单注</option>
              </select>
            <select name="select">
                <option value="<?=$z?>"><?=$z?>串1</option>
              </select>
            @<?=floor($rate2*100)/100?></td>
        </tr>
        <tr>
          <td height="25" colspan="2"  bgcolor="#ffffff" style="LINE-HEIGHT: 23px">&nbsp;下注金额:
            <INPUT class="input1" onKeyPress="return CheckKey()" id="gold" onkeyup="return CountWinGold()" maxLength=8 size=8
name="gold" value="<?=$_POST['gold'] ?>"></td>
        </tr>
        <tr>
          <td height="25" colspan="2"  bgcolor="#ffffff" style="LINE-HEIGHT: 23px">&nbsp;可蠃金额: <FONT id="pc" color="#ff0000">0</FONT></td>
        </tr>
        <TR>
          <TD height="25" colspan="2"  bgcolor="#ffffff">&nbsp;单注限额: <?=ka_memds($R,2)?></TD>
        </TR>
        <TR>
          <TD height="25" colspan="2"  bgcolor="#ffffff">&nbsp; 单项限额:<?=ka_memds($R,3)?></TD>
        </TR>
        <tr>
          <td height="30" colspan="2"  align="center" bgcolor="#ffffff" style="LINE-HEIGHT: 23px">
            
              <input  class="button_a"  onClick="self.location='main_left.php';" type="button" value="放弃" name="btnCancel" />
            &nbsp;&nbsp;
            <input  class="button_a"  type="submit" value="确定" onclick=SubChk(); name="btnSubmit" />
          </td>
        </tr>
        <INPUT type=hidden
value=SP11 name=concede>
      
    </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="3"></td>
          </tr>
      </table></td>
  </tr>
</table>
        <INPUT type=hidden value='<?=$rate2*1000?>' name=ioradio>
      </FORM>
</div>
</BODY></HTML>
﻿