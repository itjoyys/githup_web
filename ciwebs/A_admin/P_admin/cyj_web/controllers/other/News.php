<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class News extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('other/News_model');
	}

    //new最新公告
	public function index(){
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
        $is_delete = $this->input->get('is_delete');
        $is_delete = empty($is_delete)?'0':$is_delete;
		$chn_simplified = $this->input->get('chn_simplified');
		$index_id = $this->input->get('index_id');
		$index_id = empty($index_id)?'a':$index_id;

        //查询时间判断
        about_limit($end_date,$start_date);

		 //多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.str_replace('全部', '请选择站点',$this->News_model->select_sites()));
	    }

		$map = array();
		$map['site_id'] = $_SESSION['site_id'];
		$map['index_id'] = $index_id;
		$map['is_delete'] = $is_delete;
		if (!empty($start_date) && !empty($end_date)) {
		    $map['add_time'] = array(
		    	               array('>=',$start_date.' 00:00:00'),
                               array('<=',$end_date.' 23:59:59')
		    	               );
		}
		//内容检索
		if (!empty($chn_simplified)) {
		    $map['chn_simplified'] = array('like','%'.$chn_simplified.'%');
		}

		$data = $this->News_model->M(array('tab'=>'k_message','type'=>1))->where($map)->order("add_time desc")->select();

		foreach ($data as $key => $val) {
		    //显示类型
	        if($val['show_type'] == 2){
	        	$data[$key]['show_type_zh'] = '跑马灯';
			    if($val['state']==1){
				    $data[$key]['state_zh'] = '全站';
				}elseif($val['state']==2){
					$data[$key]['state_zh'] = '指定頁面';
				}
			}elseif($val['show_type'] == 1){
				$data[$key]['show_type_zh'] = '彈出';
			    $data[$key]['state_zh'] = '登陸頁面彈出';
			}

			//游戏类型
			if(strstr($val['game_type'],'1')){
			    $data[$key]['game_type_zh'] = '體育';
			}
			if(strstr($val['game_type'],'2')){
			    $data[$key]['game_type_zh'] .= ' 彩票';
			}
			if(strstr($val['game_type'],'3')){
			    $data[$key]['game_type_zh'] .= ' 視訊';
			}

			if ($val['lxxz'] == '1') {
				$data[$key]['level_power_zh'] = '指定用户';
			}elseif($val['lxxz'] == '3'){
				$data[$key]['level_power_zh'] = '全部用户';
			}elseif ($val['lxxz'] == '2') {
			    $data[$key]['level_power_zh'] = '层级';
			}
		}
		$this->add('data',$data);
		$this->add('index_id',$index_id);
        $this->display('other/news_index.html');
	}

	//发布公告
	public function news_add(){
		$id = $this->input->get('id');
		$index_id = $this->input->get('index_id');
		$index_id = empty($index_id)?'a':$index_id;
		if (!empty($id)) {
			$info = $this->News_model->rfind(array('table'=>'k_message','where'=>array('id'=>$id,'site_id'=>$_SESSION['site_id'])));
			if(strstr($info['game_type'],'1')){
			    $this->add('sp_1',1);
			}
			if(strstr($info['game_type'],'2')){
			    $this->add('fc_1',1);
			}
			if(strstr($info['game_type'],'3')){
			    $this->add('vm_1',1);
			}
		    $this->add('info',$info);
		}else{
            if (!empty($_SESSION['index_id'])) {
		    	$this->add('sites_str',str_replace('全部', '请选择站点',$this->News_model->select_sites()));
		    	$this->add('index_id',$index_id);
		    }

		}
		//层级
		$level = $this->News_model->rget(array('table'=>'k_user_level','where'=>array('site_id'=>$_SESSION['site_id'],'index_id'=>$index_id)));
		if (!empty($info['level_power'])) {
		    //层级
		    foreach ($level as $key => $val) {
		        if (strstr($info['level_power'],$val['id'])) {
		            $level[$key]['is_cstate'] = 1;
		        }
		    }
		}
		$this->add('level',$level);
		$this->add('index',$index_id);
	    $this->display('other/news_add.html');
	}

	//发布公告处理
	public function news_add_do(){
        $id = $this->input->get('id');
        $index_id = $this->input->get('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $showtype = $this->input->get('showtype');
        $state = $this->input->get('state');
        $levellx = $this->input->get('levellx');
        $simplify = $this->input->get('simplify');
        $zduser = $this->input->get('zduser');
        //p($zduser);die;
        $gametype = $this->input->get('gametype');
        $lxxz = $this->input->get('lxxz');

        foreach ($gametype as $k => $v) {
			$data['game_type'] .= $v.',';
		}

		if($lxxz == 1){	//指定用户时
			$data['level_power'] = '-1';
		}elseif ($lxxz == 2){	//层级选择时
			foreach ($levellx as $kl => $vl) {
				$data['level_power'] .= $vl.',';
			}
		}elseif ($lxxz == 3){	//全体用户时
			$data['level_power'] = '-2';
		}

		$data['game_type'] = rtrim($data['game_type'],',');
		$data['level_power'] = rtrim($data['level_power'],',');
		$data['show_type'] = $this->input->get('showtype');	//显示类型
		$data['chn_simplified'] = $simplify;//简体
		$data['state'] = $state;
		$data['name'] = $_SESSION['login_name'];//发布者
		$data['zduser'] = $zduser; //指定发送人
		$data['lxxz'] = $lxxz;

		if (!empty($id)) {
		    //编辑
		    $map = array();
		    $map['table'] = 'k_message';
		    $map['where']['site_id'] = $_SESSION['site_id'];
		    $map['where']['id'] = $id;
            if ($this->News_model->rupdate($map,$data)) {
                $log['log_info'] = '操作最新消息,ID：'.$id;
		        $this->News_model->Syslog($log);
		        showmessage('操作成功',URL.'/other/news/index');
            }else{
                showmessage('操作失败',URL.'/other/news/index',0);
            }
		}else{
			//添加
			$data['add_time'] = date('Y-m-d H:i:s');
			$data['index_id'] = $index_id;
			$data['site_id'] = $_SESSION['site_id'];
			//p($data);die;
			if ($this->News_model->radd('k_message',$data)) {
                $log['log_info'] = '添加最新消息';
		        $this->News_model->Syslog($log);
		        showmessage('操作成功',URL.'/other/news/index');
            }else{
                showmessage('操作失败',URL.'/other/news/index',0);
            }
		}

	}

	//最新消息处理
	public function news_act(){
	    $type = $this->input->get('type');
	    $id = $this->input->get('id');
	    if (empty($id) || !strstr('012',$type)) {
	        showmessage('参数错误','back',0);
	    }
        $map = array();
        $map['table'] = 'k_message';
        $map['where']['id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
	    if ($this->News_model->rupdate($map,array('is_delete'=>$type))) {
	    	$log['log_info'] = '操作最新消息,ID：'.$id;
	        $this->News_model->Syslog($log);
	        showmessage('操作成功',URL.'/other/news/index');
	    }else{
	    	showmessage('操作失败',URL.'/other/news/index',0);
	    }
	}

	//会员消息
	public function member_msg_index(){
		$type = $this->input->get('type');//消息类型
		$username = $this->input->get('username');
		$start_date = $this->input->get('start_date');
		$start_date = empty($start_date)?date('Y-m-d'):$start_date;
		$end_date = $this->input->get('end_date');
		$end_date = empty($end_date)?date('Y-m-d'):$end_date;
		$page = $this->input->get('page');
		$map = array();
		$map['site_id'] = $_SESSION['site_id'];
		$map['is_delete'] = 0;
		if (!empty($type)) {
		    $map['type'] = $type;
		}

		//查询时间判断
        about_limit($end_date,$start_date);

		if (!empty($username)) {
             $auid = $this->News_model->M(array('tab'=>'k_user','type'=>1))
		          ->where("username = '".$username."' and site_id = '".$_SESSION['site_id']."'")
		          ->getField("uid");
		     $map['uid'] = $auid;
		}

		$map['msg_time'] = array(
			               array('>=',$start_date.' 00:00:00'),
			               array('<=',$end_date.' 23:59:59')
			               );
		//p($map);die;
		$count= $this->News_model->M(array('tab'=>'k_user_msg','type'=>1))->where($map)->order("msg_id DESC")->count();
		//p($count);
		//分页
		$perNumber = 50;
		$totalPage = ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		if($totalPage<$page){
		  $page = 1;
		}
		$startCount=($page-1)*$perNumber;
		$limit=$startCount.",".$perNumber;
		$user_msg = $this->News_model->M(array('tab'=>'k_user_msg','type'=>1))->where($map)->order("msg_id DESC")->limit($limit)-> select();
		$this->add('user_msg',$user_msg);
		$page = $this->News_model->M(array('tab'=>'k_user_msg','type'=>1))->showPage($totalPage,$page);
		$this->add('page',$page);
		$this->add('start_date',$start_date);
		$this->add('end_date',$end_date);
	    $this->display('other/member_msg_index.html');
	}

	//发布会员消息
	public function member_msg_add(){
            //层级
            $index_id = $this->input->post('index_id');
            $index_id = empty($index_id)?'a':$index_id;
            $map_l = array();
            $map_l['table'] = 'k_user_level';
            $map_l['where']['site_id'] = $_SESSION['site_id'];
            $map_l['where']['index_id'] = $index_id;

            if (!empty($_SESSION['index_id'])) {
                $this->add('sites_str',str_replace('全部', '请选择站点',$this->News_model->select_sites()));
                $this->add('index_id',$index_id);
            }

            $this->add('level',$this->News_model->rget($map_l));
            $this->display('other/member_msg_add.html');
	}

	//发布会员消息处理
	public function member_msg_add_do(){
		$msg_title = $this->input->post('msg_title');
		$msg_info = $this->input->post('msg_info');
		$wtype = $this->input->post('wtype');
		$level = $this->input->post('level');
		$user = $this->input->post('user');
        $index_id = $this->input->post('index_id');
        $index_id = empty($index_id)?'a':$index_id;
        $data['msg_from']='系统消息';
		$data['msg_title']=$msg_title;
		$data['msg_info']=$msg_info;
        $data['index_id']=$index_id;
		$data['site_id']= $_SESSION['site_id'];
		$data['type']=1;
		$data['level_id']='-1';

		if ($wtype == 1) {
			$data['level']=0;
			$data['level_id']='-1';
		}elseif ($wtype == 2) {
			$data['level'] = 1;
			$level_str='';
			if(!empty($level)){
				foreach ($level as $k => $v) {
					$level_str = $level_str.','.$v;
				}
			}
			$level_str=trim($level_str,",");
			$data['level_id']=$level_str;
		}elseif ($wtype == 3) {
		    $user=explode(',',$user);
			$uid='';
			foreach($user as $v){
				$mab['username'] = $v;
				$mab['site_id'] = $_SESSION['site_id'];
				$users=$this->News_model->M(array('tab'=>'k_user','type'=>1))->where($mab)->find();
				if($users){
					if($uid==''){
						$uid = $users['uid'];
					}else{
						$uid.=','.$users['uid'];
					}
				}
			}
			if($uid!=''){
				$data['uid'] = $uid;
				$data['level'] = 2;
			}
		}
		if ($this->News_model->radd('k_user_msg',$data)) {
		    $log['log_info'] = '添加会员消息';
	        $this->News_model->Syslog($log);
	        showmessage('操作成功',URL.'/other/news/member_msg_index');
		}else{
		    showmessage('添加失败',URL.'/other/news/member_msg_index',0);
		}


	}

	//会员消息处理
	public function member_msg_act(){
		$id = $this->input->get('id');
		if (empty($id)) {
			showmessage('参数错误','back',0);
		}
        $map = array();
        $map['table'] = 'k_user_msg';
        $map['where']['msg_id'] = $id;
        $map['where']['site_id'] = $_SESSION['site_id'];
		if ($this->News_model->rupdate($map,array('is_delete'=>1))) {
            $log['log_info'] = '删除会员消息,ID：'.$id;
	        $this->News_model->Syslog($log);
	        showmessage('操作成功',URL.'/other/news/member_msg_index');
        }else{
            showmessage('操作失败',URL.'/other/news/member_msg_index',0);
        }


	}



}