<?php
/**
** 易宝配置文件
**/
if(!empty($_SESSION['payid'])){//支付前
	include_once("../../../include/config.php");
	$result = '';
	$result=M('pay_set',$db_config)->where('id='.$_SESSION['payid'])->find();

	if(!empty($result)){
		//商户id
		$p1_MerId                 = $result['pay_id'];

		//商户密钥
		$merchantKey              = $result['pay_key'];

		//跳转地址
		$reqURL_onLine            = 'http://'.$result['f_url'].'/yeepay_rep.php';
		
		//返回地址
		$ServerUrl                = 'http://'.$result['f_url'].'/yeepay_callback.php';

		//提交地址
		$form_url                 = 'https://'.$result['pay_domain'];

		//日志文件
		$logName	              = "YeePay_HTML.log";

	}
}elseif(!empty($_REQUEST['r6_Order'])){//支付完成 返回
	
	include_once("../../../include/filter.php");
	include_once("../../../include/private_config.php");
	include_once("../../../lib/class/model.class.php");
	$user    = '';
	$result  = '';
	$user=M('k_user_bank_in_record',$db_config)->field('pay_id')->where("order_num='$_REQUEST[r6_Order]'")->find();
	$result=M('pay_set',$db_config)->where("id=$user[pay_id]")->find();
	if(!empty($result)){
		//商户id
		$p1_MerId                = $result['pay_id'];

		//商户密钥
		$merchantKey             = $result['pay_key'];

		$logName	= "YeePay_HTML.log";

	}
}
?> 