<?php
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(1);
include_once "../include/config.php";
include_once "../include/login_config.php";
include_once "../lib/class/model.class.php";

$username = @$_POST["LoginName"];
$password = @$_POST["LoginPassword"];
$yzm = @$_POST["CheckCode"];

if($yzm	!=	$_SESSION["randcode"]){
	message('验证码错误');
}

$admin_url = $_SERVER["HTTP_HOST"];  
$loginA = M('sys_admin',$db_config)->field("site_id")->where("login_name_1 = '".$username."' and is_delete in (0,2) and locate('".$admin_url."',admin_url)>0")->find();

if (!empty($loginA['site_id'])) {
	$_SESSION['site_id'] = $loginA['site_id'];
} else {
	message('用户账号不存在！');
}

function message($value, $url = "") {
	//默认返回上一页
	header("Content-type: text/html; charset=utf-8");

	$js = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
	$js .= "alert(\"" . $value . "\");\r\n";
	if ($url) {
		$js .= "window.location.href=\"$url\";\r\n";
	} else {
		$js .= "window.history.go(-1);\r\n";
	}

	$js .= "</script>\r\n";

	echo $js;
	exit;
}

//$config_url = '../include/'.$_SESSION['site_id'].'_'.'private_config.php';

include_once '../include/' . $_SESSION['site_id'] . '_' . 'private_config.php';

include_once "../class/admin.php";

$arr = array();
$temp = admin::login($username, $password);
$arr = explode(',', $temp);
if ($arr[0] > 0) {
	   //更新在线
	include_once(dirname(__FILE__)."/../include/redis_config.php");
	$redis_akey = 'alg'.CLUSTER_ID.'_'.SITEID.$arr[1];
	$redis->setex($redis_akey,'1200','1'); 
	header('Content-Type: text/html; charset=utf-8');
	echo "<script>location.href='index.php';</script>";
	exit();
} else {
	if ($arr[1] == 1) {
	    if($arr[2] == 's'){
		    message('此账号已被锁定，请联系管理员！');
	    }
		message('用户密码错误,该账号还有（'.$arr[2].'）机会！');
	}elseif ($arr[1] == 4){
		message('此账号已被系统管理员暂停使用，请联系管理员！');
	}else {
		message('登陆失败');
	}
}
?>