<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Dinpay_model extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->init_db();
		
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key){
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$ServerUrl = 'http://'.$url.'/index.php/pay/dinpay_callback';//商户后台通知地址
			$form_url = 'https://pay.dinpay.com/gateway?input_charset=UTF-8 ';//第三方地址
			$data=array();
			$data['req_url'] = $req_url;
			$data['ServerUrl'] = $ServerUrl;
			$data['form_url'] = $form_url;
			$data['input_charset'] = "UTF-8";//参数编码字符集(必选)
			$data['interface_version'] = "V3.0";//接口版本(必选)固定值:V3.0
			$data['merchant_code'] = $pay_id;//商家号（必填）
			$data['notify_url'] = $ServerUrl;//后台通知地址(必填)
			$data['order_amount'] = $s_amount;//定单金额（必填）
			$data['order_no'] = $order_num;//商家定单号(必填)
			$data['order_time'] = date("Y-m-d H:i:s");//商家定单时间(必填)
			$data['sign_type'] = "MD5";//$_POST['sign_type'];//签名方式(必填)
			$data['product_code'] = '';//商品编号(选填)
			$data['product_desc'] = 'PK';//商品描述（选填）
			$data['product_name'] = "在线充值";//商品名称（必填）
			$data['product_num'] = '1';//端口数量(选填)
			$data['return_url'] = '';//页面跳转同步通知地址(选填)
			$data['service_type'] = "direct_pay";//业务类型(必填)
			$data['show_url'] = '';//商品展示地址(选填);
			$data['extend_param'] = '';//公用业务扩展参数（选填）;
			$data['extra_return_param'] = '';//公用业务回传参数（选填）
			$data['bank_code'] = $bank;// 直联通道代码（选填）
			$data['client_ip'] = '';//客户端IP（选填）;
			/*
			**
			** 签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，同时将商家支付密钥key放在最后参与签名，
			** 组成规则如下：
			** 参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
			**/
			$signSrc= "";

			//组织订单信息
			if($data['bank_code'] != "") {
			$signSrc = $signSrc."bank_code=".$data['bank_code']."&";
			}
			if($data['client_ip'] != "") {
			$signSrc = $signSrc."client_ip=".$data['client_ip']."&";
			}
			if($data['extend_param'] != "") {
			$signSrc = $signSrc."extend_param=".$data['extend_param']."&";
			}
			if($data['extra_return_param'] != "") {
			$signSrc = $signSrc."extra_return_param=".$data['extra_return_param']."&";
			}
			if($data['input_charset'] != "") {
			$signSrc = $signSrc."input_charset=".$data['input_charset']."&";
			}
			if($data['interface_version'] != "") {
			$signSrc = $signSrc."interface_version=".$data['interface_version']."&";
			}
			if($data['merchant_code'] != "") {
			$signSrc = $signSrc."merchant_code=".$data['merchant_code']."&";
			}
			if($data['notify_url'] != "") {
			$signSrc = $signSrc."notify_url=".$data['notify_url']."&";
			}
			if($data['order_amount'] != "") {
			$signSrc = $signSrc."order_amount=".$data['order_amount']."&";
			}
			if($data['order_no'] != "") {
			$signSrc = $signSrc."order_no=".$data['order_no']."&";
			}
			if($data['order_time'] != "") {
			$signSrc = $signSrc."order_time=".$data['order_time']."&";
			}
			if($data['product_code'] != "") {
			$signSrc = $signSrc."product_code=".$data['product_code']."&";
			}
			if($data['product_desc'] != "") {
			$signSrc = $signSrc."product_desc=".$data['product_desc']."&";
			}
			if($data['product_name'] != "") {
			$signSrc = $signSrc."product_name=".$data['product_name']."&";
			}
			if($data['product_num'] != "") {
			$signSrc = $signSrc."product_num=".$data['product_num']."&";
			}
			if($data['return_url'] != "") {
			$signSrc = $signSrc."return_url=".$data['return_url']."&";
			}
			if($data['service_type'] != "") {
			$signSrc = $signSrc."service_type=".$data['service_type']."&";
			}
			if($data['show_url'] != "") {
			$signSrc = $signSrc."show_url=".$data['show_url']."&";
			}
			//设置密钥
			$signSrc = $signSrc."key=".$pay_key;
			$singInfo = $signSrc;
			//签名
			$data['sign'] = md5($singInfo);

			$data['act'] = 'dinpay';

			$url = $req_url.'?';
			foreach ($data as $key => $value) {
				$url .= $key.'='.$value.'&';
			}
			$url = substr($url,0,strlen($url)-1); 
			return $url;
	}
}