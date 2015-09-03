<?php

if(empty($_SESSION['uid']))
{
	echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
	echo '<script type="text/javascript">top.location.href="/";</script>';
	exit;
}
include_once ("../include/config.php");
include_once ("./liuhecai/config.php");
include ("../include/private_config.php");
// p($_GET);exit;
$user_money = M('k_user', $db_config)->where('username = "'.$_SESSION["username"].'" and site_id = "'.SITEID.'"')->field('money')->find();

// 公告信息
$notice = M('site_notice',  $db_config)->field('notice_content')
    ->where("notice_cate='3' and notice_state = '1' and (sid = '0' or sid = '" . SITEID . "')")
    ->order("notice_date desc")
    ->find();

include ("../include/public_config.php");

if (! empty($_GET['type']) && empty($_GET['type_y'])) { // 最近交易参数转换
    switch ($_GET['type']) {
        case '1':
            $_GET['type_y'] = 7;
            break;
        case '2':
            $_GET['type_y'] = 3;
            break;
        case '3':
            $_GET['type_y'] = 5;
            break;
        case '4':
            $_GET['type_y'] = 8;
            break;
        case '5':
            $_GET['type_y'] = 1;
            break;
        case '9':
            $_GET['type_y'] = 6;
            break;
        case '6':
            $_GET['type_y'] = 2;
            break;
        case '8':
            $_GET['type_y'] = 4;
            break;
        case '9':
            $_GET['type_y'] = 9;
            break;
        case '10':
            $_GET['type_y'] = 10;
            break;
        case '11':
            $_GET['type_y'] = 11;
            break;
        case '12':
            $_GET['type_y'] = 12;
            break;
        case '13':
            $_GET['type_y'] = 13;
            break;
        case '14':
            $_GET['type_y'] = 14;
            break;
    }
}
if (! empty($_GET['fc_type'])) { // 最近交易参数转换
    switch ($_GET['fc_type']) {
        case '1':
            $_GET['type_y'] = 7;
            break;
        case '2':
            $_GET['type_y'] = 3;
            break;
        case '3':
            $_GET['type_y'] = 5;
            break;
        case '4':
            $_GET['type_y'] = 8;
            break;
        case '5':
            $_GET['type_y'] = 1;
            break;
        case '9':
            $_GET['type_y'] = 6;
            break;
        case '6':
            $_GET['type_y'] = 2;
            break;
        case '8':
            $_GET['type_y'] = 4;
            break;
        case '10':
            $_GET['type_y'] = 10;
            break;
        case '11':
            $_GET['type_y'] = 11;
            break;
        case '12':
            $_GET['type_y'] = 12;
            break;
        case '13':
            $_GET['type_y'] = 13;
            break;
        case '14':
            $_GET['type_y'] = 14;
            break;
    }
}

// $kithe = "and addtime >=  NOW() - interval 1 day";
$where = " and js=0 and uid = '" . $_SESSION['uid'] . "' " . $kithe;
$typefield = 'qishu';
$typelimit = 8;
$typeorder = 'qishu';
$typewhere = "1=1";
switch ($_GET['type_y']) {
    case '1':
        // 福彩3D
        $type_id = 5;
        $title_f = '福彩3D';

        break;
    case '2':
        // 排列三
        $type_id = 6;
        $title_f = '排列三';
        break;
    case '3':
        // 重庆时时彩
        $type_id = 2;
        $title_f = '重庆时时彩';
        break;
    case '9':
        // 上海时时彩
        $type_id = 9;
        $title_f = '上海时时彩';
        break;
    case '10':
        // 天津时时彩
        $type_id = 10;
        $title_f = '天津时时彩';
        break;
    case '11':
        // 江西时时彩
        $type_id = 11;
        $title_f = '江西时时彩';
        break;
    case '12':
        // 新疆时时彩
        $type_id = 12;
        $title_f = '新疆时时彩';
        break;
    case '4':
        // 北京快乐8
        $type_id = 8;
        $title_f = '北京快乐8';
        break;
    case '5':
        // 北京赛车PK拾
        $type_id = 3;
        $title_f = '北京赛车pk拾';
        break;
    case '6':
        // 六合彩
        $typefield = 'nn';
        $typeorder = 'nd';
        $typelimit = 28;
        $typewhere = "na!=0";
        $type_id = 7;
        $title_f = '六合彩';
        break;
    case '7':
        // 广东快乐十分
        $type_id = 1;
        $title_f = '广东快乐十分';
        break;
    case '8':
        // 重庆快乐十分
        $type_id = 4;
        $title_f = '重庆快乐十分';
        break;
    case '13':
        // 江苏快3
        $type_id = 13;
        $title_f = '江苏快3';
        break;
    case '14':
        // 吉林快3
        $type_id = 14;
        $title_f = '吉林快3';
        break;
    default:
        $type_id = 5;
        break;
}

$qishu = M('c_auto_' . $type_id, $db_config)->field('' . $typefield . '')
    ->order("datetime desc")
    ->find();
$result = M('c_auto_' . $type_id, $db_config)->where($typewhere)
    ->order("" . $typeorder . " desc")
    ->limit("0," . $typelimit . "")
    ->select();

$db_config['dbname'] = 'cyj_private';
$bet_result = M('c_bet', $db_config)->where("type = '" . $title_f . "'" . $where)
    ->order("addtime desc")
    ->limit("0,8")
    ->select();

$db_config['dbname'] = 'cyj_public';

// 显示影藏框架
if (! empty($_GET['action']) || ! empty($_GET['qishu'])) {

    $bet_show = "block";
    $qishushou = "none";
    $info_show = "block";
    $recRoad_show = "block";
} else {

    $bet_show = "none";
    $qishushou = "block";
    $info_show = "block";
    $recRoad_show = "block";
}

$_GET['haoma'] = $_GET['haoma'] == '0' ? '零' : $_GET['haoma'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>webcom</title>
<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
<link rel="stylesheet" href="./public/css/xp.css" type="text/css">
</head>
<body id="HOP" ondragstart="window.event.returnValue=false"
	oncontextmenu="window.event.returnValue=false"
	onselectstart="event.returnValue=false">
	<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
	<link rel="stylesheet" href="./public/css/mem_order_ft.css"
		type="text/css">
	<link rel="stylesheet" href="./public/css/mem_body_olympics.css"
		type="text/css">
	<link rel="stylesheet" href="./public/css/mem_order_sel.css"
		type="text/css">
	<script src="./public/js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script>
	//公告 更多的弹出窗
	function showMoreMsg(){
		window.open('/Member/index_main.php?url=9','帮助','height=630,width=1020,top=80,left=200,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no');
	}

	$(function(){
        getmoney(<?=$user_money['money']?>);
		//福彩3D开奖结果切换
		var display;
		$(".show_3d").click(function(event) {
			var k = $(this).attr('show_3d');
			for (var j = 1; j <=4 ; j++) {
				for (var i = 0; i <= 7; i++) {
					if(j==k){display=''}else{display='none'}
					$("#3d_show_result_"+j+"_"+i).css('display', display);
				};
			};
		});
		//重庆时时彩开奖结果切换
		$(".show_ssc").click(function(event) {
			var k = $(this).attr('show_ssc');
			for (var j = 1; j <=4 ; j++) {
				for (var i = 0; i <= 7; i++) {
					if(j==k){display=''}else{display='none'}
					$("#ssc_show_result_"+j+"_"+i).css('display', display);
				};
			};
		});
	})
</script>
	<script type="text/javascript">
	//交易状况
	function showOrder(){
	document.getElementById("order_button").className="ord_on";
	document.getElementById("record_button").className="record_btn";
	var BB=document.getElementById("bet_div");//下注框
	var CC=document.getElementById("pls_bet");//当前期数
	var DD=document.getElementById("rec_div");//最近8笔
	if(BB){
		BB.style.display="none";
	}
	if(CC){
		CC.style.display="block";
	}
	if(DD){
		DD.style.display="none";
	}
	}

	//最新8笔交易
	function showRec(){
    document.getElementById("order_button").className="ord_btn";document.getElementById("record_button").className="record_on";
	var B=document.getElementById("bet_div");//下注框
	var C=document.getElementById("pls_bet");//当前期数
	var D=document.getElementById("recRoad");//开奖结果
	var E=document.getElementById("info_div");//公告
	var F=document.getElementById("rec_div");//最近8笔
	if(B){
		B.style.display="none";
	}
	if(C){
		C.style.display="none";
	}
	if(D){
		D.style.display="block";
	}
	if(E){
		E.style.display="block";
	}
	if(F){
		F.style.display="block";
	}
}
</script>

<?php

if (! empty($_GET['go']) && $_GET['go'] == 1) {
    echo '<script>window.onload=function(){showRec();}</script>';
}
?>
<script type="text/javascript">
function getmoney(money){
	$.ajax({
		type: "get",
		url: "lottery_results_ajax.php",
		data: "money="+money,
		success: function(data_arr){
			$("#money").html(data_arr);
		}
	});
}
	setInterval(function(){getmoney(<?=$user_money['money']?>);},30000);

</script>

<div id="main"

		style="width: 236px; position: fixed; top: 0px; left: 0;">
		<div class="panelImfo"> 您的帐户余额：<span id='money'><?=$user_money['money']?></span>&nbsp;<font
				id="credit">RMB</font> <a id="reCredit" class="btnGreen"
				href="javascript:void(0)" onclick="getmoney();">刷新</a>
		</div>
	</div>
	<div id="menu">
		<div class="ord_on" id="order_button" onclick="showOrder();">交易资料</div>
		<div class="record_btn" id="record_button"
			onclick="self.location.href='main_left.php?go=1&fc_type=<?=$_GET['fc_type'] ?>&type_y=<?=$_GET['type_y'] ?>&type=<?=$_GET['type'] ?>'">最新8笔交易</div>

	</div>
	<div id="order_div" name="order_div"
		style="overflow: hidden; background-color: #ddd8d5">

		<script>
		//iframe自适应高度
			function SetWinHeight(obj){

                if(obj){
                    var bet_div=obj;
                }else{
                    var bet_div=document.getElementById("bet_order_frame");
                }


			if (document.getElementById)
			{
			if (bet_div && !window.opera)
			{
			if (bet_div.contentDocument && bet_div.contentDocument.body.offsetHeight&& bet_div.contentDocument.body.offsetHeight!=150){
			bet_div.height = bet_div.contentDocument.body.offsetHeight+50;
			}else if(bet_div.Document && bet_div.Document.body.scrollHeight)
			bet_div.height = bet_div.Document.body.scrollHeight+50;
			}
			}

			}
			function SetWinHeight_1(obj)
			{
			var rec_frame=obj;
			if (document.getElementById)
			{
			if (rec_frame && !window.opera)
			{
			if (rec_frame.contentDocument && rec_frame.contentDocument.body.offsetHeight)
			rec_frame.height = rec_frame.contentDocument.body.offsetHeight;
			else if(rec_frame.Document && rec_frame.Document.body.scrollHeight)
			rec_frame.height = rec_frame.Document.body.scrollHeight;
			}
			}
			}
		</script>
<?php

// 查询期数
$now_time = date("H:i:s", strtotime("+12 hours"));
$dangqian = M('c_opentime_' . type_y($_GET['type_y']), $db_config);
$data_time = $dangqian->field("*")
    ->where("ok ='0' and fengpan > '" . $now_time . "'")
    ->order("kaijiang ASC")
    ->find();

?>

  <div id="pls_bet" name="pls_bet" style="left: 0px; top: 0px; display:<?=$qishushou ?>;">
			<div id="pls_imfo" style="margin-left: 5px">
				<span>当前期数:<font id="nmcq">  <?php

    if ($_GET['type_y'] == 6) {
        // 六合彩单独查期数
        $type_y = 7;
    } elseif ($_GET['type_y'] == 1) {
        // 福彩3D每天一期
        $type_y = 5;
    } elseif ($_GET['type_y'] == 2) {
        // 排列3每天一期
        $type_y = 6;
    } elseif ($_GET['type_y'] == 4) {
        // 北京快乐8
        $type_y = 8;
    } elseif ($_GET['type_y'] == 5) {
        // 北京PK10
        $type_y = 3;
    } elseif ($_GET['type_y'] == 8) {
        // 重庆快乐10分
        $type_y = 4;
    } elseif($_GET['type_y'] == 3) {
        $type_y = 2;
    }elseif($_GET['type_y'] == 7) {
        $type_y = 1;
    }else {
        $type_y = $_GET['type_y'];
    }

    echo dq_qishu($type_y,$db_config);
    ?></font>期
				</span> <span>游戏类型：<?=$title_f?></span> <span style="display: none">盘口类型：
					<select name="class2" id="class2"
					onchange="parent.ChangePc(this.value);">
						<option value="D">D</option>
				</select>盘
				</span>

			</div>
		</div>
<?php

$datas = $_GET;
$datas1 = $_POST;

if ($_GET['action'] == 'dan' || $_GET['action'] == 'dan_1') {

    ?>
<!-- 点击赔率跳转 -->
		<div id="bet_div" name="bet_div" style="display: <?=$bet_show ?>;">
			<iframe id="bet_order_frame" width="216" name="bet_order_frame"
				scrolling="NO" frameborder="NO" border="0" height="223"
				src="time_bet.php?name=<?=$_GET['name'] ?>&type=<?=$_GET['type'] ?>&action=<?=$_GET['action'] ?>&bet=<?=$_GET['bet'] ?>&fc_type=<?=$_GET['fc_type'] ?>&class2=<?=$_GET['class2'] ?>&haoma=<?=$_GET['haoma'] ?>&style=<?=$_GET['style'] ?>&leixing_1=<?=$_GET['leixing'] ?>&qishu=<?=$_GET['qishu'] ?>"></iframe>
		</div>
<?php
} else
    if (! empty($_GET['qishu'])) {
        ?>
	<!-- 点击下注跳转(除六合彩) -->
		<div id="bet_div" name="bet_div" style="display: <?=$bet_show ?>;">
			<iframe id="bet_order_frame" width="216" name="bet_order_frame"
				scrolling="NO" frameborder="NO" border="0" height=""
				onload="Javascript:SetWinHeight(this)"
				src="
   time_bet.php?type=
	<?php
        echo $_GET['type'];
        echo "&fc_type=";
        echo $_GET['type'];
        echo "&qishu=";
        echo $_GET['qishu'];
        echo "&gold=";
        echo $_GET['gold'];
        echo "&longhu=";
        echo $_GET['longhu'];
        echo "&style=" . $_GET['style'] . "&leixing_1=" . $_GET['leixing'];
        foreach ($datas as $k => $v) {
            $ball = explode('_', $k);
            if ($ball[0] == 'ball') {
                echo "&" . $k . "=" . $v;
            }
        }
        ?>
    "></iframe>
		</div>

	<?php
    } else
        if (! empty($_GET['title_3d'])) {
            $url;
            if ($_GET['title_3d'] == '连码' || $_GET['title_3d'] == '生肖连' || $_GET['title_3d'] == '尾数连' || $_GET['title_3d'] == '全不中') {
                $url = 'liuhecai.php';
            } else {
                $url = 'time_bet.php';
            }

            ?>
		<!-- 点击下注跳转___六合彩 -->

		<div id="bet_div" name="bet_div" style="display: <?=$bet_show ?>;">

			<iframe id="bet_order_frame" width="216" name="bet_order_frame"
				scrolling="NO" frameborder="NO" border="0" height=""
				onload="Javascript:SetWinHeight(this)"
				src="
   <?=$url ?>?type=
	<?php
            echo $_GET['title_3d'];
            echo "&fc_type=";
            echo $_GET['fc_type'];
            echo "&ids=";
            echo $_GET['ids'];
            echo "&action=";
            echo $_GET['action'];
            echo "&class2=";
            echo $_GET['class2'];
            echo "&xiazhu_money=";
            echo $_GET['xiazhu_money'];
            echo "&active=shuang2";
            echo "&rrtype=";
            echo $_GET['rrtype'];
            echo "&rtype=";
            echo $_GET['rtype'];
            echo "&pabc2=";
            echo $_GET['pabc2'];
            echo "&pabc=";
            echo $_GET['pabc'];
            echo "&pan1=";
            echo $_GET['pan1'];
            echo "&pan2=";
            echo $_GET['pan2'];
            echo "&pan3=";
            echo $_GET['pan3'];
            echo "&pan4=";
            echo $_GET['pan4'];
            echo "&jq=";
            echo $_GET['jq'];
            echo "&min_bl=";
            echo $_GET['min_bl'];
            echo "&r_init=";
            echo $_GET['r_init'];
            echo "&Num_1=";
            echo $_GET['Num_1'];
            echo "&gold_all=";
            echo $_GET['gold_all'];
            echo $_POST['gold_all'];
            echo "&style=" . $_GET['style'] . "&leixing_1=" . $_GET['leixing'];
            if ($_GET['title_3d'] == '过关') {
                foreach ($datas as $k => $v) {
                    $class = explode('game', $k);
                    if ($class[1] != '') {

                        echo "&" . $k . "=" . $v;
                    }
                }
            } elseif ($_GET['title_3d'] == '连码' || $_GET['title_3d'] == '生肖连') {
                foreach ($datas as $k => $v) {
                    $class = explode('num', $k);
                    if ($class[1] != '') {

                        echo "&" . $k . "=" . $v;
                    }
                }
                foreach ($datas as $k => $v) {
                    $class = explode('dm', $k);
                    if ($class[1] != '') {

                        echo "&" . $k . "=" . $v;
                    }
                }
            } elseif ($_GET['title_3d'] == '生肖' || $_GET['title_3d'] == '生肖连' || $_GET['title_3d'] == '全不中' || $_GET['title_3d'] == '尾数连' || $_GET['title_3d'] == '半波') {
                if ($_GET['action'] == 'n1') {

                    foreach ($datas as $k => $v) {
                        $class = explode('_', $k);
                        if ($class[0] == 'Num') {
                            $class11 = 'class3_' . $class[1];
                            $class33 = $_GET[$class11];
                            echo '&class3_' . $class[1] . '=' . $class33;

                            echo "&" . $k . "=" . $v;
                        }
                    }
                } else {
                    foreach ($datas as $k => $v) {
                        $class = explode('num', $k);
                        if ($class[1] != '') {

                            echo "&" . $k . "=" . $v;
                        }
                    }
                }
            } else {
                if ($_GET['title_3d'] == '正1-6') {
                    foreach ($datas1 as $k => $v) {
                        $class = explode('_', $k);
                        if ($class[0] == 'Num') {
                            $calss11 = 'class3_' . $class[1];
                            $class33 = $_POST[$calss11];
                            echo '&class3_' . $class[1] . '=' . $class33;

                            echo "&" . $k . "=" . $v;
                        }
                    }
                } else {
                    foreach ($datas as $k => $v) {
                        $class = explode('_', $k);
                        if ($class[0] == 'Num') {
                            $calss11 = 'class3_' . $class[1];
                            $class33 = $_GET[$calss11];
                            echo '&class3_' . $class[1] . '=' . $class33;
                            // class3_11 = 11;
                            echo "&" . $k . "=" . $v;
                        }
                    }
                }
            }
            ?>
    "></iframe>
		</div>
	<?php } ?>
<div id="rec5_div" name="rec5_div" style="display: none;">
			<iframe id="rec_frame" width="216" src="" name="rec_frame"
				scrolling="NO" frameborder="NO" border="0" height=""
				onload="Javascript:SetWinHeight_1(this)"></iframe>
		</div>
		<div id="rec_div" style="display: none">
			<div class="ord">
				<script type="text/javascript">
                    function re_load(){
                    	window.location.href=window.location;
                    }
                    </script>
				<div class="title">
					<h1></h1>
					<div class="tiTimer" onclick="re_load();"></div>
				</div>
				<div class="show_info">
					<div class="ord">
			<?php
if (! empty($bet_result)) {
    foreach ($bet_result as $k => $v) {

        ?>
			<div class="show">
				<div class="tname">
					<em>			<?=$v['type'] ?><?=$v['class1'] ?>-	<?=$v['mingxi_1'] ?><?=$v['class2'] ?>:<?=$v['mingxi_2'] ?><?=$v['class3'] ?></em>
					@ <font color="#CC0000"><?=$v['odds'] ?><?=$v['rate'] ?></font><br>
					<b class="gold"><span class="fin_gold">RMB&nbsp;<b><?=$v['money'] ?><?=$v['sum_m'] ?></b></span></b>
				</div>
			</div>
			<?php }} ?>
		</div>
		<?php if (empty($bet_result)) { ?>

		您没有未结算的交易。
		<?php } ?>
      </div>
	</div>
	</div>
	</div>
	<div id="info_div" name="info_div" style="display: <?=$info_show ?>">
		<div class="msgHead">
			<h2>公告</h2>
			<a href="#" onclick="showMoreMsg()" class="btnGreen">更多</a> <span
				class="clearfix"></span>
		</div>
		<div class="msgCon clearfix">
			<marquee scrollamount="2" direction="left" onmouseover="this.stop();"
				onmouseout="this.start();">
			<?=$notice['notice_content']?>
	</marquee>
		</div>
	</div>
<?
switch ($_GET['type_y']) {
    case '1':
        $type = '3D';
        break;
    case '2':
        $type = 'pl3';
        break;
    case '3':
        $type = 'Cqss';
        break;
    case '9':
        $type = 'Shss';
        break;
    case '10':
        $type = 'Tjss';
        break;
    case '11':
        $type = 'Jxss';
        break;
    case '12':
        $type = 'Xjss';
        break;
    case '13':
        $type = 'jsk3';
        break;
    case '14':
        $type = 'jlk3';
        break;
    case '4':
        $type = 'kl8';
        break;
    case '5':
        $type = 'pk10';
        break;
    case '6':
        $type = 'lhc';
        break;
    case '7':
        $type = 'Gdsf';
        break;
    case '8':
        $type = 'Cqsf';
        break;
}

if (($_GET['type_y'] == 1 || $_GET['type_y'] == 2) && $result) {

    ?>
	<div id="recRoad" style="display: <?=$recRoad_show ?> ">
		<dl>
			<dt style="color: #fff;">
				<a target="_blank" href="result.php?type=<?=$type ?>">开奖结果</a>
			</dt>
			<dd id="tmdh_dd">
				<table class="game_table" cellspacing="1" cellpadding="0" border="0">
					<tbody>
						<tr class="tbtitle" height="30">
							<td colspan="18">
							<input class="button_a show_3d" type="button" style="height: 23px" value="号码" show_3d='1'>
							<input class="button_a show_3d" type="button" style="height: 23px" value="大小"	show_3d='2'>
							<input class="button_a show_3d" type="button" style="height: 23px" value="单双" show_3d='3'>
							<input class="button_a show_3d" type="button" value="质合" style="height: 23px"
							 show_3d='4'></td>
						</tr>
						<tr class="tbtitle">
							<td style="width: 30px">期数</td>
							<td num="4" set="true">百</td>
							<td num="2" set="true">十</td>
							<td num="4" set="true">个</td>
						</tr>

<?foreach($result   as $k =>$v){?>
	<tr class="tbtitle" id="3d_show_result_1_<?=$k ?>" style="display:;">
		<td class="ball_ff" style="width: 30px; height: 15px;"><?=$v['qishu']?></td>
		<td num="2" set="true"><?=$v['ball_1']?></td>
		<td num="2" set="true"><?=$v['ball_2']?></td>
		<td num="4" set="true"><?=$v['ball_3']?></td>
	</tr>

<?}?>
<?foreach($result   as $k =>$v){?>
	<tr class="tbtitle" id="3d_show_result_2_<?=$k ?>" style="display: none;">
		<td class="ball_ff" style="width: 30px"><?=$v['qishu']?></td>
		<td num="2" set="true"><?php if($v['ball_1']<5){echo '<span style="color:blue;">小</span>';}else{echo '<span style="color:#f00;">大</span>';}?></td>
		<td num="2" set="true"><?php if($v['ball_2']<5){echo '<span style="color:blue;">小</span>';}else{echo '<span style="color:#f00;">大</span>';}?></td>
		<td num="4" set="true"><?php if($v['ball_3']<5){echo '<span style="color:blue;">小</span>';}else{echo '<span style="color:#f00;">大</span>';}?></td>
	</tr>

<?}?>
<?foreach($result   as $k =>$v){?>
	<tr class="tbtitle" id="3d_show_result_3_<?=$k ?>"	style="display: none;">
		<td class="ball_ff" style="width: 30px"><?=$v['qishu']?></td>
		<td num="2" set="true"><?php if($v['ball_1']%2 == 1){echo '<span style="color:blue;">单</span>';}else{echo '<span style="color:#f00;">双</span>';}?></td>
		<td num="2" set="true"><?php if($v['ball_2']%2 == 1){echo '<span style="color:blue;">单</span>';}else{echo '<span style="color:#f00;">双</span>';}?></td>
		<td num="4" set="true"><?php if($v['ball_3']%2 == 1){echo '<span style="color:blue;">单</span>';}else{echo '<span style="color:#f00;">双</span>';}?></td>
	</tr>
<?}?>
<?php foreach($result   as $k =>$v){?>
	<tr class="tbtitle" id="3d_show_result_4_<?=$k ?>"	style="display: none;">
		<td class="ball_ff" style="width: 30px"><?=$v['qishu']?></td>
		<td num="2" set="true"><?php echo isPrime($v['ball_1']); ?></td>
		<td num="2" set="true"><?php echo isPrime($v['ball_2']); ?></td>
		<td num="4" set="true"><?php echo isPrime($v['ball_3']); ?></td>
	</tr>
<?}?>

<?}elseif($_GET['type_y']==3 ||$_GET['type_y']==9||$_GET['type_y']==10||$_GET['type_y']==11||$_GET['type_y']==12 && $result){ ?>
	<div id="recRoad" style="display: <?=$recRoad_show ?> ">
		<dl>
			<dt style="color: #fff;">
				<a target="_blank" href="result.php?type=<?=$type ?>">开奖结果</a>
			</dt>
			<dd id="tmdh_dd">
	<table class="game_table" cellspacing="1" cellpadding="0"
		border="0">
		<tbody>
			<tr class="tbtitle" height="30">
				<td colspan="18">
				<input class="button_a show_ssc" type="button" style="height: 23px" value="号码" show_ssc='1'>
				<input	class="button_a show_ssc" type="button" style="height: 23px"	value="大小" show_ssc='2'>
				<input class="button_a show_ssc"	type="button" style="height: 23px" value="单双" show_ssc='3'>
				<input class="button_a show_ssc" type="button" value="质合"
					style="height: 23px" show_ssc='4'></td>
			</tr>
			<tr class="tbtitle">
				<td style="width: 30px">期数</td>
				<td num="4" set="true">万</td>
				<td num="2" set="true">千</td>
				<td num="4" set="true">百</td>
				<td num="2" set="true">十</td>
				<td num="4" set="true">个</td>
			</tr>
<?foreach($result as $k =>$v){?>
	<tr class="tbtitle" id="ssc_show_result_1_<?=$k ?>" style="display:;">
		<td class="ball_ff" style="width: 30px; height: 15px;"><?=$v['qishu']?></td>
		<td num="2" set="true"><?=$v['ball_1']?></td>
		<td num="2" set="true"><?=$v['ball_2']?></td>
		<td num="2" set="true"><?=$v['ball_3']?></td>
		<td num="2" set="true"><?=$v['ball_4']?></td>
		<td num="4" set="true"><?=$v['ball_5']?></td>
	</tr>
<?}?>

<?foreach($result as $k =>$v){?>
<tr class="tbtitle" id="ssc_show_result_2_<?=$k ?>"	style="display: none;">
	<td class="ball_ff" style="width: 30px"><?=$v['qishu']?></td>
	<td num="2" set="true"><?php if($v['ball_1']<5){echo '<span style="color:blue;">小</span>';}else{echo '<span style="color:#f00;">大</span>';}?></td>
	<td num="2" set="true"><?php if($v['ball_2']<5){echo '<span style="color:blue;">小</span>';}else{echo '<span style="color:#f00;">大</span>';}?></td>
	<td num="2" set="true"><?php if($v['ball_3']<5){echo '<span style="color:blue;">小</span>';}else{echo '<span style="color:#f00;">大</span>';}?></td>
	<td num="2" set="true"><?php if($v['ball_4']<5){echo '<span style="color:blue;">小</span>';}else{echo '<span style="color:#f00;">大</span>';}?></td>
	<td num="4" set="true"><?php if($v['ball_5']<5){echo '<span style="color:blue;">小</span>';}else{echo '<span style="color:#f00;">大</span>';}?></td>
</tr>
<?}?>

<?foreach($result as $k =>$v){?>
<tr class="tbtitle" id="ssc_show_result_3_<?=$k ?>"	style="display: none;">
	<td class="ball_ff" style="width: 30px"><?=$v['qishu']?></td>
	<td num="2" set="true"><?php if($v['ball_1']%2 == 1){echo '<span style="color:blue;">单</span>';}else{echo '<span style="color:#f00;">双</span>';}?></td>
	<td num="2" set="true"><?php if($v['ball_2']%2 == 1){echo '<span style="color:blue;">单</span>';}else{echo '<span style="color:#f00;">双</span>';}?></td>
	<td num="2" set="true"><?php if($v['ball_3']%2 == 1){echo '<span style="color:blue;">单</span>';}else{echo '<span style="color:#f00;">双</span>';}?></td>
	<td num="2" set="true"><?php if($v['ball_4']%2 == 1){echo '<span style="color:blue;">单</span>';}else{echo '<span style="color:#f00;">双</span>';}?></td>
	<td num="4" set="true"><?php if($v['ball_5']%2 == 1){echo '<span style="color:blue;">单</span>';}else{echo '<span style="color:#f00;">双</span>';}?></td>
</tr>
<?}?>

<?foreach($result   as $k =>$v){?>
	<tr class="tbtitle" id="ssc_show_result_4_<?=$k ?>"	style="display: none;">
	<td class="ball_ff" style="width: 30px"><?=$v['qishu']?></td>
	<td num="2" set="true"><?php echo isPrime($v['ball_1']); ?></td>
	<td num="2" set="true"><?php echo isPrime($v['ball_2']); ?></td>
	<td num="2" set="true"><?php echo isPrime($v['ball_3']); ?></td>
	<td num="2" set="true"><?php echo isPrime($v['ball_4']); ?></td>
	<td num="4" set="true"><?php echo isPrime($v['ball_5']); ?></td>
</tr>
<?}?>

<?
} elseif ($_GET['type_y'] == 6 && $result) {
    $arr1 = array(01, 02, 07, 08, 12, 13, 18, 19, 23, 24, 29, 30, 34, 35, 40, 45, 46 ); // 红

    $arr2 = array(03, 04, 09, 10, 14, 15, 20, 25, 26, 31, 36, 37, 41, 42, 47, 48 ); // 蓝

    $arr3 = array(05, 06, 11, 16, 17, 21, 22, 27, 28, 32, 33, 38, 39, 43, 44, 49 ); // 绿
?>
<div id="recRoad">
	<dl>
		<dt>
			<a id="tmdh" class="a_style recRoad_c"
				href="javascript://">特码单号</a>
		</dt>
		<dd id="tmdh_dd">
			<table class="game_table" cellspacing="1" cellpadding="0"
				border="0">
				<tbody>
					<tr class="tbtitle" height="20">
	  <?php

    foreach ($result as $k => $v) {
        if ($k <= 9) {
            if (in_array($v['na'], $arr1)) {
                $v['style'] = "ball_r";
            } elseif (in_array($v['na'], $arr2)) {
                $v['style'] = "ball_b";
            } elseif (in_array($v['na'], $arr3)) {
                $v['style'] = "ball_g";
            }
            ?>
			<td><?=$v['nn'] ?></td>
			 <td class="<?=$v['style'] ?>" width="20"><?=$v['na'] ?></td>
	         <?
            if ($k % 2 != 0 && $k != 9) {
                ?></tr>
				<tr class="tbtitle" height="20"><?
            }
        }
    }
    ?>
		</tr>
			</tbody>
				</table>
				</dd>
				<dt>
					<a id="tmdx" class="a_style" href="javascript://">特码大小</a>
				</dt>
				<dd id="tmdx_dd" style="display: none;">
				<table class="game_table" cellspacing="1" cellpadding="0"
					border="0">
				<tbody>
		<?php

    include ("include/show_zoushi_func.php");
    $list = json_encode(louzoushi($result, 'tmdx'));
    $list1 = json_encode(louzoushi($result, 'tmds'));
    $list2 = json_encode(louzoushi($result, 'tmt'));

    ?>
<script type="text/javascript">
var data_str = <?=$list?>;
data_str= JSON.stringify(data_str);
data_str = data_str.replace(/\",\"/g, '\],\[');
data_str = data_str.replace(/\"\]/g, '\]\]');
data_str = data_str.replace(/\[\"/g, '\[\[');
 data = eval("(" + data_str + ")");  // 转换为json对象

var data_str1 = <?=$list1?>;
data_str1= JSON.stringify(data_str1);
data_str1 = data_str1.replace(/\",\"/g, '\],\[');
data_str1 = data_str1.replace(/\"\]/g, '\]\]');
data_str1 = data_str1.replace(/\[\"/g, '\[\[');
data1 = eval("(" + data_str1 + ")");  // 转换为json对象

 var data_str2 = <?=$list2?>;
 data_str2= JSON.stringify(data_str2);
 data_str2 = data_str2.replace(/\",\"/g, '\],\[');
 data_str2 = data_str2.replace(/\"\]/g, '\]\]');
 data_str2 = data_str2.replace(/\[\"/g, '\[\[');
 data2 = eval("(" + data_str2 + ")");  // 转换为json对象
$(document).ready(function(){
	show_map('tmdx','item',data);
	show_map('tmds','item1',data1);
	show_map('tmt','item2',data2);
});
function show_map(type,id,data){
		if(data[type]){
			$.each(data[type], function(j,rs){
				$.each(rs, function(i,item){
					//alert(item);
					var val = '';
					var color = '';
					if(type == 'tmdx'){
						if(item==1){
							val = '大';
							color = 'red';
						}else{
							val = '小';
							color = 'blue';
						}
					}else if(type == 'tmds'){
						if(item==1){
							val = '单';
							color = 'red';
						}else{
							val = '双';
							color = 'blue';
						}
					}else if(type == 'tmt'){
						if(item==1){
							val = '小单';
							color = 'red';
						}else if(item==2){
							val = '小双';
							color = 'blue';
						}else if(item==3){
							val = '大单';
							color = 'blue';
						}else if(item==4){
							val = '大双';
							color = 'blue';
						}
					}
					$('#'+id+'_'+j+'_'+i).html('<font color="'+color+'">'+val+'</font>');
				});
			});
		}

}

</script>
	<tr class="tbtitle" height="20px">
	<td id="item_0_0"><font color="red"></font></td>
	<td id="item_1_0"><font color="blue"></font></td>
	<td id="item_2_0"><font color="red"></font></td>
	<td id="item_3_0"></td>
	<td id="item_4_0"><font color="red"></font></td>
	<td id="item_5_0"><font color="blue"></font></td>
	<td id="item_6_0"><font color="red"></font></td>
</tr>
<tr class="tbtitle" height="20px">
	<td id="item_0_1"><font color="red"></font></td>
	<td id="item_1_1"><font color="blue"></font></td>
	<td id="item_2_1"><font color="red"></font></td>
	<td id="item_3_1"></td>
	<td id="item_4_1"><font color="red"></font></td>
	<td id="item_5_1"><font color="blue"></font></td>
	<td id="item_6_1"><font color="red"></font></td>
</tr>
<tr class="tbtitle" height="20px">
	<td id="item_0_2"><font color="red"></font></td>
	<td id="item_1_2"><font color="blue"></font></td>
	<td id="item_2_2"><font color="red"></font></td>
	<td id="item_3_2"></td>
	<td id="item_4_2"><font color="red"></font></td>
	<td id="item_5_2"><font color="blue"></font></td>
	<td id="item_6_2"><font color="red"></font></td>
</tr>
<tr class="tbtitle" height="20px">
	<td id="item_0_3"><font color="red"></font></td>
	<td id="item_1_3"><font color="blue"></font></td>
	<td id="item_2_3"><font color="red"></font></td>
	<td id="item_3_3"></td>
	<td id="item_4_3"><font color="red"></font></td>
	<td id="item_5_3"><font color="blue"></font></td>
	<td id="item_6_3"><font color="red"></font></td>
</tr>
</tbody>
</table>
</dd>
	<dt>
		<a id="tmds" class="a_style" href="javascript://">特码单双</a>
	</dt>
	<dd id="tmds_dd" style="display: none;">
	<table class="game_table" cellspacing="1" cellpadding="0"	border="0">
	<tbody>
<tr class="tbtitle" height="20px">
	<td id="item1_0_0"><font color="red"></font></td>
	<td id="item1_1_0"><font color="blue"></font></td>
				<td id="item1_2_0"><font color="red"></font></td>
				<td id="item1_3_0"></td>
				<td id="item1_4_0"><font color="red"></font></td>
				<td id="item1_5_0"><font color="blue"></font></td>
				<td id="item1_6_0"><font color="red"></font></td>
			</tr>
			<tr class="tbtitle" height="20px">
				<td id="item1_0_1"><font color="red"></font></td>
				<td id="item1_1_1"><font color="blue"></font></td>
				<td id="item1_2_1"><font color="red"></font></td>
				<td id="item1_3_1"></td>
				<td id="item1_4_1"><font color="red"></font></td>
				<td id="item1_5_1"><font color="blue"></font></td>
				<td id="item1_6_1"><font color="red"></font></td>
			</tr>
			<tr class="tbtitle" height="20px">
				<td id="item1_0_2"><font color="red"></font></td>
				<td id="item1_1_2"><font color="blue"></font></td>
				<td id="item1_2_2"><font color="red"></font></td>
				<td id="item1_3_2"></td>
				<td id="item1_4_2"><font color="red"></font></td>
				<td id="item1_5_2"><font color="blue"></font></td>
				<td id="item1_6_2"><font color="red"></font></td>
			</tr>
			<tr class="tbtitle" height="20px">
				<td id="item1_0_3"><font color="red"></font></td>
				<td id="item1_1_3"><font color="blue"></font></td>
				<td id="item1_2_3"><font color="red"></font></td>
				<td id="item1_3_3"></td>
				<td id="item1_4_3"><font color="red"></font></td>
				<td id="item1_5_3"><font color="blue"></font></td>
				<td id="item1_6_3"><font color="red"></font></td>
			</tr>
		</tbody>
	</table>
</dd>
<dt>
	<a id="tmt" class="a_style" href="javascript://">特码特</a>
</dt>
<dd id="tmt_dd" style="display: none;">
	<table class="game_table" cellspacing="1" cellpadding="0"
		border="0">
		<tbody>
			<tr class="tbtitle" height="20px">
				<td id="item2_0_0"><font color="red"></font></td>
				<td id="item2_1_0"><font color="blue"></font></td>
				<td id="item2_2_0"><font color="red"></font></td>
				<td id="item2_3_0"></td>
				<td id="item2_4_0"><font color="red"></font></td>
				<td id="item2_5_0"><font color="blue"></font></td>
				<td id="item2_6_0"><font color="red"></font></td>
			</tr>
			<tr class="tbtitle" height="20px">
				<td id="item2_0_1"><font color="red"></font></td>
				<td id="item2_1_1"><font color="blue"></font></td>
				<td id="item2_2_1"><font color="red"></font></td>
				<td id="item2_3_1"></td>
				<td id="item2_4_1"><font color="red"></font></td>
				<td id="item2_5_1"><font color="blue"></font></td>
				<td id="item2_6_1"><font color="red"></font></td>
			</tr>
			<tr class="tbtitle" height="20px">
				<td id="item2_0_2"><font color="red"></font></td>
				<td id="item2_1_2"><font color="blue"></font></td>
				<td id="item2_2_2"><font color="red"></font></td>
				<td id="item2_3_2"></td>
				<td id="item2_4_2"><font color="red"></font></td>
				<td id="item2_5_2"><font color="blue"></font></td>
				<td id="item2_6_2"><font color="red"></font></td>
			</tr>
			<tr class="tbtitle" height="20px">
				<td id="item2_0_3"><font color="red"></font></td>
				<td id="item2_1_3"><font color="blue"></font></td>
				<td id="item2_2_3"><font color="red"></font></td>
				<td id="item2_3_3"></td>
				<td id="item2_4_3"><font color="red"></font></td>
				<td id="item2_5_3"><font color="blue"></font></td>
				<td id="item2_6_3"><font color="red"></font></td>
			</tr>
		</tbody>
	</table>
</dd>

</dl>
</div>
<script type="text/javascript">
	top.lx='0';
	$(document).ready(function(e) {
	$("#recRoad dl dd").not($("#tmdh_dd")).hide();
	$("#tmdh").toggleClass("recRoad_c");
	$("#recRoad dl dt").click(
	function () {
	$(this).find("a").addClass("recRoad_c");
	$("#recRoad dl dt a").not($(this).find("a")).removeClass("recRoad_c");
	$("#"+$(this).find("a").attr("id")+"_dd").slideDown("fast");

	$("#recRoad dl dd").not($("#"+$(this).find("a").attr("id")+"_dd")).slideUp("fast");
	}
	);
	});
</script>
<?}?>
</tbody>
</table>
</dd>
</dl>
</div>

</body>
</html>