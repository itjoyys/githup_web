<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voide extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function bbin(){
		$this->display('bbin.html');
	}
	public function lebo(){
		$this->display('lebo.html');
	}

	public function ag(){
		$this->load->model('Common_model');
		$this->Common_model->login_check($_SESSION['uid']);
		$g_type = trim("ag");
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
		$this->load->model('Video_model');
		$limitval = $this->Video_model->get_limitval($userinfo,$g_type);
		$loginname = $userinfo["username"];
		$gametype = "11";
		$lang = "CN";
		$cur = "RMB";
		$limitype="";
		$this->load->library('Games');
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
		if ($g_type == "ag") {
			$title = "AG Game";
			echo $this->echo_js($url);
		}
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