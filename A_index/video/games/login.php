<?php
header('Content-type: text/html; charset=utf-8');
 //ini_set("display_errors", "On");
 //error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__) . '/../../include/site_config.php';
include_once dirname(__FILE__) . "/../../wh/site_state.php";
GetSiteStatus($SiteStatus,1,@$_GET["g_type"],1);

include_once dirname(__FILE__) . "/../../include/filter.php";
include_once dirname(__FILE__) . "/../../include/login_check.php";
include_once dirname(__FILE__) . "/../../common/function.php";
include_once(dirname(__FILE__)."/../../include/private_config.php");

$g_type = trim(@$_GET["g_type"]);
$g_type_arr = array("og", "ag", "mg", "ct", "bbin", "lebo");
if (!in_array($g_type, $g_type_arr)) {
	message('未知的游戏!');exit;
}

$reg = M("k_user", $db_config);
$uid = @$_SESSION['uid'];

$data = $reg->field("username,agent_id,shiwan")->where("site_id = '" . SITEID . "' and uid = '" . $uid . "'")->find();

//试玩账号屏蔽
if ($data['shiwan'] == '1') {
	message('请用正式账号登陆!');exit;
}

if (empty($data) OR empty($data["username"])) {
	message('请登录再进行游戏!');exit;
}
$loginname = $data["username"];
$gametype = "1";
$lang = "CN";
$cur = "RMB";
include_once dirname(__FILE__) . "/Games.class.php";

$games = new Games();

$url = $games->forwardGame($loginname, $g_type, $gametype, $lang, $cur);

$pos1 = strpos($url, "result");
$pos2 = strpos($url, "data");
if ($pos1 > 0 && $pos2 > 0) {
	$result = json_decode($url);
	if ($result->data->Code == 10006) {
		$data = $games->CreateAccount($loginname, $data["agent_id"], $g_type, $cur);
		if (!empty($data)) {
			$result = json_decode($data);
			if ($result->data->Code != 10011) {
				message('网络错误，请联系管理员!');exit;
			} else {
				$url = $games->forwardGame($loginname, $g_type, $gametype, $lang, $cur);
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
	echo echo_frame($url, $title);
} else if ($g_type == "mg") {
	$title = "TOTALeGAME Lobby";
	echo echo_frame($url, $title);
} else if ($g_type == "ct") {
	echo echo_js($url);
} else if ($g_type == "bbin") {
	$title = "BBIN";
	echo echo_js($url);//echo_frame($url, $title);
} else if ($g_type == "lebo") {
	$title = "Lebo";
	echo echo_js($url);
}

function echo_frame($url, $title) {
	$url = _($url);
	$title = _($title);
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

function echo_js($url) {
	$url = _($url);
	$str = <<<EOF
<script type='text/javascript'>
window.document.location="$url";
</script>
EOF;
	return $str;
}