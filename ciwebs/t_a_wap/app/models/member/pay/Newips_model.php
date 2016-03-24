<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Newips_model extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->init_db();
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key,$username,$vircarddoin){
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$ServerUrl = 'http://'.$url.'/index.php/pay/newips_callback';//商户后台通知地址
			$form_url = 'https://newpay.ips.com.cn/psfp-entry/gateway/payment.html';//第三方地址
			$data=array();
			$data['req_url'] = $req_url;
			$data['form_url'] = $form_url;
 //获取输入参数
$pVersion = 'v1.0.0';//版本号
$pMerCode = $pay_id;//商户号
$pMerName = '';//商户名
$pMerCert = $pay_key;//商户证书
$pAccount  =  $vircarddoin;//账户号
$pMsgId = 'msg4488';//消息编号
$pReqDate = date('YmdHis');//商户请求时间
$pMerBillNo = $order_num;//商户订单号
$pAmount = $s_amount;//订单金额
$pDate = date('Ymd');//订单日期
$pCurrencyType = 156;//币种
$pGatewayType = '01';//支付方式
$pLang = 'GB';//语言
$pMerchanturl = $ServerUrl;//支付结果成功返回的商户URL
$pFailUrl = $ServerUrl;//支付结果失败返回的商户URL
$pAttach = $username;//商户数据包
$pOrderEncodeTyp = 5;//订单支付接口加密方式 默认为5#md5
$pRetEncodeType = 17;//交易返回接口加密方式
$pRetType = 1;//返回方式
$pServerUrl = $ServerUrl;//Server to Server返回页面
$pBillEXP = 1;//订单有效期(过期时间设置为1小时)
$pGoodsName = $username;//商品名称
$pIsCredit = 0;//直连选项
$pBankCode = 00018;//银行号
$pProductType= 1;//产品类型

$reqParam="商户号".$pMerCode
          ."商户名".$pMerName
          ."账户号".$pAccount
          ."消息编号".$pMsgId
          ."商户请求时间".$pReqDate
          ."商户订单号".$pMerBillNo
          ."订单金额".$pAmount
          ."订单日期".$pDate
          ."币种".$pCurrencyType
          ."支付方式".$pGatewayType
          ."语言".$pLang
          ."支付结果成功返回的商户URL".$pMerchanturl
          ."支付结果失败返回的商户URL".$pFailUrl
          ."商户数据包".$pAttach
          ."订单支付接口加密方式".$pOrderEncodeTyp
          ."交易返回接口加密方式".$pRetEncodeType
          ."返回方式".$pRetType
          ."Server to Server返回页面 ".$pServerUrl
          ."订单有效期".$pBillEXP
          ."商品名称".$pGoodsName
          ."直连选项".$pIsCredit
          ."银行号".$pBankCode
          ."产品类型".$pProductType;



 if($pIsCredit==0)
 {
     $pBankCode="";
     $pProductType='';
 }

 //请求报文的消息体
  $strbodyxml= "<body>"
           ."<MerBillNo>".$pMerBillNo."</MerBillNo>"
           ."<Amount>".$pAmount."</Amount>"
           ."<Date>".$pDate."</Date>"
           ."<CurrencyType>".$pCurrencyType."</CurrencyType>"
           ."<GatewayType>".$pGatewayType."</GatewayType>"
                 ."<Lang>".$pLang."</Lang>"
           ."<Merchanturl>".$pMerchanturl."</Merchanturl>"
           ."<FailUrl>".$pFailUrl."</FailUrl>"
                 ."<Attach>".$pAttach."</Attach>"
                 ."<OrderEncodeType>".$pOrderEncodeTyp."</OrderEncodeType>"
                 ."<RetEncodeType>".$pRetEncodeType."</RetEncodeType>"
                 ."<RetType>".$pRetType."</RetType>"
                 ."<ServerUrl>".$pServerUrl."</ServerUrl>"
                 ."<BillEXP>".$pBillEXP."</BillEXP>"
                 ."<GoodsName>".$pGoodsName."</GoodsName>"
                 ."<IsCredit>".$pIsCredit."</IsCredit>"
                 ."<BankCode>".$pBankCode."</BankCode>"
                 ."<ProductType>".$pProductType."</ProductType>"
        ."</body>";

  $Sign=$strbodyxml.$pMerCode.$pMerCert;//签名明文


  $pSignature = md5($strbodyxml.$pMerCode.$pMerCert);//数字签名
  //请求报文的消息头
  $strheaderxml= "<head>"
                   ."<Version>".$pVersion."</Version>"
                   ."<MerCode>".$pMerCode."</MerCode>"
                   ."<MerName>".$pMerName."</MerName>"
                   ."<Account>".$pAccount."</Account>"
                   ."<MsgId>".$pMsgId."</MsgId>"
                   ."<ReqDate>".$pReqDate."</ReqDate>"
                   ."<Signature>".$pSignature."</Signature>"
              ."</head>";

//提交给网关的报文
$strsubmitxml =  "<Ips>"
              ."<GateWayReq>"
              .$strheaderxml
              .$strbodyxml
        ."</GateWayReq>"
            ."</Ips>";
$data['pGateWayReq'] = $strsubmitxml;

      $data['act'] = 'newips';
      $url = $req_url.'?';
      foreach ($data as $key => $value) {
        $url .= $key.'='.$value.'&';
      }
      $url = substr($url,0,strlen($url)-1); 
      return $url;
	}
}