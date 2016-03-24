<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('member/pay/Online_api_model');
	}

	public function index(){
		echo "11";exit;
	}
	public function payfor(){

			$action = $_REQUEST["act"];
			$this->$action($action);

	}
	//宝付
	function baofoo($action){
			$data = $_REQUEST;
			$this->add("data",$data);
			$this->display('member/rep/'.$action.'.html');
	}
	function baofoo_callback(){
		$order_num = $_REQUEST['TransID'];//订单号
		$money = $_REQUEST['FactMoney'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
		$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$MemberID=$payconf['pay_id'];//$_REQUEST['MemberID'];//商户号
		$TerminalID =$payconf['terminalid'];//$_REQUEST['TerminalID'];//商户终端号
		$TransID =$_REQUEST['TransID'];//流水号
		$Result=$_REQUEST['Result'];//支付结果
		$ResultDesc=$_REQUEST['ResultDesc'];//支付结果描述
		$FactMoney=$_REQUEST['FactMoney'];//实际成功金额
		$AdditionalInfo=$_REQUEST['AdditionalInfo'];//订单附加消息
		$SuccTime=$_REQUEST['SuccTime'];//支付完成时间
		$Md5Sign=$_REQUEST['Md5Sign'];//md5签名
		$Md5key = $payconf['pay_key'];//$result['pay_key'];
		$MARK = "~|~";
		//MD5签名格式
		$WaitSign=md5('MemberID='.$MemberID.$MARK.'TerminalID='.$TerminalID.$MARK.'TransID='.$TransID.$MARK.'Result='.$Result.$MARK.'ResultDesc='.$ResultDesc.$MARK.'FactMoney='.$FactMoney.$MARK.'AdditionalInfo='.$AdditionalInfo.$MARK.'SuccTime='.$SuccTime.$MARK.'Md5Sign='.$Md5key);
		if ($Md5Sign == $WaitSign) {
			$this->Online_api_model->update_order($user['uid'],$order_num,$money*0.01);
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}

	}
	//新生
	function hnapay($action){
			$data = $_REQUEST;
			$this->add("data",$data);
			$this->display('member/rep/'.$action.'.html');
	}

	function hnapay_callback(){
		$order_num = $_REQUEST['orderID'];//订单号
		$money = $_REQUEST['payAmount']/100;//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
		$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		$orderID = $_REQUEST["orderID"];
		$resultCode = $_REQUEST["resultCode"];
		$stateCode = $_REQUEST["stateCode"];
		$orderAmount = $_REQUEST["orderAmount"];
		$payAmount = $_REQUEST["payAmount"];
		$acquiringTime = $_REQUEST["acquiringTime"];
		$completeTime = $_REQUEST["completeTime"];
		$orderNo = $_REQUEST["orderNo"];
		$partnerID = $_REQUEST["partnerID"];
		$remark = $_REQUEST["remark"];
		$charset = $_REQUEST["charset"];
		$signType = $_REQUEST["signType"];
		$signMsg = $_REQUEST["signMsg"];
		$src = "orderID=".$orderID
		."&resultCode=".$resultCode
		."&stateCode=".$stateCode
		."&orderAmount=".$orderAmount
		."&payAmount=".$payAmount
		."&acquiringTime=".$acquiringTime
		."&completeTime=".$completeTime
		."&orderNo=".$orderNo
		."&partnerID=".$partnerID
		."&remark=".$remark
		."&charset=".$charset
		."&signType=".$signType;
			$pkey = $payconf['pay_key'];
			$src = $src."&pkey=".$pkey;
		if ($signMsg == md5($src))
		{
			$this->Online_api_model->update_order($uid,$order_num,$money);
			exit;
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}

	}
	//币付宝
	function bfpay($action){
		$data = $_REQUEST;
		$this->add("data",$data);
		$this->display('member/rep/'.$action.'.html');
	}

	function bfpay_callback(){
		$this->load->library('payapi/Befpay');
		$order_num = $_REQUEST['p3_xn'];//订单号
		$money = $_REQUEST['p4_amt'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
		$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		//初始化支付类，第一个参数为md5key，第二个参数为商户号
		$pay=new Befpay($payconf['pay_key'],$payconf['pay_id']);  //请填写相应商户对应的key和商户号
		$data=$pay->returnData();
		if($data['p7_st'] == 'success'){
			$this->Online_api_model->update_order($uid,$order_num,$money);
			echo "success";exit;//返回给币付宝success，以免漏单
		}elseif($data['p7_st'] == 'faile'){
			echo '<script>alert("支付失败，错误代码CW008！");window.close();</script>';
			echo '支付失败，错误代码CW008！';
			exit;
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}
	}
	//环迅
	function ips($action){
		$data = $_REQUEST;
		$this->add("data",$data);
		$this->display('member/rep/'.$action.'.html');
	}

	function ips_callback(){
		$order_num = $_REQUEST['billno'];//订单号
		$money = $_REQUEST['amount'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
		$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		$billno = $order_num;
		$amount = $money;
		$mydate = $_REQUEST['date'];
		$succ = $_REQUEST['succ'];
		$msg = $_REQUEST['msg'];
		$attach = $_REQUEST['attach'];
		$ipsbillno = $_REQUEST['ipsbillno'];
		$retEncodeType = $_REQUEST['retencodetype'];
		$currency_type = $_REQUEST['Currency_type'];
		$signature = $_REQUEST['signature'];
		$content = 'billno'.$billno.'currencytype'.$currency_type.'amount'.$amount.'date'.$mydate.'succ'.$succ.'ipsbillno'.$ipsbillno.'retencodetype'.$retEncodeType;
		//请在该字段中放置商户登陆merchant.ips.com.cn下载的证书
		$cert = $payconf['pay_key'];
		$signature_1ocal = md5($content . $cert);
		if ($signature_1ocal == $signature)
		{
			if($succ == 'Y'){
				$this->Online_api_model->update_order($uid,$order_num,$money);
				exit;
			}else{
				echo "<script>alert('交易失败,错误代码cw0008');window.close();</script>";exit;
			}
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}
	}
	//智付
	function dinpay($action){
		$data = $_REQUEST;
		$this->add("data",$data);
		$this->display('member/rep/'.$action.'.html');

	}

	function dinpay_callback(){
		$order_num = $_REQUEST['order_no'];//订单号
		$money = $_REQUEST['order_amount'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
		$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		/* *
		 功能：智付页面跳转同步通知页面
		 版本：3.0
		 日期：2013-08-01
		 说明：
		 以下代码仅为了方便商户安装接口而提供的样例具体说明以文档为准，商户可以根据自己网站的需要，按照技术文档编写。
		 * */
			//获取智付GET过来反馈信息
			$merchant_code	= $_REQUEST["merchant_code"];//商号号
			$notify_type = $_REQUEST["notify_type"];//通知类型
			$notify_id = $_REQUEST["notify_id"];//通知校验ID
			$interface_version = $_REQUEST["interface_version"];//接口版本
			$sign_type = $_REQUEST["sign_type"];//签名方式
			$dinpaySign = $_REQUEST["sign"];//签名
			$order_no = $order_num;//商家订单号
			$order_time = $_REQUEST["order_time"];//商家订单时间
			$order_amount = $money;//商家订单金额
			$extra_return_param = $_REQUEST["extra_return_param"];//回传参数
			$s_name=$extra_return_param;
			$trade_no = $_REQUEST["trade_no"];//智付交易定单号
			$trade_time = $_REQUEST["trade_time"];//智付交易时间
			$trade_status = $_REQUEST["trade_status"];//交易状态 SUCCESS 成功  FAILED 失败
			$bank_seq_no = $_REQUEST["bank_seq_no"];//银行交易流水号
			/**
			 *签名顺序按照参数名a到z的顺序排序，若遇到相同首字母，则看第二个字母，以此类推，
			*同时将商家支付密钥key放在最后参与签名，组成规则如下：
			*参数名1=参数值1&参数名2=参数值2&……&参数名n=参数值n&key=key值
			**/
			//组织订单信息
			$signStr = "";
			if($bank_seq_no != "") {
				$signStr = $signStr."bank_seq_no=".$bank_seq_no."&";
			}
			if($extra_return_param != "") {
			    $signStr = $signStr."extra_return_param=".$extra_return_param."&";
			}
			$signStr = $signStr."interface_version=V3.0&";
			$signStr = $signStr."merchant_code=".$merchant_code."&";
			if($notify_id != "") {
			    $signStr = $signStr."notify_id=".$notify_id."&notify_type=offline_notify&";
			}
		        $signStr = $signStr."order_amount=".$order_amount."&";
		        $signStr = $signStr."order_no=".$order_no."&";
		        $signStr = $signStr."order_time=".$order_time."&";
		        $signStr = $signStr."trade_no=".$trade_no."&";
		        $signStr = $signStr."trade_status=".$trade_status."&";
			if($trade_time != "") {
			     $signStr = $signStr."trade_time=".$trade_time."&";
			}
			$key=$payconf['pay_key'];   //"123456789a123456789_";
			$signStr = $signStr."key=".$key;
			$signInfo = $signStr;
			//将组装好的信息MD5签名
			$sign = md5($signInfo);
		if ($dinpaySign == $sign){
			$this->Online_api_model->update_order($uid,$order_num,$money);
			exit;
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}
	}
	//汇潮
	function ecpss($action){
		$data = $_REQUEST;
		$this->add("data",$data);
		$this->display('member/rep/'.$action.'.html');
	}
	function ecpss_callback(){
		$order_num = $_REQUEST['BillNo'];//订单号
		$money = $_REQUEST['Amount'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
		$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		$MD5key = $payconf['pay_key'];//MD5私钥
		$BillNo = $order_num;//订单号
		$Amount = $money;//金额
		$Succeed = $_REQUEST["Succeed"];//支付状态
		$Result = $_REQUEST["Result"];//支付结果
		$SignMD5info = $_REQUEST["SignMD5info"]; //取得的MD5校验信息
		$Remark = $_REQUEST["Remark"];//备注
		$md5src = $BillNo."&".$Amount."&".$Succeed."&".$MD5key;//校验源字符串
		$md5sign = strtoupper(md5($md5src));//MD5检验结果
		if ($SignMD5info == $md5sign){
			if($Succeed == '88'){
				$this->Online_api_model->update_order($uid,$order_num,$money);
				exit;
			}else{
				echo "<script>alert('交易失败,错误代码cw0008');window.close();</script>";exit;
			}
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}
	}
	//快捷通
	function kjtpay($action){
		$data = $_REQUEST;
		$this->add("data",$data);
		//var_dump($data);die;
		$this->display('member/rep/'.$action.'.html');
		/*$service = $this->input->post("service");
		$version = $this->input->post("version");
		$partner_id = $this->input->post("partner_id");
		$_input_charset = $this->input->post("_input_charset");
		$sign_type = $this->input->post("sign_type");
		$sign = $this->input->post("sign");
		$return_url = $this->input->post("return_url");
		$request_no = $this->input->post("request_no");
		$trade_list = $this->input->post("trade_list");
		$buyer_id = $this->input->post("buyer_id");
		$buyer_id_type = $this->input->post("buyer_id_type");
		$go_cashier = $this->input->post("go_cashier");
		$pay_method = $this->input->post("pay_method");
		$form_url = $this->input->post("form_url");
		$req_url = $this->input->post("req_url");
		$this->add('service',$service);
		$this->add('version',$version);
		$this->add('partner_id',$partner_id);
		$this->add('_input_charset',$_input_charset);
		$this->add('sign_type',$sign_type);
		$this->add('sign',$sign);
		$this->add('return_url',$return_url);
		$this->add('request_no',$request_no);
		$this->add('trade_list',$trade_list);
		$this->add('buyer_id',$buyer_id);
		$this->add('buyer_id_type',$buyer_id_type);
		$this->add('go_cashier',$go_cashier);
		$this->add('pay_method',$pay_method);
		$this->add('form_url',$form_url);
		$this->add('req_url',$req_url);
		$this->display('member/rep/'.$action.'.html');*/

	}
	function kjtpay_callback(){
		$order_num = $_REQUEST['outer_trade_no'];//订单号
		$money = $_REQUEST['trade_amount'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
		$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		$billno = $order_num;
		$amount = $money;
		$key = $payconf['pay_key'];
		$notify_id=$_REQUEST["notify_id"];
		$notify_type=$_REQUEST["notify_type"];
		$notify_time=$_REQUEST["notify_time"];
		$_input_charset=$_REQUEST["_input_charset"];
		$version=$_REQUEST["version"];
		$outer_trade_no=$_REQUEST["outer_trade_no"];
		$inner_trade_no=$_REQUEST["inner_trade_no"];
		$trade_status=$_REQUEST["trade_status"];
		$trade_amount=$_REQUEST["trade_amount"];
		$gmt_create=$_REQUEST["gmt_create"];
		$gmt_payment=$_REQUEST["gmt_payment"];
		$gmt_close=$_REQUEST["gmt_close"];
		$sign1=$_REQUEST["sign"];
		$sign_type=$_REQUEST["sign_type"];

		$str="_input_charset=".$_input_charset."&gmt_create=".$gmt_create."&gmt_payment=".$gmt_payment."&inner_trade_no=".$inner_trade_no."&notify_id=".$notify_id."&notify_time=".$notify_time."&notify_type=".$notify_type."&outer_trade_no=".$outer_trade_no."&trade_amount=".$trade_amount."&trade_status=".$trade_status."&version=".$version;
		$sign=md5($str.$key);
		if ($sign1 == $sign){
			if ("TRADE_SUCCESS"==$trade_status) {
				$this->Online_api_model->update_order($uid,$order_num,$money);
				exit;
			}else{
				echo "<script>alert('交易失败,错误代码cw0008');window.close();</script>";exit;
			}
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}

	}
	//新环迅
	function newips($action){
		$pGateWayReq= $_REQUEST['pGateWayReq'];
		$form_url = $_REQUEST["form_url"];
		$this->add('form_url',$form_url);
		$this->add('pGateWayReq',$pGateWayReq);
		$this->display('member/rep/'.$action.'.html');

	}
	function newips_callback(){

		$paymentResult = $_REQUEST["paymentResult"];//获取信息
		$xml=simplexml_load_string($paymentResult,'SimpleXMLElement', LIBXML_NOCDATA); 

		//读取相关xml中信息
		$ReferenceIDs = $xml->xpath("GateWayRsp/head/ReferenceID");//关联号
		//var_dump($ReferenceIDs); 
		$ReferenceID = $ReferenceIDs[0];//关联号
		$RspCodes = $xml->xpath("GateWayRsp/head/RspCode");//响应编码
		$RspCode=$RspCodes[0];
		$RspMsgs = $xml->xpath("GateWayRsp/head/RspMsg"); //响应说明
		$RspMsg=$RspMsgs[0];
		$ReqDates = $xml->xpath("GateWayRsp/head/ReqDate"); // 接受时间
		$ReqDate=$ReqDates[0];
		$RspDates = $xml->xpath("GateWayRsp/head/RspDate");// 响应时间
		$RspDate=$RspDates[0];
		$Signatures = $xml->xpath("GateWayRsp/head/Signature"); //数字签名
		$Signature=$Signatures[0];
		$MerBillNos = $xml->xpath("GateWayRsp/body/MerBillNo"); // 商户订单号
		$MerBillNo=$MerBillNos[0];
		$CurrencyTypes = $xml->xpath("GateWayRsp/body/CurrencyType");//币种
		$CurrencyType=$CurrencyTypes[0];
		$Amounts = $xml->xpath("GateWayRsp/body/Amount"); //订单金额
		$Amount=$Amounts[0];
		$Dates = $xml->xpath("GateWayRsp/body/Date");    //订单日期
		$Date=$Dates[0];
		$Statuss = $xml->xpath("GateWayRsp/body/Status");  //交易状态
		$Status=$Statuss[0];
		$Msgs = $xml->xpath("GateWayRsp/body/Msg");    //发卡行返回信息
		$Msg=$Msgs[0];
		$Attachs = $xml->xpath("GateWayRsp/body/Attach");    //数据包
		$Attach=$Attachs[0];
		$IpsBillNos = $xml->xpath("GateWayRsp/body/IpsBillNo"); //IPS订单号
		$IpsBillNo=$IpsBillNos[0];
		$IpsTradeNos = $xml->xpath("GateWayRsp/body/IpsTradeNo"); //IPS交易流水号
		$IpsTradeNo=$IpsTradeNos[0];
		$RetEncodeTypes = $xml->xpath("GateWayRsp/body/RetEncodeType");    //交易返回方式
		$RetEncodeType=$RetEncodeTypes[0];
		$BankBillNos = $xml->xpath("GateWayRsp/body/BankBillNo"); //银行订单号
		$BankBillNo=$BankBillNos[0];
		$ResultTypes = $xml->xpath("GateWayRsp/body/ResultType"); //支付返回方式
		$ResultType=$ResultTypes[0];
		$IpsBillTimes = $xml->xpath("GateWayRsp/body/IpsBillTime"); //IPS处理时间
		$IpsBillTime=$IpsBillTimes[0];

		$order_num = $MerBillNo;//订单号
		$money = $Amount;//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
		$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		$Mer_code = $payconf['pay_id'];
		$Mer_key  = $payconf['pay_key'];
		$vircarddoin  = $payconf['terminalid'];
		$arrayMer=array (
             'mername'=>$Mer_code,//商户id
             'mercert'=>$Mer_key,//商户密钥
             'acccode'=>$vircarddoin
           );
			 $sbReq = "<body>"
									  . "<MerBillNo>" . $MerBillNo . "</MerBillNo>"
									  . "<CurrencyType>" . $CurrencyType . "</CurrencyType>"
									  . "<Amount>" . $Amount . "</Amount>"
									  . "<Date>" . $Date . "</Date>"
									  . "<Status>" . $Status . "</Status>"
									  . "<Msg><![CDATA[" . $Msg . "]]></Msg>"
									  . "<Attach><![CDATA[" . $Attach . "]]></Attach>"
									  . "<IpsBillNo>" . $IpsBillNo . "</IpsBillNo>"
									  . "<IpsTradeNo>" . $IpsTradeNo . "</IpsTradeNo>"
									  . "<RetEncodeType>" . $RetEncodeType . "</RetEncodeType>"
									  . "<BankBillNo>" . $BankBillNo . "</BankBillNo>"
									  . "<ResultType>" . $ResultType . "</ResultType>"
									  . "<IpsBillTime>" . $IpsBillTime . "</IpsBillTime>"
								   . "</body>";
			$sign=$sbReq.$Mer_code.$arrayMer['mercert'];
			$md5sign=  md5($sign);
		if ($Signature == $md5sign){
			if ($RspCode == '000000') {
				$this->Online_api_model->update_order($uid,$order_num,$money);
				exit;
			}else{
				echo "<script>alert('交易失败,错误代码cw0008');window.close();</script>";exit;
			}
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}
	}
	//国付宝
	function states($action){
		$data = $_REQUEST;
		$this->add("data",$data);
		$this->display('member/rep/'.$action.'.html');

	}
	function states_callback(){
		$merOrderNum = $_REQUEST['merOrderNum'];//订单号
		$tranAmt = $_REQUEST['tranAmt'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($merOrderNum);
		$user = $this->Online_api_model->get_in_cash($merOrderNum);
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		//$userinfo = $this->Online_api_model->getinfo($uid);
		$payset = $this->Online_api_model->get_payset($uid);
		$version = $_REQUEST["version"];
		$charset = $_REQUEST["charset"];
		$language = $_REQUEST["language"];
		$signType = $_REQUEST["signType"];
		$tranCode = $_REQUEST["tranCode"];
		$merchantID = $payconf['pay_id'];//商户号
		$merOrderNum = $_REQUEST["merOrderNum"];
		$tranAmt = $_REQUEST["tranAmt"];
		$feeAmt = $_REQUEST["feeAmt"];
		$frontMerUrl = $_REQUEST["frontMerUrl"];
		$backgroundMerUrl = $_REQUEST["backgroundMerUrl"];
		$tranDateTime = $_REQUEST["tranDateTime"];
		$tranIP = $_REQUEST["tranIP"];
		$respCode = $_REQUEST["respCode"];
		$msgExt = $_REQUEST["msgExt"];
		$orderId = $_REQUEST["orderId"];
		$gopayOutOrderId = $_REQUEST["gopayOutOrderId"];
		$bankCode = $_REQUEST["bankCode"];
		$tranFinishTime = $_REQUEST["tranFinishTime"];
		$merRemark1 = $_REQUEST["merRemark1"];
		$merRemark2 = $_REQUEST["merRemark2"];
		$signValue = $_REQUEST["signValue"];
		$Mer_key = $payconf['pay_key'];//商户秘钥
		//注意md5加密串需要重新拼装加密后，与获取到的密文串进行验签
		$signValue2='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=['.$orderId.']gopayOutOrderId=['.$gopayOutOrderId.']tranIP=['.$tranIP.']respCode=['.$respCode.']gopayServerTime=[]VerficationCode=['.$Mer_key.']';
		$signValue2 = md5($signValue2);
		if($signValue==$signValue2){
			if($respCode=='0000'){
				$this->Online_api_model->update_order($uid,$merOrderNum,$tranAmt);
			}else{
				echo "<script>alert('交易失败,错误代码cw0008');window.close();</script>";exit;
			}
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}
	}

	function card_callback(){
		$this->load->model('member/pay/Card_model');
		#	解析返回参数.
			$r0_Cmd = $_REQUEST['r0_Cmd'];
			$r1_Code = $_REQUEST['r1_Code'];
			$p1_MerId = $_REQUEST['p1_MerId'];
			$p2_Order = $_REQUEST['p2_Order'];
			$p3_Amt = $_REQUEST['p3_Amt'];
			$p4_FrpId = $_REQUEST['p4_FrpId'];
			$p5_CardNo = $_REQUEST['p5_CardNo'];
			$p6_confirmAmount = $_REQUEST['p6_confirmAmount'];
			$p7_realAmount = $_REQUEST['p7_realAmount'];
			$p8_cardStatus = $_REQUEST['p8_cardStatus'];
			$p9_MP = $_REQUEST['p9_MP'];
			$pb_BalanceAmt = $_REQUEST['pb_BalanceAmt'];
			$pc_BalanceAct = $_REQUEST['pc_BalanceAct'];
			$hmac = $_REQUEST['hmac'];

			$order_num = $_REQUEST['p2_Order'];//订单号
			$money = $_REQUEST['p3_Amt'];//订单金额
			$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
			$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
			if($user['make_sure'] == 1){
				echo '<script>alert("支付成功");</script>';exit;
			}elseif($user['make_sure'] == 2){
				echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
			}
			$uid = $user['uid'];

			#	解析返回参数.
	$return = $this->Card_model->getCallBackValue($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,
$p9_MP,$pb_BalanceAmt,$pc_BalanceAct,$hmac);

			#	判断返回签名是否正确（True/False）

			$bRet = $this->Card_model->CheckHmac($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct,$hmac,$payconf['pay_key']);
			#	以上代码和变量不需要修改.
			#	校验码正确.
			if($bRet){
				echo "success";
			#在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理
				if($r1_Code=="1"){
					$this->Online_api_model->update_order($uid,$order_num,$money);
				}else{
					echo "<script>alert('交易失败,错误代码cw0008');window.close();</script>";exit;
				}
			}else{
				echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
			}
	}

	//易宝
	function yeepay($action){
		$data = $_REQUEST;
		$this->add("data",$data);
		$this->display('member/rep/'.$action.'.html');
	}

	function yeepay_callback(){
		$this->load->model('member/Yeepay_model');
		$order_num = $_REQUEST['r6_Order'];//订单号
		$money = $_REQUEST['r3_Amt'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_num);//根据订单号获取配置信息
		$user = $this->Online_api_model->get_in_cash($order_num);//根据订单号 获取入款注单数据
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		$r0_Cmd		= $_REQUEST['r0_Cmd'];//业务类型 固定值”Buy”.
		$r1_Code	= $_REQUEST['r1_Code'];//支付结果固定值“1”, 代表支付成功.
		$r2_TrxId	= $_REQUEST['r2_TrxId'];//易宝支付交易流水号
		$r3_Amt		= $_REQUEST['r3_Amt'];//支付金额
		$r4_Cur		= $_REQUEST['r4_Cur'];//交易币种 返回时是"RMB"
		$r5_Pid		= $_REQUEST['r5_Pid'];//商品名称易宝支付返回商户设置的商品名称.此参数如用到中文，请注意转码.
		$r6_Order	= $_REQUEST['r6_Order'];//商户订单号
		$r7_Uid		= $_REQUEST['r7_Uid'];//易宝支付会员ID 如果用户使用的易宝支付会员进行支付则返回该用户的易宝支付会员ID;反之为''.
		$r8_MP		= $_REQUEST['r8_MP'];//商户扩展信息 此参数如用到中文，请注意转码.
		$r9_BType	= $_REQUEST['r9_BType']; //交易结果返回类型 为“1”: 浏览器重定向; 为“2”: 服务器点对点通讯.
		$hmac			= $_REQUEST['hmac'];//签名数据
		$p1_MerId = $payconf['pay_id'];
		$merchantKey = $payconf['pay_key'];
		#	判断返回签名是否正确（True/False）
		$bRet = $this->Yeepay_model->CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac,$p1_MerId,$merchantKey);
		if($bRet){
			if($r1_Code=="1"){
				if($r9_BType=="1"){
					$this->Online_api_model->update_order($uid,$order_num,$money);
				}elseif($r9_BType=="2"){
					$this->Online_api_model->update_order($uid,$order_num,$money);
				}
			}
		}else{
			echo "<script>alert('操作非法，请重新操作！');</script>";
		}
	}
	//融宝

	function reapal($action){
		$data = $_REQUEST;
		$this->add("data",$data);
		$this->display('member/rep/'.$action.'.html');
	}

	function reapal_callback(){
		$this->load->library('payapi/Rongpay_notify.php');
		$order_no = $_REQUEST['order_no'];//订单号
		$total_fee = $_REQUEST['total_fee'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($order_no);
		$user = $this->Online_api_model->get_in_cash($order_no);
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		//$userinfo = $this->Online_api_model->getinfo($uid);
		$payset = $this->Online_api_model->get_payset($uid);
		$merchant_ID = $payset['pay_id'];
		$key = $payset['pay_key'];
		$sign_type = $_REQUEST['sign_type'];
		$form_url = $_REQUEST['form_url'];
		 $rongpay = new rongpay_notify();
		 $rongpay->rongpay_notify($merchant_ID,$key,$sign_type,$charset = "utf-8",$transport= "http");
		 $zhifu = $rongpay->return_verify($form_url);
		if($zhifu){
			echo "success";
				$this->Online_api_model->update_order($uid,$order_no,$total_fee);
		}else{
			   echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}
	}



	//通汇

	function remittance($action){
		$data = $_REQUEST;
		$this->add("data",$data);
		$this->display('member/rep/'.$action.'.html');
	}

	function remittance_callback(){
		$orderNo = $_REQUEST['order_no'];//订单号
		$orderAmount = $_REQUEST['order_amount'];//订单金额
		$payconf = $this->Online_api_model->get_states_conf($orderNo);
		$user = $this->Online_api_model->get_in_cash($orderNo);
		if($user['make_sure'] == 1){
			echo '<script>alert("支付成功");</script>';exit;
		}elseif($user['make_sure'] == 2){
			echo '<script>alert("交易被取消！请联系管理员");</script>';exit;
		}
		$uid = $user['uid'];
		//$userinfo = $this->Online_api_model->getinfo($uid);
		$payset = $this->Online_api_model->get_payset($uid);
		$orderNo = $_REQUEST['order_no'];
		$orderAmount = $_REQUEST['order_amount'];
		$notifyType = $_REQUEST['notify_type'];
        $merchantCode =  $_REQUEST['merchant_code'];
        $orderTime =  $_REQUEST['order_time'];
        $returnParams =  $_REQUEST['return_params'];
        $tradeNo =  $_REQUEST['trade_no'];
        $tradeTime =  $_REQUEST['trade_time'];
        $tradeStatus =  $_REQUEST['trade_status'];
        $sign =  $_REQUEST['sign'];
        $this->load->library('payapi/Helper');
        $Helper = new Helper();
        $data = array();
        $data['merchant_code'] = $merchantCode;
        $data['notify_type'] = $notifyType;
        $data['order_no'] = $orderNo;
        $data['order_amount'] = $orderAmount;
        $data['order_time'] = $orderTime;
        $data['return_params'] = $returnParams;
        $data['trade_no'] = $tradeNo;
        $data['trade_time'] = $tradeTime;
        $data['trade_status'] = $tradeStatus;
        $_sign = $Helper->sign($data,$payconf['pay_key']);
		if($_sign == $sign){
				if($tradeStatus == 'success'){
					$this->Online_api_model->update_order($uid,$orderNo,$orderAmount);
				}else{
					echo "<script>alert('交易失败,错误代码cw0008');window.close();</script>";exit;
				}
		}else{
			echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
		}
	}


}


