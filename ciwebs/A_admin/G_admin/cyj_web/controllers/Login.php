<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('Index_model');
	}

	    //登陆处理
	public function login_do(){
		$login_name_1 = trim($this->input->post('LoginName'));
		$login_pwd = $this->input->post('LoginPassword');
		$yzm_code = $this->input->post('CheckCode');
		if (empty($login_name_1) || empty($login_pwd) || empty($yzm_code)) {
		    showmessage('请完善表单','../../login.html');
		}

		if (strcasecmp($yzm_code,$_SESSION['verifCode']) != 0) {
		    showmessage('验证码错误','../../login.html');
		}

        $site_id = $this->Index_model->login($login_name_1,$login_pwd);


        switch ($site_id) {
        	case 'F1':
        		showmessage('账号不存在','../../login.html',0);
        		break;
        	case 'F2':
        		showmessage('账号已被暂停使用','../../login.html',0);
        		break;
        	case 'F3':
        	    showmessage('登录成功','../../');
        		break;
        	case 'F5':
        	    showmessage('账号密码不匹配','../../login.html',0);
        		break;
        	default:
        		showmessage('登录异常','../../login.html');
        		break;
        }
	}
}
