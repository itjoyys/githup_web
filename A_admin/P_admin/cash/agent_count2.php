<?php
include_once "../../include/config.php";
include_once "../common/login_check.php";
include_once "../../class/Level.class.php";


$qishu = M('k_qishu', $db_config)->where("site_id = '" . SITEID . "' and is_delete = '0'")->order('id desc')->select();
$qs = $_POST['qs'];
if ($qs) {
	$username = $_POST['username'];
	$where = " 1 = 1";

	if (!empty($_POST['atype'])) {
		if (!empty($_POST['username'])) {
			if ($_POST['atype'] == 1) {
				$agent_list = M('k_user_agent', $db_config)->where("is_demo = '0' and is_delete = 0 and site_id = '" . SITEID . "' and agent_type <> 's_h'")->select();
				$a_map = " and agent_type = 's_h'";
				$a_map .= " and agent_user ='" . $username . "'";
				$agent_info = M('k_user_agent', $db_config)->where("is_demo = '0' and is_delete = 0 and site_id = '" . SITEID . "'" . $a_map)->find();
				if ($agent_info) {
					$agents_id = implode(',', Level::getChildsId($agent_list, $agent_info['id'], 'a_t'));
					$where .= " and k_user_agent.id in($agents_id)";
				} else {
					echo '<script>alert("账号不正确！")</script>';
				}
			} elseif ($_POST['atype'] == 2) {
				$agent_list = M('k_user_agent', $db_config)->where("is_demo = '0' and is_delete = 0 and site_id = '" . SITEID . "' and agent_type <> 'u_a'")->select();
				$a_map = " and agent_type = 'u_a'";
				$a_map .= " and agent_user ='" . $username . "'";
				$agent_info = M('k_user_agent', $db_config)->where("is_demo = '0' and is_delete = 0 and site_id = '" . SITEID . "'" . $a_map)->find();
				if ($agent_info) {
					$agents_id = implode(',', Level::getChildsId($agent_list, $agent_info['id'], 'a_t'));
					$where .= " and k_user_agent.id in($agents_id)";
				} else {
					echo '<script>alert("账号不正确！")</script>';
				}
			} elseif ($_POST['atype'] == 3) {
				$a_map = " and agent_user ='" . $username . "'";
				$agent_info = M('k_user_agent', $db_config)->where("is_demo = '0' and is_delete = 0 and site_id = '" . SITEID . "'" . $a_map)->find();
				if ($agent_info) {
					$where .= " and k_user_agent.id ='" . $agent_info['id'] . "'";
				} else {
					echo '<script>alert("账号不正确！")</script>';
				}
			}
		} else {
			echo '<script>alert("请输入账号！")</script>';
		}
	} else {
		if (!empty($_POST['username'])) {
			message('请选择账户类型！');
		}
		$where .= " and k_user_agent.agent_type= 'a_t' and k_user_agent.site_id='" . SITEID . "' and k_user_agent.is_demo='0'";
	}
	//列表读取
	$hire = M('k_hire_config', $db_config)->where("site_id='" . SITEID . "' and is_delete ='0'")->order('self_profit asc')->select();

	foreach ($qishu as $qshu) {
		if ($qs == $qshu['id']) {
			$qs = $qshu;
			$qs['start_date'] = $qshu['start_date'] . ' 00:00:00';
			$qs['end_date'] = $qshu['end_date'] . ' 23:59:59';
		}
	}
	/////////////////////
	$table = M('k_user_agent', $db_config);
	$user_agents = $table->join(' left join k_user on k_user_agent.id=k_user.agent_id ')->field(' k_user_agent.*,k_user.uid')->where($where)->order('id desc')->select();
	$agents = array();
	foreach ($user_agents as $agent) {
		if (!empty($agent['uid'])) {
			$userIds = $userIds . ',' . $agent['uid'];
		}
	}

	$userIds = trim($userIds, ',');

	if ($userIds) {
		$userIDs = M('k_user_cash_record', $db_config)->field('DISTINCT(uid)')->where("uid in (" . $userIds . ") and cash_type in (2,3,4) and cash_date>='" . $qs['start_date'] . "' and cash_date<='" . $qs['end_date'] . "' and cash_num>=" . $hire[0]['valid_money'])->order('id desc')->select();
		$uIds = array();
		if (!empty($userIDs)) {
			foreach ($userIDs as $userID) {
				$uIds[] = $userID['uid'];
			}
			$arrUids = $uIds;
			$uIds = implode(',', $uIds);

			//彩票
			$table = M('c_bet', $db_config);
			$Cbets = $table->field('win,uid,money')->where("uid in (" . $uIds . ") and js=1 and addtime>='" . $qs['start_date'] . "' and addtime<='" . $qs['end_date'] . "'")->select();
			$userCbets = array();
			foreach ($Cbets as $Cbet) {
				$userCbets[$Cbet['uid']]['win'] += $Cbet['win'];
				$userCbets[$Cbet['uid']]['money'] += $Cbet['money'];
			}
			//体彩
			$table = M('k_bet', $db_config);
			$Kbets = $table->field('uid,ben_add,win,bet_money,bet_win ')->where("uid in (" . $uIds . ") and status in (1,2,4,5) and bet_time>='" . $qs['start_date'] . "' and bet_time<='" . $qs['end_date'] . "'")->select();

			$userKbets = array();
			foreach ($Kbets as $kbet) {
				$userKbets[$kbet['uid']]['win'] += $kbet['win'];
				$userKbets[$kbet['uid']]['money'] += $kbet['bet_money'];

			}
			//体彩串关(将体育与串关加起来。组成体育的总数据)
			$Kbets_group = M('k_bet_cg_group', $db_config)->field('uid,win,bet_money ')->where("uid in (" . $uIds . ") and status in (1,2) and bet_time>='" . $qs['start_date'] . "' and bet_time<='" . $qs['end_date'] . "'")->select();
			foreach ($Kbets_group as $kbet_group) {
				$userKbets[$kbet_group['uid']]['win'] += $kbet_group['win'];
				$userKbets[$kbet_group['uid']]['money'] += $kbet_group['bet_money'];
			}

			//视讯

			//电子

			/*****************************费用开始****************************/
			//读取手续费设定
			$fee = M('k_fee_set', $db_config)->where("site_id='" . SITEID . "' and valid_date <='" . $qs['start_date'] . "' and is_delete='0'")->order('valid_date desc')->find();

			//后台入款费用计算
			$ht_cash = M('k_user_catm', $db_config)->field('uid,catm_money,catm_give,atm_give,updatetime')->where("uid in (" . $uIds . ") and catm_type in (1,2,3,4,5,6,7,8) and updatetime>='" . $qs['start_date'] . "' and updatetime<='" . $qs['end_date'] . "' and is_rebate=1")->select();
			//(1,2,3,4,5,6,7,8)
			//<option value="1">人工存入</option>
			//<option value="2">存款优惠</option>
			//<option value="3">负数额度归零</option>
			//<option value="4">取消出款</option>
			//<option value="5">其他</option>
			//<option value="6">体育投注余额</option>
			//<option value="7">返点优惠</option>
			//<option value="8">活动优惠</option>
			//
			foreach ($ht_cash as $cash) {
				$usercash[$cash['uid']]['now_incash'] += $cash['catm_money'] * $fee['in_fee'] * 0.01; //入款金额乘以入款手续费比例
				$usercash[$cash['uid']]['now_incash'] += $cash['catm_give'] + $cash['atm_give']; //优惠总和
			}

			//前台会员申请入款计算
			$qt_in_cash = M('k_user_cash_record', $db_config)->field('uid,cash_num,discount_num,cash_date')->where("uid in (" . $uIds . ") and cash_type='11' and cash_do_type='1' and cash_date>='" . $qs['start_date'] . "' and cash_date<='" . $qs['end_date'] . "'")->select();
			foreach ($qt_in_cash as $in_cash) {
				$usercash[$in_cash['uid']]['now_incash'] += $in_cash['cash_num'] * $fee['in_fee'] * 0.01; //入款金额乘以入款手续费比例
				$usercash[$in_cash['uid']]['now_incash'] += $in_cash['discount_num']; //优惠总和

				//入款上限判断
				if ($usercash[$in_cash['uid']]['now_incash'] > $fee['in_max_fee']) {
					$usercash[$in_cash['uid']]['now_incash'] = $fee['in_max_fee'];
				}
			}

			//前台会员申请出款的计算
			$qt_in_cash = M('k_user_cash_record', $db_config)->field('uid,cash_num,cash_date')->where("uid in (" . $uIds . ") and cash_type='12' and cash_do_type='2' and cash_date>='" . $qs['start_date'] . "' and cash_date<='" . $qs['end_date'] . "'")->select();
			foreach ($qt_in_cash as $in_cashs) {
				$usercash[$in_cashs['uid']]['now_outcash'] += $in_cashs['cash_num'] * $fee['out_fee'] * 0.01; //实际出款金额乘以出款手续费比例

				//出款上限判断
				if ($usercash[$in_cash['uid']]['now_outcash'] > $fee['out_max_fee']) {
					$usercash[$in_cash['uid']]['now_outcash'] = $fee['out_max_fee'];
				}
			}

			//前台注册赠送的优惠
			$table = M('k_user_cash_record', $db_config);
			$qt_zc_cash = $table->field('k_user_cash_record.uid,k_user_cash_record.discount_num,k_user_cash_record.cash_date,k_user.agent_id')->where("k_user_cash_record.cash_type='6' and k_user_cash_record.cash_do_type='1' and k_user_cash_record.cash_date>='" . $qs['start_date'] . "' and k_user_cash_record.cash_date<='" . $qs['end_date'] . "'")->join('left join k_user on k_user.uid=k_user_cash_record.uid')->select();
			$qt_zc_cashs = array();
			foreach ($qt_zc_cash as $k => $v) {
				if (empty($qt_zc_cash[$k]['agent_id'])) {
					unset($qt_zc_cash[$k]);
				}

			}

			foreach ($qt_zc_cash as $k => $v) {
				$qt_zc_cashs[$v['agent_id']]['discount_num'] += $v['discount_num'];
			}

			/***********************费用结束**************************/

			//拼接用户
			foreach ($user_agents as $agent) {
				$uid = '';
				if (!in_array($agent['uid'], $arrUids)) {
					unset($agent['uid']);
				} else {
					$uid = $agent['uid'];
					unset($agent['uid']);
				}

				if (!isset($agents[$agent['id']])) {
					$agents[$agent['id']] = $agent;
				}

				if (!empty($uid)) {
					$agents[$agent['id']]['uid'][] = $uid;
					$agents[$agent['id']]['cbet'] += $userCbets[$uid]['win'];
					$agents[$agent['id']]['cbet_money'] += $userCbets[$uid]['money'];
					$agents[$agent['id']]['kbet'] += $userKbets[$uid]['win'];
					$agents[$agent['id']]['kbet_money'] += $userKbets[$uid]['money'];
					$agents[$agent['id']]['nowcash'] += $usercash[$uid]['now_outcash'] + $usercash[$uid]['now_incash'];

				}
			}

			foreach ($agents as $key => $agent) {
				$agents[$key]['shixun'] = 0;
				$agents[$key]['ebet'] = 0;
				$agents[$key]['oldcash'] = 0; //前期行政费用
				$agents[$key]['nowcash'] = $agent['nowcash'] + $qt_zc_cashs[$key]['discount_num']; //当期行政费用
				$agents[$key]['summoney'] = $agent['cbet_money'] + $agent['kbet_money'] + $agents[$key]['shixun_money'] + $agents[$key]['ebet_money'];
				$agents[$key]['sumbet'] = $agent['cbet'] + $agent['kbet'] + $agents[$key]['shixun'] + $agents[$key]['ebet'];
				$agents[$key]['sumbet'] = $agents[$key]['summoney'] - $agents[$key]['sumbet'];
				$agents[$key]['ebet_money'] = 0;
				$agents[$key]['shixun_money'] = 0;

				$agents[$key]['retuCash'] = 0; //可获退款
				$agentIds = $agentIds . ',' . $agent['id'];

				$agents[$key]['cbet'] = $agents[$key]['cbet_money'] - $agents[$key]['cbet'] ? $agents[$key]['cbet_money'] - $agents[$key]['cbet'] : 0;
				$agents[$key]['kbet'] = $agents[$key]['kbet_money'] - $agents[$key]['kbet'] ? $agents[$key]['kbet_money'] - $agents[$key]['kbet'] : 0;
				$agents[$key]['cbet_money'] = $agents[$key]['cbet_money'] ? $agents[$key]['cbet_money'] : 0;
				$agents[$key]['kbet_money'] = $agents[$key]['kbet_money'] ? $agents[$key]['kbet_money'] : 0;
				$agents[$key]['old_bet'] = 0;
				$agents[$key]['old_cbet'] = 0;
				$agents[$key]['old_kbet'] = 0;
				$agents[$key]['old_vbet'] = 0;
				$agents[$key]['old_ebet'] = 0;
				$agents[$key]['old_validbet'] = 0;
				$agents[$key]['old_validcbet'] = 0;
				$agents[$key]['old_validkbet'] = 0;
				$agents[$key]['old_validvbet'] = 0;
				$agents[$key]['old_validebet'] = 0;

			}
			//p($agents);
			$agentIds = trim($agentIds, ',');
			$oldbets = M('k_user_agent_record', $db_config)->field('sum(now_bet) as oldbet,sum(now_cbet) as oldcbet,sum(now_kbet) as oldkbet,sum(now_vbet) as oldvbet,sum(now_ebet) as oldebet,sum(now_validbet) as oldvalidbet ,sum(now_validcbet) as oldvalidcbet,sum(now_validkbet) as oldvalidkbet,sum(now_validvbet) as oldvalidvbet,sum(now_validebet) as oldvalidebet,sum(nowcash) as oldcash,sum(valid_usernum) as old_usernum,agent_id')->where("agent_id in (" . $agentIds . ") and status in (0,4) and qishu_starttime<'" . $qs['start_date'] . "' and site_id='" . SITEID . "' and type=0")->group('agent_id')->select();
			if (!empty($oldbets)) {
				foreach ($oldbets as $oldbet) {
					$agents[$oldbet['agent_id']]['old_bet'] = $oldbet['oldbet'];
					$agents[$oldbet['agent_id']]['old_cbet'] = $oldbet['oldcbet'];
					$agents[$oldbet['agent_id']]['old_kbet'] = $oldbet['oldkbet'];
					$agents[$oldbet['agent_id']]['old_vbet'] = $oldbet['oldvbet'];
					$agents[$oldbet['agent_id']]['old_ebet'] = $oldbet['oldebet'];
					$agents[$oldbet['agent_id']]['old_validbet'] = $oldbet['oldvalidbet'];
					$agents[$oldbet['agent_id']]['old_validcbet'] = $oldbet['oldvalidcbet'];
					$agents[$oldbet['agent_id']]['old_validkbet'] = $oldbet['oldvalidkbet'];
					$agents[$oldbet['agent_id']]['old_validvbet'] = $oldbet['oldvalidvbet'];
					$agents[$oldbet['agent_id']]['old_validebet'] = $oldbet['oldvalidebet'];
					$agents[$oldbet['agent_id']]['oldcash'] = $oldbet['oldcash'];
					$agents[$oldbet['agent_id']]['old_usernum'] = $oldbet['old_usernum'];
				}
			}

			foreach ($agents as $key => $val) {
				$agents[$key]['usernum'] = sizeof($agents[$key]['uid']) + $val['old_usernum'];
				$flag = -1;
				$flagu = -1;
				foreach ($hire as $ke => $hi) {
					if (($agents[$key]['sumbet'] + $agents[$key]['old_bet']) > $hi['self_profit']) {
						$flag = $ke;
					}
					if ($agents[$key]['usernum'] >= $hi['effective_user']) {
						$flagu = $ke;
					}
				}
				if ($flag > -1 && $flagu > -1) {
					if ($flagu >= $flag) {
						$agents[$key]['hire'] = $hire[$flag];
					}
				} else {
					$agents[$key]['hire'] = array('sport_slay_rate' => 0, 'lottery_slay_rate' => 0, 'video_slay_rate' => 0, 'evideo_slay_rate' => 0,
						'sport_water_rate' => 0, 'lottery_water_rate' => 0, 'video_water_rate' => 0, 'evideo_water_rate' => 0,
					);
				}

				//可获退佣计算
				$cbet_you = $val['cbet_money'] + $val['old_validcbet']; //彩票有效投注

				$kbet_pai = $val['kbet'] + $val['old_kbet']; //体育总派彩
				$ebet_pai = $val['ebet'] + $val['old_ebet']; //电子总派彩
				$vbet_pai = $val['shixun'] + $val['old_vbet']; //视讯总派彩

				$shengyuqian = $cbet_you * $agents[$key]['hire']['lottery_water_rate'] * 0.01 - ($val['oldcash'] + $val['nowcash']); //剩余待扣金额

				//视讯
				if ($vbet_pai < 0) {
					$v_huo = $vbet_pai * $agents[$key]['hire']['video_slay_rate'] * 0.01;
				} else {
					$shengyuqian += $vbet_pai;
					if ($shengyuqian > 0) {
						$v_huo = $shengyuqian * $agents[$key]['hire']['video_slay_rate'] * 0.01;
					} else {
						$v_huo = 0;
					}
				}

				//电子
				if ($ebet_pai < 0) {
					$e_huo = $ebet_pai * $agents[$key]['hire']['evideo_slay_rate'] * 0.01;
				} else {
					$shengyuqian += $ebet_pai;
					if ($shengyuqian > 0) {
						$e_huo = $shengyuqian * $agents[$key]['hire']['evideo_slay_rate'] * 0.01;
					} else {
						$e_huo = 0;
					}
				}
				//p($kbet_pai);
				//体育
				if ($kbet_pai < 0) {
					$k_huo = $kbet_pai * $agents[$key]['hire']['sport_slay_rate'] * 0.01;
					if ($shengyuqian < 0) {
						$k_huo += $shengyuqian;
					}
				} else {
					$shengyuqian += $kbet_pai;
					if ($shengyuqian > 0) {
						$k_huo = $shengyuqian * $agents[$key]['hire']['sport_slay_rate'] * 0.01;
					} else {
						$k_huo = $kbet_pai * $agents[$key]['hire']['sport_slay_rate'] * 0.01;
						$k_huo += $shengyuqian - $kbet_pai;
					}
				}

				$agents[$key]['retuCash'] = $v_huo + $e_huo + $k_huo;

			}
			if (isset($_POST['kf_ty'])) {
				foreach ($agents as $key => $agent) {
					if ($_POST['kf_ty'] == 1 && !isset($agent['hire']['self_profit'])) {
						unset($agents[$key]);
					} elseif ($_POST['kf_ty'] == 0 && isset($agent['hire']['self_profit'])) {
						unset($agents[$key]);
					}
				}
			}

		} else {
			$agents = ARRAY();
			foreach ($user_agents as $agent) {
				unset($agent['uid']);
				$agent['uid'] = array();
				$agenttmp = array('shixun' => 0,
					'ebet' => 0,
					'oldcash' => 0,
					'nowcash' => 0,
					'sumbet' => 0,
					'ebet_money' => 0,
					'shixun_money' => 0,
					'summoney' => 0,
					'usernum' => 0,
					'cbet' => 0,
					'kbet' => 0,
					'cbet_money' => 0,
					'kbet_money' => 0,
					'hire' => Array
					(
						'sport_slay_rate' => 0,
						'lottery_slay_rate' => 0,
						'video_slay_rate' => 0,
						'evideo_slay_rate' => 0,
						'sport_water_rate' => 0,
						'lottery_water_rate' => 0,
						'video_water_rate' => 0,
						'evideo_water_rate' => 0,
					),
					'retuCash' => 0,
					'old_bet' => 0,
					'old_cbet' => 0,
					'old_kbet' => 0,
					'old_vbet' => 0,
					'old_ebet' => 0,
					'old_validbet' => 0,
					'old_validcbet' => 0,
					'old_validkbet' => 0,
					'old_validvbet' => 0,
					'old_validebet' => 0);
				$agent = array_merge($agent, $agenttmp);
				if (!isset($agents[$agent['id']])) {
					$agents[$agent['id']] = $agent;
				}
			}
		}
	}
	// p($agents);
	if (isset($_POST['savebtn'])) {
		foreach ($agents as $agent) {
			//p($agent['id']);
			$oldrecord = '';

			$oldrecord = M('k_user_agent_record', $db_config)->where("qishu_id=" . $qs['id'] . " and agent_id=" . $agent['id'])->find();
			$data_m['agent_id'] = $agent['id'];
			$data_m['agent_user'] = $agent['agent_user'];

			$data_m['agent_name'] = $agent['agent_name'];
			$data_m['old_bet'] = $agent['old_bet'];
			$data_m['old_cbet'] = $agent['old_cbet'];
			$data_m['old_kbet'] = $agent['old_kbet'];
			$data_m['old_vbet'] = $agent['old_vbet'];
			$data_m['old_ebet'] = $agent['old_ebet'];
			$data_m['now_bet'] = $agent['sumbet'];
			$data_m['now_cbet'] = $agent['cbet'];
			$data_m['now_kbet'] = $agent['kbet'];
			$data_m['now_vbet'] = $agent['shixun'];
			$data_m['now_ebet'] = $agent['ebet'];

			$data_m['valid_usernum'] = $agent['usernum'];
			$data_m['sport_slay_rate'] = $agent['hire']['sport_slay_rate'];

			$data_m['lottery_slay_rate'] = $agent['hire']['lottery_slay_rate'];
			$data_m['video_slay_rate'] = $agent['hire']['video_slay_rate'];

			$data_m['evideo_slay_rate'] = $agent['hire']['evideo_slay_rate'];
			$data_m['old_validbet'] = $agent['old_validbet'];
			$data_m['old_validcbet'] = $agent['old_validcbet'];
			$data_m['old_validkbet'] = $agent['old_validkbet'];
			$data_m['old_validvbet'] = $agent['old_validvbet'];
			$data_m['old_validebet'] = $agent['old_validebet'];

			$data_m['now_validbet'] = $agent['summoney'];
			$data_m['now_validcbet'] = $agent['cbet_money'];
			$data_m['now_validkbet'] = $agent['kbet_money'];
			$data_m['now_validvbet'] = $agent['shixun_money'];
			$data_m['now_validebet'] = $agent['ebet_money'];
			$data_m['sport_water_rate'] = $agent['hire']['sport_water_rate'];

			$data_m['lottery_water_rate'] = $agent['hire']['lottery_water_rate'];
			$data_m['video_water_rate'] = $agent['hire']['video_water_rate'];

			$data_m['evideo_water_rate'] = $agent['hire']['evideo_water_rate'];
			$data_m['qishu_id'] = $qs['id'];
			$data_m['qishu_starttime'] = $qs['start_date'];
			$data_m['qishu_endtime'] = $qs['end_date'];

			$data_m['oldcash'] = $agent['oldcash'];
			$data_m['nowcash'] = $agent['nowcash'];

			$data_m['retuCash'] = $agent['retuCash'];
			$data_m['site_id'] = SITEID;
			$data_m['bank'] = bank_type($agent['bankid']) . '<br>' . $agent['bankno'];
			if (empty($agent['hire']['video_water_rate'])) {
				$data_m['status'] = 4;
			} else {
				$data_m['status'] = 6;
			}
			$record = M('k_qishu', $db_config)->where("id=" . $qs['id'])->find();
			if ($record['state'] == 1) {
				message('该期退佣已停止！', 'agent_count.php');
			}
			if (empty($oldrecord)) {
				M('k_user_agent_record', $db_config)->add($data_m);
			} else {
				M('k_user_agent_record', $db_config)->where("qishu_id=" . $qs['id'] . " and agent_id=" . $agent['id'])->update($data_m);
			}
		}
		$do_log = $_SESSION['login_name'] . '存档了退佣统计';
		admin::insert_log($_SESSION['adminid'], $_SESSION['login_name'], $do_log);

	}
}
?>

<?php $title = "退佣统计";require "../common_html/header.php";?>
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
        <?php if (!empty($qishu)) {
	foreach ($qishu as $key => $val) {
		?>
        <option value="<?=$val['id']?>" <?php if ($qs['id'] == $val['id']) {?>selected = "selected"<?php }
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
    <?php if (!empty($agents)) {
	foreach ($agents as $key => $value) {
		if ($agents[$key]['retuCash'] == 0) {
			unset($agents[$key]);
		}
	}
	foreach ($agents as $key => $agent) {
		?>
       	<tr class="m_cen">
		  <td ><?=$agent['agent_user']?></td>
		  <td ><?=$agent['agent_name']?></td>
		  <td ><?=$agent['usernum']?></td>
          <td onmouseover="show_div(event,&#39;<?=$agent['old_kbet']?>&#39;,&#39;<?=$agent['old_cbet']?>&#39;,&#39;<?=$agent['old_vbet']?>&#39;,&#39;<?=$agent['old_ebet']?>&#39;)" onmouseout="hide_div()"><?=$agent['old_bet']?></td>
		  <td onmouseover="show_div(event,&#39;<?=$agent['kbet']?>&#39;,&#39;<?=$agent['cbet']?>&#39;,&#39;<?=$agent['shixun']?>&#39;,&#39;<?=$agent['ebet']?>&#39;)" onmouseout="hide_div()"><?=$agent['sumbet']?></td>
          <td ><?=$agent['hire']['video_slay_rate']?>%</td>
          <td ><?=$agent['hire']['evideo_slay_rate']?>%</td>
          <td ><?=$agent['hire']['sport_slay_rate']?>%</td>
          <td ><?=$agent['hire']['lottery_slay_rate']?>%</td>
		  <td onmouseover="show_div(event,&#39;<?=$agent['old_validkbet']?>&#39;,&#39;<?=$agent['old_validcbet']?>&#39;,&#39;<?=$agent['old_validvbet']?>&#39;,&#39;<?=$agent['old_validebet']?>&#39;)" onmouseout="hide_div()"><?=$agent['old_validbet']?></td>
          <td onmouseover="show_div(event,&#39;<?=$agent['kbet_money']?>&#39;,&#39;<?=$agent['cbet_money']?>&#39;,&#39;<?=$agent['shixun_money']?>&#39;,&#39;<?=$agent['ebet_money']?>&#39;)" onmouseout="hide_div()"><?=$agent['summoney']?></td>
          <td ><?=$agent['hire']['video_water_rate']?>%</td>
          <td ><?=$agent['hire']['evideo_water_rate']?>%</td>
           <td ><?=$agent['hire']['sport_water_rate']?>%</td>
          <td ><?=$agent['hire']['lottery_water_rate']?>%</td>
		  <td ><?=$agent['oldcash']?></td>
          <td ><?=number_format($agent['nowcash'], 2)?></td>
		  <td ><?=number_format($agent['retuCash'], 2)?></td>
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
<?php require "../common_html/footer.php";?>
