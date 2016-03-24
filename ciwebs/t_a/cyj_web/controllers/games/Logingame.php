<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logingame extends MY_Controller
{

	public function __construct()
	{
	  	parent::__construct();
	  	$this->load->library('Games');
	  	$this->load->model('games/Games_model');
	}

  	public function index(){
		$g_type = trim(@$this->input->get("g_type"));
		$get_sw = trim(@$this->input->get("sw"));

		if($g_type == ''){
			$g_type = 'mg';
		}
		$g_type_arr = array("ag", "mg", "eg");
		if (!in_array($g_type, $g_type_arr)) {
			$this->message('未知的游戏!');exit;
		}

		$data = $this->Games_model->GetUserName();

		//暂时屏蔽eg正式线路
		if($g_type == 'eg' && $data['shiwan'] == 0 && $get_sw != 0){
			$sitearr = array('t','hun');
			if(!in_array(SITEID, $sitearr)){
				$this->message('即将开放，敬请期待!');exit;
			}
		}

		//屏蔽试玩账号
		if ($data['shiwan'] == '1' && $g_type != 'eg') {
			$this->message('请用正式账号登陆!');exit;
		}
		if (empty($data) OR empty($data["username"])) {
			$this->message('请登录再进行游戏!');exit;
		}
		if($data['shiwan'] == 0 && $g_type == 'eg' && $get_sw == 0){
			$this->message('请先注册试玩账号!');exit;
		}
		if($data['shiwan'] == 1 && $g_type == 'eg' && $get_sw == 1){
			$this->message('请用正式账号登陆!');exit;
		}
		if($g_type == 'eg'){
			$sw = $data['shiwan'];
		}else{
			$sw = 0;
		}
		$loginname = $data["username"];
		$gameid = trim($this->input->get("gameid"));
		if (empty($gameid)) {
			$this->message('请选择一个电子游戏!');exit;
		}
		$Games = new Games;
		$lang = "CN";
		$cur = "RMB";
		$indexid = INDEX_ID;
		$url = $Games->forwarddz($g_type,$loginname, $gameid, $lang, $cur, $sw);
		//echo $url;exit();
		$pos1 = strpos($url, "result");
		$pos2 = strpos($url, "data");
		if ($pos1 > 0 && $pos2 > 0) {
			$result = json_decode($url);
			if ($result->data->Code == 10006) {
				$data = $Games->CreateAccount($loginname, $data["agent_id"], $g_type,$indexid, $cur,$sw);
				if (!empty($data)) {
					$result = json_decode($data);
					if ($result->data->Code != 10011) {
						$this->message('网络错误，请联系管理员!');exit;
					} else {
						$url = $Games->forwarddz($g_type,$loginname, $gameid, $lang, $cur, $sw);
						$pos1 = strpos($url, "result");
						$pos2 = strpos($url, "data");
						if ($pos1 > 0 && $pos2 > 0) {
							$this->message('网络错误，请联系管理员!');exit;
						}
					}
				}
			} else {
				$this->message('网络错误，请联系管理员!');exit;
			}
		}
		$title = "TOTALeGAME " . $gameid;
		if($g_type == 'eg'){
			echo $url;exit;
		}else{
			echo $this->echo_frame($url, $title);exit;
		}

	}

	//PT登陆
	public function loginpt(){
		$data = $this->Games_model->GetUserName();
		//试玩账号屏蔽
		if ($data['shiwan'] == '1') {
			$this->message('请用正式账号登陆!');exit;
		}
		if (empty($data) OR empty($data["username"])) {
			$this->message('请登录再进行游戏!');exit;
		}
		$loginname = $data["username"];
		$gameid = trim($this->input->get("gameid"));
		if (empty($gameid)) {
			$this->message('请选择一个电子游戏!');exit;
		}
		$data_a = $this->Games_model->GetPTUser($loginname);
		$dom = $_SERVER['HTTP_HOST'];
		$games = new Games();
		if($data_a){
			$result = $games->killsession($loginname);
			$result = json_decode($result);
			if($result->data->Code != 10011){
				$url = PTLOGIN_API . "/loginpt.html#u=".$data_a['g_username']."&p=".$data_a['password']."&callback=http://".$dom."/index.php/games/Logingame/loginptdz?gameid=".$gameid."&lang=en";
				$title = "PT Games " . $gameid;
				echo $this->echo_frame($url, $title);
			}else{
				$this->message('网络错误，请重试!');exit;
			}
		}else{
			$cur = "CNY";
			$index_id = INDEX_ID;
			$g_type = "pt";
			$data_b = $games->CreateAccount($loginname, $data["agent_id"], $g_type,$index_id, $cur);
			if (!empty($data_b)) {
				$result = json_decode($data_b);
				if ($result->data->Code != 10011) {
					$this->message('网络错误，请联系管理员!');exit;
				} else {
					$data_c = $this->Games_model->GetPTUser($loginname);
					$url = PTLOGIN_API . "/loginpt.html#u=".$data_c['g_username']."&p=".$data_c['password']."&callback=http://".$dom."/index.php/games/Logingame/loginptdz?gameid=".$gameid."&lang=en";
					$title = "PT Games " . $gameid;
					echo $this->echo_frame($url, $title);
					$pos1 = strpos($url, "result");
					$pos2 = strpos($url, "data");
					if ($pos1 > 0 && $pos2 > 0) {
						$this->message('网络错误，请联系管理员!');exit;
					}
				}
			}
		}
	}

	public function loginptdz(){
		$data = $this->Games_model->GetUserName();
		//试玩账号屏蔽
		if ($data['shiwan'] == '1') {
			$this->message('请用正式账号登陆!');exit;
		}
		if (empty($data) OR empty($data["username"])) {
			$this->message('请登录再进行游戏!');exit;
		}
		$loginname = $data["username"];
		$gameid = trim($this->input->get("gameid"));
		if (empty($gameid)) {
			$this->message('请选择一个电子游戏!');exit;
		}
		$lang = "ZH-CN";
		$ip = $this->get_client_ip();
		$cur = "CNY";
		$index_id = INDEX_ID;
		$g_type = "pt";
		$producttype = 0;
		$games = new Games();
		$url = $games->forwardptdz($loginname, $gameid, $lang,$ip ,$producttype);
		$pos1 = strpos($url, "result");
		$pos2 = strpos($url, "data");
		if ($pos1 > 0 && $pos2 > 0) {
			$result = json_decode($url);
			if ($result->data->Code == 10006) {
				$data = $games->CreateAccount($loginname, $data["agent_id"], $g_type,$index_id, $cur);
				if (!empty($data)) {
					$result = json_decode($data);
					if ($result->data->Code != 10011) {
						$this->message('网络错误，请联系管理员!');exit;
					} else {
						$url = $games->forwardptdz($loginname, $gameid, $lang,$ip,$producttype);
						$pos1 = strpos($url, "result");
						$pos2 = strpos($url, "data");
						if ($pos1 > 0 && $pos2 > 0) {
							$this->message('网络错误，请联系管理员!');exit;
						}
					}
				}
			} else {
				$this->message('网络错误，请联系管理员!');exit;
			}
		}
		$title = "PT Games " . $gameid;
		echo $this->echo_frame($url, $title);
	}

	public function PtDemo(){
		$g_type = "pt";
		echo "<script>alert('您进入的是PT试玩线路！')</script>";
		$games = new Games();
		$lang = "ZH-CN";
		$gameid = $_GET['gameid'];
		$url = $games->forwardptswdz($lang, $gameid,$currencycode);
		if($url){
			echo $this->echo_frame($url, $title);
		}else{
			echo "对不起，该游戏暂时不能试玩！";
		}
	}

	function echo_frame($url, $title) {
$str = <<<EOF
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>$title</title>
</head>
<FRAMESET ROWS="*" BORDER="0" FRAMEBORDER="0" FRAMESPACING="0">
<frame name="casinoFrame" src="$url" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="NO" NORESIZE="NORESIZE" />
<noframes>
<BODY bgcolor="#000000" CLASS="bodyDocument" SCROLL="NO">Frame support is required.<BR></BODY>
</noframes>
</FRAMESET>
</html>
EOF;

	return $str;
}


	function message($value,$url=""){ //默认返回上一页
		header("Content-type: text/html; charset=utf-8");
		$js  ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>message</title>
	</head>

	<body>';
		$js  .= "<script type=\"text/javascript\" language=\"javascript\">\r\n";
		$js .= "alert(\"".$value."\");\r\n";
		if($url) $js .= "window.location.href=\"$url\";\r\n";
		else $js .= "window.history.go(-1);\r\n";
		$js .= "</script>\r\n";
	$js.="</body></html>";
		echo $js;
		exit;
	}

	public function get_client_ip(){
	    $realip = '';
	    $unknown = 'unknown';
	    if (isset($_SERVER)){
	        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
	            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	            foreach($arr as $ip){
	                $ip = trim($ip);
	                if ($ip != 'unknown'){
	                    $realip = $ip;
	                    break;
	                }
	            }
	        }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){
	            $realip = $_SERVER['HTTP_CLIENT_IP'];
	        }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){
	            $realip = $_SERVER['REMOTE_ADDR'];
	        }else{
	            $realip = $unknown;
	        }
	    }else{
	        if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){
	            $realip = getenv("HTTP_X_FORWARDED_FOR");
	        }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){
	            $realip = getenv("HTTP_CLIENT_IP");
	        }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){
	            $realip = getenv("REMOTE_ADDR");
	        }else{
	            $realip = $unknown;
	        }
	    }
	    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
	    return $realip;
	}

}



