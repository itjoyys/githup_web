<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Odds_set2_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//获取彩票结果
	public function get_fc_odds($type,$map){
		if($type == 1){
			$db_model['tab'] = 'c_odds_'.$_SESSION['site_id'];
			$db_model['type'] = 2;
            return $this->M($db_model)->where($map)->select();
		}else{
			$db_model['tab'] = 'c_odds';
			$db_model['type'] = 2;
            return $this->M($db_model)->field('odds_value,type2,type_id,input_name')->where($map)->select();
		}
	}

	//获取球数与id的关系
	public function get_balls($type){
		$db_model['tab'] = 'fc_games_type';
		$db_model['type'] = 2;
		if(!empty($type)){
		    $map['wanfa'] = $type;
		}
		$map['state'] == 1;
        return $this->M($db_model)->where($map)->select('id');
	}

	/**
	 * 修改赔率之后删除redis
	 * @param [type] 彩票类型：fc_3d
	 * @return [none]
	 */
	public function del_odd_redis($type){
	    $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $redis_key = $_SESSION['site_id'].'_'.$type.'_odds';
	    try{
	    	$redis->delete($redis_key); //清除redis里面的键值
	    }catch(Exception $e){
	    	return false;
	    }
        return true;
	}

	//通用赔率设置
	public function odds_set_do($fc_type,$odds,$type_id,$input_name,$pankou,$itype ='',$index_id){
		$db_model['tab'] = 'c_odds_'.$_SESSION['site_id'];
		$db_model['type'] = 2;

		$map = array();
		$map['lottery_type'] = $fc_type;
		//$map['input_name'] = array('in','('.$input_name.')');
		$map['index_id'] = $index_id;
		if (strpos($input_name,'/')) {
		    $type2_arr = explode('/',$input_name);
		    $map['type2'] = $type2_arr[0];
		    $map['input_name'] = $type2_arr[1];
		}elseif($type_id == '232' || $type_id == '231' || $type_id == '233' || $type_id == '234'){
			$map['type2'] = $itype;
            $map['input_name'] = array('in','('.$input_name.')');
		}else{
			$map['input_name'] = array('in','('.$input_name.')');
		}

		if ($fc_type == 'liuhecai') {
            $map['pankou'] = $pankou;
		}else{
			$map['pankou'] = 'A';
		}

		$tmp_typeid = array('159'=>'159,160,161','166'=>'166,167,168','191'=>'191,192,193,194,195','235'=>'235,236,237,238,239','246'=>'246,247,248,249,250','197'=>'197,198,199','241'=>'241,242,243','252'=>'252,253,254','173'=>'173,174,175,176,177,178,179,180','182'=>'182,183,184,185,186,187,188,189','208'=>'208,209','209'=>'208,209','211'=>'211,212,213,214,215,216,217,218,219,220');

		if ($tmp_typeid[$type_id]) {
		    $map['type_id'] = array('in','('.$tmp_typeid[$type_id].')');
		}else{
            $map['type_id'] = $type_id;
		}

		$log = $this->M($db_model)->where($map)->update(array('odds_value'=>$odds));
		return $log;
	}



}