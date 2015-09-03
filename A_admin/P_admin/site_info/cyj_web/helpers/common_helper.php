<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

//常用函数
function p($arr){
	print_r('<pre>');
	print_r($arr);
	print_r('<pre>');
}

//网站资讯系统 文案类别
function case_type($type){
	switch ($type) {
		case '1':
			return '线上入款';
			break;
		case '2':
			return '公司入款';
			break;
		case '3':
			return '关于我们';
			break;
		case '4':
			return '联系我们';
			break;
		case '5':
			return '代理联盟';
			break;
		case '6':
			return '存款帮助';
			break;
		case '7':
			return '取款帮助';
			break;
		case '8':
			return '常见问题';
			break;
		case '9':
			return '会员注册';
			break;
		case '10':
			return '代理注册';
			break;
		case '11':
			return '网站LOGO';
			break;
		case '12':
			return '会员中心LOGO';
			break;
		case '13':
			return '首页轮播图';
			break;
		case '14':
			return '优惠活动';
			break;
		case '15':
			return '首页游戏图';
			break;
		case '16':
			return '左边浮动';
			break;
		case '17':
			return '右边浮动';
			break;
		case '18':
		    return '开户协议';
		    break;
	}
}

//编辑器图片上传处理
function Uedit(){
	header("Content-Type: text/html; charset=utf-8");
	$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
	$action = $_GET['action'];
	switch ($action) {
	    case 'config':
	        $result =  json_encode($CONFIG);
	        break;

	    /* 上传图片 */
	    case 'uploadimage':
	    /* 上传涂鸦 */
	    case 'uploadscrawl':
	    /* 上传视频 */
	    case 'uploadvideo':
	    /* 上传文件 */
	    case 'uploadfile':
	        $result = include("action_upload.php");
	        break;
	    /* 列出图片 */
	    case 'listimage':
	        $result = include("action_list.php");
	        break;
	    /* 列出文件 */
	    case 'listfile':
	        $result = include("action_list.php");
	        break;

	    /* 抓取远程文件 */
	    case 'catchimage':
	        $result = include("action_crawler.php");
	        break;
	    default:
	        $result = json_encode(array(
	            'state'=> '请求地址出错'
	        ));
	        break;
	}
	/* 输出结果 */
	if (isset($_GET["callback"])) {
	    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
	        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
	    } else {
	        echo json_encode(array(
	            'state'=> 'callback参数不合法'
	        ));
	    }
	} else {
	    echo $result;
	}
}
