<?php
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
include(dirname(__FILE__).'/filter.php');
include(dirname(__FILE__).'/login_check.php');


//币别
// function rmb_type($type) {
// 	switch ($type) {
// 		case 'RMB':
// 			return '人民幣';
// 			break;
// 		case 'HKD':
// 			return '港幣';
// 			break;
// 		case 'USD':
// 			return '美金';
// 			break;
// 		case 'MYR':
// 			return '馬幣';
// 			break;
// 		case 'SGD':
// 			return '新幣';
// 			break;
// 		case 'THB':
// 			return '泰銖';
// 			break;
// 		case 'GBP':
// 			return '英磅';
// 			break;
// 		case 'JPY':
// 			return '日幣';
// 			break;
// 		case 'EUR':
// 			return '歐元';
// 			break;
// 		case 'IDR':
// 			return '印尼盾';
// 			break;

// 	}
// }


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

//打印函数
function p($arr) {
	echo "<pre>";
	print_r($arr);
	echo "<pre>";
}
?>