<?php 
function checkuserlogin($uid){
	if(isset($uid)){
		 echo "<script>location.href='/index.php'</script>";
	}
}

/*用户退出*/
function logintu($uid){
	$uid=intval($uid);
	if($uid!=''){
		global $mysqlt;
		$mysqlt->query("update k_user set logout_time=now() where uid='$uid'");
		$mysqlt->query("update `k_user_login` set `is_login`=0 WHERE `uid`='$uid' and `is_login`>0");
		$time	=	time()-3600;
		
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			$mysqlt->query("update `k_user_login` set `is_login`=0 WHERE login_time<$time and `is_login`>0 and site_id='".SITEID."'");
			$q1		=	$mysqlt->affected_rows;
			if($q1 > 0){
				$mysqlt->commit(); //事务提交
			}else{
				$mysqlt->rollback(); //数据回滚
			}
		}catch(Exception $e){
			$mysqlt->rollback(); //数据回滚
		}
	}
	return true;
}	

/*删除不在线用户*/
function renovate($uid,$loginid){
	$uid=intval($uid);
	if($uid && $loginid) {
		global $mysqlt;
		$tims	=	time();
		$time	=	$tims-3600;
		
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try{
			$mysqlt->query("update `k_user_login` set `is_login`=0 WHERE login_time<$time and `is_login`>0 and site_id='".SITEID."'");
			$q1		=	$mysqlt->affected_rows;
			if($q1 > 0){
				$mysqlt->commit(); //事务提交
			}else{
				$mysqlt->rollback(); //数据回滚
			}
		}catch(Exception $e){
			$mysqlt->rollback(); //数据回滚
		}
		
		$query	=	$mysqlt->query("select id from `k_user_login` where `uid`='$uid' and `login_id`='$loginid' and `is_login`=0 limit 1");
		$rs		=	$query->fetch_array();
		if($rs['id'] > 0){
			$mysqlt->query("update `k_user_login` set `is_login`=0 where `uid`=$uid");
			session_destroy();
			echo "<script>parent.location.href='/'</script>";
			exit;
		}else{
			$mysqlt->query("update `k_user_login` set `login_time`='".date('Y-m-d H:i:s')."' where `uid`=$uid ");
		}
	}else{
		return true;
	}
	return true;
}	


function getip(){
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
	   $ip = getenv("HTTP_CLIENT_IP");
   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
	   $ip = getenv("HTTP_X_FORWARDED_FOR");
   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
	   $ip = getenv("REMOTE_ADDR");
   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
	   $ip = $_SERVER['REMOTE_ADDR'];
   else
	   $ip = "unknown";
   return $ip;
}


/*首页十秒刷新次数超过五次，设置为非法用户*/
function indexSession($ip,$url){
	
		if(!$_SESSION["indexSessionIf"] ){
			$_SESSION["indexSessionIf"] = 0;
			$_SESSION["indexSessionTime"] = time();
		}
	    $time = time() - $_SESSION["indexSessionTime"];	
		if($time>='10') {
			$_SESSION["indexSessionIf"] = '0';
			$_SESSION["indexSessionTime"] = time();
		}
		
		if($_SESSION["indexSessionIf"]<10) {
			$_SESSION["indexSessionIf"] = $_SESSION["indexSessionIf"]+1;
		}else{
			global $mysqlt;
			
			$query	=	$mysqlt->query("select why from `ban_ip` where `ip`='$ip' and `site_id`='".SITEID."'  limit 1");
			$rs		=	$query->fetch_array();
			if($rs['why']){
				$meg=	$rs['why'].'、'.$url;
				$mysqlt->query("update `ban_ip` set his=2,`why`='$meg',`ext_time`='".time()."',is_jz=1 where `ip`='$ip' and `site_id`='".SITEID."'");	
			}else{
				$mysqlt->query("insert into `ban_ip` (`ip`,`ban_time`,`why`,`site_id`) values ('$ip','".time()."','频繁刷新网站".$url."','".SITEID."')");	
			} 
			
			echo "<script>location.href='/zzip.html'</script>";
			exit;
		}
		return true;
}
/*被禁止IP不能进人网站*/
function banIP($ip){
	global $mysqlt;
	$query	=	$mysqlt->query("select his from `ban_ip` where `ip`='$ip' and is_jz=1 and site_id='".SITEID."' limit 1");
	$rs		=	$query->fetch_array();
	if($rs['his']>1){
		echo "<script>location.href='/zzip.html'</script>";
		exit;	
	}
	return true;
}

function sessionNum($uid,$type,$cal=''){
	$uid=intval($uid);
	if(!$_SESSION["sessionIf"]){
		$_SESSION["sessionIf"] = 1;
		$_SESSION["sessionTime"] = time();
		$_SESSION["3ssessionIf"] = 1;
		$_SESSION["3ssessionTime"] = time();
	}
	$time3 = time() - $_SESSION["3ssessionTime"];	
	if($time3>='60') {
		$_SESSION["3ssessionIf"]   = '0';
		$_SESSION["3ssessionTime"] = time();
	}
	if($_SESSION["3ssessionIf"]<='50'){
		$_SESSION["3ssessionIf"] = $_SESSION["3ssessionIf"]+1;	
	}else{
		//global $mysqlt;
		//$mysqlt->query("update `k_user` set `is_stop`=1 where uid='$uid'");	
		//@session_destroy();
	}
	$time  = time() - $_SESSION["sessionTime"];
	if($time>='30') {
		$_SESSION["sessionIf"]   = '0';
		$_SESSION["sessionTime"] = time();
	}
	if($_SESSION["sessionIf"]<=25){
		$_SESSION["sessionIf"] = $_SESSION["sessionIf"]+1;
	}else{
		$_SESSION["sessionTime"] = time();
		if($type==3) {
			echo "<div id=\"location\"  style='line-height:40px;text-align:center;color:#666; border-bottom:1px solid #999;'>对不起,您点击页面太快,请在60秒后进行操作</div><script>check();</script>";
		}elseif($type==4){
			$json['zq']				= 0;
			$json['zq_ds']			= 0;
			$json['zq_gq']			= 0;
			$json['zq_sbc']			= 0;
			$json['zq_sbbd']		= 0;
			$json['zq_bd']			= 0;
			$json['zq_rqs']			= 0;
			$json['zq_bqc']			= 0;
			$json['zq_jg']			= 0;
			$json['zqzc']			= 0;
			$json['zqzc_ds']		= 0;
			$json['zqzc_sbc']		= 0;
			$json['zqzc_sbbd']		= 0;
			$json['zqzc_bd']		= 0;
			$json['zqzc_rqs']		= 0;
			$json['zqzc_bqc']		= 0;
			$json['lm']				= 0;
			$json['lm_ds']			= 0;
			$json['lm_dj']			= 0;
			$json['lm_gq']			= 0;
			$json['lm_jg']			= 0;
			$json['lmzc']			= 0;
			$json['lmzc_ds']		= 0;
			$json['lmzc_dj']		= 0;
			$json['wq']				= 0;
			$json['wq_ds']			= 0;
			$json['wq_bd']			= 0;
			$json['wq_jg']			= 0;
			$json['pq']				= 0;
			$json['pq_ds']			= 0;
			$json['pq_bd']			= 0;
			$json['pq_jg']			= 0;
			$json['bq']				= 0;
			$json['bq_ds']			= 0;
			$json['bq_zdf']			= 0;
			$json['bq_jg']			= 0;
			$json['bqzc']			= 0;
			$json['bqzc_ds']		= 0;
			$json['bqzc_zdf']		= 0;
			$json['gj']				= 0;
			$json['gj_gj']			= 0;
			$json['gj_gjjg']		= 0;
			$json['gj_jg']			= 0;
			$json['jr']				= 0;
			$json['jr_jr']			= 0;
			$json['jr_jrjg']		= 0;
			$json['jr_jg']			= 0;
			$json['tz_money']		= "0 RMB";
			$json['user_money']		= "0 RMB";
			$json['user_num']		= 0;
			echo $cal."(".json_encode($json).");";
			//echo "(".json_encode($json).");";
		}else{
			$json["fy"]["p_page"] = "error2";
			echo $type."(".json_encode($json).");";
			//echo "(".json_encode($json).");";
		}
		exit;
	}
	return true;
}


function sessionBet($uid){
	$uid=intval($uid);
	if(!$_SESSION["bets"]){
		$_SESSION["bets"] = 0;
		$_SESSION["betTime"] = time();
	}
	$time3 = time() - $_SESSION["betTime"];	
	if($time3>='15') {
		$_SESSION["bets"]   = '0';
		$_SESSION["betTime"] = time();
	}
	if(@$_SESSION["betif"]!='') {
		if($time3>='30') {
			$_SESSION["bets"]   = '0';
			$_SESSION["betTime"] = time();
			$_SESSION["betif"]	= '';
		}
	}
	if($_SESSION["bets"]<20) {
		$_SESSION["bets"] = $_SESSION["bets"]+1;
	}else{
		$_SESSION["betTime"] = time();
		$_SESSION["betif"] = rand(100000,999999);	
		echo "<div class=\"pollbox\" id =\"idcs\"> 
			      <p style=\"text-align:center\"></p> 
				  <p style=\" text-align:center\"></p>
				  <p style=\"font-size:12px; text-align: center; padding:5px; line-height: 20px;\"><font style=\"color:#2f2f2f;text-align:center;\">您点击次数太快了..<br />为了保证网站数据安全..<br />请您稍等<span id='miao'>30</span>秒后再操作..</font></p></div>
				  
	<script language=\"javascript\">\r\n
		var i = 31;\r\n
		var timeouts;\r\n
		clearTimeout(timeouts);\r\n
		checkidcs();\r\n
		function checkidcs(){\r\n
			i = i-1;\r\n
			document.getElementById('miao').innerHTML	= '';\r\n
			document.getElementById('miao').innerHTML	=i;\r\n
			if(i == 1){\r\n
			clearTimeout(timeouts);\r\n
			$('#idcs').hide();\r\n
			$('#bet_moneydiv').hide();\r\n
			$('#maxmsg_div').hide();\r\n
				document.getElementById('bet_moneydiv').style.display='none';\r\n
				document.getElementById('idcs').style.display='none';\r\n
				document.getElementById('maxmsg_div').style.display='none';\r\n
			}else{\r\n
				timeouts=setTimeout(\"checkidcs()\",1000);\r\n
			}
		}\r\n
</script>\r\n";
		exit;	
	}
	return true;	
}


function investSZ($uid='') {
	if(!@$_SESSION["investValue"]){
		@$_SESSION["investValue"] = 0;
		@$_SESSION["investTime"]  = time();
	}
	$time = time() - $_SESSION["investTime"];	
	
	if($time>='5'){
		$_SESSION["investValue"] = '0';
		$_SESSION["investTime"]  = time();
	}
	if($_SESSION["investValue"]<=2){
		$_SESSION["investValue"] = $_SESSION["investValue"]+1;	
		return $_SESSION["investValue"];	
	}else{
		$_SESSION["investTime"] = time();
		return $_SESSION["investValue"];	
	}
}

function islogin_match($uid){
	if($uid){
		return true;
	}else{
		session_destroy();
		echo "<script>window.location.href='../select.php';</script>";
		exit;
	}
}
?>