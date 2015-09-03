<?

is_fengpan($db_config);
include(dirname(__FILE__)."./../../include/private_config.php");
// include (".././include/order_info.php");
// var_dump($_POST);var_dump($_GET);exit;
$uid = $_SESSION["uid"];
$userinfo['agent_id'] =	$_SESSION["agent_id"];
$type_y = 6;

?>
<link rel="stylesheet" href="./public/css/mem_order_ft.css"
	type="text/css">
<link rel="stylesheet" href="./public/css/xp.css">
<style type="text/css">
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
</style>
</HEAD>

<SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';}
if(window.location.host!=top.location.host){top.location=window.location;}
</SCRIPT>

<!-- <SCRIPT language=javascript>
window.setTimeout("<?=$urlurl; ?>", 30000);
</SCRIPT> -->
<SCRIPT language=JAVASCRIPT>
if(self == top){location = '/';}

function ChkSubmit(){
    //设定『确定』键为反白


		document.all.btnSubmit.disabled = true;

document.form1.submit();
	}
</SCRIPT>
<!--

<table height="13" cellspacing="0" cellpadding="0" border="0" width="180">
        <tbody><tr>
          <td id="left_1" style="text-align:center; font-size:16px; font-weight:bold"><img src="/images/b002.jpg"></td>
        </tr>
      </tbody></table>



<TABLE class=Tab_backdrop cellSpacing=0 cellPadding=0 width=180 border=0>
  <tr>
    <td valign="top" class="Left_Place">
                <TABLE class=t_list cellSpacing=1 cellPadding=0 width=180 border=0> -->
<?

$sum_m = $_POST['gold'];

$gold = $_POST['gold'];

if ($sum_m > ka_memuser("ts")) {

}

if ($sum_m <= 0) {
    // echo "<script Language=Javascript>alert('对不起，下注总额错误');parent.parent.leftFrame.location.href='".$urlurl."';window.location.href='liuhecai.php?action=left';</script>";
    // exit;
}

$z = 0;
$rate2 = 1;
$class11 = "过关";
for ($t = 1; $t <= 18; $t = $t + 1) {
    if ($_POST['rate_id' . $t] != "") {

        $rate_id = $_POST['rate_id' . $t];
        $rate2 = $rate2 * ka_bl($rate_id, "rate");
        $class22 .= ka_bl($rate_id, "class2") . ",";
        //$class33 .= ka_bl($rate_id, "class3") . "," . ka_bl($rate_id, "rate") . ",";
        $class33 .= ka_bl($rate_id, "class3") . ",";
    }
}
$rate5 = floor($rate2 * 100) / 100;

switch (ka_memuser("abcd")) {

    case "A":

        $Y = 1;
        break;
    case "B":

        $Y = 4;
        break;
    case "C":
        $Y = 5;

        break;
    case "D":

        $Y = 6;
        break;
    default:
        $Y = 1;

        break;
}

// 超过单项

// $result2 = $mysqli->query("Select SUM(sum_m) As sum_m from c_odds_7 where qishu='" . $Current_Kithe_Num . "' and  class1='" . $class11 . "' and  class2='" . $class22 . "' and class3='" . $class33 . "' and username='" . $_SESSION['username'] . "' ");
// $rs5 = $result2->fetch_array();

if (($rs5['sum_m'] + $sum_m) > ka_memds($R, 3)) {
    // echo "<script Language=Javascript>alert('对不起，超过单项限额.请反回重新下注!');parent.parent.leftFrame.location.href='".$urlurl."';window.location.href='liuhecai.php?action=left';</script>";
    // exit;
}

if ($sum_m <= 0) {
    // echo "<script Language=Javascript>alert('对不起，下注总额错误');parent.parent.leftFrame.location.href='".$urlurl."';window.location.href='liuhecai.php?action=left';</script>";
    // exit;
}
$username = $_SESSION["username"];
//清空所有POST数据为空的表单
//$datas = array_filter($_POST);
//获取清空后的POST键名
//$names = array_keys($datas);

//判断会员账户额度是否大于总投注额度
// for ($i = 0; $i < count($datas)-1; $i++){
//     $qiu	= explode("_",$names[$i]);
//     if($qiu[0]!='ball'){continue;}
//     $allmoney += $datas[''.$names[$i].''];
// }

$edu = user_money($username,$sum_m,0);
if($edu==-1){
    echo '<script type="text/javascript">alert("您的账户额度不足进行本次投注，请充值后在进行投注！","投注失败");</script>';
    exit;
}

$num=date("YmdHis").mt_rand("100000","999999");
$text = date("Y-m-d H:i:s");
// $class11="过关";
// $class22=ka_bl($rate_id,"class2");
// $class33=$number1;
$sum_m = $sum_m;
$user_ds = ka_memds($R, 1);

$q1 = $q2 = 0;
$mysqlt->autocommit(FALSE);
$mysqlt->query("BEGIN"); // 事务开始
try {

    $sql = "update k_user set money=money-$sum_m where uid=" . $_SESSION['uid'] . " and money>=$sum_m"; // 扣钱
    $mysqlt->query($sql);
    $q1 = $mysqlt->affected_rows;

    if ($q1 == 1) {
        $win = $rate5 * $sum_m;
        $u_money = Get_user_One($_SESSION['username'],'money');
        $s_money = $u_money;
        $cpfsbl = $sum_m * 0.1;
        $type = "六合彩";
        $uid = Get_user_One($_SESSION['username'],'uid');
	    $sql1 = "INSERT INTO c_bet set did='" . $num . "',uid='" . $uid .
	    "',agent_id='" . $userinfo['agent_id'] . "',username='" . $_SESSION['username'] . "',addtime='" . $text .
	    "',type='" . $type . "',qishu='" . $Current_Kithe_Num . "',mingxi_1='" . $class11 .
	    "',mingxi_2='" . $class33 . "',mingxi_3='" . $class22 . "',odds='" . $rate5 . "',money='" . $sum_m .
	    "',win='" . $win . "',assets='" . $u_money . "',balance='" . $s_money .
	    "',fs='0',site_id='" . SITEID . "'";
        $mysqlt->query($sql1) or die("数据库修改出错");
        $q2 = $mysqlt->affected_rows;
        //写现金流表 cash_type=3：彩票下注 cash_do_type=2：取出
//      		$source_id .= $mysqlt->insert_id.",";
//         $source_id = rtrim($source_id, ',');
        $source_id = $mysqlt->insert_id;
        $all_money=$sum_m;//交易总金额
        $remark="彩票注单：" . $num." , 類型:" .$type;
        $sql2="insert into k_user_cash_record(source_id,site_id,uid,username,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark)
         values ('".$source_id."','".SITEID."','".$uid."','".$_SESSION['username']."','3','2','".$all_money."',
        '".$s_money."','".date("Y-m-d H:i:s",time())."','". $remark."')";
        $q3 = $mysqlt->query($sql2);
    }

    if ($q1 == 1 && $q2 == 1 &&$q3==1) {
        $mysqlt->commit(); // 事务提交
    } else {
        $mysqlt->rollback(); // 数据回滚
    }
} catch (Exception $e) {
    $mysqlt->rollback(); // 数据回滚
}

?>
    <script type="text/javascript">
    parent.parent.k_memr.location.reload();
 </script>
<div class="ord">
	<div class="title">
		<h1>六合彩</h1>
	</div>
	<div class="main">
		<div class="fin_title">
			<p class="fin_acc">成功提交注单！</p>

			<p class="error" style="display: none;"></p>
		</div>




		</font></font>
	</div>

	<p class="fin_team">




			  <?
    $jj = 0;
    for ($t = 1; $t <= 18; $t = $t + 1) {
        if ($_POST['rate_id' . $t] != "") {
            $rate_id = $_POST['rate_id' . $t];
            $jj ++;
            ?>
<font color="#0000ff">
        <?=ka_bl($rate_id,"class2")?>：<?=ka_bl($rate_id,"class3")?></font>
		@ <font color="red"><?=ka_bl($rate_id,"rate")?></font><br>

              <?

}
    }
    ?>


               ===============<br> <font color="#0000fff"><?=$jj ?>串一</font>
		@ <font color="red"><?=$rate5?><font></font></font>
	</p>
	<font color="red"><font>
			<p class="fin_amount">
				交易金额：<span class="fin_gold"><?=$sum_m?></span>
			</p>

	</font></font>
</div>
<font color="red"><font>

		<div class="betBox">
			<input type="button" name="PRINT" value="确定"
				onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="print"> <input
				type="button" name="FINISH" value="关闭"
				onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="close">
		</div>

		</BODY>
		</HTML>