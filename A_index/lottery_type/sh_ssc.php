<?php
include_once ("../include/config.php");
include_once ("../lib/class/model.class.php");
include ("../include/public_config.php");
include ("./include/function.php");
$publicdb = $db_config;
$uid = $_SESSION["uid"];
// 上海时时彩赔率读取
$style = 'sh_ssc';
if (! empty($_GET['type'])) {
    $cq_ssc_odds = M("c_odds_9", $db_config)->where("type='" . $_GET['type'] . "'")->find();
    
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
            $title_3d = '總和,龍虎';
            break;
        case 'ball_7':
            $title_3d = '前三球';
            break;
        case 'ball_8':
            $title_3d = '中三球';
            break;
        case 'ball_9':
            $title_3d = '后三球';
            break;
        case 'ball_10':
            $title_3d = '斗牛';
            break;
        case 'ball_11':
            $title_3d = '梭哈';
            break;
    }
}

// 查询期数,中奖号
$qishu = M('c_auto_9', $db_config);
$num = $qishu->field("*")
    ->order("datetime desc")
    ->find();
// 查询是否开盘
$now_time = date("H:i:s", strtotime("+12 hours"));

// $now_time=date("H:i:s");
$data_time = M('c_opentime_9', $db_config)->field("*")
    ->where("ok ='0' and fengpan > '" . $now_time . "'")
    ->order("kaijiang ASC")
    ->find();

$f_t = date("Y-m-d", strtotime("+12 hours")) . ' ' . $data_time['fengpan'];
$o_t = date("Y-m-d", strtotime("+12 hours")) . ' ' . $data_time['kaipan'];

$c_time = date("Y-m-d H:i:s", strtotime("+12 hours")); // 中国时间
                                                      // $f_t = date("Y-m-d").' '.$data_time['fengpan'];
                                                      // $o_t = date("Y-m-d").' '.$data_time['kaipan'];
                                                      
// $c_time = date("Y-m-d H:i:s");//中国时间
$f_t_stro = strtotime($f_t) - strtotime($c_time); // 距离封盘的时间
$o_t_stro = strtotime($o_t) - strtotime($c_time); // 距离开盘的时间

$f_state = date("Y-m-d") . ' ' . $data_time['fengpan']; // 封盘状态判断时间

$o_state = date("Y-m-d") . ' ' . $data_time['kaipan']; // 开盘状态判断时间

$c_time = date('Y-m-d') . ' ' . $now_time;
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
  var o_stime = <?=$o_t_stro?>;
  var f_stime = <?=$f_t_stro?>;


</script>
<script src="public/js/orderFunc.js" type="text/javascript"></script>
<!-- <script src="public/js/select.js" type="text/javascript"></script> -->
</head>

<body marginwidth="3" marginheight="3" id="HOP"
	ondragstart="window.event.returnValue=false"
	oncontextmenu="window.event.returnValue=false"
	onselectstart="event.returnValue=false">
	<div id="title_3d" style="display: none"><?=$title_3d?></div>
	<div id="style" style="display: none"><?=$style?></div>
	<div id="fc_type" style="display: none">2</div>
	<div id="kq_box_001" style="display: none;">
		<div id="kq_box_num" style="margin: 50px auto 0">
			距离 第 <b><font color="#7c3838">
        <?=dq_qishu(9,$publicdb)?>
      </font></b> 期 开盘时间还有：<font color="#7AFF00"><strong><span
					id="close_time" style="float: none; display: inline"></span></strong>
		
		</div>
	</div>
	<div class="wrapCss_004" style="display: block;" id="all_body">
<?php
if (strtotime($c_time) > strtotime($o_state) && strtotime($c_time) < strtotime($f_state)) {
    echo '<script>document.getElementById("all_body").style.display="block";document.getElementById("kq_box_001").style.display="none";var f_o_state = 1;</script>';
} else {
    // 封盘时切换显示
    echo '<script>document.getElementById("all_body").style.display="none";document.getElementById("kq_box_001").style.display="block";var f_o_state = 2;</script>';
}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0"
			class="tab_001">
			<tbody>
				<tr
					style="background: url(public/images/title_bg_004.gif) repeat-x left top">
					<td><span class="font_sty001"><strong><?=$title_3d?>下注</strong></span>
					</td>
					<td conspan="2">
						<ul
							style="padding: 3px 0 0 0; margin: 0px; list-style: none; float: right">
							<li style="float: left; height: 26px; line-height: 26px">第 <b><font
									class="colorDate"><?=$num['qishu'] ?></font></b> 期开奖结果：
							</li>
							<li align="right" class="kjjg_li"><?=$num['ball_1'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_2'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_3'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_4'] ?></li>
							<li align="right" class="kjjg_li"><?=$num['ball_5'] ?></li>
						</ul>
					</td>
				</tr>


<?php  include("./include/common_limit.php");  ?>
</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="1" class="game_table">
			<tbody>
				<tr class="tbtitle">
					<td colspan="15" id="time_td"><font>当前期数：<font class="colorDate"><b> <?=dq_qishu(9,$publicdb)?></b></font>
							期&nbsp;&nbsp;&nbsp;&nbsp;下注金额：<span id="allgold">0</span></font>
						<font>&nbsp;&nbsp;&nbsp;&nbsp;距离封盘时间：</font> <span id="time_over"
						style="color: #7AFF00; font-size: bold;"></span></td>
				</tr>
				<script type="text/javascript">
</script>
				<form autocomplete="off" name="orders" id="order_form" method="get"
					target="k_meml" action="main_left.php"
					onsubmit="return check_submit();">
					<!-- <form autocomplete="off" name="orders" id="order_form"  method="post"  action="Order.php?type=2&qishu=<?=$num['qishu']+1?>"> -->

					<input type="hidden" name="type" value="9"> <input type="hidden"
						name="qishu"
						value="<?php echo date('Ymd').BuLings($data_time['qishu']); ?>">
					<tr class="tbtitle2">
						<th colspan="15"><div
								style="float: left; line-height: 30px; text-align: center; width: 100%"
								id="title_3d"><?=$title_3d?></div></th>

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
						<?php if($_GET['type'] != 'ball_6' & $_GET['type'] != 'ball_11'){ ?>
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
		<?php } ?>
					</tr>
  <?php

if ($_GET['type'] === 'ball_1' || $_GET['type'] === 'ball_2' || $_GET['type'] === 'ball_3' || $_GET['type'] === 'ball_4' || $_GET['type'] === 'ball_5') {
    ?>
<!------------------------------------------   第几球 ------------------------------------------------------>
					<tr>
						<td class="ball_bg">0</td>

						<td class="ball_ff"><span rate="true" id="bl_1"><?=$cq_ssc_odds['h1']?></span>
						</td>
						<td class="ball_ff"><input pid="0" num="0" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_1"
							name="<?=$_GET['type'] ?>_1"></td>


						<td class="ball_bg">1</td>
						<td class="ball_ff"><span rate="true" id="bl_2"><?=$cq_ssc_odds['h2']?></span></td>
						<td class="ball_ff"><input pid="0" num="1" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_2"
							name="<?=$_GET['type'] ?>_2"></td>


						<td class="ball_bg">2</td>
						<td class="ball_ff"><span rate="true" id="bl_3"><?=$cq_ssc_odds['h3']?></span></td>
						<td class="ball_ff"><input pid="0" num="2" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_3"
							name="<?=$_GET['type'] ?>_3"></td>


						<td class="ball_bg">3</td>
						<td class="ball_ff"><span rate="true" id="bl_4"><?=$cq_ssc_odds['h4']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_4"
							name="<?=$_GET['type'] ?>_4"></td>


						<td class="ball_bg">4</td>
						<td class="ball_ff"><span rate="true" id="bl_5"><?=$cq_ssc_odds['h5']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_5"
							name="<?=$_GET['type'] ?>_5"></td>
					</tr>


					<tr>
						<td class="ball_bg">5</td>
						<td class="ball_ff"><span rate="true" id="bl_6"><?=$cq_ssc_odds['h6']?></span></td>
						<td class="ball_ff"><input pid="0" num="5" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_6"
							name="<?=$_GET['type'] ?>_6"></td>


						<td class="ball_bg">6</td>
						<td class="ball_ff"><span rate="true" id="bl_7"><?=$cq_ssc_odds['h7']?></span></td>
						<td class="ball_ff"><input pid="0" num="6" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_7"
							name="<?=$_GET['type'] ?>_7"></td>


						<td class="ball_bg">7</td>
						<td class="ball_ff"><span rate="true" id="bl_8"><?=$cq_ssc_odds['h8']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_8"
							name="<?=$_GET['type'] ?>_8"></td>


						<td class="ball_bg">8</td>
						<td class="ball_ff"><span rate="true" id="bl_9"><?=$cq_ssc_odds['h9']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_9"
							name="<?=$_GET['type'] ?>_9"></td>


						<td class="ball_bg">9</td>
						<td class="ball_ff"><span rate="true" id="bl_10"><?=$cq_ssc_odds['h10']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_10"
							name="<?=$_GET['type'] ?>_10"></td>
					</tr>
    <?php

} else 
    if ($_GET['type'] === 'ball_10') {
        ?>
<!------------------------------------------   斗牛 ------------------------------------------------------>
					<tr>
						<td class="ball_bg">没牛</td>
						<td class="ball_ff"><span rate="true" id="bl_1"><?=$cq_ssc_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_1"
							name="<?=$_GET['type'] ?>_1"></td>


						<td class="ball_bg">牛1</td>
						<td class="ball_ff"><span rate="true" id="bl_9"><?=$cq_ssc_odds['h2']?></span></td>
						<td class="ball_ff"><input pid="0" num="1" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_9"
							name="<?=$_GET['type'] ?>_9"></td>


						<td class="ball_bg">牛2</td>
						<td class="ball_ff"><span rate="true" id="bl_3"><?=$cq_ssc_odds['h3']?></span></td>
						<td class="ball_ff"><input pid="0" num="2" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_3"
							name="<?=$_GET['type'] ?>_3"></td>


						<td class="ball_bg">牛3</td>
						<td class="ball_ff"><span rate="true" id="bl_4"><?=$cq_ssc_odds['h4']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_4"
							name="<?=$_GET['type'] ?>_4"></td>


						<td class="ball_bg">牛4</td>
						<td class="ball_ff"><span rate="true" id="bl_5"><?=$cq_ssc_odds['h5']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_5"
							name="<?=$_GET['type'] ?>_5"></td>
					</tr>


					<tr>
						<td class="ball_bg">牛5</td>
						<td class="ball_ff"><span rate="true" id="bl_6"><?=$cq_ssc_odds['h6']?></span></td>
						<td class="ball_ff"><input pid="0" num="5" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_6"
							name="<?=$_GET['type'] ?>_6"></td>


						<td class="ball_bg">牛6</td>
						<td class="ball_ff"><span rate="true" id="bl_7"><?=$cq_ssc_odds['h7']?></span></td>
						<td class="ball_ff"><input pid="0" num="6" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_7"
							name="<?=$_GET['type'] ?>_7"></td>


						<td class="ball_bg">牛7</td>
						<td class="ball_ff"><span rate="true" id="bl_8"><?=$cq_ssc_odds['h8']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_8"
							name="<?=$_GET['type'] ?>_8"></td>


						<td class="ball_bg">牛8</td>
						<td class="ball_ff"><span rate="true" id="bl_9"><?=$cq_ssc_odds['h9']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_9"
							name="<?=$_GET['type'] ?>_9"></td>


						<td class="ball_bg">牛9</td>
						<td class="ball_ff"><span rate="true" id="bl_10"><?=$cq_ssc_odds['h10']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_10"
							name="<?=$_GET['type'] ?>_10"></td>
					</tr>

					<tr>
						<td class="ball_bg">牛牛</td>
						<td class="ball_ff"><span rate="true" id="bl_6"><?=$cq_ssc_odds['h6']?></span></td>
						<td class="ball_ff"><input pid="0" num="5" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_6"
							name="<?=$_GET['type'] ?>_11"></td>


						<td class="ball_bg">牛大</td>
						<td class="ball_ff"><span rate="true" id="bl_7"><?=$cq_ssc_odds['h7']?></span></td>
						<td class="ball_ff"><input pid="0" num="6" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_7"
							name="<?=$_GET['type'] ?>_12"></td>


						<td class="ball_bg">牛小</td>
						<td class="ball_ff"><span rate="true" id="bl_8"><?=$cq_ssc_odds['h8']?></span></td>
						<td class="ball_ff"><input pid="0" num="7" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_8"
							name="<?=$_GET['type'] ?>_13"></td>


						<td class="ball_bg">牛单</td>
						<td class="ball_ff"><span rate="true" id="bl_9"><?=$cq_ssc_odds['h9']?></span></td>
						<td class="ball_ff"><input pid="0" num="8" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_9"
							name="<?=$_GET['type'] ?>_14"></td>


						<td class="ball_bg">牛双</td>
						<td class="ball_ff"><span rate="true" id="bl_10"><?=$cq_ssc_odds['h10']?></span></td>
						<td class="ball_ff"><input pid="0" num="9" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_10"
							name="<?=$_GET['type'] ?>_15"></td>
					</tr>
   	<?php }elseif($_GET['type'] === 'ball_6') { ?>
    <!------------------------------- 龙虎和 ------------------------------------------------------>
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">总和大</td>
						<td class="ball_ff"><span rate="true" id="bl_974"><?=$cq_ssc_odds['h1']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_974"
							name="<?=$_GET['type'] ?>_1"></td>

						<td class="ball_bg" align="center" valign="middle">总和小</td>
						<td class="ball_ff"><span rate="true" id="bl_975"><?=$cq_ssc_odds['h2']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_975"
							name="<?=$_GET['type'] ?>_9"></td>

						<td class="ball_bg" align="center" valign="middle">总和单</td>
						<td class="ball_ff"><span rate="true" id="bl_976"><?=$cq_ssc_odds['h3']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_976"
							name="<?=$_GET['type'] ?>_3"></td>

						<td class="ball_bg" align="center" valign="middle">总和双</td>
						<td class="ball_ff"><span rate="true" id="bl_977"><?=$cq_ssc_odds['h4']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_977"
							name="<?=$_GET['type'] ?>_4"></td>

						<!-- <td colspan="3"></td> -->

					</tr>
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">龙</td>
						<td class="ball_ff"><span rate="true" id="bl_979"><?=$cq_ssc_odds['h5']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_979"
							name="<?=$_GET['type'] ?>_5"></td>

						<td class="ball_bg" align="center" valign="middle">虎</td>
						<td class="ball_ff"><span rate="true" id="bl_980"><?=$cq_ssc_odds['h6']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_980"
							name="<?=$_GET['type'] ?>_6"></td>

						<td class="ball_bg" align="center" valign="middle">和</td>
						<td class="ball_ff"><span rate="true" id="bl_981"><?=$cq_ssc_odds['h7']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_981"
							name="<?=$_GET['type'] ?>_7"></td>

						<td colspan="6" class="ball_bg"></td>
					</tr>
			
			</tbody>
		</table>
		<!------------------------------------------- 梭哈 ---------------------------------------------->
      <?php
    
} else 
        if ($_GET['type'] === 'ball_11') {
            ?>      
   <tr class="tbtitle">
			<td class="ball_bg" align="center" valign="middle">五条</td>
			<td class="ball_ff"><span rate="true" id="bl_974"><?=$cq_ssc_odds['h1']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_974" name="<?=$_GET['type'] ?>_1"></td>

			<td class="ball_bg" align="center" valign="middle">四条</td>
			<td class="ball_ff"><span rate="true" id="bl_975"><?=$cq_ssc_odds['h2']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_975" name="<?=$_GET['type'] ?>_9"></td>

							<td class="ball_bg" align="center" valign="middle">三条</td>
			<td class="ball_ff"><span rate="true" id="bl_979"><?=$cq_ssc_odds['h5']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_979" name="<?=$_GET['type'] ?>_5"></td>
							<td class="ball_bg" align="center" valign="middle">散号</td>
			<td class="ball_ff"><span rate="true" id="bl_981"><?=$cq_ssc_odds['h7']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_981" name="<?=$_GET['type'] ?>_8"></td>


			<!-- <td colspan="3"></td> -->
		</tr>
		<tr class="tbtitle">


			<td class="ball_bg" align="center" valign="middle">两对</td>
			<td class="ball_ff"><span rate="true" id="bl_980"><?=$cq_ssc_odds['h6']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_980" name="<?=$_GET['type'] ?>_6"></td>

			<td class="ball_bg" align="center" valign="middle">一对</td>
			<td class="ball_ff"><span rate="true" id="bl_981"><?=$cq_ssc_odds['h7']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_981" name="<?=$_GET['type'] ?>_7"></td>

			<td class="ball_bg" align="center" valign="middle">葫芦</td>
			<td class="ball_ff"><span rate="true" id="bl_976"><?=$cq_ssc_odds['h3']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_976" name="<?=$_GET['type'] ?>_3"></td>

			<td class="ball_bg" align="center" valign="middle">顺子</td>
			<td class="ball_ff"><span rate="true" id="bl_977"><?=$cq_ssc_odds['h4']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_977" name="<?=$_GET['type'] ?>_4"></td>

			<!-- <td colspan="3"></td> -->
		</tr>
		</tbody>
		</table>
  	<?php } ?>
                   
<!------------------------------------------------------- 前三球 ---------------------------------------->
<?php

if ($_GET['type'] === 'ball_7' || $_GET['type'] === 'ball_8' || $_GET['type'] === 'ball_9') {
    
    ?>

   <tr class="tbtitle">
			<td class="ball_bg" align="center" valign="middle">豹子</td>
			<td class="ball_ff"><span rate="true" id="bl_974"><?=$cq_ssc_odds['h1']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_974" name="<?=$_GET['type'] ?>_1"></td>

			<td class="ball_bg" align="center" valign="middle">顺子</td>
			<td class="ball_ff"><span rate="true" id="bl_975"><?=$cq_ssc_odds['h2']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_975" name="<?=$_GET['type'] ?>_9"></td>

			<td class="ball_bg" align="center" valign="middle">对子</td>
			<td class="ball_ff"><span rate="true" id="bl_976"><?=$cq_ssc_odds['h3']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_976" name="<?=$_GET['type'] ?>_3"></td>

			<td class="ball_bg" align="center" valign="middle">半顺</td>
			<td class="ball_ff"><span rate="true" id="bl_977"><?=$cq_ssc_odds['h4']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_977" name="<?=$_GET['type'] ?>_4"></td>

			<td class="ball_bg" align="center" valign="middle">杂六</td>
			<td class="ball_ff"><span rate="true" id="bl_978"><?=$cq_ssc_odds['h5']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_977" name="<?=$_GET['type'] ?>_5"></td>
		</tr> 


  <?php

}
if ($_GET['type'] === 'ball_1' || $_GET['type'] === 'ball_9' || $_GET['type'] === 'ball_3' || $_GET['type'] === 'ball_4' || $_GET['type'] === 'ball_5') {
    ?>
<!-- <table width="100%" border="0" cellpadding="0" cellspacing="1" style="margin-top:5px" class="game_table">
  <tbody> -->
		<!------------------------------------------- 大小单双 ------------------------------------------------>
		<tr class="tbtitle">
			<td class="ball_bg" align="center" valign="middle">大</td>
			<td class="ball_ff"><span rate="true" id="bl_91"><?=$cq_ssc_odds['h11']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_91" name="<?=$_GET['type'] ?>_11"></td>

			<td class="ball_bg" align="center" valign="middle">小</td>
			<td class="ball_ff"><span rate="true" id="bl_92"><?=$cq_ssc_odds['h12']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_92" name="<?=$_GET['type'] ?>_12"></td>

			<td class="ball_bg" align="center" valign="middle">单</td>
			<td class="ball_ff"><span rate="true" id="bl_93"><?=$cq_ssc_odds['h13']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_93" name="<?=$_GET['type'] ?>_13"></td>

			<td class="ball_bg" align="center" valign="middle">双</td>
			<td class="ball_ff"><span rate="true" id="bl_94"><?=$cq_ssc_odds['h14']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_94" name="<?=$_GET['type'] ?>_14"></td>


			<td colspan="3"></td>
		</tr> 


     <?php }?>


<table width="100%" border="0" cellpadding="0" cellspacing="1"
			class="game_table">
			<tbody>
				<tr class="hset2">
					<td colspan="13" class="tbtitle">
						<div style="float: right !important">
							<input name="btnSubmit" type="submit" class="button_a"
								id="btnSubmit" value="下注"> <input type="reset"
								onclick="ClearData();" class="button_a" name="Submit3"
								value="重置">

						</div>
					</td>
				</tr>


			</tbody>
		</table>
		</form>


		<!-- 开奖结果概率 -->
<?php
include ("../include/public_config.php");
$map_where = "datetime > '" . date('Y-m-d') . " 00:00:00' and datetime <'" . date('Y-m-d') . " 23:59:59'";
$result = M("c_auto_9", $db_config)->field("*")
    ->where($map_where)
    ->select();

// p($result);

$gailv_arr = array();

if (! empty($result)) {
    foreach ($result as $k => $v) {
        $j = 0;
        foreach ($v as $kk => $vv) { // 判断结果一共几个球
            $arr = explode("_", $kk);
            
            if (! empty($arr[1])) {
                $j ++;
            }
        }
        
        $i = 1;
        for ($i; $i <= $j; $i ++) { // $i == 1为最高位 递减
            for ($k = 0; $k <= 9; $k ++) {
                if ($v['ball_' . $i] == $k) {
                    $gailv_arr[$i][$k] += 1;
                }
            }
        }
    }
}
// p($gailv_arr);

?>
<div id="bc_table">
			<table width="100%" border="0" cellpadding="0" cellspacing="1"
				class="game_table">
				<tbody>
					<tr class="tbtitle" style="height: 25px">
						<th class="ball_ff" style="width: 40%">今天</th>
						<th class="ball_ff">0</th>
						<th class="ball_ff">1</th>
						<th class="ball_ff">2</th>
						<th class="ball_ff">3</th>
						<th class="ball_ff">4</th>
						<th class="ball_ff">5</th>
						<th class="ball_ff">6</th>
						<th class="ball_ff">7</th>
						<th class="ball_ff">8</th>
						<th class="ball_ff">9</th>
					</tr>
					<tr class="tbtitle">
						<td class="ball_ff">万位OXXXX出球率</td>


  <?php
for ($i = 0; $i < 10; $i ++) {
    
    if ($gailv_arr[1][$i] && $gailv_arr[1][$i] < 2) {
        ?>
<td style=""><?=$gailv_arr[1][$i] ?></td>

 <?php  }else if($gailv_arr[1][$i] > 1){ ?>

<td style="color: #ff0000; font-weight: bold"><?=$gailv_arr[1][$i] ?></td>

<?php  }else{ ?>
   <td style="">0</td>
<?php }} ?>
  </tr>
					<tr class="tbtitle">
						<td class="ball_ff">千位XOXXX出球率</td>
      <?php
    for ($i = 0; $i < 10; $i ++) {
        
        if ($gailv_arr[2][$i] && $gailv_arr[2][$i] < 2) {
            ?>
<td style=""><?=$gailv_arr[2][$i] ?></td>

 <?php  }else if($gailv_arr[2][$i] > 1){ ?>

<td style="color: #ff0000; font-weight: bold"><?=$gailv_arr[2][$i] ?></td>

<?php  }else{ ?>
   <td style="">0</td>
<?php }} ?>
  </tr>
					<tr class="tbtitle">
						<td class="ball_ff">百位XXOXX出球率</td>
     <?php
    for ($i = 0; $i < 10; $i ++) {
        
        if ($gailv_arr[3][$i] && $gailv_arr[3][$i] < 2) {
            ?>
<td style=""><?=$gailv_arr[3][$i] ?></td>

 <?php  }else if($gailv_arr[3][$i] > 1){ ?>

<td style="color: #ff0000; font-weight: bold"><?=$gailv_arr[3][$i] ?></td>

<?php  }else{ ?>
   <td style="">0</td>
<?php }} ?>
  </tr>
					<tr class="tbtitle">
						<td class="ball_ff">拾位XXXOX出球率</td>
     <?php
    for ($i = 0; $i < 10; $i ++) {
        
        if ($gailv_arr[4][$i] && $gailv_arr[4][$i] < 2) {
            ?>
<td style=""><?=$gailv_arr[4][$i] ?></td>

 <?php  }else if($gailv_arr[4][$i] > 1){ ?>

<td style="color: #ff0000; font-weight: bold"><?=$gailv_arr[4][$i] ?></td>

<?php  }else{ ?>
   <td style="">0</td>
<?php }} ?>
  </tr>
					<tr class="tbtitle">
						<td class="ball_ff">个位XXXXO出球率</td>
     <?php
    for ($i = 0; $i < 10; $i ++) {
        
        if ($gailv_arr[5][$i] && $gailv_arr[5][$i] < 2) {
            ?>
    <td style=""><?=$gailv_arr[5][$i] ?></td>

 <?php  }else if($gailv_arr[5][$i] > 1){ ?>

<td style="color: #ff0000; font-weight: bold"><?=$gailv_arr[5][$i] ?></td>

<?php  }else{ ?>
   <td style="">0</td>
<?php }} ?>
  </tr>
					<!-- <tr class="tbtitle">
  <td class="ball_ff">全五无出期数</td>
    <td style="">1</td>
  <td style="">4</td>
  <td style="">1</td>
  <td style="">0</td>
  <td style="">4</td>
  <td style="">2</td>
  <td style="">3</td>
  <td style="">3</td>
  <td style="">1</td>
  <td style="">0</td>
  </tr> -->
				</tbody>
			</table>
		</div>
		<iframe src="./show_zoushi.php?type=2" width="100%" height="150"
			frameborder="0" marginheight="1" marginwidth="1"></iframe>
	</div>





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
