<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
* 红包程序
**/

class Red extends MY_Controller
{

	public function __construct()
	{
	  	parent::__construct();
	  	$this->load->model('games/Red_model');

	  	header('Content-Type: application/json;charset=utf-8');
	}

	//轮询红包游戏
	public function index(){
		
		$data = array('code' => 99);
		//读取reids队列
		//有一个活动 读取单位时间内是否有游戏活动
		$list = $this->Red_model->get_list();
		$date_list = array();
		//var_dump($list);exit; 
		//循环所有游戏，提前 2小时 放入redis
		foreach ($list as $key => $value) {
			//已经结束的活动
			/*if((time() - strtotime($value['endtime'])) >= 0){
				continue;
			}*/
			if(time() > strtotime($value['starttime']) ){
				$this->Red_model->edit_bag($value['id']);
			}
			if(time() > strtotime($value['endtime'])){
				$this->Red_model->edit_bag_($value['id']);
			}
			if(time() > strtotime($value['starttime']) && (time() - strtotime($value['endtime'])) < 0 ) {
				//入reidis准备开抢
				if(! $this->Red_model->init_game($value)){
					//初始化失败返回日志
					$key_r = "init_game:id:".$value["id"];
					$this->Red_model->log_error($key_r,json_encode($value));
				}
				$value["opencount"] = time() - strtotime($value['starttime']);
				$value["closecount"] = strtotime($value['endtime']) - time();
				unset($value["amount_inpoint"]);
				unset($value["award_times"]);
				unset($value["create_ip"]);
				unset($value["create_uid"]);
				unset($value["endtime"]);
				unset($value["groupid"]);
				unset($value["index_id"]);
				unset($value["make_sure"]);
				unset($value["min_inpoint"]);
				unset($value["red_num"]);
				unset($value["site_id"]);
				unset($value["starttime"]);
				unset($value["create_time"]);
				unset($value["status"]);
				unset($value["totle_money"]);
				unset($value["description"]);
				unset($value["end_theme"]);
				unset($value["end_instruction"]);
				$date_list[] =  $value;
			}
		}
		foreach ($date_list as $k => $v) {
			$status = $this->Red_model->get_big_bag($v['id']);
			if($status['status'] == 3 || $status['status'] == 2){
				unset($date_list[$k]);
			}
		}
		if(!empty($date_list)){
			$date_list = array_values($date_list);
		}
		$data = array('Code' => 0,"List" => $date_list);
		echo json_encode($data);
	}

	//抢红包
	public function snatch(){
		$rid = intval($this->input->get("rid"));

		if($rid <= 0){
			//未知的错误
			echo json_encode(array('Code' =>5));
			return;
		}

		if(intval($_SESSION['uid']) <= 0){
			//未登录
			echo json_encode(array('Code' =>4));
			return;
		}
		if($_SESSION['shiwan'] == 1){
			//试玩账号
			echo json_encode(array('Code' =>20));
			return;
		}
		$red = array();
		//获取当天的红包匹配id
		$redis = new redis();  
		$redis->connect(REDIS_HOST, REDIS_PORT);

		$day_1 = $redis->lrange("red_bag".SITEID."_".INDEX_ID."_".date("Y-m-d",strtotime("-1 day")),0,-1);
		$day_1 = str_replace('--', '"', $day_1);
        if (FALSE !== $day_1){
        	foreach ($day_1 as $value) {
        		$temp = json_decode($value,TRUE);
        		if($rid  === intval($temp["id"])){
	        		$red = $temp;
	        		break;
	        	}
        	}
        }
        if(empty($red)){
			$day1 = $redis->lrange("red_bag".SITEID."_".INDEX_ID."_".date("Y-m-d",strtotime("+0 day")),0,-1);
	        $day1 = str_replace('--', '"', $day1);
	        if (FALSE !== $day1){
	        	foreach ($day1 as $value) {
	        		$temp = json_decode($value,TRUE);
	        		if($rid  === intval($temp["id"])){
		        		$red = $temp;
		        		break;
		        	}
	        	}
	        }
        }

        if(!empty($red)){
        	//判断活动是否结束
			if((time() - strtotime($red['endtime'])) >= 0){
				echo json_encode(array('Code' =>7));
        		return;
			}
        	//判断是有分组限制
        	if(!in_array($_SESSION["level_id"], explode(",", $red['groupid']))){
        		echo json_encode(array('Code' =>21));//权限不够
        		return;
        	}
        }else{
        	//没有该红包活动
        	echo json_encode(array('Code' =>99));
        	return;
        }
		$data = $this->Red_model->snatch($rid,$red);

		echo json_encode($data);
	}

	//查看红包获取的详情
	public function snatch_info(){
		$rid = intval($this->input->get("rid"));

		if($rid <= 0){
			//未知的错误
			echo json_encode(array('Code' =>5));
			return;
		}

		if(intval($_SESSION['uid']) <= 0){
			//未登录
			echo json_encode(array('Code' =>4));
			return;
		}
		$list = $this->Red_model->get_finishlist($rid);

		foreach ($list["List"] as $key => $value) {
			$str = $value["username"];
			$str = substr($str,0,4)."***";
			$list["List"][$key]["username"] = $str;

			unset($list["List"][$key]["id"]);
			unset($list["List"][$key]["amount_inpoint"]);
			unset($list["List"][$key]["createip"]);

			unset($list["List"][$key]["endtime"]);
			unset($list["List"][$key]["index_id"]);
			unset($list["List"][$key]["make_sure"]);
			unset($list["List"][$key]["min_inpoint"]);
			unset($list["List"][$key]["rid"]);

			unset($list["List"][$key]["site_id"]);
			unset($list["List"][$key]["starttime"]);
			unset($list["List"][$key]["uuid"]);
		}
		for ($i=0; $i <=100 ; $i++) { 
			$list["List"][count($list["List"])] = $this->get_($list["List"][0]["money"]);
		}
		$id = array();
		foreach ($list["List"] as $red) {
		    $id[] = $red['createtime'];
		    unset($list["List"][$key]["createtime"]);
		}
		array_multisort($id, SORT_ASC, $list);
		echo json_encode($list);
	}

	//假数据
	public function get_($money){
		if($money == 0 || $money == '0.00'){
			$money = '100.00';
		}
		$array = array();
		for ($i = 1; $i <= 4; $i++) {
		$array['username'] = chr(rand(97, 122)).chr(rand(97, 122)).chr(rand(97, 122)).chr(rand(97, 122)).'***';
		}
		$array['money'] = $this->randomFloat($money,($money*rand(10,50)));
		return $array;
	}

	function randomFloat($min = 0, $max = 1) {  
	    return $min + mt_rand() / mt_getrandmax() * ($max - $min);  
	}  

}
