<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('webcenter/Register_web_model');
		$this->load->model('Common_model');
		$this->load->library('Games');
	}

    //登录处理
	public function login_do()
	{
         //获取IP信息
        $userIP = $this->get_ip();
	    $address = $this->get_address($userIP);
	    $address = $address.'('.$userIP.')';

		$username = $this->input->post('username');
		$passwd = $this->input->post('password');
		$code = $this->input->post('vlcodes');
		 if ($code != $_SESSION['code']) {
		 	echo 5;
		     exit();
		 }
        $is_login = $this->Register_web_model->mem_login($username,$passwd,$userIP,$address);
        echo $is_login;
        exit();
	}

    //login_info
	public function login_info(){
		//查询公告配置，匹配用户
		$this->db->from('k_message');
		$this->db->where('site_id',SITEID);
		$this->db->where('index_id',INDEX_ID);
		$this->db->where('is_delete',0);
		$this->db->where('show_type',1);
		$this->db->order_by('add_time',"DESC");
		$data_notice = $this->db->get()->result_array();
		//获取用户层级
		$this->db->from('k_user');
		$this->db->where('username',$_SESSION['username']);
		$this->db->where('site_id',SITEID);
		$this->db->select('level_id');
		$uid = $this->db->get()->row_array();

		$data_mes = '';
		//判断用户是否符合要求弹出广告
		if (!empty($data_notice)) {
			foreach ($data_notice as $k => $v) {
				if(!empty($v) && $v != 'undefined'){

				$v['chn_simplified'] = htmlspecialchars_decode($v['chn_simplified']);
			    $v['chn_simplified'] = preg_replace("/<br \s*\/?\/>/i", "\n", $v['chn_simplified']);
			    $v['chn_simplified'] = str_replace("&lt;br /&gt;", "", $v['chn_simplified']);
			    $v['chn_simplified'] = str_replace("&amp;lt;br /&amp;gt;", "", $v['chn_simplified']);
			    $level = explode(",", $v['level_power']);	//层级要求
					$zduser = explode(",", $v['zduser']);	//指定用户名

					if(in_array("-1", $level) && in_array($_GET['username'], $zduser)){ //当用户符合 指定用户时弹窗
						$data_mes =	$data_mes.$v['chn_simplified'].'|';
					}elseif ( in_array($uid['level_id'], $level)){		//当用户 层级 符合时弹窗
						$data_mes =	$data_mes.$v['chn_simplified'].'|';
					}elseif (in_array("-2", $level)){	//全部用户弹窗
						$data_mes =	$data_mes.$v['chn_simplified'].'|';
					}
				}
			}
			$this->add("data_notice", $data_mes);
    	}
		$this->add('web_config',$this->Common_model->get_copyright());
        $this->display('web/login_info.html');
	}

	//登出
	public function login_out(){
        $this->setvideomoney($_SESSION["username"]);
        //更新会员登录
		$this->Register_web_model->redis_del_user();
		session_destroy();
		echo "<script>alert('成功安全退出!');top.location.href='/'</script>";exit;
	}

	public function setvideomoney($username){
        $games = new Games();
        $data = $games->SetKUserMoney($username);
    }

	public function rsa(){
        $games=new Games();
        $params=$_POST['param'];
        $key = $_POST['key'];
        $siteid = $_POST['siteid'];
        $username = $_POST['username'];
        $params= preg_replace('/[\s　]/', '+', $params);
        $rkey = $games->getKey($params);
        if($key!=$rkey){
            echo "no";
            exit();
        }
        $params =$games->decrypt($params);
        $result = json_decode(trim($params,"data="));
        $data_u = array();
		$video_config = $this->Register_web_model->get_video_config($siteid);
		foreach($video_config as $k=>$v){
			$temp = $v."balance";
			$status = $v."status";
			if(!empty($result->$status)){
			$data_u[$v.'_money'] = floatval($result->$temp);
			}
		}
       /*  if (!empty($result->ogstatus)) {
            $data_u['og_money'] = floatval($result->ogbalance);
        }
        if (!empty($result->agstatus)) {
            $data_u['ag_money'] = floatval($result->agbalance);
        }
        if (!empty($result->mgstatus)) {
            $data_u['mg_money'] = floatval($result->mgbalance);
        }
        if (!empty($result->ctstatus)) {
            $data_u['ct_money'] = floatval($result->ctbalance);
        }
        if (!empty($result->bbinstatus)) {
            $data_u['bbin_money'] = floatval($result->bbinbalance);
        }
        if (!empty($result->lebostatus)) {
            $data_u['lebo_money'] = floatval($result->lebobalance);
        } */
        if(!empty($siteid)&&!empty($username)){
        	$this->db->where('site_id',$siteid);
			$this->db->where('username',$username);
			$this->db->set($data_u);
			$this->db->update('k_user');
        }
        echo "yes";
    }


}