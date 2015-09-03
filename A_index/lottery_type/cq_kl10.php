<?php
include_once ("../include/config.php");
include ("../include/public_config.php");


include ("./include/function.php");
$style = 'cq_ten';
$publicdb = $db_config;
// 重庆快乐十分赔率读取
if (! empty($_GET['type'])) {
    $cq_kl10_odds = M('c_odds_4', $db_config)->where("type='" . $_GET['type'] . "'")->find();
    switch ($_GET['type']) {
        case 'ball_1':
            $title_3d = '第一球';
            break;
        case 'ball_2':
            $title_3d = '第二球';
            break;
        case 'ball_3':
            $title_3d = '第三球';
            break;
        case 'ball_4':
            $title_3d = '第四球';
            break;
        case 'ball_5':
            $title_3d = '第五球';
            break;
        case 'ball_6':
            $title_3d = '第六球';
            break;
        case 'ball_7':
            $title_3d = '第七球';
            break;
        case 'ball_8':
            $title_3d = '第八球';
            break;
        case 'ball_9':
            $title_3d = '總和,龍虎';
            break;
    }
    $is_lock = M("fc_games_type",$db_config)->field("*")->where("state = 0 and fc_type = '" . $title_3d . "' and wanfa = '" . $style . "'")->select();
    //p($is_lock);
	if($is_lock){
		echo '<script type="text/javascript">alert("玩法维护中，请选择其他玩法");history.go(-1);</script>';
		exit;
	}
}
//查当前玩法已下注的总额
$beted = beted_limit(4,$title_3d,$db_config);
$ball_limit_num = $beted['sum(money)'];
// 查询期数,中奖号

$qishu = M('c_auto_4', $db_config);
$num = $qishu->field("*")
    ->order("datetime desc")
    ->find();
//获取开盘时间，封盘时间开盘状态判断时间，封盘状态判断时间的数组
$array = set_arraypan(4,$db_config);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>webcom</title>
<link rel="stylesheet" href="public/css/reset.css" type="text/css">
<link rel="stylesheet" href="public/css/xp.css" type="text/css">
<link rel="stylesheet" href="public/css/default.css" type="text/css">
<script src="public/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="public/js/bet.js" type="text/javascript"></script>

<script type="text/javascript">
var o_stime = <?=$array['o_t_stro']?>;
var f_stime = <?=$array['f_t_stro']?>;
</script>
<?php  include("./include/common_limit.php");  ?>
<script src="public/js/orderFunc.js" type="text/javascript"></script>
</head>
<body marginwidth="3" marginheight="3" id="HOP"
	ondragstart="window.event.returnValue=false"
	oncontextmenu="window.event.returnValue=false"
	onselectstart="event.returnValue=false">
	<div id="title_3d" style="display: none"><?=$title_3d?></div>
	<div id="style" style="display: none"><?=$style?></div>
	<div id="fc_type" style="display: none">4</div>

	<div id="kq_box_001" style="display: none;">
		<div id="kq_box_num" style="margin: 50px auto 0">
			距离 第 <b><font color="#7c3838">
            <?=dq_qishu(4,$publicdb)?>
        </font></b> 期 开盘时间还有：<font color="#7AFF00"><strong><span
					id="close_time" style="float: none; display: inline"></span></strong>

		</div>
	</div>

	<div class="wrapCss_004" style="display: block;" id="all_body">
<?php
if (strtotime($array['c_time']) > strtotime($array['o_state']) && strtotime($array['c_time']) < strtotime($array['f_state'])) {
    echo '<script>document.getElementById("all_body").style.display="block";document.getElementById("kq_box_001").style.display="none";var f_o_state = 1;</script>';
} else {
    // 封盘时切换显示
    echo '<script>document.getElementById("all_body").style.display="none";document.getElementById("kq_box_001").style.display="block";var f_o_state = 2;</script>';
}
?>

<script type="text/javascript">

	setInterval(function(){lottery(4,8,<?=$num['qishu']?>);},30000);


</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0"
			class="tab_001">
			<tbody>
				<tr
					style="background: url(public/images/title_bg_004.gif) repeat-x left top">
					<td><span class="font_sty001"><strong><?=$title_3d?>下注</strong></span>
					</td>
					<td conspan="2">
						<ul id ="lottery"
							style="padding: 3px 0 0 0; margin: 0px; list-style: none; float: right">
							<li style="float: left; height: 26px; line-height: 26px">第 <b><font
									class="colorDate"><?=$num['qishu'] ?></font></b> 期开奖结果：
							</li>
							<li align="right" class="kjjg_li"><?=$num['ball_1'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_2'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_3'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_4'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_5'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_6'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_7'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_8'] ?></li>
						</ul>
					</td>
				</tr>

			</tbody>
		</table>

		<form autocomplete="off" name="orders" id="order_form" method="get"
			target="k_meml" action="main_left.php"
			onsubmit="return check_submit();">
			<input type="hidden" name="type" value="4"> <input type="hidden"
				name="qishu"
				value="<?=dq_qishu(4,$publicdb)?>">
			<table width="640" border="0" cellpadding="0" cellspacing="1"
				class="game_table">
				<tbody>
					<tr class="tbtitle">
						<td align="left" colspan="15" height="25" id="time_td"><font>当前期数：<font
								class="colorDate"><b>
      <?=dq_qishu(4,$publicdb)?>
      </b></font> 期&nbsp;&nbsp;&nbsp;&nbsp;下注金额：<span id="allgold">0</span></font>
							<span class="STYLE1">距离封盘时间：</span> <span id="time_over"
							style="color: #7AFF00; font-size: bold;"></span>
						<!-- 时间储存 --> <script language="javascript">

  </script></td>
					</tr>
					<tr class="hsetEm">
						<td colspan="15" class="tbtitle"></td>
					</tr>
					<tr class="hset">
						<th colspan="15" class="tbtitle4"><div
								style="float: left; line-height: 30px; text-align: center; width: 100%"
								id="title_3d"><?=$title_3d?></div></th>
					</tr>
					<tr class="tbtitle2">
						<td>号码</td>
						<td>赔率</td>
						<td>金额</td>
						<td>号码</td>
						<td>赔率</td>
						<td>金额</td>
						<td>号码</td>
						<td>赔率</td>
						<td>金额</td>
						<td>号码</td>
						<td>赔率</td>
						<td>金额</td>
				<?php if($_GET['type'] != 'ball_9'){ ?>
						<td>号码</td>
						<td>赔率</td>
						<td>金额</td>
				<?php } ?>
					</tr>
    <?php

if ($_GET['type'] == 'ball_1' || $_GET['type'] == 'ball_2' || $_GET['type'] == 'ball_3' || $_GET['type'] == 'ball_4' || $_GET['type'] == 'ball_5' || $_GET['type'] == 'ball_6' || $_GET['type'] == 'ball_7' || $_GET['type'] == 'ball_8') {
        ?>

    <tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">01</span></td>
						<td class="ball_ff"><span rate="true" id="bl_1"><?=$cq_kl10_odds['h1']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_1"
							name="<?=$_GET['type'] ?>_1"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">02</span></td>
						<td class="ball_ff"><span rate="true" id="bl_2"><?=$cq_kl10_odds['h2']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_2"
							name="<?=$_GET['type']?>_2"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">03</span></td>
						<td class="ball_ff"><span rate="true" id="bl_3"><?=$cq_kl10_odds['h3']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_3"
							name="<?=$_GET['type'] ?>_3"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">04</span></td>
						<td class="ball_ff"><span rate="true" id="bl_4"><?=$cq_kl10_odds['h4']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_4"
							name="<?=$_GET['type'] ?>_4"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">05</span></td>
						<td class="ball_ff"><span rate="true" id="bl_5"><?=$cq_kl10_odds['h5']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_5"
							name="<?=$_GET['type'] ?>_5"></td>
					</tr>

					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">06</span></td>
						<td class="ball_ff"><span rate="true" id="bl_6"><?=$cq_kl10_odds['h6']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_6"
							name="<?=$_GET['type'] ?>_6"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">07</span></td>
						<td class="ball_ff"><span rate="true" id="bl_7"><?=$cq_kl10_odds['h7']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_7"
							name="<?=$_GET['type'] ?>_7"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">08</span></td>
						<td class="ball_ff"><span rate="true" id="bl_8"><?=$cq_kl10_odds['h8']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_8"
							name="<?=$_GET['type'] ?>_8"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">09</span></td>
						<td class="ball_ff"><span rate="true" id="bl_9"><?=$cq_kl10_odds['h9']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_9"
							name="<?=$_GET['type'] ?>_9"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">10</span></td>
						<td class="ball_ff"><span rate="true" id="bl_10"><?=$cq_kl10_odds['h10']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_10"
							name="<?=$_GET['type'] ?>_10"></td>
					</tr>

					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">11</span></td>
						<td class="ball_ff"><span rate="true" id="bl_11"><?=$cq_kl10_odds['h11']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_11"
							name="<?=$_GET['type'] ?>_11"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">12</span></td>
						<td class="ball_ff"><span rate="true" id="bl_12"><?=$cq_kl10_odds['h12']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_12"
							name="<?=$_GET['type'] ?>_12"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">13</span></td>
						<td class="ball_ff"><span rate="true" id="bl_13"><?=$cq_kl10_odds['h13']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_13"
							name="<?=$_GET['type'] ?>_13"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">14</span></td>
						<td class="ball_ff"><span rate="true" id="bl_14"><?=$cq_kl10_odds['h14']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_14"
							name="<?=$_GET['type'] ?>_14"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">15</span></td>
						<td class="ball_ff"><span rate="true" id="bl_15"><?=$cq_kl10_odds['h15']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_15"
							name="<?=$_GET['type'] ?>_15"></td>
					</tr>

					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">16</span></td>
						<td class="ball_ff"><span rate="true" id="bl_16"><?=$cq_kl10_odds['h16']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_16"
							name="<?=$_GET['type'] ?>_16"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">17</span></td>
						<td class="ball_ff"><span rate="true" id="bl_17"><?=$cq_kl10_odds['h17']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_17"
							name="<?=$_GET['type'] ?>_17"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjg_li">18</span></td>
						<td class="ball_ff"><span rate="true" id="bl_18"><?=$cq_kl10_odds['h18']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_18"
							name="<?=$_GET['type'] ?>_18"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjgr_li"
							style="background-image: url(public/images/r.png)">19</span></td>
						<td class="ball_ff"><span rate="true" id="bl_19"><?=$cq_kl10_odds['h19']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_19"
							name="<?=$_GET['type'] ?>_19"></td>

						<td class="ball_bg" align="center" valign="middle"><span
							class="kjjgr_li"
							style="background-image: url(public/images/r.png)">20</span></td>
						<td class="ball_ff"><span rate="true" id="bl_20"><?=$cq_kl10_odds['h20']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_20"
							name="<?=$_GET['type'] ?>_20"></td>
					</tr>

					<tr class="tbtitle2">
						<th colspan="15"><div
								style="float: left; line-height: 30px; text-align: center; width: 100%"></div>
						</th>
					</tr>
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle"><span>大</span></td>
						<td class="ball_ff"><span rate="true" id="bl_21"><?=$cq_kl10_odds['h21']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_21"
							name="<?=$_GET['type'] ?>_21"></td>

						<td class="ball_bg" align="center" valign="middle"><span>小</span></td>
						<td class="ball_ff"><span rate="true" id="bl_22"><?=$cq_kl10_odds['h22']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_22"
							name="<?=$_GET['type'] ?>_22"></td>

						<td class="ball_bg" align="center" valign="middle"><span>单</span></td>
						<td class="ball_ff"><span rate="true" id="bl_23"><?=$cq_kl10_odds['h23']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_23"
							name="<?=$_GET['type'] ?>_23"></td>

						<td class="ball_bg" align="center" valign="middle"><span>双</span></td>
						<td class="ball_ff"><span rate="true" id="bl_24"><?=$cq_kl10_odds['h24']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_24"
							name="<?=$_GET['type'] ?>_24"></td>

						<td class="ball_bg" align="center" valign="middle"><span>尾大</span></td>
						<td class="ball_ff"><span rate="true" id="bl_25"><?=$cq_kl10_odds['h25']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_25"
							name="<?=$_GET['type'] ?>_25"></td>
					</tr>

					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle"><span>尾小</span></td>
						<td class="ball_ff"><span rate="true" id="bl_26"><?=$cq_kl10_odds['h26']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_26"
							name="<?=$_GET['type'] ?>_26"></td>

						<td class="ball_bg" align="center" valign="middle"><span>合单</span></td>
						<td class="ball_ff"><span rate="true" id="bl_27"><?=$cq_kl10_odds['h27']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_27"
							name="<?=$_GET['type'] ?>_27"></td>

						<td class="ball_bg" align="center" valign="middle"><span>合双</span></td>
						<td class="ball_ff"><span rate="true" id="bl_28"><?=$cq_kl10_odds['h28']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_28"
							name="<?=$_GET['type'] ?>_28"></td>

						<td class="ball_bg" align="center" valign="middle"><span>东</span></td>
						<td class="ball_ff"><span rate="true" id="bl_29"><?=$cq_kl10_odds['h29']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_29"
							name="<?=$_GET['type'] ?>_29"></td>

						<td class="ball_bg" align="center" valign="middle"><span>南</span></td>
						<td class="ball_ff"><span rate="true" id="bl_30"><?=$cq_kl10_odds['h30']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_30"
							name="<?=$_GET['type'] ?>_30"></td>
					</tr>

					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle"><span>西</span></td>
						<td class="ball_ff"><span rate="true" id="bl_31"><?=$cq_kl10_odds['h31']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_31"
							name="<?=$_GET['type'] ?>_31"></td>

						<td class="ball_bg" align="center" valign="middle"><span>北</span></td>
						<td class="ball_ff"><span rate="true" id="bl_32"><?=$cq_kl10_odds['h32']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_32"
							name="<?=$_GET['type'] ?>_32"></td>

						<td class="ball_bg" align="center" valign="middle"><span>中</span></td>
						<td class="ball_ff"><span rate="true" id="bl_33"><?=$cq_kl10_odds['h33']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_33"
							name="<?=$_GET['type'] ?>_33"></td>

						<td class="ball_bg" align="center" valign="middle"><span>发</span></td>
						<td class="ball_ff"><span rate="true" id="bl_34"><?=$cq_kl10_odds['h34']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_34"
							name="<?=$_GET['type'] ?>_34"></td>

						<td class="ball_bg" align="center" valign="middle"><span>白</span></td>
						<td class="ball_ff"><span rate="true" id="bl_35"><?=$cq_kl10_odds['h35']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_35"
							name="<?=$_GET['type'] ?>_35"></td>
					</tr>
<?php } ?>
<?php if($_GET['type'] =='ball_9'){ ?>

    <tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle"><span>总和大</span></td>
						<td class="ball_ff"><span rate="true" id="bl_21"><?=$cq_kl10_odds['h1']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_21"
							name="<?=$_GET['type'] ?>_1"></td>

						<td class="ball_bg" align="center" valign="middle"><span>总和小</span></td>
						<td class="ball_ff"><span rate="true" id="bl_22"><?=$cq_kl10_odds['h2']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_22"
							name="<?=$_GET['type'] ?>_2"></td>

						<td class="ball_bg" align="center" valign="middle"><span>总和单</span></td>
						<td class="ball_ff"><span rate="true" id="bl_23"><?=$cq_kl10_odds['h3']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_23"
							name="<?=$_GET['type'] ?>_3"></td>

						<td class="ball_bg" align="center" valign="middle"><span>总和双</span></td>
						<td class="ball_ff"><span rate="true" id="bl_24"><?=$cq_kl10_odds['h4']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_24"
							name="<?=$_GET['type'] ?>_4"></td>
					</tr>

					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle"><span>总和尾大</span></td>
						<td class="ball_ff"><span rate="true" id="bl_25"><?=$cq_kl10_odds['h5']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_25"
							name="<?=$_GET['type'] ?>_5"></td>
						<td class="ball_bg" align="center" valign="middle"><span>总和尾小</span></td>
						<td class="ball_ff"><span rate="true" id="bl_26"><?=$cq_kl10_odds['h6']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_26"
							name="<?=$_GET['type'] ?>_6"></td>

						<td class="ball_bg" align="center" valign="middle"><span>龙</span></td>
						<td class="ball_ff"><span rate="true" id="bl_27"><?=$cq_kl10_odds['h7']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_27"
							name="<?=$_GET['type'] ?>_7"></td>

						<td class="ball_bg" align="center" valign="middle"><span>虎</span></td>
						<td class="ball_ff"><span rate="true" id="bl_28"><?=$cq_kl10_odds['h8']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_28"
							name="<?=$_GET['type'] ?>_8"></td>
					</tr>
<?php } ?>
			<tr class="hset2"><td colspan="15" class="tbtitle" >
			<div style="float: right !important">

				<input name="btnSubmit" type="submit" class="button_a" id="btnSubmit" value="下注">
				<input type="reset" onclick="ClearData();" class="button_a"
					name="Submit3" value="重置">
					</div></td>
			</tr>

				</tbody>
			</table>
		</form>
		<iframe src="about:blank" width="100%" height="1" frameborder="0"
			marginheight="1" marginwidth="1"></iframe>
	</div>


	<!-- 快速金额设定 -->
	<script src="./public/js/js.js" type="text/javascript"></script>
	<div class="aui_state_focus" id="aui_state_focus"
		style="position: absolute; left: 300px; top: 90px; width: 220px; z-index: 1987; display: none;">
		<div class="aui_outer">
			<table class="aui_border">
				<tbody>
					<tr>
						<td class="aui_nw"></td>
						<td class="aui_n"></td>
						<td class="aui_ne"></td>
					</tr>
					<tr>
						<td class="aui_w"></td>
						<td class="aui_c"><div class="aui_inner">
								<table class="aui_dialog">
									<tbody>
		<tr>
			<td colspan="2" class="aui_header"><div class="aui_titleBar">
					<div class="aui_title" style="cursor: move;">快速金额设定</div>

					<a class="aui_close"
						onclick="document.getElementById('aui_state_focus').style.display='none'";>×</a>

				</div></td>
		</tr>
		<tr>
			<td class="aui_icon" style="display: none;"><div
					class="aui_iconBg" style="background: none;"></div></td>
			<td class="aui_main" style="width: 200px; height: 250px;"><div
					class="aui_content aui_state_full"
					style="padding: 20px 25px;">
					<div class="aui_loading" style="display: none;">
						<span></span>
					</div>
					<iframe src="./include/set_money.php"
						name="OpenartDialog14271810369650" frameborder="0"
						allowtransparency="true"
						style="width: 100%; height: 280px; border: 0px none;"></iframe>
				</div></td>
		</tr>
		<tr>
			<td colspan="2" class="aui_footer"><div class="aui_buttons"
					style="display: none;"></div></td>
		</tr>
									</tbody>
								</table>
							</div></td>
						<td class="aui_e"></td>
					</tr>
					<tr>
						<td class="aui_sw"></td>
						<td class="aui_s"></td>
						<td class="aui_se" style="cursor: se-resize;"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>