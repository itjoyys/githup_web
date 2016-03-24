<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Yeepay_model extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->init_db();
	}
			#签名函数生成签名串
	function getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse,$p1_MerId,$merchantKey){
		  $p0_Cmd = "Buy";
		  $p9_SAF = "0";
		  $sbOld = "";#进行签名处理，一定按照文档中标明的签名顺序进行
		  $sbOld = $sbOld.$p0_Cmd;#加入业务类型
		  $sbOld = $sbOld.$p1_MerId;#加入商户编号
		  $sbOld = $sbOld.$p2_Order;#加入商户订单号
		  $sbOld = $sbOld.$p3_Amt;#加入支付金额
		  $sbOld = $sbOld.$p4_Cur; #加入交易币种
		  $sbOld = $sbOld.$p5_Pid; #加入商品名称
		  $sbOld = $sbOld.$p6_Pcat;#加入商品分类
		  $sbOld = $sbOld.$p7_Pdesc;#加入商品描述
		  $sbOld = $sbOld.$p8_Url;#加入商户接收支付成功数据的地址
		  $sbOld = $sbOld.$p9_SAF;#加入送货地址标识
		  $sbOld = $sbOld.$pa_MP;#加入商户扩展信息
		  $sbOld = $sbOld.$pd_FrpId;#加入支付通道编码
		  $sbOld = $sbOld.$pr_NeedResponse;#加入是否需要应答机制
		  return $this->HmacMd5($sbOld,$merchantKey);
	}

	function getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$p1_MerId,$merchantKey){
		$sbOld = "";#取得加密前的字符串
		$sbOld = $sbOld.$p1_MerId;#加入商家ID
		$sbOld = $sbOld.$r0_Cmd;#加入消息类型
		$sbOld = $sbOld.$r1_Code;#加入业务返回码
		$sbOld = $sbOld.$r2_TrxId;#加入交易ID
		$sbOld = $sbOld.$r3_Amt;#加入交易金额
		$sbOld = $sbOld.$r4_Cur;#加入货币单位
		$sbOld = $sbOld.$r5_Pid;#加入产品Id
		$sbOld = $sbOld.$r6_Order;#加入订单ID
		$sbOld = $sbOld.$r7_Uid;#加入用户ID
		$sbOld = $sbOld.$r8_MP;#加入商家扩展信息
		$sbOld = $sbOld.$r9_BType;#加入交易结果返回类型
		return $this->HmacMd5($sbOld,$merchantKey);
	}
	function CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac,$p1_MerId,$merchantKey){
		if($hmac==$this->getCallbackHmacString($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$p1_MerId,$merchantKey))
				return true;
			else
				return false;
	}
	function HmacMd5($data,$key){
		//需要配置环境支持iconv，否则中文参数不能正常处理
		$key = iconv("GB2312","UTF-8",$key);
		$data = iconv("GB2312","UTF-8",$data);
		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
			$key = pack("H*",md5($key));
		}
		$key = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;
		return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}

	function get_all_info($url,$p2_Order,$p3_Amt,$pd_FrpId,$pay_id,$pay_key){
			$reqURL_onLine = 'http://'.$url.'/index.php/pay/payfor';
			$ServerUrl = 'http://'.$url.'/index.php/pay/yeepay_callback';
			$form_url = 'https://www.yeepay.com/app-merchant-proxy/node';
			$data=array();
			$data['p0_Cmd'] = "Buy";
			$data['p1_MerId'] = $pay_id;
			$data['merchantKey'] = $pay_key;
			$data['p2_Order'] = $p2_Order;
			$data['p3_Amt'] = $p3_Amt;
			$data['p4_Cur'] = "CNY";//交易币种,固定值"CNY".
			$data['p5_Pid'] = ""; //商品名称
			$data['p6_Pcat'] = "";//商品种类
			$data['p7_Pdesc'] = "";//商品描述
			$data['p8_Url'] = $ServerUrl;// 商户接收支付成功数据的地址,支付成功后易宝支付会向该地址发送两次成功通知.
			$data['pa_MP'] = "";//商户扩展信息
			$data['p9_SAF'] = "0";//送货地址为“1”: 需要用户将送货地址留在易宝支付系统;为“0”: 不需要，默认为”0”
			$data['pd_FrpId'] = $pd_FrpId;//支付通道编码,即银行卡
			$data['pr_NeedResponse'] = "1";//应答机制  默认为"1": 需要应答机制;
			#调用签名函数生成签名串
			$hmac = $this->getReqHmacString($p2_Order,$p3_Amt,$data['p4_Cur'],$data['p5_Pid'],$data['p6_Pcat'],$data['p7_Pdesc'],$data['p8_Url'],$data['pa_MP'],$pd_FrpId,$data['pr_NeedResponse'],$data['p1_MerId'],$data['merchantKey']);
			$data['form_url'] = $form_url;
			$data['hmac'] = $hmac;
			$data['act'] = 'yeepay';

			$url = $reqURL_onLine.'?';
			foreach ($data as $key => $value) {
				$url .= $key.'='.$value.'&';
			}
			$url = substr($url,0,strlen($url)-1); 
			return $url;
	}
}