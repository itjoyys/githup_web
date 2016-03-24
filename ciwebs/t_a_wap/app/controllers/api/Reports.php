<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends MY_Controller
{
   public function __construct() {
        parent::__construct();
        $this->load->model('member/Reports_model');
        $this->verify_login($_SESSION['uid']);
    }

    //获取当前时间
    function getDayTime(){
        $tmp_date = date('Y-m-d');
        $data = array();
        $data['ErrorCode'] = 0;
        $data['Data']['AccountDate'] = $tmp_date;
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($data);
    }
    //获取会员游戏下注类型
	function GetAccountByGame(){
        $postarr =$this->input->file_get();

        $bets = $this->Reports_model->GetALLBetByGame($postarr['GameClassID'],$_SESSION['uid'],$_SESSION['username'],$postarr['DateTime']);
        $json = str_replace('--"', '',str_replace('"--', '', json_encode($bets)));

        header('Content-Type: application/json;charset=utf-8');
        echo $json;
	}
    //当天各个游戏盈利情况
	function GetAccountByGameClass(){

        $get_data = file_get_contents("php://input");
        $postarr = json_decode($get_data, true);

        $bets = $this->Reports_model->GetALLBetByGameClass($_SESSION['uid'],$_SESSION['username'],$postarr['DateTime']);
        // p($bets);die();

        $json = str_replace('--"', '',str_replace('"--', '', json_encode($bets)));

        header('Content-Type: application/json;charset=utf-8');
        echo $json;
	}

    //一天时间
	function GetAccountByGameDetail(){
        //{DateTime: "today", GameClassID: "fc", PageSize: 10, CurrentPage: 1}
	    $get_data = file_get_contents("php://input");
	    $postarr = json_decode($get_data, true);
	    $bets = $this->Reports_model->lottery_today($postarr['GameID'],$postarr['DateTime'],$postarr['DateTime'],$postarr['PageSize'],$postarr['CurrentPage']);
		//p($bets);
		foreach ($bets as $k=>$v){
		    $Data[$k]['GameNo'] = $v['type'];
		    $Data[$k]['BetDetail'] = $v['mingxi_1']."@".$v['odds']." ".$v['mingxi_2'];
		    $Data[$k]['BetNo'] = $v['did'];
		    $Data[$k]['BetDateTime'] = $v['update_time'];
		    $Data[$k]['BetMoney'] = (float)$v['money'];
		    $Data[$k]['BackMoney'] = (float)$v['money'];
		    $Data[$k]['WinLoseMoney'] = (float)$v['win'];
		}
		$json = array('ErrorCode'=>0,'Data'=>array('Result'=>$Data));
// 		$json = <<<Eof
// {
//     "ErrorCode": 0,
//     "Data": {"Result":[
//         {
//             "GameNo":"六合彩",
//             "BetDetail":"特码 @47.4 12",
//             "BetNo":"1111111",
//             "BetDateTime": "2015-12-26",
//             "BetMoney": 100,
//             "BackMoney":100,
//             "WinLoseMoney": 100
//         },{
//             "GameNo":"六合彩",
//             "BetDetail":"特码 @47.4 12",
//             "BetNo":"222222",
//             "BetDateTime": "2015-12-26",
//             "BetMoney": 100,
//             "BackMoney":100,
//             "WinLoseMoney": 100
//         }
//     ]}
// }
// Eof;

        header('Content-Type: application/json;charset=utf-8');
        //echo $json;
        echo json_encode($json);
	}

	function GetAccountByGameDetailItems(){
        //获取七天所有投注信息
       // $bets = $this->Reports_model->GetALLBet($type);

        // $json = str_replace('--"', '',str_replace('"--', '', json_encode($bets)));


        header('Content-Type: application/json;charset=utf-8');
        echo $json;
	}
    //获取会员最近七天所有投注总额
	Public function GetAccountByWeek(){
        //获取七天所有投注信息 $uid $username
        $bets = $this->Reports_model->GetALLBet($_SESSION['uid'],$_SESSION['username']);
        // p($bets);die();

        $json = str_replace('--"', '',str_replace('"--', '', json_encode($bets)));

        header('Content-Type: application/json;charset=utf-8');
        echo $json;
	}

    //取款记录
	function GetDepositReport(){
        $get_data = file_get_contents("php://input");
        $get = json_decode($get_data, true);
        $json = $this->Reports_model->GetDepositReport($_SESSION['uid'],$get);
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
	}

    function GetWithdrawReport(){
        $get_data = file_get_contents("php://input");
        $get = json_decode($get_data, true);
        $json = $this->Reports_model->GetWithdrawReport($_SESSION['uid'],$get);
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
    }

    //入款记录
	function GetGiveLogs(){
//空 {"ErrorCode":0,"Data":{"SearchTime":"2015-12-27 12:14:26","List":[]}}
$json = <<<Eof
{"ErrorCode":0,"Data":{"SearchTime":"2015-12-27 12:19:45","List":[]}}
Eof;

header('Content-Type: application/json;charset=utf-8');
echo $json;
	}

    //游戏间换转记录
	function GetTransferLogs(){
		$uid = $_SESSION['uid'];
        $cash_record = $this->Reports_model->get_cash_record($uid);
        $json = str_replace('--"', '',str_replace('"--', '', json_encode($cash_record)));
        header('Content-Type: application/json;charset=utf-8');
        echo $json;
	}
}
?>