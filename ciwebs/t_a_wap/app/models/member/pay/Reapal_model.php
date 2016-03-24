<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Reapal_model extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->init_db();
		$this->load->library('payapi/Rongpay_service');
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key,$vircarddoin){
			$ServerUrl = 'http://'.$url.'/index.php/pay/reapal_callback';//商户后台通知地址
			$form_url = 'https://epay.reapal.com/portal?';//第三方地址
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$notify_url = 'http://'.$url.'/index.php/pay/reapal_notifyback';
			$order_no = $order_num;// 请与贵网站订单系统中的唯一订单号匹配
			$title = '在线充值';//订单名称，显示在融宝支付收银台里的“商品名称”里
			$body = 'pk在线充值';// 订单描述、订单详细、订单备注，显示在融宝支付收银台里的“商品描述”里
			$total_fee = $s_amount;// 订单总金额，显示在融宝支付收银台里的“应付总额”里
			$buyer_email = "";// 默认买家融宝支付账号
			$defaultbank = $bank;// 网银代码
			if ($defaultbank == "NO") {
				$paymethod = "bankPay"; // 支付方式，默认网关
				$defaultbank = "";
			} else {
				$paymethod = "directPay"; // 支付方式，银行直连
				$defaultbank = $bank;
			}

			// ///////////////////////////////////////////////////////////////////////////////////////////////////
			$key = $pay_key;
			$sign_type = "MD5";
			$seller_email = $vircarddoin;
			$merchant_ID = $pay_id;

			// notify_url 交易过程中服务器通知的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
			//$notify_url = "http://".$result['f_url'] . "/index.php/pay/reapal_callback";

			//$return_url = "http://".$payconf['f_url'] . "/reapal_callback";
			// 构造要请求的参数数组，无需改动
			$parameter = array(
				"service" => "online_pay", // 接口名称，不需要修改
				"payment_type" => "1", // 交易类型，不需要修改
				// 获取配置文件(rongpay_config.php)中的值
				"merchant_ID" => $merchant_ID,
				"seller_email" => $seller_email,// 签约融宝支付账号或卖家收款融宝支付帐户
				"return_url" => $notify_url,
				"notify_url" => $notify_url,
				"charset" => "utf-8",
				// 从订单数据中动态获取到的必填参数
				"order_no" => $order_no,
				"title" => $title,
				"body" => $body,
				"total_fee" => $total_fee,
				// 扩展功能参数——银行直连
				"paymethod" => $paymethod,
				"defaultbank" => $defaultbank
			);
			// 构造请求函数
			$rongpay = new rongpay_service();
			$temp  =   $rongpay->rongpay_sign($parameter, $key, $sign_type);
			$parameter['sign'] = $temp;
			$parameter['req_url'] = $req_url;
			$parameter['form_url'] = $form_url;
			$parameter['act'] = "reapal";
			$parameter['sign_type'] = $sign_type;
			 $sHtmlText = $rongpay->BuildForm($req_url,$form_url,$temp,$sign_type);
			return $sHtmlText;
	}
}