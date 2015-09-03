<?php

/**
** 宝付配置文件
**/
if(!empty($_SESSION['payid'])){//支付前
	include_once("../../../include/config.php");
	$result = '';
	$result=M('pay_set',$db_config)->where('id='.$_SESSION['payid'])->find();

	if(!empty($result)){
		//商户id
		$Mer_code            = $result['pay_id'];

		//商户密钥
		$Mer_key             = $result['pay_key'];

		//跳转地址
		$req_url             = 'http://'.$result['f_url'].'/baofoo_rep.php';

		//返回地址
		$ServerUrl           = 'http://'.$result['f_url'].'/baofoo_callback.php';

		//提交地址
		$form_url            = 'http://'.$result['pay_domain'];

		//终端ID
		$terminalid          = $result['terminalid'];

	}
}elseif(!empty($_REQUEST['TransID'])){//支付完成 返回
	include_once("../../../include/filter.php");
	include_once("../../../include/private_config.php");
	include_once("../../../lib/class/model.class.php");
	$user    = '';
	$result  = '';
	$user=M('k_user_bank_in_record',$db_config)->field('pay_id')->where("order_num='$_REQUEST[TransID]'")->find();
	$result=M('pay_set',$db_config)->where("id=$user[pay_id]")->find();
	if(!empty($result)){
		//商户id
		$Mer_code            = $result['pay_id'];

		//商户密钥
		$Mer_key             = $result['pay_key'];
		
		//终端ID
		$terminalid          = $result['terminalid'];

	}
}
?> 