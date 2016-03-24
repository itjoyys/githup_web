<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Online_api_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->init_db();
		$this->load->library('Games');
		$this->load->model('Common_model');
	}
	/*
	**根据uid查询是否绑定银行卡
	*/
	function is_bank($uid){
		$info = $this->Common_model->get_user_info($uid);
		if(!empty($info['pay_num'])){
			return false;
		}else{
			return true;
		}
	}

	/**
	* 根据用户信息查询当前使用的第三方
	* @param  array  $info      用户ID
	* @param  array  $status   第三方支付信息
	* @param  int    $is_card  支付类型
	* @return array  $arr     可用第三方 
	**/
	function get_paytype($info,$status,$is_card){
		if(empty($status)){
			if(!empty($info)){
				$level_id = $info['level_id'];
				$map = array();
				$map['table'] = "pay_set";
				$map['select'] = "id,level_id,pay_type,money_limits";
				$map['where']['is_delete'] = 0;
				$map['where']['site_id'] = SITEID;
				$map['where']['is_card'] = $is_card;
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
			$arr = array();
			$arr['payid'] = $re_array[0]['id'];
			$arr['paytype'] = $re_array[0]['pay_type'];
			$arr['is_card'] = $is_card;
		}else{
			$arr = $status;
		}
		return $arr;
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
	/**
	* 第三方支付生成订单号
	* @return string  $order_num   第三方订单号
	**/
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
		$map['where']['site_id'] = SITEID;
        $result = $this->rfind($map);
		if(!empty($result['order_num'])){
			$flag = 1;
		}
		return $flag;
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
	**写入入款记录
	*/
	function write_in_record($uid,$agent_id,$s_amount,$order_num){
		$is_firsttime = $this->is_firsttime($uid);
		$data_1 = $this->get_payset($uid);
		$userinfo = $this->Common_model->get_user_info($uid);
		$agent = $this->get_agent($uid);
		$level = $this->Common_model->get_user_level_info($userinfo['level_id']);

		if ((intval($s_amount)>intval($_SESSION['ol_catm_max'])) || (intval($s_amount)<intval($_SESSION['ol_catm_min']))){
				echo '<script>alert("支付异常,超过上限或小于下限！请联系客服！");window.close();</script>';exit;
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
			echo "<script>alert('非法操作！');history.go(-1);</script>";
		}
		 $this->db->insert('k_user_bank_in_record',$postdata);
		 return $this->db->insert_id();
	}
	/*
	**读取第三方配置信息
	*/
	function get_pay_config($payid){
		$map = array();
		$map['table'] = "pay_set";
		$map['where']['id'] = $payid;
		return $this->rfind($map);
	}
	/*
	**回调函数  根据订单号获取配置信息
	*/
	function get_states_conf($order_num){
			$user    = '';
			$result  = '';
			$map = array();
			$map['table'] = 'k_user_bank_in_record';
			$map['select'] = "pay_id";
			$map['where']['order_num'] = $order_num;
			$user = $this->rfind($map);
			unset($map);
			$map = array();
			$map['table'] = 'pay_set';
			$map['select'] = "pay_id,pay_key,terminalid";
			$map['where']['id'] = $user['pay_id'];
			return $this->rfind($map);
	}
	/*
	**回调函数   根据订单号 获取入款注单数据
	*/
	function get_in_cash($order_num){
		$user = $this->db->get_where('k_user_bank_in_record',array('order_num'=>$order_num))->row_array();
		return $user;
	}
	/*
	** 回调函数   根据订单号和金额修改订单信息
	*/
	function update_order($uid,$merOrderNum,$tranAmt){
		//更新视讯余额
		
		$user = $this->Common_model->get_user_info($uid);
		$aud = $this->get_payset($uid);
		$data_a['order_num'] = $merOrderNum;
		$data_a['site_id'] = SITEID;
		$data_a['make_sure'] = 0;
		$iMoney = $this->db->select('deposit_money,order_num,favourable_num,other_num,deposit_num,do_time,id,username,index_id,ptype')->get_where('k_user_bank_in_record',$data_a)->row_array();
		$this->getallbalance($iMoney['username']);
		if($tranAmt!=$iMoney['deposit_num']){
			echo '<script>alert("支付异常！请联系客服！");window.close();</script>';exit;
		}else{
			//支付金额匹配正确 更新开始
			$now_date = date('Y-m-d H:i:s');
			$this->db->trans_begin();
			$online_info = array();
			$online_info['uid'] = $uid;
			$online_info['username'] = $iMoney['username'];
			$online_info['order_num'] = $iMoney['order_num'];
			$online_info['info'] = json_encode($_REQUEST);
			$online_info['pay_domain'] = $_SERVER['HTTP_HOST'];
			$online_info['site_id'] = SITEID;
			$online_info['index_id'] = $iMoney['index_id'];
			$online_info['order_money'] = $tranAmt;
			$online_info['order_time'] = $now_date;
			$this->db->insert('k_online_log',$online_info);
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				echo "<script>alert('交易失败,错误代码cw0009');window.close();</script>";exit;
			}

			$this->db->where('uid',$uid);
			$this->db->set('money','money+'.$iMoney['deposit_money'],FALSE);
			$this->db->update('k_user');
			if($this->db->affected_rows() != 1){
				$this->db->trans_rollback();
				echo "<script>alert('交易失败,错误代码cw0001');window.close();</script>";exit;
			}
		    $data_bin=array();
	        $data_bin['make_sure'] = 1;
	        $data_bin['admin_user'] = $user['username'];
	        $data_bin['do_time'] = $now_date;
	        $this->db->where('id',$iMoney['id']);
	        $this->db->where('make_sure',0);
	        $this->db->update('k_user_bank_in_record',$data_bin);
	        if($this->db->affected_rows() != 1){
				$this->db->trans_rollback();
				echo "<script>alert('交易失败,错误代码cw0002');window.close();</script>";exit;
			}
			$unow_m = $this->Common_model->get_user_info($uid);
			if(empty($unow_m['money'])){
				//未获取到或者之前余额为负数回滚
				$this->db->trans_rollback();
				echo '<script>alert("入款失败,错误代码cw0003");window.close();</script>';exit;
			}
			$data_c=array();
			$data_c['source_id'] = $iMoney['id'];
			$data_c['site_id'] = SITEID;
			$data_c['index_id'] = INDEX_ID;
			$data_c['uid'] = $uid;
			$data_c['username'] = $user['username'];
			$data_c['cash_date'] = $now_date;
			$data_c['cash_type'] = 10;//表示线上入款
			$data_c['cash_do_type'] = 1;
			$data_c['agent_id'] = $user['agent_id'];
			$data_c['cash_balance'] = $unow_m['money'];//当前余额
			$data_c['cash_num'] = $iMoney['deposit_num'];
			$data_c['discount_num'] = $iMoney['favourable_num']+$iMoney['other_num'];//优惠总额
			$data_c['remark'] = $iMoney['order_num'];//备注
			$data_c['ptype'] = $iMoney['ptype'];//备注
			$this->db->insert('k_user_cash_record',$data_c);
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				echo "<script>alert('交易失败,错误代码cw0004');window.close();</script>";exit;
			}
			 //稽核
		   //更新上一个稽核的终止时间
		   $data_d = array();
		   $data_d['table'] = "k_user_audit";
		   $data_d['select'] = "id";
		   $data_d['where']['uid'] = $uid;
		   $data_d['where']['type'] = 1;
		   $data_d['where']['site_id'] = SITEID;
		   $data_d['order'] = "id desc";
		   $l_audit = $this->rfind($data_d);
			//查询稽核是否锁定
		   $this->db->from('k_user_bank_out_record');
		   $this->db->where('uid',$uid);
		   $this->db->where('site_id',SITEID);
		   $this->db->where_in('out_status','0,4');
		   $is_audit_lock = $this->db->get()->row_array();
		   //会员余额
		   $userMoney['sum_money'] = $user['money']+$user['ag_money']+$user['og_money']+$user['mg_money']+$user['ct_money']+$user['lebo_money']+$user['bbin_money']+$user['pt_money'];
			//启动清除稽核
		   if (($userMoney['sum_money'] < $aud['ol_ct_fk_audit']) && !empty($l_audit) && empty($is_audit_lock)) {
				 //更新稽核最后一笔结束时间
				 $this->db->where('id',$l_audit['id']);
  				 $this->db->set('end_date',$now_date);
  				 $this->db->update('k_user_audit');

				 $this->db->where('site_id',SITEID);
				 $this->db->where('id <=',$l_audit['id']);
				 $this->db->where('type',1);
				 $this->db->where('uid',$uid);
				 $this->db->set('type','2');
				 $this->db->update('k_user_audit');
				//写入稽核日志
				$data_auto = array();
				$data_auto['update_date'] = $now_date;
				$data_auto['uid'] = $uid;
				$data_auto['site_id'] = SITEID;
				$data_auto['username'] = $user['username'];
				$data_auto['content'] = '會員：'.$user['username'].' 餘額小於放寬額度 清除稽核點 ('.$now_date.'之前的稽核點已清除)';
				$this->db->insert('k_user_audit_log',$data_auto);
				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					echo "<script>alert('交易失败,错误代码cw0005');window.close();</script>";exit;
				}
			}elseif (!empty($l_audit) && $l_audit['id']) {
				//存在即更新上一次终止时间
				$this->db->where('id',$l_audit['id']);
  				$this->db->set('end_date',$now_date);
  				$this->db->update('k_user_audit');
				if($this->db->affected_rows() != 1){
					$this->db->trans_rollback();
					echo "<script>alert('交易失败,错误代码cw0006');window.close();</script>";exit;
				}
			}
			$data_e = array();
			$data_e['source_type'] = 2;
			$data_e['source_id'] = $iMoney['id'];
			$data_e['uid'] = $uid;
			$data_e['type'] = 1;
			$data_e['is_ct'] = 1;//线上入款有常态稽核无综合稽核
			$data_e['site_id'] = SITEID;
			$data_e['deposit_money'] = $iMoney['deposit_num'];//存款金额没优惠
			$data_e['username'] = $user['username'];
			$data_e['begin_date'] = $now_date;
			$data_e['relax_limit'] = $aud['ol_ct_fk_audit'];//放宽额度
			if ($aud['ol_is_ct_audit']) {
				$data_e['normalcy_code'] = $aud['ol_ct_audit']*$iMoney['deposit_num']*0.01;//常态稽核
			}
			if ($aud['ol_is_zh_audit']) {
				$data_e['type_code_all'] = $aud['ol_zh_audit']*($iMoney['deposit_num']+$iMoney['other_num']+$iMoney['favourable_num']);
			}
			$data_e['atm_give'] = $iMoney['other_num'];//汇款优惠
			$data_e['catm_give'] = $iMoney['favourable_num'];//存款优惠
			$data_e['expenese_num'] = $aud['ol_ct_xz_audit'];//行政费率
			$this->db->insert('k_user_audit',$data_e);
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				echo "<script>alert('交易失败,错误代码cw0007');window.close();</script>";exit;
			}
			// 发送用户信息
			$dataM=array();
			$dataM['type'] = 3;//表示出入款类型
			$dataM['site_id'] = SITEID;
			$dataM['index_id'] = INDEX_ID;
			$dataM['uid'] = $uid;
			$dataM['level'] = 2;
			$dataM['msg_title'] =  $user['username'].','."线上入款";
			$dataM['msg_info'] = $user['username'].','."线上入款" . $iMoney['deposit_num'] .'元,其他優惠：'.$iMoney['other_num']. "元成功, 祝您游戏愉快！";
			$this->db->insert('k_user_msg',$dataM);
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				echo "<script>alert('交易失败,错误代码cw0008');window.close();</script>";exit;
			}else{
				$this->db->trans_commit();
				echo '<script>alert("支付成功");window.close();</script>';
			}
		}
	}
	/*
	**获取视讯余额
	*/
	public function getallbalance($username){
   		$games = new Games;
   		$loginname = $_SESSION['username']?$_SESSION['usernmae']:$username;
   		$data = $games->GetAllBalance($loginname);
		$copyright = $this->Common_model->get_copyright();
		$list = explode(',',$copyright['video_module']);
		$result = json_decode($data);
		$data = json_decode($data,true);
		if ($result->data->Code == 10017) {
			$data_u = array();
			foreach ($list as $key => $value) {
				$strstatus = $value.'status';
				$strbalance = $value.'balance';
				if (!empty($result->data->$strstatus)) {
					$data_u[$value.'_money'] = floatval($result->data->$strbalance);
				}
			}
			if(!empty($data_u)){
				$this->db->from('k_user');
				$this->db->where('site_id',SITEID);
				$this->db->where('uid',$_SESSION['uid']);
				$this->db->set($data_u);
				$this->db->update();
			}
			
		}
    }

   //通过id获取第三方支付域名
    public function get_payurl($id){
    		//从redis上获取所有开启的银行卡信息
				$redis = new Redis();
				$redis->connect(REDIS_HOST,REDIS_PORT);
			  $vdata = $redis->hgetall('pay_type');
				if (empty($vdata)) {
					//redis中数据为空 从数据库读取
					$map['table'] = 'k_online_bank_cate';
					$map['select'] = "pay_url";
					$map['where']['state'] = 1;
					$map['where']['id'] = $id;
					$mdata = $this->rfind($map);
					$pay_url = $mdata['pay_url'];
				 }else{
				 	$result =array();
					$vdata = str_replace('--', '"', $vdata);
					foreach($vdata as $key=>$value){
					  $result[$key] = json_decode($value,true);//转数组
					}
					foreach ($result as $k => $v) {
						 if($v['id'] == $id){
						 	  $pay_url = $v['pay_url'];
						 }
					}
				}
				return $pay_url;
    }

	/**
	* 通过id获取第三方支付所有银行信息
	* @param  int    $id      第三方类型ID
	* @return array  $result  银行信息 
	**/
	public function get_bankinfo($id){
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
		}else{
			$result =array();
			$vdata = str_replace('--', '"', $vdata);
			foreach($vdata as $key=>$value){
				$temp= json_decode($value,true);//转数组
				$result[] = array('bank' =>$temp['bank_name'],'values' => $temp['values']);
			}
		}

		return $result;
	}
	/**
	* 通过id获取第三方支付类型名字
	* @param  int    $type    第三方类型ID
	* @return array  $action  第三方支付类型名字
	**/
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

				break;
			case '14':
				$action =  "Dinpay";
			   break;
		}
		return $action;
	}
}