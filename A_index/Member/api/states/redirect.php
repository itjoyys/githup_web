<?php
 header("content-Type: text/html; charset=utf-8");
include_once("../../../include/config.php");
include_once("../../../class/user.php");
include_once("config.php");
include_once("HttpClient.class.php");


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


$version = 2.1; //网关版本号         
$charset = 2;   //字符集 1:GBK,2:UTF-8 (不填则当成1处理)       
$language = 1;       //网关语言版本  1:ZH,2:EN  
$signType = 1;  //报文加密方式 1:MD5,2:SHA       
$tranCode = 8888; //交易代码         
$merchantID = $Mer_code;      //商户ID 

$merOrderNum = $_POST["Billno"];//订单号      
$tranAmt = $_POST["Amount"];   //交易金额       
$feeAmt = '';           //商户提取佣金金额
$currencyType = 156;  //币种   
$frontMerUrl = $ServerUrl;     //商户前台通知地址 
$backgroundMerUrl = $ServerUrl; //商户后台通知地址
$tranDateTime = date('YmdHis');    //交易时间 
$virCardNoIn = $vircarddoin;//'0000000002000002307'; //   国付宝账号
  
$tranIP = '127.0.0.1';//'127.0.0.1';
$isRepeatSubmit = 0;   //订单是否允许重复 0不允许 1允许（默认）
$goodsName = '';//商品名称        
$goodsDetail = '';//商品详情      
$buyerName = ''; //买家姓名        
$buyerContact = '';//买家联系方式     
$merRemark1 = '';//商户备用信息字段       
$merRemark2 = '';      //商户备用信息字段 
$bankCode = $_POST["bankCode"];         //银行代码
$userType =  1;//$_POST["userType"];         //用户类型
$gopayServerTime = '';//HttpClient::getGopayServerTime();
$Mer_key = $Mer_key;//秘钥


$signStr='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=[]gopayOutOrderId=[]tranIP=['.$tranIP.']respCode=[]gopayServerTime=['.$gopayServerTime.']VerficationCode=['.$Mer_key.']';
//VerficationCode是商户识别码为用户重要信息请妥善保存
//注意调试生产环境时需要修改这个值为生产参数


$signValue = md5($signStr);
//var_dump($virCardNoIn,$Mer_key);exit;
//echo $signStr;exit;


?>
<html>
  <head>
    <title>跳转......</title>
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>
    <form action="<?=$req_url?>" method="post" id="frm1">
      <input type="hidden" id="version" name="version" value="<?echo "$version"; ?>" size="50"/>
      <input type="hidden" id="charset" name="charset" value="<?echo "$charset"; ?>"  size="50"/>
      <input type="hidden" id="language" name="language" value="<?echo "$language"; ?>"  size="50"/>
      <input type="hidden" id="signType" name="signType" value="<?echo "$signType"; ?>"  size="50"/>
      <input type="hidden" id="tranCode" name="tranCode" value="<?echo "$tranCode"; ?>"  size="50"/>
      <input type="hidden" id="merchantID" name="merchantID" value="<?echo "$merchantID"; ?>"  size="50"/>
      <input type="hidden" id="merOrderNum" name="merOrderNum" value="<?echo "$merOrderNum"; ?>"  size="50" />
      <input type="hidden" id="tranAmt" name="tranAmt" value="<?echo "$tranAmt"; ?>"  size="50"/>
      <input type="hidden" id="feeAmt" name="feeAmt" value="<?echo "$feeAmt"; ?>"  size="50"/>
      <input type="hidden" id="currencyType" name="currencyType" value="<?echo "$currencyType"; ?>"  size="50"/>
      <input type="hidden"  id="frontMerUrl" name="frontMerUrl" value="<?echo "$frontMerUrl"; ?>"  size="50"/>
      <input type="hidden"  id="backgroundMerUrl" name="backgroundMerUrl" value="<?echo "$backgroundMerUrl"; ?>"  size="50"/>
      <input type="hidden"  id="tranDateTime" name="tranDateTime" value="<?echo "$tranDateTime"; ?>"  size="50"/>
      <input type="hidden"  id="virCardNoIn" name="virCardNoIn" value="<?echo "$virCardNoIn"; ?>"  size="50"/>
      <input type="hidden"  id="tranIP" name="tranIP" value="<?echo "$tranIP"; ?>"  size="50"/>
      <input type="hidden"  id="isRepeatSubmit" name="isRepeatSubmit" value="<?echo "$isRepeatSubmit"; ?>"  size="50"/>
      <input type="hidden"  id="goodsName" name="goodsName" value="<?echo "$goodsName"; ?>"  size="50"/>
      <input type="hidden"  id="goodsDetail" name="goodsDetail" value="<?echo "$goodsDetail"; ?>"  size="50"/>
      <input type="hidden"  id="buyerName" name="buyerName" value="<?echo "$buyerName"; ?>"  size="50"/>
      <input type="hidden"  id="buyerContact" name="buyerContact" value="<?echo "$buyerContact"; ?>"  size="50"/>
      <input type="hidden"  id="merRemark1" name="merRemark1" value="<?echo "$merRemark1"; ?>"  size="50"/>
      <input type="hidden"  id="merRemark2" name="merRemark2" value="<?echo "$merRemark2"; ?>"  size="50"/>
      <input type="hidden"  id="signValue" name="signValue" value="<?echo "$signValue"; ?>"  size="50"/>
      <input type="hidden"  id="bankCode" name="bankCode" value="<?echo "$bankCode"; ?>"  size="50"/>
      <input type="hidden"  id="userType" name="userType" value="<?echo "$userType"; ?>"  size="50"/>
    <input type="hidden"  id="gopayServerTime" name="gopayServerTime" value="<?echo "$gopayServerTime"; ?>"  size="50"/>
      <input type="hidden" name="form_url" value="<?php echo $form_url ?>">
    </form>
    <script language="javascript">
      document.getElementById("frm1").submit();
    </script>
  </body>
</html>
