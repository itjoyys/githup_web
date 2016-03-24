<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Member_reg extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->login_check();
		$this->load->model('account/Member_reg_model');
	}

	public function index(){
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $map['table'] = 'k_user_reg_config';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $data = $this->Member_reg_model->rfind($map);
        //多站点判断
        if (!empty($_SESSION['index_id'])) {
            $this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Member_reg_model->select_sites()));
        }
		$apply['table'] = 'k_user_apply';
        $apply['where']['site_id'] = $_SESSION['site_id'];
        $apply['where']['index_id'] = $index_id;
        $result = $this->Member_reg_model->rfind($apply);

        $this->add('index_id',$index_id);
        $this->add('siteid',$_SESSION['site_id']);
        $this->add('data',$data);
		$this->add('result',$result);
        $this->display('account/member_reg.html');
	}

    public function member_reg_do(){
        $reg_data = array();
        $reg_data['is_work'] = $this->input->post('is_work');

        $reg_data['email'] = $this->input->post('email');
        $is_email = $this->input->post('is_email');
        $is_email = !empty($is_email)?$is_email:'0';
        $reg_data['email'] .= '-'.$is_email;

        $reg_data['passport'] = $this->input->post('passport');
        $is_passport = $this->input->post('is_passport');
        $is_passport = !empty($is_passport)?$is_passport:'0';
        $reg_data['passport'] .= '-'.$is_passport;

        $reg_data['qq'] = $this->input->post('qq');
        $is_qqs = $this->input->post('is_qqs');
        $is_qqs = !empty($is_qqs)?$is_qqs:'0';
        $reg_data['qq'] .= '-'.$is_qqs;

        $reg_data['mobile'] = $this->input->post('mobile');
        $is_mobile = $this->input->post('is_mobile');
        $is_mobile = !empty($is_mobile)?$is_mobile:'0';
        $reg_data['mobile'] .= '-'.$is_mobile;

        $is_reg = $this->input->post('is_reg');
        $index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $reg_data['is_name'] = $this->input->post('is_name');
        $reg_data['is_banknum'] = $this->input->post('is_banknum');
        $reg_data['is_intrs'] = $this->input->post('is_intrs');
        $reg_data['is_qq'] = $this->input->post('is_qq');
        $reg_data['is_mail'] = $this->input->post('is_mail');
        $reg_data['is_tel'] = $this->input->post('is_tel');
        $reg_data['is_code'] = $this->input->post('is_code');
        //手机注册是否单页面
        $reg_data['is_wap_one'] = $this->input->post('is_wap_one');

        if (empty($is_reg)) {
            //添加
            $reg_data['site_id'] = $_SESSION['site_id'];
            $reg_data['index_id'] = $index_id;
            if($this->Member_reg_model->radd('k_user_reg_config',$reg_data)){
                $log['log_info'] = '设置会员注册设定,ID：'.$index_id;
                $this->Member_reg_model->Syslog($log);
                showmessage('设置成功','back');
            }else{
                showmessage('设置失败','back',0);
            }
        }else{
            $map['table'] = 'k_user_reg_config';
            $map['where']['site_id'] = $_SESSION['site_id'];
            $map['where']['id'] = $is_reg;
            //编辑
            if($this->Member_reg_model->rupdate($map,$reg_data)){
                $log['log_info'] = '设置会员注册设定,ID：'.$index_id;
                $this->Member_reg_model->Syslog($log);
                showmessage('更新成功','back');
            }else{
                showmessage('更新失败','back',0);
            }
        }
    }

	public function reg_discount_set(){
		//优惠设定
		$index_id = $this->input->post('index_id');
		$result_id = $this->input->post('result_id');
		$set = array();
		$set['is_ip'] = $this->input->post('is_ip');
        $set['begin_date'] = $this->input->post('start_date');
        $set['end_date'] = $this->input->post('end_date');
	    $set['discount_money']=$this->input->post('discount_money');
	    $set['discount_bet']=$this->input->post('discount_bet');
		if(empty($result_id)){
			//添加
			 $set['site_id'] = $_SESSION['site_id'];
			 $set['index_id'] = $index_id;
			 if($this->Member_reg_model->radd("k_user_apply",$set)){
				$log['log_info'] = '设置会员注册优惠设定,ID：'.$index_id;
                $this->Member_reg_model->Syslog($log);
				showmessage('设定成功','back');
             }else{
                showmessage('设定失败','back',0);
             }
		}else{
			//编辑
			$edit['table'] = 'k_user_apply';
            $edit['where']['site_id'] = $_SESSION['site_id'];
            $edit['where']['id'] = $result_id;
			if($this->Member_reg_model->rupdate($edit,$set)){
				$log['log_info'] = '设置会员注册优惠设定,ID：'.$index_id;
                $this->Member_reg_model->Syslog($log);
                showmessage('设定成功','back');
            }else{
                showmessage('设定失败','back',0);
            }
		}
	}
}