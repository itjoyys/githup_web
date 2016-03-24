<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Note_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->init_db();
    }
 public function  cancelBet($status,$bid,$uid,$why=''){ //注单状态值，注单编号
        $InsertTime=date('Y-m-d H:i:s',time());

        $this->private_db->trans_begin();
        $sql="select a.bet_money,a.balance,a.number,b.money,b.username,a.site_id,a.master_guest,a.bet_info,b.agent_id from k_bet a,k_user b where b.uid = $uid and  a.bid = $bid  and a.lose_ok=0  limit 0,1";
        $query=$this->private_db->query($sql);
        $betorder = $query->row_array();
             if(empty($betorder)){
                $this->private_db->trans_commit();
                return false;
            }
        $sql="update k_bet,k_user set k_bet.is_jiesuan=1,k_bet.lose_ok=1,k_bet.status=$status,k_user.money=k_user.money+k_bet.bet_money,k_bet.win=k_bet.bet_money,k_bet.update_time='$InsertTime',k_bet.match_endtime='$InsertTime',k_bet.sys_about='$why' where k_user.uid=k_bet.uid and k_bet.bid=$bid and k_bet.status=0 and k_bet.lose_ok=0";
        //echo $sql;
        $this->private_db->query($sql);
        $affected_rows=$this->private_db->affected_rows();
        //echo $affected_rows;

        if ($this->private_db->trans_status() === FALSE || $affected_rows<1)
        {
            $this->private_db->trans_rollback();
            return false;
        }
        else
        {
            //$sql="select a.bet_money,a.balance,a.number,b.money,b.username,a.site_id,a.master_guest,a.bet_info from k_bet a,k_user b where b.uid = $uid and  a.bid = $bid   limit 0,1";
           // $sql="update k_bet,k_user set k_bet.lose_ok=1,k_bet.status=$status,k_user.money=k_user.money+k_bet.bet_money,k_bet.win=k_bet.bet_money,k_bet.update_time='$InsertTime',k_bet.match_endtime='$InsertTime',k_bet.sys_about='$why' where k_user.uid=k_bet.uid and k_bet.bid=$bid and k_bet.status=0 and k_bet.lose_ok=0";


            $agent_id     =$betorder['agent_id'];
            $siteid     =$betorder['site_id'];
            $bet_money  =$betorder['bet_money'];
            $balance    =$betorder['money']+$betorder['bet_money'];
            $number     =$betorder['number'];
            $username   =$betorder['username'];
            $msg_title  =$betorder['master_guest'];
            $msg_info   =$betorder['bet_info'];
            $sql_cash   =   "insert into k_user_cash_record(site_id,uid,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,source_id,username,agent_id) values('".$siteid."','".$uid."','22','1','".$bet_money."','".$balance."','$InsertTime','体育注单：".$number.' '.$why." ',$bid,'$username',$agent_id)";
            $this->private_db->query($sql_cash);
            if ($this->private_db->trans_status() === FALSE) {
                $this->private_db->trans_rollback();

                return false;
            }else{

                $sql_msg="insert into k_user_msg (msg_from,uid,msg_title,msg_info,msg_time,site_id,`type`,level) values ('系统消息','".$uid."','".$msg_title.' - '.$why."','".$msg_info." <font style=\"color:#F00\"/>该注单取消，已返还本金。</font>','".$InsertTime."','".$siteid."',1,2)";
                $this->private_db->query($sql_msg);
                if ($this->private_db->trans_status() === FALSE) {
                    $this->private_db->trans_rollback();
                    return false;
                }else  {
                    $this->private_db->trans_commit();

                    return true;
                }
            }

        }
}

            //获取体育注单详情
    //获取代理
    public function get_agents($map){
        $db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        return $this->M($db_model)->field('id,agent_user')->where($map)->select('id');
    }

    public function get_information($gid){
        $db_model['tab'] = 'k_bet_cg';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        return $this->M($db_model)->field('bet_info,match_name,master_guest')->where(array('gid'=>$gid))->select();
    }

    //获取体育注单
    public function get_sp_bet($type,$map,$limit,$sort){
        $test_id = $this->agent_test_id();
        $field_name = array('bet_time','update_time','bet_money','number','username');
        $order_name=$field_name[$sort[1]];
        if($sort[0]===0){
            $order = $order_name.' desc';
        }else{
            $order = $order_name.' asc';
        }
        if ($test_id) {
            $map['agent_id'] = array('<>',$test_id);
        }
        if ($type == 'cg') {
            $db_model['tab'] = 'k_bet_cg_group';
            $db_model['type'] = 1;
            $db_model['is_port'] = 1;//读取从库
            return $this->M($db_model)->where($map)->limit($limit)->order($order)->select();
        }else{
            $db_model['tab'] = 'k_bet';
            $db_model['type'] = 1;
            $db_model['is_port'] = 1;//读取从库
            return $this->M($db_model)->where($map)->limit($limit)->order($order)->select();
        }
    }

    //体育总计
    public function get_sp_count($map,$type = ''){

        $test_id = $this->agent_test_id();
        if ($test_id) {
            $map['agent_id'] = array('<>',$test_id);
        }
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        if ($type == 'cg') {
            $db_model['tab'] = 'k_bet_cg_group';
            return $this->M($db_model)->field("count(gid) as num,sum(bet_money) as money")->where($map)->find();
        }else{
            $db_model['tab'] = 'k_bet';
            return $this->M($db_model)->field("count(bid) as num,sum(bet_money) as money")->where($map)->find();
        }
    }

    //获取测试代理商ID
    public function agent_test_id(){
        $db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;
        $map = array('site_id'=>$_SESSION['site_id'],'is_demo'=>1,'agent_type'=>'a_t');
        return $this->M($db_model)->where($map)->getField('id');
    }

     //获取视讯配置
    public function get_videos(){
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $video_module = $this->M(array('tab'=>'web_config','type'=>1))->where($map)->getField("video_module");
        if ($video_module) {
            return explode(',',$video_module);
        }
    }

    //获取视讯记录
    public function get_video_bet($type,$arr,$date = array(),$limit,$leixing,$sort){
        if (!empty($arr['uname'])) {
            $map['pkusername'] = $arr['uname'];
        }
           //订单查询
        if (!empty($arr['order'])) {
            switch ($type) {
                case 'eg':
                case 'lebo':
                    $map['game_id'] = $arr['order'];
                    break;
                case 'ag':
                    $map['bill_no'] = $arr['order'];
                    break;
                case 'og':
                    $map['order_number'] = $arr['order'];
                    break;
                case 'ct':
                    $map['transaction_id'] = $arr['order'];
                    break;
                case 'pt':
                    $map['GameCode'] = $arr['order'];
                    break;
                case 'bbin':
                    $map['wagers_id'] = $arr['order'];
                    break;
                case 'mg':
                    $map['bet_no'] = $arr['order'];
                    break;
            }
        }

        $field_name=$this->time_type($type);
        if($sort[1]<=2){
            $order_name=$field_name[$sort[1]];
        }else{
            $field_type=$this->field_type($type);
            $n=$sort[1]-3;
            $order_name=$field_type[$n];
        }

        if($sort[0]===0){
            $order = $order_name.' desc';
        }else{
            $order = $order_name.' asc';
        }
        $map[$field_name[0]]=array(array('>=',$date[0]),array('<=',$date[1]));
        //var_dump($map);die;
        $map['site_id'] = $_SESSION['site_id'];
          if ($type =="bbin"){
            if ($leixing ==1){
                $map['gamekind'] =5;//电子
            }elseif($leixing ==2){
                $map['gamekind'] = array('not in',"(1,5,12)");  //视讯
            }elseif($leixing ==3){
                $map['gamekind'] =12; //彩票
            }elseif($leixing ==4){
                $map['gamekind'] =1; //体育
            }

        }
        if ($type =="mg"){
            if ($leixing ==1){
                $map['module_id'] =  array('in','(28,29,30,32)'); //视讯
            }elseif ($leixing ==2){
                $map['module_id'] =  array('<','28'); //电子
            }

        }elseif($type == 'pt'){
             $map['Bet + Win'] = array('>',0);
        }
        //$db_model['tab'] = $type.'_bet_record_'.date('Ym',strtotime($date[0]));
        $db_model['tab'] = $type.'_bet_record';
        $db_model['type'] = 3;
          //判断是否联表
        //$is_union = $this->video_union($type,$date);
        if ($is_union) {
            return $this->M($db_model)->where($map)->limit($limit)->order($order)->unselect('',$is_union);
        }else{
            return $this->M($db_model)->where($map)->limit($limit)->order($order)->select();
        }

    }

    //视讯记录数量
    public function vcount($type,$arr,$date = array(),$leixing){
        if (!empty($arr['uname'])) {
            $map['pkusername'] = $arr['uname'];
        }
           //订单查询
        if (!empty($arr['order'])) {
            switch ($type) {
                case 'eg':
                case 'lebo':
                    $map['game_id'] = $arr['order'];
                    break;
                case 'ag':
                    $map['bill_no'] = $arr['order'];
                    break;
                case 'og':
                    $map['order_number'] = $arr['order'];
                    break;
                case 'ct':
                    $map['transaction_id'] = $arr['order'];
                    break;
                case 'pt':
                    $map['GameCode'] = $arr['order'];
                    break;
                case 'bbin':
                    $map['wagers_id'] = $arr['order'];
                    break;
                case 'mg':
                    $map['bet_no'] = $arr['order'];
                    break;
            }
        }
        $map['site_id'] = $_SESSION['site_id'];
        $field_name=$this->time_type($type);
        if ($type =="bbin"){
            if ($leixing ==1){
                $map['gamekind'] =5;//电子
            }elseif($leixing ==2){
                $map['gamekind'] = array('not in',"(1,5,12)");  //视讯
            }elseif($leixing ==3){
                $map['gamekind'] =12; //彩票
            }elseif($leixing ==4){
                $map['gamekind'] =1; //体育
            }

        }
        if ($type =="mg"){
            if ($leixing ==1){
                $map['module_id'] =  array('in','(28,29,30,32)'); //视讯
            }elseif ($leixing ==2){
                $map['module_id'] =  array('<','28'); //电子
            }

        }elseif($type == 'pt'){
            //$map['Bet'] = array('>',0);
            $map['Bet + Win'] = array('>',0);
        }
        $map[$field_name[0]] = array(array('>=',$date[0]),array('<=',$date[1]));
        //开启联表
        //$db_model['tab'] = $type.'_bet_record_'.date('Ym',strtotime($date[0]));
        //$is_union = $this->video_union($type,$date);
        $db_model['tab'] = $type.'_bet_record';
        $db_model['type'] = 3;
        //p($map);die;
        //判断是否联表

        if ($is_union) {
            return $this->M($db_model)->where($map)->uncount($is_union);
        }else{
            return $this->M($db_model)->where($map)->count();
        }
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
        case 'eg':
            $field_name = array('betstart_time','betamount',
                                'valid_betamount','payout','game_type','bet_detail');
            break;
        case 'bbin':
            $field_name = array('wagers_date','betamount',
                                'commissionable','payoff','gametype');
            break;
        case 'mg':
            $field_name = array('date','income',
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
        case 'pt':
            $field_name = array('GameDate','Bet','Bet','Win','GameType');
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
    //根据不同视讯返回对应数据库字段
    public function field_type($type){
        //注单号 系统用户名 视讯用户名
        switch ($type)
        {
        case 'lebo':
            $field_name = array('game_id','pkusername','member');
            break;
        case 'eg':
            $field_name = array('game_id','pkusername','member');
            break;
        case 'bbin':
            $field_name = array('serial_id','pkusername','username');
            break;
        case 'mg':
            $field_name = array('bet_no','pkusername','account_number');
            break;
        case 'ag':
            $field_name = array('bill_no','pkusername','player_name');
          break;
        case 'ct':
            $field_name = array('transaction_id','pkusername','member_id');
            break;
        case 'pt':
            $field_name = array('GameCode','pkusername','PlayerName');
            break;
        case 'og':
            $field_name = array('order_number','pkusername','user_name');
          break;
        default:
            $field_name = array('game_id','pkusername','member');
        }
        return $field_name;
    }
    //获取总计
    public function video_num($type,$date = array(),$arr,$leixing){

        //$db_model['tab'] = $type.'_bet_record_'.date('Ym',strtotime($date[0]));
        $db_model['tab'] = $type.'_bet_record';
        $db_model['type'] = 3;
        $field_name = $this->time_type($type);
        if (!empty($arr['uname'])) {
            $map['pkusername'] = $arr['uname'];
        }
           //订单查询
        if (!empty($arr['order'])) {
            switch ($type) {
                case 'eg':
                case 'lebo':
                    $map['game_id'] = $arr['order'];
                    break;
                case 'ag':
                    $map['bill_no'] = $arr['order'];
                    break;
                case 'og':
                    $map['order_number'] = $arr['order'];
                    break;
                case 'ct':
                    $map['transaction_id'] = $arr['order'];
                    break;
                case 'pt':
                    $map['GameCode'] = $arr['order'];
                    break;
                case 'bbin':
                    $map['wagers_id'] = $arr['order'];
                    break;
                case 'mg':
                    $map['bet_no'] = $arr['order'];
                    break;
            }
        }
        $map['site_id'] = $_SESSION['site_id'];
           if ($type =="bbin"){
            if ($leixing ==1){
                $map['gamekind'] =5;//电子
            }elseif($leixing ==2){
                $map['gamekind'] = array('not in',"(1,5,12)");  //视讯
            }elseif($leixing ==3){
                $map['gamekind'] =12; //彩票
            }elseif($leixing ==4){
                $map['gamekind'] =1; //体育
            }

        }
        if ($type =="mg"){
            if ($leixing ==1){
                $map['module_id'] =  array('in','(28,29,30,32)'); //视讯
            }elseif ($leixing ==2){
                $map['module_id'] =  array('<','28'); //电子
            }
        }elseif($type == 'pt'){
            $map['Bet + Win'] = array('>',0);
        }
        $map[$field_name[0]] = array(array('>=',$date[0]),array('<=',$date[1]));
        //判断是否联表
        //$is_union = $this->video_union($type,$date);
        if ($is_union) {
            $video_arr = $this->M($db_model)->field("sum($field_name[1]) as sum1,sum($field_name[2]) as sum2,sum($field_name[3]) as sum3")->where($map)->unselect('',$is_union);
        }else{
            $video_arr = $this->M($db_model)->field("sum($field_name[1]) as sum1,sum($field_name[2]) as sum2,sum($field_name[3]) as sum3")->where($map)->select();
        }
        return $video_arr[0];
    }

    //视讯是否联表判断
    public function video_union($type,$date){
        if (date('Ym',strtotime($date[1])) != date('Ym',strtotime($date[0]))) {
            $tab1 = $type.'_bet_record_'.date('Ym',strtotime($date[0]));
            $tab2 = $type.'_bet_record_'.date('Ym',strtotime($date[1]));
            return array('tab1'=>$tab1,'tab2'=>$tab2);
        }
    }

    //获取所有彩票下注概况
    public function get_overview_cp($map){
        $db_model['tab'] = 'c_bet';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("type,COUNT(DISTINCT(uid)) yhs,COUNT(js) zds,SUM(money) js_zxz,SUM(CASE WHEN status IN(1,2) THEN  money ELSE 0 END ) yx_zxz,SUM(win) pc,SUM(CASE WHEN status IN(1,2) THEN money ELSE 0 END)-SUM(win) jg")->where($map)->group('type')->select();

    }
    //获取所有彩票下注前10热门下注游戏数据
    public function get_hot_cp($map){
        $db_model['tab'] = 'c_bet';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("type,mingxi_1,sum(money) as allmoney,SUM(CASE WHEN status IN(1,2) THEN  money ELSE 0 END ) yx_zxz")->where($map)->group('type,mingxi_1')->order('allmoney DESC')->limit('0,10')->select();

    }

    //获取视讯所有中文对照
    public function video_type($vtype){
        $db_model = array();
        $db_model['tab'] = 'k_video_games';
        $db_model['type'] = 1;

        return $this->M($db_model)->where("vtype = '".$vtype."'")->select('type');
    }

    //将彩票类型写入redis
    public function getall_fc_type(){
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        //$data = $redis->hgetall('fc_type');
        if (empty($data)) {
            //将彩票类型写入redis
            //$redis->delete('fc_type');
            $cpmap['select'] = 'name';
            $cpmap['table'] = 'fc_games';
            $cpmap['where']['state'] = 1;
            $cplist = $this->get_table($cpmap);
            foreach ($cplist as $key => $val) {
                $fc_json = json_encode($val, JSON_UNESCAPED_UNICODE);
                $fc_json = str_replace('"', '-', $fc_json);
                $hset[$key] = $fc_json;
            }
            $redis->hmset('fc_type',$hset);
        } else {
            foreach ($data as $key => $value) {
                $value = str_replace('-', '"', $value);
                $cplist[$key] = json_decode($value,true);
            }
        }

        return $cplist;
    }
}