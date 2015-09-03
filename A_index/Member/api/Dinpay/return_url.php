<? header("content-Type: text/html; charset=utf-8");?>
<?php
include_once("../../../include/filter.php");
include_once("../../../include/private_config.php");
include_once("../../../lib/class/model.class.php");
include_once("../../../class/user.php");
include_once("config.php");

$user=M('k_user_bank_in_record',$db_config)->where("order_num='$_REQUEST[order_no]'")->find();
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
/* *
 功能：智付页面跳转同步通知页面
 版本：3.0
 日期：2013-08-01
 说明：
 以下代码仅为了方便商户安装接口而提供的样例具体说明以文档为准，商户可以根据自己网站的需要，按照技术文档编写。

 * */
	//获取智付GET过来反馈信息
//商号号
	$merchant_code	= $_REQUEST["merchant_code"];

	//通知类型
	$notify_type = $_REQUEST["notify_type"];

	//通知校验ID
	$notify_id = $_REQUEST["notify_id"];

	//接口版本
	$interface_version = $_REQUEST["interface_version"];

	//签名方式
	$sign_type = $_REQUEST["sign_type"];

	//签名
	$dinpaySign = $_REQUEST["sign"];

	//商家订单号
	$order_no = $_REQUEST["order_no"];

	//商家订单时间
	$order_time = $_REQUEST["order_time"];

	//商家订单金额
	$order_amount = $_REQUEST["order_amount"];

	//回传参数
	$extra_return_param = $_REQUEST["extra_return_param"];
	$s_name=$extra_return_param;

	//智付交易定单号
	$trade_no = $_REQUEST["trade_no"];

	//智付交易时间
	$trade_time = $_REQUEST["trade_time"];

	//交易状态 SUCCESS 成功  FAILED 失败
	$trade_status = $_REQUEST["trade_status"];

	//银行交易流水号
	$bank_seq_no = $_REQUEST["bank_seq_no"];


	/**
	 *签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，
	*同时将商家支付密钥key放在最后参与签名，组成规则如下：
	*参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
	**/
	//组织订单信息
	$signStr = "";
	if($bank_seq_no != "") {
		$signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
	}
	if($extra_return_param != "") {
	    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
	}
	$signStr = $signStr."interface_version=V3.0&";
	$signStr = $signStr."merchant_code=".$merchant_code."&";
	if($notify_id != "") {
	    $signStr = $signStr."notify_id=".$notify_id."&notify_type=offline_notify&";
	}

        $signStr = $signStr."order_amount=".$order_amount."&";
        $signStr = $signStr."order_no=".$order_no."&";
        $signStr = $signStr."order_time=".$order_time."&";
        $signStr = $signStr."trade_no=".$trade_no."&";
        $signStr = $signStr."trade_status=".$trade_status."&";

	if($trade_time != "") {
	     $signStr = $signStr."trade_time=".$trade_time."&";
	}
	$key=$key_g;   //"123456789a123456789_";
	$signStr = $signStr."key=".$key;
	$signInfo = $signStr;
	//将组装好的信息MD5签名
	$sign = md5($signInfo);
	//比较智付返回的签名串与商家这边组装的签名串是否一致
	if($dinpaySign==$sign) {
		//验签成功
		$order_num=$_REQUEST['order_no'];
		$b = M('k_user_bank_in_record',$db_config);
		$userMoney = M('k_user',$db_config)->field("money,username")->where("uid = '".$uid."'")->find();

		//会员余额
		$iMoney=$b->field('deposit_money,order_num,favourable_num,other_num,deposit_num,do_time,id')->where("order_num = '".$order_num."' and site_id = '".SITEID."' and make_sure = '0'")->find();//会员存入总金额
		if($order_amount!=$iMoney['deposit_num']){
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
	}else
        {
		//验签失败 业务结束
	}

?>
