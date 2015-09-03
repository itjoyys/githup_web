<?php
exit();
ini_set("display_errors", "Off");
error_reporting(E_ALL);

$g_type = trim($_GET["g_type"]);

$g_type_arr = array("og", "ag", "mg", "ct", "bbin","lebo");

if (!in_array($g_type, $g_type_arr)) {
	exit("未知的游戏");
}

$loginname = "wwwwww11";
$cur = "";
include_once dirname(__FILE__) . "/Games.class.php";

$games = new Games();

$data = $games->CreateAccount($loginname,$agent_id $g_type, $cur);

echo $data;