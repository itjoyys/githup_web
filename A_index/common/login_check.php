<?php
@header('Content-type: text/html; charset=utf-8');
if(!isset($_SESSION["uid"])){
	echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
	echo "<script type=\"text/javascript\" language=\"javascript\">top.location.href='/';</script>";
	exit();
}
?>