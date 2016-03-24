<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_system extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Cash_system_model');
	}

	public function index()
	{
		//时间条件
		$start_date = $this->input->get('start_date');
		$agent_id = $this->input->get('agent_id');
		$end_date = $this->input->get('end_date');
		$timearea = $this->input->get('timearea');//时区
		$timearea = empty($timearea)?0:$timearea;
		$deptype = $this->input->get('deptype');//类型
		$uid = $this->input->get('uid');//其它链接过来的uid
		$page = $this->input->get('page');
		$username = $this->input->get('username');
		$index_id = $this->input->get('index_id');
        $site_id = $_SESSION['site_id'];
        $is_quanxian = $_SESSION['guanliyuan'];
        $agentsstr = $_SESSION['agent_ids'];
        $agent_up = $this->Cash_system_model->get_agents($index_id,$site_id,$agentsstr,$is_quanxian);
        $this->add('is_guanliyuan', $is_quanxian);
        $this->add('agent_up', $agent_up);
		if (!empty($start_date)) {
			$s_date = $start_date;
		} else {
			$s_date = $start_date = date("Y-m-d");
		}
		if (!empty($end_date)) {
			$e_date = $end_date;
		} else {
			$e_date = $end_date = date("Y-m-d");
		}
		$start_date = strtotime($start_date . ' 00:00:00') - $timearea * 3600;
		$end_date = strtotime($end_date . ' 23:59:59') - $timearea * 3600;

		$start_date = date('Y-m-d H:i:s', $start_date);
        $end_date = date('Y-m-d H:i:s', $end_date);

		$map = "k_user_cash_record.site_id = '".$_SESSION['site_id']."' and k_user_cash_record.cash_date >= '".$start_date."' and k_user_cash_record.cash_date <= '".$end_date."' ";
		//代理
		if ($is_quanxian == 1){
			if (!empty($agent_id)) {
			    $map .= " and k_user_cash_record.agent_id = '".$agent_id."' ";
			}else{
				$agentstr = $_SESSION['agent_ids'];
				 $map .= " and k_user_cash_record.agent_id in (".$agentstr.") ";
			}
		}else{
			$map .= " and k_user_cash_record.agent_id = '".$_SESSION['agent_id']."' ";
		}
		//方式
		if (!empty($deptype)) {
		    $map .= $this->deptype($deptype);
		}
		//账户查询
		if (!empty($username)) {
		    $map .= " and k_user_cash_record.username = '".$username."' ";
		    $this->add('username',$username);
		}
		//其它链接过来的uid
		if (!empty($uid)) {
		   $map .= "and k_user_cash_record.uid = '".$uid."'";
		}
		//获得记录总数
		$db_model = array();
		$db_model['tab'] = 'k_user_cash_record';
        $db_model['base_type'] = 1;
        //p($map);die;
        //$join = 'left join k_user on k_user.uid = k_user_cash_record.uid';
		$count = $this->Cash_system_model->mcount($map,$db_model,$join);
		//分页
		$page_num = 50;
		$totalPage = ceil($count/$page_num);
		$page = isset($page)?$page:1;
		if($totalPage<$page){ $page = 1;}
		$startCount=($page-1)*$page_num;
		$limit=$startCount.",".$page_num;
		$record = array();
		$record = $this->Cash_system_model->get_all_system($map,$limit);
		$count_c = 0;
		foreach ($record as $key => $val) {
		    $record[$key]['cash_type_zh'] = $this->cash_type_r($val['cash_type']);
		    $record[$key]['cash_do_type_zh'] = $this->cash_do_type_r($val['cash_do_type']);
		    $count_c +=$val['cash_num']+$val['discount_num'];
		}
	    $this->add('record',$record);
	    //总计
	    $this->add('all_count',$this->Cash_system_model->get_all_count($map));
	    //分页
	    $this->add('page', $this->Cash_system_model->get_page('k_user_cash_record',$totalPage,$page));
	    $this->add('s_date',$s_date);
	    $this->add('page_num',$page_num);
	    $this->add('count_c',$count_c);
	    $this->add('deptype',$deptype);
	    $this->add('timearea',$timearea);
	    $this->add('e_date',$e_date);
		$this->display('cash/cash_system.html');
	}


	 //条件类型处理
	function deptype($type){
        if (empty($type)) {return '';}
		$arrType = explode('-', $type);
		if (count($arrType) > 1) {
		     //表示检索参数cash_do_type
		    return " and ((k_user_cash_record.cash_do_type = '".$arrType[0]."' and k_user_cash_record.cash_type = '".$arrType[1]."' ) or k_user_cash_record.cash_do_type = '".$arrType[2]."') ";
		}
		switch ($type) {
			case 'xsqk'://线上取款 包括拒绝取消
				return " and k_user_cash_record.cash_type in (7,23,19) ";
				break;
			case 'in'://入款明细 公司入款 线上入款 人工存入 优惠退水 优惠活动
				return " and (k_user_cash_record.cash_do_type = '3' or k_user_cash_record.cash_type in (6,9,10,11)) ";
				break;
			case 'out'://出款明细
				return " and ((k_user_cash_record.cash_do_type in (2,4) and k_user_cash_record.cash_type = '12') or k_user_cash_record.cash_type in (7,19,23)) ";
				break;
			case 'ot'://全网活动优惠
				return " and k_user_cash_record.cash_only = '1' ";
				break;
			case 'wx'://无效注单
				return " and  k_user_cash_record.cash_type in (22,25,26) ";
				break;
			case 'cel'://取消注单
				return " and  k_user_cash_record.cash_type in (27,28) ";
				break;
			default:
				return " and k_user_cash_record.cash_type = '".$type."' ";
				break;
		}
	}
    //类型
	function cash_type_r($type){
        switch ($type) {
	     case '1':
	       return '額度轉換';
	       break;
	     case '2':
	       return '体育下注';
	       break;
	     case '3':
	       return '彩票下注';
	       break;
	     case '4':
	       return '视讯下注';
	       break;
	     case '5':
	       return '彩票派彩';
	       break;
	     case '6':
	       return '活动优惠';
	       break;
	    case '7':
	       return '系统拒绝出款';
	       break;
	    case '8':
	       return '系统取消出款';
	       break;
	     case '9':
	       return '优惠退水';
	       break;
	     case '10':
	       return '在线存款';
	       break;
	     case '11':
	       return '公司入款';
	       break;
	     case '12':
	       return '存入取出';
	       break;
	     case '13':
	       return '优惠冲销';
	       break;
	     case '14':
	       return '彩票派彩';
	       break;
	     case '15':
	       return '体育派彩';
	       break;
	     case '19':
	       return '线上取款';
	       break;
	     case '20':
	       return '和局返本金';
	     case '22':
	       return '体育无效注单';
	     case '23':
	       return '系统取消出款';
	     case '24':
	       return '系统拒绝出款';
	     case '25':
	       return '彩票无效注单';
	     case '26':
	       return '彩票无效注单(扣本金)';
	     case '27':
	       return '注单取消(彩票)';
	     case '28':
	       return '注单取消(体育)';
	   }
	}

	//返回交易类别
	function cash_do_type_r($do_type){
	   switch ($do_type) {
	     case '1':
	       return '存入';
	       break;
	     case '2':
	       return '取出';
	       break;
	     case '3':
	       return '人工存入';
	       break;
	      case '4':
	       return '人工取出';
	       break;
	      case '5':
	       return '扣除派彩';
	       break;
	      case '6':
	       return '返回本金';
	       break;
	   }
	}


}
