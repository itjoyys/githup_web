
<?
include(dirname(__FILE__)."./../../include/private_config.php");
//用户如果处于离线状态，则不允许投注
if(empty($_SESSION['uid']))
{
  echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
  echo '<script type="text/javascript">top.location.href="/";</script>';
  exit;
}



$arr_zuhe;
  foreach ($_GET as $k => $v) {
    $class = explode('num', $k);
      if($class[1]!=''){

      $arr_zuhe[]=$v;

    }
  }
// p($arr_zuhe);
// p($_GET);
  if($_GET['class2'] == '二肖连中'){

    $num_arr_num =  2;
  }elseif($_GET['class2'] == '三肖连中'){
    $num_arr_num =  3;
  }elseif($_GET['class2'] == '四肖连中'){
    $num_arr_num =  4;
  }elseif($_GET['class2'] == '五肖连中'){
    $num_arr_num =  5;
  }elseif($_GET['class2'] == '二肖连不中'){
    $num_arr_num =  2;
  }elseif($_GET['class2'] == '三肖连不中'){
    $num_arr_num =  3;
  }elseif($_GET['class2'] == '四肖连不中'){
    $num_arr_num =  4;
  }


  if(!empty($arr_zuhe) ){
    $arr_zuhe_1=get_zuhe($arr_zuhe,$num_arr_num);
  }
// if(!defined('PHPYOU')) {
// 	exit('非法进入');
// }

if ($_GET['class2']==""){echo "<script>alert('非法进入!');parent.location.href='main_left.php';</script>";
exit;}

$n=0;
for ($t=0;$t<=12;$t=$t+1){

if ($_GET['num'.$t]!=""){
$number1.=$_GET['num'.$t].",";
$n=$n+1;
}
}
$number3=$number1;

// $result=$mysqlt->query("select sum(sum_m) as sum_mm from c_bet where qishu='".$Current_Kithe_Num."' and  username='".$_SESSION['username']."' and mingxi_1='生肖连' and mingxi_3='".$_GET['class2']."'  order by id desc");
// $ka_guanuserkk1=$result->fetch_array();
// $sum_mm=$ka_guanuserkk1[0];
 $result = $mysqli->query("Select * From c_odds_7 where class1 = '生肖连' and class2 = '".$ids."' and class3 = '".$now_shengxiao."' Order By id Desc");
 $ka_guanuserkk1=$result->fetch_array();
 $rate_low = $ka_guanuserkk1['rate'];


switch ($_GET['class2']){


 case "二肖连中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=48;
$XF=23;
$rate_id=1401;
if ($n<2 or $n>8){echo "<script>alert('对不起，请选择二-八个生肖!');parent.location.href='main_left.php';</script>";
exit;}
$zs=$n*($n-1)/2;

$mu=explode(",",$number1);
$mama=$mu[0].",".$mu[1];
for ($f=0;$f<count($mu)-2;$f=$f+1){
for ($t=2;$t<count($mu)-1;$t=$t+1){
if ($f!=$t and $f<$t){
$mama=$mama."/".$mu[$f].",".$mu[$t];
}
}
}
break;

case "三肖连中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=49;
$XF=23;
$rate_id=1413;
if ($n<3 or $n>8){echo "<script>alert('对不起，请选择三-八个生肖!');parent.location.href='main_left.php';</script>";
exit;}
$zs=$n*($n-1)*($n-2)/6;


$mu=explode(",",$number1);
$mama=$mu[0].",".$mu[1].",".$mu[2];
for ($h=0;$h<count($mu)-3;$h=$h+1){
for ($f=1;$f<count($mu)-2;$f=$f+1){
for ($t=3;$t<count($mu)-1;$t=$t+1){
if ($h!=$f and $h<$f and $f!=$t and $f<$t){
$mama=$mama."/".$mu[$h].",".$mu[$f].",".$mu[$t];
}
}
}
}

break;

case "四肖连中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=50;
$XF=23;
$rate_id=1425;
if ($n<4 or $n>8){echo "<script>alert('对不起，请选择四-八个生肖!');parent.location.href='main_left.php';</script>";
exit;}
$zs=$n*($n-1)*($n-2)*($n-3)/24;



$mu=explode(",",$number1);
$mama=$mu[0].",".$mu[1].",".$mu[2].",".$mu[3];
for ($h=0;$h<count($mu)-4;$h=$h+1){
for ($f=1;$f<count($mu)-3;$f=$f+1){
for ($t=2;$t<count($mu)-2;$t=$t+1){
for ($s=4;$s<count($mu)-1;$s=$s+1){
if ($h!=$f and $h<$f and $f!=$t and $f<$t and $t!=$s and $t<$s){
$mama=$mama."/".$mu[$h].",".$mu[$f].",".$mu[$t].",".$mu[$s];
}
}
}
}
}

break;


case "五肖连中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=51;
$XF=23;
$rate_id=1473;
if ($n<5 or $n>8){echo "<script>alert('对不起，请选择五-八个生肖!');parent.location.href='main_left.php';</script>";
exit;}
$zs=$n*($n-1)*($n-2)*($n-3)*($n-4)/120;



$mu=explode(",",$number1);
$mama=$mu[0].",".$mu[1].",".$mu[2].",".$mu[3].",".$mu[4];
for ($h=0;$h<count($mu)-5;$h=$h+1){
for ($f=1;$f<count($mu)-4;$f=$f+1){
for ($t=2;$t<count($mu)-3;$t=$t+1){
for ($s=3;$s<count($mu)-2;$s=$s+1){
for ($u=5;$u<count($mu)-1;$u=$u+1){
if ($h!=$f and $h<$f and $f!=$t and $f<$t and $t!=$s and $t<$s and $s!=$u and $s<$u){
$mama=$mama."/".$mu[$h].",".$mu[$f].",".$mu[$t].",".$mu[$s].",".$mu[$u];
}
}
}
}
}
}
break;


 case "二肖连不中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=52;
$XF=23;
$rate_id=1437;
if ($n<2 or $n>8){echo "<script>alert('对不起，请选择二-八个生肖!');parent.location.href='main_left.php';</script>";
exit;}
$zs=$n*($n-1)/2;

$mu=explode(",",$number1);
$mama=$mu[0].",".$mu[1];
for ($f=0;$f<count($mu)-2;$f=$f+1){
for ($t=2;$t<count($mu)-1;$t=$t+1){
if ($f!=$t and $f<$t){
$mama=$mama."/".$mu[$f].",".$mu[$t];
}
}
}

break;

case "三肖连不中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=53;
$XF=23;
$rate_id=1449;
if ($n<3 or $n>8){echo "<script>alert('对不起，请选择三-八个生肖!');parent.location.href='main_left.php';</script>";
exit;}
$zs=$n*($n-1)*($n-2)/6;


$mu=explode(",",$number1);
$mama=$mu[0].",".$mu[1].",".$mu[2];
for ($h=0;$h<count($mu)-3;$h=$h+1){
for ($f=1;$f<count($mu)-2;$f=$f+1){
for ($t=3;$t<count($mu)-1;$t=$t+1){
if ($h!=$f and $h<$f and $f!=$t and $f<$t){
$mama=$mama."/".$mu[$h].",".$mu[$f].",".$mu[$t];
}
}
}
}
break;

case "四肖连不中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=50;
$XF=23;
$rate_id=1461;
if ($n<4 or $n>8){echo "<script>alert('对不起，请选择四-八个生肖!');parent.location.href='main_left.php';</script>";
exit;}
$zs=$n*($n-1)*($n-2)*($n-3)/24;

$mu=explode(",",$number1);
$mama=$mu[0].",".$mu[1].",".$mu[2].",".$mu[3];
for ($h=0;$h<count($mu)-4;$h=$h+1){
for ($f=1;$f<count($mu)-3;$f=$f+1){
for ($t=2;$t<count($mu)-2;$t=$t+1){
for ($s=4;$s<count($mu)-1;$s=$s+1){
if ($h!=$f and $h<$f and $f!=$t and $f<$t and $t!=$s and $t<$s){
$mama=$mama."/".$mu[$h].",".$mu[$f].",".$mu[$t].",".$mu[$s];
}
}
}
}
}
break;

}
$beted = beted_limit(7,$_GET['type'],$db_config);
//查询该玩法当前期数已下注金额

$ball_limit_num = $beted['sum(money)'];
?>

<link rel="stylesheet" href="./public/css/xp.css">
 <link rel="stylesheet" href="./public/css/mem_order_ft.css">
 <link rel="stylesheet" href="./public/css/mem_body_olympics.css" type="text/css">
 <!-- <link rel="stylesheet" href="./public/css/mem_order_sel.css?=" type="text/css"> -->
 <script src="../public/js/jquery-1.8.3.min.js"></script>
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
<!-- <SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';}
if(window.location.host!=top.location.host){top.location=window.location;}
</SCRIPT> -->

<!-- <SCRIPT language=javascript>
window.setTimeout("self.location='liuhecai.php?action=k_sxt2'", 30000);
</SCRIPT> -->
<SCRIPT language=JAVASCRIPT>
if(self == top){location = '/';}

function ChkSubmit(){
    //设定『确定』键为反白


		document.all.btnSubmit.disabled = true;


	}
</SCRIPT>


<SCRIPT language=javascript>var count_win=false;
function CheckKey(){
if(event.keyCode == 13) return true;
if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("下注金额仅能输入数字!!"); return false;}
}

function SubChk(){

}

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
//document.all.pc1.innerHTML=Math.round(document.all.gold.value * document.all.ioradio1.value);
count_win=true;
}
}
//function CountWinGold1(){
//if(document.all.gold.value==''){
//document.all.gold.focus();
//alert('未输入下注金额!!!');
//}
//else{
//document.all.pc1.innerHTML=Math.round(document.all.gold.value * document.all.ioradio1.value);
//count_win=true;
//}
//}
</SCRIPT>
<script>

$(function(){
    var single_field_max = $("#single_field_max").text(); //单注总金额上限200000
    var single_note_max = $("#single_note_max").text();//单注金额上限20000
    var single_note_min = $("#single_note_min").text();
    var ball_limit_num = <?=$ball_limit_num ?>;//已投注的金额

     // alert(single_note_max);
      $(".single_note_max").text(single_note_max);
      $(".single_note_min").text(single_note_min);
      $(".single_field_max").text(single_field_max);
  //下单验证
  if($("#gold").val()>0 && $("#bet_val")){
    var zs = $("#zs_div").text();
    var bet_val3=$("#bet_val").text();
    var thi_val=$("#gold").val();
    new_val3 = thi_val*bet_val3*zs;

    $("#total").text(new_val3);
  }
  $("#gold").change(function() {
    var zs = $("#zs_div").text();
    var val2 = $(this).val();
    var val1=val2.replace(/\b(0+)/,"");

    $("#danzhu_money").val(val1);
    if(val1 != ""){
       var reg = /([`~!@#$%^&*()_+<>?:"{},\/;\'[\]])/ig;
         var reg1 = /([·~！#@￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/ig;
         var reg2= /([a-zA-Z]*)/ig;
         $(this).val(val1.replace(reg, ""));
         $(this).val(val1.replace(reg1, ""));
         $(this).val(val1.replace(reg2, ""));
         val1 = $(this).val();
      if(val1<0 || val1 >parseInt(single_note_max) || val1 <parseInt(single_note_min)){
        alert("金额格式错误或超过上限！");
        $(this).val(0);
        $("#total").text(0);
        $("#pc").text(0);
      }else{
         var bet_val=parseInt($("#bet_val").text());
        var new_val=parseInt(val1*bet_val*zs);
        if(parseInt(val1) >=parseInt(single_note_min) && parseInt(val1) <=parseInt(single_note_max) && parseInt(val1*zs)<=parseInt(single_field_max) && (parseInt(val1*zs)+parseInt(ball_limit_num)) <=parseInt(single_field_max)){
            new_val+="";
            Math.round(new_val);
            $("#jq").val(parseInt(new_val));

            $("#pc").text(parseInt(zs)*parseInt(val1));
          $("#total").text(parseInt(new_val));
        }else{
            alert("金额格式错误或超过上限！");
          $(this).val(0);
          $("#total").text(0);
          $("#pc").text(0);

        }
      }
    }else{
      $("#total").text('0');
    }
  });


  //点击赔率下注
  $("#order_form").submit(function(event) {
    var val = $("#gold").val();

    if(val=="" || val ==0){
      alert("请填写下注金额！");
        return false;
    }
    var val1 = $("#total").text();
    var msg = "下注金额："+val1+"\n"+"确定进行下注吗？";
    if(isNaN(val) || isNaN(val1)){
      alert("金额错误！");
        return false;
    }
    if(!confirm(msg)){
      return false;
    }else{
      if(isNaN($("#total").text())){
        alert("金额错误！");
        return false;
      }
    }
  });
  //点击下注下注
  $("#form1").submit(function(event) {
    var val = $("#gold").val();
     if(val=="" || val ==0){
      alert("请填写下注金额！");
        return false;
    }
    var val1 = $("#total").text();
    var msg = "下注金额："+val1+"\n"+"确定进行下注吗？";
    if(isNaN(val) || isNaN(val1)){
      alert("金额错误！");
        return false;
    }
    if(!confirm(msg)){
      return false;
    }else{
      if(isNaN($("#total").text())){
        alert("金额错误！");
        return false;
      }
    }
  });

})
 </script>


<body >
<div id="zs_div" style="display:none"><?=$zs ?></div>
<div id="bet_val" style="display:none"><?=ka_bl($rate_id,"rate")?></div>
<div style="display:none">
  <TABLE class=Tab_backdrop cellSpacing=0 cellPadding=0 width=100% border=0>
  <tr>
    <td valign="top" class="Left_Place">
                <TABLE class=t_list cellSpacing=1 cellPadding=0 width=100% border=0>
         <tr>
            <td height="25" colspan="2" align="center" bgcolor="#504a16"><span class="STYLE3">确认下注</span></td>
          </tr>


          <form action="liuhecai.php?action=k_tansxt2&class2=<?=$_GET['class2']?>" method="post" name="LAYOUTFORM" id="form1" >


            <tr>
              <td width="35%" colspan="2" height="25" align="center" bgcolor="#F9F7D7" style="LINE-HEIGHT: 22px"><?=ka_bl($rate_id,"class2")?></FONT>
                  </div><div align="center"><b><?=$number1?></b></div></td>
            </tr>
        <tr>
          <td height="25" bgcolor="#ffffff" colspan="2" style="LINE-HEIGHT: 23px"><div align="center"><strong>组合共&nbsp;<font color=ff0000><?=$zs?></font>&nbsp;组</strong></div></td>
        </tr>
            <tr>
              <td height="25" colspan="2" bgcolor="#ffffff" style="LINE-HEIGHT: 23px">&nbsp;下注金额:
             <INPUT
name=gold1 id="" onKeyPress="return CheckKey()"
onkeyup="return CountWinGold()" value="<?=$_GET['Num_1']?>" size=8 maxLength=8>
              </td>
            </tr>
        <tr>
          <td height="25" colspan="2" bgcolor="#ffffff" style="LINE-HEIGHT: 23px">&nbsp;总下注金额: <strong><FONT id=pc1 color=#ff0000><?=$_GET['Num_1']*$zs?>&nbsp;</FONT></strong></td>
        </tr>
            <tr>
              <td height="25" colspan="2" bgcolor="#ffffff">&nbsp;单注限额: <span class="single_note_max">20000</span></td>
            </tr>
            <tr>
              <td height="25" colspan="2" bgcolor="#ffffff">&nbsp;单项限额: <span class="single_field_max">200000</span></td>
            </tr>
            <tr>
              <td height="30" colspan="2" align="center" bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><input type='hidden' name="rate_id" value='<?=$rate_id?>' />
              <input  class="button_a"  onClick="self.location='liuhecai.php?action=k_sxt2';" type="button" value="放弃" name="btnCancel" />

                &nbsp;&nbsp;
                <input  class="button_a"  type="submit" value="确定" onclick="SubChk(<?=$zs?>);" name="btnSubmit" />
              </td>
            </tr>
            <input type="hidden"
value="SP11" name="concede" />
            <input type="hidden" value='<?=ka_bl($rate_id,"rate")*1000?>' name="ioradio" />
            <input type="hidden" value='<?=$zs?>' name="ioradio1" />
            <input type="hidden" value='<?=$mama?>' name="number1" />
            <INPUT type=hidden value='<?=$number3?>' name=number3>

        </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="3"></td>
          </tr>
      </table></td>
  </tr>
</table>
</div>

<!-- 显示下注信息 -->
<?php
// p($_GET);
if($_GET['show'] != 1){
 ?>
 <form autocomplete="off" name="order_form" id="order_form" method="get" action="liuhecai.php">
<input type="hidden" name="show" value="1">
<input type="hidden" name="action" value="<?=$_GET['action'] ?>">
<input type="hidden" name="class2" value="<?=$_GET['class2'] ?>">
<input type="hidden" name="ids" value="<?=$_GET['ids'] ?>">
<input type="hidden" name="rtype" value="<?=$_GET['rtype'] ?>">
<input type="hidden" name="xiazhu_money" value="<?=$_GET['ids'] ?>">
<input type="hidden" name="jq" id="jq" value="">
<input type="hidden" name="rrtype" value="<?=$_GET['rrtype'] ?>">
<input type="hidden" name="pabc" value="<?=$_GET['pabc'] ?>">
<input type="hidden" name="pabc2" value="<?=$_GET['pabc2'] ?>">
<input type="hidden" name="pan1" value="<?=$_GET['pan1'] ?>">
<input type="hidden" name="pan2" value="<?=$_GET['pan2'] ?>">
<input type="hidden" name="pan3" value="<?=$_GET['pan3'] ?>">
<input type="hidden" name="pan4" value="<?=$_GET['pan4'] ?>">
<input type="hidden" name="danzhu_money" value="" id="danzhu_money">
  <?php
foreach ($_GET as $k => $v) {
    $class = explode('num', $k);
      if($class[1]!=''){

      echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';

    }
  }
  // p($_GET);
   ?>



<div class="ord">
    <div class="title"><h1>生肖连-<?=$_GET['class2']  ?></h1></div>
    <div class="main">
      <!--div class="leag"><strong></strong><span class="tName">连码-三全中</span></div>
      <div class="teamName"><span class="tName"></span></div-->
      <p class="team" style="text-align:center">



      <?php
       if(!empty($arr_zuhe_1)){

      foreach ($arr_zuhe_1 as  $v) {
        if (strpos($v,$now_shengxiao) >-1){



      ?>
     <?=$v ?>@ <font color="#ff0000"><?=$rate_low?></font><br>

    <?php }else{ ?>
    <?=$v ?>@ <font color="#ff0000"><?=ka_bl($rate_id,"rate")?></font><br>

        <?php }  }   } ?>
      -----------------------<br>组合 共 <font color="red"><?=$zs?></font> 组</p>
      <p class="error" style="display: none;"></p>
      <div class="betdata">
        <p class="amount">交易金额：<input name="gold" type="text" class="txt" id="gold" onkeypress="return CheckKey(event)" onkeyup="return CountWinGold()" size="8" maxlength="10" value="<?=$_GET['Num_1']?>"></p>
        <p class="mayWin"><span class="bet_txt">总下注金额：</span><font id="pc"><?=$_GET['Num_1']*$zs?></font></p>

        <!-- <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="total"><?php echo ka_bl($rate_id,"rate")*$zs*$_GET['Num_1']  ?></font></p> -->

        <p class="minBet"><span class="bet_txt">单注最低：</span><span class="single_note_min">0</span></p>

        <p class="maxBet"><span class="bet_txt">单注限额：<span class="single_note_max">20000</span></span></p>
        <p class="maxBet_1"><span class="bet_txt">单项限额：<span class="single_field_max">200000</span></span></p>
    </div>
    </div>
    <div id="gWager" style="display: none;position: absolute;"></div>
    <div id="gbutton" style="display: block;position: absolute;"></div>
    <div class="betBox">
      <input type="button" name="btnCancel" value="取消" onclick="window.parent.showOrder();" class="no">
      <input type="submit" name="btnSubmit" value="确定交易"  class="yes">
    </div>
  </div>
  </form>
  <?php } ?>




</BODY></HTML>
