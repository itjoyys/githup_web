<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hk_card extends MY_Controller {
    public function __construct()
    {
		parent::__construct();
		$this->load->model('member/pay/Online_api_model');
		$this->load->model('Common_model');
		$this->Online_api_model->login_check($_SESSION['uid']);
	}

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
		if($_SESSION['pay']['is_card'] == 0){
			unset($_SESSION['pay']);
		}
		if($_SESSION['pay']['payid'] > 0){
			$status = $_SESSION['pay'];    //防止用户一直在银行卡页面刷新 而更换了支付方式   在成功跳转到第三方的时候清除此session
		}
		$pay_type = $this->Online_api_model->get_paytype($_SESSION['uid'],$status,1);
		if($pay_type['payid']>0){
			$_SESSION['pay'] = $pay_type;
			/*$this->add('bank_info',$this->Online_api_model->get_bank_info($pay_type['paytype']));*/
		}else{
			show_error('非常抱歉，在线支付暂时无法使用！请联系客服!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
		$this->add('payset',$this->Online_api_model->get_payset($_SESSION['uid']));
		$this->add('userinfo',$userinfo);
		$this->add('order_num',$this->Online_api_model->get_order_num());
		$this->add('paytype',$pay_type['paytype']);
		 if($pay_type['paytype'] == "13"){
			 $this->display('member/pay/data13.html');
		 }else{
			 $this->display('member/pay/data14.html');
		 }

	}

	/*
	**确认订单页面
	*/
	public function confirm_order13(){
		//var_dump($_SESSION);die;
		if(empty($_SESSION['pay']['payid'])){
			show_error('参数错误，请联系管理员!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
		$p2_Order  = $this->input->post('order_num');       //订单号
		$order_num = $p2_Order;
		$p3_Amt  = $this->input->post('s_amount');       //存钱金额
		$s_amount = $p3_Amt;
		$pa7_cardAmt  = $this->input->post('pa7_cardAmt'); //充值卡金额
		$pa8_cardNo  = $this->input->post('pa8_cardNo');   //充值卡账号
		$pa9_cardPwd  = $this->input->post('pa9_cardPwd'); //充值卡密码
		$email  = "";
		$tel  = "";
		$go_save  = $this->input->post('active');
		$username  = $this->input->post('username');
		$act  = $this->input->post('act');
		$this->load->helper('url');
		if(empty($order_num)){
			redirect('index.php/member/income/');
		}
		$this->add('p2_Order',$p2_Order);
		$this->add('p3_Amt',$p3_Amt);
		$this->add('pa7_cardAmt',$pa7_cardAmt);
		$this->add('pa8_cardNo',$pa8_cardNo);
		$this->add('pa9_cardPwd',$pa9_cardPwd);
		$this->add('username',$username);
		$this->add('order_num',$order_num);
		$link= "/member/income/Index/card";
		$this->add('url',$link);
		$this->add('act',$act);
		$this->add('copy_right',$this->Common_model->get_copyright());
		$this->display('member/card_confirm_order.html');
	}

		/*
	**确认订单页面
	*/
	public function confirm_order14(){
		if(empty($_SESSION['pay']['payid'])){
			show_error('参数错误，请联系管理员!<a href="javascript:history.go(-1)">返回</a>', 200, '提示');
		}
			$bank      = $this->input->post('bank_name');
			$s_amount  = $this->input->post('s_amount');
			$order_num = $this->input->post('order_num');
			$act = "dinpaycard";
			$this->load->helper('url');
			if(empty($order_num)){
				redirect('index.php/member/income/');
			}
			$this->add('bank',$bank);
			$this->add('order_num',$order_num);
			$this->add('s_amount',$s_amount);
			$this->add('username',$_SESSION['username']);
			$link= "/member/income/Index/common";
			$this->add('url',$link);
			$this->add('act',$act);
			$this->add('copy_right',$this->Common_model->get_copyright());
			$this->display('member/online_confirm_order.html');

	}



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
		$this->display('member/card_money.html');
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


}