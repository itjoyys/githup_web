<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Ecpss_model extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->init_db();
		
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key){
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$ServerUrl = 'http://'.$url.'/index.php/pay/ecpss_callback';//商户后台通知地址
			$form_url = 'https://pay.ecpss.com/sslpayment';//第三方地址
			$data=array();
			$data['req_url'] = $req_url;
			$data['ServerUrl'] = $ServerUrl;
			$data['form_url'] = $form_url;
			$data['MD5key'] = $pay_key;		//MD5私钥
			$data['MerNo'] = $pay_id;					//商户号
			$data['BillNo'] = $order_num;		//[必填]订单号(商户自己产生：要求不重复)
			$data['Amount'] = $s_amount;				//[必填]订单金额
			$data['ReturnURL'] = $ServerUrl; 			//[必填]返回数据给商户的地址(商户自己填写):::注意请在测试前将该地址告诉我方人员;否则测试通不过
			$data['Remark'] = "";  //[选填]升级。
			$md5src = $pay_id."&".$order_num."&".$s_amount."&".$ServerUrl."&".$pay_key;		//校验源字符串
			$data['SignInfo'] = strtoupper(md5($md5src));		//MD5检验结果
			$data['AdviceURL'] =$ServerUrl;   //[必填]支付完成后，后台接收支付结果，可用来更新数据库值
			$data['orderTime'] =date('YYYYMMDDHHMMSS');   //[必填]交易时间YYYYMMDDHHMMSS
			$data['defaultBankNumber'] =$bank;   //[选填]银行代码s
			//送货信息(方便维护，请尽量收集！如果没有以下信息提供，请传空值:'')
			//因为关系到风险问题和以后商户升级的需要，如果有相应或相似的内容的一定要收集，实在没有的才赋空值,谢谢。
			$data['products']="products info";// '------------------物品信息
			$data['act'] = 'ecpss';

			$url = $req_url.'?';
			foreach ($data as $key => $value) {
				$url .= $key.'='.$value.'&';
			}
			$url = substr($url,0,strlen($url)-1); 
			return $url;
	}
}