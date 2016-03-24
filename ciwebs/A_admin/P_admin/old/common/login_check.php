<?php
if(!isset($_SESSION["adminid"])){
    session_destroy();
    echo "<script>window.parent.location.href='../login.html'</script>";
}else{

	include(dirname(__FILE__) .'/../../../include/database.php');

	unset($mysqlt);
	$mysqlt = new MySQLi($_DBC['private']['host'], $_DBC['private']['user'], $_DBC['private']['pwd'], $_DBC['private']['dbname']);
	$mysqlt->query("set names " . $_DBC['private']['char_set']);

	$db_config['host'] = $_DBC['private']['host'];
	$db_config['user'] = $_DBC['private']['user'];
	$db_config['pass'] = $_DBC['private']['pwd'];
	$db_config['dbname'] = $_DBC['private']['dbname'];

    include_once(dirname(__FILE__).'/../../../lib/class/model.class.php');

    if (!empty($_SESSION['site_id'])) {
	    define('SITEID',$_SESSION['site_id']);
		define('DESKey',$_SESSION['site_des_key']);
		define('MD5Key',$_SESSION['site_md5_key']);
	}
			//更新在线时间
	$dataO = array();
	$dataO['updatetime'] = time();
	$outState = M('sys_admin',$db_config)
	          ->where("uid = '".$rs['uid']."'")
	          ->update($dataO);
	//引入系统函数
	include_once(dirname(__FILE__)."/../../../class/admin.php");

	$redis = new Redis();
	$redis->connect($RedisConefi['Host'],$RedisConefi['Port']);
	$redis_akey = 'alg_'.$_SESSION['site_id'].$_SESSION['adminid'];
	$redis->setex($redis_akey,'1200','1');
}

function message($value,$url=""){ //默认返回上一页
	header("Content-type: text/html; charset=utf-8");

	$js  = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
	$js .= "alert(\"".$value."\");\r\n";
	if($url) $js .= "window.location.href=\"$url\";\r\n";
	else $js .= "window.history.go(-1);\r\n";
	$js .= "</script>\r\n";

	echo $js;
	exit;
}

function check_quanxian($qx){
	$c_quanxian=$_SESSION["quanxian"];
	if(!strpos($c_quanxian,$qx)){
		unset($_SESSION["adminid"]);
		unset($_SESSION["quanxian"]);
		echo "<script>window.parent.location.href='/'</script>";
		exit();
	}
}

/**
*写文件函数
**/
function write_file($filename,$data,$method="rb+",$iflock=1){
	@touch($filename);
	$handle=@fopen($filename,$method);
	if($iflock){
		@flock($handle,LOCK_EX);
	}
	@fputs($handle,$data);
	if($method=="rb+") @ftruncate($handle,strlen($data));
	@fclose($handle);
	@chmod($filename,0777);
	if( is_writable($filename) ){
		return true;
	}else{
		return false;
	}
}

function get_bj_time($time){  //取中国时间
	//return date("Y-m-d H:i:s",strtotime($time)+43200);
	return date("Y-m-d H:i:s",strtotime($time));
}

function double_format($num){
	return $num>0 ? sprintf("%.2f",$num) : $num<0 ? sprintf("%.2f",$num) : 0;
}

function get_DSFS($piont,$bet_money){ //判断是否返水
	/*/全场让球，大小，单双；上半让球，大小
	$arr	=	array('match_ho','match_ao','match_dxdpl','match_dxxpl','match_bho','match_bao','match_bdpl','match_bxpl','Match_dsdpl','Match_dsspl');
	if(in_array($piont,$arr)){
		return $bet_money;
	}else{
		return 0;
	}*/

	return $bet_money;
}

function get_CGFS($bet_money){ //判断是否返水
	return $bet_money;
	//return 0;
}

function getColor_u($num){
	if($num == 0) return '#009900';
	else if($num == 1) return '#FF9900';
	else if($num == 2) return '#FF99FF';
	else if($num == 3) return '#0099FF';
}
?>