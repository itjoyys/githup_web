<?php
//额度转换
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../../include/site_config.php';
include_once(dirname(__FILE__)."/../../wh/site_state.php");
include_once "../../include/config.php";
include_once "../../common/login_check.php";
include_once "../../class/user.php";
if(SITEID == 't'){
	echo json_encode(array("status" => 18, "info" => "测试站点，不允许额度转换"));exit;
}
$uid = @$_SESSION['uid'];
$loginid = @$_SESSION['user_login_id'];
$username = @$_SESSION['username'];
//renovate($uid, $loginid); //验证是否登陆
$userinfo = user::getinfo($uid);
$cash_record = M('k_user_cash_record', $db_config)->field('cash_date')->where("uid='$userinfo[uid]' and cash_type = 1 and cash_do_type = 1")->order('cash_date desc')->find();
if ($cash_record && !empty($cash_record['cash_date'])) {
	$time = time() - strtotime($cash_record['cash_date']);
	if ($time < 60) {
		echo json_encode(array("status" => 17, "info" => "请在" . (60 - $time) . "秒后操作"));exit;
	}
}

$g_type_arr = array("og", "ag", "mg", "ct", "lebo", "bbin", "sport");
$trtype1 = trim($_POST['trtype1']);
$trtype2 = trim($_POST['trtype2']);

$list = array('ag','og','mg','ct','bbin','lebo');
foreach($list as $val){
	if(GetSiteStatus($SiteStatus,2,$val,1)){
		if($trtype1 == $val || $trtype2 == $val){
			$str  = $val."游戏正在进行例行维护！\n请您选择其他游戏！祝您游戏开心！";
			echo json_encode(array("status" => 18, "info" => $str ));exit;
		}
	}
}

$tc_type = $g_type = "";
if (!in_array($trtype1, $g_type_arr) && !in_array($trtype2, $g_type_arr)) {
	echo json_encode(array("status" => 1, "info" => "未知的游戏!"));exit;
}
if (empty($username)) {
	echo json_encode(array("status" => 2, "info" => "请登录再进行游戏!"));exit;
}
if ($trtype1 == "sport" && $trtype2 != "sport") {
	$tc_type = "IN";
	$g_type = $trtype2;
}
if ($trtype2 == "sport" && $trtype1 != "sport") {
	$tc_type = "OUT";
	$g_type = $trtype1;
}
if (empty($tc_type)) {
	echo json_encode(array("status" => 3, "info" => "额度转换,只能在系统余额和视讯余额之间转换,视讯余额之间不能直接转换!"));exit;
}
$credit = floatval($_POST['p3_Amt']);
if ($credit <= 1) {
	echo json_encode(array("status" => 4, "info" => "转换的额度，必须大于1!"));exit;
}

include_once "../../video/games/Games.class.php";
$games = new Games();
if ($tc_type == "IN") {
	//更新会员金额
	$data_u = array();
	$data_u['money'] = array('-', $credit);
	$Buser = M("k_user", $db_config);
	$Buser->begin();
	try {
		$log_1 = $Buser->where("uid = '" . $uid . "' AND money >= " . $credit)->update($data_u);
		$data = $games->GetBalance($username, $g_type);
		$result = json_decode($data);

		if ($result->data->Code == 10017) {
			$sxbalance = floatval($result->data->balance);
		} else if ($result->data->Code == 10006) {
			$data = $games->CreateAccount($username, $userinfo["agent_id"], $g_type);
			if (!empty($data)) {
				$result = json_decode($data);
				if ($result->data->Code != 10011) {
					$Buser->rollback(); //数据回滚
					echo json_encode(array("status" => 5, "info" => "额度转换失败,错误代码R004"));exit;
				}
			} else {
				//网络无响应
				$Buser->rollback(); //数据回滚
				echo json_encode(array("status" => 6, "info" => "由于网络原因，转账失败，请联系管理员"));exit;
			}
			$sxbalance = 0;
		} else {
			$Buser->rollback(); //数据回滚
			echo json_encode(array("status" => 7, "info" => "额度转换失败,错误代码R003"));exit;
		}
		$userinfo = user::getinfo($uid);
		//现金记录
		$data_c = array();
		$remark = "系统转出" . $g_type . ":" . $credit . " 元," . $g_type . "余额:" . ($sxbalance + $credit) . "元";
		$data_c['uid'] = $uid;
		$data_c['agent_id'] = $_SESSION['agent_id'];
		$data_c['username'] = $userinfo['username'];
		$data_c['site_id'] = SITEID;
		$data_c['cash_type'] = 1;
		$data_c['cash_do_type'] = 1; //表示存入
		$data_c['cash_num'] = $credit;
		$data_c['cash_balance'] = $userinfo['money'];
		$data_c['cash_date'] = date('Y-m-d H:i:s');
		$data_c['remark'] = $remark;
		$log_2 = $Buser->setTable("k_user_cash_record")
		               ->add($data_c);
		if ($log_1 && $log_2) {
			$Buser->commit();
			//视讯开始加款
			
		} else {
			$Buser->rollback(); //数据回滚
			echo json_encode(array("status" => 8, "info" => "额度转换失败,错误代码R001"));exit;
		}
	} catch (Exception $e) {
		$Buser->rollback(); //数据回滚
		echo json_encode(array("status" => 9, "info" => "额度转换失败,错误代码R002"));exit;
	}
}

$data = $games->TransferCredit($username, $g_type, $tc_type, $credit);
if (empty($data)) {
	echo json_encode(array("status" => 10, "info" => "由于网络原因，转账失败，请联系管理员 "));exit;
}
$result = json_decode($data);
if ($result->data->Code == 10006) {
	$data = $games->CreateAccount($username, $userinfo["agent_id"], $g_type);
	if (!empty($data)) {
		$result = json_decode($data);
		if ($result->data->Code != 10011) {
			echo json_encode(array("status" => 11, "info" => "额度转换失败,错误代码R004 "));exit;
		} else {
			//用户添加成功转账重试
			$data = $games->TransferCredit($username, $g_type, $tc_type, $credit);
			if (empty($data)) {
				echo json_encode(array("status" => 12, "info" => "由于网络原因，转账失败，请联系管理员 "));exit;
			}
			$result = json_decode($data);
		}
	}
}

if ($result->data->Code == 10013) {
	if ($tc_type == "OUT") {
		$data_u = array();
		$data_u['money'] = array('+', $credit);
		$Buser = M("k_user", $db_config);
		$Buser->begin();
		try {
			$log_1 = $Buser->where("uid = '" . $uid . "'")
			               ->update($data_u);
			$data = $games->GetBalance($username, $g_type);
			$result = json_decode($data);
			if ($result->data->Code == 10017) {
				$sxbalance = floatval($result->data->balance);
			} else {
				$Buser->rollback(); //数据回滚
				echo json_encode(array("status" => 13, "info" => "额度转换失败,错误代码R003 "));exit;
			}
			$userinfo = user::getinfo($uid);
			//现金记录
			$data_c = array();
			$remark = $g_type . "转系统：" . $credit . " 元," . $g_type . ":" . $sxbalance . "元";
			$data_c['uid'] = $uid;
			$data_c['username'] = $userinfo['username'];
			$data_c['agent_id'] = $_SESSION['agent_id'];
			$data_c['site_id'] = SITEID;
			$data_c['cash_type'] = 1;
			$data_c['cash_do_type'] = 1;
			$data_c['cash_num'] = $credit;
			$data_c['cash_balance'] = $userinfo['money'];
			$data_c['cash_date'] = date('Y-m-d H:i:s');
			$data_c['remark'] = $remark;
			$log_2 = $Buser->setTable("k_user_cash_record")
			               ->add($data_c);
			if ($log_1 && $log_2) {
				$Buser->commit();
				//视讯开始加款
			} else {
				$Buser->rollback(); //数据回滚
				echo json_encode(array("status" => 14, "info" => "额度转换失败,错误代码R001 "));exit;
			}
		} catch (Exception $e) {
			$Buser->rollback(); //数据回滚
			echo json_encode(array("status" => 15, "info" => "额度转换失败,错误代码R002 "));exit;
		}
	}
	echo json_encode(array("status" => 16, "info" => "转账成功 "));exit;
} else {
	echo json_encode(array("status" => 17, "info" => "转账失败，余额不足或第三方正在维护中 "));exit;
}

?>
