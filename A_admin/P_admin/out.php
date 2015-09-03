<?php
/**退出*/
include_once "../include/cyj.php";
include_once "../lib/class/model.class.php";
include_once(dirname(__FILE__)."/../include/redis_config.php");
include_once(dirname(__FILE__).'/../include/'.$_SESSION['site_id'].'_private_config.php');
$redis_akey = 'alg'.CLUSTER_ID.'_'.$_SESSION['site_id'].$_SESSION['adminid'];
$redis->del($redis_akey);
session_destroy();
echo "<script>window.parent.location.href='./index.html'</script>";
?>