<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Kjtpay_model extends Online_api_model {
	function __construct() {
		parent::__construct();
		$this->init_db();
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key,$vircarddoin){
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$ServerUrl = 'http://'.$url.'/index.php/pay/kjtpay_callback';//商户后台通知地址
			$form_url = 'https://mag.kjtpay.com/mag/gateway/receiveOrder.do';//第三方地址
			$data = array();
			$data['req_url'] = $req_url;
			$data['form_url'] = $form_url;
			$mo=$s_amount;//金额
			$key = $pay_key;//秘钥
			$vircarddoin = $vircarddoin;
			$service = "create_instant_trade";//接口名称
			$version = "1.0";//版本
			$partner_id = $pay_id;//商户号
			$_input_charset = "UTF-8";
			$sign_type = "MD5";//加密方式
			$return_url = $ServerUrl;//回调地址
			$request_no = $order_num;//订单号
			$trade_list=$request_no."~shopname~".$mo."~1~".$mo."~~".$vircarddoin."~1~instant~2~~~~~".date('YmdHis')."~".$ServerUrl;//交易列表
			$buyer_id = "anonymous"; //买家ID  固定值
			$buyer_id_type = 1; //买家ID类型 固定值
			$pay_method="online_bank^".$mo."^".$bank.",C,GC";//支付方式
			$go_cashier = "Y";//是否跳转银行收银台
			$str="_input_charset=".$_input_charset."&buyer_id=".$buyer_id."&buyer_id_type=".$buyer_id_type."&go_cashier=".$go_cashier."&partner_id=".$partner_id."&pay_method=".$pay_method."&request_no=".$request_no."&return_url=".$return_url."&service=".$service."&trade_list=".$trade_list."&version=".$version;
			$sign=md5($str.$key);
			$data['service'] = $service;
			$data['version'] = $version;
			$data['partner_id'] = $partner_id;
			$data['_input_charset'] = $_input_charset;
			$data['sign_type'] = $sign_type;
			$data['sign'] = $sign;
			$data['return_url'] = $return_url;
			$data['request_no'] = $request_no;
			$data['trade_list'] = $trade_list;
			$data['buyer_id'] = $buyer_id;
			$data['buyer_id_type'] = $buyer_id_type;
			$data['go_cashier'] = $go_cashier;
			$data['pay_method'] = $pay_method;
			return $data;
	}
}