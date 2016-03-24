<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_level extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Account_level_model');
	}

	public function index()
	{
		$index_id = $this->input->get('index_id');//站点切换
		$index_id = empty($index_id)?'a':$index_id;
        $page = $this->input->get('page');

		//站点所有层级
	    $db_model['tab'] = 'k_user_level';
        $db_model['base_type'] = 1;
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['index_id'] = $index_id;
        $count = $this->Account_level_model->mcount($map,$db_model);

		$perNumber = 20;
		$totalPage=ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		$startCount=($page-1)*$perNumber;
		if($totalPage<$page){
		      $page=1;
		}
		$limit=$startCount.",".$perNumber;
		$level_data = $this->Account_level_model->get_level($map,$limit);

        $level_num  = $this->Account_level_model->level_count();
        //层级下会员数
        foreach ($level_data as $k => $v) {
        	$level_data[$k]['cnum'] = $level_num[$v['id']]['num'];
        }

		$page = $this->Account_level_model->get_page('k_user_level',$totalPage,$page);

        //多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Account_level_model->select_sites()));
	    }
	    $this->add('index_id',$index_id);
	    $this->add('pay_set',$this->get_pay_set());
	    $this->add('page',$page);
	    $this->add('site_id',$_SESSION['site_id']);
	    $this->add('level_data',$level_data);
		$this->display('cash/level_index.html');
	}

	//添加层级
	public function add_level(){
		$id = $this->input->get('id');
		$edit = array();
		if (!empty($id)) {
			$map['table'] = 'k_user_level';
			$map['where']['id'] = $id;
			$edit = $this->Account_level_model->rfind($map);
		}else{
			//获取本站点层级名字
		    $map = array();
		    $map['table'] = 'k_user_level';
		    $map['select'] = 'level_name';
		    $map['where']['site_id'] = $_SESSION['site_id'];
		    $map['order'] = 'level_name DESC';
		    $level = $this->Account_level_model->rfind($map);
		    //添加层级处理
		    $b=preg_match_all('/\d+/',$level['level_name'],$arr);
		    $edit = array();
		    $edit['level_name'] = 'A'.substr(strval($arr[0][0]+10001),1,4);

		    $this->add('pay_set',$this->get_pay_set());
		}
		  //多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str',str_replace('全部', '请选择站点',$this->Account_level_model->select_sites()));
	    }
	    $this->add('edit',$edit);
        $this->display('cash/add_level.html');
	}

	//添加层级处理
	public function add_level_do(){
        $id = $this->input->post('id');
        $index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $arr = array();
        $level_name = $this->input->post('level_name');
        $arr['level_des'] = $this->input->post('level_des');
        if (empty($arr['level_des']) || empty($index_id)) {
            showmessage('请完善表单','add_level');
        }

        $arr['start_date'] = $this->input->post('start_date');
        $arr['end_date'] = $this->input->post('end_date');
        $arr['deposit_num'] = $this->input->post('deposit_num');//存款次数
        $arr['deposit_count'] = $this->input->post('deposit_count');//存款总额
        $arr['remark'] = $this->input->post('remark');//提款次数

        if (empty($id)) {
            //表示添加
            $arr['RMB_pay_set'] = $this->input->post('RMB');//支付设置
            $arr['level_name'] = $level_name;
            $arr['site_id'] = $_SESSION['site_id'];
            $arr['index_id'] = $index_id;
            if ($this->Account_level_model->radd('k_user_level',$arr)) {
            	$log['log_info'] = '添加层级,ID：'.$arr['level_name'];
                $this->Account_level_model->Syslog($log);
                showmessage('添加层级成功','index');
            }else{
            	showmessage('添加层级失败','add_level');
            }
        }else{
        	$map = array();
        	$map['table'] = 'k_user_level';
        	$map['where']['id'] = $id;
            if ($this->Account_level_model->rupdate($map,$arr)) {
            	$log['log_info'] = '编辑层级,ID：'.$level_name;
                $this->Account_level_model->Syslog($log);
                showmessage('编辑层级成功','index');
            }else{
            	showmessage('编辑层级失败','add_level');
            }
        }
	}

	//层级是否开启自助返水
	public function is_self_fd(){
        $is_self_fd = intval($this->input->get('is_self_fd'));
        $id = intval($this->input->get('id'));
        if (empty($id)) {
            showmessage('参数错误',URL.'add_level');
        }

        if ($is_self_fd == 0) {
            $arr['is_self_fd'] = 1;
        }else{
        	$arr['is_self_fd'] = 0;
        }

        $map = array();
        $map['table'] = 'k_user_level';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['id'] = $id;

        if ($this->Account_level_model->rupdate($map,$arr)) {
        	$log['log_info'] = '操作层级,ID：'.$id.'状态：'.$arr['is_self_fd'];
            $this->Account_level_model->Syslog($log);
            showmessage('操作层级成功',URL.'/cash/Account_level/index');
        }else{
        	showmessage('操作层级失败',URL.'/cash/Account_level/index');
        }
	}

	//支付设置
	public function level_pay_set(){
		$map = $arr = array();
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$arr['RMB_pay_set'] = $this->input->post('RMB');
		if (empty($id) || empty($arr['RMB_pay_set'])) {
            showmessage('参数错误',URL.'/Account_level/index',0);
		}
        $map['table'] = 'k_user_level';
        $map['where']['id'] = $id;
	    if ($this->Account_level_model->rupdate($map,$arr)) {
	        $log['log_info'] = '设置层级支付平台,ID：'.$name;
            $this->Account_level_model->Syslog($log);
            showmessage('设置层级支付平台成功','index');
	    }else{
	    	showmessage('设置层级支付平台失败','index',0);
	    }
	}

	//获取支付平台
	function get_pay_set(){
	    $map = array();
		$map['table'] = 'k_cash_config';
		$map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['is_delete'] = '0';
		$map['where']['type_name'] = 'RMB';
        //人民币默认
        $rmb_data = $this->Account_level_model->rget($map);
        unset($map['where']['type_name']);
        $map['where']['type'] = '0';
		$data = $this->Account_level_model->rget($map);
		if (!empty($rmb_data) && !empty($data)) {
		    return array_merge($rmb_data,$data);
		}else{
			return $rmb_data;
		}
	}

	//会员列表
	public function mem_level_index(){
	    $page = $this->input->get('page');
	    $id = $this->input->get('id');//层级ID
	    $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $username = $this->input->get('username');
	    $db_model['tab'] = 'k_user';
        $db_model['base_type'] = 1;
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['shiwan'] = 0;
        if (!empty($id)) { $map['level_id'] = $id; }
        if (!empty($username)) {
            $map['username'] = array('like','%'.$username.'%');
            $this->add('username',$username);
        }

        $map['index_id'] = $index_id;
        $count = $this->Account_level_model->mcount($map,$db_model);
	    $perNumber=100;
	    $totalPage=ceil($count/$perNumber);
	 	$page=isset($page)?$page:1;
	 	if ($page > $totalPage) {
	 		$page = 1;
	 	}
	    $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
		$limit=$startCount.",".$perNumber;
		$map['is_locked'] = 1;
		$data_l = $this->Account_level_model->get_user($map,$limit);//锁定数据

		$data_l = array_values($data_l);

		$map['is_locked'] = 0;
		$data_n = $this->Account_level_model->get_user($map,$limit);//未锁定数据
		$data_n = array_values($data_n);

		//获取层级
		$map_l = array();
		$map_l['site_id'] = $_SESSION['site_id'];
		$map_l['index_id'] = $index_id;

		$off_level = $this->Account_level_model->get_level($map_l);

		  //多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->Account_level_model->select_sites()));
	    }

	    $this->add('data_l',$data_l);
	    $this->add('data_n',$data_n);
	    $this->add('index_id', $index_id);
	    $this->add('level_id',$id);
	    $this->add('site_id',$_SESSION['site_id']);
	    $this->add('off_level',$off_level);
	    $this->add('page', $this->Account_level_model->get_page('k_user',$totalPage,$page));
        $this->display('cash/mem_level_index.html');
	}

	//单会员状态处理
	public function locked_do(){
		$type = $this->input->get('type');
		$uid = $this->input->get('uid');
		$state = $this->input->get('state');
		if (empty($type) || empty($uid)) {
		    showmessage('参数错误',URL.'/cash/account_level/index',0);
		}
		if ($type == 'is_locked') {
		    $arr['is_locked'] = $state;
            $map['table'] = 'k_user';
            $map['where']['uid'] = $uid;
            if ($this->Account_level_model->rupdate($map,$arr)) {
                $log['log_info'] = '设置会员锁定,ID：'.$uid;
                $this->Account_level_model->Syslog($log);
                showmessage('设置成功',URL.'/cash/account_level/index');
            }else{
                showmessage('设置失败',URL.'/cash/account_level/index',0);
            }
		}
	}

	//会员层级更改
	public function mem_level_do(){
		$new_level = $this->input->get('new');
		$new_level = array_filter($new_level);
		 //锁定会员数据
		$lockeds = $this->input->get('lockeds');

		if (empty($new_level) && empty($lockeds)) {
		    showmessage('未选择数据',URL.'/cash/account_level/index',0);
		}

        $map = array();
        $map['table'] = 'k_user';
        $map['map'] = 'uid';
        //$aaa = $this->Account_level_model->mupdate($new_level,$map);
        //
        if (!empty($new_level)) {
        	 $map['key'] = 'level_id';
        	 $this->Account_level_model->mupdate($new_level,$map);
        	 $logstr = '设置会员层级';
		    //showmessage('参数错误',URL.'/cash/account_level/index',0);
		}

		if (!empty($lockeds)) {
			 $map['key'] = 'is_locked';
        	 $this->Account_level_model->mupdate($lockeds,$map);
		    //showmessage('参数错误',URL.'/cash/account_level/index',0);
		     if ($logstr) {
		         $logstr .= ',会员锁定';
		     }else{
		     	 $logstr = '设置会员锁定';
		     }
		}

        $log['log_info'] = $logstr;
        $this->Account_level_model->Syslog($log);
        showmessage('设置成功',URL.'/cash/account_level/index');
	}


	//会员详情列表
	public function mem_cash_list(){
        $page = $this->input->get('page');
        $level_id = $this->input->get('level_id');
	    $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $username = $this->input->get('username');
	    $db_model['tab'] = 'k_user';
        $db_model['type'] = 1;
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['shiwan'] = 0;
        if (!empty($level_id)) { $map['level_id'] = $level_id; }
        if (!empty($username)) {
            $map['username'] = array('like','%'.$username.'%');
            $this->add('username',$username);
        }

        $map['index_id'] = $index_id;
        $count = $this->Account_level_model->mcount($map,$db_model);
	    $perNumber=50;
	    $totalPage=ceil($count/$perNumber);
	 	$page=isset($page)?$page:1;
	 	if ($page > $totalPage) {
	 		$page = 1;
	 	}
	    $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
		$limit=$startCount.",".$perNumber;
		$data = $this->Account_level_model->get_user($map,$limit);

        $uids = "('".implode("','",array_keys($data))."')";
        //会员入款信息
		$userBankin = $this->Account_level_model->user_cash_All($uids,'');
        $userBankrg = $this->Account_level_model->user_in_rg($uids,'');

        // p($userBankin);die();

		//会员出款信息
		$data_xs = $this->Account_level_model->user_out_xs($uids,'');
		$data_rg = $this->Account_level_model->user_out_rg($uids,'');

		// p($data_xs);die();

		foreach ($data as $key => $val) {

		    $data[$key]['bank_in_num'] = $userBankin[$key]['num'] +$userBankrg[$key]['num'] + 0;
		    $data[$key]['bank_in_money'] = $userBankin[$key]['money'] +$userBankrg[$key]['money'] + 0;
		    if ($userBankin[$key]['max_money'] > $userBankrg[$key]['max_money']) {
		        $data[$key]['bank_in_max'] = $userBankin[$key]['max_money'];
		    }else{
		    	$data[$key]['bank_in_max'] = $userBankrg[$key]['max_money'] +0;
		    }


		    $data[$key]['bank_out_num'] = $data_xs[$key]['num'] +$data_rg[$key]['num']+0;
		    $data[$key]['bank_out_money'] = $data_xs[$key]['money']+$data_rg[$key]['money']+0;

            if ($data_xs[$key]['max_money'] > $data_rg[$key]['max_money']) {
		        $data[$key]['bank_out_max'] = $data_xs[$key]['max_money'];
		    }else{
		    	$data[$key]['bank_out_max'] = $data_rg[$key]['max_money'] +0;
		    }
		}

		$data = array_values($data);

	    $this->add('data',$data);
	    $this->add('index_id', $index_id);
	    $this->add('level_id',$level_id);
	    $this->add('page', $this->Account_level_model->get_page('k_user',$totalPage,$page));
        $this->display('cash/mem_cash_list.html');
	}


	//回归操作
	public function regress(){
        $id = $this->input->get('id');//当前层级
        $index_id = $this->input->get('index_id');

        //默认层级
        $map = array();
        $map['table'] = 'k_user_level';
        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['where']['index_id'] = $index_id;
        $map['where']['is_default'] = 1;

        $level_de = $this->Account_level_model->rfind($map);
        if (empty($id) || empty($index_id) || empty($level_de)) {
             showmessage('参数错误',URL.'/cash/account_level/index',0);
        }

        $arr = array();
        $arr['level_id'] = $level_de['id'];
        $map_a = array();
        $map_a['table'] = 'k_user';
        $map_a['where']['level_id'] = $id;
        $map_a['where']['site_id'] = $_SESSION['site_id'];
        $map_a['where']['is_locked'] = 0;//0表示未锁定

        if ($this->Account_level_model->rupdate($map_a,$arr)) {
        	$log['log_info'] = '会员层级回归操作,ID:'.$id;
            $this->Account_level_model->Syslog($log);
            showmessage('回归操作成功',URL.'/cash/account_level/index');
        }else{
        	showmessage('回归操作失败',URL.'/cash/account_level/index',0);
        }

	}
	//分层操作
	public function level_up(){
		//转入层ID
		$level_id_1 = $this->input->post('level_id_1');
		$level_ids = $this->input->post('level_ids');
		$index_id = $this->input->post('index_id');

		if(empty($level_id_1) && empty($level_ids)){
		    showmessage('参数错误',URL.'/cash/account_level/index',0);
        }
		//转入条件
		$map_1 = array();
		$map_1['table'] = 'k_user_level';
		$map_1['select'] = 'level_name,start_date,end_date,deposit_num,deposit_count';
		$map_1['where']['site_id'] = $_SESSION['site_id'];
		$map_1['where']['id'] = $level_id_1;
		$levelA = $this->Account_level_model->rfind($map_1);

		//移动所有符合条件的层级用户
		foreach ($level_ids as $k => $v) {
             //获取符合条件会员
            $level_user = '';
            $level_user = $this->Account_level_model->get_yes_user($v,$levelA);
		    if (empty($level_user)) { continue;}

            //$level_user = '('.implode(',',array_keys($level_user)).')';
            $level_user = array_keys($level_user);
		    $map_u_up = array();
		    $map_u_up['table'] = 'k_user';
		    $map_u_up['where']['site_id'] = $_SESSION['site_id'];
		    $map_u_up['where']['is_locked'] = 0;
		    $map_u_up['where']['index_id'] = $index_id;
		    $map_u_up['where_in'] = array('item'=>'uid','data'=>$level_user);

            $this->Account_level_model->rupdate($map_u_up,array('level_id'=>$level_id_1));
	    }
	    $log['log_info'] = '会员层级分层操作,ID:'.$level_id_1;
        $this->Account_level_model->Syslog($log);
        showmessage('分层成功',URL.'/cash/account_level/index');
	}

	public function get_all_level(){
        $id = $this->input->get('id');
        $index_id = $this->input->get('index_id');
	    //获取层级
		$map = array();
		$map['site_id'] = $_SESSION['site_id'];
		$map['index_id'] = $index_id;
		$map['id'] = array('<>',$id);
		$map['is_delete'] = 0;
		$level_edit = $this->Account_level_model->get_level($map);
        //拼接
        foreach ($level_edit as $k => $v) {
          $Levelmain .= '<tr><td align="center"><input type="checkbox" name="level_ids[]" value="'.$v['id'].'" class="idlist"></td>
                <td>'.$v['level_name'].'</td>
                <td>'.$v['level_des'].'</td>
              </tr>';
        }
        $site_url = URL;
$level_html = <<<EOF
<form action="$site_url/cash/account_level/level_up" method="post">
<input name="level_id_1" id="level_id_1" value="$id" type="hidden">
<input name="index_id" id="index_id" value="$index_id" type="hidden">
<div style="overflow:hidden;overflow-y:auto;max-height:400px">
<table width="100%" class="m_tab" style="margin:0;width:300px;">
<tbody>
    <tr class="m_title">
      <td height="27" class="table_bg" colspan="3">
      <span id="title">会员分层</span>
      <span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" onclick="easyDialog.close();">×</a></span>
      </td>
    </tr>
    <tr class="m_title">
      <td class="table_bg" width="60">选择层级</td>
      <td class="table_bg">名稱</td>
      <td class="table_bg">描述</td>
    </tr>
    $Levelmain
    <tr align="center">
      <td class="table_bg1" colspan="13"><input value="確定" id="savebtn" type="submit" class="button_d">&nbsp;&nbsp;&nbsp;
    <input type="button" value="关闭" onclick="easyDialog.close();" class="button_d"></td>
    </tr>
EOF;
    echo $level_html;
    exit;
	}
    //查询
	public function member_search(){
        $this->display('cash/member_search.html');
	}
}
