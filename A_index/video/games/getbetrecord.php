<?php
ini_set("display_errors", "On");
error_reporting(E_ALL);

$g_type = trim($_GET["g_type"]);

$g_type_arr = array("og", "ag", "mg", "ct", "bbin", "lebo");

if (!in_array($g_type, $g_type_arr)) {
	exit("未知的游戏");
}

include_once dirname(__FILE__) . "/Games.class.php";

$loginname = "wwwwww10";
$orderId = "110852034";
$videotype = "";
$gametype = "";
$s_time = "2015-05-01 20:53:13";
$e_time = "2015-05-25 20:53:13";

$games = new Games();

$data = $games->GetBetRecord($g_type, $loginname, $orderId, $videotype, $gametype, $s_time, $e_time, 1, 15);

echo $data;