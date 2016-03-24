<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_web extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('webcenter/Register_web_model');
	}

    //试玩
	public function join_shiwan()
	{
		$pk_token = $this->Register_web_model->getPKtoken();
		$_SESSION['PKtoken']= $pk_token;
		$_SESSION['PKtokenState']= 1;
		$username = $this->Register_web_model->shiwan_user();
	    $this->add("data", $this->reg_iword(9));      //注册标题
	    $this->add("data2", $this->reg_iword(18));   //开户协议
		$this->add("username",$username);
		$this->add("pk_token",$pk_token);
		$this->display('web/join_shiwan.html');
	}

	//试玩信息处理
	public function join_shiwan_do(){
	    if (!empty($_SESSION['uid'])) {
	    	message('登陆用户不能注册试玩账号!');
	    	exit();
	    }
		$pass1 = $this->input->post('zcpwd1');
		$pass2 = $this->input->post('zcpwd2');
		$username = $this->input->post('zcname');

		if (empty($pass1) || empty($pass2) || empty($username)) {
		    message('请填写完整表单!');
		}

		$password = md5(md5($pass1));
		$password2 = md5(md5($pass2));
		$pk_token = $this->input->post('pk_token');
		$logintime = date("Y-m-d H:i:s");
		if ($password != $password2) {
			message('两次输入密码不一致!');exit;
		}

		$this->tokenCk_form($pk_token);   //验证token，防止恶意注入

		//获取测试代理商id
		$agent_demo = $this->get_agent('3');
		$shi_S = $this->Register_web_model->add_shiwan($username,$password,$agent_demo);

		if ($shi_S) {
			$_SESSION["shiwan"] = 1;
			$_SESSION["uid"] = $shi_S;
			$_SESSION["username"] = $username;
			$_SESSION['agent_id'] = $agent_demo['id'];
			$_SESSION['ua_id'] = $agent_demo['pid'];
    		$_SESSION['sh_id'] = $agent_demo['sh_id'];
			$_SESSION['level_id'] = 0;
			header("Content-type: text/html; charset=utf-8");
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			echo "<script>alert(\"恭喜您，注册成功。\");top.location.href='",URL,"';</script>";
			exit();
		} else {
			message("由于网络堵塞，本次注册失败。\\n请您稍候再试，或联系在线客服。");
		}
	}

    //会员注册
    public function join_member(){
    	    $pk_token = $this->Register_web_model->getPKtoken();
		    $_SESSION['PKtoken']= $pk_token;
		    $_SESSION['PKtokenState']= 1;
		 	//获取后台用户注册设定
			$map = array();
			$map['table'] = 'k_user_reg_config';
			$map['where']['site_id'] = SITEID;
			$map['where']['index_id'] = INDEX_ID;
			$result = array();
			$result = $this->Register_web_model->rfind($map);
			if ($result['is_work'] == 0 || empty($result)) {
				echo "<script>alert('系统禁用了用户注册功能,请联系管理员!');parent.location.href='/';</script>";exit;
			}

			$is_intrs = $result['is_intrs'];//介绍人是否显示
		    $is_code = $result['is_code'];//验证码是否演示

			$result2 = $this->user_reg_arr($result);

			//代理ID
			$agent_d = $this->get_agent('1');
			$data = array();
			$data =  $this->reg_iword(9);
			$data['is_intrs'] = $is_intrs;

			$this->add('intr',$agent_d['intr']);
			$this->add("data", $data);       //注册文案信息
			$this->add("data2", $this->reg_iword(18));       //开户协议信息
			$this->add("list", $result2);                  //是否显示和必填的注册信息
			$this->add("result", $result);                  //是否显示代理人和验证码
			$this->add('is_code',$is_code);
			$this->add("pk_token", $pk_token);
			$this->display("web/join_member.html");
		}

	//会员注册处理
	public function join_member_do(){
		$zcname = $this->input->post('zcname');//账号
		$password = $this->input->post('zcpwd1');
		$zcturename = $this->input->post('zcturename');//姓名
		//取款密码
		$address1 = $this->input->post('address1');
		$address2 = $this->input->post('address2');
		$address3 = $this->input->post('address3');
		$address4 = $this->input->post('address4');
    	//出生日期
		$birthday1 = $this->input->post('birthday1');
		$birthday2 = $this->input->post('birthday2');
		$birthday3 = $this->input->post('birthday3');
		$birthday = $birthday1.'-'.$birthday2.'-'.$birthday3;

		//扩充字段
		$email = $this->input->post('email');
		$passport = $this->input->post('passport');
		$mobile = $this->input->post('mobile');
		$qq = $this->input->post('qq');
		$pk_token = $this->input->post('pk_token');
		$this->tokenCk_form($pk_token);   //验证token，防止恶意注入
		//var_dump($_POST);die;
	    //表单验证
	    $this->load->library('form_validation');
	    $this->form_validation->set_rules('zcname', 'zcname', 'required|min_length[4]|max_length[11]');
	    $this->form_validation->set_rules('zcpwd1', 'zcpwd1', 'required');
	    $this->form_validation->set_rules('zcturename', 'zcturename', 'required');
	    $this->form_validation->set_rules('address1', 'address1', 'required');
	    $this->form_validation->set_rules('address2', 'address2', 'required');
	    $this->form_validation->set_rules('address3', 'address3', 'required');
	    $this->form_validation->set_rules('address4', 'address4', 'required');
	    $this->form_validation->set_rules('birthday1', 'birthday1', 'required');
	    $this->form_validation->set_rules('birthday2', 'birthday2', 'required');
	    $this->form_validation->set_rules('birthday3', 'birthday3', 'required');

	    if ($this->form_validation->run() == FALSE) {
	    	message('请完善表单信息,谢谢!');
	    }
           
	    $zcname = strtolower($zcname);//大写转小写
            if (!preg_match("/^[a-zA-Z0-9]{4,11}$/", $zcname)) {  //账号格式不正确
                message('用户名只能是字母与数字的组合！');
            }
	    //判断账号
	    $map_is = array();
	    $map_is['table'] = 'k_user';
	    $map_is['select'] = 'uid';
	    $map_is['where']['username'] = $zcname;
	    $map_is['where']['site_id'] = SITEID;
	    if($this->Register_web_model->rfind($map_is)){
	        message('该账号已被人使用,请重新注册,谢谢!');
	        exit();
	    }

	    //判断姓名，QQ,手机，邮箱是否重复
	    $map_isn = array();
	    $map_isn['table'] = 'k_user_reg_config';
	    $map_isn['select'] = 'is_name,is_qq,is_tel,is_mail,is_code';
	    $map_isn['where']['index_id'] = INDEX_ID;
	    $map_isn['where']['site_id'] = SITEID;
	    $is_name = $this->Register_web_model->rfind($map_isn);
    	if (!empty($is_name)) {
        	if (empty($is_name['is_name'])) {
          	$isstate = $this->Register_web_model->repeat_register('pay_name',$zcturename);
          		if (!empty($isstate)) {
              		message('注册姓名已重复，请联系客服!');
              		exit();
          		}
    		}

			//qq是否重复
			if (empty($is_name['is_qq']) && !empty($qq)) {
				$isqqstate = $this->Register_web_model->repeat_register('qq',$qq);
				if (!empty($isqqstate)) {
					message('qq号已重复，请联系客服!');
					exit();
				}
			}
			//手机是否重复
			if (empty($is_name['is_tel']) && !empty($mobile)) {
				$istelstate = $this->Register_web_model->repeat_register('mobile',$mobile);
				if (!empty($istelstate)) {
					message('手机号已重复，请联系客服!');
					exit();
				}
			}
			//邮箱是否重复
			if (empty($is_name['is_mail']) && !empty($email)) {
				$ismailstate = $this->Register_web_model->repeat_register('email',$email);
				if (!empty($ismailstate)) {
					message('注册邮箱已重复，请联系客服!');
					exit();
				}
			}
	  	}

	  	if (!empty($email)) {
			if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
				message('您输入邮箱不正确，请重新输入邮箱!');
				exit;
			}
		}

		if (!empty($qq)) {
			if (!preg_match("/^[1-9][0-9]{4,9}$/", $qq)) {
				message('您输入QQ号不正确，请重新输入QQ号!');
				exit;
			}
		}

		//会员所属代理商
		$deAgent = $this->get_agent('2');
		//获取IP信息
		$userIP = $this->get_ip();
	    $address = $this->get_address($userIP);
		//注册优惠
		$reg_state = $this->reg_dis($deAgent['id'],$deAgent['pid'],$userIP);
		//注册默认层级
		$level_id = $this->user_level();

		//会员注册信息
		$dataa = array();
		$dataa['username']    = $zcname;
		$dataa['password']    = md5(md5($password));
		$dataa['money']       = empty($reg_state['money'])?0:$reg_state['money'];
		$dataa['qk_pwd']      = $address1.$address2.$address3.$address4;
		$dataa['mobile']      = $mobile;
		$dataa['email']       = $email;
		$dataa['index_id']    = INDEX_ID;
		$dataa['reg_ip']      = $userIP;
		$dataa['login_ip']    = $userIP;
		$dataa['login_time']  = date("Y-m-d H:i:s");
		$dataa['pay_name']    = $zcturename;
		$dataa['lognum']      = 1;
		$dataa['reg_address'] = $address;
		$dataa['qq']          = $qq;
		$dataa['site_id']     = SITEID;
		$dataa['agent_id']    = $deAgent['id'];
		$dataa['ua_id']       = $deAgent['pid'];
		$dataa['sh_id']       = $deAgent['sh_id'];
		$dataa['level_id']    = $level_id;
		$dataa['passport']    = $passport;
		$dataa['birthday']    = $birthday;

    	$reg_state = $this->Register_web_model->user_reg($dataa,$reg_state);
		if($reg_state){
        	message("欢迎您的加入!", URL."/index.php/webcenter/login/login_info");
		}else{
			message("注册失败,请重新注册");
		}
	}


	//获取代理ID
	public function get_agent($type) {
		$map = array();
		if ($type == '3') {
		    $map['where']['is_demo'] = 1;
		}else{
		    //匹配代理推广域名
			$this->Register_web_model->agent_domain_intr();

			if(!empty($_SESSION['intr'])){
	            $map['where']['intr'] = $_SESSION['intr'];
			}else{
		        $map['where']['is_default'] = 1; //表示站点默认代理商
			}
			$map['where']['index_id'] = INDEX_ID;
			$map['where']['is_demo'] = 0;
		}

		$map['table'] = 'k_user_agent';
		$map['select'] = 'id,intr,pid';
		$map['where']['site_id'] = SITEID;
		$map['where']['agent_type'] = 'a_t';

		$deAgent = $this->Register_web_model->rfind($map);

		$_SESSION['intr'] = $deAgent['intr'];

		//获取股东id
		if ($type == 2 || $type == 3) {
			unset($map['where']['intr']);
			unset($map['where']['is_default']);
			unset($map['where']['is_demo']);
			$map['where']['agent_type'] = 'u_a';
			$map['where']['id'] = $deAgent['pid'];
			$sh_id = $this->Register_web_model->rfind($map);
		    $deAgent['sh_id'] = $sh_id['pid'];
		}
		return $deAgent;
	}

	//注册赠送优惠
	public function reg_dis($agent_id,$pid,$ip) {
		//获取注册用户所在IP
		$this->db->from('k_user');
		$this->db->where('index_id',INDEX_ID);
		$this->db->where('site_id',SITEID);
		$this->db->where('reg_ip',$ip);
		$isn_ip = $this->db->get()->row_array();

		//获取股东
		$this->db->from('k_user_agent');
		$this->db->where('index_id',INDEX_ID);
		$this->db->where('site_id',SITEID);
		$this->db->where('id',$pid);
		$s_h = $this->db->get()->row_array();

		//代理 总代 股东的agent_id
		$map = array();
		$map = array($s_h['pid'],$agent_id,$pid);
		$this->db->from('k_user_agent_dis');
		$this->db->where('index_id',INDEX_ID);
		$this->db->where('site_id',SITEID);
		$this->db->where('discount_money >',0);
		$this->db->where_in('agent_id',$map);
		$this->db->order_by('agent_type','ASC');
		$data = $this->db->get()->result_array();

		if (!empty($data)){
			if($data[0]["agent_type"] =="a_t"){
				if ($data[0]["is_ip"] ==0||empty($isn_ip)){
					$reg_d['money'] = $data[0]['discount_money']; //注册赠送金额
					$reg_d['d_bet'] = $data[0]['discount_bet']; //打码量
				}else{
					$reg_d['money'] = $reg_d['d_bet'] = 0;
				}
				return $reg_d;
			}else{
			if($data[0]["agent_type"] =="s_h" &&!empty($data[1])){
				if ($data[1]["is_ip"] ==0||empty($isn_ip)){
					$reg_d['money'] = $data[1]['discount_money']; //注册赠送金额
					$reg_d['d_bet'] = $data[1]['discount_bet']; //打码量
				}else{
					$reg_d['money'] = $reg_d['d_bet'] = 0;
				}
				return $reg_d;

				}else{
					if ($data[0]["is_ip"] ==0||empty($isn_ip)){
						$reg_d['money'] = $data[0]['discount_money']; //注册赠送金额
						$reg_d['d_bet'] = $data[0]['discount_bet']; //打码量
					}else{
						$reg_d['money'] = $reg_d['d_bet'] = 0;
					}
					return $reg_d;
				}
			}
		}

		//查询站点
		$this->db->from('k_user_apply');
		$this->db->where('index_id',INDEX_ID);
		$this->db->where('site_id',SITEID);
		$reg3 = $this->db->get()->row_array();
		if ($reg3) {
			if ($reg3["is_ip"] ==0||empty($isn_ip)){
				$reg_d['money'] = $reg3['discount_money']; //注册赠送金额
				$reg_d['d_bet'] = $reg3['discount_bet']; //打码量
			}else{
				$reg_d['money'] = $reg_d['d_bet'] = 0;
			}
			return $reg_d;
		}

	}

    //获取用户层级
	public function user_level(){
		$map = array();
		$map['table'] = 'k_user_level';
		$map['select'] = 'id';
		$map['where']['index_id'] = INDEX_ID;
		$map['where']['site_id'] = SITEID;
		$map['where']['is_default'] = 1;
		$data = $this->Register_web_model->rfind($map);
		return $data['id'];
	}

	//会员注册信息组合
	public function user_reg_arr($arr = array()){
		$list = array();
    foreach ($arr as $k => $v) {
			$user_reg_data = explode('-', $v);

			if (isset($user_reg_data) && $user_reg_data[1] != '') {
				if ($user_reg_data[0] == 0) {
					$list[$k]['name'] = $k; //email
					if ($k == 'email') {
						$list[$k]['name_zh'] = '邮箱';
						$list[$k]['info'] = '请填写您的真实邮箱';
					} elseif ($k == 'qq') {
						$list[$k]['name_zh'] = 'QQ';
						$list[$k]['info'] = '请填写您的真实QQ';
					} elseif ($k == 'passport') {
						$list[$k]['name_zh'] = '身份证';
						$list[$k]['info'] = '健康博彩，未成年人请勿参与';
					} elseif ($k == 'mobile') {
						$list[$k]['name_zh'] = '手机号码';
						$list[$k]['info'] = '此为您取回登入密码的唯一途径，请注意安全，务必真实！';
					}
					if ($user_reg_data[1] == 1) {
						$list[$k]['status'] = '*'; //必填
					}
				}
			}
		}
		return $list;
	}

    //代理注册 固定页面
    public function join_agent(){
		//获取代理申请配置
		$map = array();
		$map['table'] = 'k_user_agent_config';
		$map['select'] = 'is_email,is_qq,is_zh_name,is_en_name,is_card,is_phone,is_payname,from_url_form,other_method_form';
		$map['where']['index_id'] = INDEX_ID;
		$map['where']['site_id'] = SITEID;
		$result = $this->agent_reg_arr($this->Register_web_model->rfind($map));
		$this->add("data", $this->reg_iword(10));
		$this->add("config", $result);
		$this->display("web/join_agent.html");
	}

	//代理注册处理
	public function join_agent_do(){
    $mapd = array();
    $mapd['agent_login_user'] = $this->input->post('r_user_form');//账号
		$agent_pwd = $this->input->post('password_form');
		$mapd['agent_name'] = $this->input->post('is_zh_name');//昵称
		$yzm_form = $this->input->post('yzm_form');
		//取款密码
		/* $safe_pass1 = $this->input->post('safe_pass1');
		$safe_pass2 = $this->input->post('safe_pass2');
		$safe_pass3 = $this->input->post('safe_pass3');
		$safe_pass4 = $this->input->post('safe_pass4');
		$mapd['safe_pass'] = $safe_pass1.$safe_pass2.$safe_pass3.$safe_pass4; */

    //银行信息
		$mapd['bankid'] = $this->input->post('bank_type_form');//银行方式
		$mapd['bankno'] = $this->input->post('bank_account_form');//卡号
		$mapd['province'] = $this->input->post('bank_province_form');
		$mapd['city'] = $this->input->post('bank_county_form');

		//扩充字段
		$mapd['email'] = $this->input->post('is_email');
		$mapd['mobile'] = $this->input->post('is_phone');
		$mapd['qq'] = $this->input->post('is_qq');

		$mapd['from_url'] = $this->input->post('from_url_form');//推广
		$mapd['other_method'] = $this->input->post('other_method_form');
		$mapd['personalid'] = $this->input->post('is_card');
		$mapd['en_name'] = $this->input->post('is_en_name');
		$mapd['realname'] = $this->input->post('is_payname');//真实姓名

    //表单验证
    $this->load->library('form_validation');
    $this->form_validation->set_rules('r_user_form', 'r_user_form', 'required|min_length[4]|max_length[11]');
    $this->form_validation->set_rules('password_form', 'password_form', 'required');
    //$this->form_validation->set_rules('is_zh_name', 'is_zh_name', 'required');
    /* $this->form_validation->set_rules('safe_pass1', 'safe_pass1', 'required');
    $this->form_validation->set_rules('safe_pass2', 'safe_pass2', 'required');
    $this->form_validation->set_rules('safe_pass3', 'safe_pass3', 'required');
    $this->form_validation->set_rules('safe_pass4', 'safe_pass4', 'required'); */
    $this->form_validation->set_rules('yzm_form', 'yzm_form', 'required');
    $this->form_validation->set_rules('bank_account_form', 'bank_account_form', 'required');

    if ($this->form_validation->run() == FALSE) {
    	message('请完善表单信息,谢谢!');
    }

    //判断账号是否存在
    $map_a = array();
    $map_a['table'] = 'k_user_agent';
    $map_a['where']['agent_login_user'] = $mapd['agent_login_user'];
    $map_s = array();
    $map_s['table'] = 'sys_admin';
    $map_s['where']['login_name_1'] = $mapd['agent_login_user'];
    if ($this->Register_web_model->rfind($map_a) || $this->Register_web_model->rfind($map_s)) {
        message("帐号已存在,请重新注册，谢谢！");
    }

    //特定数据补充
    $mapd['site_id'] = SITEID;
    $mapd['index_id'] = INDEX_ID;//前台ID
		$mapd['add_date'] = date('Y-m-d H:i:s');
		$mapd['is_delete'] = 5;
		$mapd['is_apply'] = 1;
		$mapd['ip'] = $this->get_ip();
		$mapd['do_url'] = $_SERVER["HTTP_HOST"]; //提交网址
		$mapd['agent_pwd'] = md5(md5($agent_pwd));

		if ($this->Register_web_model->radd('k_user_agent',$mapd)) {
        message("您的申请已经提交，请等待客服人员联系和确认！");
    }else{
    	message('网络错误，申请提交失败','./');
    }
	}

	//代理注册信息组合
	public function agent_reg_arr($arr = array()){
		$clist = array();
		foreach ($arr as $k => $v) {
	        $is_on = explode('-',$v);
	        if ($is_on[0] == '0') {
	            $clist[$k]['name'] = $k;
	            switch ($k) {
	            	case 'is_email':
	            		$clist[$k]['name_zh'] = '邮箱';
						$clist[$k]['cue'] = '请输入邮箱!';
	            		break;
	            	case 'is_qq':
	            		$clist[$k]['name_zh'] = 'qq';
						$clist[$k]['cue'] = '请输入qq!';
	            		break;
	            	case 'is_zh_name':
	            		$clist[$k]['name_zh'] = '中文昵稱';
						$clist[$k]['cue'] = '请输入中文昵稱!';
	            		break;
	            	case 'is_en_name':
	            		$clist[$k]['name_zh'] = '英文昵稱';
						$clist[$k]['cue'] = '请输入英文昵稱!';
	            		break;
	            	case 'is_card':
	            		$clist[$k]['name_zh'] = '身份證';
						$clist[$k]['cue'] = '请输入身份證號!';
	            		break;
	            	case 'is_phone':
	            		$clist[$k]['name_zh'] = '手机号码';
						$clist[$k]['cue'] = '此为您取回登入密码的唯一途径,请注意安全,务必真实!';
	            		break;
	            	case 'is_payname':
	            		$clist[$k]['name_zh'] = '真實姓名';
						$clist[$k]['cue'] = '必须与您的银行帐户名称相同，否则不能出款!!';
	            		break;
	            	case 'from_url_form':
	            		$clist[$k]['name_zh'] = '推广网址';
						$clist[$k]['read'] = '输入您的网址全地址不包含http://，仅填写您的网址即可。';
	            		break;
	            	case 'other_method_form':
	            		$clist[$k]['name_zh'] = '其他方式';
						$clist[$k]['read'] = '* 若您有其他的推广平台，可以在此输入可文字描述！';
	            		break;

	            }
	            if ($is_on[1] == 1) {
					$clist[$k]['status'] = '*'; //必填
				} else {
					$clist[$k]['status'] = ''; //选填
				}
	        }

		}
		return $clist;
	}



	//注册文案信息 9会员注册  18开户协议
	public function reg_iword($type){
		//文案信息
		$map_d = array();
		if($_SESSION['ty'] == 9 || $_SESSION['ty'] == 10 || $_SESSION['ty'] == 18){
			$map_d['table'] = 'info_reg_edit';
			$map_d['where']['type'] = $_SESSION['ty'];
		}else{
			$map_d['table'] = 'info_reg_use';
			$map_d['where']['type'] = $type;
		}
		$map_d['select'] = 'title,content';
		$map_d['where']['site_id'] = SITEID;
		$map_d['where']['index_id'] = INDEX_ID;
		$map_d['where']['type'] = $type;
		return $this->Register_web_model->rfind($map_d);
	}

	//验证token
	function tokenCk_form($pk_token){
	   if ($_SESSION['PKtokenState'] == '1') {
	   	  //如果开启
		  if ($pk_token == '' || $_SESSION['PKtoken'] =='' || $pk_token != $_SESSION['PKtoken']){
			 exit('网络故障，申请失败，请刷新页面重试');
		   }
	   }
	}

}
