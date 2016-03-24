<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_analysis extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Member_analysis_model');
	}

    //有效会员列表
	public function effective_index()
	{
		$index_id = $this->input->get('index_id');//站点ID
		$index_id = empty($index_id)?'a':$index_id;
		$page = $this->input->get('page');
		$agent_user = $this->input->get('agent_user');

		$start_date = $this->input->get('start_date');
		$start_date = empty($start_date)?date('Y-m-d'):$start_date;

		$end_date = $this->input->get('end_date');
		$end_date = empty($end_date)?date('Y-m-d'):$end_date;

		$date = array($start_date,$end_date);

        //查询时间判断
        about_limit($end_date,$start_date);

				//多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str',str_replace('全部', '请选择站点','站点：'.$this->Member_analysis_model->select_sites()));
	    }
        $db_model = $map = array();
		 //站点代理商读取
		$map['is_demo'] = 0;
		$map['agent_type'] = 'a_t';
		$map['site_id'] = $_SESSION['site_id'];
		$map['index_id'] = $index_id;
		if (!empty($agent_user)) {
		    $map['agent_user'] = $agent_user;
		    $this->add('agent_user',$agent_user);
		}

		$db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;

        $count = $this->Member_analysis_model->mcount($map,$db_model);

		//分页
		$perNumber = 50;
		$totalPage=ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		$startCount=($page-1)*$perNumber;
		$limit=$startCount.",".$perNumber;
		$agent = $this->Member_analysis_model->get_agent($map,$limit,$date);
	    // p($agent);die();
		$agent =array_values($agent);//重建索引
        $this->add('agent',$agent);
        $this->add('page',$this->Member_analysis_model->get_page('k_user_agent',$totalPage,$page));
		$this->add('index_id',$index_id);
		$this->add('start_date',$start_date);
		$this->add('end_date',$end_date);
        $this->add('site_id',$_SESSION['site_id']);
		$this->display('cash/analysis/effective_index.html');
	}

	//下注分析系统
	public function analysis_note(){
		$username = $this->input->get('username');
		$start_date = $this->input->get('start_date');
		$start_date = empty($start_date)?date('Y-m-d'):$start_date;
		$end_date = $this->input->get('end_date');
		$end_date = empty($end_date)?date('Y-m-d'):$end_date;
        $type = $this->input->get('type');//分析类型
        $type = empty($type)?'fc':$type;
        $atype = $this->input->get('atype');//账号类型
        $order = $this->input->get('order');//排序类型

         //查询时间判断
        about_limit($end_date,$start_date);

        if ($order == '2') {
            $order = 'num';
        }elseif ($order == '3') {
        	$order = 'win';
        }else{
        	$order = 'bet';
        }
        //电子类型处理
        if ($type == 'mgdz') {
            $game_type = 'mgdz';
            $type = 'mg';
        }elseif($type == 'bbdz'){
        	$game_type = 'bbdz';
        	$type = 'bbin';
        }

        $date = array($start_date.' 00:00:00',$end_date.' 23:59:59');
        $map = array('uname'=>$username,'type'=>$type,'atype'=>$atype,'order'=>$order);
        switch ($type) {
        	case 'fc':
        	case 'sp':
        		$data = $this->Member_analysis_model->analysis_fun($map,$date);
        		break;
        	case 'ag':
        	case 'mg':
        	case 'og':
        	case 'ct':
        	case 'lebo':
        	case 'bbin':
        	case 'pt':
        	    if ($atype == '1') {
        	    	$mapa = array();
        	    	$mapa['table'] = 'k_user_agent';
        	    	$mapa['select'] = 'id';
        	    	$mapa['where']['site_id'] = $_SESSION['site_id'];
        	    	$mapa['where']['agent_login_user'] = $username;
        	    	$mapa['where']['agent_type'] = 'a_t';
        	    	$mapa['where']['is_demo'] = 0;
        	        $agent = $this->Member_analysis_model->rfind($mapa);
        	    }
        		$data = $this->Member_analysis_model->analysis_video($username,$agent['id'],$type,$order,$date,$game_type);

        		if ($type != 'mg' && $type != 'pt') {
        			$this->add('wtype',1);
        		}
        		break;
        }
        
        if ($type == 'sp') {
            $data = array_values($data);//索引重建
        }
        $agents = $this->Member_analysis_model->get_all_agents();
        $all = array();
        foreach ($data as $key => $val) {
            $data[$key]['agent_user'] = $agents[$val['agent_id']]['agent_login_user'];
            $tmp_num = $val['win_num']/$val['num']*100;
            $data[$key]['win_lose'] = sprintf("%01.2f", $tmp_num).'%';
            if ($tmp_num > 50) {
                $data[$key]['level'] = '<font style="font-weight: bold;color:red;" >高</font>';
            }elseif ($tmp_num > 30) {
            	$data[$key]['level'] = '<font style="font-weight: bold;color:#243792;" >中</font>';
            }else{
                $data[$key]['level'] = '<font style="font-weight: bold;color:#31790B;" >低</font>';
            }
            $all['all_bet']+= $val['all_bet'];
            $all['bet']+= $val['bet'];
            $all['win']+= $val['win'];
            $all['num']+= $val['num'];
            $all['all_win'] += $val['win'];
            
        }
        $this->add('start_date',$start_date);
        $this->add('end_date',$end_date);
		$this->add('data',$data);
        $this->add('all',$all);
		$this->add('siteid',$_SESSION['site_id']);
	    $this->display('cash/analysis/analysis_note.html');
	}

	//会员查询
	public function analysis_user(){
		$start_date = $this->input->get('start_date');
		$start_date = empty($start_date)?date('Y-m-d'):$start_date;
		$end_date = $this->input->get('end_date');
		$end_date = empty($end_date)?date('Y-m-d'):$end_date;

	     //查询时间判断
        about_limit($end_date,$start_date);

		$agent_id = $this->input->get('agent_id');
        $username = $this->input->get('username');
        $page = $this->input->get('page');
        //获取所有代理数据
        $all_agents = $this->Member_analysis_model->get_all_agents();
        $mapU = array();
        $mapU['site_id'] = $_SESSION['site_id'];
        $mapU['shiwan'] = 0;
        $mapU['reg_date'] = array(
		                      array('>=',$start_date.' 00:00:00'),
		                      array('<=',$end_date.' 23:59:59')
		                    );
        if(!empty($agent_id)) {$mapU['agent_id'] = $agent_id;}
		if(!empty($username)){
			$mapU['username'] = array('like','%'.$username.'%');
		}
        $count = $this->Member_analysis_model->M(array(
            'tab' => 'k_user',
            'type' => 1
       		 ))->where($mapU)->count();
        $perNumber = 100;
		$totalPage =ceil($count/$perNumber);

		$page = isset($page)?$page:1;
		if($totalPage<$page){
		  $page = 1;
		}
		$startCount =($page-1)*$perNumber;
		$limit = $startCount.",".$perNumber;
		$userYxAll = $this->Member_analysis_model->M(array('tab' => 'k_user',
            'type' => 1))->field("uid,username,pay_name,agent_id,reg_date,money,reg_address,qq,mobile,email")->where($mapU)->limit($limit)->select();

        $memauth = trim($_SESSION["mem_auth"]);//会员资料权限
		foreach ($userYxAll as $k=>$v){
            if (!strstr($memauth,'d1') && $_SESSION["quanxian"]!='sadmin') {
                $userYxAll[$k]['mobile'] = '无权查看';
            }
            if (!strstr($memauth,'e1') && $_SESSION["quanxian"]!='sadmin') {
                $userYxAll[$k]['email'] = '无权查看';
            }
            if (!strstr($memauth,'f1') && $_SESSION["quanxian"]!='sadmin') {
                $userYxAll[$k]['qq'] = '无权查看';
            }

	        $userYxAll[$k]['agent_user'] = $all_agents[$v['agent_id']]['agent_user'];
		}

		//分页
	    $this->add('page', $this->Member_analysis_model->get_page('k_user',$totalPage,$page));
	    $this->add('start_date',$start_date);
	    $this->add('end_date',$end_date);
		$this->add('userYxAll',$userYxAll);
		$this->add('agent_data',$all_agents);
		$this->display('cash/analysis/analysis_user.html');
	}

	//优惠分析优惠返水
	public function analysis_dis(){
		$start_date = $this->input->get('start_date');
		$start_date = empty($start_date)?date('Y-m-d'):$start_date;
		$end_date = $this->input->get('end_date');
		$end_date = empty($end_date)?date('Y-m-d'):$end_date;
		$atype = $this->input->get('atype');
        $username = $this->input->get('username');
        $page = $this->input->get('page');

         //查询时间判断
        about_limit($end_date,$start_date);

        //条件组合
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['do_time'] = array(array('>=',$start_date.' 00:00:00'),array('<=',$end_date.' 23:59:59'));
        $map['state'] = 1;//优惠退水

        if ($atype == 1 && !empty($username)) {
            $map['agent_user'] = array('like',$username.'%');
        }elseif (!empty($username)) {
            $map['username'] = array('like',$username.'%');
        }

        //获取页数
        $count = $this->Member_analysis_model->analysis_count($map);

		$perNumber = 150;
		$totalPage=ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		$startCount=($page-1)*$perNumber;
		$limit=$startCount.",".$perNumber;
        $data = $this->Member_analysis_model->analysis_user_dis($map,$limit);
        //获取所有代理商
        // $all_agents = $this->Member_analysis_model->get_all_agents();
        // foreach ($data as $key => $val) {
        //      $data[$key]['agent_user'] = $all_agents[$val['agent_id']]['agent_user'];
        // }
        $all =array();
        $all['zongji'] = $this->Member_analysis_model->analysis_user_dis_all($map);
        $all['zongji'][0]['shixun_fd'] =  $all['zongji'][0]['ag_fd']+$all['zongji'][0]['og_fd']+$all['zongji'][0]['mg_fd']+$all['zongji'][0]['ct_fd']+$all['zongji'][0]['lebo_fd']+$all['zongji'][0]['bbin_fd']+ $all['zongji'][0]['bbdz_fd']+ $all['zongji'][0]['mgdz_fd'] + $all['zongji'][0]['pt_fd'];
       //p($data);die;
        foreach($data as $k =>$v){
        	$all['xiaoji']["all_fd"] += $v['all_fd'];
        	$all['xiaoji']["sp_fd"] += $v['sp_fd'];
        	$all['xiaoji']["fc_fd"] += $v['fc_fd'];
        	$all['xiaoji']["shixun_fd"] += $v['ag_fd']+$v['og_fd']+$v['mg_fd']+$v['ct_fd']+$v['lebo_fd']+$v['bbin_fd']+$v['bbdz_fd']+$v['mgdz_fd']+$v['pt_fd'];

        }
        $this->add('page',$this->Member_analysis_model->get_page('k_user_discount_count',$totalPage,$page));
        $this->add('end_date',$end_date);
        $this->add('all',$all);
        $this->add('start_date',$start_date);
        $this->add('data',$data);
        $this->add('siteid',$_SESSION['site_id']);
        $this->display('cash/analysis/analysis_dis.html');
	}

	//出入款分析
    public function analysis_cash(){
    	$username = $this->input->get('username');
		$start_date = $this->input->get('start_date');
		$start_date = empty($start_date)?date('Y-m-d'):$start_date;
		$end_date = $this->input->get('end_date');
		$end_date = empty($end_date)?date('Y-m-d'):$end_date;
        $atype = $this->input->get('atype');//账号类型 会员账号还是代理账号
        $order = $this->input->get('order');
        $type = $this->input->get('type');
        if ($order == '1') {
        	$order = 'money';
        }else{
        	$order = 'num';
        }
        $page = $this->input->get('page');

        //查询时间判断
        about_limit($end_date,$start_date);

        $date = array($start_date.' 00:00:00',$end_date.' 23:59:59');
        $map = array();
        $map = array('uname'=>$username,'atype'=>$atype,'type'=>$type);

          //获取页数
        $count = $this->Member_analysis_model->analysis_cash_count($map,$date);

		$perNumber = 100;
		$totalPage=ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		$startCount=($page-1)*$perNumber;
		$limit=$startCount.",".$perNumber;
		$all = $this->Member_analysis_model->analysis_cash_all($map,$date,$order);
        $data = $this->Member_analysis_model->analysis_cash($map,$date,$limit,$order);
        $all['xiaoji']['renshu'] = count($data);
        //$data = array_values($data);//索引重建
        if($type==4 || $type==5){
             $agent_info=$this->Member_analysis_model->get_all_agents();
             foreach($data as $k=>$v){
                    $all['xiaoji']["money"] += $v['money'];
                    $all['xiaoji']["ckyh"] += $v['ckyh'];
                    $all['xiaoji']["hkyh"] += $v['hkyh'];
                    $all['xiaoji']["num"] += $v['num'];
                    //根据agent_id，给数组添加agent_user;
                    $data[$k]['agent_user'] = $agent_info[$v['agent_id']]['agent_user'];
            }
        }else{
            foreach($data as $k=>$v){
                    $all['xiaoji']["money"] += $v['money'];
                    $all['xiaoji']["money_dis"] += $v['money_dis'];
                    $all['xiaoji']["num"] += $v['num'];
            }
        }
        $this->add('data',$data);
        $this->add('all',$all);
        $this->add('siteid',$_SESSION['site_id']);
        $this->add('type',$type);
        $this->add('page',$this->Member_analysis_model->get_page('k_user',$totalPage,$page));
        $this->add('start_date',$start_date);
        $this->add('end_date',$end_date);
        $this->display('cash/analysis/analysis_cash.html');
    }


}
