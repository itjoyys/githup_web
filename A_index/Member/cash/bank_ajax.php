<?php
	include_once("../../include/config.php");
	include_once("../common/member_config.php");
	include_once("../../common/login_check.php");
	include_once("../../common/function.php");
	
	function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
	{
		static $recursive_counter = 0;
		if (++$recursive_counter > 1000) {
			die('possible deep recursion attack');
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				arrayRecursive($array[$key], $function, $apply_to_keys_also);
			} else {
				$array[$key] = $function($value);
			}
			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		$recursive_counter--;
	}
	function JSON($array) {
		arrayRecursive($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
	}

	if(!empty($_POST['action']) && $_POST['action'] == 'add_form')
	{
		$json_data;
		$json_data['deposit_num']=$_POST['deposit_num'];//存入金额：
		$json_data['now_date']=$_POST['now_date'];//存入时间：
		$json_data['in_name']=$_POST['in_name'];//存款人姓名：
		if(!empty($_POST['bid'])){
			$bank=M("k_bank",$db_config)->field("card_address")->where("id='".$_POST['bid']."'")->find();
		}
		$json_data['bank']= $bank['card_address'];
		$map['site_id']=SITEID;
		if($_SESSION['uid']){
			$map['uid']=$_SESSION['uid'];
			$user=M('k_user',$db_config)->where($map)->find();
		}
		$agent=M('k_user_agent',$db_config)->where("id='".$user['agent_id']."' and site_id='".SITEID."'")->find();
		//查询是不是首次入款
		$user_record=M('k_user_bank_in_record',$db_config)->field('id')->where("uid='".$user['uid']."' and site_id='".SITEID."' and make_sure = '1'")->find();
		$level_des = M('k_user_level',$db_config)->field("RMB_pay_set,level_des")->where("id = '".$user['level_id']."'")->find();

		$data_1 = M('k_cash_config',$db_config)->join(" join k_cash_config_line on k_cash_config_line.cash_id = k_cash_config.id")->field("k_cash_config_line.*")->where("k_cash_config.id = '".$level_des['RMB_pay_set']."'")->find();

		if($_POST['deposit_num']>$data_1['line_catm_max']){
			echo JSON(array('statu'=>'1','infos'=>$data_1['line_catm_max']));exit();
		}
		if($_POST['deposit_num']<$data_1['line_catm_min']){
			echo JSON(array('statu'=>'2','infos'=>$data_1['line_catm_min']));exit();
		}
		//防止用户恶意提交表单
		$table=M('k_user_bank_in_record',$db_config);
		$result=$table->where('order_num="'.$_POST['order_num'].'"')->field('order_num')->find();
		if(!empty($result)){
			echo JSON(array('statu'=>'3'));exit();
		}


		if($_POST){
			$data['is_firsttime'] = $_POST['is_firsttime'];
			if($_POST['deposit_way']==2||$_POST['deposit_way']==3||$_POST['deposit_way']==4){
				$data['in_info'] = bank_type($_POST['bank_style']).','.$_POST['in_name'].','.$_POST['in_date'].','.in_type($_POST['deposit_way']).','.$_POST['bank_location1'].$_POST['bank_location2'].$_POST['bank_location4'];
			}else{
				$data['in_info'] = bank_type($_POST['bank_style']).','.$_POST['in_name'].','.$_POST['in_date'].','.in_type($_POST['deposit_way']);
			}
			$data['in_date'] = $_POST['in_date'];//存入时间
			$data['in_type'] = $_POST['deposit_way'];//存款方式
			$data['into_style'] = 1;
			$data['bank_style'] = $_POST['bank_style'];//会员使用的银行
			$data['in_atm_address'] = $_POST['bank_location1'].$_POST['bank_location2'].$_POST['bank_location4'];
			$data['in_name'] = $_POST['in_name'];//存款人姓名
			$data['log_time'] = date("Y-m-d H:i:s");//系统提交时间
			$data['deposit_num'] = $_POST['deposit_num'];//存款金额
			$data['order_num'] = $_POST['order_num'];//订单号
			$data['username'] = $user['username'];//会员账号
			$data['agent_user'] = $agent['agent_user'];//代理商账号
			$data['uid'] = $user['uid'];//会员id
			$data['level_id'] = $user['level_id'];//会员层级id
			$data['level_des'] = $level_des['level_des'];
			$data['site_id'] = SITEID;
			$data['bid'] = $_POST['bid'];

			$data['is_firsttime'] = empty($user_record)?1:0;
			$data['bid'] = $_POST['bid'];//存入银行卡id



			//优惠计算
			$discount = M('k_cash_config',$db_config)->join("join k_user_level on k_user_level.RMB_pay_set = k_cash_config.id left join k_cash_config_line on k_cash_config.id = k_cash_config_line.cash_id")->where("k_user_level.id = '".$user['level_id']."'")->find();
			//存款优惠判断
			if ($data['deposit_num'] >= $discount['line_discount_num']) {
				$data['favourable_num'] = (0.01*$data['deposit_num']*$discount['line_discount_per']>$discount['line_discount_max'])?$discount['line_discount_max']:(0.01*$data['deposit_num']*$discount['line_discount_per']);
			}

			//其它优惠判断
			if ($data['deposit_num'] >= $discount['line_other_discount_num']) {
				$data['other_num'] = (0.01*$data['deposit_num']*$discount['line_other_discount_per']>$discount['line_other_discount_max'])?$discount['line_other_discount_max']:(0.01*$data['deposit_num']*$discount['line_other_discount_per']);
			}

			$data['deposit_money'] = $_POST['deposit_num']+$data['other_num']+$data['favourable_num'];//存入总金额

			//添加
			$data['site_id'] = SITEID;
			$json_data['d_bank_location']=$_POST['bank_location1'].$_POST['bank_location2'].$_POST['bank_location4'];//所属分行
			// p($data);
			if (M('k_user_bank_in_record',$db_config)->add($data)) {
				$json_data['ok']=1;
				$json_data['bank_style']=bank_type($_POST['bank_style']);
				echo JSON($json_data);
			}else{
				$json_data['ok']=2;
				echo JSON($json_data);
			}
		}
	}
?>

