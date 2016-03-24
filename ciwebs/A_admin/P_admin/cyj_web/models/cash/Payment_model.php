<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Payment_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	public function payment_update($id,$arr =array(),$brr =array(),$crr =array())
	{
	    $this->db->trans_start();
	    $this->db->where(array('id'=>$id,'site_id'=>$_SESSION['site_id']))->update('k_cash_config',$arr);

	    $this->db->where(array('cash_id'=>$id,'site_id'=>$_SESSION['site_id']))->update('k_cash_config_ol',$brr);

	    $this->db->where(array('cash_id'=>$id,'site_id'=>$_SESSION['site_id']))->update('k_cash_config_line',$crr);

	    return $this->db->trans_complete();
	}

	//删除
	public function payment_del($id){
		$this->db->trans_start();
        $this->db->where(array('id'=>$id,'site_id'=>$_SESSION['site_id']))->delete('k_cash_config');
        $this->db->where(array('cash_id'=>$id,'site_id'=>$_SESSION['site_id']))->delete('k_cash_config_ol');
        $this->db->where(array('cash_id'=>$id,'site_id'=>$_SESSION['site_id']))->delete('k_cash_config_line');
        return $this->db->trans_complete();
	}

	//添加支付
	public function payment_add($sid,$name){
		$map = array();
		$map['table'] = 'k_cash_config';
		$map['where']['id'] = $sid;
		$map['where']['site_id'] = $_SESSION['site_id'];
		$cash_config = $this->rfind($map);
		$cash_config['type_name']=$name;
		$cash_config['type']= 0;
		$cash_config['id'] = "";//初始化覆盖值为空

        unset($map['where']['id']);
		$map['table'] = 'k_cash_config_ol';
		$map['where']['cash_id'] = $sid;
		$cash_config_ol = $this->rfind($map);
		$cash_config_ol['id'] = "";

		$map['table'] = 'k_cash_config_line';
		$cash_config_line = $this->rfind($map);
		$cash_config_line['id'] = "";

		//添加数据
		$this->db->trans_start();
		$this->db->insert('k_cash_config',$cash_config);
		$cash_id = $this->db->insert_id();

		$cash_config_ol['cash_id'] = $cash_config_line['cash_id'] = $cash_id;
		$this->db->insert('k_cash_config_line',$cash_config_line);
		$this->db->insert('k_cash_config_ol',$cash_config_ol);

		if($this->db->trans_complete()){
	        return $cash_id;
		}

	}

	//获取层级
	public function get_levels($index_id){
	    $db_model['tab'] = 'k_user_level';
	    $db_model['type'] =  1;
	    $db_model['is_port'] = 1;//读取从库

	    $map = array();
	    $map['site_id'] = $_SESSION['site_id'];
	    if (!empty($index_id)) {
	        $map['index_id'] = $index_id;
	    }

	    $map['is_delete'] = 0;
	    return $this->M($db_model)->where($map)->select('id');
	}

	//获取银行卡存款记录
	public function get_bank_log($map,$limit){
		$db_model = array();
		$db_model['tab'] = 'k_user_bank_in_record';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;//读取从库
		return $this->M($db_model)->where($map)->limit($limit)
		        ->order('log_time DESC')->select();
	}
        //获取总计
    public function sum($sum,$map,$db_model,$join){
		if (!empty($join)) {
		    return $this->M($db_model)->join($join)->where($map)->sum($sum);
		}else{
		    return $this->M($db_model)->where($map)->sum($sum);
		}
	}

	//获取银行卡信息
	public function get_banks(){
	    $redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
	    //$vdata = $redis->hgetall('bank_type');
		if (empty($vdata)) {
			//redis中数据为空 从数据库读取
			$db_model = array();
		    $db_model['tab'] = 'k_bank_cate';
		    $db_model['type'] = 1;
			$mdata = $this->M($db_model)->where("state='1'")->order('id asc')->select('id');
			foreach ($mdata as $key => $val) {
				$bank_json = json_encode($val, JSON_UNESCAPED_UNICODE);
				$bank_json = str_replace('"', '-', $bank_json);
				$hset[$key] = $bank_json;
			}
			$redis->hmset('bank_type',$hset);
			$bank_arr = $mdata;
		 }else{
			$vdata = str_replace('-', '"', $vdata);
			foreach($vdata as $key=>$value){
			  $vdata[$key] = json_decode($value,true);//转数组
			}
			$bank_arr = $vdata;
			ksort($bank_arr);
		}
		return $bank_arr;
	}

		//获取在线支付
	public function get_pays(){
	    $redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
	    //$vdata = $redis->hgetall('pay_type');
		if (empty($vdata)) {
			//redis中数据为空 从数据库读取
			$db_model = array();
		    $db_model['tab'] = 'k_online_bank_cate';
		    $db_model['type'] = 1;
			$mdata = $this->M($db_model)->where("state='1'")->order('id asc')->select('id');
			foreach ($mdata as $key => $val) {
				$bank_json = json_encode($val, JSON_UNESCAPED_UNICODE);
				$bank_json = str_replace('"', '-', $bank_json);
				$hset[$key] = $bank_json;
			}
			$redis->hmset('pay_type',$hset);
			$bank_arr = $mdata;
		 }else{
			$vdata = str_replace('-', '"', $vdata);
			foreach($vdata as $key=>$value){
			  $vdata[$key] = json_decode($value,true);//转数组
			}
			$bank_arr = $vdata;
			ksort($bank_arr);
		}
		return $bank_arr;
	}
}