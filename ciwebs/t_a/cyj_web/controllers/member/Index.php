<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('member/Member_model');
		$this->load->model('Common_model');
		$this->Common_model->login_check($_SESSION['uid']);
	}
    //整体
	public function index()
	{
		$this->add('url',$this->input->get('url'));
		$this->display('member/index.html');
	}

	//头部
	public function mem_header(){
		$date = date('Y/m/d H:i:s');
		$this->add('date',$date);
       	$this->display('member/mem_header.html');
	}

	//主体
	public function mem_main(){
		$url = $this->input->get('url');
		$str = $this->Member_model->get_member_url($url);
	   	$this->add('url',$url);
	   	$this->add('str',$str);
	   	$this->display('member/mem_main.html');
	}

	//底部
	public function mem_footer(){
		$copyright = $this->Common_model->get_copyright();
		$this->add('copyright',$copyright);
        $this->display('member/mem_footer.html');
	}

	public function mem_left(){
		$this->add('url',$this->input->get('url'));
        $this->display('member/mem_left.html');
	}
	public function gray(){
        $this->display('member/gray.html');
	}


}
