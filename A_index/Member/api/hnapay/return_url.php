<?php
include_once("../../../include/filter.php");
include_once("../../../include/private_config.php");
include_once("../../../lib/class/model.class.php");
include_once("../../../class/user.php");
include_once("config.php");

$user=M('k_user_bank_in_record',$db_config)->where("order_num='$_REQUEST[orderID]'")->find();
if($user['make_sure']==1){
	echo '<script>alert("支付成功");</script>';exit;
}

$uid=$user['uid'];
$userinfo=user::getinfo($uid);
$user_record=M('k_user_bank_in_record',$db_config)->field('id')->where("uid='".$user["uid"]."' and site_id='".SITEID."' and make_sure = '1'")->find();

$level_des = M('k_user_level',$db_config)->field("RMB_pay_set")->where("id = '".$userinfo['level_id']."'")->find();

//读取线上入款设定
//读取线上入款设定
$aud = M('k_cash_config_view',$db_config)
        ->where("id = '".$level_des['RMB_pay_set']."'")
        ->find();

$orderID = $_REQUEST["orderID"];
$resultCode = $_REQUEST["resultCode"];
$stateCode = $_REQUEST["stateCode"];
$orderAmount = $_REQUEST["orderAmount"];
$payAmount = $_REQUEST["payAmount"];
$acquiringTime = $_REQUEST["acquiringTime"];
$completeTime = $_REQUEST["completeTime"];
$orderNo = $_REQUEST["orderNo"];
$partnerID = $_REQUEST["partnerID"];
$remark = $_REQUEST["remark"];
$charset = $_REQUEST["charset"];
$signType = $_REQUEST["signType"];
$signMsg = $_REQUEST["signMsg"];

$src = "orderID=".$orderID
."&resultCode=".$resultCode
."&stateCode=".$stateCode
."&orderAmount=".$orderAmount
."&payAmount=".$payAmount
."&acquiringTime=".$acquiringTime
."&completeTime=".$completeTime
."&orderNo=".$orderNo
."&partnerID=".$partnerID
."&remark=".$remark
."&charset=".$charset
."&signType=".$signType;

	$pkey = $Mer_key;
	$src = $src."&pkey=".$pkey;

	if ($signMsg == md5($src))
	{

		$order_num=$_REQUEST['orderID'];
		$b = M('k_user_bank_in_record',$db_config);
		$userMoney = M('k_user',$db_config)->field("money,username")->where("uid = '".$uid."'")->find();

		//会员余额
		$iMoney=$b->field('deposit_money,order_num,favourable_num,other_num,deposit_num,do_time,id')->where("order_num = '".$order_num."' and site_id = '".SITEID."' and make_sure = '0'")->find();//会员存入总金额
		if($payAmount/100!=$iMoney['deposit_num']){
			echo '<script>alert("支付异常！请联系客服！");window.close();</script>';exit;
		}else{
			//支付金额匹配正确 更新开始
			$now_date = date('Y-m-d H:i:s');
			$Buser = M('k_user',$db_config);
			$Buser->begin();
			$data_u = array();
			$data_u['money'] = array('+',$iMoney['deposit_money']);
			try {
				//会员余额更新
	            $log_1 = $Buser->where("uid = '".$uid."'")
	                   ->update($data_u);
	                $data_bin=array();
		        $data_bin['make_sure'] = 1;
		        $data_bin['admin_user'] = $user['username'];
		        $data_bin['do_time'] = $now_date;
		        $log_2 = $Buser->setTable("k_user_bank_in_record")
		                 ->where("id = '".$iMoney['id']."'")
		                 ->update($data_bin);//更新入款状态
		     
			   // //现金系统写入记录
			   $unow_m = $Buser->setTable("k_user")
			             ->where("uid = '".$uid."'")
			             ->getField('money');//获取会员当前余额
			             
		       if(empty($unow_m)){
		       	 //未获取到或者之前余额为负数回滚
		       	  $Buser->rollback();
		       	  echo '<script>alert("入款失败,错误代码XS001");window.close();</script>';exit;
		       }
		       
		       $data_c=array();
		       $data_c['source_id'] = $log_2;
		       $data_c['site_id'] = SITEID;
		       $data_c['uid'] = $uid;
		       $data_c['username'] = $user['username'];
		       $data_c['cash_date'] = $now_date;
		       $data_c['cash_type'] = 10;//表示线上入款
		       $data_c['cash_do_type'] = 1;
		       $data_c['agent_id'] = $userinfo['agent_id'];
		       $data_c['cash_balance'] = $unow_m;//当前余额
		       $data_c['cash_num'] = $iMoney['deposit_num'];
		       $data_c['discount_num'] = $iMoney['favourable_num']+$iMoney['other_num'];//优惠总额
		       $data_c['remark'] = $iMoney['order_num'];//备注
		       $log_4 = $Buser->setTable("k_user_cash_record")
			          ->add($data_c);

			      //稽核
		       //更新上一个稽核的终止时间
		       $l_audit = M('k_user_audit',$db_config)
		              ->field("max(id) as maxid")
		              ->where("uid = '".$uid."' and type = '1' and site_id = '".SITEID."'")
		              ->find();
		       if ($l_audit['maxid']) {
		       	  //存在更新终止时间
		       	  $data_l = array();
		       	  $data_l['end_date'] = $now_date;
		       	  $log_5 = $Buser->setTable("k_user_audit")
		       	         ->where("id = '".$l_audit['maxid']."'")
		       	         ->update($data_l);
		       }
		       $data_a = array();
		       $data_a['source_type'] = 2;
		       $data_a['source_id'] = $log_2;
		       $data_a['uid'] = $uid;
		       $data_a['type'] = 1;
		       $data_a['is_ct'] = 1;//线上入款有常态稽核无综合稽核
		       $data_a['site_id'] = SITEID;
		       $data_a['deposit_money'] = $iMoney['deposit_num'];//存款金额没优惠
		       $data_a['username'] = $user['username'];
		       $data_a['begin_date'] = $now_date;
		       $data_a['relax_limit'] = 10;//放宽额度
		       $data_a['normalcy_code'] = $aud['line_ct_audit']*$iMoney['deposit_num']*0.01;//常态稽核
		       $data_a['atm_give'] = $iMoney['other_num'];//汇款优惠
		       $data_a['catm_give'] = $iMoney['favourable_num'];//存款优惠
		       $data_a['expenese_num'] = $aud['line_ct_xz_audit'];//行政费率
		       $log_6 = $Buser->setTable("k_user_audit")
		       	      ->add($data_a);

	          // 发送用户信息
	          $dataM=array();
			  $dataM['type'] = 3;//表示出入款类型
			  $dataM['site_id'] = SITEID;
			  $dataM['uid'] = $uid;
			  $dataM['level'] = 2;
			  $dataM['msg_title'] =  $user['username'].','."线上入款";
			  $dataM['msg_info'] = $user['username'].','."线上入款" . $iMoney['deposit_num'] .'元,其他優惠：'.$iMoney['other_num']. "元成功, 祝您游戏愉快！";
			  $log_7 =$Buser->setTable("k_user_msg")->add($dataM);
			   if($log_1 && $log_2 && $log_4 && $log_6 && $log_7){
				  $Buser->commit(); //事务提交
			      echo '<script>alert("支付成功");window.close();</script>';exit;
				}else{
				  $Buser->rollback(); //数据回滚
				  echo '<script>alert("确定失败,错误代码XS002");window.close();</script>';exit;
				}
			}catch(Exception $e){
				 $Buser->rollback(); //数据回滚
				 echo '<script>alert("确定失败,错误代码XS003);window.close();</script>';exit;
			} 
		}
	}else{
		echo "<script>alert('交易失败,请联系客服人员！111');window.close();</script>";exit;
	}

?>