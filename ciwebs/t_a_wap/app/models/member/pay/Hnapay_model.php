<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Hnapay_model extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->init_db();
	}
		
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key,$vircarddoin){ 
		$data=array();
		$req_url = 'http://'.$url.'/index.php/pay/payfor'; //第三方地址
		$ServerUrl = 'http://'.$url.'/index.php/pay/hnapay_callback'; //商户后台通知地址
		$form_url = 'https://www.hnapay.com/website/pay.htm'; //跳转地址
		$version = '2.6';//版本 version
		$serialID = $order_num;//订单号
		$submitTime = date('YmdHis');//订单时间
		$failureTime = '';//date('YmdHis',strtotime("+1 year"));//订单失效时间
		$customerIP = '';//下单IP
		$jine = $s_amount*100;
		$orderDetails = $order_num.','.$jine.','.''.','.'PK'.','.'1';//订单号，订单金额*10，商户名称，商品名称，商品数量
		$totalAmount = $jine;//订单金额
		$type = '1000';//交易类型 1000为即时支付
		$buyerMarked = $vircarddoin;//新生账户号
		$payType = 'BANK_B2C';//付款方支付方式
		$orgCode = $bank;//银行
		$currencyCode = '';//人民币
		$directFlag = '1';//是否直连
		$borrowingMarked = '';//资金来源借贷标示
		$couponFlag = '';//优惠劵标示
		$platformID = '';//平台商ID
		$returnUrl = $ServerUrl;//返回地址
		$noticeUrl = $ServerUrl;//商户通知地址
		$partnerID = $pay_id;//商户ID
		$remark = '';//扩展字段
		$charset = "1";//编码格式
		$signType = '2';//加密方式
		$str = '&';
		$signMsg = 'version='.$version.$str.'serialID='.$serialID.$str.'submitTime='.$submitTime.$str.'failureTime='.$failureTime.$str.'customerIP='.$customerIP.$str.'orderDetails='.$orderDetails.$str.'totalAmount='.$totalAmount.$str.'type='.$type.$str.'buyerMarked='.$buyerMarked.$str.'payType='.$payType.$str.'orgCode='.$orgCode.$str.'currencyCode='.$currencyCode.$str.'directFlag='.$directFlag.$str.'borrowingMarked='.$borrowingMarked.$str.'couponFlag='.$couponFlag.$str.'platformID='.$platformID.$str.'returnUrl='.$returnUrl.$str.'noticeUrl='.$noticeUrl.$str.'partnerID='.$partnerID.$str.'remark='.$remark.$str.'charset='.$charset.$str.'signType='.$signType;//加密字符串
		$pkey = $pay_key;//商户秘钥
		$signMsg = $signMsg."&pkey=".$pkey;
		$signMsg =  md5($signMsg);
			//$data['req_url'] = $req_url;
			$data['ServerUrl'] = $ServerUrl;
			$data['form_url'] = $form_url;
			$data['version'] = '2.6';//版本 version
			$data['serialID'] = $order_num;//订单号
			$data['submitTime'] = date('YmdHis');//订单时间
			$data['failureTime'] ="";//date('YmdHis',strtotime("+1 year"));//订单失效时间
			$data['customerIP'] ="";//下单IP
			$jine = $s_amount*100;
			$orderDetails = $order_num.','.$jine.','.''.','.'PK'.','.'1';//订单号，订单金额*10，商户名称，商品名称，商品数量
			$data['orderDetails'] =$orderDetails;
			$totalAmount = $jine;//订单金额
			$data['totalAmount'] = $totalAmount;
			$data['type'] = '1000';//交易类型 1000为即时支付
			//$buyerMarked = $buyerMarked;//新生账户号
			$data['buyerMarked'] = $buyerMarked;//新生账户号
			$data['payType'] = 'BANK_B2C';//付款方支付方式
			$data['orgCode'] =  $bank;//银行
			$data['currencyCode'] = '';//人民币
			$data['directFlag'] = '1';//是否直连
			$data['borrowingMarked'] = '';//资金来源借贷标示
			$data['couponFlag'] = '';//优惠劵标示
			$data['platformID'] = '';//平台商ID
			$data['returnUrl'] = $ServerUrl;//返回地址
			$data['noticeUrl'] = $ServerUrl;//商户通知地址
			$data['partnerID'] = $pay_id;//商户ID
			$data['remark'] = '';//扩展字段
			$data['charset'] = "1";//编码格式
			$data['signType'] = '2';//加密方式
		    $data['pkey'] = $pay_key;//商户秘钥
			$data['signMsg'] = $signMsg;
			$data['act'] = 'hnapay';

			$url = $req_url.'?';
			foreach ($data as $key => $value) {
				$url .= $key.'='.$value.'&';
			}
			$url = substr($url,0,strlen($url)-1); 
			return $url;

	}
}