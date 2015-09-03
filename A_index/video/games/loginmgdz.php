<?php
// ini_set("display_errors",'1');
// error_reporting(E_ALL);
include_once dirname(__FILE__) . "/../../include/config.php";
include_once dirname(__FILE__) . "/../../common/function.php";


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
$gameid = trim($_GET["gameid"]);
if (empty($gameid)) {
	message('请选择一个电子游戏!');exit;
}
$lang = "CN";
$cur = "RMB";
$g_type = "mg";
include_once dirname(__FILE__) . "/Games.class.php";
$games = new Games();
$url = $games->forwarddz($loginname, $gameid, $lang, $cur); //forwardGame($loginname, $g_type, $gametype, $lang, $cur);
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

//echo $url;
$title = "TOTALeGAME " . $gameid;
echo echo_frame($url, $title);

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
