<?php 
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

$data['deposit_num']=$_POST['OrderMoney'];//金额

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
$data['username']=$_POST['Username'];
$data['order_num']=$_POST['TransID'];
// $data['menber_bank_num']=$_POST['bank'];//会员银行账户
$data['deposit_money']=$_POST['OrderMoney']+$data['other_num']+$data['favourable_num'];//总金额
//$data['deposit_num']=$_POST['OrderMoney'];//金额
$data['in_name']=$userinfo['pay_name'];
$data['log_time']=date('Y-m-d H:i:s');
$data['in_date']=date('Y-m-d H:i:s');
$data['in_info']=$userinfo['pay_name'].",".date("Y-m-d H:i:s").","."第三方支付";
$data['pay_id']=$_SESSION['payid'];
$data['paytype']=$_SESSION['paytype'];
$info=$in->add($data);
if($info){
}else{
  echo '<script>alert("支付失败，请稍后重试！")</script>';exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>充值接口-提交信息处理</title>

<?php
if(!empty($_POST['pay_id'])){

    $result=M('pay_set',$db_config)->where("id=$_POST[pay_id]")->find();
    if($result){
        $_POST['MemberID']=$result['pay_id'];
        $MemberID=$Mer_code;//$_POST['MemberID'];//商户号
        $TransID=$_POST['TransID'];//流水号
        $PayID=$_POST['PayID'];//支付方式
        $TradeDate=$_POST['TradeDate'];//交易时间
        $OrderMoney=$_POST['OrderMoney']*100;//订单金额
        $ProductName=$_POST['ProductName'];//产品名称
        $Amount=$_POST['Amount'];//商品数量
        $Username=$_POST['Username'];//支付用户名
        $AdditionalInfo=$_POST['AdditionalInfo'];//订单附加消息
        $PageUrl=$ServerUrl;//"http://".$result['f_url']."/baofoo_callback.php";//通知商户页面端地址
        $ReturnUrl=$ServerUrl;//"http://".$result['f_url']."/baofoo_callback.php";//服务器底层通知地址
        $NoticeType=$_POST['NoticeType'];//通知类型 
        $Md5key=$Mer_key;//$result['pay_key'];//md5密钥（KEY）
        $MARK = "|";
        //MD5签名格式
        $Signature=md5($MemberID.$MARK.$PayID.$MARK.$TradeDate.$MARK.$TransID.$MARK.$OrderMoney.$MARK.$PageUrl.$MARK.$ReturnUrl.$MARK.$NoticeType.$MARK.$Md5key);
        $payUrl=$form_url;//"http://".$result['pay_domain'];//借贷混合
        $TerminalID = $terminalid;//$result['terminalid']; 
        $InterfaceVersion = "4.0";
        $KeyType = "1";

        $_SESSION['OrderMoney']=$OrderMoney; //设置提交金额的Session
        $_SESSION['pay_id']=$result['pay_id'];//交易时使用的支付平台id
        //此处加入判断，如果前面出错了跳转到其他地方而不要进行提交
    }else{
        echo '<script>alert("系统错误！请联系客服！")</script>';
        exit;
    }
        
}
?>
</head>

<body onload="document.form1.submit()">
<form id="form1" name="form1" method="post" action="<?php echo $req_url; ?>">
        <input type='hidden' name='MemberID' value="<?php echo $MemberID; ?>" />
		<input type='hidden' name='TerminalID' value="<?php echo $TerminalID; ?>"/>
		<input type='hidden' name='InterfaceVersion' value="<?php echo $InterfaceVersion; ?>"/>
		<input type='hidden' name='KeyType' value="<?php echo $KeyType; ?>"/>
        <input type='hidden' name='PayID' value="<?php echo $PayID; ?>" />
        <input type='hidden' name='TradeDate' value="<?php echo $TradeDate; ?>" />
        <input type='hidden' name='TransID' value="<?php echo $TransID; ?>" />
        <input type='hidden' name='OrderMoney' value="<?php echo $OrderMoney; ?>" />
        <input type='hidden' name='ProductName' value="<?php echo $ProductName; ?>" />
        <input type='hidden' name='Amount' value="<?php echo $Amount; ?>" />
        <input type='hidden' name='Username' value="<?php echo $Username; ?>" />
        <input type='hidden' name='AdditionalInfo' value="<?php echo $AdditionalInfo; ?>" />
        <input type='hidden' name='PageUrl' value="<?php echo $PageUrl; ?>" />
        <input type='hidden' name='ReturnUrl' value="<?php echo $ReturnUrl; ?>" />
        <input type='hidden' name='Signature' value="<?php echo $Signature; ?>" />
        <input type='hidden' name='NoticeType' value="<?php echo $NoticeType; ?>" />
        <input type='hidden' name='form_url' value="<?php echo $form_url; ?>" />
</form>
</body>
</html>
