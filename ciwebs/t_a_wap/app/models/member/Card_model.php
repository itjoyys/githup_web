<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Card_model extends Online_api_model {
	function __construct() {
		parent::__construct();
		$this->init_db();
		$this->load->library('payapi/HttpClient');
		//$this->load->model('Common_model');
	}

		function getReqHmacString($p0_Cmd,$p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pr_NeedResponse,$pz_userId,$pz1_userRegTime,$p1_MerId)
	{
		//global $db_config;
		//include 'merchantProperties.php';
		#进行加密串处理，一定按照下列顺序进行
		$sbOld		=	"";
		#加入业务类型
		$sbOld		=	$sbOld.$p0_Cmd;
		#加入商户代码
		$sbOld		=	$sbOld.$p1_MerId;
		#加入商户订单号
		$sbOld		=	$sbOld.$p2_Order;
		#加入支付卡面额
		$sbOld		=	$sbOld.$p3_Amt;
		#是否较验订单金额
		$sbOld		=	$sbOld.$p4_verifyAmt;
		#产品名称
		$sbOld		=	$sbOld.$p5_Pid;
		#产品类型
		$sbOld		=	$sbOld.$p6_Pcat;
		#产品描述
		$sbOld		=	$sbOld.$p7_Pdesc;
		#加入商户接收交易结果通知的地址
		$sbOld		=	$sbOld.$p8_Url;
		#加入临时信息
		$sbOld 		= $sbOld.$pa_MP;
		#加入卡面额组
		$sbOld 		= $sbOld.$pa7_cardAmt;
		#加入卡号组
		$sbOld		=	$sbOld.$pa8_cardNo;
		#加入卡密组
		$sbOld		=	$sbOld.$pa9_cardPwd;
		#加入支付通道编码
		$sbOld		=	$sbOld.$pd_FrpId;
		#加入应答机制
		$sbOld		=	$sbOld.$pr_NeedResponse;
		#加入用户ID
		$sbOld		=	$sbOld.$pz_userId;
		#加入用户注册时间
		$sbOld		=	$sbOld.$pz1_userRegTime;
		#echo "localhost:".$sbOld;
		//$this->logstr($p2_Order,$sbOld,HmacMd5($sbOld,$merchantKey),$merchantKey);
		return $this->HmacMd5($sbOld,$merchantKey);

	}


	function annulCard($p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pz_userId,$pz1_userRegTime,$p1_MerId)
	{

		$httpclient = new HttpClient();
		# 非银行卡支付专业版支付请求，固定值 "ChargeCardDirect".
		$p0_Cmd					= "ChargeCardDirect";

		#应答机制.为"1": 需要应答机制;为"0": 不需要应答机制.
		$pr_NeedResponse	= "1";

		#调用签名函数生成签名串
		$hmac	= $this ->getReqHmacString($p0_Cmd,$p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pr_NeedResponse,$pz_userId,$pz1_userRegTime,$p1_MerId);

		#进行加密串处理，一定按照下列顺序进行
		$params = array(
			#加入业务类型
			'p0_Cmd'=>$p0_Cmd,
			#加入商家ID
			'p1_MerId'=>$p1_MerId,
			#加入商户订单号
			'p2_Order' =>$p2_Order,
			#加入支付卡面额
			'p3_Amt'=>$p3_Amt,
			#加入是否较验订单金额
			'p4_verifyAmt'=>$p4_verifyAmt,
			#加入产品名称
			'p5_Pid'=>$p5_Pid,
			#加入产品类型
			'p6_Pcat'=>	$p6_Pcat,
			#加入产品描述
			'p7_Pdesc'=>$p7_Pdesc,
			#加入商户接收交易结果通知的地址
			'p8_Url'=>$p8_Url,
			#加入临时信息
			'pa_MP'=>$pa_MP,
			#加入卡面额组
			'pa7_cardAmt'=>$pa7_cardAmt,
			#加入卡号组
			'pa8_cardNo'=>$pa8_cardNo,
			#加入卡密组
			'pa9_cardPwd'=>$pa9_cardPwd,
			#加入支付通道编码
			'pd_FrpId'=>$pd_FrpId,
			#加入应答机制
			'pr_NeedResponse'=>	$pr_NeedResponse,
			#加入校验码
			'hmac' =>$hmac,
			#用户唯一标识
			'pz_userId'=>$pz_userId,
			#用户的注册时间
			'pz1_userRegTime'=>$pz1_userRegTime
			);

		$pageContents = $httpclient->quickPost($reqURL_SNDApro, $params);
		$result = explode("\n",$pageContents);
		$r0_Cmd	= "";							#业务类型
		$r1_Code = "";							#支付结果
		$r2_TrxId =	"";							#易宝支付交易流水号
		$r6_Order =	"";							#商户订单号
		$rq_ReturnMsg =	"";							#返回信息
		$hmac =	"";					 	  #签名数据
		$unkonw	= "";							#未知错误


		for($index=0;$index<count($result);$index++){		//数组循环
			$result[$index] = trim($result[$index]);
			if (strlen($result[$index]) == 0) {
				continue;
			}
			$aryReturn		= explode("=",$result[$index]);
			$sKey					= $aryReturn[0];
			$sValue				= $aryReturn[1];
			if($sKey			=="r0_Cmd"){				#取得业务类型
				$r0_Cmd				= $sValue;
			}elseif($sKey == "r1_Code"){			        #取得支付结果
				$r1_Code			= $sValue;
			}elseif($sKey == "r2_TrxId"){			        #取得易宝支付交易流水号
				$r2_TrxId			= $sValue;
			}elseif($sKey == "r6_Order"){			        #取得商户订单号
				$r6_Order			= $sValue;
			}elseif($sKey == "rq_ReturnMsg"){				#取得交易结果返回信息
				$rq_ReturnMsg	= $sValue;
			}elseif($sKey == "hmac"){						#取得签名数据
				$hmac 				= $sValue;
			} else{
				return $result[$index];
			}
		}


		#进行校验码检查 取得加密前的字符串
		$sbOld="";
		#加入业务类型
		$sbOld = $sbOld.$r0_Cmd;
		#加入支付结果
		$sbOld = $sbOld.$r1_Code;
		#加入易宝支付交易流水号
		#$sbOld = $sbOld.$r2_TrxId;
		#加入商户订单号
		$sbOld = $sbOld.$r6_Order;
		#加入交易结果返回信息
		$sbOld = $sbOld.$rq_ReturnMsg;
		$sNewString = $this->HmacMd5($sbOld,$merchantKey);

		#校验码正确
		if($sNewString==$hmac) {
			if($r1_Code=="1"){
					echo "<br>提交成功!".$rq_ReturnMsg;
				  echo "<br>商户订单号:".$r6_Order."<br>";
				  #echo generationTestCallback($p2_Order,$p3_Amt,$p8_Url,$pa7_cardNo,$pa8_cardPwd,$pz_userId,$pz1_userRegTime);
				  return;
			} else if($r1_Code=="2"){
				  echo "<br>提交失败".$rq_ReturnMsg;
				  echo "<br>支付卡密无效!";
				  return;
			} else if($r1_Code=="7"){
				  echo "<br>提交失败".$rq_ReturnMsg;
				  echo "<br>支付卡密无效!";
				  return;
			} else if($r1_Code=="11"){
				  echo "<br>提交失败".$rq_ReturnMsg;
				  echo "<br>订单号重复!";
				  return;
			} else{
				  echo "<br>提交失败".$rq_ReturnMsg;
				  echo "<br>请检查后重新支付";
				  return;
			}
		} else{
			echo "<br>localhost:".$sNewString;
			echo "<br>YeePay:".$hmac;
			echo "<br>交易签名无效!";
			exit;
		}
	}

	function generationTestCallback($p2_Order,$p3_Amt,$p8_Url,$pa7_cardNo,$pa8_cardPwd,$pa_MP,$pz_userId,$pz1_userRegTime)
	{
		$HttpClient = new HttpClient();
		# 非银行卡支付专业版支付请求，固定值 "AnnulCard".
		$p0_Cmd					= "AnnulCard";
		#应答机制.为"1": 需要应答机制;为"0": 不需要应答机制.
		$pr_NeedResponse	= "1";
		# 非银行卡支付专业版请求地址,无需更改.
		#$reqURL_SNDApro		= "https://www.yeepay.com/app-merchant-proxy/command.action";
		$reqURL_SNDApro		= "http://tech.yeepay.com:8080/robot/generationCallback.action";
		#调用签名函数生成签名串
		#$hmac	= getReqHmacString($p0_Cmd,$p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pr_NeedResponse,$pz_userId,$pz1_userRegTime);
		#进行加密串处理，一定按照下列顺序进行
		$params = array(
			'p0_Cmd' =>	$p0_Cmd,#加入业务类型
			'p1_MerId' =>$p1_MerId,#加入商家ID
			'p2_Order' =>$p2_Order,#加入商户订单号
			'p3_Amt'  =>$p3_Amt,#加入支付卡面额
			'p8_Url' =>	$p8_Url,#加入商户接收交易结果通知的地址
			'pa7_cardNo'=>$pa7_cardNo,#加入支付卡序列号
			'pa8_cardPwd'=>$pa8_cardPwd,#加入支付卡密码
			'pd_FrpId'=>$pd_FrpId,#加入支付通道编码
			'pr_NeedResponse'=>$pr_NeedResponse,#加入应答机制
			'pa_MP' =>$pa_MP,#加入应答机制
			'pz_userId'=>$pz_userId,#用户唯一标识
			'pz1_userRegTime'=>$pz1_userRegTime);#用户的注册时间
		$pageContents	= $HttpClient->quickPost($reqURL_SNDApro, $params);
		return $pageContents;
	}


	#调用签名函数生成签名串.
	function getCallbackHmacString($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,
	$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct){
		#进行校验码检查 取得加密前的字符串
		$sbOld="";
		#加入业务类型
		$sbOld = $sbOld.$r0_Cmd;
		$sbOld = $sbOld.$r1_Code;
		$sbOld = $sbOld.$p1_MerId;
		$sbOld = $sbOld.$p2_Order;
		$sbOld = $sbOld.$p3_Amt;
		$sbOld = $sbOld.$p4_FrpId;
		$sbOld = $sbOld.$p5_CardNo;
		$sbOld = $sbOld.$p6_confirmAmount;
		$sbOld = $sbOld.$p7_realAmount;
		$sbOld = $sbOld.$p8_cardStatus;
		$sbOld = $sbOld.$p9_MP;
		$sbOld = $sbOld.$pb_BalanceAmt;
		$sbOld = $sbOld.$pc_BalanceAct;
		return $this->HmacMd5($sbOld,$merchantKey);

	}


	#取得返回串中的所有参数.
	function getCallBackValue(&$r0_Cmd,&$r1_Code,&$p1_MerId,&$p2_Order,&$p3_Amt,&$p4_FrpId,&$p5_CardNo,&$p6_confirmAmount,&$p7_realAmount,&$p8_cardStatus,&$p9_MP,&$pb_BalanceAmt,&$pc_BalanceAct,&$hmac){
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
	return null;

	}


	#验证返回参数中的hmac与商户端生成的hmac是否一致.
	function CheckHmac($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct,$hmac){
		if($hmac==$this->getCallbackHmacString($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct))
			return true;
		else
			return false;

	}


	function HmacMd5($data,$key)
	{
		# RFC 2104 HMAC implementation for php.
		# Creates an md5 HMAC.
		# Eliminates the need to install mhash to compute a HMAC
		# Hacked by Lance Rushing(NOTE: Hacked means written)

		#需要配置环境支持iconv，否则中文参数不能正常处理
		$key = iconv("GBK","UTF-8",$key);
		$data = iconv("GBK","UTF-8",$data);

		$b = 64; # byte length for md5
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


	function arrToString($arr,$Separators)
	{
		$returnString = "";
		foreach ($arr as $value) {
				$returnString = $returnString.$value.$Separators;
		}
		return substr($returnString,0,strlen($returnString)-strlen($Separators));
	}

	function arrToStringDefault($arr)
	{
		return arrToString($arr,",");
	}















}