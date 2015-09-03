<?php 
include_once("../../../include/config.php");
include_once("../../../common/login_check.php");
include_once("../../../class/user.php");
//版权查询
$copy_right = user::copy_right(SITEID,INDEX_ID);

$userinfo=user::getinfo($_SESSION['uid']);

$user_record=M('k_user_bank_in_record',$db_config)->field('id')->where("uid='".$_SESSION["uid"]."' and site_id='".SITEID."' and make_sure = '1'")->find();

$level_des = M('k_user_level',$db_config)->field("RMB_pay_set")->where("id = '".$userinfo['level_id']."'")->find();

//读取线上入款设定
$data_1 = M('k_cash_config',$db_config)->join(" join k_cash_config_ol on k_cash_config_ol.cash_id = k_cash_config.id")->field("k_cash_config_ol.*")->where("k_cash_config.id = '".$level_des['RMB_pay_set']."'")->find();

if(empty($userinfo['pay_num'])){
  echo "<script>alert('请填写出款银行卡资料。');";
  echo "self.location.href='hk_money_1.php'</script>";
}
$order_num=substr(date("YmdHis"),2).mt_rand(100000,999999);
$uid=intval($_SESSION["uid"]);

include("data.html");
?>
