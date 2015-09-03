<?php
//额度转换页面
exit();
ini_set("display_errors", "On");
error_reporting(E_ALL);

$g_type = trim($_GET["g_type"]);

$g_type_arr = array("og", "ag", "mg", "ct", "bbin","lebo");

if (!in_array($g_type, $g_type_arr)) {
	exit("未知的游戏");
}

$tc_type = trim($_GET["tc_type"]);
$tc_actives = array("IN", "OUT");
if (!in_array($tc_type, $tc_actives)) {
	exit("未知的操作");
}
$loginname = "www----12--13433";
$credit = 20;

include_once dirname(__FILE__) . "/Games.class.php";

$games = new Games();

$data = $games->TransferCredit($loginname, $g_type, $tc_type, $credit);

echo $data;