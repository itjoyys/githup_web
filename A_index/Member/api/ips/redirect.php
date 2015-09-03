<?php
header("Content-type:text/html; charset=utf-8"); 
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

$data['deposit_num']=$_POST['Amount'];//金额

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
$data['username']=$_POST['username'];
$data['order_num']=$_POST['order_num'];
// $data['menber_bank_num']=$_POST['bank'];//会员银行账户
$data['deposit_money']=$_POST['Amount']+$data['other_num']+$data['favourable_num'];//总金额
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


//提交地址
$form_url = $form_url; //正式


//商户号
$Mer_code = $Mer_code;

//商户证书：登陆http://merchant.ips.com.cn/商户后台下载的商户证书内容
$Mer_key = $Mer_key;

//商户订单编号
$Billno = $_POST['Billno'];

//订单金额(保留2位小数)
$Amount = number_format($_POST['Amount'], 2, '.', '');

//订单日期
$Date = date('Ymd');

//币种
$Currency_Type = 'RMB';

//支付卡种
$Gateway_Type = 01;

//语言
$Lang = 'GB';

//支付结果成功返回的商户URL
$Merchanturl = $ServerUrl;

//支付结果失败返回的商户URL
$FailUrl = $ServerUrl;

//支付结果错误返回的商户URL
$ErrorUrl = $ServerUrl;

//商户数据包
$Attach = '';

//显示金额
$DispAmount = $_POST['Amount'];

//订单支付接口加密方式
$OrderEncodeType = 5;

//交易返回接口加密方式 
$RetEncodeType = 17;

//返回方式
$Rettype = 1;

//Server to Server 返回页面URL
$ServerUrl = $ServerUrl;
//OrderEncodeType设置为5，且在订单支付接口的Signmd5字段中存放MD5摘要认证信息。
//交易提交接口MD5摘要认证的明文按照指定参数名与值的内容连接起来，将证书同时拼接到参数字符串尾部进行md5加密之后再转换成小写，明文信息如下：//例:(billno000001000123currencytypeRMBamount13.45date20031205orderencodetype5GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ)
//订单支付接口的Md5摘要，原文=订单号+金额+日期+支付币种+商户证书 
$orge = 'billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key ;
//echo '明文:'.$orge ;
//$SignMD5 = md5('billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key);
$SignMD5 = md5($orge) ;
//echo '密文:'.$SignMD5 ;
//sleep(20);
?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form action="<?=$req_url?>" method="post" id="frm1">
      <input type="hidden" name="Mer_code" value="<?php echo $Mer_code ?>">
      <input type="hidden" name="Billno" value="<?php echo $Billno ?>">
      <input type="hidden" name="Amount" value="<?php echo $Amount ?>" >
      <input type="hidden" name="Date" value="<?php echo $Date ?>">
      <input type="hidden" name="Currency_Type" value="<?php echo $Currency_Type ?>">
      <input type="hidden" name="Gateway_Type" value="<?php echo $Gateway_Type ?>">
      <input type="hidden" name="Lang" value="<?php echo $Lang ?>">
      <input type="hidden" name="Merchanturl" value="<?php echo $Merchanturl ?>">
      <input type="hidden" name="FailUrl" value="<?php echo $FailUrl ?>">
      <input type="hidden" name="ErrorUrl" value="<?php echo $ErrorUrl ?>">
      <input type="hidden" name="Attach" value="<?php echo $Attach ?>">
      <input type="hidden" name="DispAmount" value="<?php echo $DispAmount ?>">
      <input type="hidden" name="OrderEncodeType" value="<?php echo $OrderEncodeType ?>">
      <input type="hidden" name="RetEncodeType" value="<?php echo $RetEncodeType ?>">
      <input type="hidden" name="Rettype" value="<?php echo $Rettype ?>">
      <input type="hidden" name="ServerUrl" value="<?php echo $ServerUrl ?>">
      <input type="hidden" name="SignMD5" value="<?php echo $SignMD5 ?>">
      <input type="hidden" name="form_url" value="<?php echo $form_url ?>">
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
