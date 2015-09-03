<?php 
include_once(dirname(__FILE__) ."/../include/cyj.php");
if (!empty($_SESSION['site_id'])) {
	
	include_once(dirname(__FILE__).'/../include/'.$_SESSION['site_id'].'_private_config.php');
	include_once(dirname(__FILE__).'/../include/redis_config.php');
	$redis_key = 'ulg'.CLUSTER_ID.'_'.$_SESSION['site_id'].'*';
	echo json_encode(count($redis->keys($redis_key)));
}else{
	exit(json_encode(0));
}
?>