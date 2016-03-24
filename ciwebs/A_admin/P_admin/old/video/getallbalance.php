<?php
ini_set("display_errors", "Off");
error_reporting(0);
include_once("../../include/config.php");

include_once("../common/login_check.php");

include_once dirname(__FILE__) . "/../../lib/video/Games.class.php";
//include_once dirname(__FILE__) . "/../../lib/video/GameType.php";

ini_set("display_errors", "Off");
error_reporting(0);
$reg = M("k_user", $db_config);
$username = $_REQUEST['user_name'];
$data = $reg->field("username,shiwan")->where("site_id = '" . SITEID . "' and username = '" . $username . "'")->find();
$loginname = $data["username"];
$games = new Games();

$data = $games->GetAllBalance($loginname);

$list = array('ag','og','mg','ct','bbin','lebo');
	$result = json_decode($data);
	$data = json_decode($data,true);
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
		
		M("k_user", $db_config)->where("site_id = '" . SITEID . "' and username = '" . $username . "'")->update($data_u);
	}
	//echo json_encode($data);