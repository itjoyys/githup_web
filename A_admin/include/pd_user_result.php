<?php
/*判断用户是否登录*/
/*if($_SESSION["username"] == ""){
	//echo "<div style='line-height:40px;text-align:center;color:#F00; border-bottom:1px solid #999'>末登录,无法查看赛事信息.</div>";
	echo "<script>parent.location.href='/index.php'</script>";
	exit;
}*/

include_once("../common/logintu.php");

$uid = $_SESSION["uid"];

sessionNum($uid,3);

?>