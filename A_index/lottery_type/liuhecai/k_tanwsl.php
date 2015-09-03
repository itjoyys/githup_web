<?
// if(!defined('PHPYOU')) {
// 	exit('非法进入');
// }

// if ($_GET['class2']==""){echo "<script>alert('非法进入!');window.location.href='liuhecai.php?action=k_wsl';</script>";
// exit;}
is_fengpan($db_config);
include (dirname(__FILE__) . "./../../include/private_config.php");
// var_dump($_POST);var_dump($_GET);exit;
$uid = $_SESSION["uid"];

$userinfo['agent_id'] =	$_SESSION["agent_id"];
$username = $_SESSION["username"];
$gold=$_POST['gold'];
$type_y = 6;
$XF = 21;

$edu = user_money($username,$gold,0);
if($edu==-1){
    echo '<script type="text/javascript">alert("您的账户额度不足进行本次投注，请充值后在进行投注！","投注失败");</script>';
    exit;
}

// $result=$mysqli->query("select sum(sum_m) as sum_mm from c_bet where kithe='".$Current_Kithe_Num."' and  username='".$_SESSION['kauser']."' and class1='尾数连' and class2='".$_GET['class2']."'  order by id desc");
// $ka_guanuserkk1=$result->fetch_array();
// $sum_mm=$ka_guanuserkk1[0];

$drop_sort=$_GET['class2'];

switch ($_GET['class2']){

 case "二尾连中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=56;
$XF=23;
break;

 case "三尾连中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=57;
$XF=23;
break;
 case "四尾连中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=58;
$XF=23;
break;

 case "二尾连不中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=59;
$XF=23;
break;
 case "三尾连不中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=60;
$XF=23;
break;
 case "四尾连不中":
$bmmm=0;
$cmmm=0;
$dmmm=0;
$R=61;
$XF=23;
break;

}



?>




<link rel="stylesheet" href="./public/css/xp.css">
<link rel="stylesheet" href="./public/css/mem_order_ft.css" type="text/css">
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
</SCRIPT>

<!-- <SCRIPT language=javascript>
window.setTimeout("self.location='liuhecai.php?action=k_wsl'", 30000);
</SCRIPT> -->
<SCRIPT language=JAVASCRIPT>
if(self == top){location = '/';}

function ChkSubmit(){
		document.all.btnSubmit.disabled = true;
document.form1.submit();
	}
</SCRIPT>

<body>
<!-- <table height="13" cellspacing="0" cellpadding="0" border="0" width="180">
        <tbody><tr>
          <td id="left_1" style="text-align:center; font-size:16px; font-weight:bold"><img src="/images/b002.jpg"></td>
        </tr>
      </tbody></table>



<TABLE class=Tab_backdrop cellSpacing=0 cellPadding=0 width=180 border=0>
<tr>
    <td valign="top" class="Left_Place">
        <TABLE class=t_list cellSpacing=1 cellPadding=0 width=180 border=0>
        <tr>
          <td height="28" colspan="3" align="center" bordercolor="#cccccc" bgcolor="#504a16" style="LINE-HEIGHT: 23px"><span class="STYLE3">下注成功</span></td>
          </tr>
        <tr>
          <td height="22" align="center"class=left_acc_out_top style="LINE-HEIGHT: 23px"><span class="STYLE3">内容</span></td>
          <td align="center" class=left_acc_out_top style="LINE-HEIGHT: 23px"><span class="STYLE3">赔率</span></td>
          <td align="center" class=left_acc_out_top style="LINE-HEIGHT: 23px"><span class="STYLE3">下注金额</span></td>
        </tr> -->
         <div class="ord">
    <div class="title"><h1>六合彩</h1></div>
    <div class="main">
      <div class="fin_title">
          <p class="fin_acc">成功提交注单！</p>
          <p class="error" style="display: none;"></p>
         </div>
          <div class="leag_more"><?=$_GET['class2']?></div>
				      <?


$sum_m=$_POST['gold']*$_POST['ioradio1'];

$gold=$_POST['gold'];


$rate1=1;

$number1=$_POST['number1'];


$mu=explode("/",$number1);

$ioradio1=1;
$t==3;
for ($t=0;$t<count($mu);$t=$t+1){


$muname=explode(",",$mu[$t]);

switch ($_GET['class2']){

 case "二尾连中":

switch ($muname[0]){

case "1":
$r1=1301;
break;
case "2":
$r1=1302;
break;
case "3":
$r1=1303;
break;
case "4":
$r1=1304;
break;
case "5":
$r1=1305;
break;
case "6":
$r1=1306;
break;
case "7":
$r1=1307;
break;
case "8":
$r1=1308;
break;
case "9":
$r1=1309;
break;
case "0":
$r1=1310;
break;

}
switch ($muname[1]){

case "1":
$r2=1301;
break;
case "2":
$r2=1302;
break;
case "3":
$r2=1303;
break;
case "4":
$r2=1304;
break;
case "5":
$r2=1305;
break;
case "6":
$r2=1306;
break;
case "7":
$r2=1307;
break;
case "8":
$r2=1308;
break;
case "9":
$r2=1309;
break;
case "0":
$r2=1310;
break;
}
$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
if ($rate2<$rate1) $rate1=$rate2;
break;


 case "二尾连不中":

switch ($muname[0]){

case "1":
$r1=1331;
break;
case "2":
$r1=1332;
break;
case "3":
$r1=1333;
break;
case "4":
$r1=1334;
break;
case "5":
$r1=1335;
break;
case "6":
$r1=1336;
break;
case "7":
$r1=1337;
break;
case "8":
$r1=1338;
break;
case "9":
$r1=1339;
break;
case "0":
$r1=1340;
break;
}
switch ($muname[1]){

case "1":
$r2=1331;
break;
case "2":
$r2=1332;
break;
case "3":
$r2=1333;
break;
case "4":
$r2=1334;
break;
case "5":
$r2=1335;
break;
case "6":
$r2=1336;
break;
case "7":
$r2=1337;
break;
case "8":
$r2=1338;
break;
case "9":
$r2=1339;
break;
case "0":
$r2=1340;
break;
}
$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
if ($rate2<$rate1) $rate1=$rate2;
break;





 case "三尾连中":

switch ($muname[0]){

case "1":
$r1=1311;
break;
case "2":
$r1=1312;
break;
case "3":
$r1=1313;
break;
case "4":
$r1=1314;
break;
case "5":
$r1=1315;
break;
case "6":
$r1=1316;
break;
case "7":
$r1=1317;
break;
case "8":
$r1=1318;
break;
case "9":
$r1=1319;
break;
case "0":
$r1=1320;
break;

}
switch ($muname[1]){
case "1":
$r2=1311;
break;
case "2":
$r2=1312;
break;
case "3":
$r2=1313;
break;
case "4":
$r2=1314;
break;
case "5":
$r2=1315;
break;
case "6":
$r2=1316;
break;
case "7":
$r2=1317;
break;
case "8":
$r2=1318;
break;
case "9":
$r2=1319;
break;
case "0":
$r2=1320;
break;
}
switch ($muname[2]){
case "1":
$r3=1311;
break;
case "2":
$r3=1312;
break;
case "3":
$r3=1313;
break;
case "4":
$r3=1314;
break;
case "5":
$r3=1315;
break;
case "6":
$r3=1316;
break;
case "7":
$r3=1317;
break;
case "8":
$r3=1318;
break;
case "9":
$r3=1319;
break;
case "0":
$r3=1320;
break;
}
$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
$rate3=ka_bl($r3,"rate");
if ($rate2<$rate1) $rate1=$rate2;
if ($rate3<$rate1) $rate1=$rate3;
break;


 case "三尾连不中":

switch ($muname[0]){

case "1":
$r1=1341;
break;
case "2":
$r1=1342;
break;
case "3":
$r1=1343;
break;
case "4":
$r1=1344;
break;
case "5":
$r1=1345;
break;
case "6":
$r1=1346;
break;
case "7":
$r1=1347;
break;
case "8":
$r1=1348;
break;
case "9":
$r1=1349;
break;
case "0":
$r1=1350;
break;

}
switch ($muname[1]){
case "1":
$r2=1341;
break;
case "2":
$r2=1342;
break;
case "3":
$r2=1343;
break;
case "4":
$r2=1344;
break;
case "5":
$r2=1345;
break;
case "6":
$r2=1346;
break;
case "7":
$r2=1347;
break;
case "8":
$r2=1348;
break;
case "9":
$r2=1349;
break;
case "0":
$r2=1350;
break;
}
switch ($muname[2]){
case "1":
$r3=1341;
break;
case "2":
$r3=1342;
break;
case "3":
$r3=1343;
break;
case "4":
$r3=1344;
break;
case "5":
$r3=1345;
break;
case "6":
$r3=1346;
break;
case "7":
$r3=1347;
break;
case "8":
$r3=1348;
break;
case "9":
$r3=1349;
break;
case "0":
$r3=1350;
break;
}
$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
$rate3=ka_bl($r3,"rate");
if ($rate2<$rate1) $rate1=$rate2;
if ($rate3<$rate1) $rate1=$rate3;
break;


 case "四尾连中":

switch ($muname[0]){

case "1":
$r1=1321;
break;
case "2":
$r1=1322;
break;
case "3":
$r1=1323;
break;
case "4":
$r1=1324;
break;
case "5":
$r1=1325;
break;
case "6":
$r1=1326;
break;
case "7":
$r1=1327;
break;
case "8":
$r1=1328;
break;
case "9":
$r1=1329;
break;
case "0":
$r1=1330;
break;

}
switch ($muname[1]){
case "1":
$r2=1321;
break;
case "2":
$r2=1322;
break;
case "3":
$r2=1323;
break;
case "4":
$r2=1324;
break;
case "5":
$r2=1325;
break;
case "6":
$r2=1326;
break;
case "7":
$r2=1327;
break;
case "8":
$r2=1328;
break;
case "9":
$r2=1329;
break;
case "0":
$r2=1330;
break;
}
switch ($muname[2]){
case "1":
$r3=1321;
break;
case "2":
$r3=1322;
break;
case "3":
$r3=1323;
break;
case "4":
$r3=1324;
break;
case "5":
$r3=1325;
break;
case "6":
$r3=1326;
break;
case "7":
$r3=1327;
break;
case "8":
$r3=1328;
break;
case "9":
$r3=1329;
break;
case "0":
$r3=1330;
break;
}
switch ($muname[3]){
case "1":
$r4=1321;
break;
case "2":
$r4=1322;
break;
case "3":
$r4=1323;
break;
case "4":
$r4=1324;
break;
case "5":
$r4=1325;
break;
case "6":
$r4=1326;
break;
case "7":
$r4=1327;
break;
case "8":
$r4=1328;
break;
case "9":
$r4=1329;
break;
case "0":
$r4=1330;
break;
}

$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
$rate3=ka_bl($r3,"rate");
$rate4=ka_bl($r4,"rate");
if ($rate2<$rate1) $rate1=$rate2;
if ($rate3<$rate1) $rate1=$rate3;
if ($rate4<$rate1) $rate1=$rate4;
break;

 case "四尾连不中":

switch ($muname[0]){

case "1":
$r1=1351;
break;
case "2":
$r1=1352;
break;
case "3":
$r1=1353;
break;
case "4":
$r1=1354;
break;
case "5":
$r1=1355;
break;
case "6":
$r1=1356;
break;
case "7":
$r1=1357;
break;
case "8":
$r1=1358;
break;
case "9":
$r1=1359;
break;
case "0":
$r1=1360;
break;

}
switch ($muname[1]){
case "1":
$r2=1351;
break;
case "2":
$r2=1352;
break;
case "3":
$r2=1353;
break;
case "4":
$r2=1354;
break;
case "5":
$r2=1355;
break;
case "6":
$r2=1356;
break;
case "7":
$r2=1357;
break;
case "8":
$r2=1358;
break;
case "9":
$r2=1359;
break;
case "0":
$r2=1360;
break;
}
switch ($muname[2]){
case "1":
$r3=1351;
break;
case "2":
$r3=1352;
break;
case "3":
$r3=1353;
break;
case "4":
$r3=1354;
break;
case "5":
$r3=1355;
break;
case "6":
$r3=1356;
break;
case "7":
$r3=1357;
break;
case "8":
$r3=1358;
break;
case "9":
$r3=1359;
break;
case "0":
$r3=1360;
break;
}
switch ($muname[3]){
case "1":
$r4=1351;
break;
case "2":
$r4=1352;
break;
case "3":
$r4=1353;
break;
case "4":
$r4=1354;
break;
case "5":
$r4=1355;
break;
case "6":
$r4=1356;
break;
case "7":
$r4=1357;
break;
case "8":
$r4=1358;
break;
case "9":
$r4=1359;
break;
case "0":
$r4=1360;
break;
}

$rate1=ka_bl($r1,"rate");
$rate2=ka_bl($r2,"rate");
$rate3=ka_bl($r3,"rate");
$rate4=ka_bl($r4,"rate");
if ($rate2<$rate1) $rate1=$rate2;
if ($rate3<$rate1) $rate1=$rate3;
if ($rate4<$rate1) $rate1=$rate4;
break;

}

switch (ka_memuser("abcd")){

	case "A":
$rate5=$rate1;
$Y=1;
break;
	case "B":
$rate5=$rate1-$bmmm;
$Y=4;
	break;
	case "C":
	$Y=5;
$rate5=$rate1-$cmmm;
	break;
	case "D":
	$rate5=$rate1-$dmmm;
$Y=6;
break;
	default:
	$Y=1;
$rate5=$rate1;
break;
}







$num=date("YmdHis").mt_rand("100000","999999");
$text=date("Y-m-d H:i:s");
$class11=ka_bl($rate_id,"class1");
$class22=ka_bl($rate_id,"class2");
$class33=$mu[$t];
//$gold=$gold;
$user_ds=ka_memds($R,1);



$q1=$q2=0;
$mysqlt->autocommit(FALSE);
$mysqlt->query("BEGIN"); //事务开始
try{
    $sql="update k_user set money=money-$gold where uid=".$_SESSION['uid']." and money>=$sum_m"; //扣钱
    $mysqlt->query($sql);
    $q1		=	$mysqlt->affected_rows;

    if($q1==1){
        $win = $rate5 * $_POST['gold'];
        $u_money = Get_user_One($_SESSION['username'],'money');
        $s_money = $u_money;
        $cpfsbl = $sum_m * 0.1;
        $type = "六合彩";
        $uid = Get_user_One($_SESSION['username'],'uid');
	    $sql1 = "INSERT INTO c_bet set did='" . $num . "',uid='" . $uid .
	    "',agent_id='" . $userinfo['agent_id'] . "',username='" . $_SESSION['username'] . "',addtime='" . $text .
	    "',type='" . $type . "',qishu='" . $Current_Kithe_Num . "',mingxi_1='" . $class11 .
	    "',mingxi_2='" . $class33 . "',mingxi_3='" . $class22 . "',odds='" . $rate5 . "',money='" . $gold .
	    "',win='" . $win . "',assets='" . $u_money . "',balance='" . $s_money .
	    "',fs='0',site_id='" . SITEID . "'";
        $mysqlt->query($sql1) or die("数据库修改出错");
        //$sql="INSERT INTO  c_odds_7 set num='".$num."',username='".$_SESSION['username']."',kithe='".$Current_Kithe_Num."',adddate='".$text."',class1='".$class11."',class2='".$class22."',class3='".$class33."',rate='".$rate5."',sum_m='".$gold."',user_ds='".$user_ds."',dai_ds='".$dai_ds."',zong_ds='".$zong_ds."',guan_ds='".$guan_ds."',dai_zc='".$dai_zc."',zong_zc='".$zong_zc."',guan_zc='".$guan_zc."',dagu_zc='".$dagu_zc."',bm=0,dai='".$dai."',zong='".$zong."',guan='".$guan."',danid='".$danid."',zongid='".$zongid."',guanid='".$guanid."',abcd='".$abcd."',lx=0";
        //$mysqli->query($sql) or  die("数据库修改出错");
        $q2=$mysqlt->affected_rows;
        $money_all_h = $sum_m;
//         $source_id .= $mysqlt->insert_id . ",";
//         $source_id = rtrim($source_id, ',');
        $source_id = $mysqlt->insert_id;
        $remark="彩票注单：" . $num." , 類型:" .$type;
        $sql2 = "insert into k_user_cash_record(source_id,site_id,uid,username,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark)
         values ('" . $source_id . "','" . SITEID . "','" . $uid . "','".$_SESSION['username']."','3','2','" . $gold . "',
        '" . $s_money . "','" . date("Y-m-d H:i:s", time()) . "','". $remark."')";
        $q3 = $mysqlt->query($sql2)or die("数据库修改出错");

    }

    if($q1==1 && $q2==1&& $q3==1 ){
        $mysqlt->commit(); //事务提交
    }else{

        $mysqlt->rollback(); //数据回滚
    }

}catch(Exception $e){
    $mysqlt->rollback(); //数据回滚
}
include 'ds.php';
?>
               <script type="text/javascript">
    parent.parent.k_memr.location.reload();
 </script>

  <!--  <tr>
                    <td height="22" bordercolor="#FDF4CA" bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><font color="#FF0000"><?=$class22?>：<font color=ff6600><?=$mu[$t]?></font></font></td>
                    <td align="center" bordercolor="#FDF4CA" bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><?=$rate5?></td>
                    <td align="center" bordercolor="#FDF4CA" bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><?=$gold?></td>
                  </tr> -->

    <p class="fin_team margin_top"><em><?=$mu[$t]?></em> @ <strong><?=$rate5?></strong></p>
    <p class="fin_amount">交易金额：<span class="fin_gold"><?=$_POST['gold']?></span>

    </p>
                  <? }?>
				  </div>
    <div class="betBox">
      <input type="button" name="PRINT" value="确定" onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="print">
      <input type="button" name="FINISH" value="关闭" onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="close">
    </div>

  </div>
                <!--   <tr>
                    <td height="22" colspan="3" align="center" bgcolor="#ffffff" style="LINE-HEIGHT: 23px"><input  class="button_a"  onClick="self.location='liuhecai.php?action=k_wsl'" type="button" value="离开" name="btnCancel" />
&nbsp;&nbsp;<input  class="button_a"  type="submit" value="确定" onClick="self.location='liuhecai.php?action=k_wsl'" name="btnSubmit" />
</td>
                  </tr>
              </table></td>
        </tr>
        <tr>
          <td height="30" align="center">&nbsp;</td>
        </tr>

    </table></td>
  </tr>
</table> -->
</BODY></HTML>
