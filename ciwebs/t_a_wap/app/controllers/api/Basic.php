<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Basic extends MY_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Index_model');
    }
	//站点配置
	function GetSiteBasicConfig(){
		//{domain: "index_ta.com"}
		//
		$config = $this->Index_model->GetSiteBasicConfig(SITEID,INDEX_ID);
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($config);
	}

	//banner图片
	function GetPlayImages(){
		//{siteNo: "00-88", LanguageNo: "zh_cn"}
        $imgs = $this->Index_model->GetPlayImages(SITEID,INDEX_ID);
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($imgs);
	}

    //游戏类别
	function GetGameState(){
		//playlaod {SiteNo: "00-88"}
		$games = $this->Index_model->GetGameState(SITEID,INDEX_ID);
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($games);
	}

	function GetEGames(){
		//{PageSize: 18, CurrentPage: 2, SiteNo: "t_a", LanguageNo: "zh_cn"}
		//获取电子游戏
		$postarr =$this->input->file_get();

		$type = "mgh5";
		$limit = intval($postarr['CurrentPage']);
		$maxcount  = intval($postarr['PageSize']);
		$this->load->model('webcenter/Egame_model');
		$conf['site_id'] = SITEID;
		$conf['index_id'] = INDEX_ID;

		$map['type'] = "mgh5";
		$map['status'] = 1;
		if(empty($limit) || $limit == 0 ){
			$limit = 1;
		}

		$list = $this->Egame_model->get_game($map,$limit - 1,$maxcount);
		foreach ($list as $key => $value) {
			$list[$key]['image'] = "/public/official/images/mg/".$value['image'];
		}

		$data = array();
		$data['ErrorCode'] = 0;
		$data['ErrorMsg'] = "执行成功";
		$data['Data'] = array();
		$data['Data']['CurrentPage'] = $limit;
		$data['Data']['List'] = $list ;

		//$allpage = ceil($count/18);
		//$data[0]['allpage'] = $allpage;
		//$data[0]['page'] = $limit;
		//$data[0]['top_id'] = $topid;
		//$data[0]['typeOf'] = $type;

		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($data);


	}

	function GetCitys(){
		$postarr =$this->input->file_get();
		$address = $this->Index_model->address();
		$data = $address->$postarr['ProvinceName'];
		foreach ($data as $key => $value) {
			$info[]['Name'] = $key;
		}
		//$info = $data->$postarr['ProvinceName'];
		$json['ErrorCode'] = 0;
		$json['Data'] = $info;

/*$json = <<<Eof
{"ErrorCode":0,"Data":[{"Name":"和平"},{"Name":"河东"},{"Name":"河西"},{"Name":"南开"},{"Name":"河北"},{"Name":"红桥"},{"Name":"滨海新区"},{"Name":"东丽"},{"Name":"西青"},{"Name":"津南"},{"Name":"北辰"},{"Name":"宁河"},{"Name":"武清"},{"Name":"静海"},{"Name":"宝坻"},{"Name":"蓟县"}]}
Eof;*/

	header('Content-Type: application/json;charset=utf-8');
	echo json_encode($json);
	}

	function GetDepositTypeList(){

		///{"ErrorCode":0,"ErrorMsg":"执行成功","Data":[{"ID":1,"TypeName":"网银转账"},{"ID":2,"TypeName":"ATM自动柜员机"},{"ID":3,"TypeName":"ATM现金入款"},{"ID":4,"TypeName":"银行柜台"},{"ID":5,"TypeName":"手机转帐"},{"ID":6,"TypeName":"支付宝转账"},{"ID":7,"TypeName":"财付通"},{"ID":8,"TypeName":"微信支付"}]}
		//支付宝、财付通、微信
		$json = <<<Eof
{"ErrorCode":0,"ErrorMsg":"执行成功","Data":[{"ID":6,"TypeName":"支付宝转账"},{"ID":7,"TypeName":"财付通"},{"ID":8,"TypeName":"微信支付"},{"ID":4,"TypeName":"网银转账"},{"ID":5,"TypeName":"ATM自动柜员机"},{"ID":6,"TypeName":"ATM现金入款"},{"ID":7,"TypeName":"银行柜台"},{"ID":5,"TypeName":"手机转帐"}]}
Eof;
		header('Content-Type: application/json;charset=utf-8');
		echo $json;

	}

		//当天的输赢
	function GetTodayWinLossWithMember(){
		$this->verify_login($_SESSION['uid']);
		$this->load->model('member/Reports_model');
	    $data = $this->Reports_model->GetTodayWinLossWithMember($_SESSION['uid']);
	    if (empty($data['win'])) {
	        $data['win'] = 0;
	    }
        $json = '{"ErrorCode": 0, "Data": {"WinLoss": '.$data['win'].'}}';
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	//获取平台余额
	function GeMaintBalance(){
//{"ErrorCode":0,"Data":{"Balance":2000.0}}
	    $uid = $_SESSION['uid'];
	    $this->verify_login($_SESSION['uid']);
	    $map['table'] = 'k_user';
		$key = 'money';
	    $map['select'] = $key;
	    $map['where']['index_id'] = INDEX_ID;
	    $map['where']['site_id'] = SITEID;
	    $map['where']['uid'] = $uid;
	    $data = $this->Member_model->rfind($map);
	    $maindata = array('Balance'=> floatval($data[$key]));
	    $json = array('ErrorCode'=>0,'Data'=>$maindata);
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

//不用去掉 快速转载到彩票
function GameFundTransfer(){
	//Amount: 100	
		$this->verify_login($_SESSION['uid']);
		$json = <<<Eof
{"ErrorCode":0}
Eof;
		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}


	function GetMemberBalance(){
	    $uid = $_SESSION['uid'];
	    $this->verify_login($_SESSION['uid']);
	    $map['table'] = 'k_user';
	    $map['select'] = 'money';
	    $map['where']['index_id'] = INDEX_ID;
	    $map['where']['site_id'] = SITEID;
	    $map['where']['uid'] = $uid;
	    $data = $this->Index_model->rfind($map);
	    $json = array('ErrorCode'=>0,'Data'=>array('Balance'=> floatval($data['money'])));
	    header('Content-Type: application/json;charset=utf-8');
	    echo json_encode($json);
	}

    //首页滚动公告
	function GetNewNotices(){
		$data = $this->Index_model->GetNewNotices(SITEID,INDEX_ID);
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($data);
	}

	function GetProvinces(){
		$address = $this->Index_model->address();
		foreach ($address as $key => $value) {
			$info[]['Name'] = $key;
		}
		$json['ErrorCode'] = 0;
		$json['Data'] = $info;
		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($json);
	}

	function GetSiteBanks(){
        $map['table'] = 'k_bank_cate';
        $map['where']['state'] = 1;
        $result = $this->Index_model->rget($map);
        $data = array();

        foreach ($result as $key => $value) {
            $data[$key] = array('BankID' => $value['id'],'BankName' => $value['bank_name']);
        }
        rsort($data);
        //倒序
        $json = array('ErrorCode' => 0,'Data' => $data);

        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
	}

	function GetSiteCountrys(){
		//{SiteNo: "00-88", LanguageNo: "zh_cn", CountryNo: "China"}

	$json = <<<Eof
{"ErrorCode":0,"ErrorMsg":"执行成功","Data":[{"CountryNo":"China","CountryName":"中国","CountryAreaCode":86}]}
Eof;

		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}

	function GetSiteCurrencys(){
		//{SiteNo: "00-88", LanguageNo: "zh_cn", CountryNo: "China"}

		$json = <<<Eof
{"ErrorCode":0,"ErrorMsg":"执行成功","Data":[{"CurrencyNo":"RMB","CurrencyName":"人民币"}]}
Eof;

		header('Content-Type: application/json;charset=utf-8');
		echo $json;
	}


	function Notices(){
        $map = " (sid = '0' or sid = '" .SITEID. "') AND notice_cate in(1,2,3) AND notice_state = 1 ";
        $notice = $this->Index_model->db->select('notice_content')->where($map)->order_by('notice_date DESC')->get('site_notice',0,3)->result_array();
        $data = array();
        foreach ($notice  as $key => $value) {
        	$data[] = array('Content' => $value['notice_content']);
        }
        $json = array('ErrorCode' => 0,'Data' => $data);
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
	}

	function SSO(){
//{
//	"VenderNo":"12345abcde",
//	"DESDATA":"C74D623ED13093591502684F78794079553B2D4703EAD0FB43C5311DA1DA1F08531AE946F838B260C25002CE7EDB5B9EA8D8F02F6B87B8F68BB52F012CE85DBCD4C49F1ABC345279414A225B4B28B84D5DD138605FD6878DBF5EFF7650E99DF808D2956E4D6B764353FF577ACAE8D92E0EE99866FA0B7BB4DEFB9BBF23F1BE510827C291CA0919FD6EAE3B1D6CA0EC8DFCD1B493980CA379B8575BF9F19661C67AA63B090D6FC429D7924FD44C5A7675D42BFF8AC522BDBAE064644340123F4532E0FD1C0A1D427E46D335413C090F81D6E712C68E335E6943B87CD2510EC8CA498A143E15186572"
//}
 /* //tfa54n7guu1b3bee8cjes6rl03

	    $_SESSION['uid'] = $loginS['uid'];
        $_SESSION['agent_id'] = $loginS['agent_id'];
        $_SESSION['username'] = $loginS['username'];
        $_SESSION['level_id'] = $loginS['level_id'];;
        $_SESSION['shiwan']   = $loginS['shiwan'];
        $_SESSION['ssid'] = session_id();
        */
        // /lot/#/sso/1/0
        //$this->verify_login($_SESSION['uid']);
        $get =$this->input->file_get();

        if ($_SESSION['shiwan']) {
	        $TestState = 2;
	    }else{
	    	$TestState = 0;
	    }
	    $data = array();
	    $data['ErrorCode']  = 0;
	    $data['Data']  = array();;
		$data['Data']["Token"] = $_SESSION['ssid'];
		$data['Data']["User"] = array();
		$data['Data']["User"]["PageStyle"] = "default";
		$data['Data']["User"]["LanguageNo"] = "zh_cn";
		$data['Data']["User"]["MemberName"] = empty($_SESSION['username'])?"": $_SESSION['username'];
		$data['Data']["User"]["memberId"] = intval($_SESSION['uid']);
		$data['Data']["User"]["TestState"] = intval($TestState);
		//六合彩信息
		$data['Data']['BaseInfo'] =  array();
		$data['Data']['BaseInfo']['SxList'] = array(
			'羊' => array(1,13,25,37,49),
			'马' => array(2,14,26,38),
			'蛇' => array(3,15,27,39),
			'龙' => array(4,16,28,40),
			'兔' => array(5,17,29,41),
			'虎' => array(6,18,30,42),
			'牛' => array(7,19,31,43),
			'鼠' => array(8,20,32,44),
			'猪' => array(9,21,33,45),
			'狗' => array(10,22,34,46),
			'鸡' => array(11,23,35,47),
			'猴' => array(12,24,36,48)
			 );
		$data['Data']['BaseInfo']['GameId'] = intval($get['VenderNo']);

		header('Content-Type: application/json;charset=utf-8');
		echo json_encode($data);

/*
//GameId  游戏类型
//生成游戏链接
$json = <<<Eof
{"ErrorCode":0,"Data":{"Token":"$_SESSION['ssid']",
"User":{"PageStyle":"default",
"LanguageNo":"zh_cn",
"MemberName":"$_SESSION['username']",
"memberId":$_SESSION['uid'],"TestState":$TestState},
"BaseInfo":{"SxList":{"羊":[1,13,25,37,49],"马":[2,14,26,38],"蛇":[3,15,27,39],"龙":[4,16,28,40],"兔":[5,17,29,41],
"虎":[6,18,30,42],
"牛":[7,19,31,43],
"鼠":[8,20,32,44],
"猪":[9,21,33,45],"狗":[10,22,34,46],"鸡":[11,23,35,47],"猴":[12,24,36,48]},
"GameId":$get['VenderNo'] }}}
Eof;

		header('Content-Type: application/json;charset=utf-8');
		echo $json;*/
	}

}
?>