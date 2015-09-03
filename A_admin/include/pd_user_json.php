<?php
session_start();
/*判断用户是否登录*/
$json = array();
$callback="";
$callback=@$_GET['callback'];
/*if($_SESSION["username"] == ""){
	$json["fy"]["p_page"] = "error1";
	echo $callback."(".json_encode($json).");";
	exit;
}*/
/*
include_once("../common/logintu.php");

$uid = $_SESSION["uid"];

sessionNum($uid,$_GET['callback']);
*/
?>