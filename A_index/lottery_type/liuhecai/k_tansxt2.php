<?
// if(!defined('PHPYOU')) {
// exit('非法进入');
// }
is_fengpan($db_config);
include (dirname(__FILE__) . "./../../include/private_config.php");
// var_dump($_POST);var_dump($_GET);exit;
$uid = $_SESSION["uid"];
$userinfo['agent_id'] =	$_SESSION["agent_id"];
$username = $_SESSION["username"];
$sum_m = $_POST['gold'];
$type_y = 6;
if ($_GET['class2'] == "") {
    echo "<script>alert('非法进入!');window.location.href='liuhecai.php?action=k_sxt2';</script>";
    exit();
}
$number3 = $_POST['number3'];

// $result=$mysqli->query("select sum(sum_m) as sum_mm from c_odds_7 where kithe='".$Current_Kithe_Num."' and username='".$_SESSION['kauser']."' and class1='生肖连' and class2='".$_GET['class2']."' order by id desc");
// $ka_guanuserkk1=$result->fetch_array();
// $sum_mm=$ka_guanuserkk1[0];

$edu = user_money($username,$sum_m,0);
if($edu==-1){
    echo '<script type="text/javascript">alert("您的账户额度不足进行本次投注，请充值后在进行投注！","投注失败");</script>';
    exit;
}

$drop_sort = $_GET['class2'];

switch ($_GET['class2']) {

    case "二肖连中":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 48;
        $XF = 23;
        $class22='二肖连中';
        break;

    case "三肖连中":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 49;
        $XF = 23;
         $class22='三肖连中';
        break;
    case "四肖连中":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 50;
        $XF = 23;
        $class22='四肖连中';
        break;
    case "五肖连中":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 51;
        $XF = 23;
        $class22='五肖连中';
        break;

    case "二肖连不中":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 52;
        $XF = 23;
        $class22='二肖连不中';
        break;
    case "三肖连不中":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 53;
        $XF = 23;
        $class22='三肖连不中';
        break;
    case "四肖连不中":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 54;
        $XF = 23;
        $class22='四肖连不中';
        break;
}

?>




<link rel="stylesheet" href="./public/css/xp.css">
<link rel="stylesheet" href="./public/css/mem_order_ft.css"
	type="text/css">
<style type="text/css">
<!--
body, td, th {
	font-size: 9pt;
}

.STYLE3 {
	color: #FFFFFF
}

.STYLE4 {
	color: #000
}

.STYLE2 {

}
-->
</style>
</HEAD>
<SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';}
if(window.location.host!=top.location.host){top.location=window.location;}
</SCRIPT>

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

<!--
<table height="13" cellspacing="0" cellpadding="0" border="0" width="180">
        <tbody><tr>
          <td id="left_1" style="text-align:center; font-size:16px; font-weight:bold"><img src="/images/b002.jpg"></td>
        </tr>
      </tbody></table> -->


<body>
	<!-- <TABLE class=Tab_backdrop cellSpacing=0 cellPadding=0 width=100% border=0>
  <tr>
    <td valign="top" class="Left_Place">
                <TABLE class=t_list cellSpacing=1 cellPadding=0 width=100% border=0>
        <tr>
          <td height="28" colspan="3" align="center" bordercolor="#cccccc" bgcolor="#504a16" style="LINE-HEIGHT: 23px"><span class="STYLE3">下注成功</span></td>
          </tr> -->
	<!--  <tr>
          <td height="22" align="center"class=left_acc_out_top style="LINE-HEIGHT: 23px"><span class="STYLE3">内容</span></td>
          <td align="center" class=left_acc_out_top style="LINE-HEIGHT: 23px"><span class="STYLE3">赔率</span></td>
          <td align="center" class=left_acc_out_top style="LINE-HEIGHT: 23px"><span class="STYLE3">下注金额</span></td>
        </tr> -->
	<div class="ord">
		<div class="title">
			<h1>六合彩</h1>
		</div>
		<div class="main">
			<div class="fin_title">
				<p class="fin_acc">成功提交注单！</p>
				<p class="error" style="display: none;"></p>
			</div>
              <div class="leag_more"><?=$class22?></div>
				      <?

        $sum_m = $_POST['gold'] * $_POST['ioradio1'];

        $gold = $_POST['gold'];

        $rate1 = 1;

        $number1 = $_POST['number1'];

        $mu = explode("/", $number1);

        $ioradio1 = 1;
        $t == 3;
        for ($t = 0; $t < count($mu); $t = $t + 1) {

            $muname = explode(",", $mu[$t]);

            switch ($_GET['class2']) {

                case "二肖连中":

                    switch ($muname[0]) {

                        case "鼠":
                            $r1 = 1401;
                            break;
                        case "虎":
                            $r1 = 1402;
                            break;
                        case "龙":
                            $r1 = 1403;
                            break;
                        case "马":
                            $r1 = 1404;
                            break;
                        case "猴":
                            $r1 = 1405;
                            break;
                        case "狗":
                            $r1 = 1406;
                            break;
                        case "牛":
                            $r1 = 1407;
                            break;
                        case "兔":
                            $r1 = 1408;
                            break;
                        case "蛇":
                            $r1 = 1409;
                            break;
                        case "羊":
                            $r1 = 1410;
                            break;
                        case "鸡":
                            $r1 = 1411;
                            break;
                        case "猪":
                            $r1 = 1412;
                            break;
                    }
                    switch ($muname[1]) {

                        case "鼠":
                            $r2 = 1401;
                            break;
                        case "虎":
                            $r2 = 1402;
                            break;
                        case "龙":
                            $r2 = 1403;
                            break;
                        case "马":
                            $r2 = 1404;
                            break;
                        case "猴":
                            $r2 = 1405;
                            break;
                        case "狗":
                            $r2 = 1406;
                            break;
                        case "牛":
                            $r2 = 1407;
                            break;
                        case "兔":
                            $r2 = 1408;
                            break;
                        case "蛇":
                            $r2 = 1409;
                            break;
                        case "羊":
                            $r2 = 1410;
                            break;
                        case "鸡":
                            $r2 = 1411;
                            break;
                        case "猪":
                            $r2 = 1412;
                            break;
                    }
                    $rate1 = ka_bl($r1, "rate");
                    $rate2 = ka_bl($r2, "rate");
                    if ($rate2 < $rate1)
                        $rate1 = $rate2;
                    break;

                case "二肖连不中":

                    switch ($muname[0]) {

                        case "鼠":
                            $r1 = 1437;
                            break;
                        case "虎":
                            $r1 = 1438;
                            break;
                        case "龙":
                            $r1 = 1439;
                            break;
                        case "马":
                            $r1 = 1440;
                            break;
                        case "猴":
                            $r1 = 1441;
                            break;
                        case "狗":
                            $r1 = 1442;
                            break;
                        case "牛":
                            $r1 = 1443;
                            break;
                        case "兔":
                            $r1 = 1444;
                            break;
                        case "蛇":
                            $r1 = 1445;
                            break;
                        case "羊":
                            $r1 = 1446;
                            break;
                        case "鸡":
                            $r1 = 1447;
                            break;
                        case "猪":
                            $r1 = 1448;
                            break;
                    }
                    switch ($muname[1]) {

                        case "鼠":
                            $r2 = 1437;
                            break;
                        case "虎":
                            $r2 = 1438;
                            break;
                        case "龙":
                            $r2 = 1439;
                            break;
                        case "马":
                            $r2 = 1440;
                            break;
                        case "猴":
                            $r2 = 1441;
                            break;
                        case "狗":
                            $r2 = 1442;
                            break;
                        case "牛":
                            $r2 = 1443;
                            break;
                        case "兔":
                            $r2 = 1444;
                            break;
                        case "蛇":
                            $r2 = 1445;
                            break;
                        case "羊":
                            $r2 = 1446;
                            break;
                        case "鸡":
                            $r2 = 1447;
                            break;
                        case "猪":
                            $r2 = 1448;
                            break;
                    }
                    $rate1 = ka_bl($r1, "rate");
                    $rate2 = ka_bl($r2, "rate");
                    if ($rate2 < $rate1)
                        $rate1 = $rate2;
                    break;

                case "三肖连中":

                    switch ($muname[0]) {

                        case "鼠":
                            $r1 = 1413;
                            break;
                        case "虎":
                            $r1 = 1414;
                            break;
                        case "龙":
                            $r1 = 1415;
                            break;
                        case "马":
                            $r1 = 1416;
                            break;
                        case "猴":
                            $r1 = 1417;
                            break;
                        case "狗":
                            $r1 = 1418;
                            break;
                        case "牛":
                            $r1 = 1419;
                            break;
                        case "兔":
                            $r1 = 1420;
                            break;
                        case "蛇":
                            $r1 = 1421;
                            break;
                        case "羊":
                            $r1 = 1422;
                            break;
                        case "鸡":
                            $r1 = 1423;
                            break;
                        case "猪":
                            $r1 = 1424;
                            break;
                    }
                    switch ($muname[1]) {
                        case "鼠":
                            $r2 = 1413;
                            break;
                        case "虎":
                            $r2 = 1414;
                            break;
                        case "龙":
                            $r2 = 1415;
                            break;
                        case "马":
                            $r2 = 1416;
                            break;
                        case "猴":
                            $r2 = 1417;
                            break;
                        case "狗":
                            $r2 = 1418;
                            break;
                        case "牛":
                            $r2 = 1419;
                            break;
                        case "兔":
                            $r2 = 1420;
                            break;
                        case "蛇":
                            $r2 = 1421;
                            break;
                        case "羊":
                            $r2 = 1422;
                            break;
                        case "鸡":
                            $r2 = 1423;
                            break;
                        case "猪":
                            $r2 = 1424;
                            break;
                    }
                    switch ($muname[2]) {
                        case "鼠":
                            $r3 = 1413;
                            break;
                        case "虎":
                            $r3 = 1414;
                            break;
                        case "龙":
                            $r3 = 1415;
                            break;
                        case "马":
                            $r3 = 1416;
                            break;
                        case "猴":
                            $r3 = 1417;
                            break;
                        case "狗":
                            $r3 = 1418;
                            break;
                        case "牛":
                            $r3 = 1419;
                            break;
                        case "兔":
                            $r3 = 1420;
                            break;
                        case "蛇":
                            $r3 = 1421;
                            break;
                        case "羊":
                            $r3 = 1422;
                            break;
                        case "鸡":
                            $r3 = 1423;
                            break;
                        case "猪":
                            $r3 = 1424;
                            break;
                    }
                    $rate1 = ka_bl($r1, "rate");
                    $rate2 = ka_bl($r2, "rate");
                    $rate3 = ka_bl($r3, "rate");
                    if ($rate2 < $rate1)
                        $rate1 = $rate2;
                    if ($rate3 < $rate1)
                        $rate1 = $rate3;
                    break;

                case "三肖连不中":

                    switch ($muname[0]) {

                        case "鼠":
                            $r1 = 1449;
                            break;
                        case "虎":
                            $r1 = 1450;
                            break;
                        case "龙":
                            $r1 = 1451;
                            break;
                        case "马":
                            $r1 = 1452;
                            break;
                        case "猴":
                            $r1 = 1453;
                            break;
                        case "狗":
                            $r1 = 1454;
                            break;
                        case "牛":
                            $r1 = 1455;
                            break;
                        case "兔":
                            $r1 = 1456;
                            break;
                        case "蛇":
                            $r1 = 1457;
                            break;
                        case "羊":
                            $r1 = 1458;
                            break;
                        case "鸡":
                            $r1 = 1459;
                            break;
                        case "猪":
                            $r1 = 1460;
                            break;
                    }
                    switch ($muname[1]) {
                        case "鼠":
                            $r2 = 1449;
                            break;
                        case "虎":
                            $r2 = 1450;
                            break;
                        case "龙":
                            $r2 = 1451;
                            break;
                        case "马":
                            $r2 = 1452;
                            break;
                        case "猴":
                            $r2 = 1453;
                            break;
                        case "狗":
                            $r2 = 1454;
                            break;
                        case "牛":
                            $r2 = 1455;
                            break;
                        case "兔":
                            $r2 = 1456;
                            break;
                        case "蛇":
                            $r2 = 1457;
                            break;
                        case "羊":
                            $r2 = 1458;
                            break;
                        case "鸡":
                            $r2 = 1459;
                            break;
                        case "猪":
                            $r2 = 1460;
                            break;
                    }
                    switch ($muname[2]) {
                        case "鼠":
                            $r3 = 1449;
                            break;
                        case "虎":
                            $r3 = 1450;
                            break;
                        case "龙":
                            $r3 = 1451;
                            break;
                        case "马":
                            $r3 = 1452;
                            break;
                        case "猴":
                            $r3 = 1453;
                            break;
                        case "狗":
                            $r3 = 1454;
                            break;
                        case "牛":
                            $r3 = 1455;
                            break;
                        case "兔":
                            $r3 = 1456;
                            break;
                        case "蛇":
                            $r3 = 1457;
                            break;
                        case "羊":
                            $r3 = 1458;
                            break;
                        case "鸡":
                            $r3 = 1459;
                            break;
                        case "猪":
                            $r3 = 1460;
                            break;
                    }
                    $rate1 = ka_bl($r1, "rate");
                    $rate2 = ka_bl($r2, "rate");
                    $rate3 = ka_bl($r3, "rate");
                    if ($rate2 < $rate1)
                        $rate1 = $rate2;
                    if ($rate3 < $rate1)
                        $rate1 = $rate3;
                    break;

                case "四肖连中":

                    switch ($muname[0]) {

                        case "鼠":
                            $r1 = 1425;
                            break;
                        case "虎":
                            $r1 = 1426;
                            break;
                        case "龙":
                            $r1 = 1427;
                            break;
                        case "马":
                            $r1 = 1428;
                            break;
                        case "猴":
                            $r1 = 1429;
                            break;
                        case "狗":
                            $r1 = 1430;
                            break;
                        case "牛":
                            $r1 = 1431;
                            break;
                        case "兔":
                            $r1 = 1432;
                            break;
                        case "蛇":
                            $r1 = 1433;
                            break;
                        case "羊":
                            $r1 = 1434;
                            break;
                        case "鸡":
                            $r1 = 1435;
                            break;
                        case "猪":
                            $r1 = 1436;
                            break;
                    }
                    switch ($muname[1]) {
                        case "鼠":
                            $r2 = 1425;
                            break;
                        case "虎":
                            $r2 = 1426;
                            break;
                        case "龙":
                            $r2 = 1427;
                            break;
                        case "马":
                            $r2 = 1428;
                            break;
                        case "猴":
                            $r2 = 1429;
                            break;
                        case "狗":
                            $r2 = 1430;
                            break;
                        case "牛":
                            $r2 = 1431;
                            break;
                        case "兔":
                            $r2 = 1432;
                            break;
                        case "蛇":
                            $r2 = 1433;
                            break;
                        case "羊":
                            $r2 = 1434;
                            break;
                        case "鸡":
                            $r2 = 1435;
                            break;
                        case "猪":
                            $r2 = 1436;
                            break;
                    }
                    switch ($muname[2]) {
                        case "鼠":
                            $r3 = 1425;
                            break;
                        case "虎":
                            $r3 = 1426;
                            break;
                        case "龙":
                            $r3 = 1427;
                            break;
                        case "马":
                            $r3 = 1428;
                            break;
                        case "猴":
                            $r3 = 1429;
                            break;
                        case "狗":
                            $r3 = 1430;
                            break;
                        case "牛":
                            $r3 = 1431;
                            break;
                        case "兔":
                            $r3 = 1432;
                            break;
                        case "蛇":
                            $r3 = 1433;
                            break;
                        case "羊":
                            $r3 = 1434;
                            break;
                        case "鸡":
                            $r3 = 1435;
                            break;
                        case "猪":
                            $r3 = 1436;
                            break;
                    }
                    switch ($muname[3]) {
                        case "鼠":
                            $r4 = 1425;
                            break;
                        case "虎":
                            $r4 = 1426;
                            break;
                        case "龙":
                            $r4 = 1427;
                            break;
                        case "马":
                            $r4 = 1428;
                            break;
                        case "猴":
                            $r4 = 1429;
                            break;
                        case "狗":
                            $r4 = 1430;
                            break;
                        case "牛":
                            $r4 = 1431;
                            break;
                        case "兔":
                            $r4 = 1432;
                            break;
                        case "蛇":
                            $r4 = 1433;
                            break;
                        case "羊":
                            $r4 = 1434;
                            break;
                        case "鸡":
                            $r4 = 1435;
                            break;
                        case "猪":
                            $r4 = 1436;
                            break;
                    }

                    $rate1 = ka_bl($r1, "rate");
                    $rate2 = ka_bl($r2, "rate");
                    $rate3 = ka_bl($r3, "rate");
                    $rate4 = ka_bl($r4, "rate");
                    if ($rate2 < $rate1)
                        $rate1 = $rate2;
                    if ($rate3 < $rate1)
                        $rate1 = $rate3;
                    if ($rate4 < $rate1)
                        $rate1 = $rate4;
                    break;

                case "四肖连不中":

                    switch ($muname[0]) {

                        case "鼠":
                            $r1 = 1461;
                            break;
                        case "虎":
                            $r1 = 1462;
                            break;
                        case "龙":
                            $r1 = 1463;
                            break;
                        case "马":
                            $r1 = 1464;
                            break;
                        case "猴":
                            $r1 = 1465;
                            break;
                        case "狗":
                            $r1 = 1466;
                            break;
                        case "牛":
                            $r1 = 1467;
                            break;
                        case "兔":
                            $r1 = 1468;
                            break;
                        case "蛇":
                            $r1 = 1469;
                            break;
                        case "羊":
                            $r1 = 1470;
                            break;
                        case "鸡":
                            $r1 = 1471;
                            break;
                        case "猪":
                            $r1 = 1472;
                            break;
                    }
                    switch ($muname[1]) {
                        case "鼠":
                            $r2 = 1461;
                            break;
                        case "虎":
                            $r2 = 1462;
                            break;
                        case "龙":
                            $r2 = 1463;
                            break;
                        case "马":
                            $r2 = 1464;
                            break;
                        case "猴":
                            $r2 = 1465;
                            break;
                        case "狗":
                            $r2 = 1466;
                            break;
                        case "牛":
                            $r2 = 1467;
                            break;
                        case "兔":
                            $r2 = 1468;
                            break;
                        case "蛇":
                            $r2 = 1469;
                            break;
                        case "羊":
                            $r2 = 1470;
                            break;
                        case "鸡":
                            $r2 = 1471;
                            break;
                        case "猪":
                            $r2 = 1472;
                            break;
                    }
                    switch ($muname[2]) {
                        case "鼠":
                            $r3 = 1461;
                            break;
                        case "虎":
                            $r3 = 1462;
                            break;
                        case "龙":
                            $r3 = 1463;
                            break;
                        case "马":
                            $r3 = 1464;
                            break;
                        case "猴":
                            $r3 = 1465;
                            break;
                        case "狗":
                            $r3 = 1466;
                            break;
                        case "牛":
                            $r3 = 1467;
                            break;
                        case "兔":
                            $r3 = 1468;
                            break;
                        case "蛇":
                            $r3 = 1469;
                            break;
                        case "羊":
                            $r3 = 1470;
                            break;
                        case "鸡":
                            $r3 = 1471;
                            break;
                        case "猪":
                            $r3 = 1472;
                            break;
                    }
                    switch ($muname[3]) {
                        case "鼠":
                            $r4 = 1461;
                            break;
                        case "虎":
                            $r4 = 1462;
                            break;
                        case "龙":
                            $r4 = 1463;
                            break;
                        case "马":
                            $r4 = 1464;
                            break;
                        case "猴":
                            $r4 = 1465;
                            break;
                        case "狗":
                            $r4 = 1466;
                            break;
                        case "牛":
                            $r4 = 1467;
                            break;
                        case "兔":
                            $r4 = 1468;
                            break;
                        case "蛇":
                            $r4 = 1469;
                            break;
                        case "羊":
                            $r4 = 1470;
                            break;
                        case "鸡":
                            $r4 = 1471;
                            break;
                        case "猪":
                            $r4 = 1472;
                            break;
                    }

                    $rate1 = ka_bl($r1, "rate");
                    $rate2 = ka_bl($r2, "rate");
                    $rate3 = ka_bl($r3, "rate");
                    $rate4 = ka_bl($r4, "rate");
                    if ($rate2 < $rate1)
                        $rate1 = $rate2;
                    if ($rate3 < $rate1)
                        $rate1 = $rate3;
                    if ($rate4 < $rate1)
                        $rate1 = $rate4;
                    break;

                case "五肖连中":

                    switch ($muname[0]) {

                        case "鼠":
                            $r1 = 1473;
                            break;
                        case "虎":
                            $r1 = 1474;
                            break;
                        case "龙":
                            $r1 = 1475;
                            break;
                        case "马":
                            $r1 = 1476;
                            break;
                        case "猴":
                            $r1 = 1477;
                            break;
                        case "狗":
                            $r1 = 1478;
                            break;
                        case "牛":
                            $r1 = 1479;
                            break;
                        case "兔":
                            $r1 = 1480;
                            break;
                        case "蛇":
                            $r1 = 1481;
                            break;
                        case "羊":
                            $r1 = 1482;
                            break;
                        case "鸡":
                            $r1 = 1483;
                            break;
                        case "猪":
                            $r1 = 1484;
                            break;
                    }
                    switch ($muname[1]) {
                        case "鼠":
                            $r2 = 1473;
                            break;
                        case "虎":
                            $r2 = 1474;
                            break;
                        case "龙":
                            $r2 = 1475;
                            break;
                        case "马":
                            $r2 = 1476;
                            break;
                        case "猴":
                            $r2 = 1477;
                            break;
                        case "狗":
                            $r2 = 1478;
                            break;
                        case "牛":
                            $r2 = 1479;
                            break;
                        case "兔":
                            $r2 = 1480;
                            break;
                        case "蛇":
                            $r2 = 1481;
                            break;
                        case "羊":
                            $r2 = 1482;
                            break;
                        case "鸡":
                            $r2 = 1483;
                            break;
                        case "猪":
                            $r2 = 1484;
                            break;
                    }
                    switch ($muname[2]) {
                        case "鼠":
                            $r3 = 1473;
                            break;
                        case "虎":
                            $r3 = 1474;
                            break;
                        case "龙":
                            $r3 = 1475;
                            break;
                        case "马":
                            $r3 = 1476;
                            break;
                        case "猴":
                            $r3 = 1477;
                            break;
                        case "狗":
                            $r3 = 1478;
                            break;
                        case "牛":
                            $r3 = 1479;
                            break;
                        case "兔":
                            $r3 = 1480;
                            break;
                        case "蛇":
                            $r3 = 1481;
                            break;
                        case "羊":
                            $r3 = 1482;
                            break;
                        case "鸡":
                            $r3 = 1483;
                            break;
                        case "猪":
                            $r3 = 1484;
                            break;
                    }
                    switch ($muname[3]) {
                        case "鼠":
                            $r4 = 1473;
                            break;
                        case "虎":
                            $r4 = 1474;
                            break;
                        case "龙":
                            $r4 = 1475;
                            break;
                        case "马":
                            $r4 = 1476;
                            break;
                        case "猴":
                            $r4 = 1477;
                            break;
                        case "狗":
                            $r4 = 1478;
                            break;
                        case "牛":
                            $r4 = 1479;
                            break;
                        case "兔":
                            $r4 = 1480;
                            break;
                        case "蛇":
                            $r4 = 1481;
                            break;
                        case "羊":
                            $r4 = 1482;
                            break;
                        case "鸡":
                            $r4 = 1483;
                            break;
                        case "猪":
                            $r4 = 1484;
                            break;
                    }

                    switch ($muname[4]) {
                        case "鼠":
                            $r5 = 1473;
                            break;
                        case "虎":
                            $r5 = 1474;
                            break;
                        case "龙":
                            $r5 = 1475;
                            break;
                        case "马":
                            $r5 = 1476;
                            break;
                        case "猴":
                            $r5 = 1477;
                            break;
                        case "狗":
                            $r5 = 1478;
                            break;
                        case "牛":
                            $r5 = 1479;
                            break;
                        case "兔":
                            $r5 = 1480;
                            break;
                        case "蛇":
                            $r5 = 1481;
                            break;
                        case "羊":
                            $r5 = 1482;
                            break;
                        case "鸡":
                            $r5 = 1483;
                            break;
                        case "猪":
                            $r5 = 1484;
                            break;
                    }

                    $rate1 = ka_bl($r1, "rate");
                    $rate2 = ka_bl($r2, "rate");
                    $rate3 = ka_bl($r3, "rate");
                    $rate4 = ka_bl($r4, "rate");
                    $rate5 = ka_bl($r5, "rate");

                    if ($rate2 < $rate1)
                        $rate1 = $rate2;
                    if ($rate3 < $rate1)
                        $rate1 = $rate3;
                    if ($rate4 < $rate1)
                        $rate1 = $rate4;
                    if ($rate5 < $rate1)
                        $rate1 = $rate5;
                    break;
            }

            switch (ka_memuser("abcd")) {

                case "A":
                    $rate5 = $rate1;
                    $Y = 1;
                    break;
                case "B":
                    $rate5 = $rate1 - $bmmm;
                    $Y = 4;
                    break;
                case "C":
                    $Y = 5;
                    $rate5 = $rate1 - $cmmm;
                    break;
                case "D":
                    $rate5 = $rate1 - $dmmm;
                    $Y = 6;
                    break;
                default:
                    $Y = 1;
                    $rate5 = $rate1;
                    break;
            }

            $num = date("YmdHis") . mt_rand("100000", "999999");
            $text = date("Y-m-d H:i:s");
            $class11 = ka_bl($rate_id, "class1");
            $class22 = ka_bl($rate_id, "class2");
            $class33 = $mu[$t];
            // $gold=$gold;
            $user_ds = ka_memds($R, 1);

            $q1 = $q2 = 0;
            $mysqlt->autocommit(FALSE);
            $mysqlt->query("BEGIN"); // 事务开始
            try {
                $sql = "update k_user set money=money-$gold where uid=" . $_SESSION['uid'] . " and money>=$sum_m"; // 扣钱
                $mysqlt->query($sql);
                $q1 = $mysqlt->affected_rows;

                if ($q1 == 1) {

                    $win = $rate5 * $gold;

                    $u_money = Get_user_One($_SESSION['username'], 'money');
                    $s_money = $u_money;
                    $cpfsbl = $sum_m * 0.1;
                    $type = "六合彩";
                    $uid = Get_user_One($_SESSION['username'], 'uid');
                    $sql1 = "INSERT INTO c_bet set did='" . $num . "',uid='" . $uid . "',agent_id='" . $userinfo['agent_id'] . "',username='" . $_SESSION['username'] . "',addtime='" . $text . "',type='" . $type . "',qishu='" . $Current_Kithe_Num . "',mingxi_1='" . $class11 . "',mingxi_2='" . $class33 . "',mingxi_3='" . $class22 . "',odds='" . $rate5 . "',money='" . $gold . "',win='" . $win . "',assets='" . $u_money . "',balance='" . $s_money . "',fs='0',site_id='" . SITEID . "'";
                    $mysqlt->query($sql1) or die("数据库修改出错");

                    $q2 = $mysqlt->affected_rows;
                   // $source_id .= $mysqlt->insert_id . ",";
                    $money_all_h = $sum_m;
                    //$source_id = rtrim($source_id, ',');
                    $source_id = $mysqlt->insert_id;
                    $remark="彩票注单：" . $num." , 類型:" .$type;
                    $sql2 = "insert into k_user_cash_record(source_id,site_id,uid,username,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark)
         values ('" . $source_id . "','" . SITEID . "','" . $uid . "','".$_SESSION['username']."','3','2','" . $gold . "',
        '" . $s_money . "','" . date("Y-m-d H:i:s", time()) . "','". $remark."')";
                    $q3 = $mysqlt->query($sql2) or die("数据库修改出错");
                }

                if ($q1 == 1 && $q2 == 1 && $q3 == 1) {
                    $mysqlt->commit(); // 事务提交
                } else {
                    $mysqlt->rollback(); // 数据回滚
                }
            } catch (Exception $e) {
                $mysqlt->rollback(); // 数据回滚
            }

            // include 'ds.php';
            ?>

			          <script type="text/javascript">
    parent.parent.k_memr.location.reload();
 </script>


			<p class="fin_team margin_top">
				<em><?=$class33?></em> @ <strong><?=$rate5?></strong>
			</p>
			<p class="fin_amount">
				交易金额：<span class="fin_gold"><?=$gold?></span>

			</p>
			 <?
        }
        ?>
 <?
// $sql="INSERT INTO ka_tong set num='".$num."',username='".$_SESSION['username']."',kithe='".$Current_Kithe_Num."',adddate='".$text."',class1='".$class11."',class2='".$class22."',class3='".$number3."',rate='".$rate5."',sum_m='".$gold."',user_ds='".$user_ds."',dai_ds='".$dai_ds."',zong_ds='".$zong_ds."',guan_ds='".$guan_ds."',dai_zc='".$dai_zc."',zong_zc='".$zong_zc."',guan_zc='".$guan_zc."',dagu_zc='".$dagu_zc."',bm=0,dai='".$dai."',zong='".$zong."',guan='".$guan."',danid='".$danid."',zongid='".$zongid."',guanid='".$guanid."',abcd='".$abcd."',lx=0";
// $exe=$mysqli->query($sql) or die("数据库修改出错");
?>
</div>
		<div class="betBox">
			<input type="button" name="PRINT" value="确定"
				onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="print"> <input
				type="button" name="FINISH" value="关闭"
				onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="close">
		</div>

	</div>

</BODY>
</HTML>
