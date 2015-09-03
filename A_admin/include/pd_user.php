<?php
session_start();
/*判断用户是否登录*/
include_once("../cache/website.php");
if($web_site['close']) {
	header("location:../close.php");
    exit();
}

include_once("../common/logintu.php");
$uid = $_SESSION["uid"];
sessionNum($uid,1);

?>