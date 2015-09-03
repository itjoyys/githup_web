<?php
include_once "../../include/config.php";
include_once("../../common/login_check.php");
include_once  ("../../video/games/Games.class.php");
ini_set("display_errors", "off");
error_reporting(E_ALL);

$g_type = trim($_GET["g_type"]);

$g_type_arr = array("og", "ag", "mg", "ct", "bbin","lebo");

if (!in_array($g_type, $g_type_arr)) {
	exit("未知的游戏");
}



$loginname = $_GET['UserName'];
$orderId   = $_GET['OrderId'];
$videotype = $_GET['videotype'];
$gametype  = $_GET['gametype'];
$s_time    = $_GET['S_Time']." 00:00:00";
$e_time    = $_GET['E_Time']." 23:59:59";
$agentid      = $_GET['agentid'];
$page      = $_GET['Page'];
$Page_Num  = $_GET['Page_Num'];
$games = new Games();

$data = $games->GetBetRecord($g_type, $loginname, $orderId, $videotype,$gametype, $s_time, $e_time,$agentid, $page, $Page_Num);

echo $data;