
<?php 
/*ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(0);*/
 header("content-Type: text/html; charset=utf-8");
include_once("../../../include/config.php");
include_once("../../../class/user.php");
include_once("config.php");

//获取用户基本信息
$userinfo=user::getinfo($_SESSION["uid"]);


//查询是不是首次入款
$user_record=M('k_user_bank_in_record',$db_config)->field('id')->where("uid='".$_SESSION["uid"]."' and site_id='".SITEID."' and make_sure = '1'")->find();

$level_des = M('k_user_level',$db_config)->field("RMB_pay_set")->where("id = '".$userinfo['level_id']."'")->find();

//读取线上入款设定
$data_1 = M('k_cash_config',$db_config)->join(" join k_cash_config_ol on k_cash_config_ol.cash_id = k_cash_config.id")->field("k_cash_config_ol.*")->where("k_cash_config.id = '".$level_des['RMB_pay_set']."'")->find();

//$data_1['ol_deposit']   1   每次    2 首次 
$in=M('k_user_bank_in_record',$db_config);

$agent=M('k_user_agent',$db_config)->field("agent_user")->where("id = '".$userinfo['agent_id']."'")->find();

$user_record=$in->field("id")->where("uid = '".$_SESSION["uid"]."'")->find();

//是否首存
$data['is_firsttime'] = empty($user_record)?1:0;

$data['deposit_num']=$_POST['s_amount'];//金额

if($data_1['ol_deposit']==1){
    //优惠计算
    $discount = M('k_cash_config',$db_config)->join("join k_user_level on k_user_level.RMB_pay_set = k_cash_config.id left join k_cash_config_ol on k_cash_config.id = k_cash_config_ol.cash_id")->where("k_user_level.id = '".$userinfo['level_id']."'")->find();

    //存款优惠判断
    if ($data['deposit_num'] >= $discount['ol_discount_num']) {
       $data['favourable_num'] = (0.01*$data['deposit_num']*$discount['ol_discount_per']>$discount['ol_discount_max'])?$discount['ol_discount_max']:(0.01*$data['deposit_num']*$discount['ol_discount_per']);
    }

    //其它优惠判断
    if ($data['deposit_num'] >= $discount['ol_other_discount_num']) {
       $data['other_num'] = (0.01*$data['deposit_num']*$discount['ol_other_discount_per']>$discount['ol_other_discount_max'])?$discount['ol_other_discount_max']:(0.01*$data['deposit_num']*$discount['ol_other_discount_per']);
    }
}elseif($data_1['ol_deposit']==2){
    if($data['is_firsttime']==1){
        //优惠计算
        $discount = M('k_cash_config',$db_config)->join("join k_user_level on k_user_level.RMB_pay_set = k_cash_config.id left join k_cash_config_ol on k_cash_config.id = k_cash_config_ol.cash_id")->where("k_user_level.id = '".$userinfo['level_id']."'")->find();

        //存款优惠判断
        if ($data['deposit_num'] >= $discount['ol_discount_num']) {
           $data['favourable_num'] = (0.01*$data['deposit_num']*$discount['ol_discount_per']>$discount['ol_discount_max'])?$discount['ol_discount_max']:(0.01*$data['deposit_num']*$discount['ol_discount_per']);
        }

        //其它优惠判断
        if ($data['deposit_num'] >= $discount['ol_other_discount_num']) {
           $data['other_num'] = (0.01*$data['deposit_num']*$discount['ol_other_discount_per']>$discount['ol_other_discount_max'])?$discount['ol_other_discount_max']:(0.01*$data['deposit_num']*$discount['ol_other_discount_per']);
        }
    }else{
        $data['other_num']=$data['favourable_num']=0;
    }
}

$data['into_style']=2;//线上入款
$data['uid']=$_SESSION["uid"];
$data['site_id']=SITEID;
$data['level_id']=$userinfo['level_id'];
$data['agent_user']=$agent['agent_user'];//上级用户名
$data['username']=$userinfo['username'];
$data['order_num']=$_POST['order_num'];
// $data['menber_bank_num']=$_POST['bank'];//会员银行账户
$data['deposit_money']=$_POST['s_amount']+$data['other_num']+$data['favourable_num'];//总金额
//$data['deposit_num']=$_POST['OrderMoney'];//金额
$data['in_name']=$userinfo['pay_name'];
$data['log_time']=date('Y-m-d H:i:s');
$data['in_date']=date('Y-m-d H:i:s');
$data['in_info']=$userinfo['pay_name'].",".date("Y-m-d H:i:s").","."第三方支付";
$data['pay_id']=$_SESSION['payid'];
$data['paytype']=$_SESSION['paytype'];
$info=$in->add($data);

if($info){

	$MD5key = $key_g;		//MD5私钥
	$MerNo = $m_id_g;					//商户号
	$BillNo = $_POST['order_num'];		//[必填]订单号(商户自己产生：要求不重复)
	$Amount = $_POST['s_amount'];				//[必填]订单金额

	$ReturnURL = $ServerUrl; 			//[必填]返回数据给商户的地址(商户自己填写):::注意请在测试前将该地址告诉我方人员;否则测试通不过
	$Remark = "";  //[选填]升级。


	$md5src = $MerNo."&".$BillNo."&".$Amount."&".$ReturnURL."&".$MD5key;		//校验源字符串
	$SignInfo = strtoupper(md5($md5src));		//MD5检验结果


	$AdviceURL =$ServerUrl;   //[必填]支付完成后，后台接收支付结果，可用来更新数据库值
	$orderTime =date('YYYYMMDDHHMMSS');   //[必填]交易时间YYYYMMDDHHMMSS
	$defaultBankNumber =$_POST['bank_name'];   //[选填]银行代码s 

	//送货信息(方便维护，请尽量收集！如果没有以下信息提供，请传空值:'')
	//因为关系到风险问题和以后商户升级的需要，如果有相应或相似的内容的一定要收集，实在没有的才赋空值,谢谢。

	$products="products info";// '------------------物品信息

}else{
	echo "<script>alert('网络错误！请稍后重试！');</script>";exit;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad="document.dinpayForm.submit();">
正在跳转 ...
<form name="dinpayForm" method="post" action="<?=$m_okurl?>">
	<input type="hidden" name="MerNo" value="<?=$MerNo?>">
	<input type="hidden" name="BillNo" value="<?=$BillNo?>">
	<input type="hidden" name="Amount" value="<?=$Amount?>">
	<input type="hidden" name="ReturnURL" value="<?=$ReturnURL?>" >
	<input type="hidden" name="AdviceURL" value="<?=$AdviceURL?>" >
	<input type="hidden" name="orderTime" value="<?=$orderTime?>">
	<input type="hidden" name="defaultBankNumber" value="<?=$defaultBankNumber?>">
	<input type="hidden" name="SignInfo" value="<?=$SignInfo?>">
	<input type="hidden" name="Remark" value="<?=$Remark?>">
	<input type="hidden" name="products" value="<?=$products?>">
	<input Type="hidden" Name="form_url" value="<? echo $form_url?>"/>
	</form>
</body>
</html>