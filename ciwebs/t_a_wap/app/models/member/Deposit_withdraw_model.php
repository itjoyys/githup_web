<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Deposit_withdraw_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->init_db();
		$this->load->model('Common_model');
	}


	//第三方支付
	public function GetThirdPartyBanks(){
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		$level_info = $this->Common_model->get_user_level_info($userinfo['level_id']);
		//线上入款最小金额 最大金额
		$_SESSION['ol_catm_max'] = $level_info['ol_catm_max'];
		$_SESSION['ol_catm_min'] = $level_info['ol_catm_min'];
		if($_SESSION['pay']['is_card'] == 1){
			unset($_SESSION['pay']);
		}
		if($_SESSION['pay']['payid'] > 0){
			$status = $_SESSION['pay'];    //防止用户一直在银行卡页面刷新 而更换了支付方式   在成功跳转到第三方的时候清除此session
		}
		//获取站点可用的第三方
		$paytype = $this->get_paytype($_SESSION['uid'],$status,0);
		if(empty($paytype['paytype']) || empty($paytype['payid'])){
			$json = array();
			$json['ErrorCode'] = 1;
			return $json;
		}else{
			$_SESSION['pay'] = $paytype;
		}
		$json = array();
		$json['ErrorCode'] = 0;
		$json['ErrorMsg'] = "执行成功";
		$json['Data']['BankAccount']['ID'] = $paytype['payid'];
		$json['Data']['BankAccount']['Platform'] = $paytype['paytype'];
		$json['Data']['BankList'] = $this->get_bank_info($paytype['paytype']);
		return $json;

	}

	//点卡处理
	public function GetThirdPartyCardBanks(){
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		$level_info = $this->Common_model->get_user_level_info($userinfo['level_id']);
		//线上入款最小金额 最大金额
		$_SESSION['ol_catm_max'] = $level_info['ol_catm_max'];
		$_SESSION['ol_catm_min'] = $level_info['ol_catm_min'];
		if($_SESSION['pay']['is_card'] == 0){
			unset($_SESSION['pay']);
		}
		if($_SESSION['pay']['payid'] > 0){
			$status = $_SESSION['pay'];    //防止用户一直在银行卡页面刷新 而更换了支付方式   在成功跳转到第三方的时候清除此session
		}
		//获取站点可用的第三方
		$paytype = $this->get_paytype($_SESSION['uid'],$status,1);
		if(empty($paytype['paytype']) || empty($paytype['payid'])){
			$json = array();
			$json['ErrorCode'] = 1;
			return $json;
		}else{
			$_SESSION['pay'] = $paytype;
		}
		$json = array();
		$json['ErrorCode'] = 0;
		$json['ErrorMsg'] = "执行成功";
		$json['Data']['BankAccount']['ID'] = $paytype['payid'];
		$json['Data']['BankAccount']['Platform'] = $paytype['paytype'];
		$json['Data']['BankList'] = $this->get_bank_info($paytype['paytype']);
		return $json;

	}

	/*
	**根据uid查询当前使用的第三方
	**$uid    用户id
	**$status 判断用户是否首次进入支付页面
	*/
	function get_paytype($uid,$status,$is_card){
		if(empty($status)){
			$info = $this->Common_model->get_user_info($uid);
			if(!empty($info)){
				$level_id = $info['level_id'];
				$map = array();
				$map['table'] = "pay_set";
				$map['select'] = "id,level_id,pay_type,money_limits";
				$map['where']['is_delete'] = 0;
				$map['where']['is_card'] = $is_card;
				$map['where']['site_id'] = SITEID;
				$result = $this->rget($map);
				if($result){
					$re_array = $arr = $data = '';
					foreach ($result as $key => $value) {
						$arr = explode(',',$value['level_id']);
						if(in_array($level_id, $arr)){
							$re_array[] = $value;
						}
					}
				}
				if($re_array){
					foreach ($re_array as $key => $value) {
						$this->db->like('do_time',date('Y-m-d'));
						$data = $this->db->select('sum(deposit_num) as money')->get_where('k_user_bank_in_record',array('site_id' => SITEID,'pay_id' => $value['id'],'into_style' => 2,'make_sure' => 1))->row_array();
						$re_array[$key]['pay_money'] = !empty($data['money'])?$data['money']:0;
					}
					foreach ($re_array as $key => $value) {
						if( floatval($value['money_limits']) <= floatval($value['pay_money'])){
			                unset($re_array[$key]);
			            }
					}
					shuffle($re_array);
				}

			}
			unset($arr);
			$arr['payid'] = $re_array[0]['id'];
			$arr['paytype'] = $re_array[0]['pay_type'];
			$arr['is_card'] = $is_card;
		}else{
			$arr = $status;
		}
		return $arr;
	}


	/*
	**定义银行数组
	**1   新生
	**2   易宝
	**3   环迅
	**4   币付宝
	**6   宝付
	**7   智付
	**8   汇潮
	**9   国付宝
	**10  融宝
	**11  快捷通
	**12  新环迅
	**13  易宝点卡
	*/

	  //通过id获取第三方支付所有银行信息
    public function get_bank_info($id){
    		//从redis上获取所有开启的银行卡信息
				$redis = new Redis();
				$redis->connect(REDIS_HOST,REDIS_PORT);
				//$vdata = $redis->lrange('pay_bank'.$id, 0 ,$redis->lSize('pay_bank'.$id));
				if (empty($vdata)) {
					//redis中数据为空 从数据库读取
					$map['table'] = 'k_online_value';
					$map['select'] = "bank_name,values";
					$map['where']['state'] = 1;
					$map['where']['oid'] = $id;
					$result = $this->rget($map);
					foreach ($result as $key => $value) {
						unset($result[$key]);
						$result[$key]['BankName'] = $value['bank_name'];
						$result[$key]['BankNameAbbr'] = $value['values'];
					}
				 }else{
				 	$result =array();
					$vdata = str_replace('--', '"', $vdata);
					foreach($vdata as $key=>$value){
					  $temp= json_decode($value,true);//转数组
					  $result[] = array('BankName' =>$temp['bank_name'],'BankNameAbbr' => $temp['values']);
					}
				}

				return $result;
    }

	/*
	**根据第三方的类型提交相应的方法
	**1   新生 ok
	**2   易宝   ok
	**3   环迅   ok
	**4   币付宝 ok
	**6   宝付   ok
	**7   智付   ok
	**8   汇潮   ok
	**9   国付宝 ok
	**10  融宝
	**11  快捷通 ok
	**12  新环迅 ok
	**13  易宝点卡  ok
	*/
	public function get_api_url($type){
		switch ($type) {
			case '1':
				$url = 'hnapay';
				break;
			case '2':
				$url = 'yeepay';
				break;
			case '3':
				$url = 'ips';
				break;
			case '4':
				$url = 'bfpay';
				break;
			case '6':
				$url = 'baofoo';
				break;
			case '7':
				$url = 'dinpay';
				break;
			case '8':
				$url = 'ecpss';
				break;
			case '9':
				$url = 'states';
				break;
			case '10':
				$url = 'reapal';
				break;
			case '11':
				$url = 'kjtpay';
				break;
			case '12':
				$url = 'newips';
				break;
			case '13':
				$url = 'yeecard';
				break;
			default:
				break;
		}
		return $url;
	}

	public function AddThirdPartyDeposit($get){
		$order_num = $this->get_order_num();
		$bank = trim($get['BankCode']);   //銀行參數
		$payid = intval($_SESSION['pay']['payid']);//支付ID
		$s_amount = doubleval($get['DepositMoney']);//金额
		$payconf = $this->get_pay_config($payid);
		$flag = $this->order_num_unique($order_num);
		$TradeDate = date('Y-m-d H:i:s');
		$returnParams = $_SESSION['username'];
		if($flag > 0){
			$json = array();
			$json['ErrorCode'] = 1;
			return $json;
		}
		$result = $this->write_in_record($_SESSION['uid'],$_SESSION['agent_id'],$s_amount,$order_num);
		if($result){
			$type = $this->get_pay_act($_SESSION['pay']['paytype']);
			$this->load->model('member/pay/'.$type.'_model');
			$mod = $type.'_model';
			if ($_SESSION['pay']['paytype'] == 1||$_SESSION['pay']['paytype'] == 9||$_SESSION['pay']['paytype'] == 10||$_SESSION['pay']['paytype'] == 11){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$payconf['vircarddoin']);
				
				if($_SESSION['pay']['paytype'] == 10){
					unset($_SESSION['pay']);die;//沒人使用
				}
				unset($_SESSION['pay']);
			}else if($_SESSION['pay']['paytype'] == 4){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$_SESSION['username']);
				unset($_SESSION['pay']);
			}else if($_SESSION['pay']['paytype'] == 5){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$returnParams,$TradeDate);
				unset($_SESSION['pay']);
			}else if($_SESSION['pay']['paytype'] == 6){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$_SESSION['username'],$payconf['terminalid']);
				unset($_SESSION['pay']);
			}else if($_SESSION['pay']['paytype'] == 12){
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$_SESSION['username'],$payconf['vircarddoin']);
				unset($_SESSION['pay']);
			}else if($_SESSION['pay']['paytype'] == 13){
				$pa7_cardAmt = trim($get['pa7_cardAmt']);//卡面值
				$pa8_cardNo = trim($get['pa8_cardNo']);//序列号
				$pa9_cardPwd = trim($get['pa9_cardPwd']);//卡密
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key'],$_SESSION['username'],$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd);
			}else{
				$data = $this->$mod->get_all_info($payconf['f_url'],$order_num,$s_amount,$bank,$payconf['pay_id'],$payconf['pay_key']);
				unset($_SESSION['pay']);
			}
			$json = array();
			if($_SESSION['pay']['paytype'] == 13){
				$json['ErrorCode'] = 7;
			}else{
				$json['ErrorCode'] = 0;
			}
			unset($_SESSION['pay']);
			$json['Data']['PayUrl'] = $data;
			return $json;
		}else{
			$json = array();
			$json['ErrorCode'] = 1;
			return $json;
		}
		
	}

	/*
	**读取第三方配置信息
	*/

	function get_pay_config($payid){
		$map = array();
		$map['table'] = "pay_set";
		$map['where']['id'] = $payid;
		$map['where']['site_id'] = SITEID;
		return $this->rfind($map);
	}

	/*
	**查询是否为首次入款
	*/
	function is_firsttime($uid){
		$map = array();
        $map['table'] = 'k_user_bank_in_record';
		$map['select'] = "id";
        $map['where']['uid'] = $uid;
		$map['where']['site_id'] = SITEID;
		$map['where']['make_sure'] = 1;
        $user_record = $this->rfind($map);
		if(empty($user_record['id'])){
			return true;
		}else{
			return false;
		}
	}

	/*
	**获取支付参数设定
	*/
	function get_payset($uid)
	{
		$info = $this->Common_model->get_user_info($uid);
		if(isset($info)){
			$map = array();
			$map['table'] = "k_user_level";
			$map['select'] = "RMB_pay_set";
			$map['where']['id'] = $info['level_id'];
			$result = $this->rfind($map);
			if(isset($result)){
				$map = array();
				$map['table'] = "k_cash_config_view";
				$map['where']['id'] = $result['RMB_pay_set'];
				$result2 = $this->rfind($map);
				if(isset($result2)){
					return $result2;
				}
			}
		}
	}

	/*
	**写入入款记录
	*/
	function write_in_record($uid,$agent_id,$s_amount,$order_num){
		$is_firsttime = $this->is_firsttime($uid);
		$data_1 = $this->get_payset($uid);
		$userinfo = $this->Common_model->get_user_info($uid);
		$agent = $this->get_agent($uid);
		$level = $this->Common_model->get_user_level_info($userinfo['level_id']);

		if ((intval($s_amount)>intval($_SESSION['ol_catm_max'])) || (intval($s_amount)<intval($_SESSION['ol_catm_min']))){
				return false;
			}
		if($is_firsttime === true){
			$postdata['is_firsttime'] = 1;
		}else{
			$postdata['is_firsttime'] = 0;
		}
		if($data_1['ol_deposit'] == 1){
			//存款优惠判断
			    if ($s_amount >= $data_1['ol_discount_num']) {
			       $postdata['favourable_num'] = (0.01*$s_amount*$data_1['ol_discount_per']>$data_1['ol_discount_max'])?$data_1['ol_discount_max']:(0.01*$s_amount*$data_1['ol_discount_per']);
			    }
			    //其它优惠判断
			    if ($s_amount >= $data_1['ol_other_discount_num']) {
			       $postdata['other_num'] = (0.01*$s_amount*$data_1['ol_other_discount_per']>$data_1['ol_other_discount_max'])?$data_1['ol_other_discount_max']:(0.01*$s_amount*$data_1['ol_other_discount_per']);
			    }
		}elseif($data_1['ol_deposit'] == 2){
			if($postdata['is_firsttime']==1){
		        //存款优惠判断
		        if ($s_amount >= $data_1['ol_discount_num']) {
		           $postdata['favourable_num'] = (0.01*$s_amount*$data_1['ol_discount_per']>$data_1['ol_discount_max'])?$data_1['ol_discount_max']:(0.01*$s_amount*$data_1['ol_discount_per']);
		        }
		        //其它优惠判断
		        if ($s_amount >= $data_1['ol_other_discount_num']) {
		           $postdata['other_num'] = (0.01*$s_amount*$data_1['ol_other_discount_per']>$data_1['ol_other_discount_max'])?$data_1['ol_other_discount_max']:(0.01*$s_amount*$data_1['ol_other_discount_per']);
		        }
		    }else{
		        $postdata['other_num']=$postdata['favourable_num']=0;
		    }
		}
		$postdata['uid']=$uid;
		$postdata['order_num']=$order_num;
		$postdata['into_style']=2;//线上入款
		$postdata['level_des']=$level['level_des'];
		$postdata['site_id']=SITEID;
		$postdata['index_id']=INDEX_ID;
		$postdata['ptype']=1;
		$postdata['agent_id']=$_SESSION['agent_id'];
		$postdata['level_id']=$userinfo['level_id'];
		$postdata['agent_user']=$agent['agent_user'];
		$postdata['username']=$userinfo['username'];
		$postdata['deposit_money']=$s_amount+$postdata['other_num']+$postdata['favourable_num'];
		$postdata['deposit_num']=$s_amount;
		$postdata['in_name']=$userinfo['pay_name'];
		$postdata['log_time']=date('Y-m-d H:i:s');
		$postdata['in_date']=date('Y-m-d H:i:s');
		$postdata['in_info']=$userinfo['pay_name'].",".date("Y-m-d H:i:s").","."第三方支付";
		$postdata['pay_id']=$_SESSION['pay']['payid'];
		$postdata['paytype']=$_SESSION['pay']['paytype'];
		if ( (empty($postdata['pay_id'])) || (empty($postdata['paytype'])) ){
			return false;
		}
		 $this->db->insert('k_user_bank_in_record',$postdata);
		 return $this->db->insert_id();
	}

	/*
	**获取用户代理信息
	*/
	function get_agent($uid){
		$map = array();
        $map['table'] = 'k_user_agent';
        $map['where']['id'] = $_SESSION['agent_id'];
		$map['where']['site_id'] = SITEID;
		return $this->rfind($map);
	}

	/*
	**第三方支付生成订单号
	*/
	function get_order_num(){
		$order_num = substr(date("YmdHis"),2).mt_rand(100000,999999);
		return $order_num;
	}

	/*
	**判断订单号是否唯一
	*/
	function order_num_unique($order_num){
		$flag = 0;
		$map = array();
        $map['table'] = 'k_user_bank_in_record';
		$map['select'] = "order_num";
        $map['where']['order_num'] = $order_num;
        $result = $this->rfind($map);
		if(!empty($result['order_num'])){
			$flag = 1;
		}
		return $flag;
	}

	function get_pay_act($type){
		switch ($type) {
			case '1':
				$action =  "Hnapay";
				break;
			case '2':
				$action =  "Yeepay";
			   break;
			case '3':
				$action =  "Ips";
				break;
			case '4':
				$action =  "Bfpay";
			   break;
			case '5':
				$action =  "Remittance";
				break;
			case '6':
				$action =  "Baofoo";
			   break;
			case '7':
				$action =  "Dinpay";
				break;
			case '8':
				$action =  "Ecpss";
			   break;
			case '9':
				$action =  "States";
				break;
			case '10':
				$action =  "Reapal";
			   break;
			case '11':
				$action =  "Kjtpay";
				break;
			case '12':
				$action =  "Newips";
			   break;
			case '13':
				$action = "Card";
				break;
			case '14':
				$action =  "Dinpay";
			   break;
		}
		return $action;
	}
	
	
}