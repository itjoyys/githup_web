<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cash extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('member/Cash_model');
		$this->load->model('Common_model');
		$this->Common_model->login_check($_SESSION['uid']);

	}

	//银行交易 线上存款
	public function setmoney(){
		$deposit = $this->Cash_model->get_setmoney();    // 获取存款文案信息
		$this->add('deposit',$deposit);
		$this->add('shiwan',$_SESSION['shiwan']);
		$this->display('member/setmoney.html');
	}

	//额度转换
	public function zr_money(){
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
	    $MinInMoney = 10;
	    if ($userinfo['shiwan'] == 1) {
	        echo "<script>";
	        echo 'alert("试玩账号不能存取款，请注册正式账号！");window.history.go(-1)';
	        echo "</script>";
	        exit();
	    }

		//视讯配置
		$video_config = $this->Cash_model->get_video_config();
		$allmoney = $userinfo['money'];
		foreach ($video_config as $key => $val) {
			//去掉bj
            if ($val == 'eg') {
            	unset($video_config[$key]);
            }else{
                $allmoney += $userinfo[$val.'_money'];
            }
		}
		$_SESSION['edcashtoken'] = md5(round(00000000,55555555));
    	$allmoney = number_format($allmoney, '2');
		$this->add('video_config',$video_config);
		$this->add('userinfo',$userinfo);
		$this->add('MinInMoney',$MinInMoney);
		$this->add('allmoney',$allmoney);
		$this->add('edcashtoken',$_SESSION['edcashtoken']);
		$this->display('member/zr_money.html');
	}


	//额度转换的数据处理
	public function edzh(){
		if(empty($_SESSION['edcashtoken']) || $_SESSION['edcashtoken'] != $_POST['edcashtoken']){
			echo json_encode(array("status" => 22, "info" => "请刷新页面，重试！"));
			die();
		}
		unset($_SESSION['edcashtoken']);
		if(SITEID == 't' &&  $_SESSION['username'] != 'pkaaanb') {
			echo json_encode(array("status" => 21, "info" => "测试站点，不允许额度转换"));
			die();
		}

		if ($_SESSION['shiwan'] == 1) {
	       echo json_encode(array("status" => 20, "info" => "试玩账号不能存取款，请注册正式账号！"));exit;
	    }
		$uid = @$_SESSION['uid'];
		$username = @$_SESSION['username'];
		$userinfo = $this->Common_model->get_user_info($uid);

		$cash_record = $this->Cash_model->get_cash_record($userinfo['uid']);  //获取金额交易记录

		if ($cash_record && !empty($cash_record['cash_date'])) {
				$time = time() - strtotime($cash_record['cash_date']);
				if ($time < 60) {
					echo json_encode(array("status" => 17, "info" => "请在" . (60 - $time) . "秒后操作"));exit;
				}
		}

		//$g_type_arr = array("og", "ag", "mg", "ct", "lebo", "bbin", "sport");

		$copyright = $this->Common_model->get_copyright();
		$list = explode(',',$copyright['video_module']);
		$g_type_arr = array('sport');
		$g_type_arr = array_merge($g_type_arr,$list);


		$trtype1 = trim($this->input->post('trtype1'));
		$trtype2 = trim($this->input->post('trtype2'));
		if($trtype1 == $trtype2){
			echo json_encode(array("status" => 19, "info" => "转入转出平台不能相同，请重新选择" ));exit;
		}
		//$list = array('ag','og','mg','ct','bbin','lebo');
		foreach($list as $val){
			if($val == 'pt'){
					$strval = 'pt_game';
				}else{
					$strval = $val;
				}
			if($this->GetSiteStatus($this->SiteStatus,2,$strval,1)){
				if($trtype1 == $val || $trtype2 == $val){
					$str  = $val."游戏正在进行例行维护！\n请您选择其他游戏！祝您游戏开心！";
					echo json_encode(array("status" => 23, "info" => $str ));exit;
				}
			}
		}

		$tc_type = $g_type = "";
		if (!in_array($trtype1, $g_type_arr) && !in_array($trtype2, $g_type_arr)) {
			echo json_encode(array("status" => 1, "info" => "未知的游戏!"));exit;
		}
		if (empty($username)) {
			echo json_encode(array("status" => 2, "info" => "请登录再进行游戏!"));exit;
		}
		if ($trtype1 == "sport" && $trtype2 != "sport") {
			$tc_type = "IN";
			$g_type = $trtype2;
		}
		if ($trtype2 == "sport" && $trtype1 != "sport") {
			$tc_type = "OUT";
			$g_type = $trtype1;
		}
		if (empty($tc_type)) {
			echo json_encode(array("status" => 3, "info" => "额度转换,只能在系统余额和视讯余额之间转换,视讯余额之间不能直接转换!"));exit;
		}

		$credit = floatval($this->input->post('p3_Amt'));
		if ($credit < 10) {
			echo json_encode(array("status" => 4, "info" => "转换的额度，必须大于10!"));exit;
		}
		$this->load->library('Games');
		$games = new Games();
		if ($tc_type == "IN") {
			$status = $this->Cash_model->conversionIn($credit, $g_type);
			switch ($status) {
				case 5:
					echo json_encode(array("status" => 5, "info" => "额度转换失败,错误代码R002"));exit;
					break;

				case 6:
					echo json_encode(array("status" => 6, "info" => "额度转换失败,错误代码R003"));exit;
					break;

				case 7:
					echo json_encode(array("status" => 7, "info" => "由于网络原因，转账失败，请联系管理员"));exit;
					break;

				case 8:
					echo json_encode(array("status" => 8, "info" => "额度转换失败,错误代码R004"));exit;
					break;

				case 9:
					echo json_encode(array("status" => 9, "info" => "额度转换失败,错误代码R005"));exit;
					break;

				case 10:
					echo json_encode(array("status" => 10, "info" => "额度转换失败,错误代码R006"));exit;
					echo "<script>alert('交易失败,错误代码cw0004');window.close();</script>";exit;
					break;
				case 11:
					echo json_encode(array("status" => 10, "info" => "额度转换失败,错误代码R020"));exit;
					break;
			}
		}

		$data = $games->TransferCredit($username, $g_type, $tc_type, $credit);
		if (empty($data)) {
			echo json_encode(array("status" => 11, "info" => "由于网络原因，转账失败，请联系管理员 "));exit;
		}
		$result = json_decode($data);
		if ($result->data->Code == 10006) {
			if($g_type == 'pt'){$cur="CNY";}
			$data = $games->CreateAccount($username, $userinfo["agent_id"], $g_type,INDEX_ID,$cur);
			if (!empty($data)) {
				$result = json_decode($data);
				if ($result->data->Code != 10011) {
					echo json_encode(array("status" => 12, "info" => "额度转换失败,错误代码R007 "));exit;
				} else {
					//用户添加成功转账重试
					$data = $games->TransferCredit($username, $g_type, $tc_type, $credit);
					if (empty($data)) {
						echo json_encode(array("status" => 13, "info" => "由于网络原因，转账失败，请联系管理员 "));exit;
					}
					$result = json_decode($data);
				}
			}
		}

		if ($result->data->Code == 10013) {
			if ($tc_type == "OUT") {
				$status = $this->Cash_model->conversionOut($credit, $g_type);
				switch ($status) {
					case 14:
						echo json_encode(array("status" => 14, "info" => "额度转换失败,错误代码R008"));exit;
						break;

					case 15:
						echo json_encode(array("status" => 15, "info" => "额度转换失败,错误代码R009 "));exit;
						break;

					case 16:
						echo json_encode(array("status" => 16, "info" => "额度转换失败,错误代码R0010"));exit;
						break;

					case 17:
						echo json_encode(array("status" => 17, "info" => "额度转换失败,错误代码R0011"));exit;
						echo "<script>alert('交易失败,错误代码cw0004');window.close();</script>";exit;
						break;
				}
			}
			echo json_encode(array("status" => 18, "info" => "转账成功 "));exit;
		} else {
			echo json_encode(array("status" => 19, "info" => "转账失败，余额不足或第三方正在维护中 "));exit;
		}

	}

	//线上取款
	public function getmoney(){
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		$this->add('userinfo',$userinfo);
		$this->display('member/getmoney.html');
	}


	//绑定银行卡
	public function hk_money($class,$method){
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		$bank_arr = $this->Cash_model->get_bank_arr();//获取银行列表
		$this->add('userinfo',$userinfo);
		$this->add('copyright',$this->Common_model->get_copyright());
		$this->add('class',$this->router->class);
		$this->add('method',$this->router->method);
		$this->add('bank_arr',$bank_arr);
		$this->display('member/hk_money.html');
		exit;
	}
	//绑定银行卡处理
	public function hk_money_do(){
		$into       = $this->input->post('into');
		$class      = $this->input->post('class');
		$method     = $this->input->post('method');
		$province   = $this->input->post('province');
		$city       = $this->input->post('city');
		$pay_card   = $this->input->post('pay_card');
		$pay_num    = $this->input->post('pay_num');

		$data = array();
		$data["pay_address"] = $province."-".$city;
		$data["pay_card"]    = $pay_card;
		$data['pay_num']     = $pay_num;
		if(preg_match("/([`~!@#$%^&*()_+<>?:\"{},\/;'[\]·~！#￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/",$data["pay_num"])){
			message('银行卡号格式错误！',$method);
		}
		if($into == 'true'){
			$this->db->from('k_user_reg_config');
			$this->db->where('site_id',SITEID);
			$this->db->where('index_id',INDEX_ID);
			$this->db->select('is_banknum');
			$is_banknum = $this->db->get()->row_array();
			if(intval($is_banknum['is_banknum'])===0){
				$this->db->from('k_user');
				$this->db->where('site_id',SITEID);
				$this->db->where('index_id',INDEX_ID);
				$this->db->where('pay_num',$data['pay_num']);
				$result = $this->db->get()->row_array();
				if($result['pay_num']){
					message('该银行卡已经绑定到其他会员！',$method);
				}
			}
			$this->db->where('uid',$_SESSION['uid']);
			$this->db->update('k_user',$data);
			if($this->db->affected_rows()){
				echo '<script>alert("资料修改成功!")</script>';
				echo '<script>top.location.href="'.$method.'";</script>';exit;
			}else{
				message('对不起，由于网络堵塞原因。\\n您提交的汇款信息失败，请您重新提交。',$method);
			}
		}

	}

	public function show(){
		if($_SESSION['shiwan'] == 1){
			echo "试玩账号不能存取款，请注册正式账号！";exit;
		}
		//判断用户是否绑定银行卡
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		if(empty($userinfo['pay_num'])){
			echo "<script>alert('为方便后续能及时申请出款到账，请在入款前设置您的银行卡等信息，请如实填写！');</script>";
    		$this->hk_money($this->router->class,$this->router->method);
		}

		//$this->load->model('member/Audit_model');
		//出款判断
		$status = $this->Cash_model->out_cash_record();
		if(!empty($status['order_num'])){
		    echo '您有订单号：'.$status['order_num'].',出款尚未完成.请勿频繁出款';
		    exit();
		}
		// $type = 'ag,og,mg,ct,bbin,lebo';
		// $end_date = date('Y-m-d H:i:s');
		// $pay_data = $this->Common_model->get_user_level_info($_SESSION['uid']);
		// $audit_data = $this->Audit_model->get_user_audit($userinfo['uid'],$pay_data,$userinfo['username'],$type,$end_date);

		// $count_dis = $audit_data['count_dis'];
		// $count_xz = $audit_data['count_xz'];
		// $out_data = array();
		// $out_data['out_fee'] = $audit_data['out_fee'];//出款手续费
		// $out_data['out_audit'] = $count_dis + $count_xz;//稽核扣除费用
		// $out_data['fav_num'] = $count_dis;
		// unset($_SESSION['out_money']);
		// $_SESSION['out_money'] = $out_data;
		// $this->add('betAll',$audit_data['bet_all']);
		// unset($audit_data['bet_all']);
		// unset($audit_data['count_dis']);
		// unset($audit_data['count_xz']);
		// unset($audit_data['out_fee']);
		// $total = $count_dis + $count_xz + $out_data['out_fee'];
		// $total = sprintf("%.2f", $total);

		// if (!empty($audit_data)) {
		// 	$userAudit = array();
		// 	foreach ($audit_data as $key => $v){
		// 		//当前取款稽核相关信息
		// 		$ak = $v['id'];
		// 		$userAudit[$ak] =  array(
		// 							'id'=>$v['id'],
		// 							'is_pass_zh'=>$v['is_pass_zh'],
		// 							'is_pass_ct'=>$v['is_pass_ct'],
		// 							'is_expenese_num'=>$v['is_expenese_num'],
		// 							'deduction_e'=>$v['deduction_e'],
		// 							'cathectic_sport'=>$v['cathectic_sport'],
		// 							'cathectic_fc'=>$v['cathectic_fc'],
		// 							'cathectic_video'=>$v['cathectic_video']
		// 							);

		// 		$audit_data[$key]['is_pass_zh'] = $this->zh_state($v['is_pass_zh']);
		// 		$audit_data[$key]['is_pass_ct'] = $this->ct_state($v['is_pass_ct']);
		// 		$audit_data[$key]['is_expenese_num'] = $this->xz_state($v['is_expenese_num']);
		// 		$audit_data[$key]['deduction_e'] = sprintf("%.2f",$v['deduction_e']);
		// 	}
		// 	unset($_SESSION['userAudit']);
		// 	$_SESSION['userAudit'] = $userAudit;
		// }

		// $count_xz = sprintf("%.2f", $count_xz);
		// $count_dis = sprintf("%.2f", $count_dis);
		// $this->add('audit_data',$audit_data);
		// $this->add('total',$total);
		// $this->add('count_dis',$count_dis);
		// $this->add('count_xz',$count_xz);
		// $this->add('date',$end_date);
		$this->add('copyright',$this->Common_model->get_copyright());
		$this->display('member/show.html');
	}

	//取款数据请求
	public function show_data(){
		//判断是否ajax
		if(!$this->input->is_ajax_request()){
            //exit('error');
		}
        $this->load->model('member/Audit_model');
		$type = 'ag,og,mg,ct,bbin,lebo';
		$end_date = date('Y-m-d H:i:s');

		$pay_data = $this->Common_model->get_user_level_info($_SESSION['uid']);
		$audit_data = $this->Audit_model->get_user_audit($_SESSION['uid'],$pay_data,$_SESSION['username'],$type,$end_date);

		$count_dis = $audit_data['count_dis'];
		$count_xz = $audit_data['count_xz'];

		$out_data = array();
		$out_data['out_fee'] = $audit_data['out_fee'];//出款手续费
		$out_data['out_audit'] = $count_dis + $count_xz;//稽核扣除费用
		$out_data['fav_num'] = $count_dis;

        //输出json
		$adata = array();
		$adata['count_dis'] = $audit_data['count_dis'];
		$adata['count_xz'] = $audit_data['count_xz'];
		$adata['out_fee'] = $out_data['out_fee'];//出款手续费
		$adata['out_audit'] = $out_data['out_audit'];//稽核扣除费用
		$adata['fav_num'] = $out_data['fav_num'];
		$adata['bet_all'] = $audit_data['bet_all'];

		unset($_SESSION['out_money']);
		$_SESSION['out_money'] = $out_data;

		unset($audit_data['bet_all']);
		unset($audit_data['count_dis']);
		unset($audit_data['count_xz']);
		unset($audit_data['out_fee']);
		$total = $count_dis + $count_xz + $out_data['out_fee'];
		$total = sprintf("%.2f", $total);

		if (!empty($audit_data)) {
			$userAudit = array();
			foreach ($audit_data as $key => $v){
				//当前取款稽核相关信息
				$ak = $v['id'];
				$userAudit[$ak] =  array(
									'id'=>$v['id'],
									'is_pass_zh'=>$v['is_pass_zh'],
									'is_pass_ct'=>$v['is_pass_ct'],
									'is_expenese_num'=>$v['is_expenese_num'],
									'deduction_e'=>$v['deduction_e'],
									'cathectic_sport'=>$v['cathectic_sport'],
									'cathectic_fc'=>$v['cathectic_fc'],
									'cathectic_video'=>$v['cathectic_video']
									);

				$audit_data[$key]['is_pass_zh'] = $this->zh_state($v['is_pass_zh']);
				$audit_data[$key]['is_pass_ct'] = $this->ct_state($v['is_pass_ct']);
				$audit_data[$key]['is_expenese_num'] = $this->xz_state($v['is_expenese_num']);
				$audit_data[$key]['deduction_e'] = sprintf("%.2f",$v['deduction_e']);
			}
			unset($_SESSION['userAudit']);
			$_SESSION['userAudit'] = $userAudit;
		}

		$count_xz = sprintf("%.2f", $count_xz);
		$count_dis = sprintf("%.2f", $count_dis);

        //输出json
		$adata['total'] = $total;
		$adata['count_dis'] = $count_dis;
		$adata['count_xz'] = $count_xz;
		$adata['ndate'] = $end_date;

		//组合表格数据
		$audithtml = "";
		if ($audit_data) {
		    foreach ($audit_data as $key => $v) {
		        $audithtml .= "<tr class=\"m_cen\"><td style=\"width:160px;\">起始:".$v['begin_date']."</td><td rowspan=\"2\">".$v['deposit_money']."</td><td rowspan=\"2\">".($v['atm_give']+$v['catm_give'])."</td><td class=\"hide1\" rowspan=\"2\" style=\"display: none;\">".$v['cathectic_sport']."</td><td class=\"hide1\" rowspan=\"2\" style=\"display: none;\">".($v['cathectic_fc']+0)."</td><td class=\"hide1\" rowspan=\"2\" style=\"display: none;\">".($v['cathectic_video']+0)."</td><td class=\"hide2\" rowspan=\"2\" style=\"display: none;\">".$v['cathectic_sport']."</td><td class=\"hide2\" rowspan=\"2\" style=\"display: none;\">-</td><td class=\"hide2\" rowspan=\"2\" style=\"display: none;\">".($v['cathectic_fc']+0)."</td><td class=\"hide2\" rowspan=\"2\" style=\"display: none;\">-</td><td class=\"hide2\" rowspan=\"2\" style=\"display: none;\">".($v['video_audit']+0).'</td><td class="hide2" rowspan="2" style="display: none;">-</td><td class="hide2" rowspan="2" style="display: none;">'.$v['type_code_all'].'</td><td class="hide2" rowspan="2" style="display: none;">'.$v['is_pass_zh'].'</td><td rowspan="2">'.$v['normalcy_code'].'</td><td rowspan="2">'.$v['relax_limit'].'</td><td rowspan="2">'.$v['is_pass_ct'].'</td><td rowspan="2">'.$v['is_expenese_num'].'</td><td rowspan="2">'.$v['deduction_e'].'</td></tr><tr class="m_cen"><td>结束:'.$v['end_date'].'</td></tr>';
		    }
		}

		$adata['audithtml'] = $audithtml;
		echo json_encode($adata);
	}

	public function get_money_1(){
		if($_SESSION['shiwan'] == 1){
			echo "试玩账号不能存取款，请注册正式账号！";exit;
		}
		if(empty($_SESSION['out_money'])){
			echo "参数错误，请重试！";
		    exit();
		}
		//扣除费用
	    $out_data = array();
	    $out_data = $_SESSION['out_money'];
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		$pay_data = $this->Common_model->get_user_level_info($_SESSION['uid']);
		$userinfo['pay_card'] = $this->Common_model->bank_type($userinfo['pay_card']);
		$this->add('userinfo',$userinfo);
		$this->add('pay_data',$pay_data);
		$this->add('out_data',$out_data);
		$this->add('copyright',$this->Common_model->get_copyright());
		$this->display('member/get_money_1.html');
	}

	public function edit_pass(){
		if($_SESSION['shiwan'] == 1){
			echo "试玩账号不能存取款，请注册正式账号！";exit;
		}
		$ok = $this->input->post('OK');
		if(!empty($ok)){
			$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
			$oldpw=$this->input->post('oldPW1').$this->input->post('oldPW2').$this->input->post('oldPW3').$this->input->post('oldPW4');
			$newpw=$this->input->post('newPW1').$this->input->post('newPW2').$this->input->post('newPW3').$this->input->post('newPW4');
			if(strlen($newpw) != 4){
				exit("<script>alert('密码格式不正确！');history.go(-1);</script>");
			}
			if($userinfo['qk_pwd']!=$oldpw){
				exit("<script>alert('旧取款密码不正确！');history.go(-1);</script>");
			}else{
				$data1 = $this->Cash_model->edit_pass($newpw);
				if($data1){
					echo "<script>alert('修改取款密码成功');window.close();</script>";
				}else{
					echo "<script>alert('修改取款密码失败！');history.go(-1);</script>";
				}
			}
		}
		$this->display('member/edit_pass.html');
	}
	//出款数据处理
	public function getmoneydo(){
		if($_SESSION['shiwan'] == 1){
			echo "试玩账号不能存取款，请注册正式账号！";exit;
		}
		if(empty($_SESSION['out_money'])){
			echo "参数错误，请重试！";
		    exit();
		}
		//出款判断
		$status = $this->Cash_model->out_cash_record();
		if(!empty($status['order_num'])){
		    echo '您有订单号：'.$status['order_num'].',出款尚未完成.请勿频繁出款';
		    exit();
		}
		$qk_pwd = $this->input->post('qk_pwd');
		$uu_out = $this->input->post('uu_out');
		$cash   = $this->input->post('cash');  //提款金额
		if(!empty($qk_pwd) && $uu_out == 'oucd'){
			$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
			if($userinfo['qk_pwd'] != $qk_pwd){
				echo "取款密码错误，请重新输入。";
		    	exit();
			}
		}
		$pay_value = doubleval($cash);//提款金额
		$pay_data  =  $this->Common_model->get_user_level_info($_SESSION['uid']);
		if(($pay_value<0)||($pay_value>$userinfo["money"])||($pay_value>$pay_data['ol_atm_max'])||($pay_value<$pay_data['ol_atm_min'])){
			echo "提款金额错误，请重新输入。";
	    	exit();
	    }
	    //判断是否首次出款
	    $outward_style=1;
	    $is_first = $this->Cash_model->is_first();
	    if($is_first){
	        $outward_style=0;//不是首次出款
	    }else{
	        $outward_style=1;
	    }
	    if(empty($_SESSION['agent_id']) || empty($_SESSION['username'])){
	    	echo "<script>alert('参数错误，请重试！');window.close();</script>";exit;
	    }
	    //获取代理商账号
    $agent_user = $this->Cash_model->get_agent_user();
        //扣除费用
		$out_data = array();
		$out_data = $_SESSION['out_money'];
		//是否扣除优惠
		if (!empty($out_data['fav_num'])) {
			$is_fav = 1;
		}else{
			$is_fav = 0;
		}
	   //判断提出额度是否大于扣除
		$tmpUY = $pay_value - $out_data['out_audit'] - $out_data['out_fee'];
		if ($tmpUY < 0) {
			echo "减去扣除额度后提款额度小于0，请重新提交出款额度。";
	    	exit();
		}
		$order_num=date("YmdHis").mt_rand(1000,9999);//订单号
		$this->db->trans_begin();
		$this->db->where('uid',$_SESSION['uid']);
		$this->db->set('money','money-'.$pay_value,FALSE);
		$this->db->update('k_user');
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			echo "<script>alert('交易失败,错误代码cw0001');window.close();</script>";exit;
		}

		//写入出款记录
        //获取用户当前余额
        $umban = $this->Cash_model->now_money();
        if($umban['money'] < 0){
	        $this->db->trans_rollback();
	        echo "<script>alert('交易失败,错误代码cw0002');window.close();</script>";exit;
        }
        $data_o = array();
        $data_o['site_id'] = SITEID;
        $data_o['uid'] = $_SESSION['uid'];
        $data_o['index_id'] = INDEX_ID;
        $data_o['agent_user'] = $agent_user['agent_user'];
        $data_o['agent_id'] = $_SESSION['agent_id'];
        $data_o['username'] = $_SESSION['username'];
        $data_o['level_id'] = $userinfo['level_id'];
        $data_o['audit_id'] = '';
        $data_o['balance'] = $umban['money'];
        $data_o['do_url'] =  $_SERVER["HTTP_HOST"];//提交网址
        $data_o['order_num'] = $order_num;
        $data_o['out_time'] = date('Y-m-d H:i:s');
        $data_o['outward_style'] = $outward_style;//是否首次出款
        $data_o['outward_num'] = $pay_value;//提交额度
        $data_o['charge'] = $out_data['out_fee'];//手续费
        $data_o['favourable_num'] = $out_data['fav_num'];//优惠金额
        $data_o['expenese_num'] = ($out_data['out_audit'] - $out_data['fav_num']);//行政费用扣除
        $data_o['outward_money'] = ($pay_value - $out_data['out_audit'] - $out_data['out_fee']);//实际出款额度
        $data_o['favourable_out'] = $is_fav;//是否扣除优惠
        $this->db->insert('k_user_bank_out_record',$data_o);
        $log_2 = $this->db->insert_id();
    if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			echo "<script>alert('交易失败,错误代码cw0003');window.close();</script>";exit;
		}

		//写入现金系统
        $dataC = array();
        $dataC['uid'] = $_SESSION['uid'];
        $dataC['username'] = $_SESSION['username'];
        $dataC['site_id'] = SITEID;
        $dataC['index_id'] = INDEX_ID;
        $dataC['agent_id'] = $_SESSION['agent_id'];
        $dataC['cash_balance'] = $umban['money'];
        $dataC['source_id'] = $log_2;
        $dataC['cash_type'] = 19;//线上取款
        $dataC['cash_do_type'] = 2;
        $dataC['source_type'] = 4;//线上取款类型
        $dataC['cash_num'] = $pay_value;
        $dataC['cash_date'] = date('Y-m-d H:i:s');
        $dataC['remark'] = $order_num;
        $this->db->insert('k_user_cash_record',$dataC);
        if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			echo "<script>alert('交易失败,错误代码cw0004');window.close();</script>";exit;
		}else{
			$this->db->trans_commit();
			unset($_SESSION['out_money']);
      echo "<script>alert('提款申请已经提交，等待财务人员给您转账！');window.close();</script>";exit;
		}
	}

	//公司入款
	public function bank(){
		if($_SESSION['shiwan'] == 1){
			echo "试玩账号不能存取款，请注册正式账号！";exit;
		}
		//判断用户是否绑定银行卡
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		if(empty($userinfo['pay_num'])){
			echo "<script>alert('为方便后续能及时申请出款到账，请在入款前设置您的银行卡等信息，请如实填写！');</script>";
    		$this->hk_money($this->router->class,$this->router->method);
		}
		$bank_arr = $this->Cash_model->get_bank_arr();//获取银行列表

		//入款银行卡
		$banks = $this->Cash_model->get_bank_in_arr($_SESSION['level_id']);//获取入款银行列表
	    if(empty($banks)){
	        echo "<script>alert('没有可用的银行卡，请联系客服！');";
	        echo "window.close();</script>";exit;
	    }
		$copyright = $this->Common_model->get_copyright();
		$levelinfo = $this->Common_model->get_user_level_info($_SESSION['uid']);
		$order=date("YmdHis").mt_rand(1000,9999);//订单号
		$this->add('bank_arr',$bank_arr);
		$this->add('banks',$banks);
		$this->add('order',$order);
		$this->add('levelinfo',$levelinfo);
		$this->add('copyright',$copyright);
		$this->display('member/bank.html');
	}
 	//公司入款流程解说
	public function pay_explain(){
		$this->display('member/pay_explain.html');
	}

	//公司入款处理
	public function bank_ajax(){
		if($_SESSION['shiwan'] == 1){
			echo $this->Common_model->JSON(array('statu'=>'4'));exit();
		}
		$deposit_num    = $this->input->post('deposit_num');//入款金额
		$bank_style     = $this->input->post('bank_style'); //会员使用的银行
		$order_num      = $this->input->post('order_num');  //订单号
		$deposit_way    = $this->input->post('deposit_way');//存款方式
		$in_name        = $this->input->post('in_name'); //存款人姓名
		$in_date        = $this->input->post('in_date'); //存入时间
		$in_atm_address = $this->input->post('bank_location1').$this->input->post('bank_location2').$this->input->post('bank_location4');
		$action         = $this->input->post('action');
		if(!empty($action) && $action == 'add_form')
		{

			$bid = $this->input->post('bid');
			if(!empty($bid)){
				$bank = $this->Cash_model->get_bank();   //获取银行官网
			}

			$user = $this->Common_model->get_user_info($_SESSION['uid']);

			$agent = $this->Cash_model->get_agent_user();   //获取代理商帐号
			//查询是不是首次入款
			$user_record = $this->Cash_model->is_first_in();
			$levelinfo = $this->Common_model->get_user_level_info($_SESSION['uid']);

			if($deposit_num > $levelinfo['line_catm_max']){
				echo $this->Common_model->JSON(array('statu'=>'1','infos'=>$levelinfo['line_catm_max']));exit();
			}

			if($deposit_num < $levelinfo['line_catm_min']){
				echo $this->Common_model->JSON(array('statu'=>'2','infos'=>$levelinfo['line_catm_min']));exit();
			}

			//防止用户恶意提交表单
			$result = $this->Cash_model->get_order_num($order_num);
			if(!empty($result['order_num'])){
				echo $this->Common_model->JSON(array('statu'=>'3'));exit();
			}

			$level_des = $this->Cash_model->get_level_des();   //获取层级

			$data = array();
			if($deposit_way==2||$deposit_way==3||$deposit_way==4){
				$data['in_info'] = $this->Common_model->bank_type($bank_style).','.$in_name.','.$in_date.','.$this->Common_model->in_type($deposit_way).','.$in_atm_address;
			}else{
				$data['in_info'] = $this->Common_model->bank_type($bank_style).','.$in_name.','.$in_date.','.$this->Common_model->in_type($deposit_way);
			}
			$data['in_date']        = $in_date;
			$data['in_type']        = $deposit_way;
			$data['into_style']     = 1;
			$data['bank_style']     = $bank_style;
			$data['in_atm_address'] = $in_atm_address;
			$data['in_name']        = $in_name;
			$data['log_time']       = date("Y-m-d H:i:s");//系统提交时间
			$data['deposit_num']    = $deposit_num;
			$data['order_num']      = $order_num;
			$data['username']       = $_SESSION['username'];
			$data['agent_user']     = $agent['agent_user'];
			$data['agent_id']       = $_SESSION['agent_id'];
			$data['uid']            = $_SESSION['uid'];
			$data['level_id']       = $_SESSION['level_id'];
			$data['level_des']      = $level_des['level_des'];
			$data['site_id']        = SITEID;
			$data['index_id']       = INDEX_ID;
			$data['bid']            = $bid;
			$data['is_firsttime']   = empty($user_record['id'])?1:0;
			//存款优惠判断
			if ($data['deposit_num'] >= $levelinfo['line_discount_num']) {
				$data['favourable_num'] = (0.01*$data['deposit_num']*$levelinfo['line_discount_per']>$levelinfo['line_discount_max'])?$levelinfo['line_discount_max']:(0.01*$data['deposit_num']*$levelinfo['line_discount_per']);
			}

			 //判断是否开启首存优惠
			if (($levelinfo['line_deposit'] == '2') && !$data['is_firsttime']) {
                $data['favourable_num'] = 0;
			}


			//其它优惠判断
			if ($data['deposit_num'] >= $levelinfo['line_other_discount_num']) {
				$data['other_num'] = (0.01*$data['deposit_num']*$levelinfo['line_other_discount_per']>$levelinfo['line_other_discount_max'])?$levelinfo['line_other_discount_max']:(0.01*$data['deposit_num']*$levelinfo['line_other_discount_per']);
			}
			$data['deposit_money'] = $data['deposit_num']+$data['other_num']+$data['favourable_num'];//存入总金额

			$json_data = array();
			$json_data['deposit_num']=$this->input->post('deposit_num');//存入金额：
			$json_data['now_date']=$this->input->post('now_date');//存入时间：
			$json_data['in_name']=$this->input->post('in_name');//存款人姓名：
			$json_data['d_bank_location'] = $in_atm_address;
			$json_data['bank']= $bank['card_address'];

			$this->db->from('k_user_bank_in_record');
			$this->db->set($data);
			if ($this->db->insert()) {
				$json_data['ok']=1;
				$json_data['bank_style']=$this->Common_model->bank_type($data['bank_style']);
				echo $this->Common_model->JSON($json_data);
			}else{
				$json_data['ok']=2;
				echo $this->Common_model->JSON($json_data);
			}
		}
	}

	  //常态稽核状态返回
     function ct_state($ct){
        switch ($ct) {
            case '0':
                return "<font color=\"#ff0000\">未通過</font>";
                break;
            case '1':
                return "<font color=\"#00cc00\">通過</font>";
                break;
            case '2':
                return "-";
                break;
        }
    }

       //扣除行政费用状态
    function xz_state($xz){
        switch ($xz) {
            case '0':
                return "<font color=\"#ff0000\">否</font>";
                break;
            case '1':
                return "<font color=\"#00cc00\">是</font>";
                break;
            case '2':
                return "不需要稽核";
                break;
        }
    }

      //综合稽核状态返回
    function zh_state($st){
        switch ($st) {
            case '0':
                return "<font color=\"#ff0000\">否</font>";
                break;
            case '1':
                return "<font color=\"#00cc00\">是</font>";
                break;
            case '2':
                return "不需要稽核";
                break;
        }
    }

}
