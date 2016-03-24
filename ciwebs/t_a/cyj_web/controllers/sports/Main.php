<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {
    public function __construct() {
		parent::__construct();
		 
	}
    //整体
	public function index(){
		$this->add('token',$this->input->post_get('token'));
		$this->add('uid',$this->input->post_get('uid'));
		$this->add('vlock',date('Y/m/d H:i:s'));
		$vlockjs='var dd2=new Date("'.date('Y/m/d H:i:s').'"); setInterval("RefTime()",1000);';
		$this->add('vlockjs',$vlockjs);
		$this->display('sports/main.html');
	}
	public function bet(){
		$user = $this->get_token_info_is_login();
		$p=$this->input->post(['status','p','dsorcg','betpage']);
		 
		$this->load->model('sports/user_model');
		$d=$this->user_model->getbet($p,$user);
		//if($p['bettype'])
		$d['menu']='';
		echo json_encode($d);
	}
	public function matchnum(){
		$this->load->model('sports/match_model');
		$d=$this->match_model->matchnum($p,$user); 
		echo json_encode($d);
	}
	//另开页面的比赛结果
        public function result(){
            $select = '';
            for($i=0;$i<7;$i++){
                $time = strtotime("-$i days");
                $date = date('Y-m-d',$time);
                $select .= "<option value='$i'>$date</option>";
            }
            $this->add('select',$select);
            $this->display('sports/result.html');
	}
	 


}
