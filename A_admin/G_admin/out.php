<?php
/**退出*/
include_once "../include/cyj.php";
include_once "../lib/class/model.class.php";
include_once(dirname(__FILE__).'/../include/'.$_SESSION['site_id'].'_private_config.php');

$dataO = array();
$dataO['is_login'] = 0;
$dataO['ssid'] = '';
$dataO['updatetime'] = time();

$outState = M('sys_admin',$db_config)
          ->where("uid = '".$_SESSION['adminid']."'")
          ->update($dataO);

session_destroy();
echo "<script>window.parent.location.href='./index.html'</script>";
?>