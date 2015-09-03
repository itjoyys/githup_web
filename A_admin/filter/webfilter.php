<?php
webscan_error();
//日志文件路径
define('WEBSCAN_LOG_DIR', dirname(__FILE__) . '/log/');
$webscan_switch = 1;
$webscan_post = 1;
$webscan_get = 1;
$webscan_cookie = 1;
$webscan_referre = 1;
//get拦截规则
$getfilter = "<[^>]*?=[^>]*?&#[^>]*?>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\()|<[^>]*?\\b(onerror|onmousemove|onload|onclick|onmouseover)\\b[^>]*?>|^\\+\\/v(8|9)|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
//post拦截规则
$postfilter = "<[^>]*?=[^>]*?&#[^>]*?>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\()|<[^>]*?\\b(onerror|onmousemove|onload|onclick|onmouseover)\\b[^>]*?>|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
//cookie拦截规则
$cookiefilter = "\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";

//referer获取
$webscan_referer = empty($_SERVER['HTTP_REFERER']) ? array() : array('HTTP_REFERER' => $_SERVER['HTTP_REFERER']);

//写入
//写运行日志
function runattacklog($log, $halt = 0) {
	$file = "attacklog";
	$mtime = explode(' ', microtime());
	$yearmonth = sgmdate('Ym', $mtime[1]);
	$logdir = WEBSCAN_LOG_DIR;
	if (!is_dir($logdir)) {
		mkdir($logdir, 0777);
	}

	$logfile = $logdir . $yearmonth . '_' . $file . '.php';
	if (@filesize($logfile) > 2048000) {
		$dir = opendir($logdir);
		$length = strlen($file);
		$maxid = $id = 0;
		while ($entry = readdir($dir)) {
			if (strexists($entry, $yearmonth . '_' . $file)) {
				$id = intval(substr($entry, $length + 8, -4));
				$id > $maxid && $maxid = $id;
			}
		}
		closedir($dir);
		$logfilebak = $logdir . $yearmonth . '_' . $file . '_' . ($maxid + 1) . '.php';
		@rename($logfile, $logfilebak);
	}
	$log = trim($log) . "\n";
	if ($fp = @fopen($logfile, 'a')) {
		@flock($fp, 2);
		fwrite($fp, "<?PHP exit;?>\t" . str_replace(array('<?', '?>', "\r", "\n"), '', $log) . "\n");
		fclose($fp);
	}
	if ($halt) {
		exit();
	}

}

/**
 *   关闭用户错误提示
 */
function webscan_error() {
	if (ini_get('display_errors')) {
		ini_set('display_errors', '0');
	}
}

/**
 *  数据统计回传
 */
function webscan_slog($logs) {
	$data = json_encode($logs);
	//WEBSCAN_LOG_DIR
	runattacklog($data, 0);
}

/**
 *  参数拆分
 */
function webscan_arr_foreach($arr) {
	static $str;
	if (!is_array($arr)) {
		return $arr;
	}
	foreach ($arr as $key => $val) {

		if (is_array($val)) {

			webscan_arr_foreach($val);
		} else {

			$str[] = $val;
		}
	}
	return implode($str);
}

/**
 *  防护提示页
 */
function webscan_pape() {
	header('Location: /');
}

/**
 *  攻击检查拦截
 */
function webscan_StopAttack($StrFiltKey, $StrFiltValue, $ArrFiltReq, $method) {
	$StrFiltValue = webscan_arr_foreach($StrFiltValue);
	if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltValue) == 1) {
		webscan_slog(array('ip' => $_SERVER["REMOTE_ADDR"], 'time' => strftime("%Y-%m-%d %H:%M:%S"), 'page' => $_SERVER["PHP_SELF"], 'method' => $method, 'rkey' => $StrFiltKey, 'rdata' => $StrFiltValue, 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'request_url' => $_SERVER["REQUEST_URI"]));
		exit(webscan_pape());
	}
	if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltKey) == 1) {
		webscan_slog(array('ip' => $_SERVER["REMOTE_ADDR"], 'time' => strftime("%Y-%m-%d %H:%M:%S"), 'page' => $_SERVER["PHP_SELF"], 'method' => $method, 'rkey' => $StrFiltKey, 'rdata' => $StrFiltKey, 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'request_url' => $_SERVER["REQUEST_URI"]));
		exit(webscan_pape());
	}
}

/**
 *  拦截目录白名单
 */
function webscan_white($webscan_white_name, $webscan_white_url = array()) {
	$url_path = $_SERVER['PHP_SELF'];
	$url_var = $_SERVER['QUERY_STRING'];
	if (preg_match("/" . $webscan_white_name . "/is", $url_path) == 1) {
		return false;
	}
	foreach ($webscan_white_url as $key => $value) {
		if (!empty($url_var) && !empty($value)) {
			if (stristr($url_path, $key) && stristr($url_var, $value)) {
				return false;
			}
		} elseif (empty($url_var) && empty($value)) {
			if (stristr($url_path, $key)) {
				return false;
			}
		}
	}

	return true;
}

/**
 * 开始检查
 */
if ($webscan_switch) {
	if ($webscan_get) {
		foreach ($_GET as $key => $value) {
			webscan_StopAttack($key, $value, $getfilter, "GET");
		}
	}
	if ($webscan_post) {
		foreach ($_POST as $key => $value) {
			webscan_StopAttack($key, $value, $postfilter, "POST");
		}
	}
	if ($webscan_cookie) {
		foreach ($_COOKIE as $key => $value) {
			webscan_StopAttack($key, $value, $cookiefilter, "COOKIE");
		}
	}
	if ($webscan_referre) {
		foreach ($webscan_referer as $key => $value) {
			webscan_StopAttack($key, $value, $postfilter, "REFERRER");
		}
	}
}
?>