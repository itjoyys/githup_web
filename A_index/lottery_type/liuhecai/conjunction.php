<?php
//error_reporting(0);

error_reporting(E_ALL ^ E_NOTICE);



@set_magic_quotes_runtime(0);

@header("content-Type: text/html; charset=utf-8");



define('PHPYOU_VER', 'v1.1');

define('PHPYOU',__FILE__ ? getdirname(__FILE__).'/' : './');



function pr($a) {

	echo '<pre style=\'text-align:left\'>';

	print_r($a);

	echo '</pre>';

}



//北京时间

if(function_exists('date_default_timezone_set')) {

	// @date_default_timezone_set('Etc/GMT-8');

}

$timestamp = time()+12*3600;


// 允许程序在 register_globals = off 的环境下工作

if (!ini_get("register_globals")) {

    extract($_GET,EXTR_SKIP);

    extract($_POST,EXTR_SKIP);

}







//rewrite支持检查

$rewrite_enable = $_FCACHE['settings']['ifrewrite'];

if($rewrite_enable){

    if (function_exists('apache_get_modules')) {

	    $apache_mod = apache_get_modules();

	    if (!in_array('mod_rewrite',$apache_mod)) {

		    $rewrite_enable = 0;

	    }

    }

}





//登陆验证，后台使用

$admin_info = '';

if ($_SESSION['jxadmin666'] && $_SESSION['flag']) {

	if($_SESSION['jxadmin666']){

	    $admin_info = 1;

	}

}





//登陆验证，代理使用

$admin_info1 = '';

if ($_SESSION['kauser'] && $_SESSION['lx']) {

	if($_SESSION['kauser']){

	    $admin_info1 = 1;

	}

}





//登陆验证，前台使用

$admin_info2 = '';

if ($_SESSION['username']) {

	if($_SESSION['username']){

	    $admin_info2 = 1;

	}

}

//获取系统目录

function getdirname($path){

	if(strpos($path,'\\')!==false){

		return substr($path,0,strrpos($path,'\\'));

	}elseif(strpos($path,'/')!==false){

		return substr($path,0,strrpos($path,'/'));

	}else{

		return '/';

	}

}



//字符转意

// function Add_S(&$array){

// 	foreach($array as $key=>$value){

// 		if(!is_array($value)){

// 			$array[$key]=addslashes($value);

// 		}else{

// 			Add_S($array[$key]);

// 		}

// 	}

// }





//提交确认

function SubmitCheck($var = ""){

	//if (empty($_POST[$var]))

	if (empty($_POST)){
		return false;

	}



	if($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) ||

			preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))){

		return true;

	}

	else{

		return false;

	}

}



?>

