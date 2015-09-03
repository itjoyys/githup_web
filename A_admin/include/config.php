<?php
$pageendtime = microtime();//开始时间
include_once dirname(__FILE__) ."/cyj.php";
//网站配置变量读取
// $C_Patch = dirname(__FILE__) . '/../'; //  $_SERVER['DOCUMENT_ROOT'];
// @include_once $C_Patch . "/filter/webfilter.php";
// @include_once $C_Patch . "/cache/website.php"; //站点信息
// @include_once $C_Patch . "/cache/conf.php"; //一个域名

//if($web_site['close'] == 1) {
//header("location:./close.php");
//  echo "<script>parent.location.href='/close.php';< /script>";
//    exit();
//}
function MoneyType($type) {
	switch ($type) {
		case "1":
			return "存款";
			break;
		case "101":
			return "管理员加款";
			break;
		case "4":
			return "取款";
			break;
		case "102":
			return "管理员加款";
			break;
		case "5":
			return "体育/彩票账户->AG(普通厅)";
			break;
		case "6":
			return "体育/彩票账户->AG(VIP厅)";
			break;
		case "7":
			return "AG(普通厅)->体育/彩票账户";
			break;
		case "8":
			return "AG(VIP厅)->体育/彩票账户";
			break;
		case "9":
			return "体育/彩票账户->股票T+0账户";
			break;
		case "10":
			return "股票T+0账户->体育/彩票账户";
			break;
		case "99":
			return "体育/彩票账户->MG游戏厅";
			break;
		case "11":
			return "MG游戏厅->体育/彩票账户";
			break;
		case "13":
			return "AG游戏厅->MG游戏厅";
			break;
		case "14":
			return "MG游戏厅->AG游戏厅";
			break;
		default:
			return "其他";
			break;
	}
}

//返回是否
function is_not($fstate){
   $fstate = !empty($fstate)?是:否;
   return $fstate;
}


function JSON($array) {
        arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
}


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

//$str="and|union|select|update|where|order|delete|'|insert|into|values|create|table|database";  //非法字符
//$arr=explode("|",$str);//数组非法字符，变单个

//if(is_array($_REQUEST))    //add
//{
//   foreach ($_REQUEST as $key=>$value){
//        for($i=0;$i<sizeof($arr);$i++){
//            if (substr_count(strtolower($_REQUEST[$key]),$arr[$i])>0){       //检验传递数据是否包含非法字符
//                echo "<script>alert(\"您的输入包含非法字符，请检查!\");< /script>";
//        echo "您的输入包含非法字符，请检查!";
//                exit; //退出不再执行后面的代码
//            }
//        }
//    }
//}

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

//单选框选中
function radio_check($val_a, $val_b) {
	if ($val_a == $val_b) {
		echo "checked=\"checked\"";
	}
}

//多选框选中
function check_box($val_a, $val_b) {
	if ($val_a == $val_b) {
		echo "checked=\"true\"";
	}
}

// 多选框选中
function check_box2($val_a, $val_b) {
	$if = 0;
	foreach (explode(',', $val_a) as $s) {
		if ($s == $val_b) {
			$if = 1;
		}
	}
	if ($if == 1) {
		echo "checked=\"true\"";
	}
}

function select_check($field, $val) {
	if ($field == $val) {
		echo "selected=\"selected\"";
	}
}

//币别
function rmb_type($type) {
	switch ($type) {
		case 'RMB':
			return '人民幣';
			break;
		case 'HKD':
			return '港幣';
			break;
		case 'USD':
			return '美金';
			break;
		case 'MYR':
			return '馬幣';
			break;
		case 'SGD':
			return '新幣';
			break;
		case 'THB':
			return '泰銖';
			break;
		case 'GBP':
			return '英磅';
			break;
		case 'JPY':
			return '日幣';
			break;
		case 'EUR':
			return '歐元';
			break;
		case 'IDR':
			return '印尼盾';
			break;

	}
}

//线下入款方式
function in_type($type) {
	switch ($type) {
		case '1':
			return '网银转帐';
			break;
		case '2':
			return 'ATM自动柜员机';
			break;
		case '3':
			return 'ATM现金入款';
			break;
		case '4':
			return '银行柜台';
			break;
		case '5':
			return '手机转帐';
			break;
		case '6':
			return '支付宝转账';
			break;
		case '7':
			return '财付通';
			break;
		case '8':
			return '微信支付';
			break;
	}
}

function payment_type($pay_type) {
	if($pay_type==1)
		{
			return "新生";
		}
		else if($pay_type==2)
		{
			return "易宝";
		}
		else if($pay_type==3)
		{
			return "环迅";
		}
		else if($pay_type==4)
		{
			return "聚付通";
		}
		else if($pay_type==5)
		{
			return "v付通";
		}
		else if($pay_type==6)
		{
			return "宝付";
		}
		else if($pay_type==7)
		{
			return "智付";
		}
		else if($pay_type==8)
		{
			return "汇潮";
		}
		else if($pay_type==9)
		{
			return "国付宝";
		}
}
//银行类别区分
function bank_type($type) {
	switch ($type) {
		case '1':
			return '中國銀行';
			break;
		case '2':
			return '中國工商銀行';
			break;
		case '3':
			return '中國建設銀行';
			break;
		case '4':
			return '中國招商銀行';
			break;
		case '5':
			return '中國民生銀行';
			break;
		case '7':
			return '中國交通銀行';
			break;
		case '8':
			return '中國邮政銀行';
			break;
		case '9':
			return '中國农业銀行';
			break;
		case '10':
			return '華夏銀行';
			break;
		case '11':
			return '浦發銀行';
			break;
		case '12':
			return '廣州銀行';
			break;
		case '13':
			return '北京銀行';
			break;
		case '14':
			return '平安銀行';
			break;
		case '15':
			return '杭州銀行';
			break;
		case '16':
			return '溫州銀行';
			break;
		case '17':
			return '中國光大銀行';
			break;
		case '18':
			return '中信銀行';
			break;
		case '19':
			return '浙商銀行';
			break;
		case '20':
			return '漢口銀行';
			break;
		case '21':
			return '上海銀行';
			break;
		case '22':
			return '廣發銀行';
			break;
		case '23':
			return '农村信用社';
			break;
		case '24':
			return '深圳发展银行';
			break;
		case '25':
			return '渤海银行';
			break;
		case '26':
			return '东莞银行';
			break;
		case '27':
			return '宁波银行';
			break;
		case '28':
			return '东亚银行';
			break;
		case '29':
			return '晋商银行';
			break;
		case '30':
			return '南京银行';
			break;
		case '31':
			return '广州农商银行';
			break;
		case '32':
			return '上海农商银行';
			break;
		case '33':
			return '珠海农村信用合作联社';
			break;
		case '34':
			return '顺德农商银行';
			break;
		case '35':
			return '尧都区农村信用联社';
			break;
		case '36':
			return '浙江稠州商业银行';
			break;
		case '37':
			return '北京农商银行';
			break;
		case '38':
			return '重庆银行';
			break;
		case '39':
			return '广西农村信用社';
			break;
		case '40':
			return '江苏银行';
			break;
		case '41':
			return '吉林银行';
			break;
		case '42':
			return '成都银行';
			break;
		case '50':
			return '兴业银行';
			break;
		case '100':
			return '支付宝';
			break;
		case '101':
			return '微信支付';
			break;
		case '102':
			return '财付通';
			break;
	}
}

//唯一性判断
function only_f($field, $value, $tab, $db_config) {
	$state = M($tab, $db_config)->where("$field='" . $value . "'")->find();
	return $state;
}

//表单验证
function check_name($str) {
	if (!empty($str)) {

		if (!preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u", $str)) {
			message("您输入的" . $str . "格式错误!");
		}
	}
}

function check_pass($str) {
	if (!empty($str)) {
		if (!preg_match("/^[A-Za-z0-9]+$/u", $str)) {
			message("您输入的密码格式错误!");
		}
	}
}

function pregPN($test) {

	$rule = "/^((13[0-9])|147|(15[0-35-9])|180|182|(18[5-9]))[0-9]{8}$/A";
	preg_match($rule, $test, $result);
	return $result;
}
//检验用户名是否以字母开头
function is_user_name($user) {
	if (preg_match("/^[a-za-z]{1}([a-za-z0-9]|[._]){4,14}$/", $user)) {
		return true;
	} else {
		return false;
	}
}

//二维数组转一维
function i_array_column($input, $columnKey, $indexKey = null) {
	if (!function_exists('array_column')) {
		$columnKeyIsNumber = (is_numeric($columnKey)) ? true : false;
		$indexKeyIsNull = (is_null($indexKey)) ? true : false;
		$indexKeyIsNumber = (is_numeric($indexKey)) ? true : false;
		$result = array();
		foreach ((array) $input as $key => $row) {
			if ($columnKeyIsNumber) {
				$tmp = array_slice($row, $columnKey, 1);
				$tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : null;
			} else {
				$tmp = isset($row[$columnKey]) ? $row[$columnKey] : null;
			}
			if (!$indexKeyIsNull) {
				if ($indexKeyIsNumber) {
					$key = array_slice($row, $indexKey, 1);
					$key = (is_array($key) && !empty($key)) ? current($key) : null;
					$key = is_null($key) ? 0 : $key;
				} else {
					$key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
				}
			}
			$result[$key] = $tmp;
		}
		return $result;
	} else {
		return array_column($input, $columnKey, $indexKey);
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
	} // 注射判断

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
	//$post = str_replace("%", "\%", $post); // 把' % '过滤掉
	//$post = nl2br($post); // 回车转换
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

//打印函数
function p($arr) {
	echo "<pre>";
	print_r($arr);
	echo "<pre>";
}
setRequest();
?>