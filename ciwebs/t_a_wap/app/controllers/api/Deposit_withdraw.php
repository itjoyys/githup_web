<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Deposit_withdraw extends MY_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('member/Deposit_withdraw_model');
	}

	function AddThirdPartyDeposit(){
		$this->verify_login($_SESSION['uid']);
		$get =$this->input->file_get();
		$json = $this->Deposit_withdraw_model->AddThirdPartyDeposit($get);

		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($json);
	}

	//获取第三方银行信息
	function GetThirdPartyBanks(){
		$this->verify_login($_SESSION['uid']);
		$json = $this->Deposit_withdraw_model->GetThirdPartyBanks();
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($json);
	}

	//获取点卡信息
	function GetThirdPartyCardBanks(){
		$this->verify_login($_SESSION['uid']);
		$json = $this->Deposit_withdraw_model->GetThirdPartyCardBanks();
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($json);
	}
}
?>