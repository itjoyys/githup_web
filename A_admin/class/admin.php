<?php
/**
* 登陆控制 
*/
class admin {

	static function login($login_name, $login_pwd) {
		//管理员登陆
		global $db_config;
		$Sys = M('sys_admin',$db_config);
		$map_s['login_name_1'] = $login_name;
		$map_s['site_id'] = SITEID;
		$logState = $Sys->where($map_s)->find();
		//判断账号是否暂停锁定
		if ($logState['is_delete'] == 2) {
			return "0,4";
		}
		 //获取登陆基本信息
		include_once(dirname(__FILE__)."/../ip.php");
		$loginIp = admin::get_client_ip();
		#$address = admin::GetIpLookup($loginIp);
		$address = ipsetarea($loginIp); //获取客户端IP所在的国
		if ($logState['login_pwd'] === md5(md5($login_pwd))) {
			//$login_key = md5(uniqid());
			//是否已经登陆 判断 ssid 是否存在@mebar
			if (!empty($logState["ssid"])) {
				//取消上一个session
				session_id($logState["ssid"]);
				// Create new session and destroying the old one
				session_regenerate_id(true);
				// Grab current session ID and close both sessions to allow other scripts to use them;
				$newSession = session_id();
				session_write_close();
				// Set session ID to the new one, and start it back up again
				session_id($newSession);
				session_start();
			}
           

			//更新登陆状态
			$dataB = array();
			$dataB['is_login'] = 1;
			$dataB['login_ip'] = $loginIp;
			$dataB['updatetime'] = time();
			#$dataB['login_address'] = $address['country'].$address['province'].$address['city'];
			$dataB['login_address'] = $address;
			$dataB['ssid'] = session_id();
			$log_2 = $Sys->where("uid = '".$logState['uid']."'")
			         ->update($dataB);
			if (empty($log_2)) {
				return '0,7';
				exit();
			}

			//登陆日志
		    $dataG = array();
		    $dataG['uid'] = $logState['uid'];
		    $dataG['site_id'] = SITEID;
		    $dataG['username'] = $logState['login_name_1'];
		    $dataG['ip'] = $loginIp;
		    $dataG['state'] = 1;
		    #$dataG['ip_address'] = $address['country'].$address['province'].$address['city'];
		    $dataG['ip_address'] = $address;
		    $dataG['login_time'] = date('Y-m-d H:i:s');
		    $dataG['www'] = $_SERVER['HTTP_HOST'];
		    $log_3 = M('sys_admin_login',$db_config)->add($dataG);
            if (empty($log_3)) {
				return '0,7';
				exit();
			}
            //写入session
		    $_SESSION["adminid"] = $logState["uid"];
			$_SESSION["login_name"] = $logState['login_name'];//原始账号
			$_SESSION["login_name_1"] = $logState['login_name_1'];//登陆账号
			$_SESSION["quanxian"] = $logState["quanxian"];//权限
			$_SESSION["ssid"] = session_id();
			return '1,' . $logState["uid"];
		}else{

			//登陆日志
		    $dataG = array();
		    $dataG['uid'] = $logState['uid'];
		    $dataG['site_id'] = SITEID;
		    $dataG['state'] = 0;
		    $dataG['username'] = $logState['login_name_1'];
		    $dataG['ip'] = $loginIp;
		    #$dataG['ip_address'] = $address['country'].$address['province'].$address['city'];
		    $dataG['ip_address'] = $address;
		    $dataG['login_time'] = date('Y-m-d H:i:s');
		    $dataG['www'] = $_SERVER['HTTP_HOST'];
		    $log_3 = M('sys_admin_login',$db_config)->add($dataG);

			$eNum = $Sys->where("uid = '".$logState['uid']."'")
			        ->getField("error_num");//获取当前错误次数
			if ($eNum >= 3) {
				//锁定账号
				$dataD = array();
				$dataD['is_delete'] = 2;
				$Sys->where("uid = '".$logState['uid']."'") ->update($dataD);
				return '0,1,s';
			}else{
			    $dataE = array();
				$dataE['error_num'] = array('+',1);
				$Sys->where("uid = '".$logState['uid']."'") ->update($dataE);
			   return '0,1,'.(4-$eNum);
			}
			
		}
	}

	//获取ip位置
	static function GetIpLookup($ip = ''){  
	 if(empty($ip)){  
	        $ip = admin::get_client_ip();  
	    }  
	    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);//新浪api接口
	    if(empty($res)){ return false; }  
	    $jsonMatches = array();  
	    preg_match('#\{.+?\}#', $res, $jsonMatches);  
	    if(!isset($jsonMatches[0])){ return false; }  
	    $json = json_decode($jsonMatches[0], true);  
	    if(isset($json['ret']) && $json['ret'] == 1){  
	        $json['ip'] = $ip;  
	        unset($json['ret']);  
	    }else{  
	        return false;  
	    }  
	    return $json;  
	} 

	//获取ip
	static function get_client_ip(){  
	    $realip = '';  
	    $unknown = 'unknown';  
	    if (isset($_SERVER)){  
	        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){  
	            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
	            foreach($arr as $ip){  
	                $ip = trim($ip);  
	                if ($ip != 'unknown'){  
	                    $realip = $ip;  
	                    break;  
	                }  
	            }  
	        }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){  
	            $realip = $_SERVER['HTTP_CLIENT_IP'];  
	        }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){  
	            $realip = $_SERVER['REMOTE_ADDR'];  
	        }else{  
	            $realip = $unknown;  
	        }  
	    }else{  
	        if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){  
	            $realip = getenv("HTTP_X_FORWARDED_FOR");  
	        }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){  
	            $realip = getenv("HTTP_CLIENT_IP");  
	        }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){  
	            $realip = getenv("REMOTE_ADDR");  
	        }else{  
	            $realip = $unknown;  
	        }  
	    }  
	    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;  
	    return $realip;  
	} 

	/**
	 * 强制吧用户踢下线
	 * @param  integer $uid [description]
	 * @return [type]       [description]
	 */
	static function make_offline($uid) {
		global $db_config;
		$ssid = M('sys_admin',$db_config)
		       ->where("uid = '".$uid."'")->getField("ssid");
		if (!empty($ssid)) {
			$sessionpath = session_save_path();
			if (strpos($sessionpath, ";") !== FALSE) {
				$sessionpath = substr($sessionpath, strpos($sessionpath, ";") + 1);
			}
			//直接删除文件
			@unlink($sessionpath . '/sess_' . $ssid);
		}
	}

	static function insert_log($uid, $login_name, $log_info) {
		//超级管理员操作日志增加
		global $mysqlt;
        $ip=admin::get_client_ip();
		$mysqlt->query("insert into sys_log(uid,login_name,site_id,log_info,log_ip) values ('" . $uid . "','" . $login_name . "','" . SITEID . "','" . $log_info . "','" . $ip . "')");
	}

	//查询是否有该用户 $user参数可以是agent_user也可以是id
	static function getisuser($user) {
		global $mysqlt;
		$query = $mysqlt->query("select uid from sys_admin where uid='$user' or login_name='$user' or login_name_1='$user' limit 1");
		$t1 = $query->fetch_array();
		return $t1[0];
	}
	//修改用户状态
	static function user_is_delete($uid, $is_delete) {
		$uid = intval($uid);
		global $mysqlt;
		$sql = "update sys_admin set is_delete='" . $is_delete . "' where uid='$uid'";
		if ($mysqlt->query($sql)) {
			return true;
		} else {
			return false;
		}

	}

	//获取会员所有入款次数 总额
	static function getUser_Bankin($uid){
		global $db_config;
		$userCash = array();
		$catm_data = M('k_user_catm',$db_config)->field("count(id) as catmNum,sum(catm_money) as catm_money,max(catm_money) as maxMc")->where("uid = '".$uid."' and site_id = '".SITEID."' and type = 1 and catm_type = 1")->find();
		//公司入款 线上入款
		$cash_data=M('k_user_bank_in_record',$db_config)->field("sum(deposit_num) as cash_money,count(id) as cashNum,max(deposit_num) as maxMg")->where("uid = '".$uid."' and site_id = '".SITEID."' and make_sure = '1'")->find();
		$userCash['num'] = $catm_data['catmNum'] + $cash_data['cashNum'];//存款总次数
		$userCash['money'] = $catm_data['catm_money'] + $cash_data['cash_money'];//存款总额
		if ($cash_data['maxMg'] > $catm_data['maxMc']) {
			$userCash['max_money']=$cash_data['maxMg']+0;//最大存款额数
		}else{
			$userCash['max_money']=$catm_data['maxMc']+0;//最大存款额数
		}
		return $userCash;
	}

		//获取会员所有取款次数 总额
	static function getUser_Bankout($uid){
		global $db_config;
		$userCash = array();
		//会员出款
		$cash_out=M('k_user_bank_out_record',$db_config)->field("sum(outward_num) as out_money,count(id) as cashOut")->where("uid = '".$uid."' and site_id = '".SITEID."' and out_status = '1'")->find();
		$userCash['num'] = $cash_out['cashOut']+0;//会员出款次数
		$userCash['money'] = $cash_out['out_money']+0;//会员出款金额
		return $userCash;
	}

	//获取用户余额
	static function getUserMoney($uid){
		global $db_config;
		$umoney;
		$umoney = M('k_user',$db_config)->where("uid = '".$uid."'")
		        ->getField("money");
		return $umoney;
	}

}

?>