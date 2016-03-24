<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
    public function __construct()
    {
		parent::__construct();
		$this->load->model('member/pay/Online_api_model');
		$this->load->model('Common_model');
		$this->Online_api_model->login_check($_SESSION['uid']);
	}
	/*
	**1   新生
	**2   易宝
	**3   环迅
	**4   币付宝
	**5   通汇卡
	**6   宝付
	**7   智付
	**8   汇潮
	**9   国付宝
	**10  融宝
	**11  快捷通
	**12  新环迅
	**13  易宝点卡
	**14  智付点卡*/

	public function index(){

		//判断用户是否绑定银行卡
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		if(empty($userinfo['pay_num'])){
			echo "<script>alert('为方便后续能及时申请出款到账，请在入款前设置您的银行卡等信息，请如实填写！');</script>";
    		$this->hk_money($this->router->class,$this->router->method);
		}
		$level_info = $this->Common_model->get_user_level_info($userinfo['level_id']);
		//线上入款最小金额 最大金额
		$_SESSION['ol_catm_max'] = $level_info['ol_catm_max'];
		$_SESSION['ol_catm_min'] = $level_info['ol_catm_min'];
		if($_SESSION['pay']['is_card'] == 1){
			unset($_SESSION['pay']);
		}
		if($_SESSION['pay']['payid'] > 0){
			$status = $_SESSION['pay'];    //防止用户一直在银行卡页面刷新 而更换了支付方式   在成功跳转到第三方的时候清除此session
		}
		$pay_type = $this->Online_api_model->get_paytype($_SESSION['uid'],$status,0);
		if($pay_type['payid']>0){
			$_SESSION['pay'] = $pay_type;
			$this->add('bank_info',$this->Online_api_model->get_bankinfo($pay_type['paytype']));
		}else{
			show_error('非常抱歉，在线支付暂时无法使用！请联系客服!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
		$this->add('payset',$this->Online_api_model->get_payset($_SESSION['uid']));
		$this->add('info',$userinfo);
		$this->add('order_num',$this->Online_api_model->get_order_num());
		$this->add('copy_right',$this->Common_model->get_copyright());
		$this->display('member/pay/online_api_index.html');

	}

	/*
	**确认订单页面
	*/
	public function confirm_order(){
		if(empty($_SESSION['pay']['payid'])){
			show_error('参数错误，请联系管理员!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
			$bank      = $this->input->post('bank');
			$s_amount  = $this->input->post('s_amount');
			$order_num = $this->input->post('order_num');

			$this->load->helper('url');
			if(empty($order_num)){
				redirect('index.php/member/income/');
			}
			$this->add('bank',$bank);
			$this->add('order_num',$order_num);
			$this->add('s_amount',$s_amount);
			$this->add('username',$_SESSION['username']);
			$this->add('url',"/member/income/Index/common");


			$this->add('copy_right',$this->Common_model->get_copyright());
			if($_SESSION['pay']['paytype'] == 5){
				$this->add('time',date('Y-m-d H:i:s'));
				$this->add('payid',$_SESSION['pay']['payid']);
				$this->display('member/pay/huitong_confirm_order.html');
			}
			if($_SESSION['pay']['paytype'] == 2){
				$this->display('member/pay/yeepay_confirm_order.html');
			}
			else{
				$this->display('member/pay/online_confirm_order.html');
			}
	}
	/*
	**宝付
	*/
	//新生
	//币付宝
	//环迅
	//智付
	//汇潮
    //通汇卡
	//快捷通
	//新环迅
	//融宝
	//国付宝

	//绑定银行卡
	public function hk_money($class,$method){
		$this->load->model('member/Cash_model');
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		$bank_arr = $this->Cash_model->get_bank_arr();//获取银行列表
		$this->add('userinfo',$userinfo);
		$this->add('copyright',$this->Common_model->get_copyright());
		$this->add('class',$this->router->class);
		$this->add('method',$this->router->method);
		$this->add('bank_arr',$bank_arr);
		$this->display('member/online_money.html');
		exit;
	}
	//绑定银行卡处理
	public function hk_money_do(){
		$into       = $this->input->post('into');
		$class      = $this->input->post('class');
		$method     = $this->input->post('method');
		$province   = $this->input->post('province');
		$city       = $this->input->post('city');
		$pay_card   = $this->input->post('pay_card');
		$pay_num    = $this->input->post('pay_num');
		$data = array();
		$data["pay_address"] = $province."-".$city;
		$data["pay_card"]    = $pay_card;
		$data['pay_num']     = $pay_num;
		if(preg_match("/([`~!@#$%^&*()_+<>?:\"{},\/;'[\]·~！#￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/",$data["pay_num"])){
			message('银行卡号格式错误！',$method);
		}
		if($into == 'true'){
			$this->db->from('k_user_reg_config');
			$this->db->where('site_id',SITEID);
			$this->db->where('index_id',INDEX_ID);
			$this->db->select('is_banknum');
			$is_banknum = $this->db->get()->row_array();
			if(intval($is_banknum['is_banknum'])===0){
				$this->db->from('k_user');
				$this->db->where('site_id',SITEID);
				$this->db->where('index_id',INDEX_ID);
				$this->db->where('pay_num',$data['pay_num']);
				$result = $this->db->get()->row_array();
				if($result['pay_num']){
					message('该银行卡已经绑定到其他会员！',$method);
				}
			}
			$this->db->where('uid',$_SESSION['uid']);
			$this->db->update('k_user',$data);
			if($this->db->affected_rows()){
				echo '<script>alert("资料修改成功!")</script>';
				echo '<script>top.location.href="'.$method.'";</script>';exit;
			}else{
				message('对不起，由于网络堵塞原因。\\n您提交的汇款信息失败，请您重新提交。',$method);
			}
		}

	}





		//智付

	//入口方法
	function common(){
		$order_num = $this->input->post('order_num'); //订单号
		$bank = $this->input->post('bank');   //銀行參數
		$s_amount = $this->input->post('s_amount');   //支付金额
		if ($_SESSION['pay']['paytype'] == 2){
			$bank  = $this->input->post('pd_FrpId'); //银行
			$s_amount  = $this->input->post('p3_Amt');
		}else if($_SESSION['pay']['paytype'] == 5){
			$returnParams = $this->input->post('return_params');
			$TradeDate =  $this->input->post('TradeDate');
		}
		$flag = $this->Online_api_model->order_num_unique($order_num);
		$payconf = $this->Online_api_model->get_pay_config($_SESSION['pay']['payid']);
		if($flag > 0){
			show_error('参数错误，请再次尝试!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
		$result = $this->Online_api_model->write_in_record($_SESSION['uid'],$_SESSION['agent_id'],$s_amount,$order_num);
		if($result){
			$type = $this->Online_api_model->get_pay_act($_SESSION['pay']['paytype']);
			$this->load->model('member/pay/'.$type.'_model');
			$mod = $type.'_model';
			if ($_SESSION['pay']['paytype'] == 1||$_SESSION['pay']['paytype'] == 9||$_SESSION['pay']['paytype'] == 10||$_SESSION['pay']['paytype'] == 11){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$payconf['vircarddoin']);
				if($_SESSION['pay']['paytype'] == 10){
					unset($_SESSION['pay']);
					die;
				}
				unset($_SESSION['pay']);
			}else if($_SESSION['pay']['paytype'] == 4){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$_SESSION['username']);
				unset($_SESSION['pay']);exit;

			}else if($_SESSION['pay']['paytype'] == 5){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$returnParams,$TradeDate);
				unset($_SESSION['pay']);

			}else if($_SESSION['pay']['paytype'] == 6){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$_SESSION['username'],$payconf['terminalid']);
				unset($_SESSION['pay']);
				//var_dump($data);die;

			}else if($_SESSION['pay']['paytype'] == 12){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$_SESSION['username'],$payconf['vircarddoin']);
				unset($_SESSION['pay']);
				//var_dump($data);die;
			}else{
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key']);
				unset($_SESSION['pay']);
				//var_dump($data);die;
			}
			$this->add("data",$data);
			$this->display('member/pay/'.$type.'.html');
		}else{
			show_error('支付失败，请三秒后重试!<a href="javascript:history.go(-1)">返 回</a>', 200, '提示');
		}
	}


	//点卡支付
	function card(){
		$this->load->model('member/pay/Card_model');
		$act = $this->input->post('act');   //支付方式
		$p2_Order  = $this->input->post('p2_Order');
		$order_num = $p2_Order; //订单号
		$p3_Amt  = $this->input->post('p3_Amt');
		$s_amount = $p3_Amt;   //支付金额
		$pa7_cardAmt  = $this->input->post('pa7_cardAmt'); //充值卡金额
		$pa8_cardNo  = $this->input->post('pa8_cardNo');   //充值卡账号
		$pa9_cardPwd  = $this->input->post('pa9_cardPwd'); //充值卡密码
		$flag = $this->Online_api_model->order_num_unique($p2_Order);
		$payconf = $this->Online_api_model->get_pay_config($_SESSION['pay']['payid']);
		if($flag > 0){
			show_error('参数错误，请再次尝试!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
		$result = $this->Online_api_model->write_in_record($_SESSION['uid'],$_SESSION['agent_id'],$s_amount,$order_num);
		if($result){
			$p1_MerId = $payconf['pay_id'];//商户id
			$merchantKey = $payconf['pay_key'];//商户密钥
			$ServerUrl = 'http://'.$payconf['f_url'].'/index.php/pay/card_callback';//返回地址
			$reqURL_SNDApro = 'https://www.yeepay.com/app-merchant-proxy/command.action';
			//提交地址
			$p2_Order = $order_num;#商家设置用户购买商品的支付信息.#商户订单号.提交的订单号必须在自身账户交易中唯一.
			$p3_Amt	= $s_amount;#支付卡面额
			$p4_verifyAmt = 'true';#是否较验订单金额
			$p5_Pid	= '1';#产品名称
			$p6_Pcat = '2';	#iconv("UTF-8","GBK//TRANSLIT",$_POST['p5_Pid']);#产品类型
			$p7_Pdesc = '3';#产品描述
			#iconv("UTF-8","GBK//TRANSLIT",$_POST['p7_Pdesc']);
			#商户接收交易结果通知的地址,易宝支付主动发送支付结果(服务器点对点通讯).通知会通过HTTP协议以GET方式到该地址上.
			$p8_Url	= $ServerUrl;
			$pa_MP	= $_SESSION['username'];#临时信息
			#iconv("UTF-8","GB2312//TRANSLIT",$_POST['pa_MP']);
			$pa7_cardAmt = $pa7_cardAmt;#卡面额
			$pa8_cardNo	= $pa8_cardNo;#支付卡序列号.
			$pa9_cardPwd = $pa9_cardPwd;#支付卡密码.
			$pd_FrpId = '';#支付通道编码
			$pr_NeedResponse = "1";#应答机制
			$pz_userId = $_SESSION['uid'];#用户唯一标识
			$pz1_userRegTime = date('Y-m-d H:i:s');#用户的注册时间
			#非银行卡支付专业版测试时调用的方法，在测试环境下调试通过后，请调用正式方法annulCard
			#两个方法所需参数一样，所以只需要将方法名改为annulCard即可
			#测试通过，正式上线时请调用该方法
			/*var_dump($p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pz_userId,$pz1_userRegTime,$p1_MerId,$merchantKey);die;*/
			$this->Card_model->annulCard($p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pz_userId,$pz1_userRegTime,$p1_MerId,$merchantKey,$reqURL_SNDApro);
		}else{
			show_error('支付失败，请三秒后重试!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
	}
}