<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct() {
		parent::__construct();
		$this->load->model('sports/User_model');
		$this->token_data = $this->get_token_info_is_login(); 
	}
	/*
     UID,USERNAME,AGENTID,LEVELID,SHIWAN
	*/
	
	public function getuserinfo(){
		$d=$this->token_data; 
		$this->get_user($d['uid'],'uid,username,money');
		
	}
	public function get_user_money($uid){

	}
	public function get_user($uid,$field){
        $d['login']=2;
        if($uid)
        {
        	$map = array();
	       
	        $map['field'] = $field;
            $map['uid'] = $uid;
            $map['site_id'] = SITEID;
            $map['index_id'] = INDEX_ID;
		    $d=$this->User_model->get_user($map);
		    $d['login']=1;
		    $d['token']=$this->token_data['token'];
            $d['site_id']=$this->token_data['siteid'];
        } 
        echo json_encode($d);
	}
}