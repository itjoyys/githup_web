<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Bfpay_model extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->init_db();
		$this->load->library('payapi/Befpay');
	}
	function get_all_info($url,$order_num,$s_amount,$bank,$pay_id,$pay_key,$username){
			$req_url = 'http://'.$url.'/index.php/pay/payfor';//跳转地址
			$ServerUrl = 'http://'.$url.'/index.php/pay/bfpay_callback';//商户后台通知地址
			$form_url = 'http://i.5dd.com/pay.api';//第三方地址
			$data=array();
			//初始化支付类，第一个参数为md5key，第二个参数为商户号
			$pay=new Befpay($pay_key,$pay_id);
			//var_dump();die;
			$postData=array(
			    'p1_md'=>1, //网银1 卡类2
			    'p2_xn'=>$order_num,//订单号
			    'p3_bn'=>$pay_id,//商户号
			    'p4_pd'=>$bank, //支付方式id
			    'p5_name'=>$username, //产品名称
			    'p6_amount'=>$s_amount, //支付金额
			    'p7_cr'=>1, //币种，目前仅支持人民币
			    'p8_ex'=>'test the payment', //扩展信息
			    'p9_url'=>$ServerUrl, //通知支付结果地址 格式：http://your url  一定要加http
			    'p10_reply'=>1, //是否通知 1通知 0不通知
			    'p11_mode'=>0, //0 返回充值地址，由商户负责跳转 1显示币付宝充值界面，跳转到充值 2不显示币付宝充值界面，直接跳转到网银
			    'p12_ver'=>1
			);
			
			$url = $pay->webPay($postData,$req_url,$form_url); //调用网银接口
			$url = substr($url,0,strlen($url)-1); 
			return $url;
	}
}