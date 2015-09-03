<?php
include_once("../include/config.php");

include ("./liuhecai/config.php");

$type_y = 6;
if($_GET['name']){
$beted=beted_limit($_GET['fc_type'],$_GET['type'],$db_config);
$ball_limit_num = $beted['sum(money)'];
}else{
    $ball_limit_num =0;
}
$_GET=array_filter($_GET);
if(!empty($_GET['fc_type'])){
	if($_GET['fc_type']==2){
	    $type_y = 7;
		$type='重庆时时彩';
	}
	// else if($_GET['fc_type']==9){
	// 	$type='上海时时彩';
	// }
	else if($_GET['fc_type']==10){
		$type='天津时时彩';
	}else if($_GET['fc_type']==11){
		$type='江西时时彩';
	}else if($_GET['fc_type']==12){
		$type='新疆时时彩';
	}else if($_GET['fc_type']==1){
		$type='广东快乐十分';
	}else if($_GET['fc_type']==8){
		$type='北京快乐8';
	}else if($_GET['fc_type']==4){
		$type='重庆快乐十分';
	}else if($_GET['fc_type']==6){
		$type='排列三';
	}else if($_GET['fc_type']==3){
		$type='北京赛车PK拾';
	}else if($_GET['fc_type']==5){
		$type='福彩3D';
	}else if($_GET['fc_type']==9){
		$type='六合彩';
	}else if($_GET['fc_type']==13){
		$type='江苏快3';
	}else if($_GET['fc_type']==14){
		$type='吉林快3';
	}
}

 ?>
 <!doctype html>
  <html lang="en">
  <head>
  	<meta charset="UTF-8">
  	<title></title>
 <link rel="stylesheet" href="public/css/mem_order_ft.css">
 <link rel="stylesheet" href="public/css/mem_body_olympics.css" type="text/css">
 <link rel="stylesheet" href="public/css/mem_order_sel.css?=" type="text/css">
 <script src="public/js/jquery-1.8.3.min.js"></script>
 <script>

$(function(){
	 var single_field_max = $("#single_field_max").text(); //单注总金额上限200000
     var single_note_max = $("#single_note_max").text();//单注金额上限20000
     var single_note_min = $("#single_note_min").text();
     // alert(single_note_max);
     $(".single_note_max").text(single_note_max);
     $(".single_field_max").text(single_field_max);
     $(".single_note_min").text(single_note_min);
     var ball_limit_num = <?=$ball_limit_num ?>;//已投注的金额

	//下单验证
	if($("#gold").val()>0 && $("#bet_val")){

		var bet_val3=$("#bet_val").text();
		var thi_val=$("#gold").val();
		new_val3 = thi_val*bet_val3;
		$("#total").text(new_val3);
	}
	$("#gold").keyup(function() {

		var val1 = $(this).val();
		if(val1 != ""){
			 var reg = /([`~!@#$%^&*()_+<>?:"{},\/;\'[\]])/ig;
		     var reg1 = /([·~！#@￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/ig;
		     var reg2= /([a-zA-Z]*)/ig;
		     $(this).val(val1.replace(reg, ""));
		     $(this).val(val1.replace(reg1, ""));
		     $(this).val(val1.replace(reg2, ""));
		     val1 = $(this).val();
			if(val1 < parseInt(single_note_min) || val1<0 || val1 >parseInt(single_note_max)&& (parseInt(val1)+parseInt(ball_limit_num)) <parseInt(single_field_max)){
				alert("金额格式错误或超过上限！");
				$(this).val(0);
				$("#total").text(0);
			}else{
				var bet_val=$("#bet_val").text();
				var new_val=val1*bet_val;
					new_val+="";
				Math.round(new_val);
				$("#total").text(new_val);

			}
		}else{
			$("#total").text('0');
		}
	});


	//点击赔率下注
	$("#order_form").submit(function(event) {
		var val = $("#gold").val();
		if(val=="" || val ==0){
			alert("请填写下注金额！");
				return false;
		}
		var val1 = $("#total").text();
		var msg = "下注金额："+val+"\n"+"确定进行下注吗？";
		if(isNaN(val) || isNaN(val1)){
			alert("金额错误！");
				return false;
		}
		if(!confirm(msg)){
			return false;
		}else{
			if(isNaN($("#total").text())){
				alert("金额错误！");
				return false;
			}
		}
	});
	//点击下注下注
	$("#order_form_1").submit(function(event) {
		var val = $("#total_sum").text();
		if(val=="" || val ==0){
			alert("请填写下注金额！");
				return false;
		}
		var val1 = $("#total").text();
		var msg = "下注金额："+val+"\n"+"确定进行下注吗？";
		if(isNaN(val) || isNaN(val1)){
			alert("金额错误！");
				return false;
		}
		if(!confirm(msg)){
			return false;
		}else{
			if(isNaN($("#total").text())){
				alert("金额错误！");
				return false;
			}
		}
	});

	//下注显示页面点击取消时间
	$("input[name='btnCancel']").click(function(event) {
		window.parent.showOrder();
	});
})
//点击叉叉 删除当前的记录
function Revmove(v,m){
	$("#lh"+v).html(' ');
	var a = $("#total_sum").text();
	var tot = a-m;
	$("#total_sum").text(tot);
}
function Revmove_bj(v,m){

	$("#bj"+v).html(' ');
	var a = $("#total_sum").text();
	var tot = a-m;
	$("#total_sum").text(tot);
}


 </script>
  </head>
  <body style="background:none;">
  <?php

if(!empty($_GET['action']) && $_GET['action'] == 'dan'){
//一般彩票点击赔率显示

   ?>
    <form autocomplete="off" name="order_form" id="order_form" method="post" action="Order.php?type=<?=$_GET['fc_type'] ?>">
<div class="ord">
		<div class="title"><h1><?=$type ?></h1></div>
		<div class="main">
		<? if($_GET['type']=='龍虎'){?>
		<input type="hidden" name="longhu" value="longhu">
		<? }?>
		<input type="hidden" value="<?=$_GET['qishu'] ?>" name="qishu">
		  <div class="leag">下注类别: <strong> <?=$_GET['type'] ?></strong></div>
		  <p class="team"><font color="#0000ff"><?=$_GET['haoma'] ?></font> @<font color="red">賠率：<strong id="bet_val"><?=$_GET['bet'] ?></strong></font></p>
		  <p class="error" style="display: none;"></p>
		  <div class="betdata">
			  <p class="amount">交易金额：<input name="<?=$_GET['name'] ?>" type="text" class="txt" id="gold" onkeypress="return CheckKey(event)" onkeyup="return CountWinGold()" size="8" maxlength="10"></p>
			  <p class="minBet"><span class="bet_txt">单注最低：</span><span class="single_note_min">0</span></p>
			  <p class="maxBet"><span class="bet_txt">单注限额：<span class="single_note_max">20000</span></span></p>
			  <p class="maxBet_1"><span class="bet_txt">单项限额：<span class="single_field_max">200000</span></span></p>
		</div>
		</div>
		<div id="gWager" style="display: none;position: absolute;"></div>
		<div id="gbutton" style="display: block;position: absolute;"></div>
		<div class="betBox">
			<input type="button" name="btnCancel" value="取消" onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>'" class="no">
			<input type="submit" name="btnSubmit" value="确定交易"  class="yes">
		</div>
	</div></form>
<?php }else if(!empty($_GET['qishu'])){

//一般彩票点击下注显示
?>
<form autocomplete="off" name="order_form" id="order_form_1" method="post" action="Order.php?type=<?=$_GET['fc_type'] ?>&qishu=<?=$_GET['qishu'] ?>">
<input type="hidden" value="<?=$_GET['qishu'] ?>" name="qishu">
 <div class="ord">

		<div class="title"><h1><?=$type ?></h1></div>
		<div class="main">

 <?php
 	include ("./include/get_zuhe.php");
 	include ("./include/ball_name.php");
	include_once("../include/config.php");
	include ("../include/public_config.php");
	$qishu = $_GET['qishu'];
	//清空所有POST数据为空的表单
	$datas = array_filter($_GET);
 // var_dump($datas);
	//获取清空后的POST键名
	$names = array_keys($datas);
	// var_dump($datas);
$all_money;//下注金额
$all_money_1;//可赢金额
$j=0;
	for ($i = 0; $i < count($datas); $i++){
		//分割键名，取ball_后的数字，来判断属于第几球
		$qiu	= explode("_",$names[$i]);
		if($qiu[0]!='ball'){continue;}
		$qiuhao = $ball_name['qiu_'.$qiu[1]];

		if($_GET['type']==1){

			if( $qiu[1] == 9 ){
				$wanfa	= $ball_name_zh['ball_'.$qiu[2].''];
			}else{
				$wanfa	= $ball_name['ball_'.$qiu[2].''];
			}
		}else if($_GET['type']==2||$_GET['type']==9||$_GET['type']==10||$_GET['type']==11||$_GET['type']==12){
		    if( $qiu[1] == 6 ){
			$wanfa	= $ball_name_zh['ball_'.$qiu[2].''];
			}else if( $qiu[1] == 7 ||$qiu[1] == 8 || $qiu[1] == 9 ){
				$wanfa	= $ball_name_s['ball_'.$qiu[2].''];
			}else if( $qiu[1] == 10){
				$wanfa	= $ball_name_s['nball_'.$qiu[2].''];
			}else if( $qiu[1] == 11){
				$wanfa	= $ball_name_s['shball_'.$qiu[2].''];
			}else{
				$wanfa	= $ball_name['ball_'.$qiu[2].''];
			}
		}else if($_GET['type']==3){
			if( $qiu[1] == 11 ){
			$wanfa	= $ball_name_h['ball_'.$qiu[2].''];
			}else{
				$wanfa	= $ball_name['ball_'.$qiu[2].''];
			}
		}else if($_GET['type']==13 ||$_GET['type']==14){
			if ( $qiu[1] == 2 ) {
				$wanfa	= $ball_name_s['ball_'.$qiu[2].''];
			}elseif ( $qiu[1] == 3 ){
				$wanfa	= $ball_name_f['ball_'.$qiu[2].''];
			}elseif ( $qiu[1] == 4 ){
				$wanfa	= $ball_name_h['ball_'.$qiu[2].''];
			}elseif ( $qiu[1] == 5 ){
				$wanfa	= $ball_name_g['ball_'.$qiu[2].''];
			}else{
				$wanfa	= $ball_name['ball_'.$qiu[2].''];
			}
		}else if($_GET['type']==4){

			if( $qiu[1] == 9 ){
				$wanfa	= $ball_name_zh['ball_'.$qiu[2].''];
			}else{
				$wanfa	= $ball_name['ball_'.$qiu[2].''];
			}
		}else if($_GET['type']==5){
			if( $qiu[1] == 4 ){
			$wanfa	= $ball_name_zh['ball_'.$qiu[2].''];
			}else if( $qiu[1] == 5 ){
				$wanfa	= $ball_name_s['ball_'.$qiu[2].''];
			}else{
				$wanfa	= $ball_name['ball_'.$qiu[2].''];
			}
		}else if($_GET['type']==6){
			if( $qiu[1] == 4 ){
			$wanfa	= $ball_name_zh['ball_'.$qiu[2].''];
			}else if( $qiu[1] == 5 ){
				$wanfa	= $ball_name_s['ball_'.$qiu[2].''];
			}else{
				$wanfa	= $ball_name['ball_'.$qiu[2].''];
			}
		}else if($_GET['type']==8){

			$arr_zuhe = array();
			$arr_zuhe_1 = array();
			$names_s=array();

			for($ii=0; $ii < count($names); $ii++ )
			{
					$qiu1	= explode("_",$names[$ii]);
					if($qiu1[0]!='ball'){continue;}
					$arr_zuhe[] = $qiu1[2];
					// if($qiu1[1] > 1  && $qiu[1]<6)
					$names_s[]=$names[$ii];
			}


				if( $qiu[1] == 2 ){
					$n1 =  str_replace("2_","",$names_s[0]);
					$n2 =  str_replace("2_","",$names_s[1]);
					$wanfa	= $ball_name[$n1].",".$ball_name[$n2];
					if(!empty($_GET)){
					 	foreach ($_GET as $k => $v) {
					 		$val = explode("_", $k);
					 		if($val[0] == 'ball'){
					 			//echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
					 		}
					 	}
			 		}
				}else if( $qiu[1] == 3 ){
					$n1 =  str_replace("3_","",$names_s[0]);
					$n2 =  str_replace("3_","",$names_s[1]);
					$n3 =  str_replace("3_","",$names_s[2]);
					$wanfa	= $ball_name[$n1].",".$ball_name[$n2].",".$ball_name[$n3];
					if(!empty($_GET)){
					 	foreach ($_GET as $k => $v) {
					 		$val = explode("_", $k);
					 		if($val[0] == 'ball'){
					 			//echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
					 		}
					 	}
			 		}

				}else if( $qiu[1] == 4 ){
					$n1 =  str_replace("4_","",$names_s[0]);
					$n2 =  str_replace("4_","",$names_s[1]);
					$n3 =  str_replace("4_","",$names_s[2]);
					$n4 =  str_replace("4_","",$names_s[3]);
					$wanfa	= $ball_name[$n1].",".$ball_name[$n2].",".$ball_name[$n3].",".$ball_name[$n4];
					if(!empty($_GET)){
					 	foreach ($_GET as $k => $v) {
					 		$val = explode("_", $k);
					 		if($val[0] == 'ball'){
					 			//echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
					 		}
					 	}
			 		}
				}else if( $qiu[1] == 5 ){
					$n1 =  str_replace("5_","",$names_s[0]);
					$n2 =  str_replace("5_","",$names_s[1]);
					$n3 =  str_replace("5_","",$names_s[2]);
					$n4 =  str_replace("5_","",$names_s[3]);
					$n5 =  str_replace("5_","",$names_s[4]);
					$wanfa	= $ball_name[$n1].",".$ball_name[$n2].",".$ball_name[$n3].",".$ball_name[$n4].",".$ball_name[$n5];
					if(!empty($_GET)){
					 	foreach ($_GET as $k => $v) {
					 		$val = explode("_", $k);
					 		if($val[0] == 'ball'){
					 			//echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
					 		}
					 	}
			 		}
				}else if( $qiu[1] == 6 ){
					$wanfa	= $ball_name_zh['ball_'.$qiu[2].''];
				}else if( $qiu[1] == 7 ){
					$wanfa	= $ball_name_s['ball_'.$qiu[2].''];
				}else if( $qiu[1] == 8 ){
					$wanfa	= $ball_name_f['ball_'.$qiu[2].''];
				}else{
					$wanfa	= $ball_name['ball_'.$qiu[2].''];
					// echo $qiu[2];
			}
		}

		$money	= $datas[''.$names[$i].''];

		//获取赔率
		if($_GET['type'] == 8){
			$odds =$qiu[1]==1?lottery_odds($_GET['type'],'ball_'.$qiu[1],1):lottery_odds($_GET['type'],'ball_'.$qiu[1],$qiu[2]);
		}else{
			$odds = lottery_odds($_GET['type'],'ball_'.$qiu[1],$qiu[2]);
		}

$all_money+=$money;
$all_money_1+=$money*$odds;
    if(($qiu[1] == 2 || $qiu[1] == 3 || $qiu[1] == 4 ||$qiu[1] == 5) & $_GET['type'] == 8){

    }
	//选2到选5跳出循环
	if( ($qiu[1] == 2 || $qiu[1] == 3 || $qiu[1] == 4 ||$qiu[1] == 5) & $_GET['type'] == 8){
	    $arr_zuhe_1 =  get_zuhe($arr_zuhe,$qiu1[1]);
		$money=$all_money=$_GET['gold'];
		break;
	}

	//北京PK10龙虎玩法转换
	if($_GET['type'] == 3 & $_GET['longhu'] == 'longhu' ){
		if($qiu[1] == 1){
			$qiuhao = '1V10 龍虎';
		}elseif($qiu[1] == 2){
			$qiuhao = '2V9 龍虎';
		}elseif($qiu[1] == 3){
			$qiuhao = '3V8 龍虎';
		}elseif($qiu[1] == 4){
			$qiuhao = '4V7 龍虎';
		}elseif($qiu[1] == 5){
			$qiuhao = '5V6 龍虎';
		}

	}
 ?>

            <div class="div_3"  id="lh<?=$i ?>" style="display:block;">
            <input type="hidden" value="<?=$_GET['qishu'] ?>" name="qishu">
			<div class="team" style="margin-bottom:0">
				<span class="leag_txt"><?=$qiuhao ?>:<?=$wanfa ?>  @ <em><?=$odds ?></em></span>
				<span class="deletebtn"><input type="button" name="delteam1" onclick="Revmove('<?=$i ?>',<?=$money ?>)" class="par">
				</span>
			</div>
			<p class="error" style="display: none;"></p>
			<p class="mayWin_more">金额：<?=$money ?> </p>
			<?php
			if($_GET['type']!=8){


			 ?>
			 <input type="hidden" name="<?php echo 'ball_'.$qiu[1].'_'.$qiu[2]; ?>"  value="<?=$money ?>">
			<?php }else{ ?>
			<input type="hidden" name="<?php echo $names_s[$j]; ?>"  value="<?=$money ?>">

			<?php $j++;} ?>
             </div>


<?php } ?>

		<!-- 选2到选5显示页面 -->
		<?php
		$uu=0;
		for ($u = 0; $u < count($datas); $u++){
		//分割键名，取ball_后的数字，来判断属于第几球
		$qiu	= explode("_",$names[$i]);
		if($qiu[0]!='ball'){continue;}
		if(($qiu[1]!=2 || $qiu[1]!=3 ||$qiu[1]!=4 ||$qiu[1]!=5)&$_GET['type'] == 8 ){
			$uu++;}
		}
		if($uu>0){
		 ?>
		 <input type="hidden" value="<?=$_GET['qishu'] ?>" name="qishu">

            <div class="div_3"  id="lh<?=$i ?>" style="display:block;">
		<?php
		$arr_count = count($arr_zuhe_1);
		$all_money = $arr_count*$_GET['gold'];
		$all_money_1 = $arr_count*$_GET['gold']*$odds;

		foreach ($arr_zuhe_1 as $k1 => $v1) {

			$v_arr = str_replace(",", "_", $v1);
			$v_name = 'ball_'.$qiu[1].'_'.$v_arr;
		 ?> <div id="bj<?=$k1 ?>">

				<input type="hidden" name="<?=$v_name  ?>" value="<?=$_GET['gold'] ?>">

				<div class="team" style="margin-bottom:0">
					<span class="leag_txt"><?=$qiuhao ?>:<?=$v1 ?>  @ <em><?=$odds ?></em></span>
					<span class="deletebtn"><input type="button" name="delteam1" onclick="Revmove_bj('<?=$k1 ?>',<?=$money ?>)" class="par">
					</span>
				</div>
				<p class="error" style="display: none;"></p>
				<p class="mayWin_more">金额：<?=$money ?> </p>
			</div>
		<?php } ?>
			<?php
			if($_GET['type']!=8){


			 ?>
			 <input type="hidden" name="<?php echo 'ball_'.$qiu[1].'_'.$qiu[2]; ?>"  value="<?=$money ?>">
			<?php }else{ ?>


			<?php } ?>
             </div>
			<?php } ?>

		<input type="hidden" name="longhu" value="<?=$_GET['longhu'] ?>">
		<input type="hidden" name="type" value="<?=$_GET['type'] ?>">
             	<p class="error" style="display: none;"></p>
			<div class="betdata">
			  <p class="minBet"><span class="bet_txt">总计1下注：</span><span id="total_sum"><?=$all_money ?></span></p>
			</div><div id="total" style="display:none"><?=$all_money_1 ?></div>
		</div>
		<div id="gWager" style="display: none;position: absolute;"></div>
		<div id="gbutton" style="display: block;position: absolute;"></div>
		<div class="betBox">
			<input type="button" name="btnCancel" value="取消" onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>" class="no">
			<input type="submit" name="btnSubmit" value="确定交易"  class="yes">
		</div>
	</div>

	</form>
<?php }else if(!empty($_GET['action']) && $_GET['action'] == 'dan_1'){
//六合彩点击赔率显示

if($_GET['type']=='特码' || $_GET['type']=='正码' || $_GET['type']=='正特' || $_GET['type']=='正1-6' || $_GET['type']=='半波' || $_GET['type']=='生肖' ){
	$action = 'n1';
}
  ?>
	 <form autocomplete="off" name="order_form" id="order_form" method="post" action="liuhecai.php?action=<?=$action ?>&class2=<?=$_GET['class2'] ?>">
	<div class="ord">
	<input type="hidden" value="<?=$_GET['qishu'] ?>" name="qishu">
		<div class="title"><h1><?=$type ?></h1></div>
		<div class="main">

		  <div class="leag">下注类别: <strong> <?=$_GET['type'] ?></strong></div>
		  <p class="team"><font color="#0000ff"><?=$_GET['haoma'] ?></font> @<font color="red">賠率：<strong id="bet_val"><?=$_GET['bet'] ?></strong></font></p>
		  <p class="error" style="display: none;"></p>
		  <div class="betdata">
			  <p class="amount">交易金额：<input name="<?=$_GET['name'] ?>" type="text" class="txt" id="gold" onkeypress="return CheckKey(event)" onkeyup="return CountWinGold()" size="8" maxlength="10"></p>

			  <p class="minBet"><span class="bet_txt">单注最低：</span><span class="single_note_min">0</span></p>
			  <p class="maxBet"><span class="bet_txt">单注限额：<span class="single_note_max">20000</span></span></p>
			  <p class="maxBet_1"><span class="bet_txt">单项限额：<span class="single_field_max">200000</span></span></p>
		</div>
		</div>
		<div id="gWager" style="display: none;position: absolute;"></div>
		<div id="gbutton" style="display: block;position: absolute;"></div>
		<div class="betBox">
			<input type="button" name="btnCancel" value="取消" onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>" class="no">
			<input type="submit" name="btnSubmit" value="确定交易"  class="yes">
		</div>
	</div></form>


<?php }elseif ($_GET['active']=='shuang2') {
	//过关

		if($_GET['type']=='过关'){	?>
		 <form autocomplete="off" name="order_form" id="order_form" method="post" action="liuhecai.php?action=k_ggsave&class2=<?=$_GET['class2'] ?>">
	<input type="hidden" value="<?=$_GET['qishu'] ?>" name="qishu">

	<div class="ord">
		<div class="title"><h1>过关</h1></div>
		<div class="main">

		  <p class="team" style="text-align:center">
		<?php
		$rate2=1;
		$z=0;
		foreach ($_GET as $k => $v) {
			$class = explode('game', $k);
			if($class[1]!=''){

				if ($_GET['game'.$class[1]]!=""){

				$rate_id=$_GET['game'.$class[1]]+619;

				$rate2=$rate2*ka_bl($rate_id,"rate");

			$z++;

		 ?>

		  <?php  echo ka_bl($rate_id,"class2"); ?>：<?php  echo ka_bl($rate_id,"class3"); ?> @ <font color="red"><?php  echo ka_bl($rate_id,"rate"); ?></font><br>
		  <input type="hidden" name="<?='game'.$class[1] ?>" value="<?=$_GET['game'.$class[1]] ?>">
		<?php }	}
			} ?>

		 -----------------------<br>
		  <font color="#000fff"><?=$z ?>串一</font> @ <font color="red" id="bet_val"><?=$rate2 ?></font></p>
		  <p class="error" style="display: none;"></p>
		  <div class="betdata">
			  <p class="amount">交易金额：<input name="gold" type="text" class="txt" id="gold"  size="8" maxlength="10" value="<?=$_GET['xiazhu_money']  ?>"></p>

			  <p class="minBet"><span class="bet_txt">单注最低：</span><span class="single_note_min">0</span></p>
			  <p class="minBet"><span class="bet_txt">单注限额：<span class="single_note_max">20000</span></span></p>
			  <p class="minBet_1"><span class="bet_txt">单项限额：<span class="single_field_max">500000</span></span></p>
		</div>
		</div>
		<div id="gWager" style="display: none;position: absolute;"></div>
		<div id="gbutton" style="display: block;position: absolute;"></div>
		<div class="betBox">
			<input type="button" name="btnCancel" value="取消" onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>" class="no">
			<input type="submit" name="btnSubmit" value="确定交易"  class="yes">
		</div>
	</div>
	 <input type="hidden" name="rate" id="rate" value="19.54">
     <input type="hidden" name="rateid" id="rateid" value="627,649,643,640">
</form>
		 </form>

<?php	}elseif($_GET['action']=='n2' || $_GET['action']=='k_sxt2save'){	?>
<!-- 合肖 -->

 <form autocomplete="off" name="order_form" id="order_form" method="get" action="liuhecai.php">
<div class="ord">
<input type="hidden" value="<?=$_GET['qishu'] ?>" name="qishu">
<input type="hidden" name="action" value="<?=$_GET['action'] ?>">
<input type="hidden" name="class2" value="<?=$_GET['class2'] ?>">
<input type="hidden" name="min_bl" value="<?=$_GET['min_bl'] ?>">
<?php
	$n=0;
	$number1="";
	$rate1=1;
 foreach ($_GET as $k => $v) {
	$class = explode('num', $k);
		if($class[1]!=''){
			$number1=$number1.",".$class[1];
	$n++;
 ?>
<input type="hidden" name="<?=$k ?>" value="<?=$v ?>">
<?php }}
include("./include/shengxiao_bet.php");
switch ($_GET['class2']) {
	case '二肖':
		if ($n!=2){echo "<script>alert('对不起，请选择二个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '三肖':
		if ($n!=3){echo "<script>alert('对不起，请选择三个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '四肖':
		if ($n!=4){echo "<script>alert('对不起，请选择四个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '五肖':
		if ($n!=5){echo "<script>alert('对不起，请选择五个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '六肖':
		if ($n!=6){echo "<script>alert('对不起，请选择六个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '七肖':
		if ($n!=7){echo "<script>alert('对不起，请选择七个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '八肖':
		if ($n!=8){echo "<script>alert('对不起，请选择八个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '九肖':
		if ($n!=9){echo "<script>alert('对不起，请选择九个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '十肖':
		if ($n!=10){echo "<script>alert('对不起，请选择十个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '十一肖':
		if ($n!=11){echo "<script>alert('对不起，请选择十一个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '二肖连中':
		if ($n<2 or $n>8){echo "<script>alert('对不起，请选择二-八个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '三肖连中':
		if ($n<3 or $n>8){echo "<script>alert('对不起，请选择三-八个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '四肖连中':
		if ($n<4 or $n>8){echo "<script>alert('对不起，请选择四-八个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '五肖连中':
		if ($n<5 or $n>8){echo "<script>alert('对不起，请选择五-八个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '二肖连不中':
		if ($n<2 or $n>8){echo "<script>alert('对不起，请选择二-八个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '三肖连不中':
		if ($n<3 or $n>8){echo "<script>alert('对不起，请选择三-八个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;
	case '四肖连不中':
		if ($n<4 or $n>8){echo "<script>alert('对不起，请选择四-八个生肖!');parent.location.href='main_left.php?type_y=6';</script>";
exit;}
	break;

}
 ?>
 <input type="hidden" value="<?=$_GET['qishu'] ?>" name="qishu">
		<div class="title"><h1>六合彩</h1></div>
		<div class="main">

		  <div class="leag">下注类别: <strong> 合肖</strong></div>

		  <p class="team"><font color="#0000ff"><?=$_GET['ids'] ?></font> @<font color="red">賠率：<strong id="bet_val">  <?=$_GET['min_bl'] ?></strong></font></p>
		  <p class="error" style="display: none;"></p>
		  <div class="betdata">
			  <p class="amount">交易金额：<input name="Num_1" type="text" class="txt" id="gold" value="<?=$_GET['Num_1'] ?>" size="8" maxlength="10"></p>

			  <p class="minBet"><span class="bet_txt">单注最低：</span><span class="single_note_min">0</span></p>
			  <p class="maxBet"><span class="bet_txt">单注限额：<span class="single_note_max">30000</span></span></p>
			  <p class="maxBet_1"><span class="bet_txt">单项限额：<span class="single_field_max">200000</span></span></p>
		</div>
		</div>
		<div id="gWager" style="display: none;position: absolute;"></div>
		<div id="gbutton" style="display: block;position: absolute;"></div>
		<div class="betBox">
			<input type="button" name="btnCancel" value="取消" onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>" class="no">
			<input type="submit" name="btnSubmit" value="确定交易" class="yes">
		</div>
	</div>

</form>
<?php }else{?>
	<!-- 六合彩  所有下单 -->
	 <form autocomplete="off" name="order_form" id="order_form_1" method="post" action="liuhecai.php?action=<?=$_GET['action'] ?>&class2=<?=$_GET['class2'] ?>">
	<div class="ord">
<input type="hidden" value="<?=$_GET['qishu'] ?>" name="qishu">
		<div class="title"><h1><?=$type ?></h1></div>
		<div class="main">

 	<?php
 	$all_money=0;//下注金额
	$all_money_1=0;//可赢金额

 	foreach ($_GET as $k => $v) {
 		if(empty($_GET['class3_1'])){
			$_GET['class3_1']=0;
 		}

 		$class = explode('_', $k);
			if($class[0]=='Num'){
				$class11='class3_'.$class[1];
				$class33 = $_GET[$class11];//号码

				if($_GET['type'] == '正1-6'){
					if($class[1]>0 &&$class[1]<14){
						$class22='正码1';
					}elseif($class[1]>=14&&$class[1]<27){
						$class22='正码2';
					}elseif($class[1]>=27&&$class[1]<40){
						$class22='正码3';
					}elseif($class[1]>=40&&$class[1]<53){
						$class22='正码4';
					}elseif($class[1]>=53&&$class[1]<66){
						$class22='正码5';
					}elseif($class[1]>=66&&$class[1]<79){
						$class22='正码6';
					}
				}else{
					$class22 = $_GET['ids'];
				}
			//获取赔率
			$bet=M("c_odds_7",$db_config)->field("rate")->where("class1='".$_GET['type']."' and class2 = '".$class22."' and class3 = '".$class33."'")->find();
			$all_money+=$v;
			$all_money_1+=$v*$bet['rate'];


 	 ?>

            <div class="div_3" id="lh<?=$k ?>" style="display:block;">
			<div class="team" style="margin-bottom:0">
				<span class="leag_txt"><?=$_GET['type']=="正特"?$_GET['type']."-".$_GET['class2']:$_GET['type']  ?>:<?=$class33;
				 ?>  @ <em><?=$bet['rate'] ?></em></span>
				<span class="deletebtn"><input type="button" name="delteam1" onclick="Revmove('<?=$k ?>',<?=$v ?>)" class="par">
				</span>
			</div>
			<p class="error" style="display: none;"></p>
			<p class="mayWin_more">金额： <?=$v ?> </p>
			<input type="hidden" name="<?=$k ?>" value="<?=$v ?>">
			</div>

	<?php }} ?>
		<input type="hidden" name="type" value="2">
             	<p class="error" style="display: none;"></p>
			<div class="betdata">
			  <p class="minBet"><span class="bet_txt">总计2下注：</span><span id="total_sum"><?=$all_money ?></span></p>
			</div><div id="total" style="display:none"><?=$all_money_1 ?></div>
		</div>
		<div id="gWager" style="display: none;position: absolute;"></div>
		<div id="gbutton" style="display: block;position: absolute;"></div>
		<div class="betBox">
			<input type="button" name="btnCancel" value="取消" onclick="parent.location.href='main_left.php?type_y=<?=$type_y ?>" class="no">
			<input type="submit" name="btnSubmit" value="确定交易" class="yes">
		</div>
	</div>
	 </form>

<?php }}
include("./include/common_limit.php");
?>
  </body>
  </html>