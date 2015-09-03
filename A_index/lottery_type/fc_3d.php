<?php
include_once ("../include/config.php");
include ("../include/public_config.php");

include ("./include/function.php");

// 福彩3D赔率读取
$publicdb = $db_config;
$style = 'fc_3d';
if (! empty ( $_GET ['type'] )) {
	$fc_3d_odds = M ( 'c_odds_5', $db_config )->where ( "type='" . $_GET ['type'] . "'" )->find ();

	switch ($_GET ['type']) {
		case 'ball_1' :
			$title_3d = '第一球';
			$ball = 1;
			break;
		case 'ball_2' :
			$title_3d = '第二球';
			$ball = 2;
			break;
		case 'ball_3' :
			$title_3d = '第三球';
			$ball = 3;
			break;

		case 'ball_4' :
			$title_3d = '總和,龍虎';
			$ball = 4;
			break;
		case 'ball_5' :
			$title_3d = '3连';
			$ball = 5;
			break;
		case 'ball_6' :
			$title_3d = '跨度';
			$ball = 6;
			break;
		case 'ball_7' :
			$title_3d = '独胆';
			$ball = 7;
			break;
	}
	$is_lock = M("fc_games_type",$db_config)->field("*")->where("state = 0 and fc_type = '" . $title_3d . "' and wanfa = '" . $style . "'")->select();
  if($is_lock){
    echo '<script type="text/javascript">alert("玩法维护中，请选择其他玩法");history.go(-1);</script>';
    exit;
  }
}
//查当前玩法已下注的总额
$beted = beted_limit(5,$title_3d,$db_config);
$ball_limit_num = $beted['sum(money)'];
// 查询期数,中奖号
$qishu = M ( 'c_auto_5', $db_config );
$num = $qishu->field ( "*" )->order ( "datetime desc" )->find ();


//获取开盘时间，封盘时间开盘状态判断时间，封盘状态判断时间的数组
$array = set_arraypan(5,$db_config,"+1 day");

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
	<div id="fc_type" style="display: none">5</div>
	<script type="text/javascript">
	  var o_stime = <?=$array['o_t_stro']?>;
	  var f_stime = <?=$array['f_t_stro']?>;
</script>
	<script src="public/js/orderFunc.js" type="text/javascript"></script>
	<div id="kq_box_001" style="display: none;">
		<div id="kq_box_num" style="margin: 50px auto 0">
			距离 第 <b><font color="#7c3838">     <?=dq_qishu(5,$publicdb);?> </font></b> 期 开盘时间还有：<font color="#7AFF00"><strong><span
					id="close_time" style="float: none; display: inline"></span></strong>

		</div>
	</div>
 <script type="text/javascript">

	setInterval(function(){lottery(5,3,<?=$num['qishu']?>);},30000);


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
					<td colspan="15" id="time_td"><font>当前期数：<font class="colorDate"><b><?=dq_qishu(5,$publicdb);?></b></font>
							期&nbsp;&nbsp;&nbsp;&nbsp;下注金额：<span id="allgold">0</span></font>
						<font>&nbsp;&nbsp;&nbsp;&nbsp;距离封盘时间：</font> <span id="time_over"
						style="color: #7AFF00; font-size: bold;"></span> <!-- 时间储存 --></td>
				</tr>


				<form autocomplete="off" name="orders" id="order_form" method="get"
					target="k_meml" action="main_left.php"
					onsubmit="return check_submit();">
					<input type="hidden" name="type" value="5"> <input type="hidden"
						name="qishu" value="<?=dq_qishu(5,$publicdb);?>">
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

						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
						<?php if($_GET['type'] != 'ball_4'){ ?>
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
		<?php } ?>
					</tr>
  <?php

		if ($_GET ['type'] === 'ball_1' || $_GET ['type'] === 'ball_2' || $_GET ['type'] === 'ball_3' || $_GET ['type'] === 'ball_6' || $_GET ['type'] === 'ball_7') {
			if ($_GET ['type'] === 'ball_6') {
				$tabb = $ball;
			} else {
				$tabb = 1;
			}
			?>
    <tr>
						<td class="ball_bg">0</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h1"><?=$fc_3d_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>


						<td class="ball_bg">1</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h2"><?=$fc_3d_odds['h2']?></span></td>
						<td class="ball_ff"><input pid="0" num="1" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>


						<td class="ball_bg">2</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h3"><?=$fc_3d_odds['h3']?></span></td>
						<td class="ball_ff"><input pid="0" num="2" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>


						<td class="ball_bg">3</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h4"><?=$fc_3d_odds['h4']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>


						<td class="ball_bg">4</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h5"><?=$fc_3d_odds['h5']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>
					</tr>


					<tr>
						<td class="ball_bg">5</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h6"><?=$fc_3d_odds['h6']?></span></td>
						<td class="ball_ff"><input pid="0" num="5" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_6" name="ball_<?=$ball?>_6"></td>


						<td class="ball_bg">6</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h7"><?=$fc_3d_odds['h7']?></span></td>
						<td class="ball_ff"><input pid="0" num="6" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_7" name="ball_<?=$ball?>_7"></td>


						<td class="ball_bg">7</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h8"><?=$fc_3d_odds['h8']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_8" name="ball_<?=$ball?>_8"></td>


						<td class="ball_bg">8</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h9"><?=$fc_3d_odds['h9']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_9" name="ball_<?=$ball?>_9"></td>


						<td class="ball_bg">9</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$tabb?>_h10"><?=$fc_3d_odds['h10']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_10" name="ball_<?=$ball?>_10"></td>
					</tr>




    <?php }elseif($_GET['type'] === 'ball_5') {?>
    <!-- 龙虎和 -->
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">豹子</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h1"><?=$fc_3d_odds['h1']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>

						<td class="ball_bg" align="center" valign="middle">顺子</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h2"><?=$fc_3d_odds['h2']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>

						<td class="ball_bg" align="center" valign="middle">对子</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h3"><?=$fc_3d_odds['h3']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>

						<td class="ball_bg" align="center" valign="middle">半顺</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h4"><?=$fc_3d_odds['h4']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>

						<td class="ball_bg" align="center" valign="middle">杂六</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h5"><?=$fc_3d_odds['h5']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>
					</tr>

             <?php }elseif($_GET['type'] === 'ball_4') {?>
    <!-- 龙虎和 -->
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">总和大</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h1"><?=$fc_3d_odds['h1']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_1" name="ball_<?=$ball?>_1"></td>

						<td class="ball_bg" align="center" valign="middle">总和小</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h2"><?=$fc_3d_odds['h2']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_2" name="ball_<?=$ball?>_2"></td>

						<td class="ball_bg" align="center" valign="middle">总和单</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h3"><?=$fc_3d_odds['h3']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_3" name="ball_<?=$ball?>_3"></td>

						<td class="ball_bg" align="center" valign="middle">总和双</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h4"><?=$fc_3d_odds['h4']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_4" name="ball_<?=$ball?>_4"></td>
					</tr>


					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">龙</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h5"><?=$fc_3d_odds['h5']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_5" name="ball_<?=$ball?>_5"></td>
						<td class="ball_bg" align="center" valign="middle">虎</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h6"><?=$fc_3d_odds['h6']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_6" name="ball_<?=$ball?>_6"></td>

						<td class="ball_bg" align="center" valign="middle">和</td>
						<td class="ball_ff"><span rate="true" id="ball_<?=$ball?>_h7"><?=$fc_3d_odds['h7']?></span></td>
						<td class="ball_ff"><input class="inp1" type="text" js='js'
							style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_7" name="ball_<?=$ball?>_7"></td>


						<td class="ball_bg" colspan="3" align="center" valign="middle"></td>

					</tr>

              <?php }?>


  <?php

		if ($_GET ['type'] === 'ball_1' || $_GET ['type'] === 'ball_2' || $_GET ['type'] === 'ball_3') {
			?>

    <tr>
						<td class="ball_bg">大</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h11"><?=$fc_3d_odds['h11']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_11" name="ball_<?=$ball?>_11"></td>


						<td class="ball_bg">小</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h12"><?=$fc_3d_odds['h12']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_12" name="ball_<?=$ball?>_12"></td>


						<td class="ball_bg">单</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h13"><?=$fc_3d_odds['h13']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_13" name="ball_<?=$ball?>_13"></td>


						<td class="ball_bg">双</td>
						<td class="ball_ff"><span rate="true" id="ball_1_h14"><?=$fc_3d_odds['h14']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" class="inp1"
							type="text" js='js' style="height: 18px" class="input1" size="4"
							id="ball_<?=$ball?>_14" name="ball_<?=$ball?>_14"></td>


						<td colspan="3" class="ball_bg"></td>
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
		<!-- 开奖结果概率 -->
<?php
include ("../include/public_config.php");
$result = M ( "c_auto_5", $db_config )->field ( "*" )->order ( "qishu desc" )->limit ( "0,30" )->select ();

// p($result);

$gailv_arr = array ();

if (! empty ( $result )) {
	foreach ( $result as $k => $v ) {
		$j = 0;
		foreach ( $v as $kk => $vv ) { // 判断结果一共几个球
			$arr = explode ( "_", $kk );

			if (! empty ( $arr [1] )) {
				$j ++;
			}
		}

		$i = 1;
		for($i; $i <= $j; $i ++) { // $i == 1为最高位 递减
			for($k = 0; $k <= 9; $k ++) {
				if ($v ['ball_' . $i] == $k) {
					$gailv_arr [$i] [$k] += 1;
				}
			}
		}
	}
}


?>
<div id="bc_table">
			<table width="100%" border="0" cellpadding="0" cellspacing="1"
				class="game_table">
				<tbody>
					<tr class="tbtitle" style="height: 25px">
						<th class="ball_ff" style="width: 40%">近30期</th>
						<th class="ball_ff" style="width:47px;">0</th>
						<th class="ball_ff" style="width:47px;">1</th>
						<th class="ball_ff" style="width:47px;">2</th>
						<th class="ball_ff" style="width:47px;">3</th>
						<th class="ball_ff" style="width:47px;">4</th>
						<th class="ball_ff" style="width:47px;">5</th>
						<th class="ball_ff" style="width:47px;">6</th>
						<th class="ball_ff" style="width:47px;">7</th>
						<th class="ball_ff" style="width:47px;">8</th>
						<th class="ball_ff" style="width:47px;">9</th>
					</tr>
					<tr class="tbtitle">
						<td class="ball_ff">百位OXX出球率</td>


  <?php
		for($i = 0; $i < 10; $i ++) {

			if ($gailv_arr [1] [$i] && $gailv_arr [1] [$i] < 4) {
				?>
<td style=""><?=$gailv_arr[1][$i] ?></td>

 <?php  }else if($gailv_arr[1][$i] > 3){ ?>

<td style="color: #ff0000; font-weight: bold"><?=$gailv_arr[1][$i] ?></td>

<?php  }else{ ?>
   <td style="">0</td>
<?php }} ?>
  </tr>
					<tr class="tbtitle">
						<td class="ball_ff">十位XOX出球率</td>
      <?php
						for($i = 0; $i < 10; $i ++) {

							if ($gailv_arr [2] [$i] && $gailv_arr [2] [$i] < 3) {
								?>
<td style=""><?=$gailv_arr[2][$i] ?></td>

 <?php  }else if($gailv_arr[2][$i] > 4){ ?>

<td style="color: #ff0000; font-weight: bold"><?=$gailv_arr[2][$i] ?></td>

<?php  }else{ ?>
   <td style="">0</td>
<?php }} ?>
  </tr>
					<tr class="tbtitle">
						<td class="ball_ff">个位XXO出球率</td>
     <?php
					for($i = 0; $i < 10; $i ++) {

						if ($gailv_arr [3] [$i] && $gailv_arr [3] [$i] < 4) {
							?>
<td style=""><?=$gailv_arr[3][$i] ?></td>

 <?php  }else if($gailv_arr[3][$i] > 3){ ?>

<td style="color: #ff0000; font-weight: bold"><?=$gailv_arr[3][$i] ?></td>

<?php  }else{ ?>
   <td style="">0</td>
<?php }} ?>
  </tr>

				</tbody>
			</table>
		</div>
		<iframe width="100%" height="150" frameborder="0" marginheight="1"
			marginwidth="1" src="./show_zoushi.php?type=5"></iframe>
	</div>
	</div>
	</div>
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

</body>
</html>