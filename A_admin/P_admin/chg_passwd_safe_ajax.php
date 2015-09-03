<?php 
include_once("../include/config.php");
include_once '../include/'.$_SESSION['site_id'].'_private_config.php';
include_once("../lib/class/model.class.php");

if($_GET['active']=="u"){//用户名检查
	$username = $_GET['username'];
		//utf-8 一汉字=3字节
	if(strlen($username)<6 || strlen($username)>15){
		echo "3";exit();
	}

	if(!is_user_name($username))
   {
       echo "3";exit();
   }
   //判断类别 p表示系统账号  g表示代理账号
	if ($_GET['type'] == 'p') {
	   $data =  M("sys_admin",$db_config)->where("login_name = '".$username."' or login_name_1 = '".$username."'")->find();
	}elseif ($_GET['type'] == 'g') {
	   $data =  M("k_user_agent",$db_config)->where("agent_user = '".$username."' or agent_login_user = '".$username."'")->find();
	}
	
	if($data){
		echo "1";
	}else{
		echo "2";
	}
}elseif( $_GET['active']=="p"){//密码检查
	$password_new = trim($_GET['password_new']);
	if(!is_user_name($password_new) || strlen($password_new)<6 || strlen($password_new)>15)
   {
       echo "2";
   }else{
   	  echo "1";
   }
}elseif($_GET['active']=="p2"){//密码检查2
	$password_new2 = trim($_GET['password_new2']);
	$password_new = trim($_GET['password_new']);

			if($password_new!=$password_new2){				
				echo "2";
			}else{
				echo "1";//成功
			}
}
