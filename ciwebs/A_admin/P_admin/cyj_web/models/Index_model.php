<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Index_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}
	//登陆处理
	public function login($login_name_1,$login_pwd) {
		$admin_url = $_SERVER["HTTP_HOST"];
		$site_id = $this->get_admin_site($login_name_1,$admin_url);

        if ($site_id) {
            $_SESSION['site_id'] = $site_id;
        }else{
        	return 'F1';
        }

        //获取基本信息
        $ip = $this->get_ip();
        $address = $this->get_address($ip).'  系统：'.getOS();

        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['login_name_1'] = $login_name_1;
	    $this->db->where($map);
		$u_log = $this->db->get('sys_admin')->row_array();

		if ($u_log['is_delete'] == 2) {
			return 'F2';
		}
		//登陆处理
		if ($u_log['login_pwd'] === md5(md5($login_pwd))) {
            if (!empty($u_log["ssid"])) {
				//取消上一个session
				session_id($u_log["ssid"]);
				session_regenerate_id(true);
				$newSession = session_id();
				session_write_close();
				session_id($newSession);
				session_start();
			}

			//var_dump($_SESSION['site_id']);die;
			if($this->login_log_do($u_log['uid'],$login_name_1,$ip,$address,$admin_url)){
				//多站点
				$this->is_index_id();
				//读取视讯配置
				$video_config = $this->get_video_config();
				//写入session
			  $_SESSION["adminid"] = $u_log['uid'];
				$_SESSION["login_name"] = $u_log['login_name'];//原始账号
				$_SESSION["login_name_1"] = $u_log['login_name_1'];//登陆账号
				$_SESSION["quanxian"] = $u_log['quanxian'];//权限
				$_SESSION['mem_auth'] = $u_log['mem_auth'];
				$_SESSION["ssid"] = session_id();
				$_SESSION['catm_max'] = $u_log['catm_max'];
				$_SESSION['online_max'] = $u_log['online_max'];
				$_SESSION['site_des_key'] = $video_config['site_des_key'];
				$_SESSION['site_md5_key'] = $video_config['site_md5_key'];

				$this->redis_update_user();
				return 'F3';
			}else{
				 //登陆日志
			    $dataG = array();
			    $dataG['uid'] = $log['uid'];
			    $dataG['site_id'] = $_SESSION['site_id'];
			    $dataG['username'] = $log['login_name_1'];
			    $dataG['ip'] = $ip;
			    $dataG['state'] = 0;
			    $dataG['ip_address'] = $address;
			    $dataG['login_time'] = date('Y-m-d H:i:s');
			    $dataG['www'] = $admin_url;
					$this->db->insert('sys_admin_login',$dataG);
          return 'F4';
			}
		}else{
			//密码错误处理
		    //登陆日志
		    $dataG = array();
		    $dataG['uid'] = $u_log['uid'];
		    $dataG['site_id'] = $site_id;
		    $dataG['username'] = $login_name_1;
		    $dataG['ip'] = $ip.' (账号密码不匹配) ';
		    $dataG['state'] = 0;
		    $dataG['ip_address'] = $address;
		    $dataG['login_time'] = date('Y-m-d H:i:s');
		    $dataG['www'] = $admin_url;
			$this->db->insert('sys_admin_login',$dataG);

			//错误次数加一
			$u_sql = "UPDATE `sys_admin` SET `error_num` = error_num + 1 WHERE `uid` = '".$u_log['uid']."' and site_id = '".$site_id."' ";
            $this->db->query($u_sql);

            if ($u_log['error_num'] >=3) {
            	$mapru = array();
            	$mapru['table'] = 'sys_admin';
            	$mapru['where']['uid'] = $u_log['uid'];
            	$mapru['where']['site_id'] = $_SESSION['site_id'];
                $this->rupdate($mapru,array('is_delete'=>2));
            }
			return 'F5';
		}
	}

  //获取当前账号的site_id
	public function get_admin_site($login_name_1,$admin_url){
        $is_site_sql = "select site_id from `sys_admin` where login_name_1 = '".$login_name_1."' and is_delete in (0,2) and locate('".$admin_url."',admin_url)>0 ";
        $query = $this->db->query($is_site_sql);
		if ($query->num_rows() > 0)
		{
		    $row = $query->row();
		    return $row->site_id;
		}else{
			return FALSE;
		}
	}
    //登陆日志
	public function login_log_do($uid,$username,$ip,$address,$admin_url){
        $this->db->trans_strict(FALSE);
        $this->db->trans_begin();

		//更新登陆状态
		$dataB = array();
		$dataB['login_ip'] = $ip;
		$dataB['updatetime'] = time();
		$dataB['login_address'] = $address;
		$dataB['ssid'] = session_id();
		$this->db->where('uid',$uid);
		$this->db->update('sys_admin',$dataB);

		//登陆日志
	    $dataG = array();
	    $dataG['uid'] = $uid;
	    $dataG['site_id'] = $_SESSION['site_id'];
	    $dataG['username'] = $username;
	    $dataG['ip'] = $ip;
	    $dataG['state'] = 1;
	    $dataG['ip_address'] = $address;
	    $dataG['login_time'] = date('Y-m-d H:i:s');
	    $dataG['www'] = $admin_url;
		$this->db->insert('sys_admin_login',$dataG);

		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
	    }

	}

	//判断是否多站点
	public function is_index_id(){
	    $this->db->where('site_id',$_SESSION['site_id']);
	    $rows = $this->db->get('web_config')->result_array();
	    if(count($rows) >1){
            $_SESSION['index_id'] = 1;
	    }
	}

	//获取在线人数
	public function get_online_count(){
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$redis_key = 'ulg'.CLUSTER_ID.'_'.$_SESSION['site_id'].'*';
		$user_online = $redis->keys($redis_key);
		$new_user_on = array();

		if (!empty($user_online)) {
            foreach ($user_online as $key => $val) {
                $new_user_on[] = str_replace('ulg'.CLUSTER_ID.'_'.$_SESSION['site_id'],'',$val);
            }
            $user_online = array_filter($new_user_on);
            return count($user_online);
        }
		return 0;
	}

	//获取视讯配置
	public function get_video_config(){
		$this->video_db->where('site_id',$_SESSION['site_id']);
	    return $this->video_db->get('game_sites')->row_array();

	}

	//获取公告
	public function get_one_notice() {
		$this->private_db->from('site_notice');
		$this->private_db->where(array('notice_state'=>1,'notice_cate'=>6));
		$this->private_db->order_by("id DESC");
	    return $this->private_db->get()->row_array();
	}













}