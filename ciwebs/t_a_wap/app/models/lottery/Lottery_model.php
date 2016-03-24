<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
//总彩票model
class Lottery_Model extends MY_Model
{

    public function __construct()
    {
        $this->init_db();
    }

    // 获取当期开盘关盘时间
    public function _opentime_list($type, $map = array())
    {
        if (! empty($map)) {
            $this->public_db->where($map);
        }
        $query = $this->public_db->get($type . '_opentime');
        $rows = $query->result_array();
        return $rows[0];
    }

    public function get_auto($map=array()){
        if (! empty($map['where'])) {
            $this->public_db->where($map['where']);
        }
        if (! empty($map['limit'])) {
            $this->public_db->limit($map['limit']);
        }
        $this->public_db->order_by('qishu desc');
        $query = $this->public_db->get($map['table']);
        //echo $this->public_db->last_query();
        $rows = $query->result_array();
        return $rows;
    }

    // 当前期数，$type_y 表示 类别 列：'六合彩'
    function _dq_qishu($type_y)
    {
        if ($type_y == 'liuhecai') {
            $now_time = func_nowtime("Y-m-d H:i:s");
        } else {
            $now_time = func_nowtime();
        }
        $map = array(
            'ok' => '0',
            'kaijiang>' =>$now_time
            // 'kaipan<=' => $now_time,
            // 'fengpan>=' => $now_time
        );
        $this->public_db->order_by('kaijiang', 'ASC');
        $data_time = $this->_opentime_list($type_y, $map);
        if(strtotime($now_time) < strtotime($data_time['kaipan'])){
            $data_time['f_t_stro'] = -1;
        }
        $date_Y = func_nowtime('Y');
        $date_y = func_nowtime('y');
        $date_ymd = func_nowtime('ymd');
        $date_Ymd = func_nowtime('Ymd');
        // 判断是否是当天的最后一期,如果是显示明天第一期
        if (empty($data_time['qishu'])) {
            $data_time['qishu'] = 1;
            $date_Y = func_nowtime('Y', "+24 hours");
            $date_y = func_nowtime('y', "+24 hours");
            $date_ymd = func_nowtime('ymd', "+24 hours");
            $date_Ymd = func_nowtime('Ymd', "+24 hours");
        }
        if ($type_y == 'liuhecai') {
            // 六合彩
           $data_time['qishu'] = func_BuLings($data_time['qishu']);
        } elseif ($type_y == 'fc_3d') {
            // 福彩3D每天一期
            // $data_time['qishu'] = func_BuLings(func_fc_qishu());
            // return $date_Y . func_BuLings($data_time['qishu']);

             $data_time['qishu'] = $date_Y . substr(strval(func_fc_qishu()+1000),1,3);
        } elseif ($type_y == 'pl_3') {
            // 排列3每天一期
            $data_time['qishu'] = $date_y . substr(strval(func_fc_qishu()+1000),1,3);
        } elseif ($type_y == 'bj_8') {
            // 北京快乐8
            $data_time['qishu'] = func_com_qishu('bj_8');
        } elseif ($type_y == 'bj_10') {
            // 北京PK10
            $data_time['qishu'] = func_com_qishu('bj_10');
        } elseif ($type_y == 'cq_ten') {
            // 重庆快乐10分
            $data_time['qishu'] = $date_ymd . func_BuLings($data_time['qishu']);
        } elseif ($type_y == 'gd_ten') {
            // 广东快乐十分
            $data_time['qishu'] = $date_Ymd . func_BuLing($data_time['qishu']);
        } else {
            $data_time['qishu'] = $date_Ymd . func_BuLings($data_time['qishu']);
        }
        return $data_time;
    }

 	/**
	 * 获取用户信息
	 * @param  [int] $uid 用户id
	 * @return [array]
	 */
    public function _get_userinfo($uid)
    {
        if (is_numeric($uid)) {
            $this->private_db->select('uid,agent_id,username,money,index_id,site_id');
            $this->private_db->where('k_user.uid', $uid);
            $query = $this->private_db->get("k_user");
            $rows = $query->row_array();
            if ($rows) {
                return $rows;
            }
        }
        return false;
    }

    /**
     * 获取用户设置金额
     * @param  [array] $map 所有参数
     * @param  [array] $map['where'] where语句
     * @return [array]
     */
    public function _get_ka_tan_set_money($map=array()){

        if (! empty($map['where'])) {
            $this->private_db->where($map['where']);
        }
        $query = $this->private_db->get('ka_tan_set_money');
        $rows = $query->result_array();
        if($rows){
            return $rows[0];
        }else{
            return FALSE;
        }
    }
    /**
     * 添加用户设置金额
     * @param  [array] $map 所有参数
     * @return [array]
     */
    public function _add_ka_tan_set_money($map=array()){
        $insert = $this->private_db->insert('ka_tan_set_money', $map);
        if (FALSE === $insert) {
            return $insert;
        }
        return $this->private_db->insert_id();
    }
    /**
     * 获取用户设置金额
     * @param  [array] $map 所有参数
     * @param  [string] $where where语句
     * @return []
     */
    public function _update_ka_tan_set_money($map=array(),$where){
        $this->private_db->where($where);
        $update = $this->private_db->update('ka_tan_set_money', $map);
        return $update;
    }

    /**
     * 获得赔率
     * @param  [array] $map 所有参数
     * @param [array] $map['select'] 所查字段
     * @param [array] $map['where'] where语句
     * @return [array]
     */
    public function _get_odds($map=array())
    {
        $map['where']['pankou'] = $_SESSION['pankou'];
        $this->public_db->select($map['select']);
        $this->public_db->from('c_odds_'.SITEID);
        $this->public_db->join('fc_games_type', 'fc_games_type.id=c_odds_'.SITEID.'.type_id', 'left');
        $this->public_db->order_by("sort", "asc");
        if ($map['where']) {
            $this->public_db->where($map['where']);
        }
        $query = $this->public_db->get();
        $rows = $this->object_array($query->result());
        if (count($rows) == 0) {
            return FALSE;
        }
        return $rows;
    }

    public function oddspx($arr,$type){
    	foreach ($arr as $k=>$v){
    		if(!is_numeric($v['input_name'])){
    			if($v['pankou'] == $type){
	    			switch ($v['input_name']){
	    				case '1-10' : $data[0] = $v;break;
	    				case '11-20' : $data[1] = $v;break;
	    				case '21-30' : $data[2] = $v;break;
	    				case '31-40' : $data[3] = $v;break;
	    				case '41-49' : $data[4] = $v;break;
	    				case '大' : $data[5] = $v;break;
	    				case '小' : $data[6] = $v;break;
	    				case '单' : $data[7] = $v;break;
	    				case '双' : $data[8] = $v;break;
	    				case '合大' : $data[9] = $v;break;
	    				case '合小' : $data[10] = $v;break;
	    				case '合单' : $data[11] = $v;break;
	    				case '合双' : $data[12] = $v;break;
	    				case '大单' : $data[13] = $v;break;
	    				case '小单' : $data[14] = $v;break;
	    				case '大双' : $data[15] = $v;break;
	    				case '小双' : $data[16] = $v;break;
	    				case '尾大' : $data[17] = $v;break;
	    				case '尾小' : $data[18] = $v;break;
	    				case '家禽' : $data[19] = $v;break;
	    				case '野兽' : $data[20] = $v;break;
	    				case '红波' : $data[21] = $v;break;
	    				case '绿波' : $data[22] = $v;break;
	    				case '蓝波' : $data[23] = $v;break;
	    			}
    			}
    		}
    	}
    	ksort($data);
    	//p($data);exit;
    	return $data;
    }

    public function get_fanshui(){
        $map = array();
        $map['site_id'] = SITEID;
        $map['is_delete'] = 0;
        $map['index_id'] = INDEX_ID;
        $this->private_db->where($map);
        $query = $this->private_db->get('k_user_discount_set');
        $rows = $query->result_array();
        if($rows[0]['liuhecai_discount']){
            return $rows[0]['liuhecai_discount'];
        }else{
            return FALSE;
        }
    }
    public function get_lottery_name($lotteryId){
        $map['table'] = 'fc_games';
        $map['select'] = 'name';
        $map['where']['type'] = $lotteryId;
        $data = $this->get_table_one($map,3);
        return $data['name'];
    }



    /**
     *  查询该用户的玩法限制金额
     * @param [int] $type 彩票类别
     * @param [string] $wanfa 玩法
     * @return [int]
     */
    public function _get_ball_limit($type,$wanfa=''){
        $fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten','gd_ten','bj_8',
            'bj_10','tj_ssc','xj_ssc','jx_ssc','jl_k3','js_k3',
            'liuhecai');

        $Fcgame = 'fc_games_view';
        $Uagent = 'k_user_agent_fc_set';
        $Uaset = 'k_user_fc_set';
        foreach ($fcArr as $key => $val) {
            $tmpA = $tmpB=array();
//             if(!empty($_GET["aid"])){
//                 $aid = $_GET["aid"];
//             }else{$aid='';}
//             if(!empty($_GET["uid"])){
//                 $uid = $_GET["uid"];
//             }else{$uid='';}
            $aid = $_SESSION['agent_id'];
            $uid = $_SESSION['uid'];
            $where = array( $Fcgame.'.fc_type ' => $val , $Uagent . '.aid' => $aid , $Uagent . '.site_id' => SITEID);
            $this->private_db->select('*');
            $this->private_db->from($Fcgame);
            $this->private_db->where($where);
            $this->private_db->join($Uagent, $Uagent . '.type_id = ' . $Fcgame . '.id');
            $query = $this->private_db->get();
            //echo $this->private_db->last_query();exit;
            $rows = $query->result_array();
            $tmpA = $rows;

            $map = array( $Fcgame.'.fc_type= ' => $val , $Uaset . '.uid = ' => $uid);
            $this->private_db->select('*');
            $this->private_db->from($Fcgame);
            $this->private_db->where($map);
            $this->private_db->join($Uaset, $Uaset . '.type_id = ' . $Fcgame . '.id');
            $query = $this->private_db->get();
            $rows1 = $query->result_array();
            $tmpB = $rows1;
            if(empty($tmpB)){
                $tmpB = array();
            }
            $fcType[$val] = array_merge($tmpA,$tmpB);
        }

        $sets = $fcType[$type];
        //p($wanfa);p($sets);exit;
        if(empty($wanfa)){
             return $sets;
        }else{
            if(!empty($sets)){
                foreach ($sets as $k => $v) {
                    if($v['name'] == $wanfa){
                        $arr['single_field_max'] = $v['single_field_max'];
                        $arr['single_note_max'] = $v['single_note_max'];
                        $arr['single_note_min'] = $v['min'];
                    }
                }
            }
            //p($arr);exit;
            return $arr;
        }

    }

  //下注验证
    public function check_form($data){
        $xiao_arr = array('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪');
        $num_arr = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49);
        $wei_arr = array(0,1,2,3,4,5,6,7,8,9);
        // p($data);
        if(isset($data['CombNum'])){
            $min = $data['CombNum'];
        }

        $type = $this->get_lottery_type($data['LotteryId']);
        foreach ($data['MBetParameters'] as $k => $v) {
            if(empty($v['Money'])){
                return 1;
            }
            if(!is_numeric(floatval($v['Money'])) || floatval($v['Money']) <= 0 ){
                return 1;
            }

            if(!is_numeric(floatval($v['Lines'])) || floatval($v['Lines']) <= 0 ){
                return 1;
            }

            //组合数判断
            if(isset($min)){
                if($type == 'bj_8'){
                    if(($v['Pel'] == '选一' && $min != 1) || ($v['Pel'] == '选二' && $min != 2) || ($v['Pel'] == '选三' && $min != 3) || ($v['Pel'] == '选四' && $min != 4) || ($v['Pel'] == '选五' && $min != 5)){
                        return 1;
                    }
                }elseif($type == 'gd_ten' || $type == 'cq_ten'){
                    if(($v['Pel'] == '任选二' && $min != 2) || ($v['Pel'] == '任选二组' && $min != 2) || ($v['Pel'] == '任选三' && $min != 3) || ($v['Pel'] == '任选四' && $min != 4) || ($v['Pel'] == '任选五' && $min != 5)){
                        return 1;
                    }
                }elseif($type == 'liuhecai'){
                    if(($v['Pel1'] == '二全中' && $min != 2) || ($v['Pel1'] == '二中特' && $min != 2) || ($v['Pel1'] == '特串' && $min != 2) || ($v['Pel1'] == '三全中' && $min != 3) || ($v['Pel1'] == '三中二' && $min != 3) || ($v['Pel1'] == '四中一' && $min != 4)|| ($v['Pel1'] == '二肖连中' && $min != 2)|| ($v['Pel1'] == '三肖连中' && $min != 3)|| ($v['Pel1'] == '四肖连中' && $min != 4)|| ($v['Pel1'] == '五肖连中' && $min != 5)|| ($v['Pel1'] == '二肖连不中' && $min != 2)|| ($v['Pel1'] == '三肖连不中' && $min != 3)|| ($v['Pel1'] == '四肖连不中' && $min != 4)|| ($v['Pel1'] == '二尾连中' && $min != 2)|| ($v['Pel1'] == '三尾连中' && $min != 3)|| ($v['Pel1'] == '四尾连中' && $min != 4)|| ($v['Pel1'] == '二尾连不中' && $min != 2)|| ($v['Pel1'] == '三尾连不中' && $min != 3)|| ($v['Pel1'] == '四尾连不中' && $min != 4)|| ($v['Pel1'] == '五不中' && $min != 5)|| ($v['Pel1'] == '六不中' && $min != 6)|| ($v['Pel1'] == '七不中' && $min != 7)|| ($v['Pel1'] == '八不中' && $min != 8)|| ($v['Pel1'] == '九不中' && $min != 9)|| ($v['Pel1'] == '十不中' && $min != 10)|| ($v['Pel1'] == '十一不中' && $min != 11)|| ($v['Pel1'] == '十二不中' && $min != 12)){
                        return 1;
                    }
                }

            }
            //组合重复性判断
            if(isset($min)){

                if($data['LotteryPan'] == 5 && $type == 'liuhecai'){//过关
                    $bet_arr = explode(',', $v['BetContext']);
                    if(count($bet_arr) < 2){
                        return 1;
                    }
                    $bet_arr = explode(',', $v['Pel1']);
                    if(count($bet_arr) < 2){
                        return 1;
                    }
                }elseif(($data['LotteryPan'] == 11 || $data['LotteryPan'] == 12 || $data['LotteryPan'] == 13)  && $type == 'liuhecai' ){
                    $true_arr = array();
                    switch ($data['LotteryPan']) {
                        case 11:
                            $true_arr = $xiao_arr;
                            break;
                        case 12:
                            $true_arr = $wei_arr;
                            break;
                        case 13:
                            $true_arr = $num_arr;
                            break;
                    }
                    $bet_arr = explode(',', $v['BetContext']);
                    $temp_arr = array();
                    foreach ($bet_arr as $kk => $vv) {
                        $bet_arr_1 = explode(':', $vv);
                        if( !in_array($bet_arr_1[0], $true_arr)){
                            return 1;
                        }
                        $temp_arr[] = $bet_arr_1[0];
                    }
                    if(!empty($temp_arr)){
                        $temp_arr_1 = array_flip(array_flip($temp_arr));
                        if(count($temp_arr_1) != count($temp_arr)){
                            return 1;
                        }
                    }
                }else{
                    $bet_arr = explode(',', $v['BetContext']);
                    if(!empty($bet_arr)){
                        $temp_arr = array_flip(array_flip($bet_arr));
                        if(count($bet_arr) != count($temp_arr)){
                            return 1;
                        }
                    }

                }
            }
        }
        return 2;
    }

    /**
     * 下注
     * @param [array] $data 数据用于循环插入下注
     * @param [array] $list 其他个别字段数组
     * @return [bool]
     */
    public function _addlottery_bet($data)
    {
        // p($data);exit;
        $fs = 0.01;
        $type = $this->get_lottery_type($data['LotteryId']);

        $now_qishu = $this->_dq_qishu($type);
        $lotteryname = $this->get_lottery_name($type);


        $uid=$_SESSION['uid'];
        $user = $this->_get_userinfo($uid);
        $this->private_db->trans_begin();

        //p($data['MBetParameters']);exit;
        $bet_all = 0;

        foreach ($data['MBetParameters'] as $k => $v) {
            if ($v['Money'] <= 0 || !is_numeric($v['Money'])) {

                $data=array("ErrorCode"=>5,"ErrorMsg"=>"下注金额错误");
                return json_encode($data);
            }
            $all_money+=floatval($v['Money']);
			$pel[$v['Pel']][] =floatval($v['Money']);
		//p($bet_limit);exit;
        }
		  	$bet_limit = array();
			foreach($pel as $k=>$v){
            //限红
            if(empty($bet_limit)){
                $bet_limit = $this->_get_ball_limit($type,$k);
            }
			//新加玩法限红记录还没添加,添加之后注释下面
				if(empty($bet_limit['single_field_max'])){
					$bet_limit['single_field_max'] = 50000;
				}
				if(empty($bet_limit['single_note_max'])){
					$bet_limit['single_note_max'] = 2000;
				}
				if(empty($bet_limit['single_note_min'])){
					$bet_limit['single_note_min'] = 1;
				}
			foreach($v as $key =>$val){
				if($val < $bet_limit['single_note_min']){
					$data=array("ErrorCode"=>5,"ErrorMsg"=>"下注金额超出限制");
					return json_encode($data);
				}
				if($val > $bet_limit['single_note_max']){
					$data=array("ErrorCode"=>5,"ErrorMsg"=>"下注金额超出限制");
					return json_encode($data);
				}
				$allmoeny[$k] += floatval($val);
			}
			if($allmoney[$k] > $bet_limit['single_field_max']){
					$data=array("ErrorCode"=>5,"ErrorMsg"=>"下注金额超出限制");
					return json_encode($data);
				}
		}

     	 if(floatval($all_money) > floatval($user['money'])){
				$data=array("ErrorCode"=>5,"ErrorMsg"=>"余额不足");
				return json_encode($data);
			}

        $error = $this->check_form($data);
        if($error == 1){

            $data=array("ErrorCode"=>5,"ErrorMsg"=>"参数错误");
            return json_encode($data);exit;
        }
// p($data);
        $pankou = $_SESSION['pankou'];
        foreach ($data['MBetParameters'] as $k => $v) {

            $odds_amount=$v['Lines']*$v['Money'];
            $this->private_db->where('uid='.$user['uid'].' and money > 0 and money >= "'.$v['Money'].'"');
            $this->private_db->set('money','money-'.$v['Money'],FALSE);
            $update = $this->private_db->update('k_user');
            if($this->private_db->affected_rows() != 1){
                $this->private_db->trans_rollback();
               $data=array("ErrorCode"=>5,"ErrorMsg"=>"由于网络堵塞，本次下注失败。");
                return json_encode($data);
            }

            $userinfo = $this->_get_userinfo($user['uid']);
            $cbet_ordernum = func_getdid();
            $c_betdata['did'] = $cbet_ordernum;
            $c_betdata['uid'] = $user['uid'];
            $c_betdata['agent_id'] = $user['agent_id'];
            $c_betdata['ua_id'] = $_SESSION['ua_id'];//总代id
            $c_betdata['sh_id'] = $_SESSION['sh_id'];//股东id
            $c_betdata['is_shiwan'] = $_SESSION['shiwan'];
            $c_betdata['username'] = $user['username'];
            $c_betdata['addtime'] = func_nowtime('Y-m-d H:i:s','now');
            $c_betdata['type'] =$lotteryname;
            $c_betdata['qishu']=$now_qishu['qishu'];
            $c_betdata['mingxi_1'] = $v['Pel'];
            $c_betdata['mingxi_2'] = $v['BetContext'];

            if(!empty($v['Pel1'])){
                    $c_betdata['mingxi_3'] = $v['Pel1'];
            }elseif(!empty($v['Txt'])){
                 $c_betdata['mingxi_3'] = $v['Txt'];
            }
            $c_betdata['odds'] = $v['Lines'];

            if($v['Pel'] == '尾数连' || $v['Pel'] == '生肖连'){
                $mingxi_2_arr = explode(',', $v['BetContext']);
                $odd_1 = 0;
                $mingxi_2 = '';
                foreach ($mingxi_2_arr as $k => $vv) {
                    $mingxi_2_arr_1 = explode(':', $vv);
                    $mingxi_2 .= ','.$mingxi_2_arr_1[0];
                    if(empty($odd_1)){
                        $odd_1 = $mingxi_2_arr_1[1];
                    }elseif($odd_1 > $mingxi_2_arr_1[1]){
                        $odd_1 = $mingxi_2_arr_1[1];
                    }
                }
                $c_betdata['mingxi_2'] = trim($mingxi_2,',');
                $c_betdata['odds'] = $odd_1;

            }

            $c_betdata['money'] = $v['Money'];
            $c_betdata['win'] = $odds_amount;
            $c_betdata['assets'] = $user['money'];//下注之前的余额
            $c_betdata['balance'] = $userinfo['money'];
            $c_betdata['fs'] = 0;
            $c_betdata['index_id'] = $userinfo['index_id'];
            $c_betdata['site_id'] = $userinfo['site_id'];

            $c_betdata['ptype'] = 1;
            $c_betdata['mingxi_1'] = trim($c_betdata['mingxi_1']);
            $c_betdata['mingxi_2'] = trim($c_betdata['mingxi_2']);
            $c_betdata['mingxi_3'] = trim($c_betdata['mingxi_3']);
            if($c_betdata['type'] == '重庆时时彩' || $c_betdata['type'] == '天津时时彩'|| $c_betdata['type'] == '新疆时时彩' || $c_betdata['type'] == '江西时时彩'  ){

                if($c_betdata['mingxi_1'] == '前三球' || $c_betdata['mingxi_1'] == '中三球' || $c_betdata['mingxi_1'] == '后三球'){
                    $c_betdata['mingxi_1'] = str_replace('球','',$c_betdata['mingxi_1']);
                }
            }
            if($c_betdata['type'] == '六合彩'){

                if($c_betdata['mingxi_1'] == '特码生肖'){
                    $c_betdata['mingxi_1'] = '生肖';
                }
                if($c_betdata['mingxi_1'] == '全不中'){
                    $c_betdata['mingxi_3'] = num2char($data['CombNum']).'不中';
                }
                if($c_betdata['mingxi_1'] == '特码' && $c_betdata['mingxi_3'] == '特B'){
                    $c_betdata['mingxi_3'] = '特码';
                    $pankou = 'B';
                }
                if($c_betdata['mingxi_1'] == '特码' && $c_betdata['mingxi_3'] == '特A'){
                    $c_betdata['mingxi_3'] = '特码';
                }
                if($c_betdata['mingxi_1'] == '一肖/尾数'){
                    if($c_betdata['mingxi_3'] == '尾数'){
                        $c_betdata['mingxi_2'] = str_replace('尾', '', $c_betdata['mingxi_2']);
                        $c_betdata['mingxi_1'] = '尾数';
                    }elseif($c_betdata['mingxi_3'] == '一肖'){
                        $c_betdata['mingxi_1'] =  '生肖';
                    }
                }
                if($c_betdata['mingxi_1'] == '合肖' || $c_betdata['mingxi_1'] == '生肖连' || $c_betdata['mingxi_1'] == '尾数连'){
                    $c_betdata['mingxi_2'] = str_replace('尾', '', $c_betdata['mingxi_2']);
                    $c_betdata['mingxi_3'] = $data['MBetParameters'][0]['Pel1'];
                }
                if($c_betdata['mingxi_1'] == '合肖'){
                    $c_betdata['mingxi_1'] =  '生肖';
                }
                if($c_betdata['mingxi_1'] == '连码'){
                    if($c_betdata['mingxi_3'] == '三中二' || $c_betdata['mingxi_3'] == '二中特'){
                        $c_betdata['mingxi_3'] = str_replace(',', ';', $data['MBetParameters'][0]['Txt']);
                    }

                }
                if($c_betdata['mingxi_1'] == '过关'){
                    // p($c_betdata);exit;
                    $c_betdata['mingxi_3'] = rtrim($c_betdata['mingxi_3'],',');
                    $c_betdata['mingxi_2'] = $data['MBetParameters'][0]['DisplayText'];
                    $c_betdata['odds'] =  number_format($c_betdata['odds'], 2, '.', '');
                }
                if($c_betdata['mingxi_1'] == '正码1-6'){
                    $c_betdata['mingxi_1'] = '正1-6';
                }
                if($c_betdata['mingxi_1'] == '正码特'){
                    $c_betdata['mingxi_1'] = '正特';
                }
            }

            if($c_betdata['type'] == '重庆快乐十分' || $c_betdata['type'] == '广东快乐十分' ){
                if($c_betdata['mingxi_1'] == '任选二组' || $c_betdata['mingxi_1'] == '任选二' ||$c_betdata['mingxi_1'] == '任选三' ||$c_betdata['mingxi_1'] == '任选四' ||$c_betdata['mingxi_1'] == '任选五' ){

                    $c_betdata['mingxi_3'] = '';
                }
                if($c_betdata['mingxi_2'] == '合单'){
                    $c_betdata['mingxi_2'] = '合数单';
                }elseif($c_betdata['mingxi_2'] == '合双'){
                    $c_betdata['mingxi_2'] = '合数双';
                }
            }
            if($c_betdata['type'] == '北京赛车pk拾' ){
                if($c_betdata['mingxi_2'] == '龙' || $c_betdata['mingxi_2'] == '虎'){
                    if($c_betdata['mingxi_1'] == '冠军'){
                        $c_betdata['mingxi_3'] = '1V10 龍虎';
                    }elseif($c_betdata['mingxi_1'] == '亚军'){
                        $c_betdata['mingxi_3'] = '2V9 龍虎';
                    }elseif($c_betdata['mingxi_1'] == '第三名'){
                        $c_betdata['mingxi_3'] = '3V8 龍虎';
                    }elseif($c_betdata['mingxi_1'] == '第四名'){
                        $c_betdata['mingxi_3'] = '4V7 龍虎';
                    }elseif($c_betdata['mingxi_1'] == '第五名'){
                        $c_betdata['mingxi_3'] = '5V6 龍虎';
                    }
                    $c_betdata['mingxi_1'] = '龍虎';
                }
                $c_betdata['type'] = '北京赛车PK拾';
                if($c_betdata['mingxi_1'] == '冠、亚军和'){
                    $c_betdata['mingxi_1'] = str_replace('、','',$c_betdata['mingxi_1']);
                }
            }
            // p($c_betdata);
            if($c_betdata['type'] == '北京快乐8'){
                // p($c_betdata);
                if(strstr($c_betdata['mingxi_3'], '/')){
                    $str1 = '';
                    if($c_betdata['mingxi_1'] == '选二'){
                        $xia_arr_2 = explode(':', $c_betdata['mingxi_3']);
                        $xia_arr_3 = explode('/', $xia_arr_2[0]);
                        $str = '';
                        foreach ($xia_arr_3 as $k => $v) {
                            $str .= num2char($v).'中';
                        }
                        $str = substr($str,0,(strlen($str)-3));
                        $str1 = $str;
                    }elseif ($c_betdata['mingxi_1'] == '选一') {
                    }else{
                        $xia_arr_1 =  explode(',', $c_betdata['mingxi_3']);
                        foreach ($xia_arr_1 as $kk => $vv) {
                            $xia_arr_2 = explode(':', $vv);
                            $xia_arr_3 = explode('/', $xia_arr_2[0]);
                            $str = '';
                            foreach ($xia_arr_3 as $k => $v) {
                                $str .= num2char($v).'中';
                            }
                            $str = substr($str,0,(strlen($str)-3));
                            $aaa = $str.':'.$xia_arr_2[1];
                            // echo $aaa;
                            $str1 .= $aaa.',';
                        }
                        $str1 = substr($str1,0,(strlen($str1)-1));
                    }
                    $c_betdata['mingxi_3'] = $str1;
                }
            }

            $c_betdata['pankou'] = $pankou;
            if($pankou == 'B' && $c_betdata['mingxi_1'] == '特码'){//反水
                $fs_re =  $this->get_fanshui();
                $fs_re = $fs_re?$fs_re:0;
                $c_betdata['fs'] = $c_betdata['money'] * $fs * $fs_re;
            }
            $this->private_db->insert('c_bet', $c_betdata);
            $source_id=$this->private_db->insert_id();
            $c_betdata = array();
            $remark = "彩票注单：" . $cbet_ordernum . " , 類型:" . $lotteryname;
            $record['source_id'] = $source_id;
            $record['index_id'] = $userinfo['index_id'];
            $record['source_type'] = 7;//彩票下注类型
            $record['site_id'] = $userinfo['site_id'];
            $record['uid'] = $user['uid'];
            $record['agent_id'] = $user['agent_id'];
            $record['is_shiwan'] = $_SESSION['shiwan'];
            $record['username'] = $user['username'];
            $record['cash_type'] = 3;
            $record['cash_do_type'] = 2;
            $record['ptype'] = 1;
            $record['cash_num'] =$v['Money'];
            $record['cash_balance'] = $userinfo['money'];
            $record['cash_date'] = func_nowtime('Y-m-d H:i:s','now');
            $record['remark'] =$remark;
            $rcash=$this->private_db->insert('k_user_cash_record', $record);
            if(empty($update) || empty($source_id) || empty($rcash)){
                $this->private_db->trans_rollback();
                $data=array("ErrorCode"=>5,"ErrorMsg"=>"未知错误");
                return json_encode($data);
            }
        }
            $this->private_db->trans_commit();
            $data=array("ErrorCode"=>0,"ErrorMsg"=>"投注成功");
            return json_encode($data);
    }

    public function get_lottery($map)
    {
        $mapf['table'] = 'fc_games';
        $mapf['select'] = 'name as LotteryName,type,id as LotteryID';
        $mapf['where']['state'] = 1;
        $list = $this->get_table($mapf,3);
        //去掉维护的
        $this->load->model('maintenance_model');
        foreach ($list  as $k=>$v){
            $status = $this->maintenance_model->getweihu($v['type'],true)?0:1;
            $qishu  = $this->_dq_qishu($v['type']);
            $fengpan_time = $this->_get_fengpan_time($v['type']);
            $list[$k]['State'] = $status;
            $list[$k]['LotteryID'] = (int)$v['LotteryID'];
           // echo date("Y-m-d H:i:s",strtotime(func_nowtime($qishu['fengpan'])))."|".date("Y-m-d H:i:s",strtotime(func_nowtime()));
            // p($fengpan_time);

            $list[$k]['OpenCount'] = $fengpan_time['f_t_stro'];
           // $list[$k]['LotteryID'] =2;
        }
        return $list;
    }


    /**
     * 读取最近8条下注记录
     * @param [int] $uid 用户id
     * @param [array] $fc_type 彩票类别
     * @return [array]
     */
    public function _get_bet($uid, $fc_type)
    {
        $this->private_db->select("*");
        $this->private_db->where("uid", $uid);
        $this->private_db->where("type", $fc_type);
        $this->private_db->order_by("addtime DESC");
        $query = $this->private_db->get('c_bet', 8);
        //echo $this->private_db->last_query();exit;
        $rows = $query->result_array();
        return $rows;
    }
    /**
     * 读取最近30次的开奖结果
     * @param [int] $count 数量
     * @param [array] $fc_type 彩票类别
     * @return [array]
     */
    public function _get_result($fc_type,$count=30){
        if($fc_type){
            $this->public_db->select("*");
            $this->public_db->order_by("qishu DESC");
            $query = $this->public_db->get($fc_type.'_auto',$count);
            $rows = $query->result_array();
            return $rows;
        }
        return false;
    }

    public function _get_chuqiu($fc_type,$num){
        if($fc_type && $num){
            $res = $this->_get_result($fc_type,30);
            //$chuqiu = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);
            $qiu = array();

            if($res){
                for($i=1;$i<=$num;$i++){
                    $chuqiu = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0);
                    foreach($res as $k => $r){
                        //echo $k . "|" . $r['ball_1'] . '<br>';
                        if($r['ball_'.$i] == 0){
                            $chuqiu[0] = $chuqiu[0] + 1;
                        }elseif($r['ball_'.$i] == 1){
                            $chuqiu[1] = $chuqiu[1] + 1;
                        }elseif($r['ball_'.$i] == 2){
                            $chuqiu[2] = $chuqiu[2] + 1;
                        }elseif($r['ball_'.$i] == 3){
                            $chuqiu[3] = $chuqiu[3] + 1;
                        }elseif($r['ball_'.$i] == 4){
                            $chuqiu[4] = $chuqiu[4] + 1;
                        }elseif($r['ball_'.$i] == 5){
                            $chuqiu[5] = $chuqiu[5] + 1;
                        }elseif($r['ball_'.$i] == 6){
                            $chuqiu[6] = $chuqiu[6] + 1;
                        }elseif($r['ball_'.$i] == 7){
                            $chuqiu[7] = $chuqiu[7] + 1;
                        }elseif($r['ball_'.$i] == 8){
                            $chuqiu[8] = $chuqiu[8] + 1;
                        }elseif($r['ball_'.$i] == 9){
                            $chuqiu[9] = $chuqiu[9] + 1;
                        }
                    }
                    $qiu[$i] = $chuqiu;
                }
            }
            //p($qiu);
            return $qiu;
        }
        return false;
    }

    /**
     * 查看当前是否是封盘时间
     * @param [array] $type 彩票类别
     * @return [bool]
     */
    public function _is_fengpan($type)
    {
        $now_time = func_nowtime();
        $map=array('ok' => 0 , 'kaijiang >' => $now_time , 'fengpan <' => $now_time );
        $this->public_db->where($map);
        $this->public_db->order_by('kaijiang', 'ASC');
        $query = $this->public_db->get($type.'_opentime');
        $rows = $query->result_array();

        if (!empty($rows)) {
            return false;
            //封盘中
        } else {
            return true;
            //开盘中
        }
    }


    //通过ID获得玩法名称
    public function _get_wanfa_name($source_id){

    }

    /**
     *  查询当期玩法已下注的金额
     * @param [array] $fc_type 彩票类别
     * @param [array] $wanfa_id 彩票玩法id
     * @return [double]
     */
    public function _beted_limit($fc_type, $wanfa_id='')
    {
        // p($fc_type);p($wanfa_id);exit;
        $qishu = $this->_dq_qishu($fc_type);
        $map = array();
        $map['type'] = eng2chn($fc_type);
        $map['username'] = $_SESSION['username'];
        $map['qishu'] = $qishu;

        if(!empty($wanfa_id)){
            $wanfa_name = $this->_get_wanfa_name($wanfa_id);
            $map['mingxi_1'] = $wanfa_name;
        }

        $this->private_db->where($map);
        $this->private_db->select("sum(money)");
        $query = $this->private_db->get('c_bet');
        $data = $query->result_array();
        $money = $data[0]["sum(money)"] != "" ? $data[0]["sum(money)"] : 0;
        return $money;
    }

    public function _get_head(){
        $this->public_db->from('fc_games');
        $this->public_db->order_by("id", "asc");
        $query = $this->public_db->get();
        $rows = $this->object_array($query->result());
        if (count($rows) == 0) {
            return FALSE;
        }
        return $rows;
    }

    /**
     *  查询所有玩法
     * @param [array] $map
     * @return [array]
     */
    public function _get_fc_games($map=array()){

        $this->public_db->from('fc_games');
        $this->public_db->order_by("id", "asc");
        $query = $this->public_db->get();
        $rows = $this->object_array($query->result());
        if (count($rows) == 0) {
            return FALSE;
        }
        return $rows;
    }

    /**
     *  查询所有玩法的子类玩法
     * @param [array] $map['where'] where语句
     * @return [array]
     */
    public function _get_fc_games_type($map=array()){

        $this->public_db->from('fc_games_type');
        $this->public_db->where($map['where']);
        $this->public_db->order_by("id", "asc");
        $query = $this->public_db->get();
        $rows = $this->object_array($query->result());
        if (count($rows) == 0) {
            return FALSE;
        }
        foreach ($rows as $k => $v) {
            $wanfa_close = $this->lottery->_get_close_arr('',2,$v['id']);

            if($wanfa_close[0]['value'] != 1){
                $data[] = $v;
            }

        }
        return $data;


    }

    /**
     *  查询所有玩法的模板名
     * @param [int] $type_id 彩票类别id
     * @return [string]
     */
    public function _get_tep_type($type_id){
        $where = array('id'=>$type_id);
        $select = 'tep_type';
        $this->private_db->from('fc_games_type');
        $this->private_db->select($select);
        $this->private_db->where($where);
        $this->private_db->order_by("id", "asc");
        $query = $this->private_db->get();
        $rows = $this->object_array($query->result());
        if (count($rows) == 0) {
            return FALSE;
        }
        return $rows[0]['tep_type'];
    }
   /**
     *  查询封盘时间
     * @param [int] $type 彩票类别
     * @param [string] $adddate 服务器时间增加小时
     * @return [string]
     */
    public function _get_fengpan_time($type, $adddate = '+12 hours')
    {

        $now_time = func_nowtime("H:i:s");
        $c_time = func_nowtime("Y-m-d");
        $now_time_day = func_nowtime("d");
        switch ($type) {
            case 'fc_3d':
                $where = "ok ='0'";
                break;
            case 'pl_3':
                $where = "ok ='0'";
                break;
            case 'liuhecai':
                $now_time = func_nowtime("Y-m-d H:i:s");
                 $where = "ok ='0' and kaijiang > '" . $now_time . "' and kaipan < '" . $now_time . "'";
                break;
            default:
                $where = "ok ='0' and kaijiang > '" . $now_time . "'";
                break;
        }
        // 查询是否开盘
        $this->public_db->where($where);
        $this->public_db->select("*");
        $this->public_db->order_by('kaijiang', 'ASC');
        $query = $this->public_db->get($type.'_opentime');
        $data = $query->result_array();
        $data_time = $data[0];
// p($now_time);
        if(empty($data_time)){
            $this->public_db->select("*");
            $this->public_db->order_by('kaijiang', 'ASC');
            $query = $this->public_db->get($type.'_opentime');
            $data = $query->result_array();
            $data_time = $data[0];
        }
        if ($type != 'liuhecai') {
            // $f_t = $c_time. ' ' . $data_time['fengpan'];
            // $k_t = $c_time. ' ' . $data_time['kaijiang'];
            // $o_t = func_nowtime("Y-m-d",$adddate).' '. $data_time['kaipan'];
            $f_t = $data_time['fengpan'];
            $k_t = $data_time['kaijiang'];
            $o_t = $data_time['kaipan'];
            $array['f_t_stro'] = strtotime($f_t) - strtotime($now_time); // 距离封盘的时间
             //封盘补丁

            if(strtotime($now_time) < strtotime($data_time['kaipan'])){
                $array['f_t_stro'] = -1;
            }
            // echo $f_t,'<br/>';
            // echo $now_time,'<br/>';
            // echo $data_time['kaipan'],'<br/>';
            // exit;
            $array['k_t_stro'] = strtotime($k_t) - strtotime($now_time); // 距离开奖的时间
            if((strtotime($k_t) - strtotime($now_time)) < 0){
                $array['k_t_stro'] = (strtotime($c_time.' '.$k_t)+24*60*60) - strtotime($c_time.' '.$now_time);
            }
            $array['o_t_stro'] = strtotime($o_t) - strtotime($now_time); // 距离开盘的时间
            $array['f_state'] = $c_time. ' ' . $data_time['fengpan']; // 封盘状态判断时间
            $array['o_state'] = $c_time. ' ' . $data_time['kaipan']; // 开盘状态判断时间
            $array['c_time'] = $c_time. ' ' . $now_time;
        } else {

            $f_t = $data_time['fengpan'];
            $o_t = $data_time['kaipan'];
            $f_t_day = explode('-', $o_t);
            $f_t_day = $f_t_day[2];
            $left_hours = ($f_t_day - $now_time_day) * 24 * 60 * 60; // 距离下次开盘的天数换成秒

            $array['f_t_stro'] = strtotime($f_t) - strtotime($now_time); // 距离封盘的时间
            $array['o_t_stro'] = (strtotime($o_t) - strtotime($now_time)); // 距离开盘的时间
            $array['f_state'] = $data_time['fengpan']; // 封盘状态判断时间
            $array['o_state'] = $data_time['kaipan']; // 开盘状态判断时间
            $array['c_time'] = $now_time;
             //封盘补丁

            if(strtotime($now_time) < strtotime($data_time['kaipan'])){
                $array['f_t_stro'] = -1;
            }
        }
        return $array;
    }


    /**
     *  判断会员额度是否大于投注总额
     * @param [int] $username 用户名称
     * @param [string] $money 当前下注金额
     * @return [int]
     */
    public function _user_money($username,$money){
        $where = array('username' => $username , 'site_id' => SITEID);
        $this->private_db->where($where);
        $this->private_db->select("money");
        $query = $this->private_db->get('k_user');
        $data = $query->result_array();
        $user = $data[0];
        if($money<=0){
            return -1;
        }
        if($user['money']-$money>=0){
            return $user['money']-$money;
        }else{
            return -1;
        }
    }



    /**
     *  查询开奖结果
     * @param [int] $type 彩票类别
     * @return [array]
     */
    public function _get_last_auto($type){
        $where = array('ok' => 1);
        $this->public_db->where($where);
        $this->public_db->order_by("qishu",'DESC');
        $query = $this->public_db->get($type.'_auto');
        $data = $query->result_array();
        $auto = $data[0];
        return $auto;
    }

    /**
     *  查询盘口
     *  @param [str] $lottery_type 彩种
     *  @param [str] 查询类型：1彩种 2玩法 3盘口
     * @return [str]
     */
    public function _get_pankou($lottery_type){
        if(!empty($lottery_type)){
             // $arr = $this->_get_close_arr($lottery_type,3);(玩法关闭要重写)
            if(!empty($arr[0])){
                return $arr[0]['value'];
            }else{
                $where = array('site_id' => SITEID);
                $this->private_db->where($where);
                $this->private_db->order_by("id",'ASC');
                $query = $this->private_db->get('web_config');
                $data = $query->result_array();
                $auto = $data[0]['lottery_pan'];
                return $auto;
            }
        }else{
            return false;
        }

    }

    /**
     *  查询父级pid
     * @param [int] $type 父级ID
     * @return [array]
     */
    public function get_pagent_user($pid,$list=''){
      $map['table'] = 'k_user_agent';
      $map['select']='agent_user,agent_type,pid,id';
      $map['where']['id'] = $pid;
      $rows = $this->get_table_one($map);
      $list[$rows['agent_type']] = $rows['id'];
      if($rows['pid']){
      $list = array_merge($this->get_pagent_user($rows['pid'],$list),$list);
      }
      return $list;
    }

    /**
     *  查询玩法关闭表
     *  @param [str] $lottery_type 彩种
     *  @param [str] $wanfa 玩法
     *  @param [str] 查询类型：1彩种 2玩法 3盘口
     *  @return [array]
     */
    public function _get_close_arr($lottery_type,$type,$wanfa=''){
        if(!empty($_SESSION['agent_id'])){
            $arr = $this->get_pagent_user($_SESSION['agent_id']);
            if(!empty($arr)){
                foreach ($arr as $k => $v) {
                    $ids[]=$v;
                }
                if(!empty($type)){
                    $where['type'] = $type;
                }
                if(!empty($lottery_type)){
                    $where['caizhong'] = $lottery_type;
                }
                $where['site_id'] = SITEID;
                if(!empty($wanfa)){
                    $where['wanfa'] = $wanfa;
                }

                $this->private_db->where($where);
                $this->private_db->where_in('agent_id',$ids);
                $this->private_db->order_by("agent_id",'DESC');
                $query = $this->private_db->get('close_lottery');
                $data = $query->result_array();
                return $data;
            }
        }
    }


    // public function _get_num_to_name($num){
    //     $where = array('id' => $num);
    //     $this->private_db->where($where);
    //     $query = $this->private_db->get('fc_games');
    //     $data = $query->result_array();
    //     return $data[0]['type'];
    // }


    //查询所有的单个彩种赔率
    public function get_odds_all($type){

        if (! empty($type)) {
            $this->public_db->select('c_odds_'.SITEID.'.id,input_name,odds_value,count_arr,fc_games_type.fc_type');
            $map['c_odds_'.SITEID.'.pankou']  =  $_SESSION['pankou'];
            $map['c_odds_'.SITEID.'.lottery_type'] = $type;
			$map['c_odds_'.SITEID.'.index_id'] = INDEX_ID;
            $this->public_db->where($map);
            $this->public_db->join('fc_games_type', 'fc_games_type.id=c_odds_'.SITEID.'.type_id', 'left');
            $this->public_db->order_by('c_odds_'.SITEID.'.type_id', "asc");

            $query = $this->public_db->get('c_odds_'.SITEID);
            //echo $this->public_db->last_query();
            $rows = $query->result_array();
            return $rows;
        }
    }

    //查询所有的单个彩种赔率
    public function get_odds($type,$gamename2,$pk=""){

        if (! empty($type)) {
            $this->public_db->select('id,input_name,odds_value,count_arr,type2,type_id,pankou');
            $map['type_id'] = $type;
            if(!empty($gamename2)){
                $map['type2'] = $gamename2;
            }
            if(empty($pk)){
            	$map['pankou'] = 'A';
            }
			$map['index_id'] = INDEX_ID;
            $this->public_db->where($map);
            $this->public_db->order_by('sort', "asc");

            $query = $this->public_db->get('c_odds_'.SITEID);
            //echo $this->public_db->last_query();exit;
            $rows = $query->result_array();
            return $rows;
        }
    }

    //查询所有的单个彩种赔率
    public function get_odds_lianma($type,$gamename2,$pk=""){

        if (! empty($type)) {
            $this->public_db->select('id,input_name,odds_value,count_arr,type2,type_id,pankou');
            $map['type_id'] = $type;
            if(!empty($gamename2)){
                $map['input_name'] = $gamename2;
            }
            if(empty($pk)){
                $map['pankou'] = 'A';
            }
			$map['index_id'] = INDEX_ID;
            $this->public_db->where($map);
            $this->public_db->order_by('sort', "asc");

            $query = $this->public_db->get('c_odds_'.SITEID);
            //echo $this->public_db->last_query();exit;
            $rows = $query->result_array();
            return $rows;
        }
    }

    //查询所有的单个彩种赔率
    public function get_odds_one($LotteryId,$Lotterypan=0){

        if (! empty($LotteryId)) {
            $this->public_db->select('c_odds_'.SITEID.'.id,odds_value,type_id');
            if($LotteryId != 4 && $Lotterypan != 1){
            	$map['c_odds_'.SITEID.'.pankou']  = 'A';
            }
            $map['fc_games.id'] = $LotteryId;
			$map['c_odds_'.SITEID.'.index_id']  = INDEX_ID;
            $this->public_db->where($map);
            $this->public_db->join('fc_games', 'fc_games.type=c_odds_'.SITEID.'.lottery_type', 'left');
            $this->public_db->order_by('c_odds_'.SITEID.'.sort', "asc");

            $query = $this->public_db->get('c_odds_'.SITEID);

            $rows = $query->result_array();
            $count = $LotteryId==4?49:80;
            //$oddsall = array('202','203','204','205','206');
            foreach ($rows as $k=>$v){
//                 if (in_array($rows[$k]['type_id'], $oddsall)) {
//                     $rows[$k] = $this->lottery_model->selected_one(array('0'=>$rows[$k]),$count);
//                     foreach ($rows[$k] as $k1=>$v1){
//                         $list[$v1['id']]=$v1['odds_value'];
//                     }
//                 }else{
                $list[$v['id']]=$v['odds_value'];
//                 }
            }
            return $list;
        }
    }
public function get_lottery_type($LotteryId,$key='type'){
    $mapf['table'] = 'fc_games';
    $mapf['select'] = $key;
    $mapf['where']['id'] = $LotteryId;
    $list = $this->get_table_one($mapf,3);
    return $list[$key];
}

public function selected_one($odds,$count=80){
    for($i=1;$i<=$count;$i++){
        $lists[$i-1]['id'] = $odds[0]['id']."_".$i;
        $lists[$i-1]['input_name'] = $i;
        $lists[$i-1]['odds_value'] = $odds[0]['odds_value'];
        $lists[$i-1]['name'] = $odds[0]['name'];
    }
    return $lists;
}

    public function _get_json($type){
        $json_data;
        $fengpan_time = $this->_get_fengpan_time($type);
        $kaijiang = $this->_get_result($type,1);//最近开奖结果
        if($kaijiang[0]){
            $last_kaijiang = '';
            foreach ($kaijiang[0] as $k => $v) {
                $balls = explode('ball_', $k);
                if( !empty($balls[1]) ){
                    $last_kaijiang .= ','.$v;
                }else{
                    continue;
                }
            }
            $last_kaijiang = trim($last_kaijiang,',');
        }
        // $kaijiang_last30 = $this->_get_result($type,30);
        // $changlong_cq_ssc =  get_changlong($kaijiang_last30,$type);
        //两面长龙(后期再加)
        $odds_json = $this->get_odds_all($type);
        $pailie_odds_json = pailie_odds_json($odds_json);
        $money = $this->_get_userinfo($_SESSION['uid']);
        $NotCountSum = $this->_beted_limit($type);



$json_data['Success'] = 1;
$json_data['Msg'] = '';
$json_data['ExtendObj'] = array('IsLogin' => true);//是否登录
$json_data['OK'] = false;
$json_data['PageCount'] = 0;
// $json_data['Obj']['ChangLong'] = [["第一球-单","4"], ["第五球-小","4"], ["总和双","2"], ["龙","2"], ["第三球-双","2"], ["第四球-小","2"], ["第四球-双","2"]];
$json_data['Obj']['CurrentPeriod'] = $this->_dq_qishu($type);//当前期数
$json_data['Obj']['CloseCount'] = $fengpan_time['f_t_stro'];//距离封盘时间
$json_data['Obj']['OpenCount'] = $fengpan_time['k_t_stro'];//距离开奖时间
$json_data['Obj']['PrePeriodNumber'] = $kaijiang[0]['qishu'];//最近开奖期数
$json_data['Obj']['PreResult'] = $last_kaijiang;//最近开奖结果
$json_data['Obj']['WinLoss'] = 0;
$json_data['Obj']['NotCountSum'] = $NotCountSum;//及时下注(当期下注)
$json_data['Obj']['Balance'] = $money['money'];//余额
$json_data['Obj']['ZongchuYilou'] = array("miss" => [] , "hit" => array("n1" => [5,2,0,3,3,4,4,5,2,2] , "n2" => [5,2,0,3,3,4,4,5,2,2] , "n3" => [5,2,0,3,3,4,4,5,2,2] , "n4" => [5,2,0,3,3,4,4,5,2,2] , "n5" => [5,2,0,3,3,4,4,5,2,2] ));//出球概率
$json_data['Obj']['Lines'] = $pailie_odds_json;//所有的赔率
$json_data['Obj']['LuZhu'] = [
array("c"=>"7:1,3:1,1:1,5:1,0:1,6:1,8:1,3:1,5:1","n" => "第一球" , "p" => 1),
array("c"=>"7:1,3:1,1:1,5:1,0:1,6:1,8:1,3:1,5:1","n" => "第二球" , "p" => 2),
array("c"=>"7:1,3:1,1:1,5:1,0:1,6:1,8:1,3:1,5:1","n" => "第三球" , "p" => 3),
array("c"=>"7:1,3:1,1:1,5:1,0:1,6:1,8:1,3:1,5:1","n" => "第四球" , "p" => 4),
array("c"=>"7:1,3:1,1:1,5:1,0:1,6:1,8:1,3:1,5:1","n" => "第五球" , "p" => 5),
array("c"=>"大:1,小:3,大:3,小:2,大:1,小:4,大:4,小:1,大:1,小:1,大:4,小:1,大:1,小:1,大:2","n" => "总和大小" , "p" => 0),
array("c"=>"双:2,单:3, 双:4,单:1,双:1,单:3,双:1,单:5,双:1,单:1,双:2,单:2,双:2,单:2","n" => "总和单双" , "p" => 0),
array("c"=>"大:1,小:2,大:1,小:1,大:2,小:1,大:2 ,小:1,大:1,小:4,大:7,小:1,大:2,小:3,大:1","n" => "大小" , "p" => 1),
array("c"=>"单:4,双:3,单:2,双:2,单:3,双:2,单:3,双:1,单:3,双:1,单:1, 双:5","n" => "单双" , "p" => 1),
array("c"=>"大:1,小:2,大:1,小:1,大:2,小:1,大:2 ,小:1,大:1,小:4,大:7,小:1,大:2,小:3,大:1","n" => "大小" , "p" => 2),
array("c"=>"单:4,双:3,单:2,双:2,单:3,双:2,单:3,双:1,单:3,双:1,单:1, 双:5","n" => "单双" , "p" => 2),
array("c"=>"大:1,小:2,大:1,小:1,大:2,小:1,大:2 ,小:1,大:1,小:4,大:7,小:1,大:2,小:3,大:1","n" => "大小" , "p" => 3),
array("c"=>"单:4,双:3,单:2,双:2,单:3,双:2,单:3,双:1,单:3,双:1,单:1, 双:5","n" => "单双" , "p" => 3),
array("c"=>"大:1,小:2,大:1,小:1,大:2,小:1,大:2 ,小:1,大:1,小:4,大:7,小:1,大:2,小:3,大:1","n" => "大小" , "p" => 4),
array("c"=>"单:4,双:3,单:2,双:2,单:3,双:2,单:3,双:1,单:3,双:1,单:1, 双:5","n" => "单双" , "p" => 4),
array("c"=>"大:1,小:2,大:1,小:1,大:2,小:1,大:2 ,小:1,大:1,小:4,大:7,小:1,大:2,小:3,大:1","n" => "大小" , "p" => 5),
array("c"=>"单:4,双:3,单:2,双:2,单:3,双:2,单:3,双:1,单:3,双:1,单:1, 双:5","n" => "单双" , "p" => 5),
array("c"=>"龙:2,虎:1,龙:1,虎:2,龙:4,虎:1,龙:2,虎:1,龙:9,虎:2,龙:1,虎:4","n" => "龙虎" , "p" => 0)
];//最下面子出球概率

        return JSON($json_data);
    }

	    public function _get_week_recored($uid){
               //获取七天前日期
            for ($i=0; $i <7 ; $i++) {
              $tmp_date = date('Y-m-d', strtotime("-$i days"));
              $weeks[$i]['tmp_date'] = $tmp_date;
              $weeks[$i]['begin_date'] = $tmp_date.' 00:00:00';
              $weeks[$i]['end_date'] = $tmp_date.' 23:59:59';
            }
            $cstr = '';
            foreach ($weeks as $key => $val) {
                $cstr .= "sum(case when addtime >= '".$val['begin_date']."' and addtime <= '".$val['end_date']."' and `status` in ('1','2') then money end ) as bet".$key.",sum(case when addtime >= '".$val['begin_date']."' and addtime <= '".$val['end_date']."' and `status` = 0 then money end ) as nobet".$key.",sum(case when addtime >= '".$val['begin_date']."' and addtime <= '".$val['end_date']."' and `status` in ('1','2') then win end ) as win".$key.",sum(case when addtime >= '".$val['begin_date']."' and addtime <= '".$val['end_date']."'  and `status` = 0 then win end ) as nowin".$key.",count(case when addtime >= '".$val['begin_date']."' and addtime <= '".$val['end_date']."' then id end) as num".$key.',';

            }
            $cstr .= 'uid';
          $this->db->from('c_bet');
          $this->db->where('uid',$uid);
          $this->db->where('site_id',SITEID);
          //$this->db->where_in('status','1,2');
          $this->db->select($cstr);
          $result = $this->db->get()->row_array();
          foreach($weeks as $k => $v){
                $result["date".$k] = $v['tmp_date'];
          }
          return $result;
    }


     public function _get_oneday_recored($uid,$date){
               //获取当前查询日期
            $begin_date = $date.' 00:00:00';
            $end_date = $date.' 23:59:59';
            $cstr = '';
            $cstr .= "sum(case when addtime >= '".$begin_date."' and addtime <= '".$end_date."' and `status` in ('1','2') then money end ) as bet".$key.",sum(case when addtime >= '".$begin_date."' and addtime <= '".$end_date."' and `status` = 0 then money end ) as nobet".$key.",sum(case when addtime >= '".$begin_date."' and addtime <= '".$end_date."' and `status` in ('1','2') then win end ) as win".$key.",sum(case when addtime >= '".$begin_date."' and addtime <= '".$end_date."'  and `status` = 0 then win end ) as nowin".$key.",count(case when addtime >= '".$begin_date."' and addtime <= '".$end_date."' then id end) as num".$key.',';
            $cstr .= 'uid,type';
          $this->db->from('c_bet');
          $this->db->where('uid',$uid);
          $this->db->where('site_id',SITEID);
          $this->db->group_by("type");
          $this->db->select($cstr);
          $result = $this->db->get()->result_array();
          return $result;
    }






}
?>