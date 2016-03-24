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

    //判断当前彩种是否关盘
    public  function ifopen($type,$indexid,$siteid){
    	$where = "index_id='".$indexid."' and site_id='".$siteid."' and fc_module LIKE '%".$type."%'";
    	$this->manage_db->where($where);
    	$result=$this->manage_db->get('site_info');
    	$rows = $result->num_rows();
    	return $rows;
    }

    // 获取当期开盘关盘时间
    public function _opentime_list($type, $map = array())
    {
        if (! empty($map)) {
            $this->public_db->where($map);
        }
        $query = $this->public_db->get($type . '_opentime');
        $rows = $query->result_array();
        //echo $this->public_db->last_query();exit;
        return $rows[0];
    }

    public function get_auto($map=array()){
        if (! empty($map['where'])) {
            $this->public_db->where($map['where']);
        }
        if (! empty($map['limit'])) {
            $this->public_db->limit($map['limit']);
        }
        if(!empty($map['order']))$this->public_db->order_by($map['order']);
        $query = $this->public_db->get($map['table']);
        $rows = $query->result_array();
        //echo $this->public_db->last_query();exit;
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
            'kaijiang>' => $now_time
        );
        $this->public_db->order_by('kaijiang', 'ASC');
        $data_time = $this->_opentime_list($type_y, $map);
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
            return func_BuLings($data_time['qishu']);
        } elseif ($type_y == 'fc_3d') {
            // 福彩3D每天一期
            // $data_time['qishu'] = func_BuLings(func_fc_qishu());
            // return $date_Y . func_BuLings($data_time['qishu']);

            return $date_Y . substr(strval(func_fc_qishu()+1000),1,3);
        } elseif ($type_y == 'pl_3') {
            // 排列3每天一期
            //$data_time['qishu'] = func_BuLings(func_fc_qishu());
            //return $date_y . func_BuLings($data_time['qishu']);
            return $date_y . substr(strval(func_fc_qishu()+1000),1,3);

        } elseif ($type_y == 'bj_8' || $type_y == 'xy_28' ) {
            // 北京快乐8
            return func_com_qishu('bj_8');
        } elseif ($type_y == 'bj_10') {
            // 北京PK10
            return func_com_qishu('bj_10');
        } elseif ($type_y == 'cq_ten') {
            // 重庆快乐10分
            return $date_ymd . func_BuLings($data_time['qishu']);
        } elseif ($type_y == 'gd_ten') {
            // 广东快乐十分
            return $date_Ymd . func_BuLing($data_time['qishu']);
        } else {
            return $date_Ymd . func_BuLings($data_time['qishu']);
        }
    }


    /**
     * 读取最近30次的开奖结果
     * @param [int] $count 数量
     * @param [array] $fc_type 彩票类别
     * @return [array]
    */
    public function _get_result($fc_type,$count=30,$date='',$tab=1){
        if($fc_type){
            $this->public_db->select("*");

            $this->public_db->order_by("qishu DESC");
            if($tab == 2 && $fc_type != 'liuhecai'){	//方便切换新旧表
            	$typeid = $this->zh_type($fc_type);
            	$query = $this->public_db->get('c_auto_'.$typeid,$count);
            }else{
            	$query = $this->public_db->get($fc_type.'_auto',$count);
            }

            $rows = $query->result_array();
            // echo $this->public_db->last_query();
            if($date){
                $date = func_nowtime('Y-m-d');
                foreach ($rows as $k => $v) {
                    if(!strstr($v['datetime'], $date)){
                        unset($rows[$k]);
                    }
                }
            }
            return $rows;
        }
        return false;
    }

    function zh_type($v){
    	switch ($v){
    		case 'gd_ten' : return 1;break;
    		case 'cq_ssc' : return 2;break;
    		case 'bj_10' : return 3;break;
    		case 'cq_ten' : return 4;break;
    		case 'fc_3d' : return 5;break;
    		case 'pl_3' : return 6;break;
    		case 'liuhecai' : return 7;break;
    		case 'bj_8' : return 8;break;
    		case 'tj_ssc' : return 10;break;
    		case 'jx_ssc' : return 11;break;
    		case 'xj_ssc' : return 12;break;
    		case 'js_k3' : return 13;break;
    		case 'jl_k3' : return 14;break;
    	}
    }

    //无出期数
    public function _get_miss($fc_type){
        if($fc_type == 'cq_ten' || $fc_type == 'gd_ten'){
            $num = 8;
            $res = $this->_get_result($fc_type,150,1);
            $qiu = array();
            if($res){
                $chuqiu = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0,14=>0,15=>0,16=>0,17=>0,18=>0,19=>0);
                for($i=1;$i<=20;$i++){
                    foreach($res as $k => $r){
                        if($r['ball_1'] != $i && $r['ball_2'] != $i && $r['ball_3'] != $i && $r['ball_4'] != $i && $r['ball_5'] != $i && $r['ball_6'] != $i && $r['ball_7'] != $i && $r['ball_8'] != $i){
                            $chuqiu[$i-1] = $chuqiu[$i-1] + 1;
                        }
                    }
                }
                return $chuqiu;
            }
        }else{
            return false;
        }
    }

    //出球率
    public function _get_chuqiu($fc_type){
        $num = 0;
        if($fc_type == 'cq_ten' || $fc_type == 'gd_ten'){
            $num = 8;
        }else{
            $num = 5;
        }
        if($fc_type && $num){
            $res = $this->_get_result($fc_type,150,1);
            //$chuqiu = array('a'=>0,'b'=>0,'c'=>0,'d'=>0,'e'=>0,'f'=>0,'g'=>0,'h'=>0,'i'=>0,'j'=>0);
            $qiu = array();

            if($res){
                for($i=1;$i<=$num;$i++){
                    if($num == 8){

                    $chuqiu = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0,13=>0,14=>0,15=>0,16=>0,17=>0,18=>0,19=>0);
                    foreach($res as $k => $r){

                        if($r['ball_'.$i] == 1){
                            $chuqiu[0] = $chuqiu[0] + 1;
                        }elseif($r['ball_'.$i] == 2){
                            $chuqiu[1] = $chuqiu[1] + 1;
                        }elseif($r['ball_'.$i] == 3){
                            $chuqiu[2] = $chuqiu[2] + 1;
                        }elseif($r['ball_'.$i] == 4){
                            $chuqiu[3] = $chuqiu[3] + 1;
                        }elseif($r['ball_'.$i] == 5){
                            $chuqiu[4] = $chuqiu[4] + 1;
                        }elseif($r['ball_'.$i] == 6){
                            $chuqiu[5] = $chuqiu[5] + 1;
                        }elseif($r['ball_'.$i] == 7){
                            $chuqiu[6] = $chuqiu[6] + 1;
                        }elseif($r['ball_'.$i] == 8){
                            $chuqiu[7] = $chuqiu[7] + 1;
                        }elseif($r['ball_'.$i] == 9){
                            $chuqiu[8] = $chuqiu[8] + 1;
                        }elseif($r['ball_'.$i] == 10){
                            $chuqiu[9] = $chuqiu[9] + 1;
                        }elseif($r['ball_'.$i] == 11){
                            $chuqiu[10] = $chuqiu[10] + 1;
                        }elseif($r['ball_'.$i] == 12){
                            $chuqiu[11] = $chuqiu[11] + 1;
                        }elseif($r['ball_'.$i] == 13){
                            $chuqiu[12] = $chuqiu[12] + 1;
                        }elseif($r['ball_'.$i] == 14){
                            $chuqiu[13] = $chuqiu[13] + 1;
                        }elseif($r['ball_'.$i] == 15){
                            $chuqiu[14] = $chuqiu[14] + 1;
                        }elseif($r['ball_'.$i] == 16){
                            $chuqiu[15] = $chuqiu[15] + 1;
                        }elseif($r['ball_'.$i] == 17){
                            $chuqiu[16] = $chuqiu[16] + 1;
                        }elseif($r['ball_'.$i] == 18){
                            $chuqiu[17] = $chuqiu[17] + 1;
                        }elseif($r['ball_'.$i] == 19){
                            $chuqiu[18] = $chuqiu[18] + 1;
                        }elseif($r['ball_'.$i] == 20){
                            $chuqiu[19] = $chuqiu[19] + 1;
                        }
                    }
                    $qiu['n'.$i] = $chuqiu;
                    }elseif ($num == 5) {
                         $chuqiu = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0);
                        foreach($res as $k => $r){

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
                    $qiu['n'.$i] = $chuqiu;
                    }
                }
            }
            // p($qiu);
            return $qiu;
        }
        return false;
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
            $this->private_db->where('uid', $uid);
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
    // public function _get_odds($map=array())
    // {
    //     $map['where']['pankou'] = $_SESSION['pankou'];
    //     $this->public_db->select($map['select']);
    //     $this->public_db->from('c_odds_'.SITEID);
    //     $this->public_db->join('cyj_private.fc_games_type', 'cyj_private.fc_games_type.id=c_odds_'.SITEID.'.type_id', 'left');
    //     $this->public_db->order_by("sort", "asc");
    //     if ($map['where']) {
    //         $this->public_db->where($map['where']);
    //     }
    //     $query = $this->public_db->get();
    //     $rows = $this->object_array($query->result());
    //     if (count($rows) == 0) {
    //         return FALSE;
    //     }
    //     return $rows;
    // }

    public function _get_mingxi_1($id){
        $map = array();
        $map['id'] = $id;
        $this->public_db->where($map);
        $query = $this->public_db->get('fc_games_type');
        $rows = $query->result_array();
        if($rows[0]['fc_type']){
            return $rows[0]['fc_type'];
        }else{
            return FALSE;
        }
    }

    public function get_lottery_name($lotteryId){
        $map = array();
        $map['type'] = $lotteryId;
        $this->public_db->where($map);
        $query = $this->public_db->get('fc_games');
        $rows = $query->result_array();
        if($rows[0]['name']){
            return $rows[0]['name'];
        }else{
            return FALSE;
        }
    }

    public function get_odd_check($map,$order = 'DESC'){
        $this->public_db->select('lottery_type,type2,input_name,odds_value,pankou');
        $this->public_db->where($map);
        $this->public_db->order_by('odds_value',$order);
        $query = $this->public_db->get('c_odds_'.SITEID);
        $rows = $query->result_array();
        // echo $this->public_db->last_query();exit;
        if($rows){
            return $rows;
        }else{
            return FALSE;
        }
    }

    public function Redis_get_odd($map,$data,$type,$order='DESC',$type_id = '',$is_arr=false){
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $odd = 0;
        if($type_id){
            $key = SITEID.'-'.$_SESSION['pankou'].'-'.$type.'-'.$data['mingxi_1'].'-'.$map['input_name'].'-'.$type_id;
        }else{
            if($is_arr){
                $key = SITEID.'-'.$_SESSION['pankou'].'-'.$type.'-'.$data['mingxi_1'].'-'.$map['input_name'];
                // p($data);
                // echo $key;echo "<br/>";
            }else{
                $key = SITEID.'-'.$_SESSION['pankou'].'-'.$type.'-'.$data['mingxi_1'].'-'.$data['mingxi_2'];
            }
        }

        $odd = $redis->get($key);
        $odd = '';  //零時使用
        if(empty($odd)){
            $odd_arr = $this->get_odd_check($map,$order);
            $odd = $odd_arr[0]['odds_value'];
            $redis->set($key,$odd);
        }
        return $odd;
    }

    public function Redis_get_odd_arr($map,$data,$type,$order='DESC'){
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $odd_arr = array();
        $key = SITEID.'-'.$_SESSION['pankou'].'-'.$type.'-'.$data['mingxi_1'].'-'.$data['mingxi_3'];
        $odd_arr = unserialize($redis->get($key));
        $odd_arr = '';  //零時使用
        if(empty($odd_arr)){
            $odd_arr = $this->get_odd_check($map,$order);
            $redis->set($key,serialize($odd_arr));
        }
        return $odd_arr;
    }


    //彩票赔率缓存redis   $okey = $type2.$input_name.$pankou.index_id
    //第一球0A  部分玩法 特殊处理 $fctype = 'fc_3d'
    public function get_odds_redis($fctype,$okey){
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $redis_key = SITEID.'_'.$fctype.'_odds';
        $redis->delete($redis_key); //清除redis里面的键值
        $odds_arr = $mdata  = $vdata = array();
        $vdata = $redis->hgetall($redis_key);
        //$vdata = '';   //临时使用
        if (empty($vdata)) {
            //redis中数据为空 从数据库读取
            $this->public_db->select("*");
            $this->public_db->where('site_id', SITEID);
			$this->public_db->where('index_id', INDEX_ID);
            $this->public_db->order_by('odds_value', 'DESC');
            $this->public_db->where('lottery_type', $fctype);
            $query = $this->public_db->get('c_odds_'.SITEID);
            $mdata = $query->result_array();
            if (empty($mdata)) { return false;}
            foreach ($mdata as $key => $val) {
                $odds_json = json_encode($val, JSON_UNESCAPED_UNICODE);
                $odds_json = str_replace('"', '--', $odds_json);
                if($val['type_id'] == 226){
                    $hkey = trim($val['type2']).trim($val['input_name']).trim($val['pankou']).trim($val['type_id']).trim($val['index_id']);
                }else{
                    $hkey = trim($val['type2']).trim($val['input_name']).trim($val['pankou']).trim($val['index_id']);
                }
//echo $hkey;echo '-1-';
                $hset[$hkey] = $odds_json;
                $odds_arr[$hkey] = $val;
            }
            $redis->hmset($redis_key,$hset);
        }else{
            $vdata = str_replace('--', '"', $vdata);
            foreach($vdata as $key=>$val){
                $tmp_arr = json_decode($val,true);//转数组
                if($tmp_arr['type_id'] == 226){
                    $tkey = trim($tmp_arr['type2']).trim($tmp_arr['input_name']).trim($tmp_arr['pankou']).trim($tmp_arr['type_id']).trim($tmp_arr['index_id']);
                }else{
                    $tkey = trim($tmp_arr['type2']).trim($tmp_arr['input_name']).trim($tmp_arr['pankou']).trim($tmp_arr['index_id']);
                }
//$redis->delete($tkey);
//echo $tkey;echo '--';exit;
                $odds_arr[$tkey] = $tmp_arr;
            }
        }
        return $odds_arr[$okey]['odds_value'];
    }

    //赔率验证
    public function check_odd($data,$type){

        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
		$index_id = INDEX_ID;
        $odd = 0;
        $pankou = $_SESSION['pankou'];
        $map = array();
        $map['pankou'] = $_SESSION['pankou'];
        $map['lottery_type'] = $type;
        $map['type2'] = $data['mingxi_1'];
        $map['input_name'] = $data['mingxi_2'];
        // p($data);
        if($type != 'liuhecai' &&  $type != 'bj_8'){
            if($type == 'cq_ten' || $type == 'gd_ten'){
                if($data['mingxi_1'] == '总和'){
                    $type2 = '總和,龍虎'.$data['mingxi_2'].$pankou.$index_id;
                    $odd = $this->get_odds_redis($type,$type2);
                }elseif($data['mingxi_1'] == '任选二' || $data['mingxi_1'] == '任选二组' || $data['mingxi_1'] == '任选三' || $data['mingxi_1'] == '任选四' || $data['mingxi_1'] == '任选五'){
                    $type2 = '连码'.$data['mingxi_1'].$pankou.$index_id;
                    $odd = $this->get_odds_redis($type,$type2);
                }else{
                    $type2 = $data['mingxi_1'].$data['mingxi_2'].$pankou.$index_id;
                    $odd = $this->get_odds_redis($type,$type2);
                }

            }else{
                $type2 = $data['mingxi_1'].$data['mingxi_2'].$pankou.$index_id;
                $odd = $this->get_odds_redis($type,$type2);
            }


        }

        if($type == 'bj_8'){
            if($data['mingxi_1'] == '选一' ||$data['mingxi_1'] == '选二' || $data['mingxi_1'] == '选三' || $data['mingxi_1'] == '选四' || $data['mingxi_1'] == '选五' ){
                if($data['mingxi_1'] == '选一' ){
                    $type2 = $data['mingxi_1'].'一中一'.$pankou.$index_id;
                    $odd = $this->get_odds_redis($type,$type2);
                }elseif ($data['mingxi_1'] == '选二') {
                    $type2 = $data['mingxi_1'].$data['mingxi_3'].$pankou.$index_id;
                    $odd = $this->get_odds_redis($type,$type2);
                }else{
                    $mingxi_3_arr = explode(',', $data['mingxi_3']);
                    $odd = 0;
                    foreach ($mingxi_3_arr as $k => $v) {
                        $arr_temp = explode(':', $v);
                        $type2 = $data['mingxi_1'].$arr_temp[0].$pankou.$index_id;
                        $odd = $this->get_odds_redis($type,$type2);
                        if($arr_temp[1] != $odd){
                            return -1;
                        }
                    }
                }
            }else{
                $type2 = $data['mingxi_1'].$data['mingxi_2'].$pankou.$index_id;
                $odd = $this->get_odds_redis($type,$type2);

            }
        }

        if($type == 'liuhecai'){
            if($data['mingxi_1'] == '正码' || $data['mingxi_1'] == '特码' || $data['mingxi_1'] == '半波' ){
                $type2 = $data['mingxi_1'].$data['mingxi_2'].$pankou.$index_id;

                $odd = $this->get_odds_redis($type,$type2);
            }elseif ($data['mingxi_1'] == '正码特' || $data['mingxi_1'] == '正码1-6' || $data['mingxi_1'] == '生肖' || $data['mingxi_1'] == '尾数' ||  $data['mingxi_1'] == '特码生肖'  ) {
                $type2 = $data['mingxi_3'].$data['mingxi_2'].$pankou.$index_id;
                $odd = $this->get_odds_redis($type,$type2);
            }elseif ($data['mingxi_1'] == '连码') {
                if($data['mingxi_3'] == '二全中' || $data['mingxi_3'] == '特串' || $data['mingxi_3'] == '三全中' || $data['mingxi_3'] == '四中一' || $data['mingxi_3'] == '四全中'){
                    $type2 = $data['mingxi_3'].$data['mingxi_3'].$pankou.$index_id;
                    $odd = $this->get_odds_redis($type,$type2);
                }else{
                    if(strstr($data['mingxi_3'], '中特')){
                        $map = '二中特';
                    }elseif(strstr($data['mingxi_3'], '中三')){
                        $map = '三中二';
                    }
                    $mingxi_3_arr = explode(';', $data['mingxi_3']);
                    $odd = 0;
                    foreach ($mingxi_3_arr as $k => $v) {
                        $arr_temp = explode(':', $v);
                        $type2 = $map.$arr_temp[0].$pankou.$index_id;
                        $odd = $this->get_odds_redis($type,$type2);
                        if($arr_temp[1] != $odd){
                            return -1;
                        }
                    }
                }
            }elseif($data['mingxi_1'] == '过关'){
                $mingxi_2_arr = explode(',', $data['mingxi_2']);
                $mingxi_3_arr = explode(',', $data['mingxi_3']);
                if(count($mingxi_2_arr) != count($mingxi_3_arr)){
                    return -1;
                }
                $odd = 0;
                foreach ($mingxi_2_arr as $k1 => $v1) {
                    $type2 = $mingxi_3_arr[$k1].$v1.$pankou.'226'.$index_id;
                    $odd_1 = $this->get_odds_redis($type,$type2);
                    if(empty($odd)){
                        $odd = $odd_1;
                    }else{
                        $odd = $odd*$odd_1;
                    }
                }
                $odd = number_format($odd, 2, '.', '');
            }elseif ($data['mingxi_1'] == '合肖' || $data['mingxi_1'] == '全不中') {
                // p($data);
                $mingxi_2_arr = explode(',', $data['mingxi_2']);
                if($data['mingxi_3'] == '十一不中' || $data['mingxi_3'] == '十二不中'){
                    $str = str_replace('不中', '', $data['mingxi_3']);
                }elseif ($data['mingxi_3'] == '十一肖') {
                    $str = str_replace('肖', '', $data['mingxi_3']);
                }else{
                    $str = substr($data['mingxi_3'], 0,3);
                }

                if(num2char(count($mingxi_2_arr)) !=  $str){
                    return -1;
                }
                $type2 = $data['mingxi_3'].$mingxi_2_arr[0].$pankou.$index_id;
                $odd = $this->get_odds_redis($type,$type2);
            }elseif ($data['mingxi_1'] == '生肖连' || $data['mingxi_1'] == '尾数连') {
                $mingxi_2_arr = explode(',', $data['mingxi_2']);
                $odd = 0;
                foreach ($mingxi_2_arr as $k => $v) {
                    $type2 = $data['mingxi_3'].$v.$pankou.$index_id;
                    $odd_1 = $this->get_odds_redis($type,$type2);
                    if(empty($odd)){
                        $odd = $odd_1;
                    }elseif($odd_1 < $odd){
                        $odd = $odd_1;
                    }
                    // echo $odd;
                }

            }
        }
        // p($data);
        $odd = $odd?$odd:0;
        return $odd;
    }


    //下注验证
    public function check_form($data){
        $xiao_arr = array('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪');
        $num_arr = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49);
        $wei_arr = array(0,1,2,3,4,5,6,7,8,9);
        // p($data);
        $type = $data['lotteryId'];
        foreach ($data['betParameters'] as $k => $v) {
            if(empty($v['Money'])){
                return 1;
            }
            if(!is_numeric(floatval($v['Money'])) || floatval($v['Money']) <= 0 ){
                return 1;
            }
            if(($v['mingxi_1'] == 232 || $v['mingxi_1'] == 233 || $v['mingxi_1'] == 234) && empty($v['Lines'])  ){

            }else{
                if(empty($v['Lines'])){
                    return 1;
                }
            }
            //组合数判断
            if(isset($v['min'])){
                if($type == 'bj_8'){
                    if(($v['gname'] == '选一' && $v['min'] != 1) || ($v['gname'] == '选二' && $v['min'] != 2) || ($v['gname'] == '选三' && $v['min'] != 3) || ($v['gname'] == '选四' && $v['min'] != 4) || ($v['gname'] == '选五' && $v['min'] != 5)){
                        return 1;
                    }
                }elseif($type == 'gd_ten' || $type == 'cq_ten'){
                    if(($v['gname'] == '任选二' && $v['min'] != 2) || ($v['gname'] == '任选二组' && $v['min'] != 2) || ($v['gname'] == '任选三' && $v['min'] != 3) || ($v['gname'] == '任选四' && $v['min'] != 4) || ($v['gname'] == '任选五' && $v['min'] != 5)){
                        return 1;
                    }
                }elseif($type == 'liuhecai'){
                    if(($v['gname'] == '二全中' && $v['min'] != 2) || ($v['gname'] == '二中特' && $v['min'] != 2) || ($v['gname'] == '特串' && $v['min'] != 2) || ($v['gname'] == '三全中' && $v['min'] != 3) || ($v['gname'] == '三中二' && $v['min'] != 3) || ($v['gname'] == '四中一' && $v['min'] != 4)|| ($v['gname'] == '四全中' && $v['min'] != 4)|| ($v['gname'] == '二肖连中' && $v['min'] != 2)|| ($v['gname'] == '三肖连中' && $v['min'] != 3)|| ($v['gname'] == '四肖连中' && $v['min'] != 4)|| ($v['gname'] == '五肖连中' && $v['min'] != 5)|| ($v['gname'] == '二肖连不中' && $v['min'] != 2)|| ($v['gname'] == '三肖连不中' && $v['min'] != 3)|| ($v['gname'] == '四肖连不中' && $v['min'] != 4)|| ($v['gname'] == '二尾连中' && $v['min'] != 2)|| ($v['gname'] == '三尾连中' && $v['min'] != 3)|| ($v['gname'] == '四尾连中' && $v['min'] != 4)|| ($v['gname'] == '二尾连不中' && $v['min'] != 2)|| ($v['gname'] == '三尾连不中' && $v['min'] != 3)|| ($v['gname'] == '四尾连不中' && $v['min'] != 4)|| ($v['gname'] == '五不中' && $v['min'] != 5)|| ($v['gname'] == '六不中' && $v['min'] != 6)|| ($v['gname'] == '七不中' && $v['min'] != 7)|| ($v['gname'] == '八不中' && $v['min'] != 8)|| ($v['gname'] == '九不中' && $v['min'] != 9)|| ($v['gname'] == '十不中' && $v['min'] != 10)|| ($v['gname'] == '十一不中' && $v['min'] != 11)|| ($v['gname'] == '十二不中' && $v['min'] != 12)){
                        return 1;
                    }
                }

            }
            //组合重复性判断
            if(isset($v['min'])){
                if($v['mingxi_1'] == 226){//过关
                    $bet_arr = explode(',', $v['BetContext']);
                    if(count($bet_arr) < 2){
                        return 1;
                    }
                    $bet_arr = explode(',', $v['gname']);
                    if(count($bet_arr) < 2){
                        return 1;
                    }
                }elseif($v['mingxi_1'] == 232 || $v['mingxi_1'] == 233 || $v['mingxi_1'] == 234){
                    $true_arr = array();
                    switch ($v['mingxi_1']) {
                        case 232:
                            $true_arr = $xiao_arr;
                            break;
                        case 233:
                            $true_arr = $wei_arr;
                            break;
                        case 234:
                            $true_arr = $num_arr;
                            break;
                    }
                    $bet_arr = explode(',', $v['BetContext']);
                    $temp_arr = array();
                    foreach ($bet_arr as $kk => $vv) {
                        $bet_arr_1 = explode('@', $vv);
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
     * @return [json]
     */
    public function _addlottery_bet($data)
    {

        // p($data); exit;
        $fs = 0.01;
        $now_qishu = $this->_dq_qishu($data['lotteryId']);
        $lotteryname = $this->get_lottery_name($data['lotteryId']);
        if(!empty($data['betParameters'][0]['min']) &&  strstr($data['betParameters'][0]['BetContext'],'&') && !empty($data['betParameters'][0]['mingxi_1']) && $data['betParameters'][0]['mingxi_1'] != '227'){//不中多组投注 == '234'
            $shuzu_temp = explode('&', $data['betParameters'][0]['BetContext']);
            $arr_temp = array();
            foreach ($shuzu_temp as $key => $value) {
                unset($data['betParameters'][0]['min']);
                $shuzu_1 = explode('@', $value);
                $data['betParameters'][0]['Lines'] = $shuzu_1[1];
                $data['betParameters'][0]['BetContext'] = $shuzu_1[0];
                $arr_temp[] =$data['betParameters'][0];
            }
            $data['betParameters'] = $arr_temp;
        }elseif(!empty($data['betParameters'][0]['min']) &&  strstr($data['betParameters'][0]['BetContext'],'&')){
            //六合彩连码
            $shuzu_temp = explode('&', $data['betParameters'][0]['BetContext']);
            $shuzu_1 = explode(',', $shuzu_temp[0]);
            $shuzu_2 = explode(',', $shuzu_temp[1]);
            $arr_temp = array();
            if($data['betParameters'][0]['min'] ==2){
                //对碰
                foreach ($shuzu_1 as $k => $v) {
                    foreach ($shuzu_2 as $k1 => $v1) {
                        $data['betParameters'][0]['BetContext'] = $v.','.$v1;
                        $arr_temp[] =$data['betParameters'][0];
                    }
                }
                $data['betParameters'] = $arr_temp;
            }elseif($data['betParameters'][0]['min'] ==3 || $data['betParameters'][0]['min'] ==4){
                $mm = 2;
                //拖胆
                if(count($shuzu_2) >= floatval($data['betParameters'][0]['min']-$mm)){
                    $arr_t = func_get_zuhe($shuzu_2,floatval($data['betParameters'][0]['min']-$mm));
                    foreach ($arr_t as $k => $v) {
                        $data['betParameters'][0]['BetContext'] = $shuzu_temp[0].','.$v;
                        $arr_temp[] =$data['betParameters'][0];
                    }
                    $data['betParameters'] = $arr_temp;
                }else{
                    $data['betParameters'][0]['BetContext'] = implode(',', $shuzu_1).','.implode(',', $shuzu_2);
                }
            }

        }elseif(!empty($data['betParameters'][0]['min'])){
            $zuhe_arr = func_get_zuhe(explode(',', $data['betParameters'][0]['BetContext']) ,$data['betParameters'][0]['min']);
            $arr_temp = array();
            foreach ($zuhe_arr as $k => $v) {
                $data['betParameters'][0]['BetContext'] = $v;
                $arr_temp[] =$data['betParameters'][0];
            }
            $data['betParameters'] = $arr_temp;
        }
// p($data);
        $error = $this->check_form($data);
        if($error == 1){

            $data=array("result"=>5,"msg"=>"参数错误","errId"=>1026);
            return json_encode($data);exit;
        }
// exit;


        if($data['lotteryId'] == 'liuhecai'){
            $mingxi_1 = trim($this->_get_mingxi_1($data['betParameters'][0]['mingxi_1']));
        }else{
            $mingxi_1 = $data['betParameters'][0]['gname'];
        }

        $bet_limit = array();
        //限红
        if(empty($bet_limit)){

            $bet_limit = $this->_get_ball_limit($data['lotteryId'],$mingxi_1);
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

        $uid=$_SESSION['uid'];
        $user = $this->_get_userinfo($uid);
        $this->private_db->trans_begin();
        $all_money = 0;
        foreach ($data['betParameters'] as $k => $v) {
              //金额是否数字判断
            if ($v['Money'] <= 0 || !is_numeric($v['Money'])) {
                $data = array("result"=>5,"msg"=>"下注金额错误","errId"=>1026);
                return json_encode($data);
            }

            $all_money+=floatval($v['Money']);
        }
        if($all_money > $bet_limit['single_field_max']){
            $data=array("result"=>5,"msg"=>"下注金额超出限制","errId"=>1026);
            return json_encode($data);exit;
        }
        if(floatval($all_money) > floatval($user['money'])){
            $data=array("result"=>5,"msg"=>"余额不足","errId"=>1026);
            return json_encode($data);exit;
        }



        foreach ($data['betParameters'] as $k => $v) {

            if(floatval($v['Money']) < $bet_limit['single_note_min'] || floatval($v['Money']) > $bet_limit['single_note_max']){
                $data=array("result"=>5,"msg"=>"下注金额超出限制","errId"=>1026);
                return json_encode($data);exit;
            }
            $this->private_db->where('uid='.$user['uid'].' and money > 0 and money >= "'.$v['Money'].'"');
            $this->private_db->set('money','money-'.$v['Money'],FALSE);
            $update = $this->private_db->update('k_user');

            if(!$this->private_db->affected_rows()){
	            $this->private_db->trans_rollback();
	            $data=array("result"=>5,"msg"=>"下注失败","errId"=>1026);
                return json_encode($data);
                exit();
	        }

            $cbetordernum = func_getdid();
            $userinfo = $this->_get_userinfo($user['uid']);
            $c_betdata['did'] = $cbetordernum;
            $c_betdata['uid'] = $user['uid'];
            $c_betdata['agent_id'] = $user['agent_id'];
            $c_betdata['ua_id'] = $_SESSION['ua_id'];
            $c_betdata['sh_id'] = $_SESSION['sh_id'];
            $c_betdata['is_shiwan'] = $_SESSION['shiwan'];//判断是否试玩账号
            $c_betdata['username'] = $user['username'];
            $c_betdata['addtime'] = func_nowtime('Y-m-d H:i:s','now');
            $c_betdata['type'] =$lotteryname;
            $c_betdata['qishu']=$now_qishu;
            if($data['lotteryId'] == 'liuhecai'){
                $c_betdata['mingxi_1'] = trim($this->_get_mingxi_1($data['betParameters'][0]['mingxi_1']));
                $c_betdata['mingxi_3'] = trim($v['gname']);

                     //一肖尾数特殊转换
                if ($c_betdata['mingxi_3'] == '一肖') {
                    $c_betdata['mingxi_1'] = '生肖';

                }elseif($c_betdata['mingxi_3'] == '尾数'){
                    $c_betdata['mingxi_1'] = '尾数';

                }
            }else{
                $c_betdata['mingxi_1'] = $v['gname'];
            }


            if($v['gname'] == '选二' ||$v['gname'] == '选三' || $v['gname'] == '选四' || $v['gname'] == '选五'){
                $c_betdata['mingxi_3'] = $v['Lines'];
                $ming3_arr = explode(':', $v['Lines']);
                $c_betdata['odds'] = trim($ming3_arr[(count($ming3_arr)-1)]);
            }elseif($v['gname'] == '二中特'){
                $ming3_arr = explode('/', $v['Lines']);
                $c_betdata['mingxi_3'] = '中特:'.$ming3_arr[0].';中二:'.$ming3_arr[1];
                $c_betdata['odds'] = trim($ming3_arr[(count($ming3_arr)-1)]);
            }elseif($v['gname'] == '三中二'){
                $ming3_arr = explode('/', $v['Lines']);
                $c_betdata['mingxi_3'] = '中二:'.$ming3_arr[0].';中三:'.$ming3_arr[1];
                $c_betdata['odds'] = trim($ming3_arr[(count($ming3_arr)-1)]);
            }else{
                $c_betdata['odds'] = $v['Lines'];
            }

            if($v['mingxi_1'] == 232 || $v['mingxi_1'] == 233 || ($v['mingxi_1'] == 234 && !empty($data['betParameters'][0]['min']))){
// p($data);exit;
                $h_str = trim($v['BetContext']);//兔@4.35,羊@3.8
                $h_str_arr = explode(',', $h_str);
                $temp_arr = $mingxi_2_arr = $odd_arr_h = array();
                foreach ($h_str_arr as $k => $vv1) {
                    $temp_arr = explode('@', $vv1);
                    $mingxi_2_arr[] = $temp_arr[0];
                    $odd_arr_h[] = $temp_arr[1];
                }
                sort($odd_arr_h);
                $c_betdata['odds'] = $odd_arr_h[0];
                $c_betdata['mingxi_2'] = implode(',', $mingxi_2_arr);
            }else{
                $c_betdata['mingxi_2'] = trim($v['BetContext']);

                //尾数处理
                if ($c_betdata['mingxi_3'] == '尾数' && $data['lotteryId'] == 'liuhecai') {
                     $c_betdata['mingxi_2'] = str_replace('尾','',$c_betdata['mingxi_2']);
                }


            }


            $c_betdata['money'] = $v['Money'];
            $c_betdata['assets'] = $user['money'];//下注之前的余额
            $c_betdata['balance'] = $userinfo['money'];
            $c_betdata['fs'] = 0;
            $c_betdata['index_id'] = $userinfo['index_id'];
            $c_betdata['site_id'] = $userinfo['site_id'];
            $c_betdata['pankou'] = $_SESSION['pankou'];

            $c_betdata['mingxi_1'] = trim($c_betdata['mingxi_1']);
            $c_betdata['mingxi_2'] = trim($c_betdata['mingxi_2']);
            $c_betdata['mingxi_3'] = trim($c_betdata['mingxi_3']);

            if($c_betdata['type'] == '北京快乐8'){
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

            $odd_true = $this->check_odd($c_betdata,$data['lotteryId']);
            if(floatval($odd_true) <= 0){
                $data=array("result"=>5,"msg"=>"赔率不正确","errId"=>1026);
                return json_encode($data);exit;
            }else{
                $c_betdata['odds'] = $odd_true;
            }
            $c_betdata['win'] = $c_betdata['money']*$c_betdata['odds'];
            if($c_betdata['type'] == '重庆快乐十分' || $c_betdata['type'] == '广东快乐十分' ){
                if($c_betdata['mingxi_2'] == '合单'){
                    $c_betdata['mingxi_2'] = '合数单';
                }elseif($c_betdata['mingxi_2'] == '合双'){
                    $c_betdata['mingxi_2'] = '合数双';
                }
            }
            if($c_betdata['type'] == '重庆时时彩' || $c_betdata['type'] == '天津时时彩'|| $c_betdata['type'] == '新疆时时彩' || $c_betdata['type'] == '江西时时彩'  ){

                if($c_betdata['mingxi_1'] == '前三球' || $c_betdata['mingxi_1'] == '中三球' || $c_betdata['mingxi_1'] == '后三球'){
                    $c_betdata['mingxi_1'] = str_replace('球','',$c_betdata['mingxi_1']);
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

            //特码生肖处理
            if ($c_betdata['mingxi_3'] == '特肖' && $data['lotteryId'] == 'liuhecai' && $c_betdata['mingxi_1'] == '特码生肖') {
                $c_betdata['mingxi_1'] = '生肖';
            }
            //特码生肖处理
            if ($c_betdata['mingxi_1'] == '合肖' && $data['lotteryId'] == 'liuhecai') {
                $c_betdata['mingxi_1'] = '生肖';
            }

            //正码1-6处理
            if ($data['lotteryId'] == 'liuhecai' ) {
                if ($c_betdata['mingxi_1'] == '正码1-6') {
                    $c_betdata['mingxi_1'] = '正1-6';
                }elseif($c_betdata['mingxi_1'] == '正码特'){
                    $c_betdata['mingxi_1'] = '正特';
                }
            }
            // p($c_betdata);exit;
            if($_SESSION['pankou'] == 'B' && $c_betdata['mingxi_1'] == '特码'){//反水
                $fs_re =  $this->get_fanshui();
                $fs_re = $fs_re?$fs_re:0;
                $c_betdata['fs'] = $c_betdata['money'] * $fs * $fs_re;
            }

            $this->private_db->insert('c_bet', $c_betdata);
            $c_betdata = array();
            $source_id=$this->private_db->insert_id();

            $remark = "彩票注单：" . $cbetordernum . " , 類型:" . $lotteryname;
            $record['source_id'] = $source_id;
            $record['index_id'] = $userinfo['index_id'];
            $record['source_type'] = 7;//彩票下注类型
            $record['site_id'] = $userinfo['site_id'];
            $record['uid'] = $user['uid'];
            $record['agent_id'] = $user['agent_id'];
            $record['username'] = $user['username'];
            $record['is_shiwan'] = $_SESSION['shiwan'];//判断是否试玩账号
            $record['cash_type'] = 3;
            $record['cash_do_type'] = 2;
            $record['cash_num'] =$v['Money'];
            $record['cash_balance'] = $userinfo['money'];
            $record['cash_date'] = func_nowtime('Y-m-d H:i:s','now');
            $record['remark'] =$remark;

            $rcash=$this->private_db->insert('k_user_cash_record', $record);
            if(empty($update) || empty($source_id) || empty($rcash) ){
                  $this->private_db->trans_rollback();
                $data=array("result"=>5,"msg"=>"由于网络堵塞，本次下注失败。","errId"=>1026);
                return json_encode($data);
            }

        }
            $this->private_db->trans_commit();
            $data=array("result"=>1,"msg"=>"");
            return json_encode($data);
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
     *  查询当天玩法已下注的金额
     * @param [array] $fc_type 彩票类别
     * @param [array] $wanfa_id 彩票玩法id
     * @return [double]
     */
    public function _beted_limit_1($fc_type)
    {
        $date = date("Y-m-d").' 00:00:00';
        $where = 'type = "'.eng2chn($fc_type).'" and username = "'.$_SESSION['username'].'" and addtime > "'.$date.'" and js = 0';
		if(empty($_SESSION['username'])){return "0.00";}
        $this->private_db->where($where);
        $this->private_db->select("sum(money)");
        $query = $this->private_db->get('c_bet');
        $data = $query->result_array();
        $money = $data[0]["sum(money)"] != "" ? $data[0]["sum(money)"] : 0;
        // echo $this->private_db->last_query();exit;
        return $money;
    }



    /**
     *  查询当期玩法已下注的金额
     * @param [array] $fc_type 彩票类别
     * @param [array] $wanfa_id 彩票玩法id
     * @return [double]
     */
    public function _beted_limit($fc_type, $wanfa_id='')
    {
        $qishu = $this->_dq_qishu($fc_type);
        $map = array();
        $map['type'] = eng2chn($fc_type);
        $map['username'] = $_SESSION['username'];
        $map['qishu'] = $qishu;

        if(!empty($wanfa_id)){
            $wanfa_name = $this->_get_wanfa_name($wanfa_id);
            $map['mingxi_1'] = $wanfa_name;
        }
		if(empty($_SESSION['username'])){return "0.00";}
        $this->private_db->where($map);
        $this->private_db->select("sum(money)");
        $query = $this->private_db->get('c_bet');
        $data = $query->result_array();
        $money = $data[0]["sum(money)"] != "" ? $data[0]["sum(money)"] : 0;
        // echo $this->private_db->last_query();exit;
        return $money;
    }

    public function _get_head(){
        $this->public_db->from('fc_games');
        $this->public_db->where('state = 1');
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

        $this->private_db->from('fc_games');
        $this->private_db->where("state=1");
        $this->private_db->order_by("id", "asc");
        $query = $this->private_db->get();
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
    // public function _get_fc_games_type($map=array()){

    //     $this->private_db->from('fc_games_type');
    //     $this->private_db->where($map['where']);
    //     $this->private_db->order_by("id", "asc");
    //     $query = $this->private_db->get();
    //     $rows = $this->object_array($query->result());
    //     if (count($rows) == 0) {
    //         return FALSE;
    //     }
    //     foreach ($rows as $k => $v) {
    //         $wanfa_close = $this->lottery->_get_close_arr('',2,$v['id']);

    //         if($wanfa_close[0]['value'] != 1){
    //             $data[] = $v;
    //         }

    //     }
    //     return $data;


    // }

    /**
     *  查询所有玩法的模板名
     * @param [int] $type_id 彩票类别id
     * @return [string]
     */
    // public function _get_tep_type($type_id){
    //     $where = array('id'=>$type_id);
    //     $select = 'tep_type';
    //     $this->private_db->from('fc_games_type');
    //     $this->private_db->select($select);
    //     $this->private_db->where($where);
    //     $this->private_db->order_by("id", "asc");
    //     $query = $this->private_db->get();
    //     $rows = $this->object_array($query->result());
    //     if (count($rows) == 0) {
    //         return FALSE;
    //     }
    //     return $rows[0]['tep_type'];
    // }
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
        //echo $this->public_db->last_query();exit;
        $data = $query->result_array();
        $data_time = $data[0];
//p($now_time);exit;
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


            // echo $now_time,'<br/>';

            // echo $k_t,'<br/>';
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
            //p($array);exit;
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
     *  查询该用户的玩法限制金额
     * @param [int] $type 彩票类别
     * @param [string] $wanfa 玩法
     * @return [int]
     */
    public function _get_ball_limit($type,$wanfa){
        $fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten','gd_ten','bj_8',
            'bj_10','tj_ssc','xj_ssc','jx_ssc','jl_k3','js_k3',
            'liuhecai');

        $Fcgame = 'fc_games_view';
        $Uagent = 'k_user_agent_fc_set';
        $Uaset = 'k_user_fc_set';
        foreach ($fcArr as $key => $val) {
            $tmpA = $tmpB=array();
            /* if(!empty($_GET["aid"])){
                $aid = $_GET["aid"];
            }else{$aid='';}
            if(!empty($_GET["uid"])){
                $uid = $_GET["uid"];
            }else{$uid='';} */
			$aid = $_SESSION['agent_id'];
			$uid = $_SESSION['uid'];
            $where = array( $Fcgame.'.fc_type ' => $val , $Uagent . '.aid' => $aid,$Uagent . '.site_id' => SITEID);
            $this->private_db->select('*');
            $this->private_db->from($Fcgame);
            $this->private_db->where($where);
            $this->private_db->join($Uagent, $Uagent . '.type_id = ' . $Fcgame . '.id');
            $query = $this->private_db->get();
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
// p($wanfa);exit;
        if(!empty($sets)){
            foreach ($sets as $k => $v) {
                if($v['name'] == $wanfa){
                    $arr['single_field_max'] = $v['single_field_max'];
                    $arr['single_note_max'] = $v['single_note_max'];
                    $arr['single_note_min'] = $v['min'];
                }
            }
        }
        return $arr;
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
            $this->public_db->select('c_odds_'.SITEID.'.id,input_name,odds_value,type_id,count_arr,fc_games_type.fc_type');
            $map['c_odds_'.SITEID.'.pankou']  = $_SESSION['pankou'];
            $map['c_odds_'.SITEID.'.lottery_type'] = $type;
			$map['c_odds_'.SITEID.'.site_id'] = SITEID;
			$map['c_odds_'.SITEID.'.index_id'] = INDEX_ID;
            $this->public_db->where($map);
            $this->public_db->join('fc_games_type', 'fc_games_type.id=c_odds_'.SITEID.'.type_id', 'left');
            $this->public_db->order_by('c_odds_'.SITEID.'.type_id,c_odds_'.SITEID.'.sort', "asc");

            $query = $this->public_db->get('c_odds_'.SITEID);
            //echo $this->public_db->last_query();
            $rows = $query->result_array();
            return $rows;
        }
    }



    //查询所有的单个彩种赔率
    public function get_odds_liuhecai($type,$gameid,$gamename2){

        if (! empty($type)) {
            $this->public_db->select('c_odds_'.SITEID.'.id,input_name,odds_value,type_id,count_arr,fc_games_type.fc_type,type2');
            $map['c_odds_'.SITEID.'.pankou']  =  $_SESSION['pankou'];
            $map['c_odds_'.SITEID.'.lottery_type'] = $type;
            $map['c_odds_'.SITEID.'.type_id'] = $gameid;
            // $map['c_odds_'.SITEID.'.odds_value'] = '> 0';
            if(!empty($gamename2)&&$gameid!=225&&$gameid!=226){
                $map['c_odds_'.SITEID.'.type2'] = $gamename2;
            }
			$map['c_odds_'.SITEID.'.site_id'] = SITEID;
			$map['c_odds_'.SITEID.'.index_id'] = INDEX_ID;
            $this->public_db->where($map);
            $this->public_db->join('fc_games_type', 'fc_games_type.id=c_odds_'.SITEID.'.type_id', 'left');
            $this->public_db->order_by('c_odds_'.SITEID.'.type_id,c_odds_'.SITEID.'.sort', "asc");

            $query = $this->public_db->get('c_odds_'.SITEID);
            // echo $this->public_db->last_query();
            $rows = $query->result_array();
            return $rows;
        }
    }

    public function _get_luzhu($type){
        $result = $this->_get_result($type,30,1);
        if($type == 'cq_ssc' || $type == 'jx_ssc' || $type == 'tj_ssc' || $type == 'xj_ssc'){
            for($i=1;$i<=5;$i++){
                $big_arr ="big_arr_".$i;
                $dan_arr ="dan_arr_".$i;
                $haoma_arr ="haoma_arr_".$i;
                $$big_arr = func_zoushi($result,$i,'大小',$type);
                $$dan_arr = func_zoushi($result,$i,'单双',$type);
                $$haoma_arr = func_zoushi($result,$i,'号码',$type);
            }
            $hedaxiao = func_zoushi($result,0,'总和大小',$type);
            $hedanshuang = func_zoushi($result,0,'总和单双',$type);
            $longhu = func_zoushi($result,0,'龙虎',$type);
            return array($haoma_arr_1,$haoma_arr_2,$haoma_arr_3,$haoma_arr_4,$haoma_arr_5,$hedaxiao,$hedanshuang,$big_arr_1,$dan_arr_1,$big_arr_2,$dan_arr_2,$big_arr_3,$dan_arr_3,$big_arr_4,$dan_arr_4,$big_arr_5,$dan_arr_5,$longhu);
        }elseif($type == 'cq_ten' || $type == 'gd_ten'){
             $re_arr = array();
            for($i=1;$i<=8;$i++){
                $big_arr ="big_arr_".$i;
                $dan_arr ="dan_arr_".$i;
                $heshu_dan_arr ="heshu_dan_arr_".$i;
                $weida_arr ="weida_arr_".$i;
                $fangwei_arr ="fangwei_arr_".$i;
                $zhongfabai_arr ="zhongfabai_arr_".$i;

                $$big_arr = func_zoushi($result,$i,'大小',$type);
                $$dan_arr = func_zoushi($result,$i,'单双',$type);
                $$heshu_dan_arr = func_zoushi($result,$i,'合数单双',$type);
                $$weida_arr = func_zoushi($result,$i,'尾大小',$type);
                $$fangwei_arr = func_zoushi($result,$i,'方位',$type);
                $$zhongfabai_arr = func_zoushi($result,$i,'中发白',$type);

                $re_arr[] = $$big_arr;
                $re_arr[] = $$dan_arr;
                $re_arr[] = $$heshu_dan_arr;
                $re_arr[] = $$weida_arr;
                $re_arr[] = $$fangwei_arr;
                $re_arr[] = $$zhongfabai_arr;


                if($i<=4){
                    $longhu_arr ="longhu_arr_arr_".$i;
                    $$longhu_arr = func_zoushi($result,$i,'龙虎',$type);
                    $re_arr[] = $$longhu_arr;
                }
            }
            $zonghe_da_arr = func_zoushi($result,0,'总和大小',$type);
            $zonghe_dan_arr = func_zoushi($result,0,'总和单双',$type);
            $zonghe_weida_arr = func_zoushi($result,0,'总和尾大小',$type);
            $re_arr[] = $zonghe_da_arr;
            $re_arr[] = $zonghe_dan_arr;
            $re_arr[] = $zonghe_weida_arr;

            $arr =  array_filter($re_arr);
            return $arr;
        }elseif($type == 'bj_10'){
            $re_arr = array();
            $zonghe_da_arr = func_zoushi($result,0,'冠亚和',$type);
            $zonghe_dan_arr = func_zoushi($result,0,'冠亚和大小',$type);
            $zonghe_weida_arr = func_zoushi($result,0,'冠亚和单双',$type);
            $re_arr[] = $zonghe_da_arr;
            $re_arr[] = $zonghe_dan_arr;
            $re_arr[] = $zonghe_weida_arr;
            $arr =  array_filter($re_arr);
            return $arr;
        }
    }

    public function _get_liangmian($type){
        $result = $this->_get_result($type,30,1);
        if($type == 'cq_ssc' || $type == 'jx_ssc' || $type == 'tj_ssc' || $type == 'xj_ssc'){
            for($i=1;$i<=5;$i++){
                $big_arr ="big_arr_".$i;
                $dan_arr ="dan_arr_".$i;
                $$big_arr = func_zoushi($result,$i,'大小',$type,true);
                $$dan_arr = func_zoushi($result,$i,'单双',$type,true);

            }
            $hedaxiao = func_zoushi($result,0,'总和大小',$type,true);
            $hedanshuang = func_zoushi($result,0,'总和单双',$type,true);
            $longhu = func_zoushi($result,0,'龙虎',$type,true);
            $arr =  array_filter(array($hedaxiao,$hedanshuang,$big_arr_1,$dan_arr_1,$big_arr_2,$dan_arr_2,$big_arr_3,$dan_arr_3,$big_arr_4,$dan_arr_4,$big_arr_5,$dan_arr_5,$longhu));
        }elseif($type == 'gd_ten' || $type == 'cq_ten'){
            $re_arr = array();
            for($i=1;$i<=8;$i++){
                $big_arr ="big_arr_".$i;
                $dan_arr ="dan_arr_".$i;
                $heshu_dan_arr ="heshu_dan_arr_".$i;
                $weida_arr ="weida_arr_".$i;
                $fangwei_arr ="fangwei_arr_".$i;
                $zhongfabai_arr ="zhongfabai_arr_".$i;

                $$big_arr = func_zoushi($result,$i,'大小',$type,true);
                $$dan_arr = func_zoushi($result,$i,'单双',$type,true);
                $$heshu_dan_arr = func_zoushi($result,$i,'合数单双',$type,true);
                $$weida_arr = func_zoushi($result,$i,'尾大小',$type,true);
                $$fangwei_arr = func_zoushi($result,$i,'方位',$type,true);
                $$zhongfabai_arr = func_zoushi($result,$i,'中发白',$type,true);

                $re_arr[] = $$big_arr;
                $re_arr[] = $$dan_arr;
                $re_arr[] = $$heshu_dan_arr;
                $re_arr[] = $$weida_arr;
                $re_arr[] = $$fangwei_arr;
                $re_arr[] = $$zhongfabai_arr;


                if($i<=4){
                    $longhu_arr ="longhu_arr_".$i;
                    $$longhu_arr = func_zoushi($result,$i,'龙虎',$type,true);
                    $re_arr[] = $$longhu_arr;
                }
            }
            $zonghe_da_arr = func_zoushi($result,0,'总和大小',$type,true);
            $zonghe_dan_arr = func_zoushi($result,0,'总和单双',$type,true);
            $zonghe_weida_arr = func_zoushi($result,0,'总和尾大小',$type,true);
            $re_arr[] = $zonghe_da_arr;
            $re_arr[] = $zonghe_dan_arr;
            $re_arr[] = $zonghe_weida_arr;

            $arr =  array_filter($re_arr);
        }elseif($type == 'bj_10'){
            for($i=1;$i<=10;$i++){
                $big_arr ="big_arr_".$i;
                $dan_arr ="dan_arr_".$i;
                $$big_arr =  func_zoushi($result,$i,'大小',$type,true);
                $$dan_arr = func_zoushi($result,$i,'单双',$type,true);
                $re_arr[] = $$big_arr;
                $re_arr[] = $$dan_arr;

                if($i<=5){
                    $longhu_arr ="longhu_arr_".$i;
                    $$longhu_arr = func_zoushi($result,$i,'龙虎',$type,true);
                    $re_arr[] = $$longhu_arr;
                }
            }
            // $zonghe_da_arr = func_zoushi($result,0,'冠亚和大小',$type,true);

            $arr =  array_filter($re_arr);

        }
        $ages = array();
        if(!empty($arr)){
            foreach ($arr as $v) {
                $ages[] = $v[1];
            }
            array_multisort($ages, SORT_DESC, $arr);
        }

        // p($arr);
        return $arr;
    }


//JS需要的JSON排列
function pailie_odds_json($arr){

    $arr1 = array();
    $str2 =$str3 = $str4 = $str5 = '';
    foreach ($arr as $k => $v) {
      if($v['id'] == 842 || $v['id'] == 7788 ){
        $str3 .= '3/3:'.$v['odds_value'].',';
        // $str3 = rtrim(',',$str3);
        $arr1['j'.$v['id']] = $str3;
      }elseif($v['id'] == 843 || $v['id'] == 7789){
        $str3 .= '3/2:'.$v['odds_value'].',';
        // $str3 = rtrim(',',$str3);
        $arr1['j'.$v['id']] = $str3;
      }elseif($v['id'] == 841 || $v['id'] == 7787){
        $str2 .= '2/2:'.$v['odds_value'].',';
        // $str3 = rtrim(',',$str3);
        $arr1['j'.$v['id']] = $str2;
      }elseif($v['id'] == 844  || $v['id'] == 7790 ){
        $str4 .= '4/4:'.$v['odds_value'].',';
        // $str4 = rtrim(',',$str4);
        $arr1['j'.$v['id']] = $str4;
      }elseif( $v['id'] == 845  || $v['id'] == 7791 ){
        $str4 .= '4/3:'.$v['odds_value'].',';
        // $str4 = rtrim(',',$str4);
        $arr1['j'.$v['id']] = $str4;
      }elseif($v['id'] == 846 || $v['id'] == 7792 ){
        $str4 .= '4/2:'.$v['odds_value'].',';
        // $str4 = rtrim(',',$str4);
        $arr1['j'.$v['id']] = $str4;
      }elseif($v['id'] == 847  || $v['id'] == 7793 ){
        $str5 .= '5/5:'.$v['odds_value'].',';
        // $str5 = rtrim(',',$str5);
        $arr1['j'.$v['id']] = $str5;
      }elseif( $v['id'] == 848  || $v['id'] == 7794 ){
        $str5 .= '5/4:'.$v['odds_value'].',';
        // $str5 = rtrim(',',$str5);
        $arr1['j'.$v['id']] = $str5;
      }elseif( $v['id'] == 849 || $v['id'] == 7795){
        $str5 .= '5/3:'.$v['odds_value'].',';
        //$str5 = rtrim(',',$str5);
        $arr1['j'.$v['id']] = $str5;
      }else{
        $arr1['j'.$v['id']] = $v['odds_value'];
      }
    }
    return $arr1;
}


//查询所有的单个彩种赔率
public function get_odds_one($gameid){

    if (! empty($gameid)) {
        $this->public_db->select('c_odds_'.SITEID.'.id,odds_value,type_id');
        $map['c_odds_'.SITEID.'.pankou']  =  $_SESSION['pankou'];
        $map['type_id'] = $gameid;
		$map['c_odds_'.SITEID.'.site_id'] = SITEID;
		$map['c_odds_'.SITEID.'.index_id'] = INDEX_ID;
        $this->public_db->where($map);
        $this->public_db->order_by('c_odds_'.SITEID.'.type_id', "asc");

        $query = $this->public_db->get('c_odds_'.SITEID);

        $rows = $query->result_array();
        // echo $this->public_db->last_query();
        //$oddsall = array('202','203','204','205','206');
         $str3 = $str4 = $str5 = '';
        foreach ($rows as $k=>$v){
            if($v['id'] == 8503 || $v['id'] == 8504 || $v['id'] == 1634 || $v['id'] == 1635){//2中特
                if(empty($str3)){
                    $str3 .= $v['odds_value'].'/';
                }else{
                    $str3 .= $v['odds_value'];
                }
                $list[$v['id']] = $str3;



            }elseif($v['id'] == 8507 || $v['id'] == 8508 || $v['id'] == 1638 || $v['id'] == 1639){//3中2
                if(empty($str4)){
                    $str4 .= $v['odds_value'].'/';
                }else{
                    $str4 .= $v['odds_value'];
                }
                $list[$v['id']] = $str4;

            }else{
                $list[$v['id']]=$v['odds_value'];
            }

        }
        return $list;
    }
}

//当前用户输赢结果
public function get_syresule(){
	$uid = $_SESSION['uid'];
	if(empty($uid)){
		return 0;
	}else{
		//$starttime = "2016-01-23 00:00:00";
		//$endtime  = "2016-01-23 23:59:59";
		$starttime = date('Y-m-d')." 00:00:00";
		$endtime  = date('Y-m-d')." 23:59:59";

		$this->private_db->select('sum(win) as win,sum(money) as bjin');
		$where = "status = 1 and js =1 and uid =".$uid;
		$this->private_db->where($where." and addtime BETWEEN '".$starttime."' and '".$endtime."'");
		$query = $this->private_db->get('c_bet');
		//echo $this->private_db->last_query();exit;
		$rows = $query->result_array();
		$win = $rows[0]['win']-$rows[0]['bjin'];

		$this->private_db->select('sum(money) as money');
		$where1 = "status = 2 and js =1 and uid =".$uid;
		$this->private_db->where($where1." and addtime BETWEEN '".$starttime."' and '".$endtime."'");
		$query1 = $this->private_db->get('c_bet');

		$rows1 = $query1->result_array();
		$money = $rows1[0]['money'];
        $res_j = intval($win) - intval($money);
        if($res_j == 0){
            $res_j = '0.00';
        }
		return $res_j;
	}
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
        $odds_json = $this->get_odds_all($type);

        $pailie_odds_json = $this->pailie_odds_json($odds_json);
        //p($pailie_odds_json);
        // p($pailie_odds_json);exit;
        $money = $this->_get_userinfo($_SESSION['uid']);
        $NotCountSum = $this->_beted_limit_1($type);
        $changlong = $this->_get_liangmian($type);


$json_data['Success'] = 1;
$json_data['Msg'] = '';
if($_SESSION['username']){
    $IsLogin = true;
}else{
    $IsLogin = false;
}

//$IsLogin = true;

$json_data['ExtendObj'] = array('IsLogin' => $IsLogin);//是否登录
$json_data['OK'] = false;
$json_data['PageCount'] = 0;

$json_data['Obj']['IsLogin'] = $IsLogin;
$json_data['Obj']['ChangLong'] = $changlong;
$json_data['Obj']['CurrentPeriod'] = $this->_dq_qishu($type);//当前期数
$json_data['Obj']['CloseCount'] = $fengpan_time['f_t_stro'];//距离封盘时间
// $json_data['Obj']['CloseCount'] = -1;
$json_data['Obj']['OpenCount'] = $fengpan_time['k_t_stro'];//距离开奖时间
$json_data['Obj']['PrePeriodNumber'] = $kaijiang[0]['qishu'];//最近开奖期数
$json_data['Obj']['PreResult'] = $last_kaijiang;//最近开奖结果
// $json_data['Obj']['WinLoss'] = $this->get_syresule();	//今天输赢
$json_data['Obj']['NotCountSum'] = $NotCountSum?$NotCountSum:'0.00';//及时下注(当期下注)
$json_data['Obj']['Balance'] = $money['money']?$money['money']:'0.00';//余额
$chuqiulv = $this->_get_chuqiu($type);
$miss = $this->_get_miss($type);
$json_data['Obj']['ZongchuYilou'] = array("miss" => $miss , "hit" => $chuqiulv);//出球概率

$json_data['Obj']['Lines'] = $pailie_odds_json;//所有的赔率

$luzhu = $this->_get_luzhu($type);

$json_data['Obj']['LuZhu'] = $luzhu;//最下面子出球概率

        return JSON($json_data);
    }






}
?>