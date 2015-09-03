
<meta charset="UTF-8">
<?php
include_once ("../include/config.php");
include_once ("../include/public_config.php");
include_once dirname(__FILE__) . "/../wh/site_state.php";
GetSiteStatus($SiteStatus,1,'lottery',1);
include_once ('./liuhecai/conjunction.php');
include_once ('./liuhecai/config.php');
include_once ("./include/function.php");

$time = date ( "Y-m-d" , strtotime("+12 hours"));

$time1 = date ( "H:i:s" , strtotime("+12 hours"));
$time1 = explode ( ":", $time1 );
$time1 = $time1 [0] * 60 * 60 + $time1 [1] * 60 + $time1 [2];

$week = date ( "w" , strtotime("+12 hours"));

$action = $_GET ['action'];

//判断玩法关闭
switch ($action) {
	case 'k_tm':
		$type_name = "特码";
		break;
	case 'k_zm':
		$type_name = "正码";
		break;
	case 'k_zt':
		$type_name = "正码特";
		break;
	case 'k_zm6':
		$type_name = "正码1-6";
		break;
	case 'k_gg':
		$type_name = "过关";
		break;
	case 'k_lm':
		$type_name = "连码";
		break;
	case 'k_bb':
		$type_name = "半波";
		break;
	case 'k_sxp':
		$type_name = "一肖/尾数";
		break;
	case 'k_sx':
		$type_name = "特码生肖";
		break;
	case 'k_sx6':
		$type_name = "合肖";
		break;
	case 'k_sxt2':
		$type_name = "生肖连";
		break;
	case 'k_wsl':
		$type_name = "尾数连";
		break;
	case 'k_wbz':
		$type_name = "全不中";
		break;
	default:
		# code...
		break;
}

function is_fengpan($db_config){
	$now_time = date("Y-m-d H:i:s", strtotime("+12 hours"));
	$dangqian = M('c_opentime_7', $db_config);
  $data_time = $dangqian->field("*")
        ->where("ok ='0' and kaijiang >= '" . $now_time . "' and fengpan >= '" . $now_time . "' and kaipan <= '" . $now_time . "'")
        ->order("kaijiang ASC")
        ->find();
  if ($data_time['id'] != "") {
	    return true;
	} else {
	   	echo '<script type="text/javascript">alert("已经封盘，禁止下注！");</script>';
	   	 echo '<script type="text/javascript">parent.location.href="main_left.php?type_y=6"</script>';
		exit;
	}
}

$beted = beted_limit(7,$type_name,$db_config);
//查询该玩法当前期数已下注金额

$ball_limit_num = $beted['sum(money)'];

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
<link rel="stylesheet" href="public/css/xp.css" type="text/css">
<link rel="stylesheet" href="public/css/default.css" type="text/css">
<script src="./public/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="./public/js/js.js" type="text/javascript"></script>
<script src="./public/js/check_form.js" type="text/javascript"></script>
<script src="./public/js/bet.js" type="text/javascript"></script>

<style type="text/css">
body {
	font-size: 12px;
	font-style: normal;
	font-weight: lighter;
	font-variant: normal;
	color: #000;
	text-decoration: line-through;
	SCROLLBAR-FACE-color: #FDD97B;
	SCROLLBAR-HIGHLIGHT-color: #fff;
	SCROLLBAR-SHADOW-color: #F90;
	SCROLLBAR-3DLIGHT-color: buttonface;
	SCROLLBAR-ARROW-color: #000;
	SCROLLBAR-TRACK-color: #eaf0f5;
	SCROLLBAR-DARKSHADOW-color: #fff;
	TEXT-DECORATION: none;
	padding: 0;
	background-color: #E6E8E9;
	text-align: left;
}

.dndn {
	BORDER-RIGHT: #d6d3ce 0px outset;
	BORDER-TOP: #d6d3ce 0px outset;
	FONT-SIZE: 9pt;
	BACKGROUND: #d6d3ce;
	BORDER-LEFT: #d6d3ce 0px outset;
	BORDER-BOTTOM: #d6d3ce 0px outset
}

body, td, th {
	font-size: 12px;
	color: #333333;
}

.b-03 {
	FONT-WEIGHT: bold;
	FONT-SIZE: 12px;
	COLOR: #040177;
	FONT-STYLE: normal;
	FONT-FAMILY: "细明体", "新细明体"
}

.b-04 {
	FONT-WEIGHT: bold;
	FONT-SIZE: 12px;
	COLOR: #ffffff;
	FONT-STYLE: normal;
	FONT-FAMILY: "细明体", "新细明体"
}

.style2 {
	FONT-SIZE: 12px;
	FONT-STYLE: normal;
	FONT-FAMILY: "细明体", "新细明体"
}

.style3 {
	COLOR: #000000
}
-->
</style>
</HEAD>

<?
// 已登陆

echo "<meta charset='UTF-8'>";
echo "<body id=\"main_body_1\" style='padding-bottom:100px;'>";
echo "<div id='time_left_1' style='display:none;'> " . $left_time . "</div> ";

include_once ("./include/get_zuhe.php");
include_once ('./liuhecai/' . $action . '.php');
$now_time = date("Y-m-d H:i:s", strtotime("+12 hours"));

if (strtotime($now_time) > strtotime($o_state) && strtotime($now_time) < strtotime($f_state)) {
    echo '<script> $("#FastPanel").css("display", "block");$("#main_body_1").css("display", "block"); $(".all_body").css("display", "block"); $(".all_body1").css("display", "");document.getElementById("kq_box_001").style.display="none";var f_o_state = 1;</script>';
  } else {
    // 封盘时切换显示
    echo '<script>$("#FastPanel").css("display", "none");$("#main_body_1").css("display", "block"); $(".all_body").css("display","none"); $(".all_body1").css("display","none"); document.getElementById("kq_box_001").style.display="block";var f_o_state = 2;</script>';
}

include_once ("./include/common_limit.php");

echo '<div id="style" style="display:none">';
echo '</div>';

// 快速金额设置
echo '<div class="aui_state_focus" id="aui_state_focus" style="position: absolute; left: 300px; top: 90px; width: 240px; z-index: 1987;display:none;"><div class="aui_outer"><table class="aui_border"><tbody><tr><td class="aui_nw"></td><td class="aui_n"></td><td class="aui_ne"></td></tr><tr><td class="aui_w"></td><td class="aui_c"><div class="aui_inner"><table class="aui_dialog"><tbody><tr><td colspan="2" class="aui_header"><div class="aui_titleBar"><div class="aui_title" style="cursor: move;">快速金额设定</div><a class="aui_close" onclick="document.getElementById(\'aui_state_focus\').style.display=\'none\'";>×</a></div></td></tr><tr><td class="aui_icon" style="display: none;"><div class="aui_iconBg" style="background: none;"></div></td><td class="aui_main" style="width: 220px; height: 250px;"><div class="aui_content aui_state_full" style="padding: 20px 25px;"><div class="aui_loading" style="display: none;"><span></span></div><iframe src="./include/set_money.php" name="OpenartDialog14271810369650" frameborder="0" allowtransparency="true" style="width: 100%; height: 280px; border: 0px none;"></iframe></div></td></tr><tr><td colspan="2" class="aui_footer"><div class="aui_buttons" style="display: none;"></div></td></tr></tbody></table></div></td><td class="aui_e"></td></tr><tr><td class="aui_sw"></td><td class="aui_s"></td><td class="aui_se" style="cursor: se-resize;"></td></tr></tbody></table></div></div>';
   echo "</body></html>";


?>