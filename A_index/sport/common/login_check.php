<?php
//date_default_timezone_set('PRC');
@header('Content-type: text/html; charset=utf-8');
if(!isset($_SESSION["username"])){
	echo "<script type=\"text/javascript\" language=\"javascript\">window.top.location.href='/index.php';</script>";
	exit();
}
?>