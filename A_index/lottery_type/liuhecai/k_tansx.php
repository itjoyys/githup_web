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
    echo "<script>alert('非法进入!');parent.parent.leftFrame.location.href='liuhecai.php?action=k_tm';window.location.href='liuhecai.php?action=k_sx6';</script>";
    exit();
}

$edu = user_money($username,$sum_m,0);
if($edu==-1){
    echo '<script type="text/javascript">alert("您的账户额度不足进行本次投注，请充值后在进行投注！","投注失败");</script>';
    exit;
}

// $result=$mysqli->query("select sum(sum_m) as sum_mm from c_odds_7 where qishu='".$Current_Kithe_Num."' and username='".$_SESSION['username']."' and mingxi_1='生肖' and mingxi_3='".$_GET['class2']."' order by id desc");
// $ka_guanuserkk1=$result->fetch_array();
// $sum_mm=$ka_guanuserkk1[0];

$drop_sort = $_GET['class2'];

switch ($_GET['class2']) {
    case "二肖":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 19;
        $XF = 23;
        $rate_id = 901;
        $urlurl = "liuhecai.php?action=k_sx6&ids=二肖";

        break;

    case "三肖":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 20;
        $XF = 23;
        $rate_id = 913;
        $urlurl = "liuhecai.php?action=k_sx6&ids=三肖";

        break;

    case "四肖":
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;
        $R = 21;
        $XF = 23;
        $rate_id = 925;
        $urlurl = "liuhecai.php?action=k_sx6&ids=四肖";

        break;
    case "五肖":
        $urlurl = "liuhecai.php?action=k_sx6&ids=五肖";
        $bmmm = 0;
        $cmmm = 0;
        $dmmm = 0;

        $R = 23;
        $XF = 23;
        $rate_id = 937;

        break;

    case "六肖":
        $R = 26;
        $bmmm = $bsx6;
        $cmmm = $csx6;
        $dmmm = $dsx6;
        $XF = 23;
        $rate_id = 949;
        $urlurl = "liuhecai.php?action=k_sx6&ids=六肖";
        break;

    case "七肖":
        $R = 27;
        $bmmm = $bsx6;
        $cmmm = $csx6;
        $dmmm = $dsx6;
        $XF = 23;
        $rate_id = 961;
        $urlurl = "liuhecai.php?action=k_sx6&ids=七肖";
        break;

    case "八肖":
        $R = 28;
        $bmmm = $bsx6;
        $cmmm = $csx6;
        $dmmm = $dsx6;
        $XF = 23;
        $rate_id = 973;
        $urlurl = "liuhecai.php?action=k_sx6&ids=八肖";
        break;

    case "九肖":
        $R = 29;
        $bmmm = $bsx6;
        $cmmm = $csx6;
        $dmmm = $dsx6;
        $XF = 23;
        $rate_id = 985;
        $urlurl = "liuhecai.php?action=k_sx6&ids=九肖";
        break;

    case "十肖":
        $R = 31;
        $bmmm = $bsx6;
        $cmmm = $csx6;
        $dmmm = $dsx6;
        $XF = 23;
        $rate_id = 997;
        $urlurl = "liuhecai.php?action=k_sx6&ids=十肖";
        break;

    case "十一肖":
        $R = 31;
        $bmmm = $bsx6;
        $cmmm = $csx6;
        $dmmm = $dsx6;
        $XF = 23;
        $rate_id = 1009;
        $urlurl = "liuhecai.php?action=k_sx6&ids=十一肖";
        break;
}

?>


<link rel="stylesheet" href="./public/css/mem_order_ft.css"
	type="text/css">
<link rel="stylesheet" href="./public/css/xp.css">
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


<SCRIPT language=JAVASCRIPT>
if(self == top){location = '/';}

function ChkSubmit(){
    //设定『确定』键为反白


		document.all.btnSubmit.disabled = true;

document.form1.submit();
	}
</SCRIPT>

<body>
	<!-- <table height="13" cellspacing="0" cellpadding="0" border="0" width="180">
        <tbody><tr>
          <td id="left_1" style="text-align:center; font-size:16px; font-weight:bold"><img src="/images/b002.jpg"></td>
        </tr>
      </tbody></table> -->



	<TABLE class="Tab_backdrop" cellSpacing=0 cellPadding=0 width=180
		border=0>
		<tr>
			<td valign="top" class="Left_Place">
				<TABLE class=t_list cellSpacing=1 cellPadding=0 width=180 border=0>
                 <?

                $sum_m = $_POST['gold'];

                $gold = $_POST['gold'];

                $rate1 = 1;

                $number1 = $_POST['number1'];

                $pieces = explode(",", $number1);
                for ($i = 0; $i < count($pieces); $i ++) {
                    if ($pieces[$i]) {
                        // get mdrop
                        $result = $mysqli->query("Select rate from mdrop where class1='生肖' and class2='" . $_GET['class2'] . "' and class3='" . $pieces[$i] . "'");

                        $mdrop = $result['rate'];
                        $aa .= $image['rate'] . ",";
                    }
                }
                $array = explode(",", $aa);
                $rate555 = $array[0];

                for ($i = 1; $i < count($pieces); $i ++) {
                    if ($rate555 < $array[$i]) {
                        $rate555 = $array[$i];
                    }
                }
                switch (ka_memuser("abcd")) {

                    case "A":
                        $rate5 = ka_bl($rate_id, "rate") - $mdrop;
                        $Y = 1;
                        break;
                    case "B":
                        $rate5 = ka_bl($rate_id, "rate") - $mdrop - $bmmm;
                        $Y = 4;
                        break;
                    case "C":
                        $Y = 5;
                        $rate5 = ka_bl($rate_id, "rate") - $mdrop - $cmmm;
                        break;
                    case "D":
                        $rate5 = ka_bl($rate_id, "rate") - $mdrop - $dmmm;
                        $Y = 6;
                        break;
                    default:
                        $Y = 1;
                        $rate5 = ka_bl($rate_id, "rate") - $mdrop;
                        break;
                }

                $rate5 = $_POST['min_bl'];

                $num = date("YmdHis") . mt_rand("100000", "999999");
                $text = date("Y-m-d H:i:s");
                $class11 = ka_bl($rate_id, "class1");
                $class22 = ka_bl($rate_id, "class2");
                $class33 = $number1;
                $sum_m = $sum_m;
                $user_ds = ka_memds($R, 1);

                $q1 = $q2 = 0;
                $mysqlt->autocommit(FALSE);
                $mysqlt->query("BEGIN"); // 事务开始
                try {
                    $sql = "update k_user set money=money-$sum_m where uid=" . $_SESSION['uid'] . " and money>=$sum_m"; // 扣钱
                    $mysqlt->query($sql);
                    $q1 = $mysqli->affected_rows;

                    if ($q1 == 1) {
                        $win = $rate5 * $sum_m;
                        $u_money = Get_user_One($_SESSION['username'], 'money');
                        $s_money = $u_money;
                        $cpfsbl = $sum_m * 0.1;
                        $type = "六合彩";
                        $uid = Get_user_One($_SESSION['username'], 'uid');
                        $sql1 = "INSERT INTO c_bet set did='" . $num . "',uid='" . $uid . "',agent_id='" . $userinfo['agent_id'] . "',username='" . $_SESSION['username'] . "',addtime='" . $text . "',type='" . $type . "',qishu='" . $Current_Kithe_Num . "',mingxi_1='" . $class11 . "',mingxi_2='" . $class33 . "',mingxi_3='" . $class22 . "',odds='" . $rate5 . "',money='" . $sum_m . "',win='" . $win . "',assets='" . $u_money . "',balance='" . $s_money . "',fs='0',site_id='" . SITEID . "'";
                        $mysqlt->query($sql1);
                        // $sql="INSERT INTO c_odds_7 set num='".$num."',username='".$_SESSION['username']."',kithe='".$Current_Kithe_Num."',adddate='".$text."',class1='".$class11."',class2='".$class22."',class3='".$class33."',rate='".$rate5."',sum_m='".$sum_m."',user_ds='".$user_ds."',dai_ds='".$dai_ds."',zong_ds='".$zong_ds."',guan_ds='".$guan_ds."',dai_zc='".$dai_zc."',zong_zc='".$zong_zc."',guan_zc='".$guan_zc."',dagu_zc='".$dagu_zc."',bm=0,dai='".$dai."',zong='".$zong."',guan='".$guan."',danid='".$danid."',zongid='".$zongid."',guanid='".$guanid."',abcd='A',lx=0";
                        // $mysqlt->query($sql) or die("数据库修改出错");
                        $q2 = $mysqlt->affected_rows;
                        // $source_id .= $mysqlt->insert_id.",";
                        // $source_id = rtrim($source_id, ',');
                        $source_id = $mysqlt->insert_id;
                        $all_money = $sum_m; // 交易总金额
                        $remark="彩票注单：" . $num." , 類型:" .$type;
                        $sql2 = "insert into k_user_cash_record(source_id,site_id,uid,username,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark)
         values ('" . $source_id . "','" . SITEID . "','" . $uid . "','".$_SESSION['username']."','3','2','" . $all_money . "',
        '" . $s_money . "','" . date("Y-m-d H:i:s", time()) . "','". $remark."')";
                        $q3 = $mysqlt->query($sql2);
                    }

                    if ($q1 == 1 && $q2 == 1 && $q3 == 1) {
                        $mysqlt->commit(); // 事务提交
                    } else {
                        $mysqlt->rollback(); // 数据回滚
                    }
                } catch (Exception $e) {
                    $mysqlt->rollback(); // 数据回滚
                }

                include 'ds.php';
                if ($q1 == 1 && $q2 == 1 && $q3 == 1) {
                    ?>
    <script type="text/javascript">
    parent.parent.k_memr.location.reload();
 </script>
	<div class="ord">
						<div class="title">
							<h1>合肖： <?=$class22 ?></h1>
						</div>
						<div class="main">
							<div class="fin_title">
								<p class="fin_acc">成功提交注单！</p>

								<p class="error" style="display: none;"></p>
							</div>
							<p class="fin_team"> <?php echo trim($_POST['number1'],",") ?>

         @<strong><?=$rate5 ?></strong>
							</p>
							<p class="fin_amount">
								交易金额：<span class="fin_gold"><?=$sum_m ?></span>
							</p>
						</div>
						<div class="betBox">
							<input type="button" name="PRINT" value="确定"
								onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="print"> <input
								type="button" name="FINISH" value="关闭"
								onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="close">
						</div>

					</div>
  <?php
                }
                ?>
    </table>
			</td>
		</tr>
	</table>
</BODY>
</HTML>
