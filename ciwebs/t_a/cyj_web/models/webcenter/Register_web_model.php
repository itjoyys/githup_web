<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Register_web_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}
    //会员注册处理
	function user_reg($arr,$reg_state){
		$this->db->trans_strict(FALSE);
        $this->db->trans_begin();

        //更新推广域名会员数量
        if ($_SESSION['domain_intr_id']) {
            $this->agent_domain_ucount();
            $arr['domain_id'] = $_SESSION['domain_intr_id'];
        }

           //写入会员推广id
        if ($_SESSION['uuno_agent_id']) {
            $arr['top_uid'] = $_SESSION['uuno_uid'];
            $arr['agent_id'] = $_SESSION['uuno_agent_id'];

            //更新会员推广人数
			$this->db->where(array('uid'=>$_SESSION['uuno_uid']));
			$this->db->where(array('site_id'=>SITEID));
			$this->db->set('spread_num','spread_num + 1',FALSE);
			$log_2 = $this->db->update('k_user');

        }

		$this->db->insert('k_user',$arr);
		$uid = $this->db->insert_id();

		//有优惠
		if ($arr['money'] > 0) {
			//发送会员消息
			$dataM              = array();
			$dataM['type']      = 2;
			$dataM['site_id']   = SITEID;
			$dataM['index_id']  = INDEX_ID;
			$dataM['uid']       = $uid;
			$dataM['level']     = 2;
			$dataM['msg_title'] = $arr['username'] . ',' . "會員注册贈送優惠";
			$dataM['msg_info']  = $arr['username'] . ',' . "會員注册贈送優惠" . $reg_state['money'] . "元 祝您游戏愉快！";
			$mid = $this->db->insert('k_user_msg',$dataM);

			//写入流水记录
			$dataR                 = array();
			$dataR['uid']          = $uid;
			$dataR['username']     = $arr['username'];
			$dataR['agent_id']     = $arr['agent_id'];
			$dataR['site_id']      = SITEID;
			$dataR['index_id']      = INDEX_ID;
			$dataR['cash_balance'] = $reg_state['money']; // 用户当前余额;
			$dataR['cash_date']    = date('Y-m-d H:i:s');
			$dataR['cash_type']    = 6;
			$dataR['cash_only']    = 1;
			$dataR['cash_do_type'] = 1;
			$dataR['discount_num'] = $arr['money']; // 金额
			$dataR['remark'] = $arr['username'] . ',' . "會員注册贈送優惠" . $reg_state['money'] . "元";
		    $cid = $this->db->insert('k_user_cash_record',$dataR);

	    	//写入稽核记录
			if ($reg_state['d_bet'] > 0) {
				$datae                  = array();
				$datae['username']      = $arr['username'];
				$datae['site_id']       = SITEID;
				$datae['uid']           = $uid;
				$datae['source_type']   = 5; //注册优惠
				$datae['begin_date']    = date('Y-m-d H:i:s');
				$datae['type']          = 1;
				$datae['is_zh']         = 1; //有综合稽核
				$datae['is_ct']         = 0;//无常态
				$datae['catm_give']     = $reg_state['money']; //存款优惠
				$datae['type_code_all'] = $reg_state['d_bet'] * $reg_state['money']; //综合稽核打码

				$aid = $this->db->insert('k_user_audit',$datae);
			}
		}

		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();

            //会员登录
	    	$this->user_login($uid,$arr['username'],$arr['reg_ip'],1);
	    	//历史记录
	    	$this->user_history_login($uid,$arr['username'],$arr['reg_ip'],$arr['reg_address'],1);

        //更新在线
            $this->redis_update_user();
            $this->load->model('sports/User_model','User_model');
            $_SESSION["token"]=$this->User_model->redis_update_token([$uid,$arr['username'],$arr['agent_id'],$arr['level_id'],0,SITEID,INDEX_ID,$arr['ua_id'],$arr['sh_id']]);
        	$_SESSION["uid"] = $uid;
			$_SESSION["username"] = $arr['username'];
			$_SESSION['agent_id'] = $arr['agent_id'];
			$_SESSION['level_id'] = $arr['level_id'];
			$_SESSION['ua_id'] = $arr['ua_id'];
			$_SESSION['sh_id'] = $arr['sh_id'];
			$_SESSION['shiwan'] = 0;
			$_SESSION['ssid'] = session_id();
            //更新在线注册的会员
			$this->new_reg_user();
			return TRUE;
	    }
	}

		   //当日注册会员
	 public function new_reg_user(){
        $redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
	    $redis_key = SITEID.'_'.date('Ymd').INDEX_ID;
		$redis->lpush($redis_key,$_SESSION["uid"]);
		//$redis->delete(SITEID.'_users_num');
	 }


	//获取注册信息是否重复
	public function repeat_register($name,$con){
		$map['table'] = 'k_user';
        $map['where'][$name] = $con;
        $map['where']['site_id'] = SITEID;
        return $this->rfind($map);
	}

	//获取token
	function getPKtoken() {
		$hash = md5(uniqid(rand(), true));
		$n = rand(1, 26);
		$token = substr($hash, $n, 11);
		return $token;
	}


	//会员登录日志
	public function user_login($uid,$zcname,$ip,$type){
		$dataf  = array();
		$dataf['ssid']   = session_id();
		$dataf['login_time'] = date("Y-m-d H:i:s");
		$dataf['is_login']   = 1;
		$dataf['www']        = $_SERVER['HTTP_HOST'];
		$dataf['ip']  = $ip;

		if ($type == 1) {
			$dataf['uid']        = $uid;
			$dataf['site_id']    = SITEID;
		    $dataf['index_id']   = INDEX_ID;
		    $this->radd('k_user_login',$dataf);
		}else{
            //更新会员登录
			$mapUL = array();
			$mapUL['table'] = 'k_user_login';
			$mapUL['where']['uid'] = $uid;
			$this->rupdate($mapUL,$dataf);
		}
	}

	//会员登陆
	public function mem_login($uname,$pwd,$ip,$address){
        $mapL = array();
        $mapL['table'] = 'k_user';
        $mapL['select'] = 'username,uid,password,agent_id,ua_id,sh_id,level_id,is_delete,shiwan';
		$mapL['where']['site_id'] = SITEID;
		$mapL['where']['username'] = $uname;
		//$mapL['where']['shiwan'] = 0;
		$mapL['where']['index_id'] = INDEX_ID;

		$loginS = $this->rfind($mapL);

		if (empty($loginS)) {
			return 3;//账号不存在
		}
		if ($loginS['password'] != md5(md5($pwd))) {
			$this->user_history_login($loginS['uid'],$uname,$ip,$address,0);
		    return 4;//密码不对
		}
        //暂停 停止
		if ($loginS['is_delete'] == 1 || $loginS['is_delete'] == 2) {
			return 2;
		}
        //判断是否已经登陆
		$this->isLoginNow($loginS['uid'],$loginS['username'],$ip);

		//更新用户登录
		$this->db->where(array('uid'=>$loginS['uid']));
		$this->db->set('lognum','lognum + 1',FALSE);
		$this->db->set('login_ip',$ip);
		$this->db->set('login_time',date('Y-m-d H:i:s'));
		$log_2 = $this->db->update('k_user');

		//历史记录
	    $this->user_history_login($loginS['uid'],$uname,$ip,$address,1);

	    //写入session
     // $user_data["uid"] = $loginS["uid"];
		 // $user_data["agent_id"] = $loginS["agent_id"]; //代理id
		 // $user_data["username"] = $loginS['username'];
		 // $user_data["level_id"] = $loginS["level_id"];//层级ID
        //redis写入会员在线
        $this->redis_update_user();
        $this->load->model('sports/User_model','User_model');
        $_SESSION["token"]=$this->User_model->redis_update_token([$loginS['uid'],$loginS['username'],$loginS['agent_id'],$loginS['level_id'],0,SITEID,INDEX_ID,$loginS['ua_id'],$loginS['sh_id']]);

		$_SESSION['uid'] = $loginS['uid'];
        $_SESSION['agent_id'] = $loginS['agent_id'];
        $_SESSION['ua_id'] = $loginS['ua_id'];
        $_SESSION['sh_id'] = $loginS['sh_id'];
        $_SESSION['username'] = $loginS['username'];
        $_SESSION['level_id'] = $loginS['level_id'];;
        $_SESSION['shiwan']   = $loginS['shiwan'];
        $_SESSION['ssid'] = session_id();

        return 1;
	}

	//会员历史登录
	public function user_history_login($uid,$uname,$ip,$address,$state){
		$dataO = array();
		$dataO['uid'] = $uid;
		$dataO['username'] = $uname;
		$dataO['ip'] = $ip;
		$dataO['state']     = $state;
		$dataO['ip_address'] = $address;
		$dataO['login_time'] = date('Y-m-d H:i:s');
		$dataO['site_id'] = SITEID;
		$dataO['index_id'] = INDEX_ID;
		$dataO['www'] = $_SERVER['HTTP_HOST'];
		$this->radd('history_login',$dataO);
	}

	    //判断会员是否已经登录
    public function isLoginNow($iuid,$username,$ip){
    	    $map = array();
    	    $map['table'] = 'k_user_login';
    	    $map['select'] = 'uid,ssid,is_login';
    	    $map['where']['uid'] = $iuid;
    	    $nowLogin = $this->rfind($map);

			if ($nowLogin) {
				//登录过//已经在线
				if (isset($nowLogin['is_login']) && $nowLogin['is_login']) {
					//取消上一个session
					/*session_id($nowLogin['ssid']);
					session_regenerate_id(true);
					$newSession = session_id();
					session_write_close();
					session_id($newSession);*/
					session_start();
				}
				//更新会员登录
				$this->user_login($iuid,$username,$ip,0);
			}else{
					//添加会员登录
				$this->user_login($iuid,$username,$ip,1);
			}

    }

	//获取试玩注册帐号
    public function shiwan_user(){
    		$map = array();
				$map['table'] = 'k_user';
				$map['select'] = 'username';
				$map['like']['title'] = 'username';
			  $map['like']['match'] = 'TEST';
			  $map['like']['after'] = 'after';
			  $map['order'] = 'uid desc';
				$rs = $this->rfind($map);
				$username = 'TEST' . rand(10, 99) . (substr($rs['username'], 6) + 1);
				return $username;
    }


    //试玩信息插入表格
    public function add_shiwan($username,$password,$agent_demo){
        $this->load->model('sports/User_model','User_model');
    	$data_s['username'] = $username;
		$data_s['password'] = $password;
		$data_s['money'] = 2000; //试玩赠送金额
		$data_s['site_id'] = SITEID;
		$data_s['index_id'] = INDEX_ID;
		$data_s['agent_id'] = $agent_demo['id'];
		$data_s['ua_id'] = $agent_demo['pid'];
		$data_s['sh_id'] = $agent_demo['sh_id'];
		$data_s['shiwan'] = 1;
		$shi_S = $this->radd('k_user',$data_s);
        $_SESSION["token"]=$this->User_model->redis_update_token([$shi_S,$username,$data_s['agent_id'],0,0,SITEID,INDEX_ID,$data_s['ua_id'],$data_s['sh_id']]);
		return $shi_S;
    }

	 //读取视讯配置
    public function get_video_config($site_id){
		$map = array();
		$map['table'] = "web_config";
		$map['where']['site_id'] = $site_id;
		$map['select'] = 'video_module';
		$video_config = $this->rfind($map);
		$video_config = explode(',',$video_config['video_module']);
		return $video_config;
    }

        //推广域名会员人数添加
    public function agent_domain_ucount(){
        $mapdo = array();
		$mapdo['domain'] = $_SERVER['HTTP_HOST'];
		$mapdo['intr'] = $_SESSION['intr'];
		$mapdo['site_id'] = SITEID;
		$mapdo['state'] = 1;

		$this->db->where($mapdo);
		$this->db->set('ucount','ucount + 1',FALSE);
		$this->db->update('k_user_agent_domain');
    }

    //代理推广域名匹配
    public function agent_domain_intr(){
        //匹配代理推广域名
		$mapdo = array();
		$mapdo['table'] = 'k_user_agent_domain';
		$mapdo['where']['domain'] = $_SERVER['HTTP_HOST'];
		$mapdo['where']['site_id'] = SITEID;
		$mapdo['where']['state'] = 1;
		$domain_data = $this->rfind($mapdo);
		if ($domain_data) {
		    $_SESSION['intr'] = $domain_data['intr'];
		    $_SESSION['domain_intr_id'] = $domain_data['id'];//标注是匹配代理域名推广
		}
    }
}