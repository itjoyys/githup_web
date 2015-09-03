<?php
include_once ("../include/config.php");
include ("../include/public_config.php");


include ("./include/function.php");
$publicdb = $db_config;

// 福彩3D赔率读取
$style = 'gd_ten';
if (! empty($_GET['type'])) {
    $fc_gd_odds = M('c_odds_1', $db_config)->where("type='" . $_GET['type'] . "'")->find();

    switch ($_GET['type']) {
        case 'ball_1':
            $title_3d = '第一球';
            $ball = 1;
            break;
        case 'ball_2':
            $title_3d = '第二球';
            $ball = 2;
            break;
        case 'ball_3':
            $title_3d = '第三球';
            $ball = 3;
            break;
        case 'ball_4':
            $title_3d = '第四球';
            $ball = 4;
            break;
        case 'ball_5':
            $title_3d = '第五球';
            $ball = 5;
            break;
        case 'ball_6':
            $title_3d = '第六球';
            $ball = 6;
            break;
        case 'ball_7':
            $title_3d = '第七球';
            $ball = 7;
            break;
        case 'ball_8':
            $title_3d = '第八球';
            $ball = 8;
            break;
        case 'ball_9':
            $title_3d = '總和,龍虎';
            $ball = 9;
            break;
    }
    $is_lock = M("fc_games_type",$db_config)->field("*")->where("state = 0 and fc_type = '" . $title_3d . "' and wanfa = '" . $style . "'")->select();
  if($is_lock){
    echo '<script type="text/javascript">alert("玩法维护中，请选择其他玩法");history.go(-1);</script>';
    exit;
  }
}
//查当前玩法已下注的总额
$beted = beted_limit(1,$title_3d,$db_config);
$ball_limit_num = $beted['sum(money)'];
// 查询期数,中奖号

$qishu = M('c_auto_1', $db_config);
$num = $qishu->field("*")
    ->order("datetime desc")
    ->find();

//获取开盘时间，封盘时间开盘状态判断时间，封盘状态判断时间的数组
$array = set_arraypan(1,$db_config);

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
<script src="public/js/gd_sf.js" type="text/javascript"></script>
<script src="public/js/bet.js" type="text/javascript"></script>
<?php  include("./include/common_limit.php");  ?>
<script src="public/js/select.js" type="text/javascript"></script>
<script type="text/javascript">
var o_stime = <?=$array['o_t_stro']?>;
var f_stime = <?=$array['f_t_stro']?>;
</script>
<script src="public/js/orderFunc.js" type="text/javascript"></script>
</head>
<body marginwidth="3" marginheight="3" id="HOP"
	ondragstart="window.event.returnValue=false"
	oncontextmenu="window.event.returnValue=false"
	onselectstart="event.returnValue=false">
	<div id="title_3d" style="display: none"><?=$title_3d?></div>
	<div id="style" style="display: none"><?=$style?></div>
	<div id="fc_type" style="display: none">1</div>
	<div id="kq_box_001" style="display: none;">
		<div id="kq_box_num" style="margin: 50px auto 0">
			距离 第 <b><font color="#7c3838">
    <?=dq_qishu(1,$publicdb)?>
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

	setInterval(function(){lottery(1,8,<?=$num['qishu']?>);},30000);


</script>


<table width="100%" border="0" cellpadding="0" cellspacing="0"
			class="tab_001">
			<tbody>
				<tr
					style="background: url(public/images/title_bg_004.gif) repeat-x left top">
					<td><span class="font_sty001"><strong><?=$title_3d?>下注</strong></span>
					</td>
					<td conspan="2">
						<ul  id ="lottery"
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
		<table border="0" cellpadding="0" cellspacing="1" class="game_table">
			<tbody>
				<tr class="tbtitle">
					<td colspan="15" id="time_td"><font>当前期数：<font class="colorDate"><b>
    <?=dq_qishu(1,$publicdb)?>
    </b></font> 期&nbsp;&nbsp;&nbsp;&nbsp;下注金额：<span id="allgold">0</span></font>
						<font>&nbsp;&nbsp;&nbsp;&nbsp;距离封盘时间：</font> <span id="time_over"
						style="color: #7AFF00; font-size: bold;"></span>
					<!-- 时间储存 --> <script language="javascript">

	</script></td>
				</tr>

				<form autocomplete="off" name="orders" id="order_form" method="get"
					target="k_meml" action="main_left.php"
					onsubmit="return check_submit();">
					<input type="hidden" name="type" value="1"> <input type="hidden"
						name="qishu"
						value="<?=dq_qishu(1,$publicdb)?>">
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
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
				<?php if($_GET['type'] != 'ball_9'){ ?>
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
				<?php } ?>
					</tr>

  <?php

if ($_GET['type'] === 'ball_1' || $_GET['type'] === 'ball_2' || $_GET['type'] === 'ball_3' || $_GET['type'] === 'ball_4' || $_GET['type'] === 'ball_5' || $_GET['type'] === 'ball_6' || $_GET['type'] === 'ball_7' || $_GET['type'] === 'ball_8') {

    ?>
    <tr>
						<td class="ball_bg"><span class="kjjg_li">01</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h1"><?=$fc_gd_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>


						<td class="ball_bg"><span class="kjjg_li">02</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h2"><?=$fc_gd_odds['h2']?></span></td>
						<td class="ball_ff"><input pid="0" num="1" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>


						<td class="ball_bg"><span class="kjjg_li">03</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h3"><?=$fc_gd_odds['h3']?></span></td>
						<td class="ball_ff"><input pid="0" num="2" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>


						<td class="ball_bg"><span class="kjjg_li">04</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h4"><?=$fc_gd_odds['h4']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>


						<td class="ball_bg"><span class="kjjg_li">05</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h5"><?=$fc_gd_odds['h5']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>
					</tr>


					<tr>
						<td class="ball_bg"><span class="kjjg_li">06</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h6"><?=$fc_gd_odds['h6']?></span></td>
						<td class="ball_ff"><input pid="0" num="5" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_6" name="ball_<?=$ball?>_6"></td>


						<td class="ball_bg"><span class="kjjg_li">07</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h7"><?=$fc_gd_odds['h7']?></span></td>
						<td class="ball_ff"><input pid="0" num="6" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_7" name="ball_<?=$ball?>_7"></td>


						<td class="ball_bg"><span class="kjjg_li">08</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h8"><?=$fc_gd_odds['h8']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_8" name="ball_<?=$ball?>_8"></td>


						<td class="ball_bg"><span class="kjjg_li">09</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h9"><?=$fc_gd_odds['h9']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_9" name="ball_<?=$ball?>_9"></td>


						<td class="ball_bg"><span class="kjjg_li">10</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h10"><?=$fc_gd_odds['h10']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_10" name="ball_<?=$ball?>_10"></td>
					</tr>



					<tr>
						<td class="ball_bg"><span class="kjjg_li">11</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h11"><?=$fc_gd_odds['h11']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_11" name="ball_<?=$ball?>_11"></td>


						<td class="ball_bg"><span class="kjjg_li">12</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h12"><?=$fc_gd_odds['h12']?></span></td>
						<td class="ball_ff"><input pid="0" num="1" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_12" name="ball_<?=$ball?>_12"></td>


						<td class="ball_bg"><span class="kjjg_li">13</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h13"><?=$fc_gd_odds['h13']?></span></td>
						<td class="ball_ff"><input pid="0" num="2" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_13" name="ball_<?=$ball?>_13"></td>


						<td class="ball_bg"><span class="kjjg_li">14</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h14"><?=$fc_gd_odds['h14']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_14" name="ball_<?=$ball?>_14"></td>


						<td class="ball_bg"><span class="kjjg_li">15</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h15"><?=$fc_gd_odds['h15']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_15" name="ball_<?=$ball?>_15"></td>
					</tr>


					<tr>
						<td class="ball_bg"><span class="kjjg_li">16</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h16"><?=$fc_gd_odds['h16']?></span></td>
						<td class="ball_ff"><input pid="0" num="5" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_16" name="ball_<?=$ball?>_16"></td>


						<td class="ball_bg"><span class="kjjg_li">17</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h17"><?=$fc_gd_odds['h17']?></span></td>
						<td class="ball_ff"><input pid="0" num="6" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_17" name="ball_<?=$ball?>_17"></td>


						<td class="ball_bg"><span class="kjjg_li">18</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h18"><?=$fc_gd_odds['h18']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_18" name="ball_<?=$ball?>_18"></td>


						<td class="ball_bg"><span class="kjjg_li"
							style="background-image: url(public/images/r.png)">19</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h19"><?=$fc_gd_odds['h19']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_19" name="ball_<?=$ball?>_19"></td>


						<td class="ball_bg"><span class="kjjg_li"
							style="background-image: url(public/images/r.png)">20</span></td>
						<td class="ball_ff"><span rate="true" id="ball_1_h20"><?=$fc_gd_odds['h20']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_20" name="ball_<?=$ball?>_20"></td>
					</tr>

					<tr class="tbtitle2">
						<th colspan="15"><div
								style="float: left; line-height: 30px; text-align: center; width: 100%"></div>
						</th>
					</tr>


					<tr>
						<td class="ball_bg">大</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h21"><?=$fc_gd_odds['h21']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_21" name="ball_<?=$ball?>_21"></td>




						<td class="ball_bg">小</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h22"><?=$fc_gd_odds['h22']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_22" name="ball_<?=$ball?>_22"></td>


						<td class="ball_bg">单</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h23"><?=$fc_gd_odds['h23']?></span></td>
						<td class="ball_ff"><input pid="0" num="1" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_23" name="ball_<?=$ball?>_23"></td>


						<td class="ball_bg">双</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h24"><?=$fc_gd_odds['h24']?></span></td>
						<td class="ball_ff"><input pid="0" num="2" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_24" name="ball_<?=$ball?>_24"></td>


						<td class="ball_bg">尾大</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h25"><?=$fc_gd_odds['h25']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_25" name="ball_<?=$ball?>_25"></td>
					</tr>


					<tr>
						<td class="ball_bg">尾小</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h26"><?=$fc_gd_odds['h26']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_26" name="ball_<?=$ball?>_26"></td>



						<td class="ball_bg">合单</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h27"><?=$fc_gd_odds['h27']?></span></td>
						<td class="ball_ff"><input pid="0" num="5" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_27" name="ball_<?=$ball?>_27"></td>


						<td class="ball_bg">合双</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h28"><?=$fc_gd_odds['h28']?></span></td>
						<td class="ball_ff"><input pid="0" num="6" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_28" name="ball_<?=$ball?>_28"></td>


						<td class="ball_bg">东</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h29"><?=$fc_gd_odds['h29']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_29" name="ball_<?=$ball?>_29"></td>


						<td class="ball_bg">南</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h30"><?=$fc_gd_odds['h30']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_30" name="ball_<?=$ball?>_30"></td>
					</tr>

					<tr>
						<td class="ball_bg">西</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h31"><?=$fc_gd_odds['h31']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_31" name="ball_<?=$ball?>_31"></td>


						<td class="ball_bg">北</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h32"><?=$fc_gd_odds['h32']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_32" name="ball_<?=$ball?>_32"></td>


						<td class="ball_bg">中</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h33"><?=$fc_gd_odds['h33']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_33" name="ball_<?=$ball?>_33"></td>


						<td class="ball_bg">发</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h34"><?=$fc_gd_odds['h34']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_34" name="ball_<?=$ball?>_34"></td>


						<td class="ball_bg">白</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h35"><?=$fc_gd_odds['h35']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_35" name="ball_<?=$ball?>_35"></td>
					</tr>


   	<?php }elseif($_GET['type'] === 'ball_9') {?>
    <!-- 龙虎和 -->
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">总和大</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h1"><?=$fc_gd_odds['h1']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>

						<td class="ball_bg" align="center" valign="middle">总和小</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h2"><?=$fc_gd_odds['h2']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>

						<td class="ball_bg" align="center" valign="middle">总和单</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h3"><?=$fc_gd_odds['h3']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>

						<td class="ball_bg" align="center" valign="middle">总和双</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h4"><?=$fc_gd_odds['h4']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>

					</tr>


					<!-- 龙虎和 -->
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">总和尾大</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h5"><?=$fc_gd_odds['h5']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>
						<td class="ball_bg" align="center" valign="middle">总和尾小</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h6"><?=$fc_gd_odds['h6']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_6" name="ball_<?=$ball?>_6"></td>

						<td class="ball_bg" align="center" valign="middle">龙</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h7"><?=$fc_gd_odds['h7']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_7" name="ball_<?=$ball?>_7"></td>

						<td class="ball_bg" align="center" valign="middle">虎</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h8"><?=$fc_gd_odds['h8']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_8" name="ball_<?=$ball?>_8"></td>
					</tr>



              <?php }?>




			</tbody>
		</table>

		<table width="100%" border="0" cellpadding="0" cellspacing="1"
			style="margin-top: 5px" class="game_table">
			<tbody>

				<tr class="hset2">
					<td colspan="13" class="tbtitle">
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