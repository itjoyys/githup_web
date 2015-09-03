<?php
include_once "../../include/config.php";
include_once "../common/login_check.php";
include_once "../../class/Level.class.php";
include_once "../../lib/video/Games.class.php";
//退佣
//s_h 表示 股东 u_a 表示总代 a_t 表示代理商
//atype
//<option value="">请选择</option>
//<option value="1">股东</option>
//<option value="2">总代理</option>
//<option value="3">代理</option>
//期数读取
//代理
////////////////////////////////////////////////////////////////
////参数过滤
//search:true
//searchbtn:查詢
ini_set("display_errors", "On");
error_reporting(0);

$qs = intval($_POST['qs']); //期数
$atype = intval($_POST['atype']); //0 全部,1 股东,2 总代理,3  代理
$username = trim($_POST['username']); //代理的名字
$kf_ty = intval($_POST['kf_ty']); // -1 全部,0 未达门坎,1 达到门槛
//$qs = 33;
if ($qs > 0) {
	$qishu = M('k_qishu', $db_config)->where("site_id = '" . SITEID . "' and id=$qs AND is_delete = '0'")->order('id desc')->find();
}
if ($atype >= 1 && empty($username)) {
	message('请输入股东,总代理,或者代理，的用户名！');
}
//期数时间
if (!empty($qishu)) {
	$qishu['start_date'] = $qishu['start_date'] . ' 00:00:00';
	$qishu['end_date'] = $qishu['end_date'] . ' 23:59:59';

//需要统计的agentud
	$agents = getallagentid($atype, $username);
	$arr_agentid = array();
	foreach ($agents as $value) {
		$arr_agentid[] = $value["id"];
	}
//map以uid做key的数组
	$alluserbetsbyagent = array();
	$alluserbetsbyagent["cbet"] = array();
	$alluserbetsbyagent["kbet"] = array();
	$alluserbetsbyagent["shixun"] = array();
	$alluserbetsbyagent["dianzi"] = array();
//彩票
	$userCbets = M('c_bet', $db_config)->field('uid,username ,count(*) as times,agent_id,sum(win) as swin,sum(money) as smoney')->where("agent_id in (" . implode(",", $arr_agentid) . ") and status in (1,2) and addtime>='" . $qishu['start_date'] . "' and addtime<='" . $qishu['end_date'] . "'")->group('uid')->select();

	foreach ($userCbets as $value) {
		$alluserbetsbyagent["cbet"][$value['uid']] = $value;
	}

//体育
	$userKbets = M('k_bet', $db_config)->field('uid,count(*) as times,agent_id,ben_add,sum(win) as swin,sum(bet_money) as smoney')->where("agent_id in (" . implode(",", $arr_agentid) . ") and status in (1,2,4,5) and bet_time>='" . $qishu['start_date'] . "' and bet_time<='" . $qishu['end_date'] . "'")->group('uid')->select();
	foreach ($userKbets as $value) {
		//如果存在相加
		$alluserbetsbyagent["kbet"][$value['uid']] = $value;

	}

	//echo "<br/><br/>";
	//体彩串关(将体育与串关加起来。组成体育的总数据)
	$Kbets_group = M('k_bet_cg_group', $db_config)->field('uid,count(*) as times,agent_id,sum(win) as swin,sum(bet_money) as smoney')->where("agent_id in (" . implode(",", $arr_agentid) . ") and status in (1,2,4,5) and bet_time>='" . $qishu['start_date'] . "' and bet_time<='" . $qishu['end_date'] . "'")->group('uid')->select();
	foreach ($Kbets_group as $value) {
		if (isset($alluserbetsbyagent["kbet"][$value['uid']])) {
			$oldvalue = $alluserbetsbyagent["kbet"][$value['uid']];
			$oldvalue["swin"] = $oldvalue["swin"] + $value['swin'];
			$oldvalue["smoney"] = $oldvalue["smoney"] + $value['smoney'];
			$oldvalue["times"] = $oldvalue["times"] + $value['times'];
			$alluserbetsbyagent["kbet"][$value['uid']] = $oldvalue;
		} else {
			$alluserbetsbyagent["kbet"][$value['uid']] = $value;
		}
	}

//var_dump($Kbets_group);
	//echo "<br/><br/>";

	$usernames = array();
	$games = new Games();
//视讯
	$shixunbets = $games->GetAllUserAvailableAmountByAgentid(implode("|", $arr_agentid), $qishu['start_date'], $qishu['end_date']);
//转换uid，和agent_id
	$de_json = json_decode($shixunbets);
	unset($shixunbets);
	$shixunbets = array();
	if (isset($de_json->data->Code) && $de_json->data->Code == 10023) {
		foreach ($de_json->data->data as $value) {
			$shixunbets[$value->username] = $value;
			$usernames[] = $value->username;
		}
	}

//电子
	$dzbets = $games->GetAllUserAvailableAmountByAgentid(implode("|", $arr_agentid), $qishu['start_date'], $qishu['end_date'], "1");
//BetBS": 打码次数, "BetYC": 有效投注, "BetPC派彩
	$de_json = json_decode($dzbets);
	unset($dzbets);
	$dzbets = array();
	if (isset($de_json->data->Code) && $de_json->data->Code == 10023) {
		foreach ($de_json->data->data as $value) {
			$dzbets[$value->username] = $value;
			$usernames[] = $value->username;
		}
	}
	$usernames = array_unique($usernames);
//var_dump($usernames);
	//TODO根据用户统一整理数据
	$mdoels = M('k_user', $db_config);
	$users = $mdoels->field("uid,username,agent_id")
	                ->where("site_id = '" . SITEID . "' and username in('" . implode("','", $usernames) . "')")->select();
/*
'uid' => string '692' (length=3)
'username' => string 'adminww' (length=7)
'times' => string '13' (length=2)
'agent_id' => string '39' (length=2)
'swin' => string '196.00' (length=6)
'smoney' => string '130.00' (length=6)
//
public 'username' => string 'ccc3456' (length=7)
public 'BetBS' => int 16
public 'BetYC' => int 415
public 'BetPC' => int 310
 */
	foreach ($users as $value) {
		if (isset($shixunbets[$value['username']])) {

			$value['times'] = $shixunbets[$value['username']]->BetBS;
			$value['swin'] = $shixunbets[$value['username']]->BetPC;
			$value['smoney'] = $shixunbets[$value['username']]->BetYC;
			$alluserbetsbyagent["shixun"][$value['uid']] = $value;

		}
		if (isset($dzbets[$value['username']])) {
			$value['times'] = $dzbets[$value['username']]->BetBS;
			$value['swin'] = $dzbets[$value['username']]->BetPC;
			$value['smoney'] = $dzbets[$value['username']]->BetYC;
			$alluserbetsbyagent["dianzi"][$value['uid']] = $value;
		}
	}
	
	$vailduser = array();
	foreach ($alluserbetsbyagent as $type => $typebets) {
		foreach ($typebets as $key => $value) {
			$vailduser[$key]['money'] += $value['smoney'];
			$vailduser[$key]['agent_id'] = $value['agent_id'];
		}
	}

////////////////////////////////////
	//p($alluserbetsbyagent);
	//var_dump($alluserbetsbyagent); //彩票+体育
	//整合用户的打码量
	//uid做key统计有效会员
	 //有效会员统计
	$agentbets = array(); //有效会员统计
	foreach ($agents as $k => $v) {
		foreach ($alluserbetsbyagent as $type => $typebets) {

			foreach ($typebets as $key => $value) {

				if ($v['id'] == $value['agent_id']) {
					//if (isset($vailduser[$key])) {
					//$vailduser[$value['agent_id']]['money'] += $value["smoney"];
					//$vailduser[$value['agent_id']]['agent_id'] = $value["agent_id"];
					//} else {
					//$vailduser[$value['agent_id']]['money'] = $value["smoney"];
					//$vailduser[$value['agent_id']]['agent_id'] = $value["agent_id"];
					//}
					//if (isset($agentbets[$value["agent_id"]]) && isset($agentbets[$value["agent_id"]][$type])) {
					$agentbets[$value["agent_id"]][$type]["times"] += $value["times"];
					$agentbets[$value["agent_id"]][$type]["swin"] += $value["swin"];
					$agentbets[$value["agent_id"]][$type]["smoney"] += $value["smoney"];
					//} else {
					//$agentbets[$value["agent_id"]][$type]["times"] = $value["times"];
					//$agentbets[$value["agent_id"]][$type]["swin"] = $value["swin"];
					//$agentbets[$value["agent_id"]][$type]["smoney"] = $value["smoney"];
					//}



				}

			}
		}

		// if($value['id'] = ){

		// }
	}
//p($vailduser);

//****************************************是否达到门槛判断******************/
	$hire = M('k_hire_config', $db_config)->where("site_id='" . SITEID . "' and is_delete ='0'")->order('self_profit DESC')->select();
//有效会员数量
	//var_dump($vailduser);
	
	foreach ($vailduser as $key => $value) {
		if (($value['money']) >= $hire[0]['valid_money']) {
			if (isset($agentbets[$value["agent_id"]])) {
				$agentbets[$value["agent_id"]]["vaildusercount"] += 1;
			} else {
				$agentbets[$value["agent_id"]]["vaildusercount"] = 1;
			}
		} else {
			unset($vailduser[$key]);
		}
	}
//
	//var_dump($agentbets);
	//var_dump($agentbets);
	//统计完成
	/*****************************费用开始****************************/
//读取手续费设定 //获取最新的手续
	$fee = M('k_fee_set', $db_config)->where("site_id='" . SITEID . "' and valid_date <='" . $qishu['start_date'] . "' and is_delete='0'")->order('valid_date desc')->find();

	$all_now_incash = array(); //总入款
	$inmaxcash = $fee['in_max_fee'] / ($fee['in_fee'] * 0.01);
	$outmaxcash = $fee['out_max_fee'] / ($fee['out_fee'] * 0.01);
//后台入款费用计算 agent_id
	$model = M('k_user_catm', $db_config);
	$ht_cash = $model->field('uid,sum(catm_money) as money,sum(catm_give) as d1,sum(atm_give) as d2,agent_id')
	                 ->where("agent_id in (" . implode(",", $arr_agentid) . ") and catm_type in (1,2,3,4,5,6,7,8) and updatetime>='" . $qishu['start_date'] . "' and updatetime<='" . $qishu['end_date'] . "' and is_rebate=1 AND catm_money <" . $inmaxcash)->group('agent_id')->select();
	foreach ($ht_cash as $value) {
		$all_now_incash[$value['agent_id']]['money'] += $value['money'] * $fee['in_fee'] * 0.01;
		$all_now_incash[$value['agent_id']]['money'] += $value['d1'] + $value['d2'];
	}
//后台入款费用计算 agent_id 大于最高限额
	$ht_cash = $model->field('uid,count(*) as times,sum(catm_give) as d1,sum(atm_give) as d2,agent_id')
	                 ->where("agent_id in (" . implode(",", $arr_agentid) . ") and catm_type in (1,2,3,4,5,6,7,8) and updatetime>='" . $qishu['start_date'] . "' and updatetime<='" . $qishu['end_date'] . "' and is_rebate=1 AND catm_money >=" . $inmaxcash)->group('agent_id')->select();
	foreach ($ht_cash as $value) {
		$all_now_incash[$value['agent_id']]['money'] += $value['times'] * $inmaxcash * $fee['in_fee'] * 0.01;
		$all_now_incash[$value['agent_id']]['money'] += $value['d1'] + $value['d2'];
	}

	//线上入款费用计算 agent_id
	$models = M('k_user_cash_record', $db_config);
	$xs_cash = $models->field('uid,sum(cash_num) as money,sum(discount_num) as d1,agent_id')
	                 ->where("agent_id in (" . implode(",", $arr_agentid) . ") and cash_type = 10 and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "' and cash_do_type=1 AND cash_num <" . $inmaxcash)->group('agent_id')->select();

	foreach ($xs_cash as $value) {
		$all_now_incash[$value['agent_id']]['money'] += $value['money'] * $fee['in_fee'] * 0.01;
		$all_now_incash[$value['agent_id']]['money'] += $value['d1'];
	}
//线上入款费用计算 agent_id 大于最高限额
	$xs_cash = $models->field('uid,count(*) as times,sum(discount_num) as d1,agent_id')
	                 ->where("agent_id in (" . implode(",", $arr_agentid) . ") and cash_type = 10 and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "' and cash_do_type=1 AND cash_num >=" . $inmaxcash)->group('agent_id')->select();
	foreach ($xs_cash as $value) {
		$all_now_incash[$value['agent_id']]['money'] += $value['times'] * $inmaxcash * $fee['in_fee'] * 0.01;
		$all_now_incash[$value['agent_id']]['money'] += $value['d1'];
	}
	/*
	//线上入款
	$ht_cash = M('k_user_cash_record', $db_config)->field('uid,sum(catm_money) as money,sum(catm_give) as d1,sum(atm_give) as d2,agent_id')
	->where("agent_id in (" . implode(",", $arr_agentid) . ") and catm_type in (2,3,4,5,6,7,8) and updatetime>='" . $qishu['start_date'] . "' and updatetime<='" . $qishu['end_date'] . "' and is_rebate=1 ")->group('agent_id')->select();
	foreach ($ht_cash as $value) {
	$all_now_incash[$value['agent_id']]['money'] += $value['money'];
	$all_now_incash[$value['agent_id']]['money'] += $value['d1'] + $value['d2'];
	}*/

//前台会员申请入款计算 agent_id
	$table = M('k_user_cash_record', $db_config);
	$qt_in_cash = $table->field('uid,sum(cash_num) as money,sum(discount_num) as discount_num,agent_id')
	                    ->where("agent_id in (" . implode(",", $arr_agentid) . ") and cash_type='11' and cash_do_type='1' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "' AND cash_num <" . $inmaxcash)
	                    ->group('agent_id')->select();
	foreach ($qt_in_cash as $value) {
		$all_now_incash[$value['agent_id']]['money'] += $value['money'] * $fee['in_fee'] * 0.01;
		$all_now_incash[$value['agent_id']]['money'] += $value['discount_num'];
	}
//前台会员申请入款计算 agent_id 大于最高限额
	$table = M('k_user_cash_record', $db_config);
	$qt_in_cash = $table->field('uid,count(*) as times,sum(discount_num) as discount_num,agent_id')
	                    ->where("agent_id in (" . implode(",", $arr_agentid) . ") and cash_type='11' and cash_do_type='1' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "' AND cash_num >=" . $inmaxcash)
	                    ->group('agent_id')->select();
	foreach ($qt_in_cash as $value) {
		$all_now_incash[$value['agent_id']]['money'] += $value['times'] * $inmaxcash * $fee['in_fee'] * 0.01;
		$all_now_incash[$value['agent_id']]['money'] += $value['discount_num'];
	}
//前台注册赠送的优惠
	$table = M('k_user_cash_record', $db_config);
	$qt_zc_cash = $table->field('k_user_cash_record.uid,sum(k_user_cash_record.discount_num) as money,k_user.agent_id')->where("k_user.agent_id in (" . implode(",", $arr_agentid) . ") AND k_user_cash_record.cash_type='6' and k_user_cash_record.cash_do_type='1' and k_user_cash_record.cash_date>='" . $qishu['start_date'] . "' and k_user_cash_record.cash_date<='" . $qishu['end_date'] . "'")->join('left join k_user on k_user.uid=k_user_cash_record.uid')->group('k_user.agent_id')->select();
	foreach ($qt_zc_cash as $value) {
//if (isset($all_now_incash[$value['agent_id']])) {
		$all_now_incash[$value['agent_id']]['money'] += $value["money"];
//} else {
		//	$all_now_incash[$value['agent_id']]['money'] = $value["money"];
		//}
	}
//前台会员申请出款的计算
	$table = M('k_user_cash_record', $db_config);
	$qt_out_cash = $table->field('uid,sum(cash_num) as money,agent_id')
	                     ->where("agent_id in (" . implode(",", $arr_agentid) . ") and cash_type='19' and cash_do_type='2' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "' AND cash_num <" . $outmaxcash)->group('agent_id')->select();
	foreach ($qt_out_cash as $value) {
		$all_now_incash[$value['agent_id']]['money'] += $value['money'] * $fee['out_fee'] * 0.01;
	}
//前台会员申请出款的计算 大于最高限额
	$table = M('k_user_cash_record', $db_config);
	$qt_out_cash = $table->field('uid,count(*) as times,sum(cash_num) as money,agent_id')
	                     ->where("agent_id in (" . implode(",", $arr_agentid) . ") and cash_type='19' and cash_do_type='2' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "' AND cash_num >=" . $outmaxcash)->group('agent_id')->select();
	foreach ($qt_out_cash as $value) {
		$all_now_incash[$value['agent_id']]['money'] += $value['times'] * $outmaxcash * $fee['out_fee'] * 0.01;
	}
//天天返水
	$table = M('k_user_discount_count', $db_config);
	$qt_out_cash = $table->field('uid,sum(total_e_fd) as money,agent_id')
	                     ->where("agent_id in (" . implode(",", $arr_agentid) . ") and state='1' and do_time>='" . $qishu['start_date'] . "' and do_time<='" . $qishu['end_date'] . "'")->group('agent_id')->select();
	foreach ($qt_out_cash as $value) {
		$all_now_incash[$value['agent_id']]['money'] += $value['money'];
	}

//所有有手续费//
	//////////////////////////////////
	//------------------------------门槛计算
	//$oldbets = M('k_user_agent_record', $db_config)->field('sum(now_bet) as oldbet,sum(now_cbet) as oldcbet,sum(now_kbet) as oldkbet,sum(now_vbet) as oldvbet,sum(now_ebet) as oldebet,sum(now_validbet) as oldvalidbet ,sum(now_validcbet) as oldvalidcbet,sum(now_validkbet) as oldvalidkbet,sum(now_validvbet) as oldvalidvbet,sum(now_validebet) as oldvalidebet,sum(nowcash) as oldcash,sum(valid_usernum) as old_usernum,agent_id')->where("agent_id in (" . implode(",", $arr_agentid) . ") and status in (0,4,6) and qishu_starttime<'" . $qishu['start_date'] . "' and site_id='" . SITEID . "' and type=0")->group('agent_id')->select();
	$resulet = M('k_user_agent_record', $db_config)->field('max(qishu_id) as qishu_id')->where("agent_id in (" . implode(",", $arr_agentid) . ") and qishu_starttime<'" . $qishu['start_date'] . "' and site_id='" . SITEID . "' and type=0")->find();
	$oldbets = M('k_user_agent_record', $db_config)->field('now_bet as oldbet,now_cbet as oldcbet,now_kbet as oldkbet,now_vbet as oldvbet,now_ebet as oldebet,now_validbet as oldvalidbet ,now_validcbet as oldvalidcbet,now_validkbet as oldvalidkbet,now_validvbet as oldvalidvbet,now_validebet as oldvalidebet,nowcash as oldcash,valid_usernum as old_usernum,agent_id')->where("agent_id in (" . implode(",", $arr_agentid) . ") and status in (0,4) and qishu_starttime<'" . $qishu['start_date'] . "' and site_id='" . SITEID . "' and type=0 and qishu_id = '" . $resulet['qishu_id'] . "'")->select();

	if (!empty($oldbets)) {
		foreach ($oldbets as $oldbet) {
			$agentbets[$oldbet['agent_id']]['old_bet'] = $oldbet['oldbet']; //总盈利
			$agentbets[$oldbet['agent_id']]['old_cbet'] = $oldbet['oldcbet']; //彩票总盈利
			$agentbets[$oldbet['agent_id']]['old_kbet'] = $oldbet['oldkbet']; //体育
			$agentbets[$oldbet['agent_id']]['old_vbet'] = $oldbet['oldvbet']; //视讯
			$agentbets[$oldbet['agent_id']]['old_ebet'] = $oldbet['oldebet']; //电子
			$agentbets[$oldbet['agent_id']]['old_validbet'] = $oldbet['oldvalidbet']; // 总有效
			$agentbets[$oldbet['agent_id']]['old_validcbet'] = $oldbet['oldvalidcbet']; //彩票的有效
			$agentbets[$oldbet['agent_id']]['old_validkbet'] = $oldbet['oldvalidkbet']; //体育的有效
			$agentbets[$oldbet['agent_id']]['old_validvbet'] = $oldbet['oldvalidvbet']; //视讯的有效打码
			$agentbets[$oldbet['agent_id']]['old_validebet'] = $oldbet['oldvalidebet']; //电子的有效打码
			$agentbets[$oldbet['agent_id']]['old_cash'] = $oldbet['oldcash']; //手续费
			$agentbets[$oldbet['agent_id']]['old_usernum'] = $oldbet['old_usernum']; //有效会员
		}
	}
//p($agentbets);
	//如果往期的数据可以累计则在这里加上
	foreach ($agentbets as $aid => $typebetcount) {
		$vtimes = intval($typebetcount["vaildusercount"]); //有效会员数量
		$allgain = 0; //所有盈利
		$allavailbet = 0;

		$allgain += floatval($typebetcount['cbet']['smoney']) - floatval($typebetcount['cbet']['swin']);
		$allavailbet += floatval($typebetcount['cbet']['smoney']);

		$allgain += floatval($typebetcount['kbet']['smoney']) - floatval($typebetcount['kbet']['swin']);
		$allavailbet += floatval($typebetcount['kbet']['smoney']);

		$allgain += 0 - floatval($typebetcount['shixun']['swin']);
		$allavailbet += floatval($typebetcount['shixun']['smoney']);

		$allgain += 0 - floatval($typebetcount['dianzi']['swin']);
		$allavailbet += floatval($typebetcount['dianzi']['smoney']);

		//当期的
		$agentbets[$aid]['allavailbet'] = $allavailbet;
		$agentbets[$aid]['all_profit'] = $allgain;
		//判断门槛加上往期的累计
		$allgain += floatval($agentbets[$aid]['old_bet']);
		$allavailbet += floatval($agentbets[$aid]['old_validbet']);

		$vtimes += intval($agentbets[$aid]['old_usernum']);
		//var_dump($allavailbet);
		$turehire = array();
		//从最高单独
		foreach ($hire as $value) {
			if (floatval($value['self_effective_bet']) <= $allavailbet && floatval($value['self_profit']) <= $allgain && floatval($value['effective_user']) <= $vtimes) {
				$turehire = $value;
				break;
			}
		}
		$agentbets[$aid]['hire'] = $turehire;
	}

//////////////////////////////////////////////////////////////////////////////////
	//本期没有数据，往期有累计
	//----统计整合所有代理信息

	foreach ($agents as $value) {
		if (isset($agentbets[$value['id']])) {
			$agent_item = $agentbets[$value['id']];
			$agentbets[$value['id']]['feemoney'] = floatval($all_now_incash[$value['id']]["money"]);
			$agentbets[$value['id']]['fee'] = $fee;
			$agentbets[$value['id']]['agent_user'] = $value['agent_user'];
			$agentbets[$value['id']]['agent_name'] = $value['agent_name'];
			$agentbets[$value['id']]['retucash'] = getagentProfit($agentbets[$value['id']]);

			$agentbets[$value['id']]['now_cbet'] = floatval($agent_item['cbet']['smoney']) - floatval($agent_item['cbet']['swin']); //彩票总盈利
			$agentbets[$value['id']]['now_kbet'] = floatval($agent_item['kbet']['smoney']) - floatval($agent_item['kbet']['swin']); //体育
			$agentbets[$value['id']]['now_vbet'] = 0 - floatval($agent_item['shixun']['swin']); //视讯
			$agentbets[$value['id']]['now_ebet'] = 0 - floatval($agent_item['dianzi']['swin']); //电子
			$agentbets[$value['id']]['now_validcbet'] = floatval($agent_item['cbet']['smoney']); //彩票的有效
			$agentbets[$value['id']]['now_validkbet'] = floatval($agent_item['kbet']['smoney']); //体育的有效
			$agentbets[$value['id']]['now_validvbet'] = floatval($agent_item['shixun']['smoney']); //视讯的有效打码
			$agentbets[$value['id']]['now_validebet'] = floatval($agent_item['dianzi']['smoney']); //电子的有效打码
		} else {
			$agentbets[$value['id']]['feemoney'] = floatval($all_now_incash[$value['id']]["money"]);
			$agentbets[$value['id']]['fee'] = $fee;
			$agentbets[$value['id']]['agent_user'] = $value['agent_user'];
			$agentbets[$value['id']]['agent_name'] = $value['agent_name'];
			$agentbets[$value['id']]['retucash'] = getagentProfit($agentbets[$value['id']]);

			$agentbets[$value['id']]['now_cbet'] = 0; //floatval($agent_item['cbet']['smoney']) - floatval($agent_item['cbet']['swin']); //彩票总盈利
			$agentbets[$value['id']]['now_kbet'] = 0; //floatval($agent_item['kbet']['smoney']) - floatval($agent_item['kbet']['swin']); //体育
			$agentbets[$value['id']]['now_vbet'] = 0; //0 - floatval($agent_item['shixun']['swin']); //视讯
			$agentbets[$value['id']]['now_ebet'] = 0; //0 - floatval($agent_item['dianzi']['swin']); //电子
			$agentbets[$value['id']]['now_validcbet'] = 0; //floatval($agent_item['cbet']['smoney']); //彩票的有效
			$agentbets[$value['id']]['now_validkbet'] = 0; //floatval($agent_item['kbet']['smoney']); //体育的有效
			$agentbets[$value['id']]['now_validvbet'] = 0; //floatval($agent_item['shixun']['smoney']); //视讯的有效打码
			$agentbets[$value['id']]['now_validebet'] = 0; //floatval($agent_item['dianzi']['smoney']); //电子的有效打码
		}
	}

//存档
	// p($agents);

	if (isset($_POST['savebtn'])) {
		foreach ($agentbets as $aid => $agent) {

			//
			$oldrecord = M('k_user_agent_record', $db_config)->where("qishu_id=" . $qishu['id'] . " and agent_id=" . $aid)->find();
			$data_m = array();
			$data_m['agent_id'] = $aid;
			$data_m['agent_user'] = $agent['agent_user'];
			$data_m['agent_name'] = $agent['agent_name'];

			$data_m['old_bet'] = floatval($agent['old_bet']);
			$data_m['old_cbet'] = floatval($agent['old_cbet']);
			$data_m['old_kbet'] = floatval($agent['old_kbet']);
			$data_m['old_vbet'] = floatval($agent['old_vbet']);
			$data_m['old_ebet'] = floatval($agent['old_ebet']);
			$data_m['now_bet'] = floatval($agent['all_profit']); //总盈利
			$data_m['now_cbet'] = floatval($agent['now_cbet']);
			$data_m['now_kbet'] = floatval($agent['now_kbet']);
			$data_m['now_vbet'] = floatval($agent['now_vbet']);
			$data_m['now_ebet'] = floatval($agent['now_ebet']);

			$data_m['valid_usernum'] = floatval($agent['vaildusercount'] + $agent['old_usernum']);
			$data_m['sport_slay_rate'] = floatval($agent['hire']['sport_slay_rate']);

			$data_m['lottery_slay_rate'] = floatval($agent['hire']['lottery_slay_rate']);
			$data_m['video_slay_rate'] = floatval($agent['hire']['video_slay_rate']);

			$data_m['evideo_slay_rate'] = floatval($agent['hire']['evideo_slay_rate']);
			$data_m['old_validbet'] = floatval($agent['old_validbet']);
			$data_m['old_validcbet'] = floatval($agent['old_validcbet']);
			$data_m['old_validkbet'] = floatval($agent['old_validkbet']);
			$data_m['old_validvbet'] = floatval($agent['old_validvbet']);
			$data_m['old_validebet'] = floatval($agent['old_validebet']);

			$data_m['now_validbet'] = floatval($agent['allavailbet']);
			$data_m['now_validcbet'] = floatval($agent['now_validcbet']);
			$data_m['now_validkbet'] = floatval($agent['now_validkbet']);
			$data_m['now_validvbet'] = floatval($agent['now_validvbet']);
			$data_m['now_validebet'] = floatval($agent['now_validebet']);
			$data_m['sport_water_rate'] = floatval($agent['hire']['sport_water_rate']);

			$data_m['lottery_water_rate'] = floatval($agent['hire']['lottery_water_rate']);
			$data_m['video_water_rate'] = floatval($agent['hire']['video_water_rate']);

			$data_m['evideo_water_rate'] = floatval($agent['hire']['evideo_water_rate']);
			$data_m['qishu_id'] = $qishu['id'];
			$data_m['qishu_starttime'] = str_replace(' 00:00:00', '', $qishu['start_date']);
			$data_m['qishu_endtime'] = str_replace(' 23:59:59', '', $qishu['end_date']);

			$data_m['oldcash'] = floatval($agent['old_cash']); //手续费
			$data_m['nowcash'] = floatval($agent['feemoney']);

			$data_m['retuCash'] = floatval($agent['retucash']);
			$data_m['site_id'] = SITEID;
			$data_m['bank'] = bank_type($agent['bankid']) . '<br>' . $agent['bankno'];
			$data_m['info'] = "";
			$data_m['hascash'] = 0;
			//var_dump($data_m);
			//exit();
			$num = $agent['vaildusercount'] + $agent['old_usernum'];
			if (empty($num)) {
				$data_m['status'] = 4;
			} else {
				$data_m['status'] = 0;
			}
			$record = M('k_qishu', $db_config)->where("id=" . $qishu['id'])->find();
			if ($record['state'] == 1) {
				message('该期退佣已停止！', 'agent_count.php');
			}

			if (empty($oldrecord)) {
				$model = M('k_user_agent_record', $db_config);
				$model->add($data_m);
			} else {
				M('k_user_agent_record', $db_config)->where("qishu_id=" . $qishu['id'] . " and agent_id=" . $aid)->update($data_m);

			}
		}
		$do_log = $_SESSION['login_name'] . '存档了退佣统计';
		admin::insert_log($_SESSION['adminid'], $_SESSION['login_name'], $do_log);
	}
}
///retuCash等于
//var_dump($agentbets);
//计算可以获得的退佣

/**
 * 计算代理本期的可获退佣
 * @param  [type] $agentbets [description]
 * @return [type]            [description]
 */
function getagentProfit($agentbets) {
	//如果未达到门槛返回
	if (empty($agentbets['hire']) || intval($agentbets['hire']['id']) <= 0) {
		return 0;
	}

	$returncash = 0;
	$othercash = $endcash = 0;

	$sxfcash = floatval($agentbets['feemoney']) + floatval($agentbets['old_cash']); //当前手续费
	$hire = $agentbets['hire'];
	//彩票
	$agentbets['oldvalidcbet'] = floatval($agentbets['old_validcbet']) + floatval($agentbets['cbet']['smoney']);

	//累计上期 'swin' => float 3539.76 'smoney' => float 5884
	//体育
	$agentbets['old_kbet'] = floatval($agentbets['old_kbet']);
	$agentbets['old_kbet'] += floatval($agentbets['kbet']['smoney']) - floatval($agentbets['kbet']['swin']);
	//视讯
	$agentbets['old_vbet'] = floatval($agentbets['old_vbet']);
	$agentbets['old_vbet'] += 0 - floatval($agentbets['shixun']['swin']);
	//电子
	$agentbets['old_ebet'] = floatval($agentbets['old_ebet']);
	$agentbets['old_ebet'] += 0 - floatval($agentbets['dianzi']['swin']);

	$agentbets['oldvalidcbet'] = floatval($agentbets['oldvalidcbet']);
	$agentbets['old_kbet'] = floatval($agentbets['old_kbet']);
	$agentbets['old_vbet'] = floatval($agentbets['old_vbet']);
	$agentbets['old_ebet'] = floatval($agentbets['old_ebet']);

	//可获退佣计算

	$cbet_you = $agentbets['oldvalidcbet']; //彩票有效投注

	$kbet_pai = $agentbets['old_kbet']; //体育总派彩
	$ebet_pai = $agentbets['old_ebet']; //电子总派彩
	$vbet_pai = $agentbets['old_vbet']; //视讯总派彩
	//var_dump($sxfcash);
	$shengyuqian = $cbet_you * $hire['lottery_water_rate'] * 0.01 - $sxfcash; //剩余待扣金额

	//视讯
	if ($vbet_pai < 0) {
		$v_huo = $vbet_pai * $hire['video_slay_rate'] * 0.01;
	} else {
		$shengyuqian += $vbet_pai;
		if ($shengyuqian > 0) {
			$v_huo = $shengyuqian * $hire['video_slay_rate'] * 0.01;
			$shengyuqian = 0;
		} else {
			$v_huo = 0;
		}
	}

	//电子
	if ($ebet_pai < 0) {
		$e_huo = $ebet_pai * $hire['evideo_slay_rate'] * 0.01;
	} else {
		$shengyuqian += $ebet_pai;
		if ($shengyuqian > 0) {
			$e_huo = $shengyuqian * $hire['evideo_slay_rate'] * 0.01;
			$shengyuqian = 0;
		} else {
			$e_huo = 0;
		}
	}

	//p($kbet_pai);
	//体育
	if ($kbet_pai < 0) {
		$k_huo = $kbet_pai * $hire['sport_slay_rate'] * 0.01;
		if ($shengyuqian < 0) {
			$k_huo += $shengyuqian;
		}
	} else {
		$shengyuqian += $kbet_pai;
		if ($shengyuqian > 0) {

			$k_huo = $shengyuqian * $hire['sport_slay_rate'] * 0.01;

		} else {
			//$k_huo = $kbet_pai * $hire['sport_slay_rate'] * 0.01;

			$k_huo += $shengyuqian;
		}
	}

	return $v_huo + $e_huo + $k_huo;

/*

//彩票反水
$cpfs = $agentbets['oldvalidcbet'] * $hire['lottery_water_rate'] * 0.01;
$sxfcash = $cpfs - $sxfcash;

if ($sxfcash < 0 && $agentbets['old_vbet'] > 0) {
//视讯
$sxfcash = $agentbets['old_vbet'] + $sxfcash;
} else if ($agentbets['old_vbet'] < 0 || $sxfcash >= 0) {
//如果为正数 TODO 一般不会
$endcash = $sxfcash;
$othercash += $agentbets['old_vbet'] * $hire['video_slay_rate'] * 0.01;
}
//
if ($sxfcash < 0 && $agentbets['old_ebet'] > 0) {
//电子
$sxfcash = $agentbets['old_ebet'] + $sxfcash;
} else if ($agentbets['old_ebet'] < 0 || $sxfcash >= 0) {
//如果为正数
if ($endcash == 0) {
$endcash = $sxfcash * $hire['video_slay_rate'] * 0.01;
}
$othercash += $agentbets['old_ebet'] * $hire['evideo_slay_rate'] * 0.01;
}

if ($sxfcash < 0 && $agentbets['old_kbet'] > 0) {
//体育
$sxfcash = $agentbets['old_kbet'] + $sxfcash;
} else if ($agentbets['old_kbet'] < 0 || $sxfcash >= 0) {
//如果为正数
if ($endcash == 0) {
$endcash = $sxfcash * $hire['evideo_slay_rate'] * 0.01;
}
$othercash += $agentbets['old_kbet'] * $hire['sport_slay_rate'] * 0.01;
}
if ($sxfcash > 0) {
//体育
if ($endcash == 0) {
$endcash = $sxfcash * $hire['sport_slay_rate'] * 0.01;
}
}

return $endcash + $othercash;*/
}

$title = "退佣统计";
require "../common_html/header.php";
$qishus = M('k_qishu', $db_config)->where("site_id = '" . SITEID . "' and is_delete = '0'")->order('id desc')->select();
?>
<body>
<script type="text/javascript">
function show_div(event,ty,cp,yx,dz)
{
	var event = event ? event : window.event;
	var top = left = 0;
	//var div = $("#detail_info");
	$("#detail_yx").text(yx);
	$("#detail_dz").text(dz);
	$("#detail_ty").text(ty);
	$("#detail_cp").text(cp);
	var div = document.getElementById('detail_info');
	top = event.pageY;
	left =event.pageX;
	div.style.top = top + "px";
	div.style.left = left + "px";
	div.style.display = "block";
}
function hide_div(){
	var div = document.getElementById('detail_info');
	div.style.display = "none";
}

</script>
<div id="con_wrap">
<div class="input_002">退傭統計</div>
<div class="con_menu">
<form name="search_form" method="post">
<input type="hidden" name="search" value="true">
	<a href="agent_count.php" style="color:red;">退佣统计</a>
    <a href="agent_search.php">退佣查询</a>
    <a href="end_hire.php">期數管理</a>
	<a href="fee_list.php">手續費設定</a>
	<a href="endhire_list.php">代理退傭設定</a>&nbsp;&nbsp;
   期數選擇：<select name="qs" id="qs" class="za_select">
        <?php if (!empty($qishus)) {
	foreach ($qishus as $key => $val) {
		?>
        <option value="<?=$val['id']?>" <?php if ($qishu['id'] == $val['id']) {?>selected = "selected"<?php }
		?>><?=$val['qsname']?>  </option>
        <?php }}
?>
        </select>
    帳號：
    <select class="za_select" name="atype">
        <option value="">请选择</option>
        <option <?php if ($_POST[atype] == '1') {?> selected = "selected" <?php }
?> value="1">股东</option>
        <option <?php if ($_POST[atype] == '2') {?> selected = "selected" <?php }
?> value="2">总代理</option>
        <option <?php if ($_POST[atype] == '3') {?> selected = "selected" <?php }
?> value="3">代理</option>
    </select>
    <input type="text" name="username" class="za_text" value="<?=$username?>" style="min-width:80px;width:80px">
    <select name="kf_ty">
	<option value="-1" <?php if (!isset($_POST[kf_ty])) {?> selected = "selected" <?php }
?>>全部</option>
  	<option value="0" <?php if ($_POST[kf_ty] == '0') {?> selected = "selected" <?php }
?>>未達門檻</option>
  	<option value="1" <?php if ($_POST[kf_ty] == 1) {?> selected = "selected" <?php }
?>>已達門檻</option>
  	</select>
    &nbsp;&nbsp;<input type="submit" value="查詢" name="searchbtn" class="button_d">
    <?php if (!empty($_POST['searchbtn']) && empty($_POST['savebtn'])) {
	?>
    <input type="submit" value="存檔" onclick="return confirm('是否要保存本期退傭統計結果？')" name="savebtn" class="button_d">
   <?php }
?>
        </form>
</div>
</div>
<div class="content">
<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
	<tbody>
    <tr class="m_title_over_co">
		  <td colspan="20"></td>
    </tr>
		<tr class="m_title_over_co">
		  <td rowspan="2">代理帳號</td>
		  <td rowspan="2">名稱</td>
		  <td rowspan="2">有效會員</td>
          <td rowspan="2">前期累積總派彩</td>
		  <td rowspan="2">當期新增總派彩</td>
		  <td colspan="4">退傭比例(%)</td>
		  <td rowspan="2">前期累積有效投注</td>
          <td rowspan="2">當期新增有效投注</td>
		  <td colspan="4">退水比例(%)</td>
		  <td rowspan="2">前期累積總費用</td>
          <td rowspan="2">當期新增總費用</td>
		  <td colspan="2" rowspan="2">可獲退傭</td>
    </tr>
		<tr class="m_title_over_co">
            <td>視訊</td>
            <td>電子</td>
            <td>體育</td>
            <td>彩票</td>
            <td>視訊</td>
            <td>電子</td>
            <td>體育</td>
            <td>彩票</td>
        </tr>
    <?php if (!empty($agentbets)) {
	foreach ($agentbets as $key => $value) {

		if ($kf_ty == 1) {
			if (empty($value['hire']) || intval($value['hire']['id']) <= 0) {
				unset($agentbets[$key]);
			}
		} else if ($kf_ty == 0) {
			if (!empty($value['hire']) || intval($value['hire']['id']) > 0) {
				unset($agentbets[$key]);
			}
		}
	}
	foreach ($agentbets as $key => $agent) {
		?>
       <tr class="m_cen">
		  <td ><?=$agent['agent_user']?></td>
		  <td ><?=$agent['agent_name']?></td>
		  <td ><?=intval($agent['vaildusercount'] + $agent['old_usernum'])?></td>
          <td onmouseover="show_div(event,&#39;<?=number_format($agent['old_kbet'], 2)?>&#39;,&#39;<?=number_format($agent['old_cbet'], 2)?>&#39;,&#39;<?=number_format($agent['old_vbet'], 2)?>&#39;,&#39;<?=number_format($agent['old_ebet'], 2)?>&#39;)" onmouseout="hide_div()">
          		<?=number_format($agent['old_bet'], 2)?>
          </td>
		  <td onmouseover="show_div(event,&#39;<?=number_format($agent['now_kbet'], 2)?>&#39;,&#39;<?=number_format($agent['now_cbet'], 2)?>&#39;,&#39;<?=number_format($agent['now_vbet'], 2)?>&#39;,&#39;<?=number_format($agent['now_ebet'], 2)?>&#39;)" onmouseout="hide_div()">
		  		<?=number_format($agent['all_profit'], 2)?>
		  </td>
          <td ><?=number_format($agent['hire']['video_slay_rate'], 2)?>%</td>
          <td ><?=number_format($agent['hire']['evideo_slay_rate'], 2)?>%</td>
          <td ><?=number_format($agent['hire']['sport_slay_rate'], 2)?>%</td>
          <td ><?=number_format($agent['hire']['lottery_slay_rate'], 2)?>%</td>
		  <td onmouseover="show_div(event,&#39;<?=number_format($agent['old_validkbet'], 2)?>&#39;,&#39;<?=number_format($agent['old_validcbet'], 2)?>&#39;,&#39;<?=number_format($agent['old_validvbet'], 2)?>&#39;,&#39;<?=number_format($agent['old_validebet'], 2)?>&#39;)" onmouseout="hide_div()">
		  		<?=number_format($agent['old_validbet'], 2)?>
		  </td>
          <td onmouseover="show_div(event,&#39;<?=number_format($agent['now_validkbet'], 2)?>&#39;,&#39;<?=number_format($agent['now_validcbet'], 2)?>&#39;,&#39;<?=number_format($agent['now_validvbet'], 2)?>&#39;,&#39;<?=number_format($agent['now_validebet'], 2)?>&#39;)" onmouseout="hide_div()">
          		<?=number_format($agent['now_validkbet'] + $agent['now_validcbet'] + $agent['now_validvbet'] + $agent['now_validebet'], 2)?>
          </td>
          <td ><?=number_format($agent['hire']['video_water_rate'], 2)?>%</td>
          <td ><?=number_format($agent['hire']['evideo_water_rate'], 2)?>%</td>
           <td ><?=number_format($agent['hire']['sport_water_rate'], 2)?>%</td>
          <td ><?=number_format($agent['hire']['lottery_water_rate'], 2)?>%</td>
		  <td ><?=number_format($agent['old_cash'], 2)?></td>
          <td ><?php
if ($agent['feemoney'] > 0) {
			$feemoney = number_format($agent['feemoney'], 2);
			echo "<a href=\"charge_total.php?qs_id=" . $qishu['id'] . "&agentid=$key\" target=\"_blank\" class=\"a_001\">$feemoney</a>";
		} else {
			echo "0.00";
		}
		?></td>
		  <td ><?=number_format($agent['retucash'], 2)?></td>
		  <td ><?php
if (!empty($agent['hire']) || intval($agent['hire']['id']) > 0) {
			echo "已達門檻";
		} else {
			echo "未達門檻";
		}
		?></td>
 		</tr>
        <?php }}
?>

		</tbody>

  </table>
</div>


<div style="position:absolute;width:300px;display:none;" id="detail_info">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>視訊</td>
            <td>電子</td>
            <td>體育</td>
            <td>彩票</td>
		</tr>
		<tr>
			<td id="detail_yx"></td>
			<td id="detail_dz"></td>
			<td id="detail_ty"></td>
            <td id="detail_cp"></td>
		</tr>
	</tbody></table>
</div>
<!-- 公共尾部 -->
<?php require "../common_html/footer.php";
/**
 * 获取一定条件下的--代理用户uid数组
 * @param  [int] $atype       [0 全部,1 股东,2 总代理,3  代理]
 * @param  [string] $username [当type 1=股东, 2=总代理, 3=代理的名字]
 * @return [array]            [用户id的数组]
 */
function getallagentid($atype, $username) {
	global $db_config;
	$arr_agentid = array();
	$where = " 1=1 ";
	$model = M('k_user_agent', $db_config);
	if ($atype == 0) {
		$where = "agent_type= 'a_t' and site_id='" . SITEID . "' and is_demo='0'";
	} else if ($atype == 1) {
		//查询总代pid
		$inwhere = "select id from k_user_agent WHERE agent_type = 's_h'  AND site_id='" . SITEID . "' and is_demo='0' and agent_user ='" . $username . "'";
		$agent = $model->field("id")
		               ->where("agent_type = 'u_a'  AND site_id='" . SITEID . "' and is_demo='0' AND pid IN($inwhere)")
		               ->select();
		if (empty($agent)) {
			return $arr_agentid;
		}
		$agentids = array();
		foreach ($agent as $value) {
			$agentids[] = $value["id"];
		}
		$where = "agent_type = 'a_t' AND pid in(" . implode(",", $agentids) . ") AND site_id='" . SITEID . "' and is_demo='0'";
	} else if ($atype == 2) {
		//查询总代pid
		$agent = $model->field("id")
		               ->where("agent_type = 'u_a' AND site_id='" . SITEID . "' and is_demo='0' AND agent_user ='" . $username . "'")
		               ->find();
		if (empty($agent)) {
			return $arr_agentid;
		}
		$where = "agent_type = 'a_t' AND pid=" . $agent['id'] . " and site_id='" . SITEID . "' and is_demo='0'";
	} else if ($atype == 3) {
		//查询代理pid
		$where = "agent_type = 'a_t' and site_id='" . SITEID . "' and is_demo='0' and agent_user ='" . $username . "'";
	}
	$model->clearAtrr();
	$rows = $model->field("id,agent_user,agent_name,bankid,bankno")->where($where)->select();
	foreach ($rows as $value) {
		$arr_agentid[] = $value;
	}
	return $arr_agentid;
}