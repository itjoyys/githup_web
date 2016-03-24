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
		$site_id = $_SESSION['site_id'];
		$notices = $this->Index_model->get_one_notice();
		$this->add('notices',$notices);
		$this->add('site_id',$site_id);
		$this->display('index.html');
	}

	public function main(){
		$this->display('main.html');
	}

	public function dialogo(){
		$notices = $this->Index_model->get_one_notice();
		$this->add('notices',$notices);
		$this->display('dialogo.html');
	}

	public function meau(){
		$mtime  = date('Y/m/d').' '.date('H:i:s');
        $meau = $this->meau_show(return_meau(),$_SESSION['quanxian']);
        //顶级栏目判断
        $m_type = array('a','b','c','d','e','f');
        foreach ($m_type as $key => $val) {
            $$val = key_exists($val,$meau);
        }
		$this->add('site_id',$_SESSION['site_id']);
		$this->add('login_name',$_SESSION['login_name']);
		$this->add('login_name_1',$_SESSION['login_name_1']);
		$this->add('mtime',$mtime);
		$this->add('data',$meau);
		$this->add('a',$a);
		$this->add('b',$b);
		$this->add('c',$c);
		$this->add('d',$d);
		$this->add('e',$e);
		$this->add('f',$f);
		$this->display('meau.html');
	}

	//获取在线人数
	public function user_online(){
		$count = $this->Index_model->get_online_count();
		if (!empty($count)) {
			echo json_encode($count);
		}else{
			exit(json_encode(0));
		}
	}
    //导航拼接
	function meau_show($arr,$quanxian){
		$data = array();
		if ($_SESSION['site_id'] != 't') {
		    //unset($arr['f']['val'][8]);
		}
        foreach ($arr as $key => $val) {
		    foreach ($val['val'] as $k => $v) {
			    if ($quanxian != 'sadmin') {
                    //栏目标识$key.$k;
			        if (strpos($_SESSION['quanxian'], $key.$k) !== false) {
			        	 if ($key.$k == 'd3' && $_SESSION['site_id'] == 't') {
			        	     //$val['url'][$k] = '../result/odds_set2/index_fc';
			        	 }

                        $data[$key][] = array('name'=>$v,'url'=>$val['url'][$k],'type'=>$val['type'][$k]);
			        }
			    }else{
			    	if ($key.$k == 'd3' && $_SESSION['site_id'] == 't') {
		         	    //$val['url'][$k] = '../result/odds_set2/index_fc';
		         	}
			        $data[$key][] = array('name'=>$v,'url'=>$val['url'][$k],'type'=>$val['type'][$k]);
			    }


			}
		}
		return $data;
	}

	//资讯系统链接
	function site_info_url(){
		$surl = 'ssid='.$_SESSION['site_id'].'&ussd='.$_SESSION['login_name_1'].'&iid='.$_SESSION['index_id'].'&auid='.$_SESSION['adminid'];
		if ($_SESSION['site_id'] == 't') {
		    $siteurl = 't00';
		}else{
			$siteurl = $_SESSION['site_id'];
		}
		return 'http://siteinfo'.SITEINFO_ID.'.pk-gaming.com/index.php/site_info/index?sino='.base64_encode($surl).$siteurl;
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
