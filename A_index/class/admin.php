<?php

class admin {

	static function login($login_name, $login_pwd) {
		//管理员登陆
		global $mysqlt;
		$sql = "select uid,login_name_1,quanxian,is_login,ssid,ip,about,address from sys_admin where (login_name='" . $login_name . "' or login_name_1='" . $login_name . "') and login_pwd='" . md5(md5($login_pwd)) . "' limit 1";

		$query = $mysqlt->query($sql);
		$t = $query->fetch_array();

		if ($t['uid'] > 0) {
			if (empty($t['login_name_1'])) {
				message("请设置登录账号！", "log_name_set.php?uid=" . $t['uid']);
			}
			$login_key = md5(uniqid());
			include_once "../ip.php";
			//$ClientSity = iconv("GB2312", "UTF-8", convertip($_SERVER["REMOTE_ADDR"], '../'));   //取出客户端IP所在城市
			$ClientSity = ipsetarea($_SERVER["REMOTE_ADDR"]); //获取客户端IP所在的国家
			$bool = true;
			if ($t['address']) {
				$bool = false;
				$arr = explode(',', $t['address']);
				foreach ($arr as $k => $v) {
					if (strpos('=' . $ClientSity, $v)) {
						//会员登陆的地址是在限制的地区内
						$bool = true;
						break;
					}
				}
			}
			//是否已经登陆 判断 ssid 是否存在@mebar
			if (!empty($t["ssid"])) {
				//取消上一个session
				session_id($t["ssid"]);
				// Create new session and destroying the old one
				session_regenerate_id(true);
				// Grab current session ID and close both sessions to allow other scripts to use them;
				$newSession = session_id();
				session_write_close();
				// Set session ID to the new one, and start it back up again
				session_id($newSession);
				session_start();
			}
			if ($t["ip"] && $bool) {
				//限制登陆ip的情况
				if ($t["ip"] === $_SERVER['REMOTE_ADDR']) {
					$_SESSION["adminid"] = $t["uid"];
					$_SESSION["about"] = $t["about"];
					$_SESSION["login_name"] = $login_name;
					$_SESSION["quanxian"] = $t["quanxian"];
					$_SESSION["ssid"] = session_id();

					include_once "../cache/conf.php";
					$sql = "update sys_admin set is_login=1,login_ip='" . $_SERVER["REMOTE_ADDR"] . "',ssid='" . $_SESSION["ssid"] . "',login_address='$ClientSity',www='$conf_www',updatetime='" . time() . "' where uid=" . $t["uid"];
					$mysqlt->query($sql);
					$sql = "insert into `admin_login` (`uid`,`username`,`ip`,`ip_address`,`login_time`,`www`) VALUES (" . $t["uid"] . ",'$login_name','" . $_SERVER['REMOTE_ADDR'] . "','" . $ClientSity . "',now(),'$conf_www')";
					$mysqlt->query($sql);

					return '1,' . $t["uid"];
				} else {
					return '0,3,' . $_SERVER['REMOTE_ADDR'];
				}
			} elseif ($bool) {
				$_SESSION["adminid"] = $t["uid"];
				$_SESSION["about"] = $t["about"];
				$_SESSION["login_name"] = $login_name;
				$_SESSION["quanxian"] = $t["quanxian"];
				$_SESSION["ssid"] = session_id();
				$_SESSION['login_key'] = $login_key;

				include_once "../cache/conf.php";
				$sql = "update sys_admin set is_login=1,login_ip='" . $_SERVER["REMOTE_ADDR"] . "',ssid='" . $_SESSION["ssid"] . "',login_address='$ClientSity',www='$conf_www',updatetime='" . time() . "' where uid=" . $t["uid"];
				$mysqlt->query($sql);
				$sql = "insert into `admin_login` (`uid`,`username`,`ip`,`ip_address`,`login_time`,`www`) VALUES (" . $t["uid"] . ",'$login_name','" . $_SERVER['REMOTE_ADDR'] . "','" . $ClientSity . "',now(),'$conf_www')";
				$mysqlt->query($sql);

				return '1,' . $t["uid"];
			} else {
				return '0,2,' . $ClientSity;
			}
		} else {
			return '0,1';
		}
	}

	static function agent_login($login_name, $login_pwd) {
		//管理员登陆
		global $mysqlt;
		$sql = "select id,agent_user from k_user_agent where (agent_user='" . $login_name . "' or agent_login_user='" . $login_name . "') and agent_pwd='" . md5(md5($login_pwd)) . "' limit 1";

		$query = $mysqlt->query($sql);
		$t = $query->fetch_array();
		if ($t['id'] > 0) {
			if (empty($t['agent_user'])) {
				message("请设置登录账号！", "log_name_set.php?uid=" . $t['id']);
			}

			$_SESSION["adminid"] = $t["id"];
			$_SESSION["about"] = $t["about"];
			$_SESSION["login_name"] = $login_name;
			$_SESSION["quanxian"] = $t["quanxian"];
			$_SESSION["ssid"] = session_id();
			return '1,' . $t["id"];
		}
	}

	/**
	 * 强制吧用户踢下线
	 * @param  integer $uid [description]
	 * @return [type]       [description]
	 */
	static function make_offline($uid) {
		global $mysqlt;
		$sql = "select ssid from sys_admin where uid= '" . $uid . "' limit 1";

		$query = $mysqlt->query($sql);
		$t = $query->fetch_array();
		if (count($t) > 0 && !empty($t['ssid'])) {
			@session_id($t['ssid']);
			@session_write_close();
			@session_destroy();
		}
		//TODO 是否写记录
	}

	static function insert_log($uid, $login_name, $log_info) {
		//超级管理员操作日志增加
		global $mysqlt;
		$mysqlt->query("insert into sys_log(uid,login_name,site_id,log_info,log_ip) values ('" . $uid . "','$login_name','" . SITEID . "','$log_info','" . $_SERVER['REMOTE_ADDR'] . "')");
	}
}

// $quanxian = array(
// 	array('en'=>'zdgl','cn'=>'注单管理'),
// 	array('en'=>'bfgl','cn'=>'比分管理'),
// 	array('en'=>'ssgl','cn'=>'赛事管理'),
// 	array('en'=>'hygl','cn'=>'会员管理'),
// 	array('en'=>'sgjzd','cn'=>'手工结注单'),
// 	array('en'=>'cwgl','cn'=>'财务管理'),
// 	array('en'=>'jkkk','cn'=>'加款扣款'),
// 	array('en'=>'xxgl','cn'=>'消息管理'),
// 	array('en'=>'dlgl','cn'=>'代理管理'),
// 	array('en'=>'glygl','cn'=>'管理员管理'),
// 	array('en'=>'bbgl','cn'=>'报表管理'),
// 	array('en'=>'jyqk','cn'=>'交易情况'),
// 	array('en'=>'xtgl','cn'=>'系统管理'),
// 	array('en'=>'rzgl','cn'=>'日志管理'),
// 	array('en'=>'sjgl','cn'=>'数据管理'),
// 	array('en'=>'lottery','cn'=>'彩票管理'),
// 	array('en'=>'lhcgl','cn'=>'六合彩管理')
// );
?>