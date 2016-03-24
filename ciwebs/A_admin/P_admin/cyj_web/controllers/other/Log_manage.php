<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_manage extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('other/Log_manage_model');
	}
    //会员登录日志
	public function member_login_log()
	{
		$index_id = $this->input->get('index_id');
		$start_date = $this->input->get('start_date');
		$start_date = empty($start_date)?date('Y-m-d'):$start_date;
		$end_date = $this->input->get('end_date');
		$end_date = empty($end_date)?date('Y-m-d'):$end_date;
		$state = $this->input->get('state');
		$ip = $this->input->get('ip');
		$page = $this->input->get('page');
		$username = $this->input->get('username');

		 //查询时间判断
        about_limit($end_date,$start_date);

        $map_cp = array();
        $map_cp['site_id'] = $_SESSION['site_id'];
        $map_cp['login_time'] = array(
        	                       array('>=',$start_date.' 00:00:00'),
                                   array('<=',$end_date.' 23:59:59'));

        //站点判断
        if (!empty($index_id)) {
            $map_cp['index_id'] = $index_id;
        }
		//账号检索
		if(!empty($username)){
		    $map_cp['username'] = array('like','%'.$username.'%');
		}
		//ip检索
		if(!empty($ip)){
		    $map_cp['ip'] = array('like','%'.$ip.'%');
		}
		if ($state == '0' || $state == 1) {
		    $map_cp['state'] = $state;
		}

		//总数
		$db_model['tab'] = 'history_login';
        $db_model['base_type'] = 1;
        $count = $this->Log_manage_model->mcount($map_cp,$db_model);

		$perNumber = 50; //每页显示的记录数
		$totalPage=ceil($count/$perNumber); //计算出总页数
		$page=isset($page)?$page:1;
		if($totalPage<$page){
		  $page = 1;
		}

		$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
		$limit=$startCount.",".$perNumber;
		$user_login = $this->Log_manage_model->get_member_login($map_cp,$limit);
		foreach ($user_login as $key => $val) {
		    $user_login[$key]['state_zh'] = $this->loginState($val['state'],$val['www']);
		}

		   //多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.$this->Log_manage_model->select_sites());
	    }
		$this->add('user_login',$user_login);
		$this->add('start_date',$start_date);
		$this->add('end_date',$end_date);
		$this->add('index_id',$index_id);
		$this->add('page', $this->Log_manage_model->get_page('history_login',$totalPage,$page));
        $this->display('other/member_login_log.html');
	}

    //前台登陆状态转换
	function loginState($state,$url){
	   switch ($state) {
	     case '1':
	       return '登入成功('.$url.')';
	       break;

	     default:
	        return '登入失败('.$url.')';
	       break;
	   }
	}

    //管理员登录
    public function admin_login_log(){
		$start_date = $this->input->get('start_date');
		$start_date = empty($start_date)?date('Y-m-d'):$start_date;
		$end_date = $this->input->get('end_date');
		$end_date = empty($end_date)?date('Y-m-d'):$end_date;
		$state = $this->input->get('state');
		$ip = $this->input->get('ip');
		$page = $this->input->get('page');
		$username = $this->input->get('username');

		 //查询时间判断
        about_limit($end_date,$start_date);

        $map_cp = array();
        $map_cp['site_id'] = $_SESSION['site_id'];
        $map_cp['login_time'] = array(
        	                       array('>=',$start_date.' 00:00:00'),
                                   array('<=',$end_date.' 23:59:59')
                                   );

		//账号检索
		if(!empty($username)){
		    $map_cp['username'] = array('like','%'.$username.'%');
		}
		//ip检索
		if(!empty($ip)){
		    $map_cp['ip'] = array('like','%'.$ip.'%');
		}
		if ($state == '0' || $state == 1) {
		    $map_cp['state'] = $state;
		}

		//总数
		$db_model['tab'] = 'sys_admin_login';
        $db_model['base_type'] = 1;
        $count = $this->Log_manage_model->mcount($map_cp,$db_model);

		$perNumber = 50; //每页显示的记录数
		$totalPage=ceil($count/$perNumber); //计算出总页数
		$page=isset($page)?$page:1;
		if($totalPage<$page){
		  $page = 1;
		}

		$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
		$limit=$startCount.",".$perNumber;
		$user_login = $this->Log_manage_model->get_admin_login($map_cp,$limit);
		foreach ($user_login as $key => $val) {
		    $user_login[$key]['state_zh'] = $this->loginState($val['state'],$val['www']);
		}

		$this->add('user_login',$user_login);
		$this->add('start_date',$start_date);
		$this->add('end_date',$end_date);
		$this->add('page', $this->Log_manage_model->get_page('history_login',$totalPage,$page));
        $this->display('other/admin_login_log.html');
    }

    //管理操作日志
    public function admin_do_log(){
    	$start_date = $this->input->get('start_date');
    	$start_date = empty($start_date)?date('Y-m-d'):$start_date;
    	$end_date = $this->input->get('end_date');
    	$end_date = empty($end_date)?date('Y-m-d'):$end_date;
        $page = $this->input->get('page');
    	$username = $this->input->get('username');
    	$ip = $this->input->get('ip');

    	 //查询时间判断
        about_limit($end_date,$start_date);

    	$map = array();
    	$map['site_id'] = $_SESSION['site_id'];
    	$map['log_time'] = array(
    		                 array('>=',$start_date.' 00:00:00'),
    		                 array('<=',$end_date.' 23:59:59')
    		             );
    	if (!empty($ip)) {
    	    $map['log_ip'] = array('like','%'.$ip.'%');
    	}

		//账号检索
		if (!empty($username)) {
		    $uid = $this->Log_manage_model->rfind(array('table'=>'sys_admin','where'=>array('site_id'=>$_SESSION['site_id'],'login_name_1'=>$username)));
		    if (!empty($uid)) {
		        $map['uid'] = $uid['uid'];
		    }else{

		    }
		}

			//总数
		$db_model['tab'] = 'sys_log';
        $db_model['type'] = 1;
        $count = $this->Log_manage_model->mcount($map,$db_model);

		$perNumber = 50; //每页显示的记录数
		$totalPage=ceil($count/$perNumber); //计算出总页数
		$page=isset($page)?$page:1;
		if($totalPage<$page){
		  $page = 1;
		}

		$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
		$limit=$startCount.",".$perNumber;
		$sys_log = $this->Log_manage_model->get_admin_do($map,$limit);
		$this->add('sys_log',$sys_log);
		$this->add('start_date',$start_date);
		$this->add('end_date',$end_date);
		$this->add('page', $this->Log_manage_model->get_page('sys_log',$totalPage,$page));
        $this->display('other/admin_do_log.html');
    }

    //非管理员用户操作日志
    public function user_do_log() {
        $start_date = $this->input->get('start_date');
        $start_date = empty($start_date) ? date('Y-m-d') : $start_date;
        $end_date = $this->input->get('end_date');
        $end_date = empty($end_date) ? date('Y-m-d') : $end_date;

        $log_info = $this->input->get('operation');
        $type = $this->input->get('type');
        $uname = $this->input->get('uname');
        $page = $this->input->get('page');

         //查询时间判断
        about_limit($end_date,$start_date);

        $map_log = array();
        $map_log['site_id'] = $_SESSION['site_id'];
        $map_log['log_time'] = array(
            array(
                '>=',
                $start_date . ' 00:00:00'
            ),
            array(
                '<=',
                $end_date . ' 23:59:59'
            )
        );

        if (! empty($log_info)) {
            $map_log['log_info'] = array(
                'like',
                '%' . $log_info . '%'
            );
        }

        // 判断会员和代理 1会员 2股东 代理
        if (! empty($type)) {
            $map_log['type'] = $type;
        }

        if (! empty($uname)) {
            $map_log['uname'] = $uname;
        }

        // 总数
        $db_model['tab'] = 'sys_log';
        $db_model['type'] = 1;
        $count = $this->Log_manage_model->mcount($map_log, $db_model);

        $perNumber = 50; // 每页显示的记录数
        $totalPage = ceil($count / $perNumber); // 计算出总页数
        $page = isset($page) ? $page : 1;
        if ($totalPage < $page) {
            $page = 1;
        }
        $startCount = ($page - 1) * $perNumber; // 分页开始,根据此方法计算出开始的记录
        $limit = $startCount . "," . $perNumber;
        $sys_log = $this->Log_manage_model->get_admin_do($map_log, $limit);

        $this->add('sys_log', $sys_log);
        $this->add('start_date', $start_date);
        $this->add('end_date', $end_date);
        $this->add('page', $this->Log_manage_model->get_page('sys_log', $totalPage, $page));
        $this->display('other/user_do_log.html');
    }

     public function user_login_audit(){
        $this->display('other/user_login_audit.html');
    }

    //会员自动稽核功能
    public function user_login_audit_do(){
        $udata = $this->input->get('udata');//登陆ip
        $type = $this->input->get('type');//类型

        if (empty($udata)) {
            showmessage('请输入账号或者IP!',URL.'/other/Log_manage/user_login_audit',0);
        }

        $db_model['tab'] = 'history_login';
        $db_model['type'] = 1;

        if ($type == '1') {
            //为ip类型 先检索会员账号
            $map_iu['site_id'] = $_SESSION['site_id'];
            $map_iu['ip'] = $udata;
            $udata = $this->Log_manage_model->M($db_model)->field("uid,username")->group("uid")->where($map_iu)->select("username");
            $udata = array_keys($udata);

        }else{
            $udata = array($udata);
        }

        // p($udata);

        //第一层ip
        $ips[0] = $this->get_udata($udata,'ip','username');
        //循环向下查询ip
        for ($i=1; $i < 4; $i++) {
            $ips[$i] = $this->get_udata($this->get_udata($ips[$i-1],'uid','ip'),'ip','uid');
            //如果经过一轮循环
            if (count($ips[$i]) == count($ips[$i-1]) || $i == 3) {
            	$ips = $ips[$i];
                break;
            }
        }

        // p($ips);die();


        $map_1['ip'] = array('in',"('".implode("','", $ips)."')");
        $map_1['site_id'] = $_SESSION['site_id'];

        $user_login = $this->Log_manage_model->M($db_model)->group("uid")->where($map_1)->select();

        $this->add('user_login',$user_login);
        $this->display('other/user_login_audit.html');

    }
    //查询对应的uid ip
    function get_udata($ips,$type,$mtype){
    	$db_model['tab'] = 'history_login';
        $db_model['type'] = 1;

    	$map[$mtype] = array('in',"('".implode("','", $ips)."')");
        $map['site_id'] = $_SESSION['site_id'];

        $ips = $this->Log_manage_model->M($db_model)->field($type)->group($type)->where($map)->select($type);
        return array_keys($ips);
    }
}
