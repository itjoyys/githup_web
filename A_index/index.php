<?php
include_once dirname(__FILE__) . '/include/site_config.php';
include_once(dirname(__FILE__)."/wh/site_state.php");
GetSiteStatus($SiteStatus,1,'webhome',1);
include_once "include/private_config.php";
include_once "lib/class/model.class.php";
// if ($web_site['close'] == 1) {
// 	echo "<script>parent.location.href='./close.php';</script>";
// 	exit();
// ces   }

function message($value,$url=""){ //默认返回上一页
	header("Content-type: text/html; charset=utf-8");
	$js  = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
	$js .= "alert(\"".$value."\");\r\n";
	if($url) $js .= "window.location.href=\"$url\";\r\n";
	else $js .= "window.history.go(-1);\r\n";
	$js .= "</script>\r\n";
	echo $js;
	exit;
}

$_GET = array_change_key_case($_GET, CASE_LOWER);//将$_GET的key  转化为小写
if (!empty($_GET['intr'])) {
   $_SESSION['intr'] = $_GET['intr'];
   //直接跳转注册
  // echo "<script>parent.location.href='./index.php?a=zhuce';</script>";
}

//获取token
function getPKtoken() {
	$hash = md5(uniqid(rand(), true));
	$n = rand(1, 26);
	$token = substr($hash, $n, 11);
	return $token;
}

include_once './website/common.php';

Router::init();

?>