
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
}else{
  echo '<script>alert("支付失败，请稍后重试！")</script>';exit;
}
/* *
 *功能：即时到账交易接口接入页
 *版本：3.0
 *日期：2013-08-01
 *说明：
 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,
 *并非一定要使用该代码。该代码仅供学习和研究智付接口使用，仅为提供一个参考。
 **/
 ////////////////////////////////////请求参数//////////////////////////////////////

		//参数编码字符集(必选)
		$input_charset = "UTF-8";

		//接口版本(必选)固定值:V3.0
		$interface_version = "V3.0";

		//商家号（必填）
		$merchant_code = $m_id_g;

		//后台通知地址(必填)
		$notify_url = $ServerUrl;

		//定单金额（必填）
		$order_amount = $_POST['s_amount'];

		//商家定单号(必填)
		$order_no = $_POST['order_num'];

		//商家定单时间(必填)
		$order_time = date("Y-m-d H:i:s");//$_POST['order_time'];

		//签名方式(必填)
		$sign_type = "MD5";//$_POST['sign_type'];

		//商品编号(选填)
		$product_code = '';//$_POST['order_num'];

		//商品描述（选填）
		$product_desc = 'PK';//$_POST['product_desc'];

		//商品名称（必填）
		$product_name = "在线充值";//$_POST['product_name'];

		//端口数量(选填)
		$product_num = '1';//$_POST['product_num'];

		//页面跳转同步通知地址(选填)
		$return_url = '';//"http://".$rows_pay["pay_domain"]."/php/Dinpay/DinpayToMer.php";//返回地址$_POST['return_url'];

		//业务类型(必填)
		$service_type = "direct_pay";//$_POST['service_type'];

		//商品展示地址(选填)
		$show_url = '';//$_POST['show_url'];

		//公用业务扩展参数（选填）
		$extend_param = '';//$_POST['extend_param'];

		//公用业务回传参数（选填）
		$extra_return_param = '';//$_POST['S_Name'];//$_POST['extra_return_param'];

		// 直联通道代码（选填）
		$bank_code = $_POST['bank_name'];

		//客户端IP（选填）
		$client_ip = '';//$_POST['client_ip'];
	
	/* 注  new String(参数.getBytes("UTF-8"),"此页面编码格式"); 若为GBK编码 则替换UTF-8 为GBK*/
	/*if($product_name != "") {
	  $product_name = mb_convert_encoding($product_name, "UTF-8", "UTF-8");
	}
	if($product_desc != "") {
	  $product_desc = mb_convert_encoding($product_desc, "UTF-8", "UTF-8");
	}
	if($extend_param != "") {
	  $extend_param = mb_convert_encoding($extend_param, "UTF-8", "UTF-8");
	}
	if($extra_return_param != "") {
	  $extra_return_param = mb_convert_encoding($extra_return_param, "UTF-8", "UTF-8");
	}
	if($product_code != "") {
	  $product_code = mb_convert_encoding($product_code, "UTF-8", "UTF-8");
	}
	if($return_url != "") {
	  $return_url = mb_convert_encoding($return_url, "UTF-8", "UTF-8");
	}
	if($show_url != "") {
	  $show_url = mb_convert_encoding($show_url, "UTF-8", "UTF-8");
	}*/


	/*
	**
	 ** 签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，同时将商家支付密钥key放在最后参与签名，
	 ** 组成规则如下：
	 ** 参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
	 **/
	$signSrc= "";

	//组织订单信息
	if($bank_code != "") {
		$signSrc = $signSrc."bank_code=".$bank_code."&";
	}
	if($client_ip != "") {
                $signSrc = $signSrc."client_ip=".$client_ip."&";
	}
	if($extend_param != "") {
		$signSrc = $signSrc."extend_param=".$extend_param."&";
	}
	if($extra_return_param != "") {
		$signSrc = $signSrc."extra_return_param=".$extra_return_param."&";
	}
	if($input_charset != "") {
		$signSrc = $signSrc."input_charset=".$input_charset."&";
	}
	if($interface_version != "") {
		$signSrc = $signSrc."interface_version=".$interface_version."&";
	}
	if($merchant_code != "") {
		$signSrc = $signSrc."merchant_code=".$merchant_code."&";
	}
	if($notify_url != "") {
		$signSrc = $signSrc."notify_url=".$notify_url."&";
	}
	if($order_amount != "") {
		$signSrc = $signSrc."order_amount=".$order_amount."&";
	}
	if($order_no != "") {
		$signSrc = $signSrc."order_no=".$order_no."&";
	}
	if($order_time != "") {
		$signSrc = $signSrc."order_time=".$order_time."&";
	}
	if($product_code != "") {
		$signSrc = $signSrc."product_code=".$product_code."&";
	}
	if($product_desc != "") {
		$signSrc = $signSrc."product_desc=".$product_desc."&";
	}
	if($product_name != "") {
		$signSrc = $signSrc."product_name=".$product_name."&";
	}
	if($product_num != "") {
		$signSrc = $signSrc."product_num=".$product_num."&";
	}
	if($return_url != "") {
		$signSrc = $signSrc."return_url=".$return_url."&";
	}
	if($service_type != "") {
		$signSrc = $signSrc."service_type=".$service_type."&";
	}
	if($show_url != "") {
		$signSrc = $signSrc."show_url=".$show_url."&";
	}
        //设置密钥
	$key = $key_g;
	$signSrc = $signSrc."key=".$key;

	$singInfo = $signSrc;
	//echo "singInfo=".$singInfo."<br>";

	//签名
	$sign = md5($singInfo);
	//echo "sign=".$sign."<br>";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad="document.dinpayForm.submit();">
正在跳转 ...
<form name="dinpayForm" method="post" action="<?=$m_okurl?>"><!-- 注意 非UTF-8编码的商家网站 此地址必须后接编码格式 -->
	<input type="hidden" name="sign" value="<? echo $sign?>" />
	<input type="hidden" name="merchant_code" value="<? echo $merchant_code?>" />
	<input type="hidden" name="bank_code" value="<? echo $bank_code?>"/>
	<input type="hidden" name="order_no" value="<? echo $order_no?>"/>
	<input type="hidden" name="order_amount" value="<? echo $order_amount?>"/>
	<input type="hidden" name="service_type" value="<? echo $service_type?>"/>
	<input type="hidden" name="input_charset" value="<? echo $input_charset?>"/>
	<input type="hidden" name="notify_url" value="<? echo $notify_url?>">
	<input type="hidden" name="interface_version" value="<? echo $interface_version?>"/>
	<input type="hidden" name="sign_type" value="<? echo $sign_type?>"/>
	<input type="hidden" name="order_time" value="<? echo $order_time?>"/>
	<input type="hidden" name="product_name" value="<? echo $product_name?>"/>
	<input Type="hidden" Name="client_ip" value="<? echo $client_ip?>"/>
	<input Type="hidden" Name="extend_param" value="<? echo $extend_param?>"/>
	<input Type="hidden" Name="extra_return_param" value="<? echo $extra_return_param?>"/>
	<input Type="hidden" Name="product_code" value="<? echo $product_code?>"/>
	<input Type="hidden" Name="product_desc" value="<? echo $product_desc?>"/>
	<input Type="hidden" Name="product_num" value="<? echo $product_num?>"/>
	<input Type="hidden" Name="return_url" value="<? echo $return_url?>"/>
	<input Type="hidden" Name="show_url" value="<? echo $show_url?>"/>
	<input Type="hidden" Name="form_url" value="<? echo $form_url?>"/>
	</form>
</body>
</html>