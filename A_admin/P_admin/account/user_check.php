<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
if($_POST['name']=='agent_user'){
	$info = M('k_user_agent',$db_config)->where('agent_user="'.$_POST['param'].'"')->find();
	$info1 = M('sys_admin',$db_config)->where('login_name_1 ="'.$_POST['param'].'"')->find();
	if(!empty($info) || !empty($info1)){
		echo '此帳號已有人使用';exit;
	}else{
		echo 'y'; exit;
	}
}

?>