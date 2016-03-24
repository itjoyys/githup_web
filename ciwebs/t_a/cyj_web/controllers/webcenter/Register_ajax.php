<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_ajax extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('webcenter/Register_web_model');
	}

    //会员注册账号检测 验证码检测
	public function user_ajax_check()
	{
		$ajax = $this->input->get('ajax');
        if($this->input->is_ajax_request() && $ajax == 'CheckDBuser'){
		    $map = array();
			$map['table'] = 'k_user';
			$map['select'] = 'uid';
			$map['where']['site_id'] = SITEID;
			$map['where']['username'] = $this->input->get('user');

			if ($this->Register_web_model->rfind($map)) {
			    exit(false);
			}else{
				exit(true);
			}
		}elseif($this->input->is_ajax_request() && $ajax == 'CheckCode'){
			$code = $this->input->get('a_idcode');
			if ($code == $_SESSION['code']) {
		        exit(true);
		    }else{
		    	exit(false);
		    }
		}
	}

    //代理注册ajax检测
    public function agent_ajax_check()
	{
		$ajax = $this->input->get('ajax');
        if($this->input->is_ajax_request() && $ajax == 'Check_agent_user'){
		    $map = array();
			$map['table'] = 'k_user_agent';
			$map['where']['agent_login_user'] = $this->input->get('user');

			$maps = array();
			$maps['table'] = 'sys_admin';
			$maps['where']['login_name_1'] = $this->input->get('user');

			if ($this->Register_web_model->rfind($map) || $this->Register_web_model->rfind($maps)) {
			    exit(json_encode(0));
			}else{
				exit(json_encode(1));
			}
		}elseif($this->input->is_ajax_request() && $ajax == 'CheckCode'){
			$code = $this->input->get('a_idcode');
			if ($code == $_SESSION['code']) {
		        exit(json_encode(1));
		    }else{
		    	exit(json_encode(0));
		    }
		}
	}
}
