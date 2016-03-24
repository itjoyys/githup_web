<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Report_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    Public function report_fc($map_fc,$type = 0,$time_str="addtime",$table="c_bet",$id="id"){
        $tmp_str = '-'.$type.' day';
        $tmp_field = 'count('.$id.') as num';
        $map_fc["$time_str"]  = array(array('>=',date('Y-m-d',strtotime($tmp_str))." 00:00:00"),array('<=',date('Y-m-d',strtotime($tmp_str))." 23:59:59"));
        $db_model['tab'] = $table;
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        return $this->M($db_model)->field($tmp_field)->where($map_fc)
                    ->find();
    }

    //总报表类型 date时间 agent_id代理ID varr报表类型 rtype代理类型
    Public function get_report_result($date =array(),$aids,$varr =array(),$gid =''){
        $data = array();
        if ($gid) {$data['0'] = array();}//总报表
        foreach ($varr as $key => $val) {
            switch ($val) {
                case 'fc':
                    $data['fc'] = $this->get_report_fc($date,$aids,$val,$gid);
                    if ($gid) {
                        $data[0] = array_merge_recursive($data[0],$data['fc']);
                    }
                    break;
                case 'sp':
                    $data['sp'] = $this->get_report_sp($date,$aids,$val,$gid);
                    if ($gid) {
                        $data[0] = array_merge_recursive($data[0],$data['sp']);
                    }
                    break;
                case 'ag':
                case 'og':
                case 'mg':
                case 'ct':
                case 'pt':
                case 'eg':
                case 'lebo':
                case 'bbin':
                    $data[$val] = $this->get_report_video($date,$aids,$val,'',$gid);
                    if ($gid) {
                        $data[0] = array_merge_recursive($data[0],$data[$val]);
                    }
                    if ($val == 'bbin') {
                        $data['bbdz'] = $this->get_report_video($date,$aids,$val,'bbdz',$gid);
                        if ($gid) {
                            $data[0] = array_merge_recursive($data[0],$data['bbdz']);
                        }

                    }elseif($val == 'mg'){
                        $data['mgdz'] = $this->get_report_video($date,$aids,$val,'mgdz',$gid);

                        if ($gid) {
                            $data[0] = array_merge_recursive($data[0],$data['mgdz']);
                        }
                    }
                    break;
                default:
                    return '0000';
                    break;
            }
        }

        return array_filter($data);
    }

    //彩票报表
    public function get_report_fc($date =array(),$agent_id ='',$type ='',$gid){
        //代理ID
        if (!empty($agent_id)) {
            if (strstr($agent_id,',')) {
                $map['agent_id'] = array('in','('.$agent_id.')');
            }else{
                $map['agent_id'] = $agent_id;
            }
        }else{
            //除去测试代理
            $agent_test_id = $this->get_agent_test_id();
            $map['agent_id'] = array('<>',$agent_test_id);
        }

        //结算时间
        $map['update_time'] = array(array('>=',$date[0]),array('<=',$date[1]));
        $map['site_id'] = $_SESSION['site_id'];
        $map['js'] = 1;
        $db_model = array();
        $db_model['tab'] = 'c_bet';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        $obj = $this->M($db_model);
        $obj->field("count(id) as num,sum(money) as all_bet,sum(case when status in (1,2) then money end) as bet,sum(case when status in (1,2) then win end) as win,agent_id,username,uid");
        $obj->where($map);
        if ($gid == '1') {
            $obj->group('agent_id');
            return $obj->select('u-agent_id');
        }elseif ($gid == '2') {
            $obj->group('uid');
            return $obj->select('u-username');
        }else{
            return $obj->find();
        }
    }


    //体育报表
    public function get_report_sp($date =array(),$agent_id ='',$type ='',$gid){
        //代理ID
        if (!empty($agent_id)) {
            if (strstr($agent_id,',')) {
                $map['agent_id'] = array('in','('.$agent_id.')');
            }else{
                $map['agent_id'] = $agent_id;
            }
        }else{
            //除去测试代理
            $agent_test_id = $this->get_agent_test_id();
            $map['agent_id'] = array('<>',$agent_test_id);
        }

        //结算时间
        $map['update_time'] = array(array('>=',$date[0]),array('<=',$date[1]));
        $map['site_id'] = $_SESSION['site_id'];
        $map['is_jiesuan'] = 1;

        $db_model = array();
        $db_model['tab'] = 'k_bet_cg_group';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        $obj = $this->M($db_model);

        $obj->field("count(gid) as num,sum(bet_money) as all_bet,sum(case when status in (1,2) then bet_money end) as bet,sum(case when status in (1,2) then win end) as win,agent_id,username,uid");
        $obj->where($map);
        if ($gid == '1') {
            $obj->group('agent_id');
            $arr_cg = $obj->select('u-agent_id');
        }elseif ($gid == '2') {
            $obj->group('uid');
            $arr_cg = $obj->select('u-username');
        }else{
            $arr_cg = $obj->find();
        }

        $db_model['tab'] = 'k_bet';
        $obj = $this->M($db_model);

        $obj->field("count(bid) as num,sum(bet_money) as all_bet,sum(case when status in (1,2,4,5) then bet_money end) as bet,sum(case when status in (1,2,4,5) then win end) as win,agent_id,username,uid");
        $obj->where($map);

        if ($gid == '1') {
            $obj->group('agent_id');
            $arr_sp = $obj->select('u-agent_id');
        }elseif ($gid == '2') {
            $obj->group('uid');
            $arr_sp = $obj->select('u-username');
        }else{
            $arr_sp = $obj->find();
        }

        return $this->v_array($arr_sp,$arr_cg,$gid);
    }

    //视讯报表
    public function get_report_video($date,$aid,$type,$game_type,$gid){

        //$db_model['tab'] = $type.'_bet_record_'.date('Ym',strtotime($date[0]));
        $db_model['tab'] = $type.'_bet_record';
        $db_model['type'] = 3;
        $field_name = $this->time_type($type);

        //代理ID
        if (!empty($aid)) {
            if (strstr($aid,',')) {
                $map['agent_id'] = array('in','('.$aid.')');
            }else{
                $map['agent_id'] = $aid;
            }
        }

        $map['site_id'] = $_SESSION['site_id'];
        $map[$field_name[0]]=array(array('>=',$date[0]),array('<=',$date[1]));

        if ($type == 'bbin') {
            $map['result_type'] = array('<>','-1');
            $map['result'] = array('<>','D');
            $map['result'] = array('<>','-1');
            $map['payoff'] = array('!=','0.0000');
            if ($game_type == 'bbdz') {
                //$map['gamekind'] = 5;
                $map['gamekind'] = array('in','(5,15)');
            }else{
                //视讯类别 体育 彩票
                //$map['gamekind'] = array('in','(1,3,12,15)');
                $map['gamekind'] = array('not in','(5,15)');
            }
        }elseif($type == 'mg'){
            if ($game_type == 'mgdz') {
                $map['module_id'] =  array('<','28');
            }else{
                //mg视讯类别
                $map['module_id'] =  array('in','(28,29,30,32)');
            }
        }elseif($type == 'pt'){
            $map['Bet + Win'] = array('>',0);
        }
        elseif($type == 'eg'){
            $map['payout'] = array('!=','0.0000');
        }

        //判断是否联表
        //$is_union = $this->video_union($type,$date);
        $obj = $this->M($db_model);
        // return $is_union;
        if ($is_union) {
            $obj->field("pkusername as username,agent_id,sum($field_name[1]) as all_bet,sum($field_name[2]) as bet,sum($field_name[3]) as win,count(site_id) as num");
            $obj->where($map);
            if ($gid == '1') {
                $obj->group('agent_id');
                $video_arr = $obj->unselect('u-agent_id',$is_union);
            }elseif($gid == '2'){
                $obj->group('username');
                $video_arr = $obj->unselect('u-username',$is_union);
            }else{
                $video_arr = $obj->unselect('',$is_union);
                return $video_arr[0];
            }

        }else{
            $obj->field("pkusername as username,agent_id,sum($field_name[1]) as all_bet,sum($field_name[2]) as bet,sum($field_name[3]) as win,count(site_id) as num");
            $obj->where($map);
            if ($gid == '1') {
                $obj->group('agent_id');
                $video_arr = $obj->select('u-agent_id');
            }elseif($gid == '2'){
                $obj->group('username');
                $video_arr = $obj->select('u-username');
            }else{
                $video_arr = $obj->select();
                return $video_arr[0];
            }
        }
        return $video_arr;
    }

      //数组合并
    public function v_array($arr = array(),$bet = array(),$gid){
        if (empty($arr) && !empty($bet)) {
            $arr = $bet;
        }elseif(!empty($arr) && !empty($bet)){
            if (empty($gid)) {
                $arr['num'] = $arr['num'] + $bet['num'];
                $arr['bet'] = $arr['bet'] + $bet['bet'];
                $arr['all_bet'] = $arr['all_bet'] + $bet['all_bet'];
                $arr['win'] = $arr['win'] + $bet['win'];
            }else{
                //数组处理
                $arr = array_merge_recursive($arr,$bet);
            }
        }
        return $arr;
    }

      //根据不同视讯返回对应数据库字段
    public function time_type($type){
        //注单时间 下注 有效下注 派彩金额 游戏类型 下注详情
        switch ($type)
        {
        case 'lebo':
            $field_name = array('update_time','betamount','valid_betamount','payout+valid_betamount','game_type','bet_detail');
            break;
        case 'bbin':
            $field_name = array('wagers_date','betamount','commissionable','payoff+commissionable','gametype');
            break;
        case 'mg':
            $field_name = array('date','income','income','payout');
            break;
        case 'ag':
            $field_name = array('bet_time','bet_amount','valid_betamount','netamount+valid_betamount','game_type');
          break;
        case 'pt':
            $field_name = array('GameDate','Bet','Bet','Win','GameType');
            break;
        case 'eg':
            $field_name = array('betstart_time','betamount','valid_betamount','payout+valid_betamount','game_type','bet_detail');
            break;
        case 'ct':
            $field_name = array('transaction_date_time','betpoint','availablebet','win_or_loss+availablebet-betpoint','game_type');
            break;
        case 'og':
            $field_name = array('add_time','betting_amount','valid_amount','win_lose_amount+valid_amount','game_name_id','game_betting_content');
          break;
        }
        return $field_name;
    }

      //视讯是否联表判断
    public function video_union($type,$date){
        if (date('Ym',strtotime($date[1])) != date('Ym',strtotime($date[0]))  && date('Ym') >= date('Ym',strtotime($date[1]))) {
            $tab1 = $type.'_bet_record_'.date('Ym',strtotime($date[0]));
            $tab2 = $type.'_bet_record_'.date('Ym',strtotime($date[1]));
            return array('tab1'=>$tab1,'tab2'=>$tab2);
        }
    }

    //获取测试代理ID
    public function get_agent_test_id(){
        $map_te = array();
        $map_te['table'] = 'k_user_agent';
        $map_te['select'] = 'id';
        $map_te['where']['is_demo'] = 1;
        $map_te['where']['site_id'] = $_SESSION['site_id'];
        $map_te['where']['agent_type'] = 'a_t';
        $agent_test_id = $this->rfind($map_te);
        return $agent_test_id['id'];
    }

    //获取代理id
    public function get_agentsid($pid,$type){
        $map = array();
        $map['pid'] = $pid;
        $map['site_id'] = $_SESSION['site_id'];
        $map['is_demo'] = 0;

        $sh_ids = $this->M(array('tab'=>'k_user_agent','type'=>1,'is_port'=>1))->field("id")->where($map)->select("id");

        //股东id获取总代id 再用in查询总代ID获取代理ID
        if (!empty($sh_ids)) {

            $sh_ids = implode(',',array_keys($sh_ids));
            if ($type == 'u_a') {
                //如果是总代直接返回代理id
                return $sh_ids;
            }else{
                 //获取代理商
                $map_sh = array();
                $map_sh['pid'] = array('in','('.$sh_ids.')');
                $map_sh['site_id'] = $_SESSION['site_id'];

                return $this->M(array('tab'=>'k_user_agent','type'=>1,'is_port'=>1))->field("group_concat(id) as agents")->where($map_sh)->getField('agents');
            }
        }
    }

    //获取股东
    public function get_agent_sh($type,$aid){
        $db_model = array();
        $db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库

        $map = array();
        if (!empty($aid)) {
            $map['pid'] = $aid;
        }
        $map['site_id'] = $_SESSION['site_id'];
        $map['agent_type'] = $type;
        $map['is_demo'] = 0;

        return $this->M($db_model)->field("id,agent_name,agent_user,agent_login_user")->where($map)->select('id');
    }

    //代理专用
    public function get_agent_idu($pid){
        $map = array();
        $map['pid'] = $pid;
        $map['site_id'] = $_SESSION['site_id'];
        $map['is_demo'] = 0;
        $map['agent_type'] = 'a_t';
        return $this->M(array('tab'=>'k_user_agent','type'=>1,'is_port'=>1))->field("id")->where($map)->select("u-id");
    }

}