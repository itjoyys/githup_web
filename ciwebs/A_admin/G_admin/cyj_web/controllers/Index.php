<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('Index_model');
	}

	public function index()
	{

		$this->display('index.html');
	}

	public function main(){
		$this->display('main.html');
	}

	public function meau(){
		$mtime  = date('Y/m/d').' '.date('H:i:s');
        $meau = $this->meau_show(return_meau(),$_SESSION['guanliyuan']);
        //var_dump($meau);die;
		$this->add('login_name',$_SESSION['login_name']);
		$this->add('login_name_1',$_SESSION['login_name_1']);
		$this->add('mtime',$mtime);
		$this->add('data',$meau);
		$this->display('meau.html');
	}


	    //导航拼接
	function meau_show($arr,$quanxian){
		$data = array();
        foreach ($arr as $key => $val) {
		    foreach ($val['val'] as $k => $v) {
			   /* if ($quanxian != 1) {
                    //栏目标识$key.$k;
                    p($arr);
			        if (strpos($_SESSION['guanliyuan'], $key.$k) !== false) {
                        $data[$key][] = array('name'=>$v,'url'=>$val['url'][$k],'type'=>$val['type'][$k]);
			        }
			    }else{
			    	//p($arr);
			        $data[$key][] = array('name'=>$v,'url'=>$val['url'][$k],'type'=>$val['type'][$k]);
			    }
	*/

				if($quanxian != 1){
				    if($v == "代理管理"){
				    	continue;
				    }
				   $data[$key][] =  array('name'=>$v,'url'=>$val['url'][$k],'type'=>$val['type'][$k]);
				}else{
					$data[$key][] =  array('name'=>$v,'url'=>$val['url'][$k],'type'=>$val['type'][$k]);
				}


			}
		}
		//p($data);
		return $data;
	}

	//修改密码
	public function set_pwd(){
		$this->add('login_name',$_SESSION['login_name']);
	    $this->display('set_pwd.html');
	}

	public function set_pwd_do(){
		$password = $this->input->post('password');
		$password2 = $this->input->post('password2');
		if ($password != $password2) {
		    showmessage('两次密码不相同','back',0);
		}

        $map = array();
        $map['table'] = 'sys_admin';
        $map['where']['uid'] = $_SESSION['adminid'];
        $map['where']['site_id'] = $_SESSION['site_id'];
        if ($this->Index_model->rupdate($map,array('login_pwd'=>md5(md5($password))))) {
    	    $log['log_info'] = '修改登录密码';
            $this->Index_model->Syslog($log);
            showmessage('操作成功','back');
        }else{
        	showmessage('操作失败','back',0);
        }
	}

	//退出
	public function login_out(){
		$this->Index_model->redis_del_user();
        session_destroy();
        showmessage('成功安全退出','../../login.html');
	}

}
