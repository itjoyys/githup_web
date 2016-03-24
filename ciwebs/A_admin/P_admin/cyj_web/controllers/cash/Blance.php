<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blance extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Blance_model');
	}

	public function index()
	{
		$index_id = $this->input->get('index_id');//站点ID
		$agent_id = $this->input->get('agent_id');//代理ID

        $map = array();

        $map['site_id'] = $_SESSION['site_id'];
        if (!empty($agent_id)) {
            $map['agent_id'] = $agent_id;
        }
        if (!empty($index_id)) {
            $map['index_id'] = $index_id;
        }
        $map['is_delete'] = 0;
        $map['shiwan'] = 0;


        $blance_on = $this->Blance_model->get_user_blance_on($map);


        $map['is_delete'] = 2;
        $blance_off = $this->Blance_model->get_user_blance_on($map);
		/*$sum = $blance_on['money'] + $blance_on['ag'] + $blance_on['og']
		      +$blance_on['mg']+$blance_on['bbin']+$blance_on['ct']
		      +$blance_on['lebo'];

		$sums = $blance_off['money'] + $blance_off['ag'] + $blance_off['og']
		        +$blance_off['mg'] + $blance_off['bbin'] + $blance_off['ct']
		        +$blance_off['lebo'];*/

				//多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.$this->Blance_model->select_sites());
	    }



        //获取站点视讯配置
        $video_money = array();
        $vdata = $this->Blance_model->get_video_config();
        if ($vdata) {
            $sum = $blance_on['money'];
            $sums = $blance_off['money'];
            $video_money[0]['vtype'] = 'money';
            $video_money[0]['title'] = '系统额度';
            $video_money[0]['on'] = $blance_on['money'];
            $video_money[0]['off'] = $blance_off['money'];
            foreach ($vdata as $key => $val) {
                if ($val == 'eg') {
                    unset($vdata[$key]);
                }else{
                    $video_money[$key+1]['vtype'] = $val;
                    $video_money[$key+1]['title'] = strtoupper($val).'额度';
                    $video_money[$key+1]['on'] = $blance_on[$val];
                    $video_money[$key+1]['off'] = $blance_off[$val];
                    $sum = $sum + $blance_on[$val];
                    $sums = $sums + $blance_off[$val];
                }
            }
        }


        $map_b = array();
        $map_b['table'] = 'k_user_agent';
        $map_b['where']['site_id'] = $_SESSION['site_id'];
        $map_b['where']['agent_type'] = 'a_t';
        $map_b['where']['is_delete'] = 0;
        $map_b['where']['is_demo'] = 0;
        if (!empty($index_id)) {
            $map_b['where']['index_id'] = $index_id;
        }

        $this->add('video_money',$video_money);
        $this->add('agent',$this->Blance_model->rget($map_b));
		//$this->add('blance_on',$blance_on);
		$this->add('index_id',$index_id);
		$this->add('agent_id',$agent_id);
		//$this->add('blance_off',$blance_off);
		$this->add('u_date',date('Y-m-d H:i:s'));
		$this->add('sum',$sum);
		$this->add('sums',$sums);
		$this->display('cash/blance.html');
	}

	//统计各个代理总额度
	public function list_blance(){
        $type = $this->input->get('type');//额度类型
        $utype = $this->input->get('utype');//统计类型
        $field = 'money';
        if (!empty($type)) {
            $field = $type.'money';
        }

	}


     //查询代理
    public function  blance_agent(){
    	$type = $this->input->get('type');
    	$agent_id = $this->input->get('agent_id');
    	$field = 'money';
        if (!empty($type)) {
            $field = $type.'_money';
        }
        $index_id = $this->input->get('index_id');
    	$agent_user = $this->input->get('agentname');

    	if (!empty($index_id)) {
            $map['index_id'] = $index_id;
        }

        if (!empty($agent_user)) {
            $agentn = $this->Blance_model->get_agent_name($agent_user);
            //$agentid = array_keys($agentn);
            $map['agent_id'] = $agentn[0]['id'];
           // unset($map['agent_user']);
        }elseif(!empty($agent_id)){
            $map['agent_id'] = $agent_id;
        }

    	$data = $this->Blance_model->get_agent($map['agent_id'],$field);

        // $agent_id = array_keys($data);
    	// $agent_id = '(' . implode(',', $agent_id) . ')';
    	// $mapa['id'] = array('in' , $agent_id);
        //查询代理
        $agent_name = $this->Blance_model->get_agent_name();
        //p($mapa);die;
        foreach ($data as $key => $value) {
    	    $sum += $value['yes'];
            $sums += $value['no'];
            $data[$key]['agent_user'] = $agent_name[$value['agent_id']]['agent_name'].'【'.$agent_name[$value['agent_id']]['agent_user'].'】';
        }


        $data = array_values($data);

        $this->add('$agent_name',$agent_name);
    	$this->add('data_on',$data);
    	$this->add('index_id',$index_id);
    	$this->add('u_date',date('Y-m-d H:i:s'));
    	$this->add('sum',$sum);
        $this->add('sums',$sums);
    	$this->display('cash/blance_agent.html');
    }

    //查询代理下的会员
    public function get_user(){
        $page = $this->input->get('page');
    	$type = $this->input->get('type');
    	$field = 'money';
        if (!empty($type)) {
            $field = $type.'_money';
        }
    	$agent_id = $this->input->get('agent_id');
        $username = $this->input->get('username');
        if(!empty($username)){
            $map['username']  = $username;
        }
        $map['agent_id'] = $agent_id;
        $map['shiwan'] = 0;
        //p($map);
    	if (!empty($agent_id)) {
            $map['agent_id'] = $agent_id;
        }
        $map[$field] = array('>' , 0);

        //分页
        $db_model = array();
        $db_model['tab'] = 'k_user';
        $db_model['type'] = 1;
        $count = $this->Blance_model->mcount($map,$db_model);
        $perNumber=100;
        $totalPage=ceil($count/$perNumber);
        $page=isset($page)?$page:1;
        if($totalPage<$page){
          $page = 1;
        }
        $startCount=($page-1)*$perNumber;
        $limit=$startCount.",".$perNumber;

        $data = $this->Blance_model->get_mem_cash($map,$field,$limit);
        //p($data);die;
        foreach($data as $v){
            $sum += $v['y'];
            $sums += $v['n'];
        }

    	$this->add('data',$data);
        $this->add('type',$type);
        $this->add('sum',$sum);
        $this->add('sums',$sums);
    	$this->add('page', $this->Blance_model->get_page('k_user',$totalPage,$page));
    	$this->add('agent_id',$agent_id);
    	$this->add('u_date',date('Y-m-d H:i:s'));
    	$this->display('cash/get_mem_cash.html');
    }




}
