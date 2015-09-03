
<?
include(dirname(__FILE__)."./../../include/private_config.php");
//用户如果处于离线状态，则不允许投注
if(empty($_SESSION['uid']))
{
  echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
  echo '<script type="text/javascript">top.location.href="/";</script>';
  exit;
}
 // p($_GET);
$arr_zuhe;
  foreach ($_GET as $k => $v) {
    $class = explode('num', $k);
      if($class[1]!=''){

      $arr_zuhe[]=$v;

    }
  }
$arr_zuhe_num = count($arr_zuhe);

  if($_GET['class2'] == '五不中'){
    $num_arr_num =  5;
    if($arr_zuhe_num < $num_arr_num){
      echo "<script>alert('请选择五至十个号码!');parent.location.href='main_left.php';</script>";
      exit;
    }
  }elseif($_GET['class2'] == '六不中'){
    $num_arr_num =  6;
    if($arr_zuhe_num < $num_arr_num){
      echo "<script>alert('请选择六至十个号码!');parent.location.href='main_left.php';</script>";
      exit;
    }
  }elseif($_GET['class2'] == '七不中'){
    $num_arr_num =  7;
    if($arr_zuhe_num < $num_arr_num){
      echo "<script>alert('请选择七至十个号码!');parent.location.href='main_left.php';</script>";
      exit;
    }
  }elseif($_GET['class2'] == '八不中'){
    $num_arr_num =  8;
    if($arr_zuhe_num < $num_arr_num){
      echo "<script>alert('请选择八至十个号码!');parent.location.href='main_left.php';</script>";
      exit;
    }
  }elseif($_GET['class2'] == '九不中'){
    $num_arr_num =  9;
    if($arr_zuhe_num < $num_arr_num){
      echo "<script>alert('请选择九至十一个号码!');parent.location.href='main_left.php';</script>";
      exit;
    }
  }elseif($_GET['class2'] == '十不中'){
    $num_arr_num =  10;
    if($arr_zuhe_num < $num_arr_num){
      echo "<script>alert('请选择十至十二个号码!');parent.location.href='main_left.php';</script>";
      exit;
    }
  }elseif($_GET['class2'] == '十一不中'){
    $num_arr_num =  11;
    if($arr_zuhe_num < $num_arr_num){
      echo "<script>alert('请选择十一至十三个号码!');parent.location.href='main_left.php';</script>";
      exit;
    }
  }elseif($_GET['class2'] == '十二不中'){
    $num_arr_num =  12;
    if($arr_zuhe_num < $num_arr_num){
      echo "<script>alert('请选择十二至十四个号码!');parent.location.href='main_left.php';</script>";
      exit;
    }
  }


  if(!empty($arr_zuhe)){
    $arr_zuhe_1=get_zuhe($arr_zuhe,$num_arr_num);
  }
  $arr_zuhe_count =  count($arr_zuhe_1);
$zs=0;

$n=0;
for ($t=1;$t<=49;$t=$t+1){
if ($_GET['num'.$t]!=""){
$number1.=intval($_GET['num'.$t]).",";
$n=$n+1;
}
}


switch ($_GET['rtype']){

case "五不中":
$mu=explode(",",$number1);
$mamama="1,1,1,1,1";
for ($d=0;$d<count($mu)-5;$d=$d+1){
for ($f=$d+1;$f<count($mu)-4;$f=$f+1){
for ($t=$f+1;$t<count($mu)-3;$t=$t+1){
for ($u=$t+1;$u<count($mu)-2;$u=$u+1){
for ($v=$u+1;$v<count($mu)-1;$v=$v+1){

$mama=$mama."/".$mu[$d].",".$mu[$f].",".$mu[$t].",".$mu[$u].",".$mu[$v];
}
}
}
}
}
$ff=explode("/",$mama);
for ($p=0;$p<count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
for ($k=0;$k<=3;$k=$k+1){
for ($f=k+1;$f<=4;$f=$f+1){
if ($un[$k]>$un[$f]){
$tmp=$un[$k];
$un[$k]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[3].",".$un[2].",".$un[1].",".$un[4];
}

for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){

}else{
$zzz=$zzz."/".$ff[$f];
}
}
break;



case "六不中":

$mu=explode(",",$number1);
$mamama="1,1,1,1,1,1";
for ($d=0;$d<count($mu)-6;$d=$d+1){
for ($f=$d+1;$f<count($mu)-5;$f=$f+1){
for ($t=$f+1;$t<count($mu)-4;$t=$t+1){
for ($u=$t+1;$u<count($mu)-3;$u=$u+1){
for ($v=$u+1;$v<count($mu)-2;$v=$v+1){
for ($w=$v+1;$w<count($mu)-1;$w=$w+1){
$mama=$mama."/".$mu[$d].",".$mu[$f].",".$mu[$t].",".$mu[$u].",".$mu[$v].",".$mu[$w];
}
}
}
}
}
}
$ff=explode("/",$mama);
for ($p=0;$p<count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
for ($k=0;$k<=4;$k=$k+1){
for ($f=k+1;$f<=5;$f=$f+1){
if ($un[$k]>$un[$f]){
$tmp=$un[$k];
$un[$k]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[4].",".$un[3].",".$un[2].",".$un[1].",".$un[5];
}
for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){
}else{
$zzz=$zzz."/".$ff[$f];
}
}
break;


case "七不中":

$mu=explode(",",$number1);
$mamama="1,1,1,1,1,1,1";
for ($d=0;$d<count($mu)-7;$d=$d+1){
for ($f=$d+1;$f<count($mu)-6;$f=$f+1){
for ($t=$f+1;$t<count($mu)-5;$t=$t+1){
for ($u=$t+1;$u<count($mu)-4;$u=$u+1){
for ($v=$u+1;$v<count($mu)-3;$v=$v+1){
for ($w=$v+1;$w<count($mu)-2;$w=$w+1){
for ($x=$w+1;$x<count($mu)-1;$x=$x+1){
$mama=$mama."/".$mu[$d].",".$mu[$f].",".$mu[$t].",".$mu[$u].",".$mu[$v].",".$mu[$w].",".$mu[$x];
}
}
}
}
}
}
}
$ff=explode("/",$mama);
for ($p=0;$p<count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
for ($k=0;$k<=5;$k=$k+1){
for ($f=k+1;$f<=6;$f=$f+1){
if ($un[$k]>$un[$f]){
$tmp=$un[$k];
$un[$k]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[5].",".$un[4].",".$un[3].",".$un[2].",".$un[1].",".$un[6];
}
for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){
}else{
$zzz=$zzz."/".$ff[$f];
}
}
break;



case "八不中":

$mu=explode(",",$number1);
$mamama="1,1,1,1,1,1,1,1";
for ($d=0;$d<count($mu)-8;$d=$d+1){
for ($f=$d+1;$f<count($mu)-7;$f=$f+1){
for ($t=$f+1;$t<count($mu)-6;$t=$t+1){
for ($u=$t+1;$u<count($mu)-5;$u=$u+1){
for ($v=$u+1;$v<count($mu)-4;$v=$v+1){
for ($w=$v+1;$w<count($mu)-3;$w=$w+1){
for ($x=$w+1;$x<count($mu)-2;$x=$x+1){
for ($y=$x+1;$y<count($mu)-1;$y=$y+1){
$mama=$mama."/".$mu[$d].",".$mu[$f].",".$mu[$t].",".$mu[$u].",".$mu[$v].",".$mu[$w].",".$mu[$x].",".$mu[$y];
}
}
}
}
}
}
}
}

$ff=explode("/",$mama);
for ($p=0;$p<count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
for ($k=0;$k<=6;$k=$k+1){
for ($f=k+1;$f<=7;$f=$f+1){
if ($un[$k]>$un[$f]){
$tmp=$un[$k];
$un[$k]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[6].",".$un[5].",".$un[4].",".$un[3].",".$un[2].",".$un[1].",".$un[7];
}
for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){
}else{
$zzz=$zzz."/".$ff[$f];
}
}
break;

case "九不中":

$mu=explode(",",$number1);
$mamama="1,1,1,1,1,1,1,1,1";
for ($d=0;$d<count($mu)-9;$d=$d+1){
for ($f=$d+1;$f<count($mu)-8;$f=$f+1){
for ($t=$f+1;$t<count($mu)-7;$t=$t+1){
for ($u=$t+1;$u<count($mu)-6;$u=$u+1){
for ($v=$u+1;$v<count($mu)-5;$v=$v+1){
for ($w=$v+1;$w<count($mu)-4;$w=$w+1){
for ($x=$w+1;$x<count($mu)-3;$x=$x+1){
for ($y=$x+1;$y<count($mu)-2;$y=$y+1){
for ($z=$y+1;$z<count($mu)-1;$z=$z+1){
$mama=$mama."/".$mu[$d].",".$mu[$f].",".$mu[$t].",".$mu[$u].",".$mu[$v].",".$mu[$w].",".$mu[$x].",".$mu[$y].",".$mu[$z];
}
}
}
}
}
}
}
}
}
$ff=explode("/",$mama);
for ($p=0;$p<count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
for ($k=0;$k<=7;$k=$k+1){
for ($f=k+1;$f<=8;$f=$f+1){
if ($un[$k]>$un[$f]){
$tmp=$un[$k];
$un[$k]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[7].",".$un[6].",".$un[5].",".$un[4].",".$un[3].",".$un[2].",".$un[1].",".$un[8];
}
for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){
}else{
$zzz=$zzz."/".$ff[$f];
}
}
break;


case "十不中":

$mu=explode(",",$number1);
$mamama="1,1,1,1,1,1,1,1,1,1";
for ($d=0;$d<count($mu)-10;$d=$d+1){
for ($f=$d+1;$f<count($mu)-9;$f=$f+1){
for ($t=$f+1;$t<count($mu)-8;$t=$t+1){
for ($u=$t+1;$u<count($mu)-7;$u=$u+1){
for ($v=$u+1;$v<count($mu)-6;$v=$v+1){
for ($w=$v+1;$w<count($mu)-5;$w=$w+1){
for ($x=$w+1;$x<count($mu)-4;$x=$x+1){
for ($y=$x+1;$y<count($mu)-3;$y=$y+1){
for ($z=$y+1;$z<count($mu)-2;$z=$z+1){
for ($g=$z+1;$g<count($mu)-1;$g=$g+1){
$mama=$mama."/".$mu[$d].",".$mu[$f].",".$mu[$t].",".$mu[$u].",".$mu[$v].",".$mu[$w].",".$mu[$x].",".$mu[$y].",".$mu[$z].",".$mu[$g];
}
}
}
}
}
}
}
}
}
}

$ff=explode("/",$mama);
for ($p=0;$p<count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
for ($k=0;$k<=8;$k=$k+1){
for ($f=k+1;$f<=9;$f=$f+1){
if ($un[$k]>$un[$f]){
$tmp=$un[$k];
$un[$k]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[8].",".$un[7].",".$un[6].",".$un[5].",".$un[4].",".$un[3].",".$un[2].",".$un[1].",".$un[9];
}
for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){
}else{
$zzz=$zzz."/".$ff[$f];
}
}
break;

case "十一不中":

$mu=explode(",",$number1);
$mamama="1,1,1,1,1,1,1,1,1,1,1";
for ($d=0;$d<count($mu)-11;$d=$d+1){
for ($f=$d+1;$f<count($mu)-10;$f=$f+1){
for ($t=$f+1;$t<count($mu)-9;$t=$t+1){
for ($u=$t+1;$u<count($mu)-8;$u=$u+1){
for ($v=$u+1;$v<count($mu)-7;$v=$v+1){
for ($w=$v+1;$w<count($mu)-6;$w=$w+1){
for ($x=$w+1;$x<count($mu)-5;$x=$x+1){
for ($y=$x+1;$y<count($mu)-4;$y=$y+1){
for ($z=$y+1;$z<count($mu)-3;$z=$z+1){
for ($g=$z+1;$g<count($mu)-2;$g=$g+1){
for ($h=$g+1;$h<count($mu)-1;$h=$h+1){
$mama=$mama."/".$mu[$d].",".$mu[$f].",".$mu[$t].",".$mu[$u].",".$mu[$v].",".$mu[$w].",".$mu[$x].",".$mu[$y].",".$mu[$z].",".$mu[$g].",".$mu[$h];
}
}
}
}
}
}
}
}
}
}
}

$ff=explode("/",$mama);
for ($p=0;$p<count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
for ($k=0;$k<=9;$k=$k+1){
for ($f=k+1;$f<=10;$f=$f+1){
if ($un[$k]>$un[$f]){
$tmp=$un[$k];
$un[$k]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[9].",".$un[8].",".$un[7].",".$un[6].",".$un[5].",".$un[4].",".$un[3].",".$un[2].",".$un[1].",".$un[10];
}
for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){
}else{
$zzz=$zzz."/".$ff[$f];
}
}
break;


case "十二不中":

$mu=explode(",",$number1);
$mamama="1,1,1,1,1,1,1,1,1,1,1,1";
for ($d=0;$d<count($mu)-12;$d=$d+1){
for ($f=$d+1;$f<count($mu)-11;$f=$f+1){
for ($t=$f+1;$t<count($mu)-10;$t=$t+1){
for ($u=$t+1;$u<count($mu)-9;$u=$u+1){
for ($v=$u+1;$v<count($mu)-8;$v=$v+1){
for ($w=$v+1;$w<count($mu)-7;$w=$w+1){
for ($x=$w+1;$x<count($mu)-6;$x=$x+1){
for ($y=$x+1;$y<count($mu)-5;$y=$y+1){
for ($z=$y+1;$z<count($mu)-4;$z=$z+1){
for ($g=$z+1;$g<count($mu)-3;$g=$g+1){
for ($h=$g+1;$h<count($mu)-2;$h=$h+1){
for ($i=$h+1;$i<count($mu)-1;$i=$i+1){
$mama=$mama."/".$mu[$d].",".$mu[$f].",".$mu[$t].",".$mu[$u].",".$mu[$v].",".$mu[$w].",".$mu[$x].",".$mu[$y].",".$mu[$z].",".$mu[$g].",".$mu[$h].",".$mu[$i];
}
}
}
}
}
}
}
}
}
}
}
}

$ff=explode("/",$mama);
for ($p=0;$p<count($ff);$p=$p+1){
$un=explode(",",$ff[$p]);
for ($k=0;$k<=10;$k=$k+1){
for ($f=k+1;$f<=11;$f=$f+1){
if ($un[$k]>$un[$f]){
$tmp=$un[$k];
$un[$k]=$un[$f];
$un[$f]=$tmp;
}
}
}
$ff[$p]=$un[0].",".$un[10].",".$un[9].",".$un[8].",".$un[7].",".$un[6].",".$un[5].",".$un[4].",".$un[3].",".$un[2].",".$un[1].",".$un[11];
}
for ($f=0;$f<=count($ff);$f=$f+1){
if (strpos($zzz,$ff[$f])>0){
}else{
$zzz=$zzz."/".$ff[$f];
}
}
break;

}


switch ($_GET['rtype']){

case "五不中":
$rtype="五不中";
$rate_id=1101;
$zs=$n*($n-1)*($n-2)*($n-3)*($n-4)/120;
$R=37;
break;

case "六不中":
$rtype="六不中";
$rate_id=1151;
$zs=$n*($n-1)*($n-2)*($n-3)*($n-4)*($n-5)/720;
$R=38;
break;

case "七不中":
$rtype="七不中";
$rate_id=1201;
$zs=$n*($n-1)*($n-2)*($n-3)*($n-4)*($n-5)*($n-6)/5040;
$R=39;
break;

case "八不中":
$rtype="八不中";
$rate_id=1251;
$zs=$n*($n-1)*($n-2)*($n-3)*($n-4)*($n-5)*($n-6)*($n-7)/40320;
$R=40;
break;

case "九不中":
$rtype="九不中";
$rate_id=1501;
$zs=$n*($n-1)*($n-2)*($n-3)*($n-4)*($n-5)*($n-6)*($n-7)*($n-8)/362880;
$R=41;
break;

case "十不中":
$rtype="十不中";
$rate_id=1551;
$zs=$n*($n-1)*($n-2)*($n-3)*($n-4)*($n-5)*($n-6)*($n-7)*($n-8)*($n-9)/3628780;
$R=42;
break;

case "十一不中":
$rtype="十一不中";
$rate_id=1601;
$zs=$n*($n-1)*($n-2)*($n-3)*($n-4)*($n-5)*($n-6)*($n-7)*($n-8)*($n-9)*($n-10)/39916780;
$R=43;
break;

case "十二不中":
$rtype="十二不中";
$rate_id=1651;
$zs=$n*($n-1)*($n-2)*($n-3)*($n-4)*($n-5)*($n-6)*($n-7)*($n-8)*($n-9)*($n-10)*($n-11)/479001600;
$R=44;
break;

}


// $result=$mysqli->query("select sum(sum_m) as sum_mm from c_bet where qishu='".$Current_Kithe_Num."' and  username='".$_SESSION['username']."' and mingxi_1='全不中' and mingxi_3='".$rtype."'  order by id desc");
// $ka_guanuserkk1=$result->fetch_array();
// $sum_money=$ka_guanuserkk1[0];


if ($zs==0){

if ($_GET['rtype']=="五不中"){
echo "<script>alert('请选择五至十个号码!');parent.location.href='main_left.php';</script>";
exit;
}
if ($_GET['rtype']=="六不中"){
echo "<script>alert('请选择六至十个号码!');parent.location.href='main_left.php';</script>";
exit;
}
if ($_GET['rtype']=="七不中"){
echo "<script>alert('请选择七至十个号码!');parent.location.href='main_left.php';</script>";
exit;
}
if ($_GET['rtype']=="八不中"){
echo "<script>alert('请选择八至十个号码!');parent.location.href='main_left.php';</script>";
exit;
}
if ($_GET['rtype']=="九不中"){
echo "<script>alert('请选择九至十一个号码!');parent.location.href='main_left.php';</script>";
exit;
}
if ($_GET['rtype']=="十不中"){
echo "<script>alert('请选择十至十二个号码!');parent.location.href='main_left.php';</script>";
exit;
}
if ($_GET['rtype']=="十一不中"){
echo "<script>alert('请选择十一至十三个号码!');parent.location.href='main_left.php';</script>";
exit;
}
if ($_GET['rtype']=="十二不中"){
echo "<script>alert('请选择十二至十四个号码!');parent.location.href='main_left.php';</script>";
exit;
}





}

$XF=21;

$beted = beted_limit(7,$_GET['type'],$db_config);
//查询该玩法当前期数已下注金额

$ball_limit_num = $beted['sum(money)'];

?>
<HTML>
<HEAD>


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
</style><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></HEAD>
<!-- <SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';}
if(window.location.host!=top.location.host){top.location=window.location;}
window.setTimeout("self.location='liuhecai.php?action=k_wbz'", 60000);
</SCRIPT> -->
<SCRIPT language=javascript>var count_win=false;
function CheckKey(){
if(event.keyCode == 13) return true;
if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("下注金额仅能输入数字!!"); return false;}
}


function CountWinGold(){
if(document.all.gold.value==''){
document.all.gold.focus();
alert('未输入下注金额!!!');
}else{
//document.all.pc.innerHTML=Math.round(document.all.gold.value * document.all.ioradio.value /1000 - document.all.gold.value);
document.all.pc1.innerHTML=Math.round(document.all.gold.value * document.all.ioradio1.value);
count_win=true;
}
}
function CountWinGold1(){
if(document.all.gold.value==''){
document.all.gold.focus();
alert('未输入下注金额!!!');
}else{
document.all.pc1.innerHTML=Math.round(document.all.gold.value * document.all.ioradio1.value);
count_win=true;
}
}
</SCRIPT>

<script>

$(function(){
    var single_field_max = $("#single_field_max").text(); //单注总金额上限200000
    var single_note_min = $("#single_note_min").text();
    var single_note_max = $("#single_note_max").text();//单注金额上限20000
    var ball_limit_num = <?=$ball_limit_num ?>;//已投注的金额

     // alert(single_note_max);
      $(".single_note_min").text(single_note_min);
      $(".single_note_max").text(single_note_max);
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
        if(parseInt(val1) >=parseInt(single_note_min) &&parseInt(val1) <=parseInt(single_note_max) && parseInt(val1*zs)<=parseInt(single_field_max) && (parseInt(val1*zs)+parseInt(ball_limit_num)) <=parseInt(single_field_max)){
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
  $("#order_form_1").submit(function(event) {
    var val = $("#total_sum").text();
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
<!-- <table height="13" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody><tr>
          <td id="left_1" style="text-align:center; font-size:16px; font-weight:bold"><img src="/images/b002.jpg"></td>
        </tr>
      </tbody></table> -->
<body>

<div id="zs_div" style="display:none"><?=$zs ?></div>
<div id="bet_val" style="display:none"><?=ka_bl($rate_id,"rate")?></div>
<div style="display:none">
<TABLE class=Tab_backdrop cellSpacing=0 cellPadding=0 width=100% border=0>
  <tr>
    <td valign="top" class="Left_Place">
                <TABLE class=t_list cellSpacing=1 cellPadding=0 width=100% border=0>
      <tr>
        <td height="25" colspan="2" align="center" bgcolor="#504a16" style="color:#FFF">确认下注</td>
      </tr>

      <FORM name=LAYOUTFORM onSubmit="return false" action=liuhecai.php?action=k_tanwbz method=post >
        <tr>
          <td width="35%" colspan="2" height="25" bgcolor="#ffffff" align="center" style="LINE-HEIGHT: 22px"><div align="center"><FONT color=#cc0000>&nbsp;<?=$_GET['rtype']?>  </FONT> </div><?=$number1?></td>
        </tr>
        <tr>
          <td height="25" colspan="2" bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><div align="center"><strong>组合共&nbsp;<font color=ff0000><?=$arr_zuhe_count?></font>&nbsp;组</strong></div></td>
        </tr>
        <tr>
          <td height="25" colspan="2" bgcolor="#ffffff" style="LINE-HEIGHT: 23px">&nbsp;下注金额:<?=$_GET['jq']?>
            <INPUT type="hidden" name="gold" id=""  value="<?=$_GET['gold']?>" ></td>
        </tr>
        <tr>
          <td height="25" colspan="2" bgcolor="#ffffff" style="LINE-HEIGHT: 23px">&nbsp;总下注金额: <strong><FONT id=pc1 color=#ff0000><?=$_GET['jq']*$zs?>&nbsp;</FONT></strong></td>
        </tr>
        <TR>
          <TD height="25" colspan="2" bgcolor="#ffffff">&nbsp;单注限额: <span class="single_note_max">20000</span></TD>
        </TR>
        <TR>
          <TD height="25" colspan="2" bgcolor="#ffffff">&nbsp;单项限额: <span class="single_field_max">200000</span></TD>
        </TR>
        <tr>
          <td height="25" colspan="2" align="center" bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><INPUT type='hidden' Name=rate_id value='<?=$rate_id?>'>

              <input  class="button_a"  onClick="self.location='liuhecai.php?action=k_wbz'" type="button" value="放弃" name="btnCancel" />

            &nbsp;&nbsp;
            <input  class="button_a"  type="submit" value="确定" onClick="SubChk(<?=$zs?>);" name="btnSubmit" /></td>
        </tr>
        <INPUT type=hidden value=SP11 name=concede>
        <INPUT type=hidden value='<?=ka_bl($rate_id,"rate")*1000?>' name=ioradio>
        <INPUT type=hidden value='<?=$zs?>' name=ioradio1>
        <INPUT type=hidden value='<?=$zzz?>' name=number1>
         <input name=rtype type=hidden id="rtype" value='<?=$_GET['rtype']?>'>
      </FORM>

    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="3"></td>
      </tr>
    </table></td>
</tr>
</table></div>

<!-- 显示下注信息 -->
<?php
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
    <div class="title"><h1>全不中-<?=$_GET['class2']  ?></h1></div>
    <div class="main">
      <!--div class="leag"><strong></strong><span class="tName">连码-三全中</span></div>
      <div class="teamName"><span class="tName"></span></div-->
      <p class="team" style="text-align:center">
      <?php foreach ($arr_zuhe_1 as  $v) {
        $vs = explode(',', $v);

      ?>


         <?php foreach ($vs as $kk => $vv) {
          if($kk+1 == count($vs)){
            echo $vv;
          }else{
            echo $vv.',';
          }

            if($kk == 6){
              echo "</br>";
            }
         } ?> @ <font color="#ff0000"><?=ka_bl($rate_id,"rate")?></font></br>

    <?php } ?>
      -----------------------<br>组合 共 <font color="red"><?=$arr_zuhe_count ?></font> 组</p>
      <p class="error" style="display: none;"></p>
      <div class="betdata">
        <p class="amount">交易金额：<input name="gold" type="text" class="txt" id="gold" onkeypress="return CheckKey(event)" onkeyup="return CountWinGold()" size="8" maxlength="10" value="<?=$_GET['jq']?>"></p>
        <p class="mayWin"><span class="bet_txt">总下注金额：</span><font id="pc"><?=$_GET['jq']*$zs?></font></p>

        <!-- <p class="mayWin"><span class="bet_txt">可赢金额：</span><font id="total"><?php echo ka_bl($rate_id,"rate")*$zs*$_GET['jq']  ?></font></p> -->

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

<?php
if(!empty($_GET['show']) && $_GET['show'] == 1){
  echo "<script>";
  echo 'document.LAYOUTFORM.submit();';
  echo "</script>";
}

 ?>

</BODY></HTML>
