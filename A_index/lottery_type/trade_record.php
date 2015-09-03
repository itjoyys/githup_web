<?php
include_once("../include/config.php");



switch ($_GET['type']) {
	case '1':
		//福彩3D

        $title_f = '福彩3D';
		break;
	case '2':
		//排列三

        $title_f = '排列三';
		break;
	case '3':
		//重庆时时彩

        $title_f = '重庆时时彩';
		break;
	case '4':
		//北京快乐8

        $title_f = '北京快乐8';
		break;
	case '5':
		//北京赛车PK拾

        $title_f = '北京赛车PK拾';
		break;
	case '6':
		//六合彩

        $title_f = '六合彩';
		break;
	case '7':

        $title_f = '广东快乐十分';
		break;
	case '8':
		//重庆快乐十分

        $title_f = '重庆快乐十分';
		break;
	default:
		# code...
		break;
}
 $record= M('c_bet',$db_config)->field('mingxi_1,mingxi_2,odds,money')->where("type='".$title_f."'")->order('datetime desc')->limit('0,8')->select();
 p($record);
 echo 1111;
?>

<html><head><link type="text/css" rel="stylesheet" href="public/css/mem_order_ft.css?=24">
<script type="text/javascript">

window.onload = function (){
	parent.onloadSet(document.body.scrollWidth,document.body.scrollHeight,"rec_frame");
}
function re_load(){
	window.location.href=window.location;
}

</script>
</head><body id="OHIS">
	<div class="ord">
			<div class="title"><h1></h1>
				<div onclick="re_load();" class="tiTimer"></div>
			</div>

						<div class="show">

				<div class="tname">
					<em>				百定位:双</em> @ <font color="#CC0000">1.94</font><br>
					<b class="gold"><span class="fin_gold">RMB&nbsp;<b>10</b></span></b>
					<div style="display:none" class="error"></div>
				</div>

			</div>


						</div>

</body></html>