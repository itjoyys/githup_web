<?php
ini_set("display_errors", "Off");
error_reporting(0);
include_once dirname(__FILE__) . "/../../include/config.php";
include_once $C_Patch . "/common/function.php";
ini_set("display_errors", "Off");
error_reporting(0);
$g_type = trim($_GET["g_type"]);

$g_type_arr = array("og", "ag", "mg", "ct", "bbin","lebo");

if (!in_array($g_type, $g_type_arr)) {
	message('未知的游戏!');exit;
}
$reg = M("k_user", $db_config);
$uid = @$_SESSION['uid'];
$data = $reg->field("username")->where("site_id = '" . SITEID . "' and uid = '" . $uid . "'")->find();
if (empty($data) OR empty($data["username"])) {
	message('请登录再进行游戏!');exit;
}
$loginname = $data["username"];

include_once dirname(__FILE__) . "/Games.class.php";

$games = new Games();

$data = $games->GetBalance($loginname, $g_type);
$result = json_decode($data);
//print_r($result);
if ($result->data->Code == 10006) {
	//用户不存在
	echo "0.00";
} else if ($result->data->Code == 10017) {
	//更新用户的余额
	$data_u = array();
	$data_u[$g_type . '_money'] = floatval($result->data->balance);
	$reg->where("site_id = '" . SITEID . "' and uid = '" . $uid . "'")->update($data_u);
	echo $result->data->balance;
} else {
	echo "0.00";
	//message('网络错误，请联系管理员!');exit;
}