<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Member_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

        //会员登陆
    public function Login($uname,$pwd,$ip,$address){
        $mapL = array();
        $mapL['table'] = 'k_user';
        $mapL['select'] = 'username,uid,password,agent_id,ua_id,sh_id,level_id,is_delete,shiwan,pay_name,qk_pwd';
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

        //redis写入会员在线
        $_SESSION['uid'] = $loginS['uid'];
        $this->redis_update_user();
        $this->load->model('sports/User_model','User_model');
        $_SESSION["token"]=$this->User_model->redis_update_token([$loginS['uid'],$loginS['username'],$loginS['agent_id'],$loginS['level_id'],0,SITEID,INDEX_ID,$loginS['ua_id'],$loginS['sh_id']]);

        $_SESSION['agent_id'] = $loginS['agent_id'];
        $_SESSION['ua_id'] = $loginS['ua_id'];//总代id
        $_SESSION['sh_id'] = $loginS['sh_id'];//股东id
        $_SESSION['username'] = $loginS['username'];
        $_SESSION['uname']    = $loginS['pay_name'];
        $_SESSION['qkpwd']    = $loginS['qk_pwd'];
        $_SESSION['level_id'] = $loginS['level_id'];;
        $_SESSION['shiwan']   = $loginS['shiwan'];
        $_SESSION['ssid'] = session_id();

        return 1;
    }
    /*
    錯誤代碼(除1以外，其餘代碼業主可自訂)
    1：成功
    3：用戶名或密碼錯誤
    5：登入錯誤次數達到上限
    6：用戶未激活
    8：自我限制用戶
    100：未知錯誤
    101：xml 錯誤
    102：未知請求
    103: Element ID 錯誤
    999：内部錯誤
    */
     public function bbin_app_login($uname,$pwd,$siteid){
        $mapL = array();
        $mapL['table'] = 'k_user';
        $mapL['select'] = 'username,uid,password,agent_id,level_id,is_delete,shiwan,pay_name,qk_pwd';
        $mapL['where']['site_id'] = SITEID;
        $mapL['where']['username'] = $uname;
        //$mapL['where']['shiwan'] = 0;
        //$mapL['where']['index_id'] = INDEX_ID;

        $loginS = $this->rfind($mapL);

        if (empty($loginS)) {
            return 6;//账号不存在
        }
        if ($loginS['password'] != md5(md5($pwd))) {
            $this->user_history_login($loginS['uid'],$uname,$ip,$address,0);
            return 3;//密码不对
        }
        //暂停 停止
        if ($loginS['is_delete'] == 1 || $loginS['is_delete'] == 2) {
            return 8;
        }

        return 1;
    }

    //会员登录日志
    public function user_login($uid,$zcname,$ip,$type){
        $dataf  = array();
        $dataf['ssid']   = session_id();
        $dataf['login_time'] = date("Y-m-d H:i:s");
        $dataf['is_login']   = 1;
        $dataf['www']        = $_SERVER['HTTP_HOST'];
        $dataf['ip']  = $ip;
        $dataf['ptype']  = 1;

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

    //会员历史登录
    public function user_history_login($uid,$uname,$ip,$address,$state){
        $dataO = array();
        $dataO['uid'] = $uid;
        $dataO['username'] = $uname;
        $dataO['ip'] = $ip;
        $dataO['ptype'] = 1;
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
            $redis = new Redis();
            $redis->connect(REDIS_HOST,REDIS_PORT);
            $redis_key = 'ulg'.CLUSTER_ID.'_'.SITEID.$nowLogin['ssid'];
            $redis->del($redis_key);
            if (isset($nowLogin['is_login']) && $nowLogin['is_login']) {
                //取消上一个session
                session_start();
            }
            //更新会员登录
            $this->user_login($iuid,$username,$ip,0);
        }else{
                //添加会员登录
            $this->user_login($iuid,$username,$ip,1);
        }

    }

	//获取用户信息
    public function get_user_views($uid,$site_id){
	    $this->db->from('k_user');
	    $this->db->where('site_id',$site_id);
	    $this->db->where('uid',$uid);
	    $data = $this->db->get()->row_array();
	    $result = array();
	    $json = array();
	    $json['ErrorCode'] = "--0--";
	    $result['MemberID'] = '--'.$data['uid'].'--';
	    $result['RealName'] = $data['pay_name'];
	    $result['NickName'] = $data['username'];
	    $result['Phone'] = $data['mobile'];
	    $result['EMail'] = $data['email'];
	    $result['LevelName'] = $this->level_name($data['level_id']);
	    $result['CountryNo'] = "China";
	    $result['CurrencyNo'] = "RMB";
		$result['money'] = $data['money'];
		$result['ag_money'] = $data['ag_money'];
		//$result['og_money'] = $data['og_money'];
		$result['mg_money'] = $data['mg_money'];
		$result['bbin_money'] = $data['bbin_money'];
		//$result['ct_money'] = $data['ct_money'];
		$result['lebo_money'] = $data['lebo_money'];
	    $result['Poundage'] = '--'.$data['money'].'--';
	    $result['CountryName'] = $data['reg_address'];
	    $result['GameLevels'][0]['GameClassID'] = "--1--";
	    $result['GameLevels'][0]['LevelNo'] = "--0--";
	    $result['GameLevels'][1]['GameClassID'] = "--2--";
	    $result['GameLevels'][1]['LevelNo'] = "--0--";
	    $result['GameLevels'][2]['GameClassID'] = "--3--";
	    $result['GameLevels'][2]['LevelNo'] = "--0--";
	    $result['GameLevels'][3]['GameClassID'] = "--4--";
	    $result['GameLevels'][3]['LevelNo'] = "--0--";
	    $result['GameLevels'][4]['GameClassID'] = "--5--";
	    $result['GameLevels'][4]['LevelNo'] = "--0--";
		 //视讯配置
	    $map = array();
	    $map['site_id'] = SITEID;
	    if (defined('INDEX_ID')) {
	        $map['index_id'] = INDEX_ID;
	    }
	    $video_config = $this->db->from('web_config')->where($map)->select('video_module')->get()->row_array();
		$video_config = explode(',',$video_config['video_module']);
		$result['allmoney'] = $data['money'];
        //AG  BBIN   MG   LEBO
		foreach ($video_config as $k=> $v){
            if($v == "pt" || $v == "og" || $v == "ct"){
                continue;
            }
			$temp = $v.'_money';
			$result['video_model'][] = array('name'=>strtoupper($v),'money'=>$data[$temp]);
			$result['allmoney']+=$data[$temp];
		}
        //$result['video_model'][] = array('name'=>strtoupper($v),'money'=>$data[$temp]);
	    $json['Data'] = $result;
	    return $json;
    }

    public function level_name($level_id){
    	switch ($level_id) {
    		case '1':
    			$level_name = "普通会员";
    			break;
    		case '2':
    			$level_name = "中级会员";
    			break;
    		case '3':
    			$level_name = "高级会员";
    			break;
    		default:
    			$level_name = "VIP会员";
    			break;
    	}
    	return $level_name;
    }

    //修改密码
    public function edit_password($oldpsw,$newpsw,$uid,$site_id,$type){
        $json = array();
        $this->db->from('k_user');
        $this->db->where('uid',$uid);
        $this->db->where('site_id',$site_id);
        if($type == 1){
           $this->db->where('password',$oldpsw);
        }elseif ($type == 2) {
           $this->db->where('qk_pwd',$oldpsw);
        }
        $result = $this->db->get()->row_array();
        if($result){
        	 $this->db->where('uid',$uid);
        	 $this->db->where('site_id',$site_id);
             if($type == 1){
                $this->db->set('password',$newpsw);
             }elseif ($type == 2) {
                $this->db->set('qk_pwd',$newpsw);
             }
        	 $this->db->update('k_user');
        	 $result2 = $this->db->affected_rows();
        	 if($result2){
        	 	 	$json['ErrorCode'] = "--0--";
        			$json['ErrorMsg'] = "执行成功";
        	 }else{
        	 		$json['ErrorCode'] = "--2--";
        			$json['ErrorMsg'] = "执行失败";
        	 }
        }else{
        	$json['ErrorCode'] = "--2--";
        	$json['ErrorMsg'] = "旧密码不正确";
        }
        return $json;
    }

        //获取注册信息
        public function GetBankInfo(){
            $map['table'] = 'k_user';
            $map['where']['uid'] = $_SESSION['uid'];
            $map['where']['site_id'] = SITEID;
            return $this->rfind($map);
	}
        public function BankType($type) {
            $map['table'] = 'k_bank_cate';
            $map['where']['id'] = $type;
            $result = $this->rfind($map);
            return $result['bank_name'];
	}

        //获取站点注册字段是否需要验证重复,返回0需要验证，1不需要验证
        public function GetRegConfig($type) {
            $map['table'] = 'k_user_reg_config';
            $map['where']['site_id'] = SITEID;
            $map['where']['index_id'] = INDEX_ID;
            $result = $this->rfind($map);
            switch ($type) {
                case 'is_name':
                    $r = $result['is_name'];
                    break;
                case 'email':
                    $r = $result['is_mail'];
                    break;
                case 'mobile':
                    $r = $result['is_tel'];
                    break;
                case 'qq':
                    $r = $result['is_qq'];
                    break;
                case 'all': //获取表单选项是否显示,是否必填
                    $r = array(explode("-", $result['email']),explode("-", $result['passport']),explode("-", $result['qq']),explode("-", $result['mobile']));
                    break;
                default:
                    $r = 1;
                    break;
            }
            return $r;
        }

        //验证账号是否已被注册
        public function RegisterMember($name) {
            $map['table'] = 'k_user';
            $map['where']['site_id'] = SITEID;
            $map['where']['index_id'] = INDEX_ID;
            $map['where']['username'] = $name;
            $result = $this->rfind($map);
            $RegConfig = $this->GetRegConfig('all');
            if($result){
                return array(1,$RegConfig);
            }
            return array(0,$RegConfig);
	}

        //验证用户名是否已被注册
        public function RegisterRealName($name) {
            if($this->GetRegConfig('is_name')==0){
                //不能重复
                $map['table'] = 'k_user';
                $map['where']['site_id'] = SITEID;
                $map['where']['index_id'] = INDEX_ID;
                $map['where']['pay_name'] = $name;
                $result = $this->rfind($map);
                if($result){
                    return 1;
                }
                return 0;
            }else{
                //能重复
                return 0;
            }

	}

        //验证手机号是否已被注册
        public function RegisterPhone($Phone) {
            if($this->GetRegConfig('mobile')==0){
                //不能重复
                $map['table'] = 'k_user';
                $map['where']['site_id'] = SITEID;
                $map['where']['index_id'] = INDEX_ID;
                $map['where']['mobile'] = $Phone;
                $result = $this->rfind($map);
                if($result){
                    return 1;
                }
                return 0;
            }else{
                //能重复
                return 0;
            }

	}

        //验证邮箱是否已被注册
        public function RegisterEMail($EMail) {
            if($this->GetRegConfig('email')==0){
                //不能重复
                $map['table'] = 'k_user';
                $map['where']['site_id'] = SITEID;
                $map['where']['index_id'] = INDEX_ID;
                $map['where']['email'] = $EMail;
                $result = $this->rfind($map);
                if($result){
                    return 1;
                }
                return 0;
            }else{
                //能重复
                return 0;
            }

	}

        //验证qq是否已被注册
        public function RegisterQQ($QQ) {
            if($this->GetRegConfig('qq')==0){
                //不能重复
                $map['table'] = 'k_user';
                $map['where']['site_id'] = SITEID;
                $map['where']['index_id'] = INDEX_ID;
                $map['where']['qq'] = $QQ;
                $result = $this->rfind($map);
                if($result){
                    return 1;
                }
                return 0;
            }else{
                //能重复
                return 0;
            }

	}

    //获取ip
    public function GetClientIp(){
        $realip = '';
        $unknown = 'unknown';
        if (isset($_SERVER)){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach($arr as $ip){
                    $ip = trim($ip);
                    if ($ip != 'unknown'){
                        $realip = $ip;
                        break;
                    }
                }
            }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){
                $realip = $_SERVER['REMOTE_ADDR'];
            }else{
                $realip = $unknown;
            }
        }else{
            if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){
                $realip = getenv("HTTP_CLIENT_IP");
            }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){
                $realip = getenv("REMOTE_ADDR");
            }else{
                $realip = $unknown;
            }
        }
        $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
        return $realip;
    }

    //获取本站点默认代理
    public function GetAgent() {
        $map = array();
        $map['where']['index_id'] = INDEX_ID;
        $map['where']['site_id'] = SITEID;
        if(!empty($_SESSION['intr'])){
            $map['where']['intr'] = $_SESSION['intr'];
        }else{
            $map['where']['is_default'] = 1; //表示站点默认代理商
        }
        $map['where']['agent_type'] = 'a_t';
        $map['table'] = 'k_user_agent';
        $deAgent = $this->rfind($map);
        if(empty($deAgent['id'])){
            $map = array();
            $map['where']['index_id'] = INDEX_ID;
            $map['where']['site_id'] = SITEID;
            $map['where']['is_default'] = 1; //表示站点默认代理商
            $map['where']['agent_type'] = 'a_t';
            $map['table'] = 'k_user_agent';
            $deAgent = $this->rfind($map);
        }
        //查询股东id
        $mapS = array();
        $mapS['table'] = 'k_user_agent';
        $mapS['where']['id'] = $deAgent['pid'];
        $mapS['where']['site_id'] = SITEID;
        $Astate = $this->rfind($mapS);
        if ($Astate) {
            $deAgent['id'] = $deAgent['id'];
            $deAgent['pid'] = $deAgent['pid'];
            $deAgent['sh_id'] = $Astate['pid'];
        }
        return $deAgent;
    }

    //注册赠送优惠
    public function RegDis($agent_id,$pid,$ip) {
        //获取注册用户所在IP
        $map_ip['where']['index_id'] = INDEX_ID;
        $map_ip['where']['site_id'] = SITEID;
        $map_ip['where']['reg_ip'] = $ip;
        $map_ip['table'] = 'k_user';
        $k_user = $this->rfind($map_ip);
        $isn_ip = $k_user['uid'];

        //获取股东
        $mapb['where']['id'] = $pid;
        $mapb['where']['site_id'] = SITEID;
        $mapb['table'] = 'k_user_agent';
        $s_h = $this->rfind($mapb);
        //代理 总代 股东的agent_id
        $agentsstr = $s_h['pid'].",".$agent_id.",".$pid;
        $nsql = "select * from k_user_agent_dis where index_id = '" . INDEX_ID. "' and site_id = '" . SITEID. "' and agent_id in (" .$agentsstr.") and discount_money > 0 order by agent_type";
        $query = $this->private_db->query($nsql);
        $data = $query->result_array();
        if (!empty($data)){
            if($data[0]["agent_type"] =="a_t"){
                if ($data[0]["is_ip"] ==0||empty($isn_ip)){
                   $reg_d['money'] = $data[0]['discount_money']; //注册赠送金额
                   $reg_d['d_bet'] = $data[0]['discount_bet']; //打码量
                }else{
                   $reg_d['money'] = $reg_d['d_bet'] = 0;
                }
                return $reg_d;
            }else{
                if($data[0]["agent_type"] =="s_h" &&!empty($data[1])){
                    if ($data[1]["is_ip"] ==0||empty($isn_ip)){
                       $reg_d['money'] = $data[1]['discount_money']; //注册赠送金额
                       $reg_d['d_bet'] = $data[1]['discount_bet']; //打码量
                    }else{
                            $reg_d['money'] = $reg_d['d_bet'] = 0;
                    }
                    return $reg_d;

                }else{
                    if ($data[0]["is_ip"] ==0||empty($isn_ip)){
                       $reg_d['money'] = $data[0]['discount_money']; //注册赠送金额
                       $reg_d['d_bet'] = $data[0]['discount_bet']; //打码量
                    }else{
                            $reg_d['money'] = $reg_d['d_bet'] = 0;
                    }
                    return $reg_d;
                }
            }
        }

        //查询站点
        $mapyh['where']['index_id'] = INDEX_ID;
        $mapyh['where']['site_id'] = SITEID;
        $mapyh['table'] = 'k_user_apply';
        $reg3 = $this->rfind($mapyh);
        if ($reg3) {
            if ($reg3["is_ip"] ==0||empty($isn_ip)){
               $reg_d['money'] = $reg3['discount_money']; //注册赠送金额
               $reg_d['d_bet'] = $reg3['discount_bet']; //打码量
            }else{
               $reg_d['money'] = $reg_d['d_bet'] = 0;
            }
            return $reg_d;
        }else{
            $reg_d['money'] = $reg_d['d_bet'] = 0;
        }
        return $reg_d;

    }

    //获取站点默认层级
    public function getUserlevel() {
        $map['where']['index_id'] = INDEX_ID;
        $map['where']['site_id'] = SITEID;
        $map['where']['is_default'] = 1;
        $map['table'] = 'k_user_level';
        $deLevel =  $this->rfind($map);
        return $deLevel['id'];
    }

     //验证用户名是否已被注册
    public function AddMember($dataa,$reg_state,$address,$userIP) {
        //开启事物
        $this->db->trans_begin();
        //添加会员
        $this->db->insert('k_user',$dataa);
        $id =  $this->db->insert_id();
        if(intval($id) <= 0){
            $this->db->trans_rollback();
            return 9;
        }
        $zcname = $dataa['username'];
        $agent_id = $dataa['agent_id'];
        if ($reg_state['money'] > 0) {
            //发送会员消息
            $dataM              = array();
            $dataM['type']      = 2;
            $dataM['site_id']   = SITEID;
            $dataM['index_id']   = INDEX_ID;
            $dataM['uid']       = $id;
            $dataM['level']     = 2;
            $dataM['msg_title'] = $zcname . ',' . "會員注册贈送優惠";
            $dataM['msg_info']  = $zcname . ',' . "會員注册贈送優惠" . $reg_state['money'] . "元 祝您游戏愉快！";
            $log_2 = $this->db->insert('k_user_msg',$dataM);

            //写入流水记录
            $dataR                 = array();
            $dataR['uid']          = $id;
            $dataR['ptype']        = 1;
            $dataR['username']     = $zcname;
            $dataR['agent_id']     = $agent_id;
            $dataR['site_id']      = SITEID;
            $dataR['index_id']   = INDEX_ID;
            $dataR['cash_balance'] = intval($reg_state['money']); // 用户当前余额;
            $dataR['cash_date']    = date('Y-m-d H:i:s');
            $dataR['cash_type']    = 6;
            $dataR['cash_only']    = 1;
            $dataR['cash_do_type'] = 1;
            $dataR['discount_num'] = $reg_state['money']; // 金额
            $dataR['remark'] = $zcname . ',' . "會員注册贈送優惠" . $reg_state['money'] . "元";
            $log_3 = $this->db->insert('k_user_cash_record',$dataR);

            //写入稽核记录
            if ($reg_state['d_bet'] > 0) {
                $datae                  = array();
                $datae['username']      = $zcname;
                $datae['site_id']       = SITEID;
                $datae['uid']           = $id;
                $datae['source_type']   = 5; //注册优惠
                $datae['begin_date']    = date('Y-m-d H:i:s');
                $datae['type']          = 1;
                $datae['is_zh']         = 1; //有综合稽核
                $datae['is_ct']         = 0;//无常态
                $datae['catm_give']     = $reg_state['money']; //存款优惠
                $datae['type_code_all'] = $reg_state['d_bet'] * $reg_state['money']; //综合稽核打码
                $log_4 = $this->db->insert('k_user_audit',$datae);
            }
        }

        /*$mtime = time();
        $dataf               = array();
        $dataf['ssid']       = session_id();
        $dataf['uid']        = $id;
        $dataf['login_time'] = date("Y-m-d H:i:s");
        $dataf['is_login']   = 1;
        $dataf['www']        = $_SERVER['HTTP_HOST'];
        $dataf['site_id']    = SITEID;
        $dataf['ip']  = $address . $userIP;
        $log_5 = $this->db->insert('k_user_login',$dataf);
        $datag                = array();
        $datag['uid']         = $id;
        $datag['username']    = $zcname;
        $datag['ip']          = $userIP;
        $datag['level_id'] = $level_id;
        $datag['state']     = 1;
        $datag['ip_address']  = $address;
        $datag['login_time']  = date("Y-m-d H:i:s");
        $datag['www']         = $_SERVER['HTTP_HOST'];
        $datag['site_id']     = SITEID;
        $log_6 = $this->db->insert('history_login',$datag);*/

        if($this->db->trans_status()){
            //事物提交
            $this->db->trans_commit();
            //体育登录

            $this->load->model('sports/User_model','User_model');
            $_SESSION["token"]=$this->User_model->redis_update_token([$id,$dataa['username'],$dataa['agent_id'],$dataa['level_id'],0,SITEID,INDEX_ID,$dataa['ua_id'],$dataa['sh_id']]);
            //
            $_SESSION["uid"] = $id;
              //更新在线
            $this->redis_update_user();
            $_SESSION["username"] = $dataa['username'];
            $_SESSION['agent_id'] = $dataa['agent_id'];
            $_SESSION['level_id'] = $dataa['level_id'];
            $_SESSION['ua_id'] = $dataa['ua_id'];
            $_SESSION['sh_id'] = $dataa['sh_id'];
            $_SESSION['shiwan'] = 0;
            $_SESSION['ssid'] = session_id();
            //更新在线注册的会员

            $this->new_reg_user();
            return 0;
        }else{
            $this->db->trans_rollback();
            return 9;
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


    //获取会员入款银行
    public function GetDepositsBanks(){
        $user['level_id'] = $_SESSION['level_id'];
        //入款银行卡
        $this->db->from('k_bank');
        $this->db->where('is_delete',0);
        $this->db->where('site_id',SITEID);
        $banksa = $this->db->get()->result_array();
        if($banksa){
                $banks = $arr = $data = '';
                foreach ($banksa as $key => $value) {
                    $arr = explode(',',$value['level_id']);
                    if(in_array($user['level_id'], $arr)){
                        $banks[] = $value;
                    }
                }
            }
        $date = date("Y-m-d")." 00:00:00";
        foreach ($banks as $key => $value) {
            $this->db->from('k_user_bank_in_record');
            $this->db->select('sum(deposit_money) as c,bid');
            $this->db->where('bid',$value['id']);
            $this->db->where('log_time >',$date);
            $money = $this->db->get()->row_array();
            if($money['c']>$value['stop_amount']){
                unset($banks[$key]);
            }
        }
        return $banks;
    }

    //公司入款处理
   public function AddDepositDo($get){
        //读取限额设定
        $this->load->model('Common_model');
        $userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
        $data = $this->Common_model->get_user_level_info($userinfo['level_id']);
        //var_dump($_SESSION);exit;
        $DepositMoney = doubleval($get['DepositMoney']);
        if($userinfo['is_delete'] == 2){
            return '{"ErrorCode":1,"Data":[]}';
        }
        if($DepositMoney > $data['line_catm_max'] || $DepositMoney <  $data['line_catm_min']){
            return '{"ErrorCode":3,"Data":[]}';
        }

        //防止用户恶意提交表单
        $this->db->from('k_user_bank_in_record');
        $this->db->where('order_num',$get['OrderNum']);
        $this->db->select('order_num');
        $result = $this->db->get()->row_array();
        if(!empty($result['order_num'])  || empty($get['BankAccountID']) || empty($get['DepositAccountTypeID']) || empty($get['BankID'])){
            return '{"ErrorCode":4,"Data":[]}';
        }

        //查询会员所在的层级名字
        $this->db->from('k_user_level');
        $this->db->where('id',$userinfo['level_id']);
        $this->db->select('level_des');
        $level_des = $this->db->get()->row_array();

        //查询代理信息
        $agent = $this->get_agent($userinfo['agent_id']);

        //查询是不是首次入款
        $this->db->from('k_user_bank_in_record');
        $this->db->where('uid',$userinfo['uid']);
        $this->db->where('site_id',SITEID);
        $this->db->where('make_sure',1);
        $this->db->select('id');
        $user_record = $this->db->get()->row_array();

        $data_a = array();

        //存款优惠判断
        if ($DepositMoney >= $data['line_discount_num']) {
            $data_a['favourable_num'] = (0.01*$DepositMoney*$data['line_discount_per']>$data['line_discount_max'])?$discount['line_discount_max']:(0.01*$DepositMoney*$data['line_discount_per']);
        }

        //其它优惠判断
        if ($DepositMoney >= $data['line_other_discount_num']) {
            $data_a['other_num'] = (0.01*$DepositMoney*$data['line_other_discount_per']>$data['line_other_discount_max'])?$data['line_other_discount_max']:(0.01*$DepositMoney*$data['line_other_discount_per']);
        }

        if($get['DepositAccountTypeID']==2 || $get['DepositAccountTypeID']==3 || $get['DepositAccountTypeID']==4){
            $data_a['in_info'] =$get['DepositName'].','.date("Y-m-d H:i:s").','.$this->Common_model->in_type($get['DepositAccountTypeID']).','.$get['Remark'];
        }else{
            $data_a['in_info'] =$get['DepositName'].','.date("Y-m-d H:i:s").','.$this->Common_model->in_type($get['DepositAccountTypeID']);
        }

        $data_a['deposit_money'] = $DepositMoney+$data_a['other_num']+$data_a['favourable_num'];//存入总金额
        $data_a['is_firsttime']  = empty($user_record['id'])?1:0;
        $data_a['in_date']       = date("Y-m-d H:i:s");//入款时间
        $data_a['in_type']       = $get['DepositAccountTypeID'];//存款方式
        $data_a['into_style']    = 1;
        $data_a['bank_style']    = $get['BankID'];//会员使用的银行
        $data_a['in_atm_address']= $get['Remark'];
        $data_a['in_name']       = $get['DepositName'];//入款名字
        $data_a['log_time']      = date("Y-m-d H:i:s");//系统提交时间
        $data_a['deposit_num']   = $DepositMoney;//存款金额
        $data_a['order_num']     = $get['OrderNum']; //订单号
        $data_a['username']      = $userinfo['username'];//会员账号
        $data_a['agent_user']    = $agent['agent_user'];//代理商账号
        $data_a['agent_id']    = $_SESSION['agent_id'];//代理id
        $data_a['uid']           = $userinfo['uid'];//会员UID
        $data_a['level_id']      = $userinfo['level_id'];
        $data_a['level_des']     = $level_des['level_des'];
        $data_a['site_id']       = SITEID;
        $data_a['index_id']      = INDEX_ID;
        $data_a['ptype']         = 1;
        $data_a['bid']           = $get['BankAccountID'];
        $this->db->insert('k_user_bank_in_record',$data_a);
        $insert_id = $this->db->insert_id();
        if($insert_id > 0){
            return '{"ErrorCode":0,"Data":[]}';
        }else{
            return '{"ErrorCode":5,"Data":[]}';
        }

   }

   //出款数据处理
    public function AddWithdrawDo($get){
        $this->load->model('Common_model');
        if($_SESSION['shiwan'] == 1){
            return '{"ErrorCode":10,"Data":[]}';
        }
        if(empty($_SESSION['out_money'])){
            return '{"ErrorCode":2,"Data":[]}';
        }
        //出款判断
        $status = $this->out_cash_record();
        if(!empty($status['order_num'])){
            return '{"ErrorCode":11,"Data":[]}';
        }
        $qk_pwd = $get['withdrawPwd'];
        if(!empty($qk_pwd)){
            $userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
            if($userinfo['qk_pwd'] != $qk_pwd){
                return '{"ErrorCode":1,"Data":[]}';
            }
        }
        $pay_value  =  doubleval($get["applyMoney"]);//提款金额
        $pay_data  =  $this->Common_model->get_user_level_info($_SESSION['uid']);
        if(($pay_value<0)||($pay_value>$userinfo["money"])||($pay_value>$pay_data['ol_atm_max'])||($pay_value<$pay_data['ol_atm_min'])){
            return '{"ErrorCode":2,"Data":[]}';
        }
         //判断是否首次出款
        $outward_style=1;
        $this->db->from('k_user_bank_out_record');
        $this->db->where('uid',$userinfo['uid']);
        $this->db->where('out_status',1);
        $this->db->select('id');
        $is_first = $this->db->get()->row_array();
        if($is_first){
            $outward_style=0;//不是首次出款
        }else{
            $outward_style=1;
        }
        if(empty($_SESSION['agent_id']) || empty($_SESSION['username'])){
            return '{"ErrorCode":2,"Data":[]}';
        }
        //获取代理商账号
        $agent_user = $this->get_agent($userinfo['agent_id']);
        //扣除费用
        $out_data = array();
        $out_data = $_SESSION['out_money'];
        //是否扣除优惠
        if (!empty($out_data['fav_num'])) {
            $is_fav = 1;
        }else{
            $is_fav = 0;
        }
       //判断提出额度是否大于扣除
        $tmpUY = $pay_value - $out_data['out_audit'] - $out_data['out_fee'];
        if ($tmpUY < 0) {
            echo "减去扣除额度后提款额度小于0，请重新提交出款额度。";
            exit();
        }
        $order_num=date("YmdHis").mt_rand(1000,9999);//订单号
        $this->db->trans_begin();
        $this->db->where('uid',$_SESSION['uid']);
        $this->db->set('money','money-'.$pay_value,FALSE);
        $this->db->update('k_user');
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return '{"ErrorCode":4,"Data":[]}';
        }

        //写入出款记录
        //获取用户当前余额
        $umban = $this->now_money();
        if($umban['money'] < 0){
            $this->db->trans_rollback();
            return '{"ErrorCode":4,"Data":[]}';
        }
        $data_o = array();
        $data_o['site_id'] = SITEID;
        $data_o['uid'] = $_SESSION['uid'];
        $data_o['index_id'] = INDEX_ID;
        $data_o['agent_user'] = $agent_user['agent_user'];
        $data_o['agent_id'] = $_SESSION['agent_id'];
        $data_o['username'] = $_SESSION['username'];
        $data_o['level_id'] = $userinfo['level_id'];
        $data_o['audit_id'] = '';
        $data_o['ptype'] = 1;
        $data_o['balance'] = $umban['money'];
        $data_o['do_url'] =  $_SERVER["HTTP_HOST"];//提交网址
        $data_o['order_num'] = $order_num;
        $data_o['out_time'] = date('Y-m-d H:i:s');
        $data_o['outward_style'] = $outward_style;//是否首次出款
        $data_o['outward_num'] = $pay_value;//提交额度
        $data_o['charge'] = $out_data['out_fee'];//手续费
        $data_o['favourable_num'] = $out_data['fav_num'];//优惠金额
        $data_o['expenese_num'] = ($out_data['out_audit'] - $out_data['fav_num']);//行政费用扣除
        $data_o['outward_money'] = ($pay_value - $out_data['out_audit'] - $out_data['out_fee']);//实际出款额度
        $data_o['favourable_out'] = $is_fav;//是否扣除优惠
        $this->db->insert('k_user_bank_out_record',$data_o);
        $log_2 = $this->db->insert_id();
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return '{"ErrorCode":4,"Data":[]}';
        }

        //写入现金系统
        $dataC = array();
        $dataC['uid'] = $_SESSION['uid'];
        $dataC['username'] = $_SESSION['username'];
        $dataC['site_id'] = SITEID;
        $dataC['index_id'] = INDEX_ID;
        $dataC['agent_id'] = $_SESSION['agent_id'];
        $dataC['cash_balance'] = $umban['money'];
        $dataC['source_id'] = $log_2;
        $dataC['cash_type'] = 19;//线上取款
        $dataC['cash_do_type'] = 2;
        $dataC['source_type'] = 4;//线上取款类型
        $dataC['ptype'] = 1;
        $dataC['cash_num'] = $pay_value;
        $dataC['cash_date'] = date('Y-m-d H:i:s');
        $dataC['remark'] = $order_num;
        $this->db->insert('k_user_cash_record',$dataC);
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return '{"ErrorCode":4,"Data":[]}';
        }else{
            $this->db->trans_commit();
            unset($_SESSION['out_money']);
            $data = array();
            $data['ErrorCode'] = 0;
            $data['Data']['Balance']  = $umban['money'];
            $data['Data']['OrderNum'] = $order_num;
            $data['Data']['PayName'] = $userinfo['pay_name'];
            return json_encode($data);
        }
    }

    public function get_agent($agent_id){
        //查询代理信息
        $this->db->from('k_user_agent');
        $this->db->where('id',$agent_id);
        $this->db->where('site_id',SITEID);
        return $this->db->get()->row_array();
    }

    public function out_cash_record(){      //获取出款信息
        $this->db->from('k_user_bank_out_record');
        $this->db->where('uid',$_SESSION['uid']);
        $this->db->where_in('out_status',array(0,4));
        $this->db->where('site_id',SITEID);
        $this->db->select('order_num');
        $result = $this->db->get()->row_array();
        return $result;
    }

    public function now_money(){    //查询用户当前余额
            $this->db->from('k_user');
      $this->db->select('money');
      $this->db->where('uid',$_SESSION['uid']);
      $umban = $this->db->get()->row_array();
      return $umban;
    }

    public function GetIpAddrss($ip){
        require_once dirname(__FILE__).'../../../libraries/Ip.php';
        return ipsetarea($ip);
    }

    public function SaveMemberBank($get){
        $this->load->model("Common_model");
        $userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
        if($userinfo['pay_num'] > 0){
            $json = '{"ErrorCode":1,"Data":[]}';
            return $json;
        }

        $data = array();
        $data['pay_card'] = $get['BankID'];
        $data['pay_address'] = $get['BankProvince'].'-'.$get['BankCity'];
        $data['pay_num'] = $get['BankAccount'];
        $this->db->where('uid',$_SESSION['uid']);
        $this->db->set($data);
        $this->db->update('k_user');
        $num = $this->db->affected_rows();
        if($num > 0){
            $json = '{"ErrorCode":0,"Data":[]}';
            return $json;
        }else{
            $json = '{"ErrorCode":3,"Data":[]}';
            return $json;
        }
    }
}