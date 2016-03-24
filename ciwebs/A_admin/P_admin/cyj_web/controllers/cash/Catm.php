<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catm extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Catm_model');
		$this->add('siteid',$_SESSION['site_id']);
	}

	public function index()
	{
		$username = $this->input->get('username');

		if (!empty($username)) {
			$map['table'] = 'k_user';
			$map['where']['username'] = $username;
			$map['where']['site_id'] = $_SESSION['site_id'];
			$user = $this->Catm_model->rfind($map);
			if (empty($user) || empty($user['level_id'])) {
			    showmessage('会员不存在',URL.'/cash/catm/index',0);
			}else{
				//获取优惠稽核信息
                $this->add('discount',$this->get_cash_config($user['level_id']));
			}
            $this->load->library('video/Games');
			$games = new Games();
		    $data = $games->GetAllBalance($user["username"]);
		    $list = array('ag','og','mg','ct','bbin','lebo','pt');
			$result = json_decode($data);

			if ($result->data->Code == 10017) {
			    $data_u = array();
				if (!empty($result->data->ogstatus)) {
					$data_u['og_money'] = floatval($result->data->ogbalance);
				}
				if (!empty($result->data->agstatus)) {
					$data_u['ag_money'] = floatval($result->data->agbalance);
				}
				if (!empty($result->data->mgstatus)) {
					$data_u['mg_money'] = floatval($result->data->mgbalance);
				}
				if (!empty($result->data->ctstatus)) {
					$data_u['ct_money'] = floatval($result->data->ctbalance);
				}
				if (!empty($result->data->bbinstatus)) {
					$data_u['bbin_money'] = floatval($result->data->bbinbalance);
				}
				if (!empty($result->data->lebostatus)) {
					$data_u['lebo_money'] = floatval($result->data->lebobalance);
				}
				if (!empty($result->data->ptstatus)) {
					$data_u['pt_money'] = floatval($result->data->ptbalance);
				}
				if (!empty($result->data->bjstatus)) {
					$data_u['bj_money'] = floatval($result->data->bjbalance);
				}

                if (!empty($data_u)) {
                    $map_u = array();
		            $map_u['table'] = 'k_user';
		            $map_u['where']['uid'] = $user['uid'];
		            $map_u['where']['site_id'] = $_SESSION['site_id'];
					$this->Catm_model->rupdate($map_u,$data_u);
                }
			}
		    $this->add('user',$user);
		}

		$this->add('site_id',$_SESSION['site_id']);

		$this->display('cash/catm_index.html');
	}

	//人工存款
	public function catm_do(){
	    $dtype = $this->input->post('op_type'); //1存款2取款
	    $uid = $this->input->post('userid');
	    $amount = $this->input->post('amount');//存款金额
	    $amount = empty($amount)?0:$amount;
	    $c_remark = $this->input->post('c_remark');
	    $type_memo = $this->input->post('type_memo');
	    $username = $this->input->post('username');

	    if (empty($uid)) {
	        showmessage('请先查询会员',URL.'/cash/catm/index',0);
	    }
	    if (empty($dtype)) {
	        showmessage('参数错误',URL.'/cash/catm/index',0);
	    }

	    if ($_SESSION['quanxian'] != 'sadmin' && $amount > $_SESSION['catm_max']) {
	        showmessage('金额超出你的权限范围，金额要小于'.$_SESSION['catm_max'],URL.'/cash/catm/index',0);
	    }


	    //直接跳到取款
	    if ($dtype == 2) {
	    	$delete_audit = $this->input->post('delete_audit');//是否清楚稽核
	        $this->atm_do($uid,$amount,$type_memo,$c_remark,$delete_audit,$username);
	    }

	    $agent_id = $this->input->post('agent_id');
	    $ifSp = $this->input->post('ifSp');
	    $sp_other = $this->input->post('sp_other');
	    $ifSp_other = $this->input->post('ifSp_other');
	    $spAmount = $this->input->post('spAmount');
	    $isComplex = $this->input->post('isComplex');
	    $ComplexValue = $this->input->post('ComplexValue');

	    $isnormality = $this->input->post('isnormality');

        $arr = array();
	    $arr['op_type'] = $this->input->post('op_type');
        $arr['line_ct_xz_audit'] = $this->input->post('line_ct_xz_audit');
	    $arr['expenese_num'] = $this->input->post('expenese_num');//行政
	    $arr['relax_limit'] = $this->input->post('relax_limit');//放宽额度
	    $arr['is_rebate'] = $this->input->post('c_is_rebate');//是否退佣


	    $catmArray = array('人工存入', '存款優惠', '负数额度归零', '取消出款', '其他', '体育投注余额', '返点优惠', '活动优惠');

	    if ($ifSp == 1) {
			if (floatval($sp_other) < 0 || !is_numeric($sp_other)) {
				showmessage('存款優惠金额格式不正确！',URL.'/cash/catm/index',0);
			}
		}
		if ($ifSp_other == 1) {
			if (floatval($spAmount) < 0 || !is_numeric($spAmount)) {
				showmessage('匯款優惠金额格式不正确！',URL.'/cash/catm/index',0);
			}
		}
		if ($isComplex == 1) {
			if (floatval($ComplexValue) < 0 || !is_numeric($ComplexValue)) {
		        showmessage('綜合打碼量格式不正确！',URL.'/cash/catm/index',0);
			}
		}

        $deposit_money = $amount;
		//存款优惠
		if ($_POST['ifSp'] == "1") {
			$deposit_money = $deposit_money + $sp_other;
			$give_count = $sp_other;
			$arr['catm_give'] = $sp_other; //存款优惠
		}else{
			$arr['catm_give'] = 0;
		}
		//汇款优惠
		if ($ifSp_other == '1') {
			$deposit_money = $deposit_money + floatval($spAmount);
			$arr['atm_give'] = $spAmount; //汇款优惠
			$give_count = $give_count + $spAmount;
		}else{
			$arr['atm_give'] = 0;
		}

		//综合打码量
		if ($isComplex == '1') {
			$is_code_count = $isComplex;
			$code_count = $ComplexValue; //综合打码量
		} else {
			$is_code_count = 0;
			$code_count = 0;
		}
		//常态打码
		if (!empty($isnormality)) {
			$isnormality = $isnormality * $amount * 0.01;
			$is_isnormality = 1;
		} else {
			$is_isnormality = 0;
			$isnormality = 0;
		}


        $arr['amount'] = $amount;
        $arr['give_count'] = $give_count;
        $arr['spAmount'] = $spAmount;
        $arr['sp_other'] = $sp_other;
        $arr['code_count'] = $code_count;
        $arr['ifSp'] = $ifSp;
        $arr['ifSp_other'] = $ifSp_other;
        $arr['is_code_count'] = $is_code_count;
        $arr['is_isnormality'] = $is_isnormality;
        $arr['isnormality'] = $isnormality;
        $arr['deposit_money'] = $deposit_money;
		$catmT = $type_memo - 1;
		$arr['remark'] = $catmArray[$catmT] . ','.$c_remark ; //备注

		if($this->Catm_model->catm_do($uid,$arr,$type_memo)){
			 $log['log_info'] = '人工存入,ID：'.$uid;
			 $log['type'] = 1;
			 $log['uname'] = $username;
	         $this->Catm_model->Syslog($log);
	         showmessage('人工存入成功！',URL.'/cash/Catm/index');
		}else{
			 showmessage('人工存入失败！',URL.'/cash/Catm/index',0);
		}
	}

	//人工取款
	public function atm_do($uid,$amount,$type_memo,$remark,$delete_audit,$uname){
        if (empty($amount) || ($amount < 0)) {
            showmessage('取款金额不能为0！',URL.'/cash/Catm/index',0);
        }
        $map = array();
        $map['table'] = 'k_user';
        $map['select'] = 'money';
        $map['where']['uid'] = $uid;
        $user = $this->Catm_model->rfind($map);
        if (floatval($user['money']) < floatval($amount)) {
			showmessage('取款金额不足！',URL.'/cash/Catm/index',0);
		}
		if($this->Catm_model->atm_do($uid,$amount,$type_memo,$remark,$delete_audit)){
			 $log['log_info'] = '人工取款,ID：'.$uid;
			 $log['type'] = 1;
			 $log['uname'] = $uname;
	         $this->Catm_model->Syslog($log);
	         showmessage('人工取款成功！',URL.'/cash/Catm/index');
		}else{
			 showmessage('人工取款失败！',URL.'/cash/Catm/index',0);
		}

	}

	//人工额度转换
	public function conversion(){
		$this->load->library('video/Games');
        $username = $this->input->get('username');

        if (!empty($username)) {
            $map = array();
	        $map['table'] = 'k_user';
	        $map['where']['username'] = $username;
	        $map['where']['site_id'] = $_SESSION['site_id'];
		    $user = $this->Catm_model->rfind($map);
		    $games = new Games();
		    $data = $games->GetAllBalance($user["username"]);
		    $list = array('ag','og','mg','ct','bbin','lebo','pt');
			$result = json_decode($data);

			if ($result->data->Code == 10017) {
			    $data_u = array();
				if (!empty($result->data->ogstatus)) {
					$data_u['og_money'] = floatval($result->data->ogbalance);
				}
				if (!empty($result->data->agstatus)) {
					$data_u['ag_money'] = floatval($result->data->agbalance);
				}
				if (!empty($result->data->mgstatus)) {
					$data_u['mg_money'] = floatval($result->data->mgbalance);
				}
				if (!empty($result->data->ctstatus)) {
					$data_u['ct_money'] = floatval($result->data->ctbalance);
				}
				if (!empty($result->data->bbinstatus)) {
					$data_u['bbin_money'] = floatval($result->data->bbinbalance);
				}
				if (!empty($result->data->lebostatus)) {
					$data_u['lebo_money'] = floatval($result->data->lebobalance);
				}

				if (!empty($result->data->ptstatus)) {
					$data_u['pt_money'] = floatval($result->data->ptbalance);
				}

	            $map_u = array();
	            $map_u['table'] = 'k_user';
	            $map_u['where']['uid'] = $user['uid'];
	            $map_u['where']['site_id'] = $_SESSION['site_id'];
				$this->Catm_model->rupdate($map_u,$data_u);
				$this->add('data_u',$data_u);
			}else{
	            showmessage('视讯余额获取失败',URL.'/cash/Catm/conversion',0);
		    }

		    $this->add('user',$user);
        }

	    $this->display('cash/conversion.html');
	}

	//额度转换出来
	public function conversion_do(){
        $uid = $this->input->post('userid');
        $username = $this->input->post('username');
        $amount = $this->input->post('amount');
        $type_out = $this->input->post('type_out');
        $type_in = $this->input->post('type_in');
        $c_remark = $this->input->post('c_remark');
        $index_id = $this->input->post('index_id');

        if (empty($username)){
			showmessage('请先查询会员',URL.'/cash/Catm/conversion',0);
		}
		if ((empty($amount))||($amount=="0")){
			showmessage('请输入转换金额',URL.'/cash/Catm/conversion',0);
		}
		if ($amount<0){
			showmessage('转换金额必须大于1元！',URL.'/cash/Catm/conversion',0);
		}
		  //权限金额判断
	    if ($_SESSION['quanxian'] != 'sadmin' && $amount > $_SESSION['catm_max']) {
		    showmessage('金额超出你的权限范围，金额要小于'.$_SESSION['catm_max'],URL.'/cash/catm/index',0);
		}

		//转换类型
		$g_type_arr = array('og', 'ag', 'mg', 'ct','pt', 'lebo', 'bbin', 'sport');
		$list = array('ag','og','mg','ct','pt','bbin','lebo');

		$tc_type = $g_type = "";
		if (!in_array($type_out, $g_type_arr) || !in_array($type_in, $g_type_arr)) {
		    showmessage('类型错误',URL.'/cash/Catm/conversion',0);
		}

		if ($type_out == $type_in) {
			showmessage('请选择不同平台！',URL.'/cash/Catm/conversion',0);
		}

		if ($type_out == "sport" && $type_in != "sport") {
			$tc_type = "IN";
			$g_type = $type_in;
		}

		if ($type_in == "sport" && $type_out != "sport") {
			$tc_type = "OUT";
			$g_type = $type_out;
		}
		if (empty($tc_type)) {
			showmessage('额度转换,只能在系统余额和视讯余额之间转换',URL.'/cash/Catm/conversion',0);
		}
		$credit = floatval($amount);
		if ($credit < 0) {
			showmessage('额度转换,额度转换必须大于1',URL.'/cash/Catm/conversion',0);
		}
		$this->load->library('video/Games');
		$games = new Games();
		//转入
        if ($tc_type == 'IN') {
            $this->Catm_model->conversion_in($uid,$credit,$g_type);
        }

		$data = $games->TransferCredit($username, $g_type, $tc_type, $credit);
		if (empty($data)) {
			$log['log_info'] = '会员：'.$username.',人工额度转换失败';
	        $this->Catm_model->Syslog($log);
			showmessage('额度转换失败,错误代码R009',URL.'/cash/Catm/conversion',0);
		}
		$result = json_decode($data);
		if ($result->data->Code == 10006) {
			$data = $games->CreateAccount($username, $_POST["agent_id"], $g_type,$index_id);
			if (!empty($data)) {
				$result = json_decode($data);
				if ($result->data->Code != 10011) {
					showmessage('额度转换失败,错误代码R010',URL.'/cash/Catm/conversion',0);
				} else {
					//用户添加成功转账重试
					$data = $games->TransferCredit($username, $g_type, $tc_type, $credit);
					if (empty($data)) {
						showmessage('额度转换失败,错误代码R011',URL.'/cash/Catm/conversion',0);
					}
					$result = json_decode($data);
				}
			}
		}

		if ($result->data->Code == 10013) {
			if ($tc_type == "OUT") {
				if(true != $this->Catm_model->conversion_out($uid,$credit,$g_type)){
				     $log['log_info'] = '会员：'.$username.',人工额度转换失败';
				     $log['type'] = 1;
			         $log['uname'] = $username;
	                 $this->Catm_model->Syslog($log);
			         showmessage('额度转换失败,错误代码R009',URL.'/cash/Catm/conversion',0);
				}
			}

			$log['log_info'] = '会员：'.$username.',人工额度转换成功';
			$log['type'] = 1;
			$log['uname'] = $username;
	        $this->Catm_model->Syslog($log);
			showmessage('额度转换成功',URL.'/cash/Catm/conversion');
		} else {
			showmessage('额度转换失败,错误代码R4',URL.'/cash/Catm/conversion',0);
		}
	}

	//额度转换稽核
	public function conversion_log(){
	    $start_date = $this->input->get('start_date');
	    $end_date = $this->input->get('end_date');
	    $status = $this->input->get('status');
	    $username = $this->input->get('username');
	    $page = $this->input->get('page');
	    $start_date = $this->input->get('start_date');

		$map ="site_id = '".$_SESSION['site_id']."' and credit > 0 ";

		//时间判断
		if (!empty($start_date)) {
		    $s_date = $start_date;
		    $start_date = $start_date.' 00:00:00';
		}else{
		    $s_date = date('Y-m-d');
		    $start_date = date("Y-m-d").' 00:00:00';
		}

        if($status == '1' || $status == '0'){
            $map .= " and status = '".$status."' ";
        }

		if (!empty($end_date)) {
		   $e_date = $end_date;
		   $end_date = $end_date.' 23:59:59';
		}else{
		   $e_date = date('Y-m-d');
		   $end_date = date("Y-m-d").' 23:59:59';
		}

		//查询时间判断
	    about_limit($s_date,$e_date);

		$map .= " and update_time >='".$start_date."' and update_time <= '".$end_date."'";

		//账号检索
		if($username){
		    $user_str = $username.'%';
	        $map .= " and username like '".$user_str."'";
		}
        $db_model = array();
		$db_model['tab'] = 'game_cash_record';
        $db_model['type'] = 3;

        $count = $this->Catm_model->mcount($map,$db_model);
		$perNumber = 50;
		$totalPage=ceil($count/$perNumber);
		$page=isset($page)?$page:1;
		$page=($totalPage<$page)?1:$page;

		$startCount=($page-1)*$perNumber;

		$limit=$startCount.",".$perNumber;
		//查询表信息
		$data=$this->Catm_model->get_conversion_log($map,$limit);

        $this->add('s_date',$s_date);
        $this->add('e_date',$e_date);
        $this->add('username',$username);
        $this->add('status',$status);
        $this->add('data',$data);
		$this->add('page',$this->Catm_model->get_page('k_user_bank_out_record',$totalPage,$page));
	    $this->display('cash/conversion_log.html');
	}

		//人工入款批量
	public function catm_operation(){
            $map = array();
            $map['table'] = 'k_user_level';
            $map['select'] = 'id,level_des,level_name';
            $map['where']['site_id'] = $_SESSION['site_id'];
            $map['order'] = 'id ASC';
            $level = $this->Catm_model->rget($map);
            $select='<select name="tier" >';
            foreach ($level as $k=>$v){
                $select .= "<option value=".$v['id'].">".$v['level_name'].':'.$v['level_des']."</option>";
            }
            $select .= '</select>';
            $this->add('select',$select);
            $this->display('cash/catm_operation.html');
	}

	//批量人工入款处理
	public function catm_operation_do(){
        $type_batch = intval($this->input->post('type_batch'));
        $tier = intval($this->input->post('tier'));
        $username = $this->input->post('username');
        $type_memo = $this->input->post('type_memo');

        if($type_batch=='1'){
            if (empty($type_memo) || empty($username)) {
                showmessage('参数错误',URL.'/cash/Catm/cash_operation',0);
            }else{
                $data['username'] = $username;
            }
        }else{
            if ($tier<1) {
                showmessage('参数错误',URL.'/cash/catm/catm_operation',0);
            }else{
                $namearr = $this->Catm_model->GetUserName($tier);
                if($namearr){
                    $n=count($namearr);
                    if($n>3000){
                        showmessage('一次操作会员数不能多于3000个,该层级有'.$n.'个会员',URL.'/cash/catm/catm_operation',0);
                    }else{
                        $data['username']=join($namearr,',');
                    }
                }else{
                   showmessage('层级会员数不能少于1个',URL.'/cash/catm/catm_operation',0);
                }
            }
        }
        $c_remark= $this->input->post('c_remark');//备注
        $data['type_memo'] = $type_memo;//类型
        $ifSp_other = $this->input->post('ifSp_other');
        $data['ifSp_other'] = empty($ifSp_other)?0:$ifSp_other;
        $spAmount = $this->input->post('spAmount');//汇款优惠
        $data['spAmount'] = empty($spAmount)?0:$spAmount;
        $data['spAmount'] = empty($data['ifSp_other'])?0:$spAmount;

        $ifSp = $this->input->post('ifSp');
        $data['ifSp']= empty($ifSp)?0:$ifSp;
        $sp_other = $this->input->post('sp_other');//存款优惠
        $data['sp_other'] = empty($sp_other)?0:$sp_other;
        $data['sp_other'] = empty($data['ifSp'])?0:$data['sp_other'];

        $amount = $this->input->post('amount');//存款金额
        $data['amount'] = empty($amount)?0:$amount;

        $isComplex = $this->input->post('isComplex');//是否综合打码
        $data['isComplex'] = empty($isComplex)?0:$isComplex;
        $ComplexValue = $this->input->post('ComplexValue');//综合打码
        $data['ComplexValue'] = empty($ComplexValue)?0:$ComplexValue;
        $data['ComplexValue'] = empty($data['isComplex'])?0:$data['ComplexValue'];

        $is_isnormality = $this->input->post('is_isnormality');//是否常态打码
        $data['is_isnormality'] = empty($is_isnormality)?0:$is_isnormality;
        $isnormality = $this->input->post('isnormality');//常态稽核

        $data['isnormality'] = empty($data['is_isnormality'])?0:$data['amount'];

        $c_is_rebate = $this->input->post('c_is_rebate');//是否写入退佣
        $data['c_is_rebate'] = empty($c_is_rebate)?0:$c_is_rebate;

        $catmArray = array('人工存入','存款優惠','负数额度归零','取消出款','其他','体育投注余额','返点优惠','活动优惠');

		$catmT = $type_memo-1;
	    $data['remark'] = $catmArray[$catmT].','.$c_remark;//备注

	    $info = $this->Catm_model->cash_operation($data);
	    if ($info === 'E0') {
	        showmessage('会员不存在!',URL.'/cash/Catm/catm_operation',0);
	    }else{
            showmessage('会员批量处理成功',URL.'/cash/Catm/catm_operation');
	    }
	}



	//人工存入取出记录
	public function catm_record(){
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $timearea = $this->input->get('timearea');
        $timearea = empty($timearea)?0:$timearea;
        $otype = $this->input->get('otype');//查询类别
        $username = $this->input->get('username');
        $page = $this->input->get('page');
        $index_id = $this->input->get('index_id');

		//时间判断
		if (!empty($start_date)) {
			$s_date = $start_date = $start_date;
		}else{
			$s_date = $start_date = date("Y-m-d");
		}

		if (!empty($end_date)) {
			$e_date = $end_date = $end_date;
		}else{
			$e_date = $end_date = date("Y-m-d");
		}

		//查询时间判断
	    about_limit($s_date,$e_date);

		$start_date = strtotime($start_date.' 00:00:00') - $timearea*3600;
		$end_date = strtotime($end_date.' 23:59:59') - $timearea*3600;
		$start_date = date('Y-m-d H:i:s',$start_date);
		$end_date = date('Y-m-d H:i:s',$end_date);

		$map = "site_id = '".$_SESSION['site_id']."' and updatetime >='".$start_date."' and updatetime <= '".$end_date."'";

		// p($map);
		if(!empty($otype)){
			$typeArr = explode('-', $otype);
			//判断第二个type是否存在
			if (!empty($typeArr[2])) {
			   $map.= " and (type = '".$typeArr[0]."' and catm_type in ('".$typeArr[1]."','".$typeArr[2]."','".$typeArr[3]."'))";
			   $otype = '1-1';
			}elseif (empty($typeArr[1])) {
			   $map.= " and type = '".$typeArr[0]."'";
			}else{
			   $map.= " and type = '".$typeArr[0]."' and catm_type='".$typeArr[1]."'";
			}

		}
		//账号检索
		if($username){
			$map.= " and username = '".$username."' ";
			$this->add('username',$username);
		}
		if (!empty($index_id)) {
		    $map.= " and index_id = '".$index_id."' ";
		    $this->add('index_id',$index_id);
		}

        $db_model = array();
		$db_model['tab'] = 'k_user_catm';
        $db_model['base_type'] = 1;
        $count = $this->Catm_model->mcount($map,$db_model);


		//分页
		$perNumber = 50;
		$totalPage=ceil($count/$perNumber); //计算出总页数
		$page=isset($page)?$page:1;
		if($totalPage<$page){
		  $page=1;
		}
		$startCount=($page-1)*$perNumber;
		$limit=$startCount.",".$perNumber;

		$data = $this->Catm_model->get_catm_record($map,$limit);
        $catm_money = $catm_give = $atm_give = 0;
		foreach ($data as $key => $val) {

		    $catm_money = $catm_money + $val['catm_money'];
		    $catm_give = $catm_give + $val['catm_give'];
		    $atm_give = $atm_give + $val['atm_give'];

		    $data[$key]['catm_type_zh'] = $this->catm_type($val['type'],$val['catm_type']);
		}

		  //多站点判断
	    if (!empty($_SESSION['index_id'])) {
	    	$this->add('sites_str','站点：'.$this->Catm_model->select_sites());
	    }

        $this->add('data',$data);
        $this->add('otype',$otype);
        $this->add('catm_money',$catm_money);
        $this->add('catm_give',$catm_give);
        $this->add('atm_give',$atm_give);

        $this->add('catm_count',$this->Catm_model->catm_count($map));
        $this->add('page', $this->Catm_model->get_page('k_user_catm',$totalPage,$page));
        $this->add('index_id',$index_id);
	    $this->add('s_date',$s_date);
	    $this->add('e_date',$e_date);
	    $this->add('timearea',$timearea);
        $this->display('cash/catm_record.html');
	}

	//综合打码 常态打码
	public function complex_do(){
        $type = $this->input->post('complex_lx');
        $complex = $this->input->post('complex');
        $id = $this->input->post('id');//数据ID
        if (empty($id)) {
            showmessage('参数错误',URL.'/cash/catm/catm_record',0);
        }
        $arr = array();
        $arr['id'] = $id;
        $arr['complex'] = intval($complex);
        if ($type == 'complex') {
            //综合稽核
            if ($this->Catm_model->complex_do($arr)) {

		        $log['log_info'] = '人工存款,ID：'.$id.' 综合稽核修改成功';
	            $this->Catm_model->Syslog($log);
                showmessage('综合稽核更改成功',URL.'/cash/catm/catm_record');
            }else{
            	showmessage('综合稽核更改失败',URL.'/cash/catm/catm_record',0);
            }
        }elseif ($type == 'normality') {
        	//常态稽核
        	if ($this->Catm_model->normality_do($arr)) {
        		$log['log_info'] = '人工存款,ID：'.$id.' 常态稽核修改成功';
	            $this->Catm_model->Syslog($log);
                showmessage('常态稽核更改成功',URL.'/cash/catm/catm_record');
            }else{
            	showmessage('常态稽核更改失败',URL.'/cash/catm/catm_record',0);
            }

        }
	}



    //读取支付设定优惠信息
	function get_cash_config($level_id){
        $map = array();
        $map['table'] = 'k_user_level';
        $map['select'] = 'RMB_pay_set';
        $map['where']['id'] = $level_id;
        $map['where']['site_id'] = $_SESSION['site_id'];
        $pay_id = $this->Catm_model->rfind($map);

        $map_v = array();
        $map_v['table'] = 'k_cash_config_view';
        $map_v['where']['id'] = $pay_id['RMB_pay_set'];
        $map_v['where']['site_id'] = $_SESSION['site_id'];

        return $this->Catm_model->rfind($map_v);
	}


    //类型
	function catm_type($type,$catm_type){
		switch (true) {
			case ($type == 1 && $catm_type == 1):
				return '人工存入';
				break;
			case ($type == 1 && $catm_type == 2):
				return '存款優惠';
				break;
			case ($type == 1 && $catm_type == 3):
				return '负数额度归零';
				break;
			case ($type == 1 && $catm_type == 4):
				return '取消出款';
				break;
			case ($type == 1 && $catm_type == 5):
				return '其他';
				break;
			case ($type == 1 && $catm_type == 6):
				return '体育投注余额';
				break;
			case ($type == 1 && $catm_type == 7):
				return '返点优惠';
				break;
			case ($type == 1 && $catm_type == 8):
				return '活动优惠';
				break;
			case ($type == 2 && $catm_type == 1):
				return '重复出款';
				break;
			case ($type == 2 && $catm_type == 2):
				return '公司入款存误';
				break;
			case ($type == 2 && $catm_type == 3):
				return '公司负数回冲';
				break;
			case ($type == 2 && $catm_type == 4):
				return '手动申请出款';
				break;
			case ($type == 2 && $catm_type == 5):
				return '扣除非法下注派彩';
				break;
			case ($type == 2 && $catm_type == 6):
				return '放弃存款优惠';
				break;
			case ($type == 2 && $catm_type == 7):
				return '其他';
				break;
			case ($type == 2 && $catm_type == 8):
				return '体育投注余额';
				break;
		}
	}


}
