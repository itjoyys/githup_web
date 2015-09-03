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
$XF = 21;

$edu = user_money($username,$sum_m,0);
if($edu==-1){
    echo '<script type="text/javascript">alert("您的账户额度不足进行本次投注，请充值后在进行投注！","投注失败");</script>';
    exit;
}
?>
<HTML>
<HEAD>


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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</HEAD>

<body>

	<div class="ord">
		<div class="title">
			<h1>六合彩</h1>
		</div>
		<div class="main">
			<div class="fin_title">
				<p class="fin_acc">成功提交注单！</p>
				<p class="error" style="display: none;"></p>
			</div>
            <div class="leag_more"><?=$_POST['rtype']?></div>
                  <?
                $rate_id = $_POST['rate_id'];

                $gold = $_POST['gold'];

                switch ($_POST['rtype']) {

                    case "五不中":
                        $R = 37;
                        $ratess2 = 0;
                        $urlurl = "liuhecai.php?action=k_wbz&ids=五不中";
                        break;

                    case "六不中":
                        $R = 38;
                        $ratess2 = 0;
                        $urlurl = "liuhecai.php?action=k_wbz&ids=六不中";
                        break;

                    case "七不中":
                        $R = 39;
                        $ratess2 = 0;
                        $urlurl = "liuhecai.php?action=k_wbz&ids=七不中";
                        break;
                    case "八不中":
                        $R = 40;
                        $ratess2 = 0;
                        $urlurl = "liuhecai.php?action=k_wbz&ids=八不中";
                        break;
                    case "九不中":
                        $R = 41;
                        $ratess2 = 0;
                        $urlurl = "liuhecai.php?action=k_wbz&ids=九不中";
                        break;
                    case "十不中":
                        $R = 42;
                        $ratess2 = 0;
                        $urlurl = "liuhecai.php?action=k_wbz&ids=十不中";
                        break;
                    case "十一不中":
                        $R = 43;
                        $ratess2 = 0;
                        $urlurl = "liuhecai.php?action=k_wbz&ids=十一不中";
                        break;
                    case "十二不中":
                        $R = 44;
                        $ratess2 = 0;
                        $urlurl = "liuhecai.php?action=k_wbz&ids=十二不中";
                        break;
                }

                $sum_sum = $_POST['gold'] * $_POST['icradio1'];
                // p($_POST);

                $number1 = $_POST['number1'];

                $mu = explode("/", $number1);

                $ioradio1 = 1;
                $t == 3;
                for ($t = 2; $t < count($mu) - 1; $t = $t + 1) {

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

                    $num = date("YmdHis") . mt_rand("100000", "999999");
                    $text = date("Y-m-d H:i:s");
                    $class11 = "全不中";
                    $class22 = ka_bl($rate_id, "class2");
                    $class33 = $mu[$t];
                    $sum_m = $_POST['gold'];

                    if ($sum_m <= 0) {
                        // echo "<script Language=Javascript>alert('对不起，下注总额错误');parent.parent.leftFrame.location.href='liuhecai.php?action=k_wbz';window.location.href='liuhecai.php?action=k_wbz';</script>";
                        // exit;
                    }

                    $user_ds = ka_memds($R, 1);

                    $rnum = explode(",", $class33);

                    switch ($_POST['rtype']) {

                        case "五不中":
                            $r1 = ka_bl($rnum[0] + 1100, "rate");
                            $r2 = ka_bl($rnum[1] + 1100, "rate");
                            $r3 = ka_bl($rnum[2] + 1100, "rate");
                            $r4 = ka_bl($rnum[3] + 1100, "rate");
                            $r5 = ka_bl($rnum[4] + 1100, "rate");

                            $rate5 = $r1;
                            if ($r2 < $rate5) {
                                $rate5 = $r2;
                            }
                            if ($r3 < $rate5) {
                                $rate5 = $r3;
                            }
                            if ($r4 < $rate5) {
                                $rate5 = $r4;
                            }
                            if ($r5 < $rate5) {
                                $rate5 = $r5;
                            }
                            break;

                        case "六不中":
                            $r1 = ka_bl($rnum[0] + 1150, "rate");
                            $r2 = ka_bl($rnum[1] + 1150, "rate");
                            $r3 = ka_bl($rnum[2] + 1150, "rate");
                            $r4 = ka_bl($rnum[3] + 1150, "rate");
                            $r5 = ka_bl($rnum[4] + 1150, "rate");
                            $r6 = ka_bl($rnum[5] + 1150, "rate");

                            $rate5 = $r1;
                            if ($r2 < $rate5) {
                                $rate5 = $r2;
                            }
                            if ($r3 < $rate5) {
                                $rate5 = $r3;
                            }
                            if ($r4 < $rate5) {
                                $rate5 = $r4;
                            }
                            if ($r5 < $rate5) {
                                $rate5 = $r5;
                            }
                            if ($r6 < $rate5) {
                                $rate5 = $r6;
                            }
                            break;

                        case "七不中":
                            $r1 = ka_bl($rnum[0] + 1200, "rate");
                            $r2 = ka_bl($rnum[1] + 1200, "rate");
                            $r3 = ka_bl($rnum[2] + 1200, "rate");
                            $r4 = ka_bl($rnum[3] + 1200, "rate");
                            $r5 = ka_bl($rnum[4] + 1200, "rate");
                            $r6 = ka_bl($rnum[5] + 1200, "rate");
                            $r7 = ka_bl($rnum[6] + 1200, "rate");

                            $rate5 = $r1;
                            if ($r2 < $rate5) {
                                $rate5 = $r2;
                            }
                            if ($r3 < $rate5) {
                                $rate5 = $r3;
                            }
                            if ($r4 < $rate5) {
                                $rate5 = $r4;
                            }
                            if ($r5 < $rate5) {
                                $rate5 = $r5;
                            }
                            if ($r6 < $rate5) {
                                $rate5 = $r6;
                            }
                            if ($r7 < $rate5) {
                                $rate5 = $r7;
                            }
                            break;

                        case "八不中":
                            $r1 = ka_bl($rnum[0] + 1250, "rate");
                            $r2 = ka_bl($rnum[1] + 1250, "rate");
                            $r3 = ka_bl($rnum[2] + 1250, "rate");
                            $r4 = ka_bl($rnum[3] + 1250, "rate");
                            $r5 = ka_bl($rnum[4] + 1250, "rate");
                            $r6 = ka_bl($rnum[5] + 1250, "rate");
                            $r7 = ka_bl($rnum[6] + 1250, "rate");
                            $r8 = ka_bl($rnum[7] + 1250, "rate");

                            $rate5 = $r1;
                            if ($r2 < $rate5) {
                                $rate5 = $r2;
                            }
                            if ($r3 < $rate5) {
                                $rate5 = $r3;
                            }
                            if ($r4 < $rate5) {
                                $rate5 = $r4;
                            }
                            if ($r5 < $rate5) {
                                $rate5 = $r5;
                            }
                            if ($r6 < $rate5) {
                                $rate5 = $r6;
                            }
                            if ($r7 < $rate5) {
                                $rate5 = $r7;
                            }
                            if ($r8 < $rate5) {
                                $rate5 = $r8;
                            }

                            break;

                        case "九不中":
                            $r1 = ka_bl($rnum[0] + 1500, "rate");
                            $r2 = ka_bl($rnum[1] + 1500, "rate");
                            $r3 = ka_bl($rnum[2] + 1500, "rate");
                            $r4 = ka_bl($rnum[3] + 1500, "rate");
                            $r5 = ka_bl($rnum[4] + 1500, "rate");
                            $r6 = ka_bl($rnum[5] + 1500, "rate");
                            $r7 = ka_bl($rnum[6] + 1500, "rate");
                            $r8 = ka_bl($rnum[7] + 1500, "rate");
                            $r9 = ka_bl($rnum[8] + 1500, "rate");

                            $rate5 = $r1;
                            if ($r2 < $rate5) {
                                $rate5 = $r2;
                            }
                            if ($r3 < $rate5) {
                                $rate5 = $r3;
                            }
                            if ($r4 < $rate5) {
                                $rate5 = $r4;
                            }
                            if ($r5 < $rate5) {
                                $rate5 = $r5;
                            }
                            if ($r6 < $rate5) {
                                $rate5 = $r6;
                            }
                            if ($r7 < $rate5) {
                                $rate5 = $r7;
                            }
                            if ($r8 < $rate5) {
                                $rate5 = $r8;
                            }
                            if ($r9 < $rate5) {
                                $rate5 = $r9;
                            }

                            break;

                        case "十不中":
                            $r1 = ka_bl($rnum[0] + 1550, "rate");
                            $r2 = ka_bl($rnum[1] + 1550, "rate");
                            $r3 = ka_bl($rnum[2] + 1550, "rate");
                            $r4 = ka_bl($rnum[3] + 1550, "rate");
                            $r5 = ka_bl($rnum[4] + 1550, "rate");
                            $r6 = ka_bl($rnum[5] + 1550, "rate");
                            $r7 = ka_bl($rnum[6] + 1550, "rate");
                            $r8 = ka_bl($rnum[7] + 1550, "rate");
                            $r9 = ka_bl($rnum[8] + 1550, "rate");
                            $r10 = ka_bl($rnum[9] + 1550, "rate");

                            $rate5 = $r1;
                            if ($r2 < $rate5) {
                                $rate5 = $r2;
                            }
                            if ($r3 < $rate5) {
                                $rate5 = $r3;
                            }
                            if ($r4 < $rate5) {
                                $rate5 = $r4;
                            }
                            if ($r5 < $rate5) {
                                $rate5 = $r5;
                            }
                            if ($r6 < $rate5) {
                                $rate5 = $r6;
                            }
                            if ($r7 < $rate5) {
                                $rate5 = $r7;
                            }
                            if ($r8 < $rate5) {
                                $rate5 = $r8;
                            }
                            if ($r9 < $rate5) {
                                $rate5 = $r9;
                            }
                            if ($r10 < $rate5) {
                                $rate5 = $r10;
                            }

                            break;

                        case "十一不中":
                            $r1 = ka_bl($rnum[0] + 1600, "rate");
                            $r2 = ka_bl($rnum[1] + 1600, "rate");
                            $r3 = ka_bl($rnum[2] + 1600, "rate");
                            $r4 = ka_bl($rnum[3] + 1600, "rate");
                            $r5 = ka_bl($rnum[4] + 1600, "rate");
                            $r6 = ka_bl($rnum[5] + 1600, "rate");
                            $r7 = ka_bl($rnum[6] + 1600, "rate");
                            $r8 = ka_bl($rnum[7] + 1600, "rate");
                            $r9 = ka_bl($rnum[8] + 1600, "rate");
                            $r10 = ka_bl($rnum[9] + 1600, "rate");
                            $r11 = ka_bl($rnum[10] + 1600, "rate");

                            $rate5 = $r1;
                            if ($r2 < $rate5) {
                                $rate5 = $r2;
                            }
                            if ($r3 < $rate5) {
                                $rate5 = $r3;
                            }
                            if ($r4 < $rate5) {
                                $rate5 = $r4;
                            }
                            if ($r5 < $rate5) {
                                $rate5 = $r5;
                            }
                            if ($r6 < $rate5) {
                                $rate5 = $r6;
                            }
                            if ($r7 < $rate5) {
                                $rate5 = $r7;
                            }
                            if ($r8 < $rate5) {
                                $rate5 = $r8;
                            }
                            if ($r9 < $rate5) {
                                $rate5 = $r9;
                            }
                            if ($r10 < $rate5) {
                                $rate5 = $r10;
                            }
                            if ($r11 < $rate5) {
                                $rate5 = $r11;
                            }

                            break;

                        case "十二不中":
                            $r1 = ka_bl($rnum[0] + 1650, "rate");
                            $r2 = ka_bl($rnum[1] + 1650, "rate");
                            $r3 = ka_bl($rnum[2] + 1650, "rate");
                            $r4 = ka_bl($rnum[3] + 1650, "rate");
                            $r5 = ka_bl($rnum[4] + 1650, "rate");
                            $r6 = ka_bl($rnum[5] + 1650, "rate");
                            $r7 = ka_bl($rnum[6] + 1650, "rate");
                            $r8 = ka_bl($rnum[7] + 1650, "rate");
                            $r9 = ka_bl($rnum[8] + 1650, "rate");
                            $r10 = ka_bl($rnum[9] + 1650, "rate");
                            $r11 = ka_bl($rnum[10] + 1650, "rate");
                            $r12 = ka_bl($rnum[11] + 1650, "rate");

                            $rate5 = $r1;
                            if ($r2 < $rate5) {
                                $rate5 = $r2;
                            }
                            if ($r3 < $rate5) {
                                $rate5 = $r3;
                            }
                            if ($r4 < $rate5) {
                                $rate5 = $r4;
                            }
                            if ($r5 < $rate5) {
                                $rate5 = $r5;
                            }
                            if ($r6 < $rate5) {
                                $rate5 = $r6;
                            }
                            if ($r7 < $rate5) {
                                $rate5 = $r7;
                            }
                            if ($r8 < $rate5) {
                                $rate5 = $r8;
                            }
                            if ($r9 < $rate5) {
                                $rate5 = $r9;
                            }
                            if ($r10 < $rate5) {
                                $rate5 = $r10;
                            }
                            if ($r11 < $rate5) {
                                $rate5 = $r11;
                            }
                            if ($r12 < $rate5) {
                                $rate5 = $r12;
                            }

                            break;
                    }

                    switch (ka_memuser("abcd")) {
                        case "B":
                            $rate5 = $rate5 - $bzx;
                            break;
                        case "C":
                            $rate5 = $rate5 - $czx;
                            break;
                        case "D":
                            $rate5 = $rate5 - $dzx;
                            break;
                        default:
                            $Y = 1;
                            break;
                    }

                    $q1 = $q2 = 0;
                    $mysqlt->autocommit(FALSE);
                    $mysqlt->query("BEGIN"); // 事务开始
                    try {
                        $sql = "update k_user set money=money-$sum_m where uid=" . $_SESSION['uid'] . " and money>=$sum_m"; // 扣钱
                        $mysqlt->query($sql);
                        $q1 = $mysqlt->affected_rows;

                        if ($q1 == 1) {
                            $win = $rate5 * $sum_m;
                            $u_money = Get_user_One($_SESSION['username'], 'money');
                            $s_money = $u_money;
                            $cpfsbl = $sum_m * 0.1;
                            $type = "六合彩";
                            $uid = Get_user_One($_SESSION['username'], 'uid');
                            $sql1 = "INSERT INTO c_bet set did='" . $num . "',uid='" . $uid . "',agent_id='" . $userinfo['agent_id'] . "',username='" . $_SESSION['username'] . "',addtime='" . $text . "',type='" . $type . "',qishu='" . $Current_Kithe_Num . "',mingxi_1='" . $class11 . "',mingxi_2='" . $class33 . "',mingxi_3='" . $class22 . "',odds='" . $rate5 . "',money='" . $sum_m . "',win='" . $win . "',assets='" . $u_money . "',balance='" . $s_money . "',fs='0',site_id='" . SITEID . "'";
                            $mysqlt->query($sql1) or die("数据库修改出错");
                            // $sql="INSERT INTO c_odds_7 set num='".$num."',username='".$_SESSION['username']."',kithe='".$Current_Kithe_Num."',adddate='".$text."',class1='".$class11."',class2='".$class22."',class3='".$class33."',rate='".$rate5."',sum_m='".$sum_m."',user_ds='".$user_ds."',dai_ds='".$dai_ds."',zong_ds='".$zong_ds."',guan_ds='".$guan_ds."',dai_zc='".$dai_zc."',zong_zc='".$zong_zc."',guan_zc='".$guan_zc."',dagu_zc='".$dagu_zc."',bm=0,dai='".$dai."',zong='".$zong."',guan='".$guan."',danid='".$danid."',zongid='".$zongid."',guanid='".$guanid."',abcd='".$abcd."',lx=0,rate2='".$ratess2."'";
                            // $mysqli->query($sql) or die("数据库修改出错");
                            $q2 = $mysqlt->affected_rows;
                           // $source_id .= $mysqlt->insert_id . ",";
                            $money_all_h = $sum_m;
                           // $source_id = rtrim($source_id, ',');
                            $source_id = $mysqlt->insert_id;
                            $remark="彩票注单：" . $num." , 類型:" .$type;
                            $sql2 = "insert into k_user_cash_record(source_id,site_id,uid,username,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark)
         values ('" . $source_id . "','" . SITEID . "','" . $uid . "','".$_SESSION['username']."','3','2','" . $money_all_h . "',
        '" . $s_money . "','" . date("Y-m-d H:i:s", time()) . "','". $remark."')";
                            $q3 = $mysqlt->query($sql2) or die("数据库修改出错");
                        }

                        if ($q1 == 1 && $q2 == 1&& $q3 == 1) {
                            $mysqlt->commit(); // 事务提交
                        } else {
                            $mysqlt->rollback(); // 数据回滚
                        }
                    } catch (Exception $e) {
                        $mysqlt->rollback(); // 数据回滚
                    }

                    if ($q1 == 1 && $q2 == 1) {

                        ?>

                      <script type="text/javascript">
    parent.parent.k_memr.location.reload();
 </script>

			<p class="fin_team margin_top">
				<em><?=$mu[$t]?></em> @ <strong><?=$rate5?></strong>
			</p>
			<p class="fin_amount">
				交易金额：<span class="fin_gold"><?=$_POST['gold']?></span>

			</p>
                  <?
                    }
                }

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