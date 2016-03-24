<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class States_model extends Online_api_model {
	function __construct() {
		parent::__construct();
		$this->init_db();
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key,$vircarddoin){
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$ServerUrl = 'http://'.$url.'/index.php/pay/states_callback';//商户后台通知地址
			$form_url = 'https://gateway.gopay.com.cn/Trans/WebClientAction.do';//第三方地址
			$frontMerUrl = $ServerUrl;     //商户前台通知地址
			$backgroundMerUrl = $ServerUrl; //商户后台通知地址
			$data=array();
			$data['req_url'] = $req_url;
			$data['form_url'] = $form_url;
			$data['frontMerUrl'] = $ServerUrl;
			$data['backgroundMerUrl'] = $ServerUrl;
			$data['version'] = 2.1; //网关版本号
			$data['charset'] = 2;   //字符集 1:GBK,2:UTF-8 (不填则当成1处理)
			$data['language'] = 1;       //网关语言版本  1:ZH,2:EN
			$data['signType'] = 1;  //报文加密方式 1:MD5,2:SHA
			$data['tranCode'] = 8888; //交易代码
			$data['merchantID'] = $pay_id;      //商户ID
			$data['merOrderNum'] = $order_num;//订单号
			$data['tranAmt'] = $s_amount;   //交易金额
			$data['feeAmt'] = '';           //商户提取佣金金额
			$data['currencyType'] = 156;  //币种
			$data['tranDateTime'] = date('YmdHis');    //交易时间
			$data['virCardNoIn'] = $vircarddoin;//   国付宝账号
			$data['tranIP'] = '127.0.0.1';//'127.0.0.1';
			$data['isRepeatSubmit'] = 0;   //订单是否允许重复 0不允许 1允许（默认）
			$data['goodsName'] = '';//商品名称
			$data['goodsDetail'] = '';//商品详情
			$data['buyerName'] = ''; //买家姓名
			$data['buyerContact'] = '';//买家联系方式
			$data['merRemark1'] = '';//商户备用信息字段
			$data['merRemark2'] = '';      //商户备用信息字段
			$data['bankCode'] = $bank;         //银行代码
			$data['userType'] =  1;//$_POST["userType"];         //用户类型
			$data['gopayServerTime'] = '';//HttpClient::getGopayServerTime();
			$data['Mer_key'] = $pay_key;//秘钥
			$signStr='version=['.$data['version'].']tranCode=['.$data['tranCode'].']merchantID=['.$data['merchantID'].']merOrderNum=['.$data['merOrderNum'].']tranAmt=['.$data['tranAmt'].']feeAmt=['.$data['feeAmt'].']tranDateTime=['.$data['tranDateTime'].']frontMerUrl=['.$data['frontMerUrl'].']backgroundMerUrl=['.$data['backgroundMerUrl'].']orderId=[]gopayOutOrderId=[]tranIP=['.$data['tranIP'].']respCode=[]gopayServerTime=['.$data['gopayServerTime'].']VerficationCode=['.$pay_key.']';
			//VerficationCode是商户识别码为用户重要信息请妥善保存
			//注意调试生产环境时需要修改这个值为生产参数
			$signValue = md5($signStr);
			$data['signValue'] = $signValue;
			return $data;
	}
}