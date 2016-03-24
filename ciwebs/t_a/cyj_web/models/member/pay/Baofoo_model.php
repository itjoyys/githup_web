<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Baofoo_model extends Online_api_model {
	function __construct() {
		parent::__construct();
		$this->init_db();
		
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key,$username,$terminalid){
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$ServerUrl = 'http://'.$url.'/index.php/pay/baofoo_callback';//商户后台通知地址
			$form_url = 'http://gw.baofoo.com/payindex ';//第三方地址
			$data=array();
			$data['req_url'] = $req_url;
			$data['ServerUrl'] = $ServerUrl;
			$data['form_url'] = $form_url;
			$data['MemberID'] = $pay_id;//商户号
			$data['TransID'] = $order_num;//流水号
			$data['PayID'] = $bank;//银行参数
			$data['TradeDate'] = date('YmdHis');//交易时间
			$data['OrderMoney'] = $s_amount*100;//订单金额
			$data['ProductName'] = '';//产品名称
			$data['Amount'] = 1;//商品数量
			$data['Username'] = $username;//支付用户名
			$data['AdditionalInfo'] = '';//订单附加消息
			$data['PageUrl'] = $ServerUrl;//通知商户页面端地址
			$data['ReturnUrl'] = $ServerUrl;//服务器底层通知地址
			$data['NoticeType'] = 1;//通知类型
			$data['Md5key'] = $pay_key;//md5密钥（KEY）
			$MARK = "|";
			//MD5签名格式
			$Signature=md5($pay_id.$MARK.$bank.$MARK.$data['TradeDate'].$MARK.$order_num.$MARK.$data['OrderMoney'].$MARK.$ServerUrl.$MARK.$ServerUrl.$MARK.$data['NoticeType'].$MARK.$pay_key);
			$payUrl=$form_url;//"http://".$result['pay_domain'];//借贷混合
			$data['TerminalID'] = $terminalid;//终端ID
			$data['InterfaceVersion'] = "4.0";
			$data['Signature'] = $Signature;
			$data['KeyType'] = "1";
			return $data;
	}
}