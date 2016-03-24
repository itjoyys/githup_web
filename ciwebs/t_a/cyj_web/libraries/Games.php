<?php
//0123123
include(dirname(__FILE__).'/../../../database.php');
include(dirname(__FILE__).'/../../site_config.php');
//ob_start()
/**
 * AsiaGame SERVICE API  数据接口处理
 */
class Games {
	private $UserAgent = "Game_Go_";
	private $MD5Key = SITE_MD5_KEY;
	private $DESKey = SITE_DES_KEY;
	private $siteid = SITEID;

	private $agurl = AG_API;
	private $ogurl = OG_API;
	private $cturl = CT_API;
	private $mgurl = MG_API;
	private $bbinurl = BBIN_API;
	private $lebourl = LEBO_API;
	private $dzurl = DZ_API; //'http://pklebo.pkgamings.com';
	private $abaurl = ABA_API;
	private $pturl = PT_API;
	/*
	private $agurl = 'http://localhost:3002';
	private $ogurl = 'http://localhost:3001';
	private $cturl = 'http://localhost:3003';
	private $mgurl = 'http://localhost:3004';
	private $bbinurl = 'http://pkbbin.pkgamings.com';
	/**
	 * [forwardGame description]
	 * @param  [type]  $loginname [description]
	 * @param  [type]  $password  [description]
	 * @param  string  $dm        [description]
	 * @param  integer $lang      [description]
	 * @param  integer $gameType  [description]
	 * @param  string  $oddtype   [description]
	 * @param  integer $actype    [description]
	 * @return [type]             [description]
	 */
	function forwardGame($loginname, $gtype, $gametype,$limit="",$limitype ="", $lang = "CN", $cur = 'RMB') {
		$crypt = $this->DES1($this->DESKey);
		if ($gtype == "lebo" || $gtype == "bbin") {
			$lang = "zh-cn";
		}
		if ($gtype == "bbin") {
			$gametype = "";
		}
		if ($gtype == "og") {
			$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname . "/\\\\/cur=" . $cur . 
			"/\\\\/gametype=" . $gametype . "/\\\\/limit=" . $limit. "/\\\\/limitype=" . $limitype. "/\\\\/lang=" . $lang);
		}else{
			$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname . "/\\\\/cur=" . $cur . 
			"/\\\\/gametype=" . $gametype . "/\\\\/limit=" . $limit. "/\\\\/lang=" . $lang);
		}
		
		$Key = $this->getKey($params);
		$url = $this->_getUrl($gtype) . "/forwardgame?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);

		return $result;
	}

	//登录mg电子
	function forwarddz($gtype,$loginname, $gameid, $lang = "CN", $cur = 'RMB',$sw = 0) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname . "/\\\\/cur=" . $cur . "/\\\\/gameid=" . $gameid . "/\\\\/lang=" . $lang . "/\\\\/sw=" . $sw);
		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/forwarddz?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);

		return $result;
	}

	//返回{"result":true,"data":{"Code":10011}}为修改成功
	function MgEditAccountPwd($loginname,$pwd){
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname . "/\\\\/password=" . $pwd );
		$Key = $this->getKey($params);


		$url = $this->_getUrl('mg') . "/editaccountpwd?params=" . $params . "&key=" . $Key;


		$result = $this->web_curl($url);
		return $result;
	}

	//登录PT电子
	function forwardptdz($loginname, $gameid, $lang = "EN", $ip ,$producttype) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname . "/\\\\/ipaddress=" . $ip . "/\\\\/gameid=" . $gameid . "/\\\\/lang=" . $lang . "/\\\\/producttype=" . $producttype);
		$Key = $this->getKey($params);

		$url = $this->pturl . "/forwarddz?params=" . $params . "&key=" . $Key;
		$result = $this->web_curl($url);

		return $result;
	}

	//PT电子试玩
	function forwardptswdz($lang = "EN", $gameid,$currencycode) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid ."/\\\\/currencycode=" . $currencycode . "/\\\\/gameid=" . $gameid . "/\\\\/lang=" . $lang);
		$Key = $this->getKey($params);
		$url = $this->pturl . "/forwardptswdz?params=" . $params . "&key=" . $Key;
		$result = $this->web_curl($url);

		return $result;
	}

	//PT删除玩家会话
	function killsession($loginname) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid ."/\\\\/loginname=" . $loginname);
		$Key = $this->getKey($params);
		$url = $this->pturl . "/killsession?params=" . $params . "&key=" . $Key;
		$result = $this->web_curl($url);

		return $result;
	}

	function PtEditAccountPwd($loginname,$pwd){
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname . "/\\\\/password=" . $pwd );
		$Key = $this->getKey($params);

		$url = $this->_getUrl("pt") . "/editaccountpwd?params=" . $params . "&key=" . $Key;
		//echo $url;echo "<br>";echo $this->siteid;
		$result = $this->web_curl($url);
		return $result;
	} 

	function BbinEditAccountPwd($loginname,$pwd){
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname . "/\\\\/password=" . $pwd );
		$Key = $this->getKey($params);

		$url = $this->_getUrl("bbin") . "/editaccountpwd?params=" . $params . "&key=" . $Key;
		//echo $url;echo "<br>";echo $this->siteid;
		$result = $this->web_curl($url);
		return $result;
	} 

	function SetKUserMoney($username) {
		$crypt = $this->DES1($this->DESKey);
		if (empty($_SERVER["REQUEST_SCHEME"])) {
			if (!empty($_SERVER["HTTPS"])) {
				$_SERVER["REQUEST_SCHEME"] = 'https';
			} else {
				$_SERVER["REQUEST_SCHEME"] = 'http';
			}
		}
		$backurl = urlencode($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/index.php/webcenter/Login/rsa");
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $username . "/\\\\/backurl=" . $backurl);
		$Key = $this->getKey($params);
		$url = $this->agurl . "/updateallbalance?params=" . $params . "&key=" . $Key;
		$result = $this->web_curl($url);
		return $result;
	}
	//ag创建账号 如果账号存在 判断账号密码是否吻合 createaccount
	//$index_id
	function CreateAccount($loginname, $agent_id, $gtype, $index_id="a", $cur = 'RMB', $sw = 0) {
		if($gtype == 'pt'){
			$cur = 'CNY';
		}
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname . "/\\\\/agent_id=" . $agent_id ."/\\\\/index_id=" . $index_id . "/\\\\/cur=" . $cur . "/\\\\/sw=" . $sw);
		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/createaccount?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	/*
	读取游戏账号的可用餘額
	$actype  1真线帐户,0试玩帐号
	 */

	function GetBalance($loginname, $gtype) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/getbalance?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetAllBalance($loginname) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname);
		$Key = $this->getKey($params);
		$url = $this->ogurl . "/getallbalance?params=" . $params . "&key=" . $Key;
		$result = $this->web_curllong($url);
		return $result;
	}

	//额度转换
	function TransferCredit($loginname, $gtype, $type, $credit) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname . "/\\\\/type=" . $type . "/\\\\/credit=" . $credit);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/transfercredit?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetBetRecord($gtype, $loginname, $orderId, $videotype, $gametype, $s_time, $e_time, $agentid, $page = 1, $page_num = 15) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $loginname
			. "/\\\\/orderid=" . $orderId . "/\\\\/video_type=" . $videotype . "/\\\\/game_type=" . $gametype . "/\\\\/s_time=" . $s_time
			. "/\\\\/e_time=" . $e_time . "/\\\\/agentid=" . $agentid . "/\\\\/page=" . $page . "/\\\\/page_num=" . $page_num);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/getbetrecords?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetAvailableAmountByUser($gtype, $username, $s_time = "", $e_time = "", $dz = '') {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $username . "/\\\\/s_time=" . $s_time
			. "/\\\\/e_time=" . $e_time . "/\\\\/dz=" . $dz);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/getavailableamountbyuser?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetAvailableAmountBySiteid($gtype, $s_time, $e_time, $dz = '') {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/s_time=" . $s_time . "/\\\\/e_time=" . $e_time . "/\\\\/dz=" . $dz);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/getavailableamountbysiteid?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetAvailableAmountByAgentid($gtype, $agentid, $s_time, $e_time, $dz = '') {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/agentid=" . $agentid
			. "/\\\\/s_time=" . $s_time . "/\\\\/e_time=" . $e_time . "/\\\\/dz=" . $dz);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/getavailableamountbyagentid?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetUserAvailableAmountByUser($gtype, $username, $s_time = "", $e_time = "", $dz = '', $notbk = '') {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $username . "/\\\\/s_time=" . $s_time
			. "/\\\\/e_time=" . $e_time . "/\\\\/dz=" . $dz . "/\\\\/notbk=" . $notbk);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/getuseravailableamountbyuser?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetUserAvailableAmountBySiteid($gtype, $s_time, $e_time, $dz = '') {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/s_time=" . $s_time . "/\\\\/e_time=" . $e_time . "/\\\\/dz=" . $dz);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/getuseravailableamountbysiteid?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetUserAvailableAmountByAgentid($gtype, $agentid, $s_time, $e_time, $dz = '') {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/agentid=" . $agentid
			. "/\\\\/s_time=" . $s_time . "/\\\\/e_time=" . $e_time . "/\\\\/dz=" . $dz);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/getuseravailableamountbyagentid?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetAgentAvailableAmountByAgentid($gtype, $agentid, $s_time, $e_time, $dz = '') {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/agentid=" . $agentid
			. "/\\\\/s_time=" . $s_time . "/\\\\/e_time=" . $e_time . "/\\\\/dz=" . $dz);

		$Key = $this->getKey($params);

		$url = $this->_getUrl($gtype) . "/getagentavailableamountbyagentid?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	//会员分开
	//$gtypes "og","ag","mg","ct","bbin","lebo",'mgdz','bbdz'以|分割
	function GetUserAllAvailableAmountByAgentid($gtypes,$agentid, $s_time, $e_time) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/agentid=" . $agentid .
			 "/\\\\/types=" . $gtypes ."/\\\\/s_time=" . $s_time . "/\\\\/e_time=" . $e_time . "/\\\\/dz=" . $dz);

		$Key = $this->getKey($params);

		$url = $this->ogurl . "/getuserallavailableamountbyagentid?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function GetUserAllAvailableAmount($gtypes,$username, $s_time, $e_time, $notbk = '') {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/username=" . $username
			. "/\\\\/s_time=" . $s_time ."/\\\\/types=" . $gtypes . "/\\\\/e_time=" . $e_time . "/\\\\/notbk=" . $notbk);

		$Key = $this->getKey($params);

		$url = $this->ogurl . "/getuserallavailableamount?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}


	function GetAllUserAvailableAmountByAgentid($agentid, $s_time, $e_time, $dz = '') {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("siteid=" . $this->siteid . "/\\\\/agentid=" . $agentid
			. "/\\\\/s_time=" . $s_time . "/\\\\/e_time=" . $e_time . "/\\\\/dz=" . $dz);

		$Key = $this->getKey($params);

		$url = $this->ogurl . "/getalluseravailableamountbyagentid?params=" . $params . "&key=" . $Key;

		$result = $this->web_curl($url);
		return $result;
	}

	function agTransferCreditConfirm($loginname, $password, $billno, $credit, $type, $actype = 1, $flag = 1) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("cagent=" . $this->ag_cagent . "/\\\\/loginname=" . $loginname . "/\\\\/method=tcc/\\\\/billno=" . $billno . "/\\\\/type=" . $type . "/\\\\/credit=" . $credit . "/\\\\/actype=" . $actype . "/\\\\/flag=" . $flag . "/\\\\/password=" . $password . "/\\\\/cur=CNY/\\\\/time=" . time());
		$Key = $this->getKey($params);
		$url = $this->giProtocol . $this->giUrl . ":" . $this->giPort . "/doBusiness.do?params=" . $params . "&key=" . $Key . "&cagent=" . $this->ag_cagent;
		$result = $this->web_curl($url);
		if ($result['@attributes']['info'] == "error") {
			return 0;
		} elseif ($result['@attributes']['info'] == "0") {
			return 1;
		}
		return 0;
	}

	function agQueryOrderStatus($loginname, $password, $billno, $actype = 1) {
		$crypt = $this->DES1($this->DESKey);
		$params = $this->encrypt("cagent=" . $this->ag_cagent . "/\\\\/billno=" . $billno . "/\\\\/method=qos/\\\\/actype=" . $actype . "/\\\\/cur=CNY/\\\\/time=" . time());
		$Key = $this->getKey($params);
		$url = $this->giProtocol . $this->giUrl . ":" . $this->giPort . "/doBusiness.do?params=" . $params . "&key=" . $Key . "&cagent=" . $this->ag_cagent;
		$result = $this->web_curl($url);
		return $result['@attributes'];
	}

	function _getUrl($g_type) {
		$url = "";
		if ($g_type == "og") {
			$url = $this->ogurl;
		} else if ($g_type == "ag") {
			$url = $this->agurl;
		} else if ($g_type == "mg") {
			$url = $this->mgurl;
		} else if ($g_type == "ct") {
			$url = $this->cturl;
		} else if ($g_type == "bbin") {
			$url = $this->bbinurl;
		} else if ($g_type == "lebo") {
			$url = $this->lebourl;
		} else if ($g_type == "eg") {
			$url = $this->dzurl;
		}else if ($g_type == "aba") {
			$url = $this->abaurl;
		}else if ($g_type == "pt") {
			$url = $this->pturl;
		}

		return $url;
	}

	function getKey($params) {
		return md5($params . $this->MD5Key);
	}

	/*
	最小转入金额
	 */

	function MinInMoney() {
		return $this->MinInMoney;
	}

	/*
	服务启用，启用true
	 */

	function Runing() {
		return $this->Runing;
	}

	/*
	保留字母和数字
	 */

	function GetLoginName($loginname) {
		if ($loginname != "") {
			$str = preg_replace("/[^0-9a-zA-Z]/i", '', $loginname);
		}

		$str = strlen($str) < $this->LoginNameMinLen ? $str . "0000" : $str;
		$str = strlen($str) > $this->LoginNameMaxLen ? substr($str, 0, $this->LoginNameMaxLen) : $str;
		return $str;
	}

	/*
	检测订单号
	 */

	function CheckBillno($Billno) {
		return preg_replace("#[^A-z0-9]#", '', $Billno);
	}

	/*
	loginname检测
	 */

	function CheckLoginName($loginname) {

		if (!preg_match("/^[0-9a-zA-Z_]{" . $this->LoginNameMinLen . "," . $this->LoginNameMaxLen . "}$/", $loginname)) {
			return array("result" => false, "info" => "err unsafe cha or len", "msg" => "只能是英文字母或数字, 且长度必须是" . $this->LoginNameMinLen . "-" . $this->LoginNameMaxLen . "个字");
		} else {
			return array("result" => true, "info" => "0", "msg" => "匹配成功");
		}
	}

	/*
	password检测
	 */

	function CheckPassword($password) {

		if (!preg_match("/^[0-9a-zA-Z]{" . $this->PasswordMinLen . "," . $this->PasswordMaxLen . "}$/", $password)) {
			return array("result" => false, "info" => "err unsafe cha or len", "msg" => "只能是英文字母或数字, 且长度必须是" . $this->PasswordMinLen . "-" . $this->PasswordMaxLen . "个");
		} else {
			return array("result" => true, "info" => "0", "msg" => "匹配成功");
		}
	}

	/*
	随机数
	 */

	function getRandStr($length) {
		$str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randString = '';
		$len = strlen($str) - 1;

		for ($i = 0; $i < $length; $i++) {
			$num = mt_rand(0, $len);
			$randString .= $str[$num];
		}

		return $randString;
	}

	function agCreateBillno() {
		return $this->ag_cagent . date("YmdHis");
	}

	//返回数据
	function web_curl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->UserAgent . $this->siteid);
		//执行命令
		$data = curl_exec($ch);
		//关闭URL请求
		curl_close($ch);
		return $data;

		/*
	$Client = new HttpClient("127.0.0.1"); //目标主机的地址，我这里填上测试的地址
	$Client->setUserAgent($this->UserAgent . $this->siteid);
	$pageContents = $Client->get($url);

	return $pageContents;
	 */
	}

	//返回数据
	function web_curllong($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->UserAgent . $this->siteid);
		//执行命令
		$data = curl_exec($ch);
		//关闭URL请求
		curl_close($ch);
		return $data;
	}
	//des加密
	function DES1($key) {
		$this->key = $key;
	}

	function encrypt($input) {
		$size = mcrypt_get_block_size('des', 'ecb');
		$input = $this->pkcs5_pad($input, $size);
		$key = $this->key;
		$td = mcrypt_module_open('des', '', 'ecb', '');
		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		@mcrypt_generic_init($td, $this->DESKey, $iv);
		$data = mcrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = base64_encode($data);
		return preg_replace("/\s*/", '', $data);
	}

	function decrypt($encrypted) {
		$this->DES1($this->DESKey);
		$encrypted = preg_replace('/[\s　]/', '+', $encrypted);
		$encrypted = base64_decode($encrypted);
		$key = $this->key;
		$td = mcrypt_module_open('des', '', 'ecb', '');
		//使用MCRYPT_DES算法,cbc模式
		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
		@mcrypt_generic_init($td, $key, $iv);
		//初始处理
		$decrypted = mdecrypt_generic($td, $encrypted);
		//解密
		mcrypt_generic_deinit($td);
		//结束
		mcrypt_module_close($td);
		$y = $this->pkcs5_unpad($decrypted);
		return $y;
	}

	function pkcs5_pad($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	function pkcs5_unpad($text) {
		$pad = ord($text{strlen($text) - 1});
		if ($pad > strlen($text)) {
			return false;
		}

		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
			return false;
		}

		return substr($text, 0, -1 * $pad);
	}

}

?>