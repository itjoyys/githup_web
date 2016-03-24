<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Ips_model extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->init_db();
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key){
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$ServerUrl = 'http://'.$url.'/index.php/pay/ips_callback';//商户后台通知地址
			$form_url = 'https://pay.ips.com.cn/ipayment.aspx';//第三方地址
			$data=array();
			//$data['req_url'] = $req_url;
			$data['ServerUrl'] = $ServerUrl;
			$data['form_url'] = $form_url;
			
			
			$Mer_code = $pay_id;//商户号
			$Mer_key = $pay_key;//商户证书：登陆http://merchant.ips.com.cn/商户后台下载的商户证书内容
			$Billno = $order_num;//商户订单编号
			$Amount = number_format($s_amount, 2, '.', '');//订单金额(保留2位小数)
			$Date = date('Ymd');//订单日期
			$Currency_Type = 'RMB';//币种
			$Gateway_Type = 01;//支付卡种
			$Lang = 'GB';//语言
			$Merchanturl = $ServerUrl;//支付结果成功返回的商户URL
			$FailUrl = $ServerUrl;//支付结果失败返回的商户URL
			$ErrorUrl = $ServerUrl;//支付结果错误返回的商户URL
			$Attach = '';//商户数据包
			$DispAmount = $s_amount;//显示金额
			$OrderEncodeType = 5;//订单支付接口加密方式
			$RetEncodeType = 17;//交易返回接口加密方式
			$Rettype = 1;//返回方式
			$ServerUrl = $ServerUrl;//Server to Server 返回页面URL
			$orge = 'billno'.$Billno.'currencytype'.$Currency_Type.'amount'.$Amount.'date'.$Date.'orderencodetype'.$OrderEncodeType.$Mer_key ;
			
			$SignMD5 = md5($orge) ;
			//var_dump($SignMD5);die;
			$data['Mer_code'] = $Mer_code;
			$data['Billno'] = $Billno;
			$data['Amount'] = $Amount;
			$data['Date'] = $Date;
			$data['Currency_Type'] = $Currency_Type;
			$data['Gateway_Type'] = $Gateway_Type;
			$data['Lang'] = $Lang;
			$data['Merchanturl'] = $Merchanturl;
			$data['FailUrl'] = $FailUrl;
			$data['ErrorUrl'] = $ErrorUrl;
			$data['Attach'] = $Attach;
			$data['DispAmount'] = $DispAmount;
			$data['OrderEncodeType'] = $OrderEncodeType;
			$data['RetEncodeType'] = $RetEncodeType;
			$data['RetEncodeType'] = $RetEncodeType;
			$data['Rettype'] = $Rettype;
			$data['ServerUrl'] = $ServerUrl;
			$data['SignMD5'] = $SignMD5;

			$data['act'] = 'ips';
			$url = $req_url.'?';
			foreach ($data as $key => $value) {
				$url .= $key.'='.$value.'&';
			}
			$url = substr($url,0,strlen($url)-1); 
			return $url;
	}
}