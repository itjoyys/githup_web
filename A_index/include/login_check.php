<?
/**
 *会员检测
 */
if(!isset($_SESSION["uid"]))
{
	echo "<script>alert(\"请先登录再进行操作\");top.location.href='/';</script>";
	exit();
}else{
	include_once(dirname(__FILE__).'/private_config.php');
    include_once(dirname(__FILE__).'/../lib/class/model.class.php');
   // include(dirname(__FILE__).'/redis_config.php');
    $ulog = M('k_user_login',$db_config)
          ->where("uid = '".$_SESSION['uid']."' and is_login = '1'")
          ->find();
    //判断会员是否已经禁用
    $isUse = M('k_user',$db_config)
           ->where("uid='".$_SESSION['uid']."'")
           ->getField("is_delete");
    if ($isUse == '1') {
    	echo "<script>alert('对不起，您的账号异常已被停止，请与在线客服联系！');</script>";
		echo "<script>top.location.href='/';</script>";
		exit;
    }elseif($isUse == '2'){
    	echo "<script>alert('对不起，您的账号异常已被暂停使用，请与在线客服联系！');</script>";
		echo "<script>top.location.href='/';</script>";
		exit;
    }

    //屏蔽试玩账号检测
    if ($_SESSION['shiwan'] == '1') {
    	$ulog['uid'] = $_SESSION['uid'];
    }

	if($ulog['uid'] > 0){
		if($ulog['ssid'] != $_SESSION["ssid"])
		{
			//别处登陆
			echo "<script  charset=\"utf-8\" language=\"javascript\" type=\"text/javascript\">alert(\"帐号在别处登陆\");</script>";
		    session_destroy();
		    echo "<script>top.location.href='/';</script>";
			exit();
		}else{
			//更新在线时间
			redis_update_user();
		}

	}else{
		session_destroy();
		echo "<script>top.location.href='/';</script>";
	}
}

 //更新会员在线
function redis_update_user(){
	include(dirname(__FILE__).'/redis_config.php');
	$redis_key = 'ulg'.CLUSTER_ID.'_'.SITEID.$_SESSION['uid'];
	$redis->setex($redis_key,'1200','1'); 
}

 //会员离线清除
function redis_del_user(){
    include(dirname(__FILE__).'/redis_config.php');
	$redis_key = 'ulg'.CLUSTER_ID.'_'.SITEID.$_SESSION['uid'];
	$redis->del($redis_key); 
 }
