<?php

set_include_path(get_include_path()
	. PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '\class'
	. PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '\cache'
	. PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '\include'
	. PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '\\'
);

class user {

	static function login($username,$passwrod,$login_url,$login_ip,$ClientSity='') {
		//登陆，进行验证，和信息更新,产生UID
		global $db_config;
		$mapL = array();
		$mapL['site_id'] = SITEID;
		$mapL['username'] = $username;
		$mapL['password'] = $passwrod;
		if (defined('INDEX_ID')) {
		   $mapL['index_id'] = INDEX_ID; 
		}
		$loginS = M('k_user',$db_config)
		        ->field("uid,username,agent_id,level_id,is_delete,shiwan")
		        ->where($mapL)
		        ->find();

		//判断账号密码是否存在
		if (!empty($loginS)) {
			//判断账号状态
			user::userState($loginS['is_delete']);
			//判断是否已经登录
		    $log_1 = user::isLoginNow($loginS['uid'],$loginS['username'],$login_url,$ClientSity);
             
            if (empty($log_1['state'])) {
                return "3";
            }
			//更新用户登录
			$dataU = array();
			$dataU['login_time'] = date('Y-m-d H:i:s');
			$dataU['login_ip'] = $login_ip;
			$dataU['lognum'] = array('+','1');

			$log_2 = M('k_user',$db_config)->where("uid ='".$loginS['uid']."'")
			       ->update($dataU);
           
			//更新会员历史登录
			$dataO = array();
			$dataO['uid'] = $loginS['uid'];
			$dataO['username'] = $loginS['username'];
			$dataO['ip'] = $login_ip;
			$dataO['level_id'] = $loginS['level_id'];
			$dataO['state']     = 1;
			$dataO['ip_address'] = $ClientSity;
			$dataO['login_time'] = date('Y-m-d H:i:s');
			$dataO['site_id'] = SITEID;
			$dataO['www'] = $login_url;
                        //print_r($dataO);exit;
			$log_3 = M('history_login',$db_config)
			       ->add($dataO);
			if (empty($log_3)) {
                return "5";
            }       
            if ($log_3 && $log_1['state'] && $log_2) {
            	$_SESSION["uid"] = $loginS["uid"];
				$_SESSION["agent_id"] = $loginS["agent_id"]; //所属代理
				$_SESSION["username"] = $loginS['username'];
				$_SESSION["level_id"] = $loginS["level_id"];
				$_SESSION['ssid'] = session_id();
				if ($loginS["shiwan"] == 1) {
					$_SESSION["shiwan"] = 2;
				}
				return $loginS['uid'];
            }else{
            	return "56";
            }

		}else{
           return "45";
		}
	}

	//判断账号状态
	static function userState($state){
		switch ($state) {
			case '1':
				echo "<script>alert('对不起，您的账号异常已被停止，请与在线客服联系！');</script>";
				echo "<script>window.location.href='/'</script>";
				exit;
				break;
			case '2':
				echo "<script>alert('对不起，您的账号异常已被暂停使用，请与在线客服联系！');</script>";
				echo "<script>window.location.href='/'</script>";
				exit;
				break;
		}
    }

    //判断会员是否已经登录
    static function isLoginNow($iuid,$username,$login_url,$addre){
    	global $db_config;
    		$nowLogin = M('k_user_login',$db_config)
			          ->field("uid,ssid")
			          ->where("uid = '".$iuid."'")
			          ->find();
			if ($nowLogin) {
				//登录过
				//已经在线
				if (isset($nowLogin['is_login']) && $nowLogin['is_login']) {
					//取消上一个session
					session_id($nowLogin['ssid']);
					session_regenerate_id(true);
					$newSession = session_id();
					session_write_close();
					session_id($newSession);
					session_start();
				}

				//更新会员登录
				$dataUL = array();
				$dataUL['ssid'] = session_id();
				$dataUL['login_time'] = date('Y-m-d H:i:s');
				$dataUL['is_login'] = 1;
				$dataUL['www'] = $login_url;
				$dataUL['ip'] = $addre;
				$log_ul = M('k_user_login',$db_config)
				        ->where("uid = '".$iuid."'")
				        ->update($dataUL);
			}else{
					//添加会员登录
				$dataUL = array();
				$dataUL['ssid'] = session_id();
				$dataUL['login_time'] = date('Y-m-d H:i:s');
				$dataUL['is_login'] = 1;
				$dataUL['uid'] = $iuid;
				$dataUL['site_id'] = SITEID;
				$dataUL['www'] = $login_url;
				$dataUL['ip'] = $addre;
				$log_ul = M('k_user_login',$db_config)
				        ->add($dataUL);
			}
			if ($log_ul) {
				$userR['ssid'] = session_id();
				$userR['state'] = 1;
			}else{
				$userR['state'] = 0;
			}
			return $userR;
    }


	static function islogin() {
		//验证是否登录
		return isset($_SESSION["uid"], $_SESSION["username"]);
	}

	static function getinfo($uid) {
		$uid = intval($uid);
		if ($uid < 1) {
			return 0;
		} else {
			global $db_config;
			$t1 = M('k_user',$db_config)->where("uid = '$uid'")->find();
			return $t1;
		}
	}
	//查询是否有该用户 $user参数可以是username也可以是uid
	static function getisuser($user) {
		global $mysqlt;
		$query = $mysqlt->query("select uid from k_user where uid='$user' or username='$user' limit 1");
		$t1 = $query->fetch_array();
		return $t1[0];
	}

	static function user_is_delete($uid, $is_delete) {
		$uid = intval($uid);
		global $mysqlt;
		$sql = "update k_user set is_delete='" . $is_delete . "' where uid='$uid'";
		if ($mysqlt->query($sql)) {
			return true;
		} else {
			return false;
		}

	}

	static function getusername($uid) {
		$uid = intval($uid);
		if ($uid < 1) {
			return false;
		} else {
			global $mysqlt;
			$query = $mysqlt->query("select username from k_user where uid='$uid' limit 1");
			$t = $query->fetch_array();

			return $t["username"];
		}
	}

	static function update_pwd($uid, $oldpwd, $newpwd, $type = 'password') {
		$uid = intval($uid);
		global $mysqlt;
		if ($oldpwd == $newpwd) {
			return array(
				'status' => false,
				'msg' => '原登录密码不能与修改后的登录密码一致！',
			);
		}
		$sql2 = "select * from k_user where uid='$uid' and " . $type . "='" . md5(md5($oldpwd)) . "'";

		$sql = "update k_user set " . $type . "='" . md5(md5($newpwd)) . "' where uid='$uid' and " . $type . "='" . md5(md5($oldpwd)) . "'";
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try {
			$mysqlt->query($sql2);
			$q2 = $mysqlt->affected_rows;
			if ($q2 == 1) {
				$mysqlt->query($sql);
				$q1 = $mysqlt->affected_rows;
				if ($q1 == 1) {
					$mysqlt->commit(); //事务提交
					return array(
						'status' => true,
						'msg' => '登录密码修改成功！',
					);
				} else {
					$mysqlt->rollback(); //数据回滚
					return array(
						'status' => false,
						'msg' => '由于网络原因，修改失败，请联系管理员！',
					);
				}
			} else {
				$mysqlt->rollback(); //数据回滚
				return array(
					'status' => false,
					'msg' => '原登录密码错误！',
				);
			}

		} catch (Exception $e) {
			$mysqlt->rollback(); //数据回滚
			return array(
				'status' => false,
				'msg' => '由于网络原因，修改失败，请联系管理员！',
			);
		}
	}

	static function update_paycard($uid, $pay_card, $pay_num, $pay_address, $pay_name, $username) {
		$uid = intval($uid);
		global $mysqlt;
		$sql = "update k_user set pay_card='$pay_card',pay_num='$pay_num',pay_address='$pay_address' where uid='$uid'";
		$sql1 = "insert into history_bank (uid,username,pay_card,pay_num,pay_address,pay_name) values (" . $uid . ",'$username','$pay_card','$pay_num','$pay_address','$pay_name')";
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try {
			$mysqlt->query($sql);
			$q1 = $mysqlt->affected_rows;
			$mysqlt->query($sql1);
			$q2 = $mysqlt->affected_rows;
			if ($q1 == 1 && $q2 == 1) {
				$mysqlt->commit(); //事务提交
				return true;
			} else {
				$mysqlt->rollback(); //数据回滚
				return false;
			}
		} catch (Exception $e) {
			$mysqlt->rollback(); //数据回滚
			return false;
		}
	}

	static function msg_add($uid, $from, $title, $info) {
		$uid = intval($uid);
		global $mysqlt;
		$sql = "insert into k_user_msg(uid,msg_from,msg_title,msg_info,site_id) values ('$uid','$from','$title','$info','" . SITEID . "')";
		$mysqlt->autocommit(FALSE);
		$mysqlt->query("BEGIN"); //事务开始
		try {
			$mysqlt->query($sql);
			$q1 = $mysqlt->affected_rows;
			if ($q1 == 1) {
				$mysqlt->commit(); //事务提交
				return true;
			} else {
				$mysqlt->rollback(); //数据回滚
				return false;
			}
		} catch (Exception $e) {
			$mysqlt->rollback(); //数据回滚
			return false;
		}
	}

	static function is_daili($uid) {
		$uid = intval($uid);
		global $mysqlt;
		$query = $mysqlt->query("select is_daili from k_user where uid='$uid' and is_daili=1 and site_id='" . SITEID . "' limit 1");
		//echo "select is_daili from k_user where uid=$uid and is_daili=1 limit 1";exit;
		$t = $query->fetch_array();
		if ($t['is_daili'] == 1) {
			setcookie("is_daili", $uid, time() + 8 * 3600);
			return true;
		} else {
			setcookie("is_daili", "", time() - 3600, "/");
			return false;
		}
	}


	/*
	**会员中心版权读取
	**site_id     站点ID
	**INDEX_ID    前台ID
	*/
	static function copy_right($site_id,$INDEX_ID){
		global $db_config;
		//版权查询
		$site_id=$site_id;
		$index_id = $INDEX_ID;
		if($site_id=="hun"){
			if($index_id=="a"){
				$copy_right['copy_right'] ="太阳城申博";
			}elseif($index_id=="b"){
				$copy_right['copy_right'] ="澳门银河赌场";
			}elseif($index_id=="c"){
				$copy_right['copy_right'] ="威尼斯人娱乐城";
			}elseif($index_id=="d"){
				$copy_right['copy_right'] ="金沙娱乐场";
			}elseif($index_id=="e"){
				$copy_right['copy_right'] ="新葡京娱乐场";
			}elseif($index_id=="f"){
				$copy_right['copy_right'] ="澳门星际赌场";
			}elseif($index_id=="g"){
				$copy_right['copy_right'] ="太阳城申博";
			}elseif($index_id=="h"){
				$copy_right['copy_right'] ="太阳城申博";
			}elseif($index_id=="i"){
				$copy_right['copy_right'] ="太阳城申博";
			}elseif($index_id=="j"){
				$copy_right['copy_right'] ="太阳城申博";
			}
		}else if($site_id=="jun"){
			if($index_id=="a"){
				$copy_right['copy_right'] ="威尼斯人娱乐场";
			}elseif($index_id=="b"){
				$copy_right['copy_right'] ="金沙娱乐场";
			}
		}else{
			$copy_right=M('web_config',$db_config)->field('copy_right')->where("site_id='$site_id'")->find();
		}
		return $copy_right;
	}
}

?>
