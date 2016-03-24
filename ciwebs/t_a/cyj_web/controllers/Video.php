<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->library('Games');
		$this->load->model('Common_model');
		$this->load->model('Video_model');
	}

	public function rule(){
		$this->display('video/rule.html');
	}

	public function login(){
		$this->Common_model->login_check($_SESSION['uid']);
		$g_type = trim($this->input->get('g_type'));
		$g_type_arr = array("og", "ag", "mg", "ct", "bbin", "lebo");
		if (!in_array($g_type, $g_type_arr)) {
			message('未知的游戏!');exit;
		}
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		if ($userinfo['shiwan'] == '1') {
			message('请用正式账号登陆!');exit;
		}
		if (empty($userinfo) OR empty($userinfo["username"])) {
			message('请登录再进行游戏!');exit;
		}
		$limitval = $this->Video_model->get_limitval($userinfo,$g_type);
		$loginname = $userinfo["username"];
		$gametype = "1";
		$lang = "CN";
		$cur = "RMB";
		$limitype="";

		$games = new Games();
		$url = $games->forwardGame($loginname, $g_type, $gametype,$limitval,$limitype,  $lang, $cur);
		$pos1 = strpos($url, "result");
		$pos2 = strpos($url, "data");
		if ($pos1 > 0 && $pos2 > 0) {
			$result = json_decode($url);
			if ($result->data->Code == 10006) {
				$data = $games->CreateAccount($loginname, $userinfo["agent_id"], $g_type,$userinfo['index_id'], $cur);
				if (!empty($data)) {
					$result = json_decode($data);
					if ($result->data->Code != 10011) {
						message('网络错误，请联系管理员!');exit;
					} else {
						$url = $games->forwardGame($loginname, $g_type, $gametype,$limitval,$limitype, $lang, $cur);
						$pos1 = strpos($url, "result");
						$pos2 = strpos($url, "data");
						if ($pos1 > 0 && $pos2 > 0) {
							message('网络错误，请联系管理员!');exit;
						}
					}
				}
			} else {
				message('网络错误，请联系管理员!');exit;
			}
		}


		//是否是json
		if ($g_type == "og") {
			echo $url;
		} else if ($g_type == "ag") {
			$title = "AG Game";
			echo $this->echo_frame($url, $title);
		} else if ($g_type == "mg") {
			$title = "TOTALeGAME Lobby";
			echo $this->echo_frame($url, $title);
		} else if ($g_type == "ct") {
			echo $this->echo_js($url);
		} else if ($g_type == "bbin") {
			$title = "BBIN";
			echo $this->echo_js($url);
		} else if ($g_type == "lebo") {
			$title = "Lebo";
			echo $this->echo_js($url);
		}

	}

	public function loginmgdz(){
		$this->Common_model->login_check($_SESSION['uid']);
		$userinfo = $this->Common_model->get_user_info($_SESSION['uid']);
		//试玩账号屏蔽
		if ($userinfo['shiwan'] == '1') {
			message('请用正式账号登陆!');exit;
		}
		if (empty($userinfo) OR empty($userinfo["username"])) {
			message('请登录再进行游戏!');exit;
		}
		$loginname = $userinfo["username"];
		$gameid = trim($this->input->get('gameid'));
		if (empty($gameid)) {
			message('请选择一个电子游戏!');exit;
		}
		$lang = "CN";
		$cur = "RMB";
		$g_type = "mg";
		$games = new Games();
		$url = $games->forwarddz($loginname, $gameid, $lang, $cur);
		$pos1 = strpos($url, "result");
		$pos2 = strpos($url, "data");
		if ($pos1 > 0 && $pos2 > 0) {
			$result = json_decode($url);
			if ($result->data->Code == 10006) {
				$data = $games->CreateAccount($loginname, $userinfo["agent_id"], $g_type,$userinfo['index_id'], $cur);
				if (!empty($data)) {
					$result = json_decode($data);
					if ($result->data->Code != 10011) {
						message('网络错误，请联系管理员!');exit;
					} else {
						$url = $games->forwarddz($loginname, $gameid, $lang, $cur);
						$pos1 = strpos($url, "result");
						$pos2 = strpos($url, "data");
						if ($pos1 > 0 && $pos2 > 0) {
							message('网络错误，请联系管理员!');exit;
						}
					}
				}
			} else {
				message('网络错误，请联系管理员!');exit;
			}
		}
		$title = "TOTALeGAME " . $gameid;
		echo $this->echo_frame($url, $title);
	}



public function echo_frame($url, $title) {
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

public function echo_js($url) {
	$str = <<<EOF
<script type='text/javascript'>
window.document.location="$url";
</script>
EOF;
	return $str;
}
}
