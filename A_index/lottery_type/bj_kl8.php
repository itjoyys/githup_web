<?php
include_once ("../include/config.php");
include ("../include/public_config.php");

include ("./include/order_info.php");
include ("./include/function.php");
$publicdb = $db_config;
$style = 'bj_8';
// 重庆时时彩赔率读取
if (! empty ( $_GET ['type'] )) {
	$bj_kl8_odds = M ( 'c_odds_8', $db_config )->where ( "type='" . $_GET ['type'] . "'" )->find ();
	$ball_limit=11;
	$ball_limit_down;
	switch ($_GET ['type']) {
		case 'ball_1' :
			$title_3d = '选一';
			break;
		case 'ball_2' :
			$title_3d = '选二';
			$ball_limit=6;
			$ball_limit_down = 2;
			break;
		case 'ball_3' :
			$title_3d = '选三';
			$ball_limit=7;
			$ball_limit_down = 3;
			break;
		case 'ball_4' :
			$title_3d = '选四';
			$ball_limit=8;
			$ball_limit_down = 4;
			break;
		case 'ball_5' :
			$title_3d = '选五';
			$ball_limit=8;
			$ball_limit_down = 5;
			break;
		case 'ball_6' :
			$title_3d = '和值';
			break;
		case 'ball_7' :
			$title_3d = '上中下';
			break;
		case 'ball_8' :
			$title_3d = '奇和偶';
			break;
	}
	$is_lock = M("fc_games_type",$db_config)->field("*")->where("state = 0 and fc_type = '" . $title_3d . "' and wanfa = '" . $style . "'")->select();
  if($is_lock){
    echo '<script type="text/javascript">alert("玩法维护中，请选择其他玩法");history.go(-1);</script>';
    exit;
  }
}
//查当前玩法已下注的总额
$beted = beted_limit(8,$title_3d,$db_config);
$ball_limit_num = $beted['sum(money)'];


// 查询期数,中奖号
$qishu = M ( 'c_auto_8', $db_config );
$num = $qishu->field ( "*" )->order ( "datetime desc" )->find ();


//获取开盘时间，封盘时间开盘状态判断时间，封盘状态判断时间的数组
$array = set_arraypan(8,$db_config);

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
<?php  include("./include/common_limit.php");  ?>
<script src="public/js/select.js" type="text/javascript"></script>
</head>
<body marginwidth="3" marginheight="3" id="HOP"
	ondragstart="window.event.returnValue=false"
	oncontextmenu="window.event.returnValue=false"
	onselectstart="event.returnValue=false">
	<div id="title_3d" style="display: none"><?=$title_3d?></div>
	<div id="style" style="display: none"><?=$style?></div>
	<div id="fc_type" style="display: none">8</div>
	<div id="kq_box_001" style="display: none;">
		<div id="kq_box_num" style="margin: 50px auto 0">
			距离 第 <b><font color="#7c3838"> <?=dq_qishu(8,$publicdb)+1?> </font></b> 期 开盘时间还有：<font color="#7AFF00"><strong><span
					id="close_time" style="float: none; display: inline"></span></strong>

		</div>
	</div>
	<script type="text/javascript">
	$(function(){
		//判断选2到选5勾选个数
		$("input[type='checkbox']").click(function(event) {

			var obj = $(this);
			var in_type_1=0;
	        $("input[type='checkbox']").each(function() {
	            if($(this).attr("checked")==undefined){
	            }else{
	              in_type_1++;
	                <?php if($ball_limit!=11){ ?>
			        	if(in_type_1> <?=$ball_limit  ?>){

					      obj.attr('checked', false);
					        alert("最多选择"+<?=$ball_limit  ?>+"个号码！");

			        	}
			        <?php } ?>
	            }
	        });
		});

	})

  var o_stime = <?=$array['o_t_stro']?>;
  var f_stime = <?=$array['f_t_stro']?>;


  function check_submit2(){
    var inputs = $("input[type='checkbox']");
    var checked_counts = 0;
    for(var i=0;i<inputs.length;i++){
        if(inputs[i].checked){checked_counts++;
        }
    }
    if(checked_counts!=2){
      alert("请选择2个号码！");
      return false;
    }
    if($("#gold").val() == ''){
      alert("请填写下注金额！");
      return false;
    }
  }
  function check_submit3(){
    var inputs = $("input[type='checkbox']");
    var checked_counts = 0;
    for(var i=0;i<inputs.length;i++){
        if(inputs[i].checked){checked_counts++;
        }
    }
    if(checked_counts!=3){
      alert("请选择3个号码！");
      return false;
    }
    if($("#gold").val() == ''){
      alert("请填写下注金额！");
      return false;
    }
  }
  function check_submit4(){
    var inputs = $("input[type='checkbox']");
    var checked_counts = 0;
    for(var i=0;i<inputs.length;i++){
        if(inputs[i].checked){checked_counts++;
        }
    }
    if(checked_counts!=4){
      alert("请选择4个号码！");
      return false;
    }
    if($("#gold").val() == ''){
      alert("请填写下注金额！");
      return false;
    }
  }
  function check_submit5(){
    var inputs = $("input[type='checkbox']");
    var checked_counts = 0;
    for(var i=0;i<inputs.length;i++){
        if(inputs[i].checked){checked_counts++;
        }
    }
    if(checked_counts!=5){
      alert("请选择5个号码！");
      return false;
    }
    if($("#gold").val() == ''){
      alert("请填写下注金额！");
      return false;
    }
  }
  //submit验证
  $(function(){
     $("#order_form").submit(function(event) {

			var in_type_1=0;
	        $("input[type='checkbox']").each(function() {
	            if($(this).attr("checked")==undefined){
	            }else{
	              in_type_1++;
	            }
	        });

	 		if(in_type_1 < <?=$ball_limit_down?$ball_limit_down:0;?>){
	 			alert("最少选择"+<?=$ball_limit_down?$ball_limit_down:0; ?>+"个号码！");
	 			return false;
	 		}

		return check_submit();
     });
  })
</script>
	<script src="public/js/orderFunc.js" type="text/javascript"></script>

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

setInterval(function(){lottery(8,20,<?=$num['qishu']?>);},30000);


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
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_1'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_2'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_3'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_4'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_5'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_6'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_7'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_8'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_9'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_10'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_11'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_12'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_13'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_14'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_15'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_16'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_17'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_18'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_19'] ?></li>
							<li align="right" class="kjjg_li" style="margin: 0px -1px;"><?=$num['ball_20'] ?></li>
						</ul>
					</td>
				</tr>

			</tbody>
		</table>
		<table border="0" cellpadding="0" cellspacing="1" class="game_table">
			<tbody>
				<tr class="tbtitle">
					<td colspan="15" id="time_td"><font>当前期数：<font class="colorDate"><b><?=dq_qishu(8,$publicdb)?></b></font>
							期&nbsp;&nbsp;&nbsp;&nbsp;下注金额：<span id="allgold">0</span></font>
						<font>&nbsp;&nbsp;&nbsp;&nbsp;距离封盘时间：</font><span id="time_over"
						style="color: #7AFF00; font-size: bold;"></span>
					<!-- 时间储存 --></td>
				</tr>

				<form autocomplete="off" name="orders" id="order_form" method="get"
					target="k_meml" action="main_left.php">
					<input type="hidden" name="type" value="8"> <input type="hidden"
						name="qishu"
						value="<?=dq_qishu(8,$publicdb)?>">
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
<?php if($_GET['type'] != 'ball_7' & $_GET['type'] != 'ball_8'){ ?>
    <th>号码</th>
						<th>赔率</th>
						<th>金额</th>
						<th>号码</th>
						<th>赔率</th>
						<th>金额</th>
  <?php } ?>
  </tr>
  <?php

if ($_GET ['type'] === 'ball_1') {
			?>
<!------------------------------------------   选一 ------------------------------------------------------>
    <?php
    	$titl = "选1中1 赔率：3";
			// 循环出80个数字
			for($i = 0; $i < 16; $i ++) {
				?>
  <tr>

						<td class="ball_bg"><?=$i*5+1; ?></td>
						<td class="ball_ff"><span rate="true" id="bl_1"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_1"
							name="<?=$_GET['type'] ?>_<?=$i*5+1; ?>"></td>


						<td class="ball_bg"><?=$i*5+2; ?></td>
						<td class="ball_ff"><span rate="true" id="bl_2"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="1" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_2"
							name="<?=$_GET['type'] ?>_<?=$i*5+2; ?>"></td>


						<td class="ball_bg"><?=$i*5+3; ?></td>
						<td class="ball_ff"><span rate="true" id="bl_3"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="2" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_3"
							name="<?=$_GET['type'] ?>_<?=$i*5+3; ?>"></td>


						<td class="ball_bg"><?=$i*5+4; ?></td>
						<td class="ball_ff"><span rate="true" id="bl_4"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_4"
							name="<?=$_GET['type'] ?>_<?=$i*5+4; ?>"></td>


						<td class="ball_bg"><?=$i*5+5; ?></td>
						<td class="ball_ff"><span rate="true" id="bl_5"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_5"
							name="<?=$_GET['type'] ?>_<?=$i*5+5; ?>"></td>
					</tr>
   	<?php } ?>
    <!------------------------------- 选二到选五 ------------------------------------------------------>
    	<?php }elseif($_GET['type'] ==='ball_2' || $_GET['type'] ==='ball_3' || $_GET['type'] === 'ball_4' || $_GET['type'] ==='ball_5') { ?>


     <?php
     //获取赔率
     $odds	= lottery_odds(8,$_GET['type'],1);
     $odds4 = lottery_odds(8,$_GET['type'],2,1);
     $odds3 = lottery_odds(8,$_GET['type'],3,1);
     $titl = "赔率：";
     	switch ($_GET['type']){
     		case 'ball_2' : $titl .= "二中二:".$odds;break;
     		case 'ball_3' : $titl .= "三中三:".$odds.",三中二:".$odds4;break;
     		case 'ball_4' : $titl .= "四中四:".$odds.",四中三:".$odds4.",四中二:".$odds3;break;
     		case 'ball_5' : $titl .= "五中五:".$odds.",五中四:".$odds4.",五中三:".$odds3;break;
     	}
			// 循环出80个数字
			for($i = 0; $i < 16; $i ++) {
				?>
  <tr>

						<td class="ball_bg"><?=$i*5+1; ?></td>
						<td class="ball_ff"><span id="bl_1"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="0" type="checkbox"
							class="" id="Num_1" name="<?=$_GET['type'] ?>_<?=$i*5+1; ?>"></td>


						<td class="ball_bg"><?=$i*5+2; ?></td>
						<td class="ball_ff"><span id="bl_2"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="1" type="checkbox"
							id="Num_2" name="<?=$_GET['type'] ?>_<?=$i*5+2; ?>"></td>


						<td class="ball_bg"><?=$i*5+3; ?></td>
						<td class="ball_ff"><span id="bl_3"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="2" type="checkbox"
							id="Num_3" name="<?=$_GET['type'] ?>_<?=$i*5+3; ?>"></td>


						<td class="ball_bg"><?=$i*5+4; ?></td>
						<td class="ball_ff"><span id="bl_4"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="3" type="checkbox"
							id="Num_4" name="<?=$_GET['type'] ?>_<?=$i*5+4; ?>"></td>


						<td class="ball_bg"><?=$i*5+5; ?></td>
						<td class="ball_ff"><span id="bl_5"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input pid="0" num="4" type="checkbox"
							id="Num_5" name="<?=$_GET['type'] ?>_<?=$i*5+5; ?>"></td>
					</tr>

    <?php } ?>
    <tr>
						<td colspan="15" class="ball_ff" style="text-align: right;">金额：<input
							type="text" js='js' style="width: 60px;" id="gold" name="gold"></td>
					</tr>


   	<?php }elseif($_GET['type'] === 'ball_6') { ?>
    <!------------------------------- 和值 ------------------------------------------------------>
					<tr class="tbtitle">
						<td class="ball_bg" align="center" valign="middle">总和大</td>
						<td class="ball_ff"><span rate="true" id="bl_274"><?=$bj_kl8_odds['h1']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_274"
							name="<?=$_GET['type'] ?>_1"></td>

						<td class="ball_bg" align="center" valign="middle">总和小</td>
						<td class="ball_ff"><span rate="true" id="bl_275"><?=$bj_kl8_odds['h2']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_275"
							name="<?=$_GET['type'] ?>_2"></td>

						<td class="ball_bg" align="center" valign="middle">总和单</td>
						<td class="ball_ff"><span rate="true" id="bl_276"><?=$bj_kl8_odds['h3']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_276"
							name="<?=$_GET['type'] ?>_3"></td>

						<td class="ball_bg" align="center" valign="middle">总和双</td>
						<td class="ball_ff"><span rate="true" id="bl_277"><?=$bj_kl8_odds['h4']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_277"
							name="<?=$_GET['type'] ?>_4"></td>

						<td class="ball_bg" align="center" valign="middle">总和810</td>
						<td class="ball_ff"><span rate="true" id="bl_279"><?=$bj_kl8_odds['h5']?></span></td>
						<td class="ball_ff"><input type="text" js='js'
							style="height: 18px" class="input1" size="4" id="Num_279"
							name="<?=$_GET['type'] ?>_5"></td>
					</tr>

			</tbody>
		</table>
		<!------------------------------------------- 上中下 ---------------------------------------------->
      <?php

} else if ($_GET ['type'] === 'ball_7') {
			?>
   <tr class="tbtitle">
			<td class="ball_bg" align="center" valign="middle">上盘</td>
			<td class="ball_ff"><span rate="true" id="bl_274"><?=$bj_kl8_odds['h1']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_274" name="<?=$_GET['type'] ?>_1"></td>

			<td class="ball_bg" align="center" valign="middle">中盘</td>
			<td class="ball_ff"><span rate="true" id="bl_275"><?=$bj_kl8_odds['h2']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_275" name="<?=$_GET['type'] ?>_2"></td>

			<td class="ball_bg" align="center" valign="middle">下盘</td>
			<td class="ball_ff"><span rate="true" id="bl_276"><?=$bj_kl8_odds['h3']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_276" name="<?=$_GET['type'] ?>_3"></td>


		</tr>
		</tbody>
		</table>
  	<?php } ?>

<!------------------------------------------------------- 奇和偶 ---------------------------------------->
<?php

if ($_GET ['type'] === 'ball_8') {

	?>

   <tr class="tbtitle">
			<td class="ball_bg" align="center" valign="middle">奇盘</td>
			<td class="ball_ff"><span rate="true" id="bl_274"><?=$bj_kl8_odds['h1']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_274" name="<?=$_GET['type'] ?>_1"></td>

			<td class="ball_bg" align="center" valign="middle">和盘</td>
			<td class="ball_ff"><span rate="true" id="bl_275"><?=$bj_kl8_odds['h2']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_275" name="<?=$_GET['type'] ?>_2"></td>

			<td class="ball_bg" align="center" valign="middle">偶盘</td>
			<td class="ball_ff"><span rate="true" id="bl_276"><?=$bj_kl8_odds['h3']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_276" name="<?=$_GET['type'] ?>_3"></td>
			<!-- <td colspan="6"></td> -->
		</tr>


  <?php

}
if ($_GET ['type'] === 'ball_111' || $_GET ['type'] === 'ball_112' || $_GET ['type'] === 'ball_311' || $_GET ['type'] === 'ball_411' || $_GET ['type'] === 'ball_511') {
	?>

		<!------------------------------------------- 大小单双 ------------------------------------------------>

		<tr class="tbtitle">
			<td class="ball_bg" align="center" valign="middle">大</td>
			<td class="ball_ff"><span rate="true" id="bl_21"><?=$bj_kl8_odds['h11']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_21" name="<?=$_GET['type'] ?>_11"></td>

			<td class="ball_bg" align="center" valign="middle">小</td>
			<td class="ball_ff"><span rate="true" id="bl_22"><?=$bj_kl8_odds['h12']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_22" name="<?=$_GET['type'] ?>_12"></td>

			<td class="ball_bg" align="center" valign="middle">单</td>
			<td class="ball_ff"><span rate="true" id="bl_23"><?=$bj_kl8_odds['h13']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_23" name="<?=$_GET['type'] ?>_13"></td>

			<td class="ball_bg" align="center" valign="middle">双</td>
			<td class="ball_ff"><span rate="true" id="bl_24"><?=$bj_kl8_odds['h14']?></span></td>
			<td class="ball_ff"><input type="text" js='js' style="height: 18px"
				class="input1" size="4" id="Num_24" name="<?=$_GET['type'] ?>_14"></td>


			<td colspan="3"></td>
		</tr>
     <?php }?>

		<table width="100%" border="0" cellpadding="0" cellspacing="1"
			class="game_table">
			<tbody>
				<tr class="hset2">
					<td colspan="13" class="tbtitle">
						<div style="float: right !important">
						<span style="margin: 0 250px 0 0;"><?=$titl ?></span>
							<input name="btnSubmit" type="submit" class="button_a"
								id="btnSubmit" value="下注"> <input type="reset"
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