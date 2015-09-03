<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");

//p($_POST);
if($_POST["action"] == 'user'){
	$uid = $_POST["test_id"];
	$user = M('k_user_cash_record',$db_config);
    //会员入款信息
	$userBankin = admin::getUser_Bankin($uid);
    //会员出款
    $userBankout = admin::getUser_Bankout($uid);

	//最近3笔入款
	$data2 = $user->field("cash_num,cash_type")->where("uid = '".$uid."' and (cash_type = '10' or cash_type = '11' or cash_type = '12')")->order("cash_date desc")->limit("3")->select();

	//盈利
	$owen_money = intval($userBankin['money']) - intval($userBankout['money']);

	$value = array();
	$value['in_all_money']=$userBankin['money'];//入款总额
	$value['count_in']=$userBankin['num'];
	$value['out_all_money']=$userBankout['money'];
	$value['count_out']=$userBankout['num'];
	$value['owen_money']=$owen_money;
	$value['data1']=$data2['0'];
	$value['data2']=$data2['1'];
	$value['data3']=$data2['2'];
	echo json_encode($value);

}else if($_POST["action"] == 'bank'){
	$uid = $_POST["test_id"];
	//$user = M('k_user_bank_in_record',$db_config);
	//$data = $user->join("left join k_bank on k_bank.id = k_user_bank_in_record.bid left join k_user on k_user.uid  = k_user_bank_in_record.uid")->field("k_user_bank_in_record.in_name,k_bank.*,k_user.mobile,k_user.reg_address")->where("k_user_bank_in_record.uid = '".$uid."'")->find();
	$data=M('k_user',$db_config)->where("uid = '".$uid."'")->find();
	$data['city']=explode("-",$data['pay_address']);
	$data['pay_card']=bank_type($data['pay_card']);
	
	//p($data);
	echo json_encode($data);

}











