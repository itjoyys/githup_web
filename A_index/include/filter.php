<?php

function customError($errno, $errstr, $errfile, $errline) {
	echo "<b>Error number:</b> [$errno],error on line $errline in $errfile<br />";
	die();
}
set_error_handler("customError", E_ERROR);
$getfilter = "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$postfilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
$cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
function StopAttack($StrFiltKey, $StrFiltValue, $ArrFiltReq) {

	if (is_array($StrFiltValue)) {
		$StrFiltValue = implode($StrFiltValue);
	}
	if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltValue) == 1) {
		//slog("<br><br>操作IP: ".$_SERVER["REMOTE_ADDR"]."<br>操作时间: ".strftime("%Y-%m-%d %H:%M:%S")."<br>操作页面:".$_SERVER["PHP_SELF"]."<br>提交方式: ".$_SERVER["REQUEST_METHOD"]."<br>提交参数: ".$StrFiltKey."<br>提交数据: ".$StrFiltValue);
		print "notice:Illegal operation!";
		exit();
	}
}
//$ArrPGC=array_merge($_GET,$_POST,$_COOKIE);
foreach ($_GET as $key => $value) {
	StopAttack($key, $value, $getfilter);
}
foreach ($_POST as $key => $value) {
	StopAttack($key, $value, $postfilter);
}
foreach ($_COOKIE as $key => $value) {
	StopAttack($key, $value, $cookiefilter);
}

if (!get_magic_quotes_gpc()) {
	!empty($_POST) && Add_S($_POST);
	!empty($_GET) && Add_S($_GET);
	!empty($_COOKIE) && Add_S($_COOKIE);
	// !empty($_SESSION) && Add_S($_SESSION);
}
!empty($_FILES) && Add_S($_FILES);

function Add_S(&$array) {
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			if (!is_array($value)) {
				$value = @addslashes($value);
				$array[$key] = @htmlspecialchars($value, ENT_QUOTES);
			} else {
				Add_S($array[$key]);
			}
		}
	}
}

// 防入驻和过滤的参数方法
function I($c = 'get', $str) {
	if ($str) {
		if ($c == 'get') {
			$str = $_GET[$str];
			return verify_id($str);
		} elseif ($c == 'post') {
			$strs = $_POST[$str];
			if (is_array($strs)) {
				foreach ($strs as $j => $k) {
					$array[] = post_check($k);
				}
				return $array;
			} else {
				return post_check($strs);
			}
		}
	}
}

function verify_id($id) {
	if (inject_check($id)) {
		exit('提交的参数非法！');
	} // 注入判断

	return $id;
}

function inject_check($sql_str) //判断get请求非法字符
{
	return preg_match("/\bselect\b|\binsert\b|\band\b|\bor\b|\bupdate\b|\bdelete\b|\bunion\b|\binto\b|\bload_file\b|\bdrop\b/i", $sql_str);
}

function post_check($post) //过滤post请求的方法
{
	if (!get_magic_quotes_gpc()) // 判断magic_quotes_gpc是否为打开
	{
		$post = addslashes($post); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
	}
	// $post = str_replace("_", "\_", $post); // 把 '_'过滤掉
	$post = str_replace("%", "\%", $post); // 把' % '过滤掉
	$post = nl2br($post); // 回车转换
	$post = htmlspecialchars($post); // html标记转换
	return $post;
}

function setRequest() //过滤所有的get和post请求
{
	foreach ($_GET as $v => $k) {
		$_GET[$v] = I('get', $v);
	}
	foreach ($_POST as $v => $k) {
		$_POST[$v] = I('post', $v);
	}
}
//验证token
function tokenCk_form(){
   if ($_SESSION['PKtokenState'] == '1') {
   	  //如果开启
	  if ($_POST['pk_token']=='' || $_SESSION['PKtoken']=='' || $_POST['pk_token'] != $_SESSION['PKtoken']){
		 exit('请勿非法提交');
	   }
   }
}

setRequest();
?>