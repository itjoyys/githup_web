<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Note_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//获取代理
	public function get_agents($map){
		$db_model['tab'] = 'k_user_agent';
		$db_model['type'] = 1;
        return $this->M($db_model)->field('id,agent_user')->where($map)->select('id');
	}

            //获取体育注单详情
    public function get_information($gid){
        $db_model['tab'] = 'k_bet_cg';
        $db_model['type'] = 1;
        return $this->M($db_model)->field('bet_info,match_name,master_guest')->where(array('gid'=>$gid))->select();
    }

    //获取体育注单
    public function get_sp_bet($type,$map,$limit){
        if ($type == 'cg') {
            $db_model['tab'] = 'k_bet_cg_group';
            $db_model['type'] = 1;
            return $this->M($db_model)->where($map)->limit($limit)->order("gid DESC")->select();
        }else{
            $db_model['tab'] = 'k_bet';
            $db_model['type'] = 1;

            return $this->M($db_model)->where($map)->limit($limit)->order("bid DESC")->select();
        }
    }

    //体育总计
    public function get_sp_count($map,$type = ''){
        $db_model['type'] = 1;
        if ($type == 'cg') {
            $db_model['tab'] = 'k_bet_cg_group';
            return $this->M($db_model)->field("count(gid) as num,sum(bet_money) as money")->where($map)->find();
        }else{
            $db_model['tab'] = 'k_bet';
            return $this->M($db_model)->field("count(bid) as num,sum(bet_money) as money")->where($map)->find();
        }
    }


    //获取视讯记录
    public function get_video_bet($type,$uname,$date = array(),$limit){
        if (!empty($uname)) {
            $map['pkusername'] = $uname;
        }
        $field_name=$this->time_type($type);
        if ($_SESSION['guanliyuan'] == 1){
            $agentsstr = $_SESSION['agent_ids'];
            $map['agent_id'] = array('in','('.$agentsstr.')');
        }else{
            $map['agent_id'] = $_SESSION['agent_id'];
        }
        $map['site_id'] = $_SESSION['site_id'];
        //$map['index_id'] = $_SESSION['index_id'];
        $order = $field_name[0].' desc';
        $map[$field_name[0]]=array(array('>=',$date[0]),array('<=',$date[1]));
        //var_dump($map);die;
        $db_model['tab'] = $type.'_bet_record';
    	$db_model['type'] = 3;

        return $this->M($db_model)->where($map)->limit($limit)->order($order)->select();

    }

    //视讯记录数量
    public function vcount($type,$uname,$date = array()){
    	if (!empty($uname)) {
            $map['pkusername'] = $uname;
        }
        $field_name=$this->time_type($type);

        $map[$field_name[0]] = array(array('>=',$date[0]),array('<=',$date[1]));
        if ($_SESSION['guanliyuan'] == 1){
            $agentsstr = $_SESSION['agent_ids'];
            $map['agent_id'] = array('in','('.$agentsstr.')');
        }else{
            $map['agent_id'] = $_SESSION['agent_id'];
        }
        $map['site_id'] = $_SESSION['site_id'];
        //$map['index_id'] = $_SESSION['index_id'];
        $db_model['tab'] = $type.'_bet_record_'.date('Ym',strtotime($date[0]));
    	$db_model['type'] = 3;

        return $this->M($db_model)->where($map)->count();
    }
    //根据不同视讯返回对应数据库字段
    public function time_type($type){
        //注单时间 下注 有效下注 结果 游戏类型 下注详情
        switch ($type)
        {
        case 'lebo':
            $field_name = array('betstart_time','betamount',
                                'valid_betamount','payout','game_type','bet_detail');
            break;
        case 'bbin':
            $field_name = array('wagers_date','betamount',
                                'commissionable','payoff','gametype');
            break;
        case 'mg':
            $field_name = array('date','all_income',
                                'income','payout');
            break;
        case 'ag':
            $field_name = array('bet_time','bet_amount',
                                'valid_betamount','netamount','game_type');
          break;
        case 'ct':
            $field_name = array('transaction_date_time','betpoint',
                                'availablebet','win_or_loss-betpoint','game_type');
            break;
        case 'og':
            $field_name = array('add_time','betting_amount',
                                'valid_amount','win_lose_amount','game_name_id','game_betting_content');
          break;
        default:
            $field_name = array('betstart_time','betamount',
                                'valid_betamount','payout');
        }
        return $field_name;
    }
    //获取总计
    public function video_num($type,$date = array(),$username = ''){

        $db_model['tab'] = $type.'_bet_record';
	    $db_model['type'] = 3;
        $field_name = $this->time_type($type);

        if(!empty($username)){
            $map['pkusername'] = $username;
        }
        if ($_SESSION['guanliyuan'] == 1){
            $agentsstr = $_SESSION['agent_ids'];
            $map['agent_id'] = array('in','('.$agentsstr.')');
        }else{
            $map['agent_id'] = $_SESSION['agent_id'];
        }
        $map[$field_name[0]] = array(array('>=',$date[0]),array('<=',$date[1]));

        $video_arr = $this->M($db_model)->field("sum($field_name[1]) as sum1,sum($field_name[2]) as sum2,sum($field_name[3]) as sum3")->where($map)->select();
        return $video_arr[0];
    }
}