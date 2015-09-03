<?php
include_once "../../include/config.php";
include_once "../common/login_check.php";
//查询会员信息

// if (!empty($_POST['user_name'])) {
// 	$c = M('k_user',$db_config);
// 	$user = $c->join('left join k_user_games on k_user.uid = k_user_games.uid')->where("k_user.username = '".$_POST['user_name']."' and k_user.site_id = '".SITEID."' and is_delete = 0" )->find();
// }

//判断存款优惠前的checkbox 是否被选中
if (empty($_POST['ifSp'])) {
	unset($_POST['sp_other']);
}
//判断匯款優惠前的checkbox 是否被选中
if (empty($_POST['ifSp_other'])) {
	unset($_POST['spAmount']);
}
//判断綜合打碼量稽核前的checkbox 是否被选中
if (empty($_POST['isComplex'])) {
	unset($_POST['ComplexValue']);
}

$catmArray = array('人工存入', '存款優惠', '负数额度归零', '取消出款', '其他', '体育投注余额', '返点优惠', '活动优惠');
$atmArray = array('重复出款', '公司入款存误', '公司负数回冲', '手动申请出款', '扣除非法下注派彩', '放弃存款优惠', '其他', '体育投注余额');

$for_what = "12"; //用途类型12表示存入取出(k_user_cash_record.)(暂定为12)

//存款取款操作
if (!empty($_POST['userid'])) {
	$data['user_id'] = $_POST['userid'];
	$data['username'] = $_POST['username'];
	$dtype = $_POST['op_type']; //1存款2取款
	$c = M('k_user', $db_config);
	$user = $c->where("uid=" . $_POST['userid'])->find();
	if ($_POST['op_type'] == '1') {
		/******************存款********************************************************/
		//验证所有金额格式不能为负数和字母
		if ($_POST['ifSp'] == 1) {
			if (floatval($_POST['sp_other']) < 0 || !is_numeric($_POST['sp_other'])) {
				message("存款優惠金额格式不正确！", "catm.php");
			}
		}
		if ($_POST['ifSp_other'] == 1) {
			if (floatval($_POST['spAmount']) < 0 || !is_numeric($_POST['spAmount'])) {
				message("匯款優惠金额格式不正确！", "catm.php");
			}
		}
		if ($_POST['isComplex'] === 1) {
			if (floatval($_POST['ComplexValue']) < 0 || !is_numeric($_POST['ComplexValue'])) {
				message("綜合打碼量格式不正确！", "catm.php");
			}
		}

		if (!empty($_POST['amount'])) {
			$amount = $_POST['amount']; //存款金额
		} else {
			$amount = 0;
		}
		$deposit_money = $_POST['amount']; //存款总金额
		//存款优惠
		if ($_POST['ifSp'] == "1") {
			$deposit_money += $_POST['sp_other'];
			$give_count = $_POST['sp_other'];
			$data['catm_give'] = $_POST['sp_other']; //存款优惠
		}
		//汇款优惠
		if ($_POST['ifSp_other'] == '1') {
			$deposit_money += floatval($_POST['spAmount']);
			$data['atm_give'] = $_POST['spAmount']; //汇款优惠
			$give_count += $_POST['spAmount'];
		}

		//综合打码量
		if ($_POST['isComplex'] == '1') {
			$is_code_count = $_POST['isComplex'];
			$code_count = $_POST['ComplexValue']; //综合打码量
		} else {
			$is_code_count = 0;
			$code_count = 0;
		}
		//常态打码
		if (!empty($_POST['isnormality'])) {
			$isnormality = $_POST['isnormality'] * $_POST['amount'] * 0.01;
			$is_isnormality = 1;
		} else {
			$is_isnormality = 0;
			$isnormality = 0;
		}

		$catmT = $_POST['type_memo'] - 1;
		$remark = $catmArray[$catmT] . ',' . $_POST['c_remark'] . '操作者：' . $_SESSION['login_name']; //备注
		//统一时间
		$now_date = date('Y-m-d H:i:s');
		$kUser = M("k_user", $db_config);
		//获取当前会员余额
		$user_old_money = $kUser->where("uid = '".$data['user_id']."'")->getField("money");
        //获取稽核放宽额度
        $pay_id = M('k_user_level',$db_config)->where("id='".$user['level_id']."'")->getField('RMB_pay_set');
        $line_ct_fk_audit = M('k_cash_config_view',$db_config)->where("id = '".$pay_id."'")->getField("line_ct_fk_audit");
        //稽核状态查询
        $map_audit = array();
        $map_audit['site_id'] = SITEID;
        $map_audit['type'] = 1;
        $map_audit['uid'] = $_POST['userid'];
        $is_audit = M('k_user_audit',$db_config)->where($map_audit)->order("id desc")->find();

        //判断稽核是否锁定
        $map_lock['site_id'] = SITEID;
        $map_lock['uid'] = $_POST['userid'];
        $map_lock['out_status'] = array('in','(0,4)');
        $is_audit_lock = M('k_user_bank_out_record',$db_config)->where($map_lock)->find();

		$kUser->begin();
		try {
			/**
			 * 事务开始
			 */
			$data_u = array();
			$data_u['money'] = array('+', $deposit_money);
			$log_1 = $kUser->where("uid = '" . $_POST['userid'] . "'")
			               ->update($data_u); //更新会员余额
			//获取当前余额
			$unowmoney = M('k_user', $db_config)
				->where("uid = '" . $_POST['userid'] . "'")
				->getField('money');
			if (empty($unowmoney)) {
				//未获取到或者之前余额为负数回滚
				$kUser->rollback();
				message("入款失败,错误代码C001");
			}

			//启动清除稽核
			if (($user_old_money < $line_ct_fk_audit) && $is_audit && !$is_audit_lock) {
				$data_au = array();
				$data_au['type'] = 2;
			    $log_c1 = $kUser->setTable('k_user_audit')->update($data_au);  
			     //更新稽核最后一笔结束时间
		        $data_al = array();
		        $data_al['end_date'] = $now_date;
			    $log_c2 = $kUser->setTable('k_user_audit')
			              ->where("id = '".$is_audit['id']."'")
			              ->update($data_al);
			    //写入稽核日志
			    $data_auto = array();
		        $data_auto['update_date'] = $now_date;
		        $data_auto['uid'] = $_POST['userid'];
		        $data_auto['site_id'] = SITEID;
		        $data_auto['username'] = $_POST['username'];
		        $data_auto['content'] = '會員：'.$_POST['username'].' 餘額小於放寬額度 清除稽核點 ('.$now_date.'之前的稽核點已清除)';
			    $log_c3 = $kUser->setTable('k_user_audit_log')->add($data_auto);
			    
			}else{
				$log_c1 = 1;
				$log_c2 = 1;
				$log_c3 = 1;
			}

			//写入人工记录
			$data_ca = array();
			$data_ca['uid'] = $_POST['userid'];
			$data_ca['agent_id'] = $_POST['agent_id']; //代理商id
			$data_ca['site_id'] = SITEID;
			$data_ca['username'] = $_POST['username'];
			$data_ca['type'] = $_POST['op_type']; //存款与取款
			$data_ca['balance'] = $unowmoney;
			$data_ca['catm_money'] = $amount; //存款金额
			$data_ca['catm_give'] = $_POST['sp_other']; //存款优惠
			$data_ca['atm_give'] = $_POST['spAmount']; //汇款优惠
			$data_ca['is_code_count'] = $is_code_count; //是否有综合打码
			$data_ca['code_count'] = $code_count; //综合打码
			$data_ca['routine_check'] = $isnormality; //常态打码
			$data_ca['catm_type'] = $_POST['type_memo']; //存款项目
			$data_ca['updatetime'] = $now_date;
			$data_ca['is_rebate'] = $_POST['c_is_rebate']; //是否退佣
			$data_ca['remark'] = $remark; //备注
			$data_ca['do_admin_id'] = $_SESSION["login_name"]; //操作人
			$log_2 = $kUser->setTable('k_user_catm')->add($data_ca);

			//写入现金系统
			$dtype = 1;
			//人工存入现金记录那边对应的是人工存入 还有取消出款
			if ($_POST['type_memo'] == 1 or $_POST['type_memo'] == 4) {
				$dtype = 3;
			} elseif ($_POST['type_memo'] == 7) {
				$for_what = 9; //表示现金系统的优惠退水
				$cash_only = 1;
			} elseif ($_POST['type_memo'] == 2 || $_POST['type_memo'] == 8) {
				$cash_only = 1;
			}
			$data_c = array();
			$data_c['source_id'] = $log_2;
			$data_c['source_type'] = 3;
			$data_c['site_id'] = SITEID;
			$data_c['agent_id'] = $_POST['agent_id'];
			$data_c['uid'] = $_POST['userid'];
			$data_c['username'] = $_POST['username'];
			$data_c['cash_date'] = $now_date;
			
			$data_c['cash_type'] = $for_what;
			$data_c['cash_do_type'] = $dtype;
			$data_c['cash_balance'] = $unowmoney; //当前余额

			//负数额度归0 其他属于优惠
		    if ($_POST['type_memo'] == '3' || $_POST['type_memo'] == '5') {
		    	$data_c['cash_num'] = 0; //存款金额
		    	$cash_only = 1;
			    $data_c['discount_num'] = $amount+$_POST['sp_other'] + $_POST['spAmount']; //优惠总额
		    }else{
		    	$data_c['cash_num'] = $amount; //存款金额
			    $data_c['discount_num'] = $_POST['sp_other'] + $_POST['spAmount']; //优惠总额
		    }
			$data_c['cash_only'] = $cash_only; //表示活动优惠特殊
			$data_c['remark'] = $remark; //备注
			$log_4 = $kUser->setTable("k_user_cash_record")
			               ->add($data_c);

			//稽核写入
			//更新上一个稽核的终止时间
			$l_audit = M('k_user_audit', $db_config)
				->field("max(id) as maxid")
				->where("uid = '" . $_POST['userid'] . "' and type = '1' and site_id = '" . SITEID . "'")
				->find();
			if ($l_audit['maxid']) {
				//存在更新终止时间
				$data_l = array();
				$data_l['end_date'] = $now_date;
				$log_5 = $kUser->setTable("k_user_audit")
				               ->where("id = '" . $l_audit['maxid'] . "'")
				               ->update($data_l);
				if (empty($log_5)) {
					//失败回滚
					$kUser->rollback();
					message("入款失败,错误代码C002");
				}
			}
			$data_a = array();
			$data_a['site_id'] = SITEID;
			$data_a['uid'] = $_POST['userid'];
			$data_a['username'] = $_POST['username'];
			$data_a['begin_date'] = $now_date;
			$data_a['deposit_money'] = $amount; //存款金额
			$data_a['atm_give'] = $_POST['spAmount'];
			$data_a['catm_give'] = $_POST['sp_other'];
			$data_a['source_id'] = $log_2;
			$data_a['source_type'] = 1; //表示存入与取出
			$data_a['type'] = 1;
			$data_a['is_ct'] = $is_isnormality; //是否常态稽核
			$data_a['normalcy_code'] = $isnormality; //常态稽核
			$data_a['is_zh'] = $is_code_count; //是否综合稽核
			$data_a['type_code_all'] = $code_count; //综合稽核量
			$data_a['relax_limit'] = $_POST['relax_limit'];
			$data_a['expenese_num'] = $_POST['line_ct_xz_audit'];
			$log_6 = $kUser->setTable("k_user_audit")
			               ->add($data_a);

			//事务提交
			if ($log_1 && $log_2 && $log_4 && $log_6 && $log_c1 && $log_c2 && $log_c3) {
				$kUser->commit(); //事务提交
				$do_log = '会员' . $_POST['username'] . ':人工存款与取款操作';
				admin::insert_log($_SESSION['adminid'], $_SESSION['login_name'], $do_log);
				message("加款成功", "catm.php");
			} else {
				$kUser->rollback(); //数据回滚
				message("加款失败,错误代码C003", "catm.php");
			}

		} catch (Exception $e) {
			$kUser->rollback(); //数据回滚
			message("加款失败,错误代码C004", "catm.php");
		}

	} elseif ($_POST['op_type'] == '2') {
		/********************************取款*********************************/
		if ($_POST['amount'] <= 0 || !is_numeric($_POST['amount'])) {
			message("取款金额不正确！", "catm.php");
		}
		/********判断取款余额是否充足*********/
		if (floatval($user['money']) < floatval($_POST['amount'])) {
			message("余额不足", "catm.php");
		}
		$catmT = $_POST['type_memo'] - 1;
		$remark = $atmArray[$catmT] . ',' . $_POST['c_remark'] . '操作者：' . $_SESSION['login_name']; //备注
		//统一时间
		$now_date = date('Y-m-d H:i:s');
		$kUser = M("k_user", $db_config);
		$kUser->begin();
		try {
			/**
			 * 事务开始
			 */
			$data_o = array();
			$data_o['money'] = array('-', $_POST['amount']);
			$log_1 = $kUser->where("uid = '" . $_POST['userid'] . "'")
			               ->update($data_o); //更新会员余额
			//获取当前余额
			$unowmoney = M('k_user', $db_config)
				->where("uid = '" . $_POST['userid'] . "'")
				->getField('money');
			if (empty($unowmoney)) {
				//未获取到或者之前余额为负数回滚
				$kUser->rollback();
				message("取款失败,错误代码C011");
			}
			//写入人工记录
			$data_oa = array();
			$data_oa['uid'] = $_POST['userid'];
			$data_oa['agent_id'] = $_POST['agent_id']; //代理商id
			$data_oa['site_id'] = SITEID;
			$data_oa['username'] = $_POST['username'];
			$data_oa['type'] = $_POST['op_type']; //存款与取款
			$data_oa['balance'] = $unowmoney;
			$data_oa['catm_money'] = $_POST['amount']; //取款金额
			$data_oa['catm_type'] = $_POST['type_memo']; //存款项目
			$data_oa['updatetime'] = $now_date;
			$data_oa['remark'] = $remark; //备注
			$data_oa['do_admin_id'] = $_SESSION["login_name"]; //操作人
			//$data_oa['remark'] = $atmArray[$atmT] . ',' . $_POST['c_remark'] . '操作者：' . $_SESSION['login_name']; //备注
			$log_2 = $kUser->setTable('k_user_catm')->add($data_oa);

			if ($_POST['type_memo'] == '4') {
				//清除稽核点
				$log_state = M('k_user_audit', $db_config)
					->where("uid = '" . $_POST['userid'] . "' and type = '1'")
					->find();
				if ($log_state && !empty($_POST['delete_audit'])) {
					$l_audit = M('k_user_audit', $db_config)
						->field("max(id) as maxid")
						->where("uid = '" . $_POST['userid'] . "' and type = '1' and site_id = '" . SITEID . "'")
						->find();
					if ($l_audit['maxid']) {
						//存在更新终止时间
						$data_l = array();
						$data_l['end_date'] = $now_date;
						$log_3 = $kUser->setTable("k_user_audit")
						               ->where("id = '" . $l_audit['maxid'] . "'")
						               ->update($data_l);
						if (empty($log_3)) {
							//失败回滚
							$kUser->rollback();
							message("入款失败,错误代码C012");
						}
					}
					$datau = array();
					$datau['type'] = 2;
					$log_5 = $kUser->setTable('k_user_audit')
					               ->where("uid = '" . $_POST['userid'] . "' and type = '1'")
					               ->update($datau);
					if (empty($log_5)) {
						//失败回滚
						$kUser->rollback();
						message("入款失败,错误代码C015");
					}
				}
			}
			if ($_POST['type_memo'] != 4) {
				$do_type = 2;
			} else {
				$do_type = 4;
			}
			//写入现金系统
			$data_c = array();
			$data_c['source_id'] = $log_2;
			$data_c['source_type'] = 3;
			$data_c['site_id'] = SITEID;
			$data_c['uid'] = $_POST['userid'];
			$data_c['agent_id'] = $_POST['agent_id'];
			$data_c['username'] = $_POST['username'];
			$data_c['cash_date'] = $now_date;
			$data_c['cash_type'] = $for_what;
			$data_c['cash_do_type'] = $do_type;
			$data_c['cash_balance'] = $unowmoney; //当前余额
			$data_c['cash_num'] = $_POST['amount']; //取款金额
			$data_c['remark'] = $remark; //备注
			$log_4 = $kUser->setTable("k_user_cash_record")
			               ->add($data_c);

			if ($log_1 && $log_2 && $log_4) {
				$kUser->commit(); //事务提交
				$do_log = '会员' . $_POST['username'] . ':人工存款与取款操作';
				admin::insert_log($_SESSION['adminid'], $_SESSION['login_name'], $do_log);
				message("人工取出成功！", "catm.php");

			} else {
				$kUser->rollback(); //数据回滚
				message("人工取出失败！错误代码C013", "catm.php");
			}
		} catch (Exception $e) {
			$kUser->rollback(); //数据回滚
			message("人工取出失败！错误代码C014", "catm.php");
		}
	}
}
