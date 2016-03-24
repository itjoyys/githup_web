<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Remittance_model extends Online_api_model {
	function __construct() {
		parent::__construct();
		$this->init_db();
		$this->load->library('payapi/Helper');
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key,$username,$TradeDate){
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$ServerUrl = 'http://'.$url.'/index.php/pay/remittance_callback';//商户后台通知地址
			$form_url = 'http://pay.41.cn/gateway';//第三方地址
			$data=array();
			$Helper = new Helper();
			$paydata = array();
			$paydata['input_charset'] = "UTF-8";
			$paydata['notify_url'] = $ServerUrl;
			$paydata['return_url'] = $ServerUrl;
			$paydata['pay_type'] = "1";
			$paydata['bank_code'] = $bank;
			$paydata['merchant_code'] = $pay_id;
			$paydata['order_no'] = $order_num;
			$paydata['order_amount'] = $s_amount;
			$paydata['order_time'] = $TradeDate;
			$paydata['req_referer'] = $url;
			$paydata['customer_ip'] = '127.0.0.1';
			$paydata['return_params'] = $username;
			$sign = $Helper->sign($paydata,$pay_key);
			$paydata['sign'] = $sign;
			$paydata['product_name'] = '';
			$paydata['product_num'] = '';
			$paydata['customer_phone'] = '';
			$paydata['receive_address'] = '';
			$paydata['req_url'] = $req_url;
			$paydata['form_url'] = $form_url;
			return $paydata;
	}
}