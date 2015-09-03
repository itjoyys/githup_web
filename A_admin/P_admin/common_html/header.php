<?php 
//define($path, dirname(__FILE__));//定义当前文件夹路径常量

if(!empty($_SESSION['site_id'])){
	$site_id = $_SESSION['site_id'];
	$file_name = $site_id."_header.php";
	include_once $file_name;
}

?>