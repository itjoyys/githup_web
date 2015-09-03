<?php
ini_set("display_errors", "On");
error_reporting(E_ALL);

//$g_type = trim($_GET["g_type"]);

// $g_type_arr = array("og", "ag", "mg", "ct", "bbin", "lebo");

// if (!in_array($g_type, $g_type_arr)) {
// 	exit("未知的游戏");
// }
$g_type = 'ct';
define("SITEID", 't');
//define("MD5Key", '88u2i4i2jjj234uicc');
//define("DESKey", 'GF8662HF');

include_once dirname(__FILE__) . "/Games.class.php";

//'455','479','481','483','497','545','579','1211')
//$loginname = "busixin";
$agentid = "112|1127|481|483|497|545|579|1211";
$s_time = "2015-06-01 00:00:00";
$e_time = "2015-08-20 23:59:59";

$games = new Games();
/*
$data = $games->GetUserAvailableAmountByUser($g_type, $loginname, $s_time, $e_time);
echo $data;
$data = json_decode($data);
$videoUser = $data->data->data;
print_r($videoUser);
 */
// echo "<br/>";
$data = $games->GetAgentAvailableAmountByAgentid($g_type, $agentid, $s_time, $e_time);
echo $data;
$data = json_decode($data);
$videoUser = $data->data->data;
print_r($videoUser);

// echo "<br/>";
// $data = $games->GetUserAvailableAmountBySiteid($g_type, $s_time, $e_time);
// // $data = json_decode($data);
// // $videoUser = $data->data->data;
// // print_r($videoUser);
// echo $data;
// echo "<br/>";

//以userid为分组
// $data = $games->GetAllUserAvailableAmountByAgentid($agentid, $s_time, $e_time);
// $data = json_decode($data);
//  $videoUser = $data->data->data;
// print_r($videoUser);
echo "<br/>";