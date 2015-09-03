<?php

include_once "../../include/config.php";
include_once "../common/login_check.php";
include_once "../../class/Level.class.php";

include_once $C_Patch . "/lib/video/Games.class.php";

$mgpwd = intval($_GET['mgpwd']); //期数
$lebopwd = intval($_GET['lebopwd']); //0 全部,1 股东,2 总代理,3  代理

if (empty($mgpwd) && empty($lebopwd)) {
	exit("11");
}

$games = new Games();

$data = "";
if (!empty($mgpwd)) {
	$data = $games->ChangeAgentPwd($mgpwd, "");
}
if (!empty($lebopwd)) {
	$data = $games->ChangeAgentPwd("", $lebopwd);
}
try {
	$data1 = json_decode($data);
} catch (Exception $e) {
	exit("ee");
}
if (empty($data1) || !$data1->result) {
	exit("ee");
}
echo "yes";