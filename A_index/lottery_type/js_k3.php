<?php
include_once ("../include/config.php");
include ("../include/public_config.php");

include ("./include/function.php");
$publicdb = $db_config;

// 江苏快3赔率读取
$style = 'js_k3';
if (! empty ( $_GET ['type'] )) {
	$fc_3d_odds = M ( 'c_odds_13', $db_config )->where ( "type='" . $_GET ['type'] . "'" )->find ();

	switch ($_GET ['type']) {
		case 'ball_1' :
			$title_3d = '和值';
			$ball = 1;
			break;
		case 'ball_2' :
			$title_3d = '独胆';
			$ball = 2;
			break;
		case 'ball_3' :
			$title_3d = '豹子';
			$ball = 3;
			break;
		case 'ball_4' :
			$title_3d = '两连';
			$ball = 4;
			break;
		case 'ball_5' :
			$title_3d = '对子';
			$ball = 5;
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
$beted = beted_limit(13,$title_3d,$db_config);
$ball_limit_num = $beted['sum(money)'];
// 查询期数,中奖号
$qishu = M ( 'c_auto_13', $db_config );
$num = $qishu->field ( "*" )->order ( "datetime desc" )->find ();

//获取开盘时间，封盘时间开盘状态判断时间，封盘状态判断时间的数组
$array = set_arraypan(13,$db_config);
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
<script src="public/js/fc_3d_order.js" type="text/javascript"></script>
<script src="public/js/bet.js" type="text/javascript"></script>
<?php  include("./include/common_limit.php");  ?>
<script src="public/js/select.js" type="text/javascript"></script>

</head>
<body marginwidth="3" marginheight="3" id="HOP"
	ondragstart="window.event.returnValue=false"
	oncontextmenu="window.event.returnValue=false"
	onselectstart="event.returnValue=false">
	<div id="title_3d" style="display: none"><?=$title_3d?></div>
	<div id="style" style="display: none"><?=$style?></div>
	<div id="fc_type" style="display: none">13</div>
	<script type="text/javascript">
	var o_stime = <?=$array['o_t_stro']?>;
	var f_stime = <?=$array['f_t_stro']?>;
</script>
	<script src="public/js/orderFunc.js" type="text/javascript"></script>
	<div id="kq_box_001" style="display: none;">
		<div id="kq_box_num" style="margin: 50px auto 0">
			距离 第 <b><font color="#7c3838"><?php echo dq_qishu(13,$publicdb);?>  </font></b> 期 开盘时间还有：<font color="#7AFF00"><strong><span
					id="close_time" style="float: none; display: inline"></span></strong>

		</div>
	</div>
<script type="text/javascript">

	setInterval(function(){lottery(13,3,<?=$num['qishu']?>);},30000);


</script>
	<div class="wrapCss_004" style="display: block;" id="all_body">
		<table width="100%" border="0" cellpadding="0" cellspacing="0"
			class="tab_001">
			<tbody>
				<tr
					style="background: url(./public/images/title_bg_004.gif) repeat-x left top">
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

						</ul>
					</td>
				</tr>
			</tbody>
		</table>
<?php
if (strtotime($array['c_time']) > strtotime($array['o_state']) && strtotime($array['c_time']) < strtotime($array['f_state'])) {
    echo '<script>document.getElementById("all_body").style.display="block";document.getElementById("kq_box_001").style.display="none";var f_o_state = 1;</script>';
} else {
    // 封盘时切换显示
    echo '<script>document.getElementById("all_body").style.display="none";document.getElementById("kq_box_001").style.display="block";var f_o_state = 2;</script>';
}
?>

<table border="0" cellpadding="0" cellspacing="1" class="game_table">
			<tbody>
				<tr class="tbtitle">
					<td colspan="15" id="time_td"><font>当前期数：<font class="colorDate"><b><?php echo dq_qishu(13,$publicdb); ?></b></font>
							期&nbsp;&nbsp;&nbsp;&nbsp;下注金额：<span id="allgold">0</span></font>
						<font>&nbsp;&nbsp;&nbsp;&nbsp;距离封盘时间：</font> <span id="time_over"
						style="color: #7AFF00; font-size: bold;"></span> <!-- 时间储存 --></td>
				</tr>


				<form autocomplete="off" name="orders" id="order_form" method="get"
					target="k_meml" action="main_left.php"
					onsubmit="return check_submit();">
					<input type="hidden" name="type" value="13"> <input type="hidden"
						name="qishu" value="<?php echo dq_qishu(13,$publicdb);?>">
					<tr class="tbtitle2">


					<tr class="tbtitle2">
						<th colspan="15"><div
								style="float: left; line-height: 30px; text-align: center; width: 100%"
								id="title_3d"><?=$title_3d?></div> </th>
					</tr>
					<tr class="tbtitle2">
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
					<?php if($_GET['type'] == 'ball_1'){ ?>
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>

					<?} ?>
					</tr>
  <?php

		if ($_GET ['type'] === 'ball_1') {
			?>
			<!-- 和值 -->
    <tr>
    					<td class="ball_bg">3</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h1"><?=$fc_3d_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>

						<td class="ball_bg">4</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h2"><?=$fc_3d_odds['h2']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>


						<td class="ball_bg">5</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h3"><?=$fc_3d_odds['h3']?></span></td>
						<td class="ball_ff"><input pid="0" num="5" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>


						<td class="ball_bg">6</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h4"><?=$fc_3d_odds['h4']?></span></td>
						<td class="ball_ff"><input pid="0" num="6" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>


						<td class="ball_bg">7</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h5"><?=$fc_3d_odds['h5']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>
					</tr>


					<tr>

						<td class="ball_bg">8</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h6"><?=$fc_3d_odds['h6']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_6" name="ball_<?=$ball?>_6"></td>

						<td class="ball_bg">9</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h7"><?=$fc_3d_odds['h7']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_7" name="ball_<?=$ball?>_7"></td>


						<td class="ball_bg">10</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h8"><?=$fc_3d_odds['h8']?></span></td>
						<td class="ball_ff"><input pid="0" num="10" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_8" name="ball_<?=$ball?>_8"></td>


						<td class="ball_bg">11</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h9"><?=$fc_3d_odds['h9']?></span></td>
						<td class="ball_ff"><input pid="0" num="11" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_9" name="ball_<?=$ball?>_9"></td>


						<td class="ball_bg">12</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h10"><?=$fc_3d_odds['h10']?></span></td>
						<td class="ball_ff"><input pid="0" num="12" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_10" name="ball_<?=$ball?>_10"></td>
					</tr>


					<tr>

						<td class="ball_bg">13</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h11"><?=$fc_3d_odds['h11']?></span></td>
						<td class="ball_ff"><input pid="0" num="13" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_11" name="ball_<?=$ball?>_11"></td>

						<td class="ball_bg">14</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h12"><?=$fc_3d_odds['h12']?></span></td>
						<td class="ball_ff"><input pid="0" num="14" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_12" name="ball_<?=$ball?>_12"></td>


						<td class="ball_bg">15</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h13"><?=$fc_3d_odds['h13']?></span></td>
						<td class="ball_ff"><input pid="0" num="15" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_13" name="ball_<?=$ball?>_13"></td>


						<td class="ball_bg">16</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h14"><?=$fc_3d_odds['h14']?></span></td>
						<td class="ball_ff"><input pid="0" num="16" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_14" name="ball_<?=$ball?>_14"></td>


						<td class="ball_bg">17</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h15"><?=$fc_3d_odds['h15']?></span></td>
						<td class="ball_ff"><input pid="0" num="17" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_15" name="ball_<?=$ball?>_15"></td>
					</tr>


					<tr>

						<td class="ball_bg">18</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h16"><?=$fc_3d_odds['h16']?></span></td>
						<td class="ball_ff"><input pid="0" num="17" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_16" name="ball_<?=$ball?>_16"></td>

						<td class="ball_bg">大</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h17"><?=$fc_3d_odds['h17']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_17" name="ball_<?=$ball?>_17"></td>


						<td class="ball_bg">小</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h18"><?=$fc_3d_odds['h18']?></span></td>
						<td class="ball_ff"><input pid="0" num="1" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_18" name="ball_<?=$ball?>_18"></td>


						<td class="ball_bg">单</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h19"><?=$fc_3d_odds['h19']?></span></td>
						<td class="ball_ff"><input pid="0" num="2" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_19" name="ball_<?=$ball?>_19"></td>


						<td class="ball_bg">双</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h20"><?=$fc_3d_odds['h20']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_20" name="ball_<?=$ball?>_20"></td>

					</tr>




    <?php }elseif($_GET['type'] === 'ball_2') {?>
    <!-- 独胆  -->
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">1</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h1"><?=$fc_3d_odds['h1']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>

						<td class="ball_bg" align="center" valign="middle">2</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h2"><?=$fc_3d_odds['h2']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>

						<td class="ball_bg" align="center" valign="middle">3</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h3"><?=$fc_3d_odds['h3']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>
					</tr>
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">4</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h4"><?=$fc_3d_odds['h4']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>

						<td class="ball_bg" align="center" valign="middle">5</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h5"><?=$fc_3d_odds['h5']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>

						<td class="ball_bg" align="center" valign="middle">6</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h6"><?=$fc_3d_odds['h6']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_6" name="ball_<?=$ball?>_6"></td>
					</tr>

             <?php }elseif($_GET['type'] === 'ball_3') {?>
    <!-- 豹子 -->
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">
						<div style="display: none">1,1,1</div>
						<img src="public/images/dice_01.png"><img src="public/images/dice_01.png"><img src="public/images/dice_01.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h1"><?=$fc_3d_odds['h1']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>

						<td class="ball_bg" align="center" valign="middle">
						<div style="display: none">2,2,2</div>
						<img src="public/images/dice_02.png"><img src="public/images/dice_02.png"><img src="public/images/dice_02.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h2"><?=$fc_3d_odds['h2']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>

						<td class="ball_bg" align="center" valign="middle">
						<div style="display: none">3,3,3</div>
						<img src="public/images/dice_03.png"><img src="public/images/dice_03.png"><img src="public/images/dice_03.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h3"><?=$fc_3d_odds['h3']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>

					</tr>

					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">
						<div style="display: none">4,4,4</div>
						<img src="public/images/dice_04.png"><img src="public/images/dice_04.png"><img src="public/images/dice_04.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h4"><?=$fc_3d_odds['h4']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>

						<td class="ball_bg" align="center" valign="middle">
						<div style="display: none">5,5,5</div>
						<img src="public/images/dice_05.png"><img src="public/images/dice_05.png"><img src="public/images/dice_05.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h5"><?=$fc_3d_odds['h5']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>

						<td class="ball_bg" align="center" valign="middle">
						<div style="display: none">6,6,6</div>
						<img src="public/images/dice_06.png"><img src="public/images/dice_06.png"><img src="public/images/dice_06.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h6"><?=$fc_3d_odds['h6']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_6" name="ball_<?=$ball?>_6"></td>
					</tr>

					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">任意豹子</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h7"><?=$fc_3d_odds['h7']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_7" name="ball_<?=$ball?>_7"></td>

						<td colspan="6"></td>
					</tr>

              <?php }elseif($_GET['type'] === 'ball_4') {?>
    			<!-- 两连 -->
    			<tr>
						<td class="ball_bg">
						<div style="display: none">1,2</div>
						<img src="public/images/dice_01.png"><img src="public/images/dice_02.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h1"><?=$fc_3d_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>


						<td class="ball_bg">
						<div style="display: none">1,3</div>
						<img src="public/images/dice_01.png"><img src="public/images/dice_03.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h2"><?=$fc_3d_odds['h2']?></span></td>
						<td class="ball_ff"><input pid="0" num="5" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>


						<td class="ball_bg">
						<div style="display: none">1,4</div>
						<img src="public/images/dice_01.png"><img src="public/images/dice_04.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h3"><?=$fc_3d_odds['h3']?></span></td>
						<td class="ball_ff"><input pid="0" num="6" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>
						</tr>
						<tr>
						<td class="ball_bg">
						<div style="display: none">1,5</div>
						<img src="public/images/dice_01.png"><img src="public/images/dice_05.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h4"><?=$fc_3d_odds['h4']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>


						<td class="ball_bg">
						<div style="display: none">1,6</div>
						<img src="public/images/dice_01.png"><img src="public/images/dice_06.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h5"><?=$fc_3d_odds['h5']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>

						<td class="ball_bg">
						<div style="display: none">2,3</div>
						<img src="public/images/dice_02.png"><img src="public/images/dice_03.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h6"><?=$fc_3d_odds['h6']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_6" name="ball_<?=$ball?>_6"></td>
</tr>
					<tr>
						<td class="ball_bg">
						<div style="display: none">2,4</div>
						<img src="public/images/dice_02.png"><img src="public/images/dice_04.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h7"><?=$fc_3d_odds['h7']?></span></td>
						<td class="ball_ff"><input pid="0" num="10" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_7" name="ball_<?=$ball?>_7"></td>


						<td class="ball_bg">
						<div style="display: none">2,5</div>
						<img src="public/images/dice_02.png"><img src="public/images/dice_05.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h8"><?=$fc_3d_odds['h8']?></span></td>
						<td class="ball_ff"><input pid="0" num="11" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_8" name="ball_<?=$ball?>_8"></td>


						<td class="ball_bg">
						<div style="display: none">2,6</div>
						<img src="public/images/dice_02.png"><img src="public/images/dice_06.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h9"><?=$fc_3d_odds['h9']?></span></td>
						<td class="ball_ff"><input pid="0" num="12" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_9" name="ball_<?=$ball?>_9"></td>
</tr>

					<tr>
						<td class="ball_bg">
						<div style="display: none">3,4</div>
						<img src="public/images/dice_03.png"><img src="public/images/dice_04.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h10"><?=$fc_3d_odds['h10']?></span></td>
						<td class="ball_ff"><input pid="0" num="13" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_10" name="ball_<?=$ball?>_10"></td>


						<td class="ball_bg">
						<div style="display: none">3,5</div>
						<img src="public/images/dice_03.png"><img src="public/images/dice_05.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h11"><?=$fc_3d_odds['h11']?></span></td>
						<td class="ball_ff"><input pid="0" num="14" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_11" name="ball_<?=$ball?>_11"></td>


						<td class="ball_bg">
						<div style="display: none">3,6</div>
						<img src="public/images/dice_03.png"><img src="public/images/dice_06.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h12"><?=$fc_3d_odds['h12']?></span></td>
						<td class="ball_ff"><input pid="0" num="15" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_12" name="ball_<?=$ball?>_12"></td>
</tr>


					<tr>

						<td class="ball_bg">
						<div style="display: none">4,5</div>
						<img src="public/images/dice_04.png"><img src="public/images/dice_05.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h13"><?=$fc_3d_odds['h13']?></span></td>
						<td class="ball_ff"><input pid="0" num="16" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_13" name="ball_<?=$ball?>_13"></td>


						<td class="ball_bg">
						<div style="display: none">4,6</div>
						<img src="public/images/dice_04.png"><img src="public/images/dice_06.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h14"><?=$fc_3d_odds['h14']?></span></td>
						<td class="ball_ff"><input pid="0" num="17" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_14" name="ball_<?=$ball?>_14"></td>


						<td class="ball_bg">
						<div style="display: none">5,6</div>
						<img src="public/images/dice_05.png"><img src="public/images/dice_06.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h15"><?=$fc_3d_odds['h15']?></span></td>
						<td class="ball_ff"><input pid="0" num="18" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_15" name="ball_<?=$ball?>_15"></td>
					</tr>
					<?php }elseif($_GET['type'] === 'ball_5') {?>
    			<!-- 对子 -->
    				<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">
							<div style="display: none">1,1</div>
							<img src="public/images/dice_01.png"><img src="public/images/dice_01.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h1"><?=$fc_3d_odds['h1']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>

						<td class="ball_bg" align="center" valign="middle">
							<div style="display: none">2,2</div>
							<img src="public/images/dice_02.png"><img src="public/images/dice_02.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h2"><?=$fc_3d_odds['h2']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>

						<td class="ball_bg" align="center" valign="middle">
							<div style="display: none">3,3</div>
							<img src="public/images/dice_03.png"><img src="public/images/dice_03.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h3"><?=$fc_3d_odds['h3']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>
					</tr>
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">
							<div style="display: none">4,4</div>
							<img src="public/images/dice_04.png"><img src="public/images/dice_04.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h4"><?=$fc_3d_odds['h4']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>

						<td class="ball_bg" align="center" valign="middle">
							<div style="display: none">5,5</div>
							<img src="public/images/dice_05.png"><img src="public/images/dice_05.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h5"><?=$fc_3d_odds['h5']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>

						<td class="ball_bg" align="center" valign="middle">
							<div style="display: none">6,6</div>
							<img src="public/images/dice_06.png"><img src="public/images/dice_06.png">
						</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h6"><?=$fc_3d_odds['h6']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_6" name="ball_<?=$ball?>_6"></td>
					</tr>
              <?php }?>

          <tr class="hset2">
						<td colspan="15" class="tbtitle">
							<div style="float: right !important">

								&nbsp;&nbsp; <input name="btnSubmit" type="submit"
									class="button_a" id="btnSubmit" value="下注"> <input type="reset"
									onclick="ClearData();" class="button_a" name="Submit3"
									value="重置">
							</div>
						</td>

					</tr>
				</form>

			</tbody>
		</table>

	<div style="height: 200px; width: 100px;"></div>

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
<!--aa-->
</body>
</html>