<?php
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");
header('Content-type: text/json; charset=utf-8');
include('../../include/filter.php');
include_once("../include/pd_user_json.php");
include_once("../../include/private_config.php");
include_once("../../include/redis_config.php");
include_once("../include/function_cj.php");
include("../include/function.php");
$d=RedisZQGQ();
print_r($d);