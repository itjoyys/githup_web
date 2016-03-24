<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class System_model extends MY_Model {

	function __construct() {
		//$this->init_db();
	}
	public function get_fc_limit($aid) {
		$redis = new Redis();
	    $redis->connect(REDIS_HOST,REDIS_PORT);
	    $redis_key = $_SESSION['site_id'].'_'.$aid.'_fc_limit';
	    //$redis->delete($redis_key);
	    $vdata = $redis->hgetall($redis_key);
	    $fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten','gd_ten','bj_8','bj_10','tj_ssc','xj_ssc','jx_ssc','jl_k3','js_k3','liuhecai');
	    if (empty($vdata)) {
			$mobj = $this->M(array('tab'=>'fc_games_view','type'=>1));
			//系统设定默认为1
	    	$is_default = empty($aid)?1:0;
	        foreach ($fcArr as $key => $val) {
	        	$map = "k_user_agent_fc_set.is_default = '".$is_default."' and fc_games_view.fc_type = '".$val."' and site_id = '".$_SESSION['site_id']."' and k_user_agent_fc_set.aid = '".$aid."'";
	            $data[$val] = $mobj->join("inner join k_user_agent_fc_set on fc_games_view.id = k_user_agent_fc_set.type_id")->where($map)->select();
	            $fc_json = json_encode($data[$val], JSON_UNESCAPED_UNICODE);
		        $fc_json = str_replace('"', '--', $fc_json);
		        $hset[$val] = $fc_json;
	        }
	        $redis->hmset($redis_key,$hset);
	    }else{
	    	$vdata = str_replace('--', '"', $vdata);
	    	sort($vdata);
	    	foreach ($vdata as $key => $val) {
	    		$tmp_data = json_decode($val,true);//转数组;
	    		$data[$tmp_data[0]['fc_type']] = $tmp_data;//转数组
	    	}
	    }
		return $data;
	}

	 //获取体育限额
	public function get_sp_limit($aid) {
        $redis = new Redis();
	    $redis->connect(REDIS_HOST,REDIS_PORT);
	    $redis_key = $_SESSION['site_id'].'_'.$aid.'_sp_limit';
	    //$vdata = $redis->delete($redis_key);
	    $vdata = $redis->hgetall($redis_key);
	    $spArr = array('ft','bk','vb','bs','tn');
	    if (empty($vdata)) {
			$mobj = $this->M(array('tab'=>'sp_games_view','type'=>1));
	        foreach ($spArr as $key => $val) {
	            $data[$val] = $mobj->join("inner join k_user_agent_sport_set on sp_games_view.id = k_user_agent_sport_set.type_id")->where("k_user_agent_sport_set.is_default = 1 and sp_games_view.type = '".$val."' and site_id = '".$_SESSION['site_id']."'")->select();
	            $sp_json = json_encode($data[$val], JSON_UNESCAPED_UNICODE);
		        $sp_json = str_replace('"', '-', $sp_json);
		        $hset[$val] = $sp_json;
	        }
	        $redis->hmset($redis_key,$hset);
	    }else{
	    	$vdata = str_replace('-', '"', $vdata);
	    	rsort($vdata);//逆向排序
	    	foreach ($vdata as $key => $val) {
	    		$tmp_data = json_decode($val,true);//转数组;
	    		$data[$tmp_data[0]['type']] = $tmp_data;//转数组
	    	}
	    }
		return $data;
	}

}