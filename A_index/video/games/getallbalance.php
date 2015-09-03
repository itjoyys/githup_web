<?php
ini_set("display_errors", "Off");
error_reporting(0);
include_once dirname(__FILE__) . '/../../include/site_config.php';
include_once(dirname(__FILE__)."/../../wh/site_state.php");
include_once dirname(__FILE__) . "/../../include/config.php";
include_once $C_Patch . "/common/function.php";

ini_set("display_errors", "Off");
error_reporting(0);

$reg = M("k_user", $db_config);
$uid = @$_SESSION['uid'];
$data = $reg->field("username,shiwan")->where("site_id = '" . SITEID . "' and uid = '" . $uid . "'")->find();
if(!empty($data) && $data['shiwan'] == 1){
	$info['error'] = '试玩账号不能刷新视讯额度，请注册真实账号';
	echo json_encode($info);exit;
}
if (empty($data) OR empty($data["username"])) {
	$info['error'] = '请登录再进行游戏!';
	echo json_encode($info);exit;
}
$loginname = $data["username"];

include_once dirname(__FILE__) . "/Games.class.php";

$games = new Games();

$data = $games->GetAllBalance($loginname);

$list = array('ag','og','mg','ct','bbin','lebo');
$action = trim($_GET["action"]);
if ($action == "save") {
	$result = json_decode($data);
	$data = json_decode($data,true);
	foreach($list as $val){
		$str = $val.'info';
		//$info[$val] = GetSiteStatus($SiteStatus,2,$val,1);
		if(GetSiteStatus($SiteStatus,2,$val,1)){
			$data['data'][$str] = 9999;
		}else{
			$data['data'][$str] = 1111;
		}
	}
	if ($result->data->Code == 10017) {
		$data_u = array();
		if (!empty($result->data->ogstatus)) {
			$data_u['og_money'] = floatval($result->data->ogbalance);
		}
		if (!empty($result->data->agstatus)) {
			$data_u['ag_money'] = floatval($result->data->agbalance);
		}
		if (!empty($result->data->mgstatus)) {
			$data_u['mg_money'] = floatval($result->data->mgbalance);
		}
		if (!empty($result->data->ctstatus)) {
			$data_u['ct_money'] = floatval($result->data->ctbalance);
		}
		if (!empty($result->data->bbinstatus)) {
			$data_u['bbin_money'] = floatval($result->data->bbinbalance);
		}
		if (!empty($result->data->lebostatus)) {
			$data_u['lebo_money'] = floatval($result->data->lebobalance);
		}
		
		M("k_user", $db_config)->where("site_id = '" . SITEID . "' and uid = '" . $uid . "'")->update($data_u);
	}
	echo json_encode($data);
} else {
	echo json_encode($data);
}
