<meta charset="UTF-8">

<?
is_fengpan($db_config);
include (dirname(__FILE__) . "./../../include/private_config.php");


$_POST = array_filter(array_filter($_POST));
// p($_POST);p($_GET);exit;
$uid = $_SESSION["uid"];
$userinfo['agent_id'] =	$_SESSION["agent_id"];
//用户如果处于离线状态，则不允许投注
if(empty($_SESSION['uid']))
{
	echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
	echo '<script type="text/javascript">top.location.href="/";</script>';
	exit;
}

$type_y = 6;
// if(!defined('PHPYOU')) {
// exit('非法进入');
// }

// $get = array_filter($_GET);
// $post = array_filter($_POST);
// var_dump($get);echo "</br>";
// var_dump($post);
// exit();

if ($_GET['class2'] == "") {
    echo "<script>alert('非法进入!');parent.parent.leftFrame.location.href='liuhecai.php?action=k_tm.asp';window.location.href='liuhecai.php?action=k_tm';</script>";
    // exit;
}

switch ($_GET['class2']) {

    case "特A":

        $XF = 11;
        $mumu = 0;
        $urlurl = "liuhecai.php?action=k_tm&ids=特A";
        $numm = 68;
        $class11='特码';
        break;
    case "特B":

        $XF = 11;
        $mumu = 58;
        $numm = 66;
        $urlurl = "liuhecai.php?action=k_tm&ids=特B";
          $class11='特码';
        break;
    case "正A":
        $XF = 15;
        $mumu = 464;
        $urlurl = "liuhecai.php?action=k_zm&ids=正A";
        $numm = 58;
          $class11='正码';
        break;
    case "正B":
        $XF = 15;
        $mumu = 517;
        $numm = 58;
        $urlurl = "liuhecai.php?action=k_zm&ids=正B";
        $class11='正码';
        break;

    case "正1特":
        $XF = 13;
        $mumu = 116;
        $urlurl = "liuhecai.php?action=k_zt&ids=正1特";
        $numm = 60;
        $class11='正码特';
        break;

    case "正2特":
        $XF = 13;
        $mumu = 174;
        $urlurl = "liuhecai.php?action=k_zt&ids=正2特";
        $numm = 60;
        $class11='正码特';
        break;

    case "正3特":
        $XF = 13;
        $mumu = 232;
        $urlurl = "liuhecai.php?action=k_zt&ids=正3特";
        $numm = 60;
        $class11='正码特';
        break;

    case "正4特":
        $XF = 13;
        $mumu = 290;
        $urlurl = "liuhecai.php?action=k_zt&ids=正4特";
        $numm = 60;
        $class11='正码特';
        break;

    case "正5特":
        $XF = 13;
        $mumu = 348;
        $urlurl = "liuhecai.php?action=k_zt&ids=正5特";
        $numm = 60;
        $class11='正码特';
        break;

    case "正6特":
        $XF = 13;
        $mumu = 406;
        $urlurl = "liuhecai.php?action=k_zt&ids=正6特";
        $numm = 60;
        $class11='正码特';
        break;

    case "正1-6":
        $XF = 13;
        $mumu = 570;
        $urlurl = "liuhecai.php?action=k_zm6&ids=正1-6";
        $numm = 78;
        $class11='正1-6';
        break;

    case "五行":
        $XF = 17;
        $mumu = 712;
        $urlurl = "liuhecai.php?action=k_wx&ids=五行";
        $numm = 5;

        break;

    case "半波":
        $XF = 25;
        $mumu = 661;
        $urlurl = "liuhecai.php?action=k_bb&ids=半波";
        $numm = 18;
        break;
    case "半半波":
        $XF = 25;
        $mumu = 751;
        $urlurl = "liuhecai.php?action=k_bbb&ids=半半波";
        $numm = 12;
        break;

    case "正肖":
        $XF = 25;
        $mumu = 782;
        $urlurl = "liuhecai.php?action=k_qsb&ids=正肖";
        $numm = 12;
        break;
    case "七色波":
        $XF = 25;
        $mumu = 778;
        $urlurl = "liuhecai.php?action=k_qsb&ids=正肖";
        $numm = 4;
        break;

    case "尾数":
        $XF = 27;
        $mumu = 689;
        $urlurl = "liuhecai.php?action=k_ws&ids=尾数";
        $numm = 79;
        break;
    case "特肖":
        $XF = 23;
        $mumu = 673;
        $urlurl = "liuhecai.php?action=k_sx&ids=特肖";
        $numm = 12;
        $class11='特码生肖';
        break;

    case "一肖":
        $XF = 23;
        $mumu = 699;
        $urlurl = "liuhecai.php?action=k_sxp&ids=一肖";
        $numm = 12;
        $class11='一肖/尾数';
        break;

    case "正特尾数":
        $XF = 23;
        $mumu = 768;
        $urlurl = "liuhecai.php?action=k_sxp&ids=一肖";
        $numm = 12;
        $class11='一肖/尾数';
        break;

    case "过关":
        $XF = 19;
        $class11='过关';
        break;
    case "连码":
        $XF = 21;
        $class11='连码';
        break;
    default:
        $mumu = 0;
        $numm = 58;
        $urlurl = "liuhecai.php?action=k_tm&ids=特A";

        $XF = 11;
        break;
}
?>

<link rel="stylesheet" href="./public/css/mem_order_ft.css"
	type="text/css">
<style type="text/css">
body {
	width: 100%;
	height: 100%;
}

#n1_table {
	position: absolute;
	top: 30%;
	left: 30%;
}

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
<body>

	<!-- <div id="n1_table"> -->
	<SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';}
if(window.location.host!=top.location.host){top.location=window.location;}
</SCRIPT>

	<SCRIPT language=javascript>
window.setTimeout("<?=$urlurl ?>", 30000);
</SCRIPT>
	<SCRIPT language=JAVASCRIPT>
if(self == top){location = '/';}

function ChkSubmit(){
    //设定『确定』键为反白


		document.all.btnSubmit.disabled = true;

document.form1.submit();
	}
</SCRIPT>



	<!-- <table height="13" cellspacing="0" cellpadding="0" border="0" width="180">
        <tbody><tr>
          <td id="left_1" style="text-align:center; font-size:16px; font-weight:bold"><img src="/images/b002.jpg"></td>
        </tr>
      </tbody></table> -->





       <?

    $sum_sum = 0;
    $username = $_SESSION["username"];
    $money_all_h; // 总交易金额
    for ($r = 0; $r <= $numm; $r ++) {
        if ($_POST['Num_' . $r] > 0) {
            $sum_sum = $sum_sum + $_POST['Num_' . $r];
        }
        if ($sum_sum > ka_memuser("ts")) {
            // echo "<script Language=Javascript>alert('对不起，下注总额不能大于可用信用额');parent.parent.leftFrame.location.href='".$urlurl."';window.location.href='".$urlurl."';</script>";
            // exit;
        }
    }
    $edu = user_money($username,$sum_sum,0);
    if($edu==-1){
        echo '<script type="text/javascript">alert("您的账户额度不足进行本次投注，请充值后在进行投注！","投注失败");</script>';
        exit;
    }
    ?>
   <div class="ord">
        <div class="main">
            <div class="fin_title">
                <p class="fin_acc">成功提交注单！</p>

                <p class="error" style="display: none;"></p>
            </div>
              <div class="title">
            <h1><?=$class11 ?></h1>
        </div>

    <?php

    for ($r = 0; $r <= $numm; $r ++) {
        if($_POST['Num_' . $r] == ""){
            continue;
        }
        if (abs($_POST['Num_' . $r]) != "" || abs($_POST['Num_' . $r]) != 0) {

            $sum_sum = $sum_sum + abs($_POST['Num_' . $r]);

            if ($r == 59 or $r == 60) {
                if ($_POST['class2'] == "特A") {
                    $rate_id = $r + 687;
                } else {
                    switch ($_GET['class2']) {

                        case "正1特": // 1034
                            $rate_id = $r + 975;
                            break;
                        case "正2特": // 1045
                            $rate_id = $r + 1023;
                            if ($r == 59) {
                                $rate_id = $r + 986;
                            }
                            break;
                        case "正3特": // 1044
                            $rate_id = $r + 1024;
                            if ($r == 59) {
                                $rate_id = $r + 985;
                            }
                            break;
                        case "正4特": // 1043
                            $rate_id = $r + 1025;
                            if ($r == 59) {
                                $rate_id = $r + 984;
                            }
                            break;
                        case "正5特": // 1042
                            $rate_id = $r + 1026;
                            if ($r == 59) {
                                $rate_id = $r + 983;
                            }
                            break;
                        case "正6特": // 1041
                            $rate_id = $r + 1027;
                            if ($r == 59) {
                                $rate_id = $r + 982;
                            }
                            break;
                        default:
                            $rate_id = $r + 689;
                    }
                }
            } else {
                if ($_GET['class2'] == "半波" && $r >= 13) {
                    $rate_id = $r + 705;
                } else {
                    $rate_id = $r + $mumu;
                }
            }
            if ($r == 61) {
                if ($_GET['class2'] == "特A") {
                    $rate_id = 795;
                } else {
                    $rate_id = 801;
                }
            }
            if ($r == 62) {
                if ($_GET['class2'] == "特A") {
                    $rate_id = 796;
                } else {
                    $rate_id = 802;
                }
            }
            if ($r == 63) {
                if ($_GET['class2'] == "特A") {
                    $rate_id = 797;
                } else {
                    $rate_id = 803;
                }
            }
            if ($r == 64) {
                if ($_GET['class2'] == "特A") {
                    $rate_id = 798;
                } else {
                    $rate_id = 804;
                }
            }
            if ($r == 65) {
                if ($_GET['class2'] == "特A") {
                    $rate_id = 799;
                } else {
                    $rate_id = 805;
                }
            }
            if ($r == 66) {
                if ($_GET['class2'] == "特A") {
                    $rate_id = 800;
                } else {
                    $rate_id = 806;
                }
            }
            if ($_GET['class2'] == "正1-6") {
                // echo $r."<br>";
                if ($r >= 1 && $r <= 7) {
                    $rate_id = $r + $mumu;
                } elseif ($r >= 14 && $r <= 20) {
                    $rate_id = ($r - 6) + $mumu;
                } elseif ($r >= 27 && $r <= 33) {
                    $rate_id = ($r - 12) + $mumu;
                } elseif ($r >= 40 && $r <= 46) {
                    $rate_id = ($r - 18) + $mumu;
                } elseif ($r >= 53 && $r <= 59) {
                    $rate_id = ($r - 24) + $mumu;
                } elseif ($r >= 66 && $r <= 72) {
                    $rate_id = ($r - 30) + $mumu;
                } elseif ($r >= 8 && $r <= 13) {
                    $rate_id = $r + 1039;
                } elseif ($r >= 21 && $r <= 26) {
                    $rate_id = ($r - 7) + 1039;
                } elseif ($r >= 34 && $r <= 39) {
                    $rate_id = ($r - 14) + 1039;
                } elseif ($r >= 47 && $r <= 52) {
                    $rate_id = ($r - 21) + 1039;
                } elseif ($r >= 60 && $r <= 65) {
                    $rate_id = ($r - 28) + 1039;
                } elseif ($r >= 73 && $r <= 78) {
                    $rate_id = ($r - 35) + 1039;
                }

                /*
                 * if ($r<=9){
                 * $rate_id=$r+$mumu;
                 * }elseif($r<=18){
                 * $rate_id=$r+214;
                 * }elseif($r<=27){
                 * $rate_id=$r+263;
                 * }elseif($r<=36){
                 * $rate_id=$r+312;
                 * }elseif($r<=45){
                 * $rate_id=$r+361;
                 * }elseif($r<=54){
                 * $rate_id=$r+410;
                 * }
                 */
            }

            switch (ka_bl($rate_id, "class1")) {
                case "特码":

                    switch (ka_bl($rate_id, "class3")) {
                        case "单":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 2;
                            $drop_sort = "单双";
                            break;

                        case "双":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 2;
                            $drop_sort = "单双";
                            break;

                        case "家禽":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 24;
                            $drop_sort = "家禽野兽";
                            break;

                        case "野兽":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 24;
                            $drop_sort = "家禽野兽";
                            break;

                        case "尾大":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 32;
                            $drop_sort = "尾大尾小";
                            break;

                        case "尾小":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 32;
                            $drop_sort = "尾大尾小";
                            break;

                        case "大单":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 33;
                            $drop_sort = "大单小单";
                            break;

                        case "小单":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 33;
                            $drop_sort = "大单小单";
                            break;

                        case "大双":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 34;
                            $drop_sort = "大双小双";
                            break;

                        case "小双":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 34;
                            $drop_sort = "大双小双";
                            break;

                        case "大":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 3;
                            $drop_sort = "大小";
                            break;

                        case "小":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 3;
                            $drop_sort = "大小";
                            break;

                        case "合单":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 4;
                            $drop_sort = "合数单双 ";
                            break;

                        case "合双":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 4;
                            $drop_sort = "合数单双 ";
                            break;

                        case "红波":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 10;
                            $drop_sort = "波色";

                            break;
                        case "绿波":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 10;
                            $drop_sort = "波色";
                            break;

                        case "蓝波":
                            $bmmm = $btmdx;
                            $cmmm = $ctmdx;
                            $dmmm = $dtmdx;
                            $R = 10;
                            $drop_sort = "波色";

                            break;
                        default:
                            $bmmm = $btm;
                            $cmmm = $ctm;
                            $dmmm = $dtm;
                            if (ka_bl($rate_id, "class2") == "特A") {
                                $R = 0;
                            } else {
                                $R = 1;
                            }

                            $drop_sort = "特码";

                            break;
                    }
                    break;
                case "正码":
                    switch (ka_bl($rate_id, "class3")) {
                        case "总单":
                            $R = 8;
                            $drop_sort = "总数单双";
                            $bmmm = $bzmdx;
                            $cmmm = $czmdx;
                            $dmmm = $dzmdx;
                            break;

                        case "总双":
                            $R = 8;
                            $drop_sort = "总数单双";
                            $bmmm = $bzmdx;
                            $cmmm = $czmdx;
                            $dmmm = $dzmdx;
                            break;

                        case "总大":
                            $R = 9;
                            $drop_sort = "总数大小";
                            $bmmm = $bzmdx;
                            $cmmm = $czmdx;
                            $dmmm = $dzmdx;
                            break;

                        case "总小":
                            $R = 9;
                            $drop_sort = "总数大小";
                            $bmmm = $bzmdx;
                            $cmmm = $czmdx;
                            $dmmm = $dzmdx;

                            break;
                        default:

                            if (ka_bl($rate_id, "class2") == "正A") {
                                $R = 6;
                            } else {
                                $R = 7;
                            }

                            $drop_sort = "正码";

                            $bmmm = $bzm;
                            $cmmm = $czm;
                            $dmmm = $dzm;
                            break;
                    }
                    break;

                case "五行":
                    $R = 25;
                    $drop_sort = "五行";
                    $bmmm = $bzm6;
                    $cmmm = $czm6;
                    $dmmm = $dzm6;
                    break;

                case "生肖":

                    switch (ka_bl($rate_id, "class2")) {
                        case "特肖":
                            $bmmm = $bsx;
                            $cmmm = $csx;
                            $dmmm = $dsx;
                            $R = 18;
                            $drop_sort = "特肖";

                            break;
                        case "四肖":
                            $bmmm = 0;
                            $cmmm = 0;
                            $dmmm = 0;
                            $R = 19;
                            $drop_sort = "四肖";
                            break;

                        case "五肖":
                            $bmmm = 0;
                            $cmmm = 0;
                            $dmmm = 0;
                            $R = 20;
                            $drop_sort = "五肖";
                            break;

                        case "六肖":
                            $bmmm = $bsx6;
                            $cmmm = $csx6;
                            $dmmm = $dsx6;
                            $R = 21;
                            $drop_sort = "六肖";
                            break;

                        case "一肖":
                            $bmmm = $bsxp;
                            $cmmm = $csxp;
                            $dmmm = $dsxp;
                            $R = 22;
                            $drop_sort = "一肖";
                            break;

                        case "正特尾数":
                            $bmmm = $bsxp;
                            $cmmm = $csxp;
                            $dmmm = $dsxp;
                            $R = 29;
                            $drop_sort = "正特尾数";
                            break;

                            break;
                        default:
                            $R = 18;
                            $drop_sort = "特肖";
                            $bmmm = $bsxp;
                            $cmmm = $csxp;
                            $dmmm = $dsxp;
                            break;
                    }
                    break;

                case "半波":
                    $bmmm = $bbb;
                    $cmmm = $cbb;
                    $dmmm = $dbb;
                    $R = 11;
                    $drop_sort = "半波";
                    break;
                case "半半波":
                    $bmmm = $bbb;
                    $cmmm = $cbb;
                    $dmmm = $dbb;
                    $R = 11;
                    $drop_sort = "半半波";
                case "正肖":
                    $bmmm = $bbb;
                    $cmmm = $cbb;
                    $dmmm = $dbb;
                    $R = 11;
                    $drop_sort = "正肖";
                case "七色波":
                    $bmmm = $bbb;
                    $cmmm = $cbb;
                    $dmmm = $dbb;
                    $R = 11;
                    $drop_sort = "七色波";
                    break;
                case "正特":
                    switch (ka_bl($rate_id, "class3")) {
                        case "单":
                            $R = 2;
                            $drop_sort = "单双";
                            $bmmm = $bztdx;
                            $cmmm = $cztdx;
                            $dmmm = $dztdx;
                            break;

                        case "双":
                            $R = 2;
                            $drop_sort = "单双";
                            $bmmm = $bztdx;
                            $cmmm = $cztdx;
                            $dmmm = $dztdx;
                            break;

                        case "大":
                            $R = 3;
                            $drop_sort = "大小";
                            $bmmm = $bztdx;
                            $cmmm = $cztdx;
                            $dmmm = $dztdx;
                            break;

                        case "小":
                            $R = 3;
                            $drop_sort = "大小";
                            $bmmm = $bztdx;
                            $cmmm = $cztdx;
                            $dmmm = $dztdx;

                            break;
                        case "合单":
                            $R = 4;
                            $drop_sort = "合数单双 ";
                            $bmmm = $bztdx;
                            $cmmm = $cztdx;
                            $dmmm = $dztdx;

                            break;
                        case "合双":
                            $R = 4;
                            $drop_sort = "合数单双 ";
                            $bmmm = $bztdx;
                            $cmmm = $cztdx;
                            $dmmm = $dztdx;
                            break;

                        case "红波":
                            $R = 10;
                            $drop_sort = "波色";
                            $bmmm = $bztdx;
                            $cmmm = $cztdx;
                            $dmmm = $dztdx;
                            break;

                        case "绿波":
                            $R = 10;
                            $drop_sort = "波色";
                            $bmmm = $bztdx;
                            $cmmm = $cztdx;
                            $dmmm = $dztdx;

                            break;
                        case "蓝波":
                            $R = 10;
                            $drop_sort = "波色";
                            $bmmm = $bztdx;
                            $cmmm = $cztdx;
                            $dmmm = $dztdx;

                            break;
                        default:
                            $R = 5;
                            $drop_sort = "正特";
                            $bmmm = $bzt;
                            $cmmm = $czt;
                            $dmmm = $dzt;
                            break;
                    }
                    break;

                case "尾数":
                    $R = 23;
                    $drop_sort = "尾数";
                    $bmmm = 0;
                    $cmmm = 0;
                    $dmmm = 0;
                    break;

                case "正1-6":
                    $R = 38;
                    $drop_sort = "正1-6";
                    $bmmm = 0;
                    $cmmm = 0;
                    $dmmm = 0;

                    break;

                default:
                    $R = 23;
                    $drop_sort = "尾数";
                    $bmmm = 0;
                    $cmmm = 0;
                    $dmmm = 0;
                    break;
            }

            switch (ka_memuser("abcd")) {

                case "A":
                    $rate5 = ka_bl($rate_id, "rate");
                    $Y = 1;
                    break;
                case "B":
                    $rate5 = ka_bl($rate_id, "rate") - $bmmm;
                    $Y = 4;
                    break;
                case "C":
                    $Y = 5;
                    $rate5 = ka_bl($rate_id, "rate") - $cmmm;
                    break;
                case "D":
                    $rate5 = ka_bl($rate_id, "rate") - $dmmm;
                    $Y = 6;
                    break;
                default:
                    $Y = 1;
                    $rate5 = ka_bl($rate_id, "rate");
                    break;
            }

            $num = date("YmdHis") . mt_rand("100000", "999999");
            $text = date("Y-m-d H:i:s");
            $class11 = ka_bl($rate_id, "class1");
            $class22 = ka_bl($rate_id, "class2");
            $class33 = ka_bl($rate_id, "class3");
            $sum_m = $_POST['Num_' . $r];
            if ($sum_m <= 0) {
                // echo "<script Language=Javascript>alert('对不起,投注金额有误.请反回重新选择!');parent.parent.leftFrame.location.href='".$urlurl."';window.location.href='".$urlurl."';</script>";
                // exit;
            }
            $user_ds = ka_memds($R, 1);

            $q1 = $q2 = 0;
            $mysqli->autocommit(FALSE);
            $mysqli->query("BEGIN"); // 事务开始
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

                    $q2 = $mysqlt->affected_rows;
                    $source_id = $mysqlt->insert_id;
                    $all_money = $sum_m; // 交易总金额
                    $remark="彩票注单：" . $num." , 類型:" .$type;
                    $sql2 = "insert into k_user_cash_record(source_id,site_id,uid,username,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark)
         values ('" . $source_id . "','" . SITEID . "','" . $uid . "','".$_SESSION['username']."','3','2','" . $all_money . "',
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

            include 'ds.php';

            if ($q1 == 1 && $q2 == 1) {
                $money_all_h += $sum_m;

                ?>
<?
            }
        }


    ?>



			<p class="fin_team">
        <?php

                if($class11=='正特'){
                    echo $class22."-";
                    echo $class33;
                }else if($class11=='正1-6'){
                    echo $class22."-";
                    echo $class33;
                }else{
                     echo $class33;
                }

                echo '@';
                 ?>
                 <strong>
                 <?php

                echo $rate5;
            ?></strong>
			</p>
			<p class="fin_amount">
				交易金额：<span class="fin_gold"><?=$sum_m ?></span>
			</p>


        <?php } ?>
		<div class="betBox">
			<input type="button" name="PRINT" value="确定"
				onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="print"> <input
				type="button" name="FINISH" value="关闭"
				onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="close">
		</div>

	</div>
    <script type="text/javascript">
    parent.parent.k_memr.location.reload();

    parent.parent.k_meml.SetWinHeight();
 </script>

</form>


</BODY>
</HTML>
