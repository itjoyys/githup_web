
<?
is_fengpan($db_config);
// p($_POST);exit;
include (dirname(__FILE__) . "./../../include/public_config.php");
$rtype = $_POST['rtype'];
$class22='';
if($rtype == '三中二'){
   $data = M("c_odds_7",$db_config)->where("class1 = '连码' and class2 = '三中二'")->select();
   foreach ($data as $k => $v) {
     $class22 .= $v['class3'].':'.$v['rate'].';';
   }
   $class22 = rtrim($class22,';');
}else if($rtype == '二中特'){
      $data = M("c_odds_7",$db_config)->where("class1 = '连码' and class2 = '二中特'")->select();
   foreach ($data as $k => $v) {
     $class22 .= $v['class3'].':'.$v['rate'].';';
   }
   $class22 = rtrim($class22,';');
}


include (dirname(__FILE__) . "./../../include/private_config.php");
// include (".././include/order_info.php");
// var_dump($_POST);var_dump($_GET);exit;
$uid = $_SESSION["uid"];
$userinfo['agent_id'] =	$_SESSION["agent_id"];
$type_y = 6;
$username = $_SESSION["username"];
$sum_m = $_POST['danzhu_money'];

//p($_POST);exit;

// if(!defined('PHPYOU')) {
// exit('非法进入');
// }
$number3 = $_POST['number3'];
$number2 = $_POST['number2'];
$number3 = $number2 . "--" . $number3;

$XF = 21;
$edu = user_money($username,$sum_m,0);
if($edu==-1){
    echo '<script type="text/javascript">alert("您的账户额度不足进行本次投注，请充值后在进行投注！","投注失败");</script>';
    exit;
}
?>
<HTML>
<HEAD>


<!-- <link rel="stylesheet" href="./public/css/xp.css"> -->
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

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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



                $sum_sum = $_POST['gold'] * $_POST['icradio1'];

                $number1 = $_POST['number1'];

                // $mu = explode("/", $number1);

                $ioradio1 = 1;

              foreach ($number1 as $k => $v) {

                     //判断玩法

                    $num = date("YmdHis") . mt_rand("100000", "999999");
                    $text = date("Y-m-d H:i:s");
                    $class11 = "连码";

                    $class22 = $class22!=''?$class22:ka_bl($rate_id, "class2");
                    $class33 = $v;

                    $user_ds = ka_memds($R, 1);

                    $rate5 = ka_bl($rate_id, "rate");//赔率



                    $q1 = $q2 = 0;
                    $mysqlt->autocommit(FALSE);
                    $mysqlt->query("BEGIN"); // 事务开始
                    try {
                        $sql = "update k_user set money=(money-" . $sum_m . ") where uid=" . $_SESSION['uid'] . " and money>=" . $sum_m; // 扣钱
                        $mysqlt->query($sql);
                        $q1 = $mysqlt->affected_rows;
                        // echo $q1;

                        if ($q1 == 1) {
                            $win = $rate5 * $sum_m;
                            $u_money = Get_user_One($_SESSION['username'], 'money');
                            $s_money = $u_money;
                            $cpfsbl = $sum_m * 0.1;
                            $type = "六合彩";
                            $uid = Get_user_One($_SESSION['username'], 'uid');
                            $sql1 = "INSERT INTO c_bet set did='" . $num . "',uid='" . $uid . "',agent_id='" . $userinfo['agent_id'] . "',username='" . $_SESSION['username'] . "',addtime='" . $text . "',type='" . $type . "',qishu='" . $Current_Kithe_Num . "',mingxi_1='" . $class11 . "',mingxi_2='" . $class33 . "',mingxi_3='" . $class22 . "',odds='" . $rate5 . "',money='" . $sum_m . "',win='" . $win . "',assets='" . $u_money . "',balance='" . $s_money . "',fs='0',site_id='" . SITEID . "'";
                            $mysqlt->query($sql1) or die("数据库修改出错");

                            $q2 = $mysqlt->affected_rows;
                            // 写现金流表 cash_type=3：彩票下注 cash_do_type=2：取出
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
                    if ($q1 == 1 && $q2 == 1) {

                        ?>

          <script type="text/javascript">
    parent.parent.k_memr.location.reload();
 </script>

			<p class="fin_team margin_top">
				<em><?=$class33?></em> @ <strong><?=$rate5?></strong>
			</p>
			<p class="fin_amount">
				交易金额：<span class="fin_gold"><?=$sum_m?></span>

			</p>

                  <?
                    }
                    $all_money += $sum_m; // 交易总金额
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
