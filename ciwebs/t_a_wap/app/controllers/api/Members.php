<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Members extends MY_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('member/Member_model');
        $this->load->model('member/Notice_model');
    }

    function GetOrderNum(){
    	$this->verify_login($_SESSION['uid']);
    	$json = '';
    	$data = array();
    	$data['ErrorCode'] = 0;
    	$data['Data']['OrderNum'] = date("YmdHis").mt_rand(1000,9999);//订单号
    	$json = json_encode($data);
    	header('Content-Type: application/json;charset=utf-8');
		echo $json;
    }

    /*
    * ErrorCode 1 取款密码不正确
    * ErrorCode 2 参数错误
    * ErrorCode 9  今天出款次数已超出限制次数
	* ErrorCode 11 取款密码不正确
	* ErrorCode 12 已经提交取款单 不能再申请
    */
	function AddWithdraw()
	{
		$this->verify_login($_SESSION['uid']);
		$json = '';
		$get =$this->input->file_get();
		$json = $this->Member_model->AddWithdrawDo($get);
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	//修改密码
	function ChangeMemberPwd(){
		$this->verify_login($_SESSION['uid']);
		//{OldLoginPwd: "123123", NewLoginPwd: "zxc123"}
		$postarr =$this->input->file_get();
		if(!empty($postarr['OldLoginPwd']) && !empty($postarr['NewLoginPwd'])){
			$oldpsw = md5(md5($postarr['OldLoginPwd']));
			$newpsw = md5(md5($postarr['NewLoginPwd']));
			$uid = $_SESSION['uid'];
			$site_id = SITEID;
			$views = $this->Member_model->edit_password($oldpsw,$newpsw,$uid,$site_id,$type = 1);
		}else if(!empty($postarr['OldWithdrawPwd']) && !empty($postarr['NewWithDrawPwd'])){
			$old_qk_psw = $postarr['OldWithdrawPwd'];
			$new_qk_psw = $postarr['NewWithDrawPwd'];
			$uid = $_SESSION['uid'];
			$site_id = SITEID;
			$views = $this->Member_model->edit_password($old_qk_psw,$new_qk_psw,$uid,$site_id,$type = 2);
		}
		$json = str_replace('--"', '',str_replace('"--', '', json_encode($views)));
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}


	function CheckToken(){
	//playload {SiteNo: "00-88"}

	$json = '{"ErrorCode":0}';
	if(intval($_SESSION["uid"]) > 0){
			$json = '{"ErrorCode":0}';
	}else{
		$json = '{"ErrorCode":108}';
	}


		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	function FundTransfer(){
		$this->verify_login($_SESSION['uid']);
		$this->load->model('member/Cash_model');
		$this->load->model('Common_model');
		header('Content-Type: application/json;charset=utf-8');

		if ($_SESSION['shiwan'] == 1) {
	       echo '{"ErrorCode":1,"ErrorMsg":"试玩账号不能存取款，请注册正式账号！"}';
	       exit;
	    }
	    if (SITEID == 't' && $_SESSION['username'] != 'owei') {
	       echo '{"ErrorCode":25,"ErrorMsg":"演示站点，不允许额度转换"}';
	       exit;
	    }
	    $uid = @$_SESSION['uid'];
		$username = @$_SESSION['username'];
		$userinfo = $this->Common_model->get_user_info($uid);

		$cash_record = $this->Cash_model->get_cash_record($userinfo['uid']);  //获取金额交易记录

		if ($cash_record && !empty($cash_record['cash_date'])) {
				$time = time() - strtotime($cash_record['cash_date']);
				if ($time < 60) {
					echo json_encode(array("ErrorCode" => 17, "ErrorMsg" => "请在" . (60 - $time) . "秒后操作"));exit;
				}
		}

		//ID的意思
		//0，系统 3 ag，4 lebo，5 bbin,6 ct,7 pt,8 mg,9 og
		//type = 1 正常的转账
		//{FromGameClassID: 0, ToGameClassID: 1, Amount: 100, Type: 1}
		//type = 2 一建转账转所有额度
		//{FromGameClassID: 0, ToGameClassID: 3, Type: 2}
		//{FromGameClassID: 0, ToGameClassID: 3, Amount: 100, Type: 1}
		//转换数字
		$getjson =$this->input->file_get();
		if($getjson["Type"] == 2){
			//获取全部余额
			$map['table'] = 'k_user';
			$map['select'] = "money";
		    $map['where']['index_id'] = INDEX_ID;
		    $map['where']['site_id'] = SITEID;
		    $map['where']['uid'] = $uid;
		    $data = $this->Member_model->rfind($map);
		    $credit = floatval($data['money']);
		} else {
			$credit = floatval($getjson["Amount"]);
		}
		$trtype1 = $trtype2 = "sport";

		if($getjson["FromGameClassID"] == 3){
			$trtype1 = 'ag';
		}else if($getjson["FromGameClassID"] == 4){
			$trtype1 = 'lebo';
		}else if($getjson["FromGameClassID"] == 5){
			$trtype1 = 'bbin';
		}else if($getjson["FromGameClassID"] == 6){
			$trtype1 = 'ct';
		}else if($getjson["FromGameClassID"] == 7){
			$trtype1 = 'pt';
		}else if($getjson["FromGameClassID"] == 8){
			$trtype1 = 'mg';
		}else if($getjson["FromGameClassID"] == 9){
			$trtype1 = 'og';
		}
		if($getjson["ToGameClassID"] == 3){
			$trtype2 = 'ag';
		}else if($getjson["ToGameClassID"] == 4){
			$trtype2 = 'lebo';
		}else if($getjson["ToGameClassID"] == 5){
			$trtype2 = 'bbin';
		}else if($getjson["ToGameClassID"] == 6){
			$trtype2 = 'ct';
		}else if($getjson["ToGameClassID"] == 7){
			$trtype2 = 'pt';
		}else if($getjson["ToGameClassID"] == 8){
			$trtype2 = 'mg';
		}else if($getjson["ToGameClassID"] == 9){
			$trtype2 = 'og';
		}

		$g_type_arr = array("og", "ag", "mg", "ct", "lebo", "bbin","pt", "sport");

		//$trtype1 = trim($this->input->post('trtype1'));
		//$trtype2 = trim($this->input->post('trtype2'));
		//$credit = floatval($this->input->post('p3_Amt'));

		if($trtype1 == $trtype2){
			echo json_encode(array("ErrorCode" => 19, "ErrorMsg" => "转入转出平台不能相同，请重新选择" ));exit;
		}
		$list = array('ag','og','mg','ct','bbin','lebo',"pt");
		foreach($list as $val){
			if($this->Common_model->GetSiteStatus($SiteStatus,2,$val,1)){
				if($trtype1 == $val || $trtype2 == $val){
					$str  = $val."游戏正在进行例行维护！\n请您选择其他游戏！祝您游戏开心！";
					echo json_encode(array("ErrorCode" => 18, "ErrorMsg" => $str ));exit;
				}
			}
		}

		$tc_type = $g_type = "";
		if (!in_array($trtype1, $g_type_arr) && !in_array($trtype2, $g_type_arr)) {
			echo json_encode(array("ErrorCode" => 1, "ErrorMsg" => "未知的游戏!"));exit;
		}
		if (empty($username)) {
			echo json_encode(array("ErrorCode" => 2, "ErrorMsg" => "请登录再进行游戏!"));exit;
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
			echo json_encode(array("ErrorCode" => 3, "ErrorMsg" => "额度转换,只能在系统余额和视讯余额之间转换,视讯余额之间不能直接转换!"));exit;
		}

		//$credit = floatval($this->input->post('p3_Amt'));
		if ($credit < 10) {
			echo json_encode(array("ErrorCode" => 4, "ErrorMsg" => "转换的额度，必须大于10!"));exit;
		}
		$this->load->library('Games');
		$games = new Games();
		if ($tc_type == "IN") {

			//更新会员金额
			$this->db->trans_begin();
	    	$this->db->where('uid',$uid);
			$this->db->where('money >=',$credit);
			$this->db->set('money','money-'.$credit,FALSE);
			$this->db->update('k_user');
			if($this->db->affected_rows() == 0){
				$this->db->trans_rollback();
				echo json_encode(array("ErrorCode" => 5, "ErrorMsg" => "额度转换失败,错误代码R002"));exit;
			}

			$data = $games->GetBalance($username, $g_type);
			$result = json_decode($data);

			if ($result->data->Code == 10017) {
				$sxbalance = floatval($result->data->balance);
			} else if ($result->data->Code == 10006) {
				$data = $games->CreateAccount($username, $userinfo["agent_id"], $g_type,INDEX_ID);
				if (!empty($data)) {
					$result = json_decode($data);
					if ($result->data->Code != 10011) {
						$this->db->trans_rollback(); //数据回滚
						echo json_encode(array("ErrorCode" => 6, "ErrorMsg" => "额度转换失败,错误代码R003"));exit;
					}
				} else {
					//网络无响应
					$this->db->trans_rollback(); //数据回滚
					echo json_encode(array("ErrorCode" => 7, "ErrorMsg" => "由于网络原因，转账失败，请联系管理员"));exit;
				}
				$sxbalance = 0;
			} else {
				$this->db->trans_rollback(); //数据回滚
				echo json_encode(array("ErrorCode" => 8, "ErrorMsg" => "额度转换失败,错误代码R004"));exit;
			}
			$userinfo = $this->Common_model->get_user_info($uid);
			if(empty($_SESSION['agent_id'])){
				$this->db->trans_rollback(); //数据回滚
				echo json_encode(array("ErrorCode" => 9, "ErrorMsg" => "额度转换失败,错误代码R005"));exit;
			}
			//现金记录
			$remark = "系统转出" . $g_type . ":" . $credit . " 元," . $g_type . "余额:" . ($sxbalance + $credit) . "元";
			$log_2 = $this->Cash_model->add_cash_record($uid,$userinfo['username'],$credit,$userinfo['money'],$remark);   //插入现金交易记录

			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				echo json_encode(array("ErrorCode" => 10, "ErrorMsg" => "额度转换失败,错误代码R006"));exit;
				echo "<script>alert('交易失败,错误代码cw0004');window.close();</script>";exit;
			}else{
				$this->db->trans_commit();
				//视讯开始加款
			}
		}

		$data = $games->TransferCredit($username, $g_type, $tc_type, $credit);
		if (empty($data)) {
			echo json_encode(array("ErrorCode" => 11, "ErrorMsg" => "由于网络原因，转账失败，请联系管理员 "));exit;
		}
		$result = json_decode($data);
		if ($result->data->Code == 10006) {
			$data = $games->CreateAccount($username, $userinfo["agent_id"], $g_type,INDEX_ID);
			if (!empty($data)) {
				$result = json_decode($data);
				if ($result->data->Code != 10011) {
					echo json_encode(array("ErrorCode" => 12, "ErrorMsg" => "额度转换失败,错误代码R007 "));exit;
				} else {
					//用户添加成功转账重试
					$data = $games->TransferCredit($username, $g_type, $tc_type, $credit);
					if (empty($data)) {
						echo json_encode(array("ErrorCode" => 13, "ErrorMsg" => "由于网络原因，转账失败，请联系管理员 "));exit;
					}
					$result = json_decode($data);
				}
			}
		}

		if ($result->data->Code == 10013) {
			if ($tc_type == "OUT") {
				$this->db->trans_begin();
		    $this->db->where('uid',$uid);
				$this->db->set('money','money+'.$credit,FALSE);
				$this->db->update('k_user');
				if($this->db->affected_rows() == 0){
					$this->db->trans_rollback();
					echo json_encode(array("ErrorCode" => 14, "ErrorMsg" => "额度转换失败,错误代码R008"));exit;
				}
				$data = $games->GetBalance($username, $g_type);
				$result = json_decode($data);
				if ($result->data->Code == 10017) {
					$sxbalance = floatval($result->data->balance);
				} else {
					$this->db->trans_rollback(); //数据回滚
					echo json_encode(array("ErrorCode" => 15, "ErrorMsg" => "额度转换失败,错误代码R009 "));exit;
				}
				$userinfo = $this->Common_model->get_user_info($uid);
				if(empty($_SESSION['agent_id'])){
					$this->db->trans_rollback(); //数据回滚
					echo json_encode(array("ErrorCode" => 16, "ErrorMsg" => "额度转换失败,错误代码R0010"));exit;
				}
				//现金记录
				$remark = $g_type . "转系统：" . $credit . " 元," . $g_type . ":" . $sxbalance . "元";

				$log_3 = $this->Cash_model->add_cash_record($uid,$userinfo['username'],$credit,$userinfo['money'],$remark);   //插入现金交易记录

				if($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					echo json_encode(array("ErrorCode" => 17, "ErrorMsg" => "额度转换失败,错误代码R0011"));exit;
					echo "<script>alert('交易失败,错误代码cw0004');window.close();</script>";exit;
				}else{
					$this->db->trans_commit();
					//视讯开始加款
				}
			}
			$this->GetUserMoney();
			echo json_encode(array("ErrorCode" => 0, "ErrorMsg" => "转账成功 "));exit;
		} else {
			echo json_encode(array("ErrorCode" => 19, "ErrorMsg" => "转账失败，余额不足或第三方正在维护中 "));exit;
		}
	}


	//更新客户余额
	function GetUserMoney(){
		$this->verify_login($_SESSION['uid']);
		$map = array();
	    $map['table'] = 'k_user';
	    $map['where']['uid'] = $_SESSION['uid'];
	    $map['where']['site_id'] = SITEID;
		  $user = $this->Member_model->rfind($map);
		  $this->load->library('Games');
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
	            $map_u['where']['uid'] = $_SESSION['uid'];
	            $map_u['where']['site_id'] = SITEID;
				$this->Member_model->rupdate($map_u,$data_u);
				$this->add('data_u',$data_u);
			}
	}











	/*
	* params MinAccountDepositMoney 公司入款下限
	* params MaxAccountDepositMoney 公司入款上限
	* params MinThirdPartyDepositMoney 在线入款下限
	* params MaxThirdPartyDepositMoney 在线入款入款上限
	*/

	function GetDepositLimit(){
		$this->verify_login($_SESSION['uid']);
		$this->load->model('Common_model');
		$data = $this->Common_model->get_user_level_info($_SESSION['level_id']);
		$_SESSION['ol_catm_max'] = $data['ol_catm_max'];
		$_SESSION['ol_catm_min'] = $data['ol_catm_min'];
		$json = '';
		$json['Data']['MinAccountDepositMoney'] = $data['line_catm_min'];
		$json['Data']['MaxAccountDepositMoney'] = $data['line_catm_max'];
		$json['Data']['MinThirdPartyDepositMoney'] = $data['ol_catm_min'];
		$json['Data']['MaxThirdPartyDepositMoney'] = $data['ol_catm_max'];
		$json['ErrorCode'] = 0;
		$json = json_encode($json);
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	/*
	*  收款人的信息
	*/
	function GetDepositsBanks(){
		$this->verify_login($_SESSION['uid']);
		$this->load->model('Common_model');
		$banks = '';
		$banks = $this->Member_model->GetDepositsBanks();
		$josn = '';
		$json['ErrorCode'] = 0;
		foreach ($banks as $k => $v) {
			$json['Data'][$k]['BankAccountID'] = intval($v['id']);
			$json['Data'][$k]['BankName'] = $this->Common_model->bank_type($v['bank_type']);
			$json['Data'][$k]['BankBranchName'] = $v['card_address'];
			$json['Data'][$k]['BankNickName'] = $v['card_userName'];
			$json['Data'][$k]['BankAccount'] = str_replace(' ', '',$v['card_ID']);
		}
		$json = json_encode($json);
		/*$json = <<<Eof
{"ErrorCode":0,"Data":[{"BankAccountID":1154,"BankName":"招商银行","BankBranchName":"广东省","BankNickName":"邱奕纯","BankAccount":"6214837556914715"},{"BankAccountID":1154,"BankName":"招商银行","BankBranchName":"广东省","BankNickName":"邱奕纯","BankAccount":"62148375569147157"}]}
Eof;*/
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}


	/*
	** ErrorCode 1   您的帐号已被停用
	** ErrorCode 2   您的账号被锁定
	** ErrorCode 3   存款金额超出限额
	** ErrorCode 4   参数错误
	** ErrorCode 5   网络连接超时，请到交易记录查看是否成功
	*/
	function AddDeposit(){
		$this->verify_login($_SESSION['uid']);
		$json = '';
		$get =$this->input->file_get();
		$json = $this->Member_model->AddDepositDo($get);
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	//http://m.pkpkpk.lo/#/inappgame/emVyb3BocCMjIzEyMzQ1NiMjIzE=
	function GetInGameRedirect(){
		//{GameClassID: "1", GameID: "0", PageStyle: ""}
		$get =$this->input->file_get();

		if(!empty($get['DAScode'])){
			$strr = base64_decode(trim($get['DAScode']));
			$sarr = explode("###", $strr);
			if(count($sarr) == 3){
				$get['GameClassID']= intval($sarr[2]);
				$get['GameID'] = $get['GameClassID'];
				$this->App_Login($sarr[0],$sarr[1]);
			}else{
				$data['ErrorCode'] = 3;
				$data['ErrorMsg'] = '执行失败';
	       		header('Content-Type: application/json;charset=utf-8');
	        	echo json_encode($data);
	        	exit();
			}
		}

		$url = "";
		if ($get['GameClassID'] == 1){//彩票
			$url = "/lot/#/sso/".$get['GameID']."/".$get['ID'];
		}else if($get['GameClassID'] == 2){//体育

		}else if($get['GameClassID'] == 3){//ag
			$url = "/voide/ag";
		}else if($get['GameClassID'] == 4){//lebo
			$this->verify_login($_SESSION['uid']);
			$url = "/voide/lebo";
		}else if($get['GameClassID'] == 5){//bbin
			$this->verify_login($_SESSION['uid']);
			$url = "/voide/bbin";
		}else if($get['GameClassID']==6){
		    $url = "/#/deposit/0";
		}else if($get['GameClassID']==7){
		    $url = "/#/day/0/0";
		}else if($get['GameClassID'] == 61){//mg h5
			$this->verify_login($_SESSION['uid']);
			//9999999999999999999999999
			$url = $this->_logindz($get['GameId'],$get['GameType']);
			if (empty($url )){
				$json = '{"ErrorCode":1,"ErrorMsg":"执行失败"}';
				header('Content-Type: application/json;charset=utf-8');
				echo $json;
				exit;
			}
		}


		//生成游戏链接
		$json = <<<Eof
{"ErrorCode":0,"Data":{"Redirect":"$url"}}
Eof;

		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	function _logindz($id,$type){
		$this->load->library('Games');
	  	$this->load->model('games/Games_model');
		include_once dirname(__FILE__) . "../../../../wh/site_state.php";
		GetSiteStatus($SiteStatus,1,'mg_game',1);
		$g_type = trim($type);

		$gametype = "";
		if ( $g_type == 'mgh5'){
			$gametype = "11";
		}
		if($g_type == '' || $g_type == 'mgh5'){
			$g_type = 'mg';
		}
		$g_type_arr = array("ag", "mg",'pt','pk');
		if (!in_array($g_type, $g_type_arr)) {
			return "";
		}

		$data = $this->Games_model->GetUserName();

		//试玩账号屏蔽
		if ($data['shiwan'] == '1') {
			return "";
		}
		if (empty($data) OR empty($data["username"])) {
			return "";
		}
		$loginname = $data["username"];
		$gameid = trim($id);
		if (empty($gameid)) {
			return "";
		}
		$Games = new Games;
		$lang = "CN";
		$cur = "RMB";
		$indexid = INDEX_ID;
		$url = $Games->forwarddz($g_type,$loginname, $gameid, $lang, $cur,$gametype);
		$pos1 = strpos($url, "result");
		$pos2 = strpos($url, "data");
		if ($pos1 > 0 && $pos2 > 0) {
			$result = json_decode($url);
			if ($result->data->Code == 10006) {
				$data = $Games->CreateAccount($loginname, $data["agent_id"],$g_type, $indexid,  $cur);
				if (!empty($data)) {
					$result = json_decode($data);
					if ($result->data->Code != 10011) {
						return "";
					} else {
						$url = $Games->forwarddz($g_type,$loginname, $gameid, $lang, $cur,$gametype);
						$pos1 = strpos($url, "result");
						$pos2 = strpos($url, "data");
						if ($pos1 > 0 && $pos2 > 0) {
							return "";
						}
					}
				}else{
					return "";
				}
			} else {
				return "";
			}
		}

		return $url;
	}

	function GetInternalMessage(){
		$this->verify_login($_SESSION['uid']);
		$get =$this->input->file_get();
		$map['key'] = 'msg_id';
		$map['set'] = 'islook';
		$map['val'] = $get['MessID'];
		$map['tab'] = 'k_user_msg';
		$this->Notice_model->UpSmsChange($map);
		$data = $this->Notice_model->GetOneNews($map['val']);
		$json = json_encode($data);
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	function GetInternalMessageList(){
		$this->verify_login($_SESSION['uid']);
		$get =$this->input->file_get();
		$data = $this->Notice_model->GetSms($get);
		$json = str_replace('--"', '',str_replace('"--', '', json_encode($data)));

/*$json = <<<Eof
{"ErrorCode":0,
"Data":{
	"SearchTime":"2015-12-27 10:29:00",
	"List":[
		{"MessID":2774696,
		"SendType":1,
		"SendContent":"尊敬的会员，感谢您成为玛雅吧的会员，在这里您可以体验高效快速的存取款业务。我们推荐玛雅娱乐的真人视讯，一边看美女一边玩游戏，游戏娱乐两不误！此外我们对接的高频彩全天24小时开奖，更高的彩票赔率，让您时时畅玩不停，娱乐不断。3D真人电子游戏，独创路珠记牌模式，好玩更刺激！感受心动不如赶快行动！玛雅吧祝您游戏愉快！",
		"SendDateTime":"2015-12-27 10:02:05","IsReply":0,"ReadState":1},
		{"MessID":2128011,
		"SendType":1,
		"SendContent":"温馨提醒：玛雅吧在线支付是【智付平台】如支付页面出现【环迅支付】之类或其它支付平台，请您不要支付款项。",
		"SendDateTime":"2015-12-05 01:33:57","IsReply":0,"ReadState":0},
		{"MessID":1769513,"SendType":1,"SendContent":"尊贵的会员:您好，玛雅吧官方网址：www.maya86.com ，导航线路网址：www.maya555.com，手机登录M站网址www.m.maya86.com ，\\n请各位会员牢记我们官方网址，如有任何疑问请咨询我们24小时在线客服。谢谢！",
		"SendDateTime":"2015-11-21 23:32:45","IsReply":0,"ReadState":0}
		]
	}
}
Eof;*/

header('Content-Type: application/json;charset=utf-8');
echo $json;
	}

	function  GetMember(){
        $uid = $_SESSION['uid'];
        $this->verify_login($_SESSION['uid']);
	    $views = $this->Member_model->get_user_views($uid,SITEID);
	    $json = str_replace('--"', '',str_replace('"--', '', json_encode($views)));
	    header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	function  GetMember2(){
		$this->verify_login($_SESSION['uid']);
	    //稽核
	    $this->load->model('member/Audit_model');
	    $this->load->model('Common_model');
	    $copyright = $this->Common_model->get_copyright();
	    $type = $copyright['video_module'];
	    $end_date = date('Y-m-d H:i:s');
	    $pay_data = $this->Common_model->get_user_level_info($_SESSION['uid']);
	    //$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);

	    $userinfo['uid'] = $_SESSION['uid'];
	    $userinfo['username'] = $_SESSION['username'];
	    $audit_data = $this->Audit_model->get_user_audit($userinfo['uid'],$pay_data,$userinfo['username'],$type,$end_date);

	    $count_dis = $audit_data['count_dis'];
	    $count_xz = $audit_data['count_xz'];
	    $out_data = array();
	    $out_data['out_fee'] = $audit_data['out_fee'];//出款手续费
	    $out_data['out_audit'] = $count_dis + $count_xz;//稽核扣除费用
	    $out_data['fav_num'] = $count_dis;
	    unset($_SESSION['out_money']);
	    $_SESSION['out_money'] = $out_data;

	    $data = array();
	    $data['ErrorCode'] = 0;
	    $data['Data']['OutAudit'] = $_SESSION['out_money']['out_audit']+$_SESSION['out_money']['out_fee'];
	    $json = json_encode($data);
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	//用户绑定的取款银行
	function GetMemberBankList(){
		$this->verify_login($_SESSION['uid']);

    $user = $this->Member_model->GetBankInfo();
    $data = array();
    $Data =array();
    if(empty($user['pay_num'])){
        $data['ErrorCode'] = 0;
        $data['Data'] = $Data;
    }else{
    	$data['ErrorCode'] = 0;
        $a = explode('-', $user['pay_address']);
        $Data['BankProvince'] = $a[0];
        $Data['BankCity'] = $a[1];
        $Data['BankAccount'] = $user['pay_num'];
        $Data['BankNickName'] = $user['pay_name'];
        $Data['BankName'] = $this->Member_model->BankType($user['pay_card']);
        $Data['MemberBankID'] = $user['pay_card'];
        $data['Data'][] = $Data;
    }
    $json = json_encode($data);

header('Content-Type: application/json;charset=utf-8');
echo $json;
	}


	function GetMemberGameState(){
		//获取该站点使用的模块
		$this->load->model('Common_model');
      	$copyright = $this->Common_model->get_copyright();
      	$copyright['video_module'] = strtolower($copyright['video_module']);
      	$video_modules = explode(",", $copyright['video_module']);

		//{"GameClassID":6,"State":1,"CloseTime":"","OpenTime":"","OpenState":1,"TestUserWalletModel":1,"GameClassName":"CT电子"},
		//{"GameClassID":7,"State":1,"CloseTime":"","OpenTime":"","OpenState":1,"TestUserWalletModel":1,"GameClassName":"PT视讯"},
		//{"GameClassID":9,"State":1,"CloseTime":"","OpenTime":"","OpenState":1,"TestUserWalletModel":1,"GameClassName":"OG视讯"}
		$data = array();
		$data["ErrorCode"] = 0;
		$data["Data"] = array();

		if(in_array("ag", $video_modules)){
			$data["Data"][] = array('GameClassID' => 3,
									 'State' => 1,
									 'CloseTime' => "",
									 'OpenTime' => "",
									 'OpenState' => 1,
									 'TestUserWalletModel' => 1,
									 'GameClassName' => "AG视讯"
									 );
		}
		if(in_array("bbin", $video_modules)){
			$data["Data"][] = array('GameClassID' => 5,
									 'State' => 1,
									 'CloseTime' => "",
									 'OpenTime' => "",
									 'OpenState' => 1,
									 'TestUserWalletModel' => 1,
									 'GameClassName' => "BBIN视讯"
									 );
		}
		if(in_array("lebo", $video_modules)){
			$data["Data"][] = array('GameClassID' => 4,
									 'State' => 1,
									 'CloseTime' => "",
									 'OpenTime' => "",
									 'OpenState' => 1,
									 'TestUserWalletModel' => 1,
									 'GameClassName' => "LEBO视讯"
									 );
		}
		if(in_array("mg", $video_modules)){
			$data["Data"][] = array('GameClassID' => 8,
									 'State' => 1,
									 'CloseTime' => "",
									 'OpenTime' => "",
									 'OpenState' => 1,
									 'TestUserWalletModel' => 1,
									 'GameClassName' => "MG电子"
									 );
		}
/*
		$json = <<<Eof
{"ErrorCode":0,
	"Data":[
		{"GameClassID":3,"State":1,"CloseTime":"","OpenTime":"","OpenState":1,"TestUserWalletModel":1,"GameClassName":"AG视讯"},
		{"GameClassID":5,"State":0,"CloseTime":"","OpenTime":"","OpenState":1,"TestUserWalletModel":1,"GameClassName":"BBIN视讯"},
		{"GameClassID":4,"State":1,"CloseTime":"","OpenTime":"","OpenState":1,"TestUserWalletModel":1,"GameClassName":"LEBO视讯"},
		{"GameClassID":8,"State":1,"CloseTime":"","OpenTime":"","OpenState":1,"TestUserWalletModel":1,"GameClassName":"MG电子"}]}
Eof;
*/
header('Content-Type: application/json;charset=utf-8');
echo json_encode($data);
	}

	function GetMessageCount(){
		$this->verify_login($_SESSION['uid']);
		//{MemberName: "test2323", SiteNo: "00-88"}
		//{Phone: "86 1333123312333", SiteNo: "00-88"}
		//$get_data = file_get_contents("php://input");
		//$get = json_decode($get_data,true);
		$count = $this->Notice_model->GetSmsCount();
		$json = str_replace('--"', '',str_replace('"--', '', json_encode($count)));
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	function GetMoreNotices(){
		$this->verify_login($_SESSION['uid']);
		$get =$this->input->file_get();
		$siteno = trim($get['SiteNo']);
		$SearchData  = trim($get['SearchData']);
		$SearchDatas = explode("|", $SearchData);
		$sites = explode("_", $siteno);
		if(count($sites) != 2){
			$json = '{"ErrorCode":1,"Data":{}}';
			header('Content-Type: application/json;charset=utf-8');
			echo $json;
			exit();
		}
		$site_id = $sites[0];
		$index_id = $sites[1];
		$start_id = intval($SearchDatas[0]);
		$data = $this->Notice_model->GetHistiryNews($site_id,$index_id,$start_id);
		$json = str_replace('--"', '',str_replace('"--', '', json_encode($data)));
/*$json = <<<Eof
{"ErrorCode":0,
"Data":{"SearchData":"40206|635313852000000000",
"List":[
	{"ID":40996,"StartDateTime":"2015-12-22 09:00:00","Content":"尊敬的会员，玛雅吧快乐彩游戏隆重上线，业内最用心的快乐彩，给您无限时的极致娱乐体验。","Read":0},
	{"ID":40206,"StartDateTime":"2014-03-25 23:00:00","Content":"欢迎您的光临，玛雅吧正倾心打造业界最专业的娱乐投注平台，是目前亚洲市场上最具公信力的\\r\\n顶级娱乐网站。千万巨资出款无忧，是您值得信赖的娱乐博彩平台。","Read":0}
	]}}
Eof;*/

		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	//试玩注册
	function getTestMemberName(){
		$_SESSION['PKtoken']= $pk_token;
	    $_SESSION['PKtokenState']= 1;
	    $map = array();
	    $map['table'] = 'k_user';
	    $map['select'] = 'username';
	    $map['like']['title'] = 'username';
	    $map['like']['match'] = 'TEST';
	    $map['like']['after'] = 'after';
	    $map['order'] = 'uid desc';
	    $rs = $this->Member_model->rfind($map);
		$username = 'TEST' . rand(10, 99) . (substr($rs['username'], 6) + 1);
		$json = array();
		$json['ErrorCode'] = 0;
		$json['Data']['MemberName'] = $username;
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($json);
	}

	function GetTestMember(){
		$get =$this->input->file_get();
	    $_SESSION['PKtoken']= $pk_token;
	    $_SESSION['PKtokenState']= 1;
	    //获取测试代理商id
	    $agent_demo = $this->get_agent();
	    $data_s['username'] = $get['MemberName'];
	    $pwd = $get['Pwd'];
	    $data_s['password'] = md5(md5($pwd));
	    $data_s['money'] = 2000; //试玩赠送金额
	    $data_s['site_id'] = SITEID;
	    $data_s['index_id'] = INDEX_ID;
	    $data_s['agent_id'] = $agent_demo['id'];
	    $data_s['shiwan'] = 1;
	    $data_s['ptype'] = 1;
	    $shi_S = $this->Member_model->radd('k_user',$data_s);
	    	if($shi_S){
	    	    $_SESSION["shiwan"] = 1;
	    	    $_SESSION["uid"] = $shi_S;
	    	    $_SESSION["username"] = $username;
	    	    $_SESSION['agent_id'] = $agent_demo['id'];
	    	    $_SESSION['level_id'] = 0;
	    	    $json =array('ErrorCode'=>0,'Data'=>array('MemberName'=>$data_s['username'],'Pwd'=>$pwd));
	    	}

        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
	}

	//获取試玩代理ID
	public function get_agent() {
	    $map = array();
        $map['where']['is_demo'] = 1; //表示站点默认代理商
	    $map['table'] = 'k_user_agent';
	    $map['select'] = 'id,intr,pid';
	    $map['where']['site_id'] = SITEID;
	    $map['where']['agent_type'] = 'a_t';
	    return $this->Member_model->rfind($map);
	}

	function GetThirdPartyBanks(){
$json = <<<Eof
{"ErrorCode":0,"ErrorMsg":"执行成功","Data":{
	"BankAccount":{"ID":1181,"Platform":"DinPay"},
	"BankList":[{"BankLogo":"","BankName":"建设银行","BankNameAbbr":"CCB"},
	{"BankLogo":"","BankName":"招商银行","BankNameAbbr":"CMB"},
	{"BankLogo":"","BankName":"广东发展银行","BankNameAbbr":"GDB"},
	{"BankLogo":"","BankName":"华夏银行","BankNameAbbr":"HXB"}]}
}
Eof;

header('Content-Type: application/json;charset=utf-8');
echo $json;
	}

	function GetWallets(){
		//{GameClassIDs: 0}//游戏id
$postarr =$this->input->file_get();

    $postarr['GameClassIDs'];//3 ag，4 lebo，5 bbin,6 ct,7 pt,8 mg,9 og

    $uid = $_SESSION['uid'];
    $this->verify_login($_SESSION['uid']);
    $map['table'] = 'k_user';
    $key = "";
	if ($postarr['GameClassIDs'] == 0) {
		//刷新视讯额度
		$this->_getallbalance();
		$key = 'money';
	}else if($postarr['GameClassIDs'] == 3){
	 	$key = 'ag_money';
	}else if($postarr['GameClassIDs'] == 4){
		$key = 'lebo_money';
	}else if($postarr['GameClassIDs'] == 5){
		$key = 'bbin_money';
	}else if($postarr['GameClassIDs'] == 6){
		$key = 'ct_money';
	}else if($postarr['GameClassIDs'] == 7){
		$key = 'pt_money';
	}else if($postarr['GameClassIDs'] == 8){
		$key = 'mg_money';
	}else if($postarr['GameClassIDs'] == 9){
		$key = 'og_money';
	}

    $map['select'] = $key;
    $map['where']['index_id'] = INDEX_ID;
    $map['where']['site_id'] = SITEID;
    $map['where']['uid'] = $uid;
    $data = $this->Member_model->rfind($map);
    $WalletBalanceList[0] =array('GameClassID'=>0,'Balance'=> floatval($data[$key]),'RateBalance'=> floatval($data[$key]));
    $json = array('ErrorCode'=>0,'WalletBalanceList'=>$WalletBalanceList);


	    header('Content-Type: application/json;charset=utf-8');
	    echo json_encode($json);
	}

	//取款界面获取 取款限额
	function GetWithdrawLimit(){
		$this->verify_login($_SESSION['uid']);
		$this->load->model('Common_model');
		$data = $this->Common_model->get_user_level_info($_SESSION['level_id']);
		$json = '';
		$json['Data']['LimitType'] = 0;
		$json['Data']['LimitData']['MinWithdrawalMoney'] = $data['ol_atm_min'];
		$json['Data']['LimitData']['MaxWithdrawalMoney'] = $data['ol_atm_max'];
		$json['ErrorCode'] = 0;
		$json = json_encode($json);
		//{"ErrorCode":0,"Data":{"LimitType":0,"LimitData":{"MinWithdrawalMoney":"100","MaxWithdrawalMoney":"1000000"}}}
/*$json = <<<Eof
{"ErrorCode":0,"Data":{"LimitType":0,"LimitData":{"MinWithdrawalMoney":"100","MaxWithdrawalMoney":"1000000"}}}
Eof;*/

		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	function _getallbalance(){
   		$this->load->library('Games');
		$games = new Games();
   		$loginname = $_SESSION['username'];
   		$data = $games->GetAllBalance($loginname);
   		$result = json_decode($data);
		$data = json_decode($data,true);
		$this->load->model('Common_model');
		$copyright = $this->Common_model->get_copyright();
		$list = explode(',',$copyright['video_module']);
		if ($result->data->Code == 10017) {
			$data_u = array();
			foreach ($list as $key => $value) {
				$strstatus = $value.'status';
				$strbalance = $value.'balance';
				if (!empty($result->data->$strstatus)) {
					$data_u[$value.'_money'] = floatval($result->data->$strbalance);
				}
			}
			if (!empty($data_u)){
				$this->db->from('k_user');
				$this->db->where('site_id',SITEID);
				$this->db->where('uid',$_SESSION['uid']);
				$this->db->set($data_u);
				$this->db->update();
			}
		}
   }

	function App_Login($name,$pwd){
		$name_siteid = explode("_", $name);
		if(count($name_siteid) != 2){
			$data['ErrorCode'] = 4;
			$data['ErrorMsg'] = '执行失败';
	        header('Content-Type: application/json;charset=utf-8');
	        echo json_encode($data);
	        exit();
		}
        $log = $this->Member_model->Login($name_siteid[1],$pwd,'127.0.0.1','美国');

        if ($log == '2') {
	        $data['ErrorCode'] = 3;
	    }elseif($log == '3'){
	    	$data['ErrorCode'] = 1;
	    }elseif($log == '4'){
	        $data['ErrorCode'] = 2;
	    }

	    if ($log != '1') {
	        $data['ErrorMsg'] = '执行失败';
	        header('Content-Type: application/json;charset=utf-8');
	        echo json_encode($data);
	        exit();
	    }
	}



	//登录
	function Login(){
	    $postarr =$this->input->file_get();
        $username = $postarr['MemberName'];
        $passwore = $postarr['Pwd'];
        $login_id =  $this->Member_model->GetClientIp();
        $addrss =  $this->Member_model->GetIpAddrss($login_id);
        $log = $this->Member_model->Login($username,$passwore,$login_id,$addrss);
        //exit();
        if ($log == '2') {
	        $data['ErrorCode'] = 3;
	    }elseif($log == '3'){
	    	$data['ErrorCode'] = 1;
	    }elseif($log == '4'){
	        $data['ErrorCode'] = 2;
	    }

	    if ($log == '1') {
	    	$data['ErrorCode'] = 0;
	        $data['ErrorMsg'] = '执行成功';
	    }else{
	        $data['ErrorMsg'] = '执行失败';
	        header('Content-Type: application/json;charset=utf-8');
	        echo json_encode($data);
	        exit();
	    }

	    // $data['Data']['GameClassList'][5] = array('GameClassID'=>6,'TestUserWalletModel'=>1,'GameClassName'=>'CT视讯');
	    //$data['Data']['GameClassList'][6] = array('GameClassID'=>7,'TestUserWalletModel'=>1,'GameClassName'=>'PT视讯');
	    //$data['Data']['GameClassList'][8] = array('GameClassID'=>9,'TestUserWalletModel'=>1,'GameClassName'=>'OG视讯');
	    $data['Data']['Token']  = $_SESSION['ssid'];

	    $this->load->model('Common_model');
      	$copyright = $this->Common_model->get_copyright();
      	$copyright['video_module'] = strtolower($copyright['video_module']);
      	$video_modules = explode(",", $copyright['video_module']);

	    $data['Data']['GameClassList'][0] = array('GameClassID'=>1,'TestUserWalletModel'=>1,'GameClassName'=>'彩票游戏');
	    $data['Data']['GameClassList'][1] = array('GameClassID'=>2,'TestUserWalletModel'=>1,'GameClassName'=>'体育游戏');

	    if(in_array("ag", $video_modules)){
			$data['Data']['GameClassList'][] = array('GameClassID'=>3,'TestUserWalletModel'=>1,'GameClassName'=>'AG视讯');
		}
		if(in_array("lebo", $video_modules)){
			$data['Data']['GameClassList'][] = array('GameClassID'=>4,'TestUserWalletModel'=>1,'GameClassName'=>'LEBO视讯');
		}
		if(in_array("bbin", $video_modules)){
			$data['Data']['GameClassList'][] = array('GameClassID'=>5,'TestUserWalletModel'=>1,'GameClassName'=>'BBIN视讯');
		}
		if(in_array("mg", $video_modules)){
			$data['Data']['GameClassList'][] = array('GameClassID'=>8,'TestUserWalletModel'=>1,'GameClassName'=>'MG电子');
		}

	    //3 ag，4 lebo，5 bbin,6 ct,7 pt,8 mg,9 og
	    $data['Data']['RealName'] = empty($_SESSION['uname'])?"":$_SESSION['uname'];
	    $data['Data']['qkpwd'] = empty($_SESSION['qkpwd'])?"":$_SESSION['qkpwd'];
	    $data['Data']['State'] = 0;
	    if ($_SESSION['shiwan']) {
	        $data['Data']['TestState'] = 2;
	    }else{
	    	$data['Data']['TestState'] = 0;
	    }

	    $data['Data']['CountryNo'] = 'China';
	    $data['Data']['CurrencyNo'] = 'RMB';
	    $data['Data']['Poundage'] = '0.0000';

	    //登录以后session保存的数据
	    //暂时不支持跨域
	    /*
	    $_SESSION['uid'] = $loginS['uid'];
        $_SESSION['agent_id'] = $loginS['agent_id'];
        $_SESSION['username'] = $loginS['username'];
        $_SESSION['level_id'] = $loginS['level_id'];;
        $_SESSION['shiwan']   = $loginS['shiwan'];
        $_SESSION['ssid'] = session_id();
        */

	    header('Content-Type: application/json;charset=utf-8');
	    echo json_encode($data);
	}

	//退出
	function Logout(){
        //更新会员登录
        $this->Member_model->redis_del_user();
        @session_destroy();

        $data['ErrorCode'] = 0;
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($data);
	}

    function RegisterMember(){
     $get =$this->input->file_get();
		if($get['MemberName'] == $get['Pwd']){ //账号密码不能相同
			echo json_encode(array("ErrorCode" => 8));exit;
		}

		$Config = $this->Member_model->RegisterMember($get['MemberName']);
		if($Config[0] == 1){ //账号重复
			echo json_encode(array("ErrorCode" => 3));exit;
		}
        $RealName = $get['RealName'];
        $RealNameExist = $this->Member_model->RegisterRealName($RealName);
        if($RealNameExist == 1){ //真实姓名控制
        	echo json_encode(array("ErrorCode" => 7));exit;
        }

        $Phone = $get['PhoneNum'];
        if(!empty($Phone)){
        	$PhoneExist = $this->Member_model->RegisterPhone($Phone);
	        if($PhoneExist == 1){ //手机号控制
	        	echo json_encode(array("ErrorCode" => 2));exit;
	        }
        }

        $EMail = $get['EMail'];
        if(!empty($EMail)){
	        $EMailExist = $this->Member_model->RegisterEMail($EMail);
	        if($PhoneExist == 1){ //邮箱控制
	        	echo json_encode(array("ErrorCode" => 1));exit;
	        }
    	}
        if (!empty($EMail)) { //邮箱格式不正确
			if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $EMail)) {
				echo json_encode(array("ErrorCode" => 12));exit;
			}
		}
        $QQ = $get['QQ'];
        if (!empty($QQ)) {  //QQ格式不正确
        	$QQExist = $this->Member_model->RegisterQQ($QQ);
	        if($PhoneExist == 1){ //QQ控制
	        	echo json_encode(array("ErrorCode" => 10));exit;
	        }
			if (!preg_match("/^[1-9][0-9]{4,9}$/", $QQ)) {
				echo json_encode(array("ErrorCode" => 13));exit;
			}
		}
		/*
        if($get['WithdrawPwd'] != $get['drawPwdrpt']){  //取款密码不一致
        	echo json_encode(array("ErrorCode" => 15));exit;
        }*/
        if($get['Pwd'] != $get['RptPwd']){  //登录密码不一致
        	echo json_encode(array("ErrorCode" => 11));exit;
        }
        if (!preg_match("/^[0-9]{4}$/", $get['WithdrawPwd'])) {  //取款密码
			echo json_encode(array("ErrorCode" => 6));exit;
		}
        if (!preg_match("/^[a-zA-Z0-9]{4,11}$/", $get['MemberName'])) {  //账号格式不正确
			echo json_encode(array("ErrorCode" => 14));exit;
		}

        //会员所属代理商
        $deAgent = $this->Member_model->GetAgent();
        $agent_id = $deAgent['id'];
        $pid = $deAgent['pid'];
        $reg_ip = $this->Member_model->GetClientIp();
        $address = $this->Member_model->GetIpAddrss($reg_ip);
        //注册优惠
        $reg_state = $this->Member_model->RegDis($agent_id,$pid,$reg_ip);

        //注册默认层级
        $level_id = $this->Member_model->getUserlevel();

        $dataa = array();
        $dataa['username']    = $get['MemberName'];
        $dataa['ptype']       = 1;
        $dataa['password']    = md5(md5($get['Pwd']));
        $dataa['money']       = empty($reg_state['money'])?0:$reg_state['money'];
        $dataa['qk_pwd']      = $get['WithdrawPwd'];
        $dataa['mobile']      = $get['PhoneNum'];
        $dataa['email']       = $get['EMail'];
        $dataa['index_id']    = INDEX_ID;
        $dataa['reg_ip']      = $reg_ip;
        $dataa['login_ip']    = $reg_ip;
        $dataa['login_time']  = date("Y-m-d H:i:s");
        $dataa['pay_name']    = $get['RealName'];
        $dataa['lognum']      = 1;
        $dataa['reg_address'] = $address;
        $dataa['qq']          = $get['QQ'];
        $dataa['site_id']     = SITEID;
        $dataa['agent_id']    = $agent_id;
        $dataa['ua_id']    = $deAgent['pid'];//总代id
        $dataa['sh_id']    = $deAgent['sh_id'];//股东id
        $dataa['level_id']    = $level_id;
        $dataa['passport']    = $get['PassPort'];
        $dataa['birthday']    = $get['birthday1']."-".$get['birthday2']."-".$get['birthday3'];

        //添加注册信息
        $Code = $this->Member_model->AddMember($dataa,$reg_state,$address,$reg_ip);
        //$Code 0 成功 1 邮箱重复 2手机重复 3.账号重复 4.账号格式 5.密码格式
        //6.取款密码格式 7.姓名存在 8.密码和账号不能相同 9注册失败 10 QQ重复
        // 11 登录密码不一致  12邮箱格式不正确  13 QQ格式不正确  14账号格式不正确
        //15 取款密码不一致
        $json = json_encode(array("ErrorCode" => $Code));
        header('Content-Type: application/json;charset=utf-8');
        echo $json;
    }


    /*
	*绑定银行卡
	* ErrorCode 1 已经绑定了  重复绑定
    */
	function SaveMemberBank(){
		$this->verify_login($_SESSION['uid']);
	$get =$this->input->file_get();
	$json = $this->Member_model->SaveMemberBank($get);
	header('Content-Type: application/json;charset=utf-8');
	echo $json;
	}

	function SetReadNotices(){
		$this->verify_login($_SESSION['uid']);
		$get =$this->input->file_get();
		$data = $this->Notice_model->GetOneNews($get['NoticeID'],'k_message',0);
		$json = json_encode($data);

/*$json = <<<Eof
{
    "ErrorCode": 0,
    "SendContent": "尊敬的会员，感谢您成为玛雅吧的会员，在这里您可以体验高效快速的存取款业务。我们推荐玛雅娱乐的真人视讯，一边看美女一边玩游戏，游戏娱乐两不误！此外我们对接的高频彩全天24小时开奖，更高的彩票赔率，让您时时畅玩不停，娱乐不断。3D真人电子游戏，独创路珠记牌模式，好玩更刺激！感受心动不如赶快行动！玛雅吧祝您游戏愉快！",
    "SendDateTime": "2015-12-27 10:02:05"
}
Eof;*/

		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

//验证注册的信息
	function VerifyRegisterMember(){
		//{MemberName: "zxcqweqweqwe", SiteNo: "t_a"} //验证用户名
		//{RealName: "zxcvzcxv", SiteNo: "t_a"}
		//{Phone: "86 11345123333", SiteNo: "t_a"}
		//{EMail: "qweqe@11.com", SiteNo: "t_a"}

/*
$json = <<<Eof
{
    "ErrorCode": 0,
    "SendContent": "尊敬的会员，感谢您成为玛雅吧的会员，在这里您可以体验高效快速的存取款业务。我们推荐玛雅娱乐的真人视讯，一边看美女一边玩游戏，游戏娱乐两不误！此外我们对接的高频彩全天24小时开奖，更高的彩票赔率，让您时时畅玩不停，娱乐不断。3D真人电子游戏，独创路珠记牌模式，好玩更刺激！感受心动不如赶快行动！玛雅吧祝您游戏愉快！",
    "SendDateTime": "2015-12-27 10:02:05"
}
Eof;
*/
//$json = <<<Eof
//{"ErrorCode":0,"Data":{"MemberNameExist":0,"IsTryPlay":0,"EMailExist":0,"PhoneExist":0,"RealNameExist":0}}
//Eof;
            $postarr =$this->input->file_get();
            if($postarr['MemberName'] && $postarr['RealName'] && $postarr['Phone'] && $postarr['EMail']){
                $MemberName = $postarr['MemberName'];
                $MemberNameExist = $this->Member_model->RegisterMember($MemberName);
                $RealName = $postarr['RealName'];
                $RealNameExist = $this->Member_model->RegisterRealName($RealName);
                $Phone = explode(' ', $postarr['Phone'])[1];
                $PhoneExist = $this->Member_model->RegisterPhone($Phone);
                $EMail = $postarr['EMail'];
                $EMailExist = $this->Member_model->RegisterEMail($EMail);
                $json = json_encode(array('ErrorCode' => 0,'Data' => array("MemberNameExist" => $MemberNameExist,"RealNameExist" => $RealNameExist,"PhoneExist" => $PhoneExist,"EMailExist" => $EMailExist)));
            }
            if($postarr['MemberName']){
                $MemberName = $postarr['MemberName'];
                $Config = $this->Member_model->RegisterMember($MemberName);
                $MemberNameExist = $Config[0];
                $RC = $Config[1];
                $json = json_encode(array('ErrorCode' => 0,'Data' => array("MemberNameExist" => $MemberNameExist,
                    "EmailCS" => $RC[0][0],"EmailMT" => $RC[0][1],
                    "PassPortCS" => $RC[1][0],"PassPortMT" => $RC[1][1],
                    "QQCS" => $RC[2][0],"QQMT" => $RC[2][1],
                    "MobileCS" => $RC[3][0],"MobileMT" => $RC[3][1])));
            }
            if($postarr['RealName']){
                $RealName = $postarr['RealName'];
                $RealNameExist = $this->Member_model->RegisterRealName($RealName);
                $json = json_encode(array('ErrorCode' => 0,'Data' => array("RealNameExist" => $RealNameExist)));
            }
            if($postarr['Phone']){
                $Phone = explode(' ', $postarr['Phone'])[1];
                $PhoneExist = $this->Member_model->RegisterPhone($Phone);
                $json = json_encode(array('ErrorCode' => 0,'Data' => array("PhoneExist" => $PhoneExist)));
            }

            if($postarr['EMail']){
                $EMail = $postarr['EMail'];
                $EMailExist = $this->Member_model->RegisterEMail($EMail);
                $json = json_encode(array('ErrorCode' => 0,'Data' => array("EMailExist" => $EMailExist)));
            }
            if($postarr['QQ']){
                $QQ = $postarr['QQ'];
                $QQExist = $this->Member_model->RegisterQQ($QQ);
                $json = json_encode(array('ErrorCode' => 0,'Data' => array("QQExist" => $QQExist)));
            }
            header('Content-Type: application/json;charset=utf-8');
            echo $json;
	}




}
?>