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
$data['order_num']=$_POST['Billno'];
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


$version = '2.6';//版本 version
$serialID = $_REQUEST['Billno'];//订单号
$submitTime = date('YmdHis');//订单时间
$failureTime = '';//date('YmdHis',strtotime("+1 year"));//订单失效时间
$customerIP = '';//下单IP
$jine = $_POST['Amount']*100;
$orderDetails = $_REQUEST['Billno'].','.$jine.','.''.','.'PK'.','.'1';//订单号，订单金额*10，商户名称，商品名称，商品数量
$totalAmount = $jine;//订单金额
$type = '1000';//交易类型 1000为即时支付
$buyerMarked = $vircarddoin;//新生账户号
$payType = 'BANK_B2C';//付款方支付方式
$orgCode = $_POST['bankCode'];//银行
$currencyCode = '';//人民币
$directFlag = '1';//是否直连
$borrowingMarked = '';//资金来源借贷标示
$couponFlag = '';//优惠劵标示
$platformID = '';//平台商ID
$returnUrl = $ServerUrl;//返回地址
$noticeUrl = $ServerUrl;//商户通知地址
$partnerID = $Mer_code;//商户ID
$remark = '';//扩展字段
$charset = "1";//编码格式
$signType = '2';//加密方式

$str = '&';
//version=2.6&serialID=1439960460124&submitTime=20150819130101&failureTime=20160819130101&customerIP=localhost[127.0.0.1]&orderDetails=604601241,100,上海易生,测试机票A,1&totalAmount=100&type=1000&buyerMarked=&payType=ALL&orgCode=&currencyCode=1&directFlag=0&borrowingMarked=0&couponFlag=1&platformID=&returnUrl=http://localhost/returnUrl.php&noticeUrl=http://localhost/noticeUrl.php&partnerID=10000000029&remark=扩展字段&charset=1&signType=2
$signMsg = 'version='.$version.$str.'serialID='.$serialID.$str.'submitTime='.$submitTime.$str.'failureTime='.$failureTime.$str.'customerIP='.$customerIP.$str.'orderDetails='.$orderDetails.$str.'totalAmount='.$totalAmount.$str.'type='.$type.$str.'buyerMarked='.$buyerMarked.$str.'payType='.$payType.$str.'orgCode='.$orgCode.$str.'currencyCode='.$currencyCode.$str.'directFlag='.$directFlag.$str.'borrowingMarked='.$borrowingMarked.$str.'couponFlag='.$couponFlag.$str.'platformID='.$platformID.$str.'returnUrl='.$returnUrl.$str.'noticeUrl='.$noticeUrl.$str.'partnerID='.$partnerID.$str.'remark='.$remark.$str.'charset='.$charset.$str.'signType='.$signType;//加密字符串
$pkey = $Mer_key;//商户秘钥

  $signMsg = $signMsg."&pkey=".$pkey;
  $signMsg =  md5($signMsg);

?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form action="<?=$req_url?>" method="post" id="frm1">
    <input type="hidden" name="version"  value="<?php echo $version;?>">  
    <input type="hidden" name="serialID"  value="<?php echo $serialID;?>">
    <input type="hidden" name="submitTime"  value="<?php echo $submitTime;?>">
    <input type="hidden" name="failureTime"  value="<?php echo $failureTime;?>">
    <input type="hidden" name="customerIP"  value="<?php echo $customerIP;?>">
    <input type="hidden" name="orderDetails"  value="<?php echo $orderDetails;?>">
    <input type="hidden" name="totalAmount"  value="<?php echo $totalAmount;?>">
    <input type="hidden" name="type"  value="<?php echo $type;?>">
    <input type="hidden" name="buyerMarked"  value="<?php echo $buyerMarked;?>">
    <input type="hidden" name="payType"  value="<?php echo $payType;?>">
    <input type="hidden" name="orgCode"  value="<?php echo $orgCode;?>">
    <input type="hidden" name="currencyCode"  value="<?php echo $currencyCode;?>">
    <input type="hidden" name="directFlag"  value="<?php echo $directFlag;?>">
    <input type="hidden" name="borrowingMarked"  value="<?php echo $borrowingMarked;?>">
    <input type="hidden" name="couponFlag"  value="<?php echo $couponFlag;?>">
    <input type="hidden" name="platformID"  value="<?php echo $platformID;?>">
    <input type="hidden" name="returnUrl"  value="<?php echo $returnUrl;?>">
    <input type="hidden" name="noticeUrl"  value="<?php echo $noticeUrl;?>">
    <input type="hidden" name="partnerID"  value="<?php echo $partnerID;?>">
    <input type="hidden" name="charset"  value="<?php echo $charset;?>">
    <input type="hidden" name="signType"  value="<?php echo $signType;?>">
    <input type="hidden" name="remark"  value="<?php echo $remark;?>">
    <input type="hidden" name="signMsg"   value="<?php echo $signMsg ?>">
    <input type="hidden" name="form_url" value="<?php echo $form_url ?>">
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
