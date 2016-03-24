<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Member_analysis_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }
    //获取代理数据
    public function get_agent($map,$limit,$date){
         $db_model = array();
         $db_model['tab'] = 'k_user_agent';
         $db_model['type'] = 1;
         $agent = $this->M($db_model)->field("id,agent_user,agent_name,agent_login_user")->where($map)->limit($limit)->select('id');
         $agents_id = array_keys($agent);
         foreach ($agent as $k => $v) {
             $agents_str .= "'".$v['agent_user']."',";
         }

         //新注册会员
         $new_user = $this->get_agent_reg_user($agents_id,$date);
         //有效会员
         $active_user = $this->get_agent_act_user($agents_id,$date);
         //代理商出入款总汇
         $rg_data_in = $this->get_agent_money_rg($agents_id,$date,1);
         $rg_data_out = $this->get_agent_money_rg($agents_id,$date,2);
         $gs_data_in = $this->get_agent_money_gs(rtrim($agents_str,','),$date,0);
         $gs_data_out = $this->get_agent_out_money(rtrim($agents_str,','),$date);

         foreach ($agent as $key => $val) {
             $field = 'u-'.$key;
             $agent[$key]['user_new_num'] = $new_user[$key]['num'] + 0;
             $agent[$key]['user_act_num'] = count($active_user[$field]['user']) + 0;

             $agent[$key]['rg_in_money'] = $rg_data_in[$key]['money']+0;
             $agent[$key]['gs_in_money'] = $gs_data_in[$val['agent_user']]['money']+0;

             $agent[$key]['rg_out_money'] = $rg_data_out[$key]['money']+0;
             $agent[$key]['xs_out_money'] = $gs_data_out[$val['agent_user']]['money']+0;
             $agent[$key]['money'] = $rg_data_in[$key]['money']+
                                     $gs_data_in[$val['agent_user']]['money']-
                                     $rg_data_out[$key]['money']-
                                     $gs_data_out[$val['agent_user']]['money']+0;
         }
         //p($agent);die;
         return $agent;

    }
    //获取所有代理数据
    public function get_all_agents(){
        $db_model = array();
        $db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("id,agent_user,agent_name,agent_login_user")->where("site_id = '".$_SESSION['site_id']."' and is_demo = 0 and agent_type = 'a_t' ")->select('id');
    }

    //获取代理旗下注册的会员
    public function get_agent_reg_user($agents,$date = array()){
        $agents_str = implode(",", $agents);

        $map_n['agent_id'] = array('in','('.$agents_str.')');
        $map_n['site_id'] = $_SESSION['site_id'];
        $map_n['reg_date'] = array(
                                array('>=',$date[0].' 00:00:00'),
                                array('<=',$date[1].' 23:59:59')
                                );


        $db_model['tab'] = 'k_user';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("count('uid') as num,agent_id")->where($map_n)->group('agent_id')->select('agent_id');
    }

        //获取代理人工入款 type入款1 出款类型
    public function get_agent_money_rg($agents,$date,$type =1){
        $agents_str = implode(",", $agents);

        $map_n['agent_id'] = array('in','('.$agents_str.')');
        $map_n['site_id'] = $_SESSION['site_id'];
        $map_n['type'] = $type;
        if ($type == 2) {
            $map_n['catm_type'] = array('in','(1,2,4,8)');
        }else{
            $map_n['catm_type'] = array('in','(1,6,4)');
        }

        $map_n['updatetime'] = array(
                                array('>=',$date[0].' 00:00:00'),
                                array('<=',$date[1].' 23:59:59')
                                );
        $db_model['tab'] = 'k_user_catm';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("count(1) as num,sum(catm_money) as money,agent_id")->where($map_n)->group('agent_id')->select('agent_id');
    }

    //获取代理
    public function get_agent_money_gs($agents,$date,$type){
        $map = array();
        $map['agent_user'] = array('in','('.$agents.')');
        $map['make_sure'] = 1;
        $map['site_id'] = $_SESSION['site_id'];
        if(!empty($type)){
           $map['into_style'] = $type;//1表示公司入款 2 表示线上入款
        }
        if (!empty($date)) {
           $map['do_time'] = array(
                                  array('>=',$date[0].' 00:00:00'),
                                  array('<=',$date[1].' 23:59:59')
                                );
        }
        $db_model['tab'] = 'k_user_bank_in_record';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("sum(deposit_num) as money,count(1) as num,agent_user")->where($map)->group('agent_user')->select('agent_user');
    }

    //获取代理出款
    public function get_agent_out_money($agents,$date){
        $map = array();
        $map['agent_user'] = array('in','('.$agents.')');
        $map['out_status'] = 1;
        $map['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
           $map['out_time'] = array(
                                  array('>=',$date[0].' 00:00:00'),
                                  array('<=',$date[1].' 23:59:59')
                                );
        }
        $db_model['tab'] = 'k_user_bank_out_record';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("sum(outward_num) as money,count(1) as num,agent_user")->where($map)->group('agent_user')->select('agent_user');
    }



    //获取代理旗下有效会员
    public function get_agent_act_user($agents,$date = array()){
        $type = 'ag,og,ct,mg,lebo,bbin';
        $agents = implode(",", $agents);
        //p($agents );die;
        $this->load->model('cash/Agentsh_num_model');
        $bet =  array();
        $fc_bet = $this->agentsh_fc_num($agents,$date,2);//彩票
        $sp_bet = $this->agentsh_sp_num($agents,$date,2);//体育
        $spcg_bet = $this->agentsh_spcg_num($agents,$date,2);//体育串关
        $ARR = $this->agentsh_video_num($date,$type,$agents,2);//视讯
        if (!empty($fc_bet)) {
            $ARR = $this->v_array1($fc_bet,$ARR);
        }
        if (!empty($sp_bet)) {
            $ARR = $this->v_array1($sp_bet,$ARR);
        }
        if (!empty($spcg_bet)) {
            $ARR = $this->v_array1($spcg_bet,$ARR);
        }
        //p($ARR);die;
        foreach ($ARR as $k=>$v){
           $ARR[$k]['user'] = implode(",", $v['users']);
           unset($ARR[$k]['users']);
           unset($ARR[$k]['agent_id']);
           $ARR[$k]['user'] = array_unique(explode(",",$ARR[$k]['user']));
        }
        //p($ARR);die;
        return $ARR;
    }

        //数组
    public function arr_mer($arr = array(),$bet = array()){
        if (!empty($arr) && !empty($bet)) {
            $arr = array_merge_recursive($arr,$bet);
        }elseif (empty($arr) && !empty($bet)) {
            $arr = $bet;
        }
        return $arr;
    }

        //数据分析
    public function analysis_fun($map,$date = array()){
        if (!empty($map['uname'])) {
            //代理账号查询
            if (!empty($map['atype'])) {
                $map_a = array();
                $map_a['table'] = 'k_user_agent';
                $map_a['select'] = 'id';
                $map_a['where']['agent_login_user'] = $map['uname'];
                $map_a['where']['site_id'] = $_SESSION['site_id'];
                $agent = $this->rfind($map_a);
            }else{
                //会员账号查询
                $map_u = array();
                $map_u['table'] = 'k_user';
                $map_u['select'] = 'uid';
                $map_u['where']['username'] = $map['uname'];
                $map_u['where']['site_id'] = $_SESSION['site_id'];
                $user = $this->rfind($map_u);
            }
        }

        if ($map['type'] == 'fc') {
            return $this->analysis_fc($user['uid'],$agent['id'],$map['order'],$date);
        }elseif ($map['type'] = 'sp') {
            return $this->analysis_sp($user['uid'],$agent['id'],$map['order'],$date);
        }
    }

    //彩票数据
    public function analysis_fc($uid ='',$agent_id ='',$order ='all_bet',$date){
        $mapFc = array();
        //代理账号查询
        if (!empty($agent_id)) {
            $mapFc['agent_id'] = $agent_id;
        }elseif(!empty($uid)){
            //会员账号查询
            $mapFc['uid'] = $uid;
        }else{
            $map = array();
            $map['table'] = 'k_user_agent';
            $map['select'] = 'id';
            $map['where']['is_demo'] = 1;
            $map['where']['site_id'] = $_SESSION['site_id'];
            $map['where']['agent_type'] = 'a_t';
            $agent_id = $this->rfind($map);
            $mapFc['agent_id'] = array('<>',$agent_id['id']);
        }
        $mapFc['js'] = 1;
        // $mapFc['status'] = array('in','(1,2)');
        $mapFc['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
            $mapFc['update_time'] = array(
                            array('>=',$date[0]),
                            array('<=',$date[1])
                            );
        }

        $db_model = array();
        $db_model['tab'] = 'c_bet';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("count(id) as num,count(case status when 1 then id end) win_num,count(case status when 2 then id end) lose_num,sum(money) as all_bet,sum(case when (status = 1 or status = 2) then money end) as bet,sum(case when status in (1,2) then win end) as win,username,agent_id")->where($mapFc)->order($order.' desc')->group('uid')->select();
    }

    //体育数据
    public function analysis_sp($uid ='',$agent_id ='',$order ='all_bet',$date){
        $mapSp = array();
        //代理账号查询
        if (!empty($agent_id)) {
            $mapSp['agent_id'] = $agent_id;
        }elseif(!empty($uid)){
            //会员账号查询
            $mapSp['uid'] = $uid;
        }else{
            $map = array();
            $map['table'] = 'k_user_agent';
            $map['select'] = 'id';
            $map['where']['is_demo'] = 1;
            $map['where']['agent_type'] = 'a_t';

            $map['where']['site_id'] = $_SESSION['site_id'];
            $agent_id = $this->rfind($map);
            $mapSp['agent_id'] = array('<>',$agent_id['id']);
        }
        // $mapSp['status'] = array('in','(1,2,4,5)');
        $mapSp['is_jiesuan'] = 1;
        $mapSp['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
            $mapSp['update_time'] = array(
                            array('>=',$date[0]),
                            array('<=',$date[1])
                            );
        }

        $db_model = array();
        $db_model['tab'] = 'k_bet_cg_group';
        $db_model['type'] = 1;

        $arr_cg = $this->M($db_model)->field("uid,count(gid) as num,count(case status when 1 then gid end) win_num,count(case status when 2 then gid end) lose_num,sum(bet_money) as all_bet,sum(case when (status =1 or status = 2) then bet_money end) as bet,sum(case when status in (1,2) then win end) as win,username")->where($mapSp)->group('uid')->select('u-uid');
        //return $arr_cg;
        $db_model['tab'] = 'k_bet';
        $arr_sp = $this->M($db_model)->field("uid,count(bid) as num,count(case status when 1 then bid end) win_num,count(case status when 2 then bid end) lose_num,sum(bet_money) as all_bet,sum(case when status in (1,2,4,5) then bet_money end) as bet,sum(case when status in (1,2,4,5) then win end) as win,username,agent_id")->where($mapSp)->order($order.' desc')->group('uid')->select('u-uid');

        //return $arr_sp;

        return $this->v_array($arr_sp,$arr_cg);
    }

      //视讯电子数据分析
    public function analysis_video($username,$agent_id,$type,$order ='all_bet',$date,$game_type =''){
        $db_model['tab'] = $type.'_bet_record_'.date('Ym',strtotime($date[0]));
        $db_model['type'] = 3;
        $field_name = $this->time_type($type);

        if(!empty($username) && empty($agent_id)){
            $map['pkusername']=$username;
        }elseif(!empty($agent_id)){
            $map['agent_id'] = $agent_id;
        }

        $map['site_id'] = $_SESSION['site_id'];
        $map[$field_name[0]]=array(array('>=',$date[0]),array('<=',$date[1]));

        if ($type == 'bbin') {
            if ($game_type == 'bbdz') {
                $map['gamekind'] = 5;
            }else{
                //视讯类别
                $map['gamekind'] = array('in','(1,3,12,15)');
            }
        }elseif($type == 'mg'){
            if ($game_type == 'mgdz') {
                $map['module_id'] =  array('<','28');
            }else{
                //mg视讯类别
                $map['module_id'] =  array('in','(28,29,30,32)');
            }
        }
        if ($type == 'pt') {
            $map["$field_name[1] + $field_name[3]"] = array('>',0);
        }

        //判断是否联表
        $is_union = $this->video_union($type,$date);
        if ($is_union) {
            if ($type == 'mg' || $type == 'pt') {
                $video_arr = $this->M($db_model)->field("pkusername as username,agent_id,sum($field_name[1]) as all_bet,sum($field_name[2]) as bet,sum($field_name[3]) as win,count(site_id) as num,count(case when ($field_name[3]-$field_name[2]) > 0 then site_id end) win_num,count(case when ($field_name[3]-$field_name[2]) < 0 then site_id end) lose_num")->where($map)->group('pkusername')->order($order." desc")->unselect('',$is_union);
            }else{
                $video_arr = $this->M($db_model)->field("pkusername as username,agent_id,sum($field_name[1]) as all_bet,sum($field_name[2]) as bet,sum($field_name[3]) as win,count(site_id) as num,count(case when $field_name[3] > 0 then site_id end) win_num,count(case when $field_name[3] < 0 then site_id end) lose_num")->where($map)->group('pkusername')->order($order." desc")->unselect('',$is_union);
            }

        }else{
            if ($type == 'mg' || $type == 'pt') {
                $video_arr = $this->M($db_model)->field("pkusername as username,agent_id,sum($field_name[1]) as all_bet,sum($field_name[2]) as bet,sum($field_name[3]) as win,count(site_id) as num,count(case when ($field_name[3]-$field_name[2]) > 0 then site_id end) win_num,count(case when ($field_name[3]-$field_name[2]) < 0 then site_id end) lose_num")->where($map)->group('pkusername')->order($order." desc")->select();
            }else{
                $video_arr = $this->M($db_model)->field("pkusername as username,agent_id,sum($field_name[1]) as all_bet,sum($field_name[2]) as bet,sum($field_name[3]) as win,count(site_id) as num,count(case when $field_name[3] > 0 then site_id end) win_num,count(case when $field_name[3] < 0 then site_id end) lose_num")->where($map)->group('pkusername')->order($order." desc")->select();
            }

        }
        return $video_arr;

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
        case 'pt':
            $field_name = array('GameDate','Bet','Bet','Win','GameType');
          break;
        default:
            $field_name = array('betstart_time','betamount',
                                'valid_betamount','payout');
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

    //数组合并
    public function v_array($arr = array(),$bet = array()){
        if (!empty($arr) && !empty($bet)) {

            $keys_uid = array_keys($arr);
            $keys_uid_cg = array_keys($bet);
            $intersect_arr = array_intersect($keys_uid, $keys_uid_cg);
            if (!empty($intersect_arr)) {
                foreach ($intersect_arr as $key => $val) {
                    $arr[$val]['num'] = $arr[$val]['num'] + $bet[$val]['num'];
                    $arr[$val]['win_num'] = $arr[$val]['win_num'] + $bet[$val]['win_num'];
                    $arr[$val]['lose_num'] = $arr[$val]['lose_num'] + $bet[$val]['lose_num'];
                    $arr[$val]['bet'] = $arr[$val]['bet'] + $bet[$val]['bet'];
                    $arr[$val]['all_bet'] = $arr[$val]['all_bet'] + $bet[$val]['all_bet'];
                    $arr[$val]['win'] = $arr[$val]['win'] + $bet[$val]['win'];
                    unset($bet[$val]);
                }
            }
            $arr = array_merge_recursive($arr,$bet);
        }elseif (empty($arr) && !empty($bet)) {
            $arr = $bet;
        }
        return $arr;
    }

       //优惠分析
    public function analysis_user_dis($map = array(),$limit ='',$order = 'all_fd'){
        $db_model = array();
        $db_model['tab'] = 'k_user_discount_count';
        $db_model['type'] = 1;

        return $this->M($db_model)->field("uid,username,agent_id,count(id) as num,sum(total_e_fd) as all_fd,sum(sp_fd) as sp_fd,sum(fc_fd) as fc_fd,sum(ag_fd) as ag_fd,sum(og_fd) as og_fd,sum(mg_fd) as mg_fd,sum(mgdz_fd) as mgdz_fd,sum(ct_fd) as ct_fd,sum(lebo_fd) as lebo_fd,sum(bbin_fd) as bbin_fd,sum(bbdz_fd) as bbdz_fd,sum(pt_fd) as pt_fd")->where($map)->group('uid')->limit($limit)->order($order.' desc')->select();
    }

    //获取总数
    public function analysis_count($map =array()){
        $db_model = array();
        $db_model['tab'] = 'k_user_discount_count';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("count(distinct uid) as num,username,agent_id,do_time,site_id,state")->where($map)->getField('num');
    }

       //出入款分析
    public function analysis_cash($arr,$date =array(),$limit,$order ='money'){
        $db_model = array();
        $db_model['type'] = 1;
        $map['site_id'] = $_SESSION['site_id'];
        //账号检索判断
        if ($arr['atype'] == 1 && !empty($arr['uname'])) {
            $map['agent_user'] = $arr['uname'];
        }elseif(!empty($arr['uname'])){
            $map['username'] = $arr['uname'];
        }

        if ($arr['type'] == 1 || $arr['type'] == 2) {
            $db_model['tab'] = 'k_user_bank_in_record';
            $map['make_sure'] =1;
            $map['into_style'] = $arr['type'];
            if ($arr['type'] == '1') {
                $map['do_time'] = array(array('>=',$date[0]),array('<=',$date[1]));
            }elseif($arr['type'] == '2'){
                $map['in_date'] = array(array('>=',$date[0]),array('<=',$date[1]));
            }

            return $this->M($db_model)->field("uid,username,agent_user,count(id) as num,sum(deposit_num) as money,sum(favourable_num + other_num) as money_dis")->where($map)->group('uid')->limit($limit)->order($order.' desc')->select();
        }elseif($arr['type'] == 3){
            $db_model['tab'] = 'k_user_bank_out_record';
            $map['do_time'] = array(array('>=',$date[0]),array('<=',$date[1]));
            $map['out_status'] = 1;
            return $this->M($db_model)->field("uid,username,agent_user,count(id) as num,sum(outward_money) as money")->where($map)->group('uid')->limit($limit)->order($order.' desc')->select();
        }elseif($arr['type'] == 4 ||$arr['type'] == 5){
            //根据代理用户名查出代理id
            if ($arr['atype'] == 1 && !empty($arr['uname'])) {
                $map['agent_id'] = $this->get_agent_id($arr['uname']);
                unset($map["agent_user"]);
            }
            $db_model['tab'] = 'k_user_catm';
            $map['updatetime'] = array(array('>=',$date[0]),array('<=',$date[1]));
            $map['catm_type'] = 1;
            if($arr['type'] == 4){
                $map['type'] = 1;
                return $this->M($db_model)->field("uid,username,agent_id,count(id) as num,sum(catm_money) as money,sum(catm_give) as ckyh,sum(atm_give) as hkyh")->where($map)->group('uid')->limit($limit)->order($order.' desc')->select();
            }else{
                $map['type'] = 2;
                return $this->M($db_model)->field("uid,username,agent_id,count(id) as num,sum(catm_money) as money")->where($map)->group('uid')->limit($limit)->order($order.' desc')->select();
            }
        }
    }
    //出入款总数
    public function analysis_cash_count($arr,$date =array()){
        $db_model = array();
           //账号检索判断
        if ($arr['atype'] == 1 && !empty($arr['uname'])) {
            $map['agent_user'] = $arr['uname'];
        }elseif(!empty($arr['uname'])){
            $map['username'] = $arr['uname'];
        }
        $db_model['type'] = 1;
        $map['site_id'] = $_SESSION['site_id'];

        if ($arr['type'] == '1' || $arr['type'] == '2') {
            $db_model['tab'] = 'k_user_bank_in_record';
            $map['make_sure'] = 1;
            $map['into_style'] = $arr['type'];

            if ($arr['type'] == '1') {
                $map['do_time'] = array(array('>=',$date[0]),array('<=',$date[1]));
            }elseif($arr['type'] == '2'){
                $map['in_date'] = array(array('>=',$date[0]),array('<=',$date[1]));
            }

        }elseif($arr['type'] == '3'){
            $db_model['tab'] = 'k_user_bank_out_record';
            $map['out_status'] = 1;
            $map['do_time'] = array(array('>=',$date[0]),array('<=',$date[1]));
        }elseif($arr['type'] == '4' || $arr['type'] == '5'){
            //根据代理用户名查出代理id
            if ($arr['atype'] == 1 && !empty($arr['uname'])) {
                $map['agent_id'] = $this->get_agent_id($arr['uname']);
            }
            $db_model['tab'] = 'k_user_catm';
            $map['updatetime'] = array(array('>=',$date[0]),array('<=',$date[1]));
            unset($map["agent_user"]);
            $map['type'] = $arr['type'] - 3;
            $map['catm_type'] = 1;
        }

        return $this->M($db_model)->field("count(distinct uid) as num,username")->where($map)->getField('num');
    }
    //所有出入款分析
    public function analysis_cash_all($arr,$date =array(),$order ='money'){
        $db_model = array();
        $db_model['type'] = 1;
        $map['site_id'] = $_SESSION['site_id'];
        //账号检索判断
        if ($arr['atype'] == 1 && !empty($arr['uname'])) {
            $map['agent_user'] = $arr['uname'];
        }elseif(!empty($arr['uname'])){
            $map['username'] = $arr['uname'];
        }

        if ($arr['type'] == 1 || $arr['type'] == 2) {
            $db_model['tab'] = 'k_user_bank_in_record';
            $map['make_sure'] =1;
            $map['into_style'] = $arr['type'];
            if ($arr['type'] == '1') {
                $map['do_time'] = array(array('>=',$date[0]),array('<=',$date[1]));
            }elseif($arr['type'] == '2'){
                $map['in_date'] = array(array('>=',$date[0]),array('<=',$date[1]));
            }

            return $this->M($db_model)->field("count(Distinct username)as renshu,count(id) as num,sum(deposit_num) as money,sum(favourable_num + other_num) as money_dis")->where($map)->order($order.' desc')->select();
        }elseif($arr['type'] == 3){
            $db_model['tab'] = 'k_user_bank_out_record';
            $map['do_time'] = array(array('>=',$date[0]),array('<=',$date[1]));
            $map['out_status'] = 1;
            return $this->M($db_model)->field("count(Distinct username)as renshu,count(id) as num,sum(outward_money) as money")->where($map)->select();
        }elseif($arr['type'] == 4||$arr['type']==5){
            //根据代理用户名查出代理id
            if ($arr['atype'] == 1 && !empty($arr['uname'])) {
                $map['agent_id'] = $this->get_agent_id($arr['uname']);;
                unset($map["agent_user"]);
            }
            $db_model['tab'] = 'k_user_catm';
            $map['updatetime'] = array(array('>=',$date[0]),array('<=',$date[1]));
            $map['catm_type'] = 1;
            if($arr['type'] == 4){
                $map['type'] = 1;
                return $this->M($db_model)->field("count(Distinct username)as renshu,count(id) as num,sum(catm_money) as money,sum(catm_give) as ckyh,sum(atm_give) as hkyh")->where($map)->select();
            }else{
                $map['type'] = 2;
                return $this->M($db_model)->field("count(Distinct username)as renshu,count(id) as num,sum(catm_money) as money")->where($map)->select();
            }
        }
    }

    //所有优惠分析
    public function analysis_user_dis_all($map = array(),$order = 'all_fd'){
        $db_model = array();
        $db_model['tab'] = 'k_user_discount_count';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("count(id) as num,sum(total_e_fd) as all_fd,sum(sp_fd) as sp_fd,sum(fc_fd) as fc_fd,sum(ag_fd) as ag_fd,sum(og_fd) as og_fd,sum(mg_fd) as mg_fd,sum(mgdz_fd) as mgdz_fd,sum(ct_fd) as ct_fd,sum(lebo_fd) as lebo_fd,sum(bbin_fd) as bbin_fd,sum(bbdz_fd) as bbdz_fd,sum(pt_fd) as pt_fd")->where($map)->order($order.' desc')->select();
    }

      //数组合并
    public function v_array1($arr = array(),$bet = array()){
        if (!empty($arr) && !empty($bet)) {
            $keys_uid = array_keys($arr);
            $keys_uid_cg = array_keys($bet);
            $intersect_arr = array_intersect($keys_uid, $keys_uid_cg);
            $arr = array_merge_recursive($arr,$bet);
        }elseif (empty($arr) && !empty($bet)) {
            $arr = $bet;
        }
        return $arr;
    }

    //视讯打码 type视讯类别 agents代理 bet_type 1打码 2数量
    public  function agentsh_video_num($date=array(),$type,$agents,$bet_type = 1){
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        if (!empty($agents)) {
            if (strstr($agents,',')) {
                $map['agent_id'] = array('in','('.$agents.')');
            }else{
                $map['agent_id'] = $agents;
            }
        }
        $ARR = array();
        $db_model = array();
        $db_model['type'] = 3;
        //AG视讯
        if (FALSE !== strpos($type, 'ag')) {
            $map['bet_time'] = array(
                                array('>=',$date[0].' 00:00:00'),
                                array('<=',$date[1].' 23:59:59')
                                );
            $db_model['tab'] = 'ag_bet_record_'.date('Ym',strtotime($date[0]));
            //判断是否联表
            $is_union = $this->video_union("ag",$date);
            if ($is_union) {
                $ag_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->unselect('u-agent_id',$is_union);
                $ARR = $this->v_array1($ARR,$ag_arr);
            }else{
                $ag_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->select("u-agent_id");
                $ARR = $this->v_array1($ARR,$ag_arr);
            }
            unset($map['bet_time']);
        }
        //BBIN视讯
        if (FALSE !== strpos($type, 'bbin')) {
            $map['wagers_date'] = array(
                                array('>=',$date[0].' 00:00:00'),
                                array('<=',$date[1].' 23:59:59')
                                );

            $db_model['tab'] = 'bbin_bet_record_'.date('Ym',strtotime($date[0]));
            $map['payoff'] = array('<>','0.0000');
          //判断是否联表
            $is_union = $this->video_union("bbin",$date);
            if ($is_union) {
                $bbin_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->unselect('u-agent_id',$is_union);
                $ARR = $this->v_array1($ARR,$bbin_arr);
            }else{
                $bbin_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->select("u-agent_id");
                $ARR = $this->v_array1($ARR,$bbin_arr);
            }
            unset($map['payoff']);
            unset($map['wagers_date']);
        }
        //CT视讯
        if (FALSE !== strpos($type, 'ct')) {
            $map['transaction_date_time'] = array(
                                array('>=',$date[0].' 00:00:00'),
                                array('<=',$date[1].' 23:59:59')
                                );
            $db_model['tab'] = 'ct_bet_record_'.date('Ym',strtotime($date[0]));
              //判断是否联表
            $is_union = $this->video_union("ct",$date);
            if ($is_union) {
                $ct_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->unselect('u-agent_id',$is_union);
                $ARR = $this->v_array1($ARR,$ct_arr);
            }else{
                $ct_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->select("u-agent_id");
                $ARR = $this->v_array1($ARR,$ct_arr);
            }
            unset($map['transaction_date_time']);

        }

        //LEBO视讯
        if (FALSE !== strpos($type, 'lebo')) {
            $map['betstart_time'] = array(
                                array('>=',$date[0].' 00:00:00'),
                                array('<=',$date[1].' 23:59:59')
                                );
            $db_model['tab'] = 'lebo_bet_record_'.date('Ym',strtotime($date[0]));
              //判断是否联表
            $is_union = $this->video_union("lebo",$date);
            if ($is_union) {
                $lebo_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->unselect('u-agent_id',$is_union);
                $ARR = $this->v_array1($ARR,$lebo_arr);
            }else{
                $lebo_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->select("u-agent_id");
                $ARR = $this->v_array1($ARR,$lebo_arr);
            }
            unset($map['betstart_time']);

        }

        //MG
        if (FALSE !== strpos($type, 'mg')) {
            $map['date'] = array(
                                array('>=',$date[0].' 00:00:00'),
                                array('<=',$date[1].' 23:59:59')
                                );
            $db_model['tab'] = 'mg_bet_record_'.date('Ym',strtotime($date[0]));
              //判断是否联表
            $is_union = $this->video_union("mg",$date);
            if ($is_union) {
                $mg_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->unselect('u-agent_id',$is_union);
                $ARR = $this->v_array1($ARR,$mg_arr);
            }else{
                $mg_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->select("u-agent_id");
                $ARR = $this->v_array1($ARR,$mg_arr);
            }
            unset($map['date']);
        }

        //OG视讯
        if (FALSE !== strpos($type, 'og')) {
            $map['add_time'] = array(
                                array('>=',$date[0].' 00:00:00'),
                                array('<=',$date[1].' 23:59:59')
                                );
            $db_model['tab'] = 'og_bet_record_'.date('Ym',strtotime($date[0]));
              //判断是否联表
            $is_union = $this->video_union("og",$date);
            if ($is_union) {
                $og_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->unselect('u-agent_id',$is_union);
                $ARR = $this->v_array1($ARR,$og_arr);
            }else{
                $og_arr = $this->M($db_model)->field("group_concat(distinct pkusername) as users,agent_id")->where($map)->group('agent_id')->select("u-agent_id");
                $ARR = $this->v_array1($ARR,$og_arr);
            }
            unset($map['add_time']);
        }
        return $ARR;
    }

         //股东彩票所有打码
    public  function agentsh_fc_num($agents,$date=array(),$bet_type = 1){
        $mapFc = array();
        if (!empty($agents)) {
            if (strstr($agents,',')) {
                $mapFc['agent_id'] = array('in','('.$agents.')');
            }else{
                $mapFc['agent_id'] = $agents;
            }
        }
        $mapFc['status'] = array('in','(1,2)');
        $mapFc['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
            $mapFc['update_time'] = array(
                            array('>=',$date[0].' 00:00:00'),
                            array('<=',$date[1].' 23:59:59')
                            );
        }
        $db_model = array();
        $db_model['tab'] = 'c_bet';
        $db_model['type'] = 1;
        $fc_arr = $this->M($db_model)->field("group_concat(distinct username) as users,agent_id")->where($mapFc)->group('agent_id')->select('u-agent_id');
       // p($fc_arr);die;
        return $fc_arr;
    }

     //股东体育下注
    public  function agentsh_sp_num($agents,$date=array(),$bet_type = 1){
        $mapSp = array();
        if (!empty($agents)) {
            if (strstr($agents,',')) {
                $mapSp['agent_id'] = array('in','('.$agents.')');
            }else{
                $mapSp['agent_id'] = $agents;
            }
        }
        $mapSp['status'] = array('in','(1,2,4,5)');
        $mapSp['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
            $mapSp['update_time'] = array(
                            array('>=',$date[0].' 00:00:00'),
                            array('<=',$date[1].' 23:59:59')
                            );
        }
        $db_model = array();
        $db_model['tab'] = 'k_bet';
        $db_model['type'] = 1;
        $sp_arr1 =array();
        $sp_arr = $this->M($db_model)->field("group_concat(distinct username) as users,agent_id")->where($mapSp)->group('agent_id')->select('u-agent_id');
        //p($sp_arr);die;
         return $sp_arr;
    }
    //体育串关
    public  function agentsh_spcg_num($agents,$date=array(),$bet_type = 1){
        $mapSp = array();
        if (!empty($agents)) {
            if (strstr($agents,',')) {
                $mapSp['agent_id'] = array('in','('.$agents.')');
            }else{
                $mapSp['agent_id'] = $agents;
            }
        }
        $mapSp['status'] = array('in','(1,2)');
        $mapSp['site_id'] = $_SESSION['site_id'];
        if (!empty($date)) {
            $mapSp['update_time'] = array(
                            array('>=',$date[0].' 00:00:00'),
                            array('<=',$date[1].' 23:59:59')
                            );
        }
        $db_model = array();
        $db_model['tab'] = 'k_bet_cg_group';
        $db_model['type'] = 1;
        $sp_cg_arr = $this->M($db_model)->field("group_concat(distinct username) as users,agent_id")->where($mapSp)->group('agent_id')->select('u-agent_id');
        //p($sp_cg_arr);die;
        return $sp_cg_arr;
    }

    //获取代理id
    public function get_agent_id($uname){
        $db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("id")->where("site_id = '".$_SESSION['site_id']."' and agent_user = '".$uname."'")->getField('id');
    }
}